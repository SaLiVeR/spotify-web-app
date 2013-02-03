//jQuery uses colons for other functions. We need to remove them from our IDs
function sanitizeID(ID) {
    return ID.replace('/\:/g','---');
}
function unsanitizeID(ID) {
    return ID.replace(new RegExp('---', 'g'), ':');
}

function formatTime(time) {
    var hours = 0
    var minutes = 0
    var seconds = 0;
    while(time > 60*60) {
        hours++;
        time -= 60*60;
    }
    while(time > 60) {
        minutes++;
        time -= 60;
    }
    time = Math.round(time);
    
    if(time < 10) time = '0' + time;
    
    if(hours > 0) {
        if(minutes < 10) minutes = '0' + minutes;
        return hours + ':' + minutes + ':' + time;
    } else {
        return minutes + ':' + time;
    }
}

function changeNav(page) {
    if(document.body.id == page) return false;
}