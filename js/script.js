$(document).ready(function(){

	$('.submitbtn').click(function(ev){
		
		if ($(this).hasClass('load')) {
			return;
		}
		ev.preventDefault();
		
		var parent = $(this).parent();
		
		var e = {};
		e.name = $(parent).find('#name');
		e.email = $(parent).find('#email');
		e.country = $(parent).find('#country');
		e.resume = $(parent).find('#resume');
		e.captcha = $(parent).find('#captcha');
		
		var o = {};
		o.name = $(e.name).val().trim();
		o.country = $(e.country).val().trim();
		o.email = $(e.email).val().trim();
		o.resume = $(e.resume).val().trim();
		o.captcha = $(e.captcha).val().trim();
		
		var error = false;
		var mandatory = ['name','email', 'country','captcha'];
		for (var i = 0; i < mandatory.length; i++) {
			if (o[mandatory[i]] === '') {
				$(e[mandatory[i]]).addClass('error');
				error = true;
			}
		}
		
		if (o.resume !== undefined && o.resume === '') {
			delete o.resume;
		}
		
		if (error) return;
		
		var self = this;
		function onSuccess(data) {
			$(self).removeClass('load');
			$('.captchawrap').click();
			if (data.error) {
				$('#signup .err').show().html(data.error);
				return;
			}
			$('#signup .suc').show();
			$(e.name).val('');
			$(e.country).val('');
			$(e.email).val('');
			$(e.resume).val('');
			$(e.captcha).val('');
		}
		
		$(this).addClass('load');
		
		$.ajax({
			type: "POST",
			url: 'process.php',
			data: o,
			success: onSuccess,
			dataType: 'json'
		});
	});
});
