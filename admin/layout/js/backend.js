$(function () {
	'use strict';
	$('.pl-mi').click(function(){
		$(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);
		if ($(this).hasClass('selected')) {
			$(this).html('<i class="fa fa-minus ">');
		}else
		{$(this).html('<i class="fa fa-plus ">');}
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


	
	$('.cat h3').click(function(){
		$(this).next('.mini').fadeToggle(200);
	});

	$('.cli').hover(function(){
		$(this).find('.showde').fadeIn(400);
	}, function(){
		$(this).find('.showde').fadeOut(400);
	});

});