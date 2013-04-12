$.function() {
    reload();
}

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
    $('#end-time').html(info.length);
    
    $('#votes').html(info.votes);
    
    
    window.setTimeout("reload();", 1000*10);
    window.setTimeout("second();", 1000);
}