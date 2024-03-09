/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/
$(function() {
	$('#up-to-top').click(function() {
		$('html, body').animate({scrollTop: 0},500);
		return false;
	})

	$(document).on('click','.callback-product-btn',function(){
		$('.phone_block .order_wrap_btn .callback_btn').click();
	});
    $(document).on('click','.quick-order-link',function(){
        $('.one_click').click();
    });
})

	setTimeout(function(){
		console.log('setCSS');
		$('.pfWidget').css('left','0px').remove();
const sheet = new CSSStyleSheet();
sheet.replaceSync('.pfWidget {display:none !important; }');
sheet.insertRule('.pfWidget {display:none !important; }');
document.adoptedStyleSheets = [sheet];
},8000);