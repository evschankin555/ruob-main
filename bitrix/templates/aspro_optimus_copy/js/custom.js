/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/
$(function() {
	$('#up-to-top').click(function() {
		$('html, body').animate({scrollTop: 0},500);
		return false;
	})
})