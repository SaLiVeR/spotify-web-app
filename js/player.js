$(function() {
    reload();
});

function reload() {
    var apiURL = "mpd-api.php";
    
    $.ajax({
        "type": "GET",
        "url": apiURL,
        "data": {'action': 'playerinfo'},
        "dataType": "json",
        "success": function(data) {
            if(typeof data === 'object') {
                console.log(data);
                addInfo(data);
            }
       } 
    });
}

var secondTimer;
function addInfo(info) {
    
    $('#artist').html(info.artist);
    $('#song').html(info.track);
    $('#current-time').html(timestamp(info.position));
    $('#current-time-seconds').html(info.position);
    $('#end-time').html(timestamp(info.length));
    $('#end-time-seconds').html(info.length);
    $('#votes').html(info.votes);
    
    
    reloadTimer = window.setTimeout("reload();", 1000*10);
    window.clearTimeout(secondTimer);
    secondTimer = window.setTimeout("second();", 1000);
}

function second() {
    var time = parseInt($('#current-time-seconds').html());
    time += 1;
    
    $('#current-time').html(timestamp(time));
    $('#current-time-seconds').html(time);
    
    var newLeft = parseInt($('#current-position').css('left')) + 350/$('#end-time-seconds').html();
    $('#current-position').css('left', newLeft + 'px');
    
    if(time !== parseInt($('#end-time-seconds').html())) {
        secondTimer = window.setTimeout("second();", 1000);
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