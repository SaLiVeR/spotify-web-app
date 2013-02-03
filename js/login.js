$(document).ready(function() {
	$(".passwordstrengthcheck").keyup(function(){
		var valid = 0,tovalidiate = $(this).val(),numcheck = /[0-9]/g; 
		var result = numcheck.test(tovalidiate);
		if(result == true) {valid += 1;}
		if(tovalidiate.length >= 5){valid += 1;}
		$(".validinnerbar").css("width",valid*0.5*(parseInt($(".validbar").css("width"))));
	});
});