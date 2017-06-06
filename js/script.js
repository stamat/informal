$(document).ready(function(){

	$('.submitbtn').click(function(ev){

		if ($(this).hasClass('load')) {
			return;
		}
		ev.preventDefault();

		var parent = $(this).parent();

		var e = {};
		e.class_name = $(parent).find('#class_name');
		e.class_description = $(parent).find('#class_description');

		var o = {};
		o.class_name = $(e.class_name).val().trim();
		o.class_description = $(e.class_description).val().trim();



		var self = this;
		function onSuccess(data) {
			$(self).removeClass('load');
			if (data.error) {
				$('#signup .err').show().html(data.error);
				return;
			}
			$('#signup .suc').show();
			$(e.class_name).val('');
			$(e.class_description).val('');
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
