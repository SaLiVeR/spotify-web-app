$(function() {
    reload();
});

function reload() {
    var apiURL = "mdp-api.php";
    
    $.ajax({
        "type": "GET",
        "url": apiURL,
        "data": {'action': 'radioinfo'},
        "dataType": "json",
        "success": function(data) {
            if(typeof data === 'object') {
                console.log(data);
                addInfo(data);
            }
       } 
    });
}

function addInfo(info) {
    
    $('#artist').html(info.artist);
    $('#song').html(info.title);
    $('#current-time').html(info.position);
    $('#current-time-seconds').html(timestamp(info.position));
    $('#end-time').html(info.length);
    $('#votes').html(info.votes);
    
    
    window.setTimeout("reload();", 1000*10);
    window.setTimeout("second();", 1000);
}

function second() {
    var time = parseInt($('#current-time').html());
    time += 1;
    
    $('#current-time').html(time);
    $('#current-time-seconds').html(timestamp(time));
    if(time !== parseInt($('#end-time-seconds').html())) {
        window.setTimeout("second();", 1000);
    }
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
        ret = hours + ':'
    }
    ret += howTimeNumber(minutes) + ':' + howTimeNumber(time);
}

function showTimeNumber(digit) {
    if(digit < 10) return '0' + digit;
}