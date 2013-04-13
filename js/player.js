$(function() {
    reload(true);
});

function reload(initial) {
    var apiURL = "mpd-api.php";
    
    $.ajax({
        "type": "GET",
        "url": apiURL,
        "data": {'action': 'playerinfo'},
        "dataType": "json",
        "success": function(data) {
            if(typeof data === 'object') {
                console.log(data);
                addInfo(data, initial);
            }
       } 
    });
}

var secondTimer;
var barWidth = 350;
function addInfo(info, initial) {
    
    if(info.position > info.length) {
        info.position = info.length;
    } else {
        window.clearTimeout(secondTimer);
        secondTimer = window.setTimeout("second();", 1000);
    }
    
    $('#artist').html(info.artist);
    $('#song').html(info.track);
    $('#current-time').html(timestamp(info.position));
    $('#current-time-seconds').html(info.position);
    $('#end-time').html(timestamp(info.length));
    $('#end-time-seconds').html(info.length);
    $('#votes').html(info.votes);
    
    if(initial) {
        movePointer(info.position);
    }   
    
    reloadTimer = window.setTimeout("reload();", 1000*10);
    
}

function second() {
    var time = parseInt($('#current-time-seconds').html());
    time += 1;
    
    $('#current-time').html(timestamp(time));
    $('#current-time-seconds').html(time);
    
    movePointer(1);
    
    if(time !== parseInt($('#end-time-seconds').html())) {
        secondTimer = window.setTimeout("second();", 1000);
    }
}

function movePointer(seconds) {
    var newLeft = parseInt($('#current-position').css('left')) + barWidth/$('#end-time-seconds').html() * seconds;
    $('#current-position').css('left', newLeft + 'px');
}

function timestamp(time) {
    var hours = 0;
    var minutes = 0;
    
    while(time > 60*60) {
        hours += 1;
        time -= 60*60;
    }
    while(time > 60) {
        minutes += 1;
        time -= 60;
    }
    var ret = '';
    
    if(hours > 0) {
        ret = hours + ':' + showTimeNumber(minutes);
    } else {
        ret = minutes;
    }
    ret += ':' + showTimeNumber(time);
    return ret;
}

function showTimeNumber(digit) {
    if(typeof digit == 'undefined' || digit == '' || !isInt(digit)) return '00';
    if(digit < 10) return '0' + digit;
    return digit;
}

function isInt(n) {
    return n % 1 === 0;
}