function updateSearch() {
    var search = $('#searchinput').val();
    if(typeof search == 'undefined' || search.length === 0) {
        hideSearch();
        return;
    }
    
    var libraries = []
    $('#library-buttons input').each(function(index) {
        if($(this).is(":checked")) libraries.push($(this).attr('id').substring($(this).attr('id').indexOf('-') + 1));
    });
    
    //var spotifyAPI = "http://ws.spotify.com/search/1/track.json";
    var API = "http://192.168.1.109/spotify-web-app/mpd-api.php";
    $.ajax({
        "type": "GET",
        "url": API,
        "data": {'action': 'search', 'search': search, 'libraries': libraries.join('|')},
        "dataType": "json",
        "success": function(data) {
            if(typeof data === 'object') {
                console.log(data);
                //showTracks(filterGB(data));
                showTracks(filterSongs(data));
            }
       }
    });
}

$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('body').fileupload({
        url: 'upload.php',
        dropZone: $('#dropzone')
    });

});

$(document).bind('dragover', function (e) {
    var dropZone = $('#dropcanvas'),
        timeout = window.dropZoneTimeout;
    if (!timeout) {
        dropZone.addClass('in');
    } else {
        clearTimeout(timeout);
    }
    if (e.target === dropZone[0]) {
        dropZone.addClass('hover');
    } else {
        dropZone.removeClass('hover');
    }
    window.dropZoneTimeout = setTimeout(function () {
        window.dropZoneTimeout = null;
        dropZone.removeClass('in hover');
    }, 100);
});

$('#fileupload').bind('fileuploadprogress', function (e, data) {
    // Log the current bitrate for this upload:
    console.log(data);
});

$(document).bind('drop dragover', function (e) {
    e.preventDefault();
});

//Controller function managing all movements. The only one that needs to be called
function moveRow(id, distance) {
    var o = $('#row-' + id);
    o.css('z-index', 1);
    lift(o, distance);
}
function lift(o, distance) {
    o.animate({
        top: '-2px',
        left: '2px'
    }, {
        duration: 200,
        queue: false,
        complete: function() {
            moveUp(o, distance);
            moveRows(o, distance);
        }
    });
}
function moveRows(o, total) {
    if(total < 0) {
        var rows = o.nextAll().splice(0,-total)
        var direction = -1;
    } else {
        var rows = o.prevAll().splice(0,total);
        var direction = 1;
    }
    $(rows).each(function(e) {
        $(this).animate({
            top: direction*25 + 'px'
        }, {
            duration: 300,
            queue: false
        });
    });
}

function moveUp(o, distance) {
    o.animate({
        top: -25*distance-2
    }, {
        duration: 800,
        queue: false,
        complete: function() {
            drop(o);
        }
    });
}

function drop(o) {
    o.animate({
        top: (parseInt(o.css('top')) + 2) + 'px',
        left: (parseInt(o.css('left')) - 2) + 'px'
    }, {
        duration: 200,
        queue: false,
        complete: function() {
            o.css('z-index', 0);
            reloadTable();
        }
    });
}

//There are a whole bunch of tracks that aren't available in the UK, and Spotify is a scumbag and won't let me filter the API
function filterGB(data) {
    for(d in data.tracks) {
        if(data.tracks[d].album.availability.territories.indexOf('GB') > 0) delete data.tracks[d];
    }
    console.log(data);
    return data;
}

function filterSongs(data) {
    for(d in data) {
        if(data[d].Time == "0") delete data[d];
    }
    return data;
}

function showTracks(data) {
    var html = "<table id='search-results-table'><thead><tr><th>Track Name</th><th>Artist</th><th></th><th>Album</th></tr></thead><tbody>";
    var limit = (data.length > 20) ? 20 : data.length;
    var row = 'even';
    var current = 0;
    for(t in data) {
        row = (row === 'even') ? 'odd' : 'even';
        html += "<tr id='" + sanitizeID(data[t].file) + "' class='row" + row + "'><td>" + data[t].Title + "</td><td>" + data[t].Artist + "</td><td>";
        html += formatTime(data[t].Time) + "</td><td>" + data[t].Album + "</td></tr>";
        if(current++ > limit) break;
    }
    html += "</tbody></table>";
    $(html);

    $('#search-results').html(html);
    showSearch();
    $('#search-results-table').dataTable({
        "bFilter": true
    });
    addTableEvents();
}

function addTableEvents() {
    $('#search-results-table tbody tr').on('dblclick', function() {
        addSong($(this).attr('id'));
        $(this).off('dblclick');
    }).on('click', function() {
        addClickButton($(this).attr('id'));
        $(this).addClass("selected").siblings().removeClass("selected");
    }).on('mouseover', function() {
        addClickButton($(this).attr('id'));
    });
    
}
function addClickButton(id) {
    //For some reason, jQuery keeps messing up here, so resort back to normal JS
    $('.clickToAdd').remove();
    document.getElementById(id).firstChild.innerHTML += "<input type='button' class='clickToAdd' onclick='alert(addSong(\'" + id + "\'););'/>";
}

function hideSearch() {
    $('html').off('click');
    $('#search').slideUp();
}
               
function showSearch() {       
    $('html').on('click',function(event) {
        if(!$(event.target).closest('#search').length) {
            hideSearch();
        }
    });
    
    $('#search').slideDown();
}

function addSong(songid) {
    console.log(songid);
    $.ajax({
        type: "GET",
        url: "ajax.php",
        data: {
            action: 'add',
            track: songid
        },
        success: function(data){
            alert('track added');
        }
    })
}

// 1 = up, 0 = down
function vote(direction, id, currentpos) {
    if(direction == 1) {
        $('#button-up-' + id).removeClass('voteup');
        $('#button-up-' + id).removeClass('voteup-red');
        $('#button-up-' + id).addClass('voteup-green');
        $('#button-down-' + id).removeClass('votedown-green');
        $('#button-down-' + id).removeClass('votedown-red');
        $('#button-down-' + id).addClass('votedown');
    } else if(direction == 0) {
        $('#button-down-' + id).removeClass('votedown');
        $('#button-down-' + id).removeClass('votedown-green');
        $('#button-down-' + id).addClass('votedown-red');
        $('#button-up-' + id).removeClass('voteup');
        $('#button-up-' + id).removeClass('voteup-green');
        $('#button-up-' + id).addClass('voteup');
    } else {
        return;
    }
    
    $.ajax({
        type: "GET",
        url: "ajax.php",
        data: {
            action: 'vote',
            track: unsanitizeID(id),
            direction: direction
        },
        success: function(votedata) {
            votedata = votedata.split('!!');
            $('#score-' + id).html(votedata[0]);
            if(currentpos - votedata[1] !== 0) moveRow(id, currentpos - votedata[1]);
        }
    })
}

function reloadTable() {
    $.ajax({
        type: "GET",
        url: "ajax.php",
        data: {
            action: 'table'
        },
        success: function(table) {
            $('#table-container').html(table);
        }
    })
}
