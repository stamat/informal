$(document).ready(function(){
	$('select').select2();


	var hero_image = null;
	var images = [];

	Dropzone.autoDiscover = false;
	var dz = new Dropzone('.dropzone-hero', {
		acceptedFiles: 'image/*',
		maxFiles: 1,
		maxFilesize: 2
	});

	var dza = new Dropzone('.dropzone-additional', {
		acceptedFiles: 'image/*',
		maxFiles: 5,
		maxFilesize: 2
	});

	dz.on("success", function(file, fname) {
    	hero_image = fname;
  	});

	dza.on("success", function(file, fname) {
    	images.push(fname);
  	});

	$('.submitbtn').click(function(ev){

		if ($(this).hasClass('load')) {
			return;
		}
		ev.preventDefault();

		var parent = $(this).parent();

		var e = {};
		e.class_name = $(parent).find('#class_name');
		e.class_description = $(parent).find('#class_description');
		e.class_location = $(parent).find('#class_location');

		var o = {};
		o.class_name = $(e.class_name).val().trim();
		o.class_description = $(e.class_description).val().trim();
		o.class_location = $(e.class_location).val().trim();

		if (hero_image) {
			o.hero_image = hero_image;
		}

		if (images.length) {
			for (var i = 0; i < images.length; i++) {
				var id = i+1;
				o['image'+id] = images[i];
			}
		}

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
