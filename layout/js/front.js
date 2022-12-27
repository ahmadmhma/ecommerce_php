$(function () {
	'use strict';
	$('.sign h1 span').click(function(){
		$(this).addClass('selected').siblings().removeClass('selected');
		$('.sign form').hide();
		$('.' + $(this).data('class')).show();
	});


	$('[placeholder]').focus(function(){
		$(this).attr('data',$(this).attr('placeholder'));
		$(this).attr('placeholder','');
	}).blur(function(){
		$(this).attr('placeholder',$(this).attr('data'));
	});


	$('input').each(function(){
		if ($(this).attr('required')==='required') {
			$(this).after('<span class="asterisk">*</span>');
		}
	});


	$('.eye-form').hover(function(){
		$('.password').attr('type' , 'text');
	}, function(){
		$('.password').attr('type' , 'password');
	});


	$('.confirm').click(function(){
		return confirm('are you sure');
	});





	$(".read").keyup(function(){
		$($(this).data('class')).text($(this).val());
	});

});