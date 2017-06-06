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

	var fields = ['class_name', 'class_description','class_type','demo', 'class_location','address1', 'address2', 'city', 'state', 'zip', 'price', 'start_time',
	'end_time','facebook','instagram', 'twitter', 'seats', 'bio', 'paypal', 'phone'];

	$('.submitbtn').click(function(ev){
		ev.preventDefault();
		if ($(this).hasClass('load')) {
			return;
		}

		$('#signup .suc').hide();

		var parent = $(this).parent();

		var e = {};
		var o = {};
		for (var i = 0; i < fields.length; i++) {
			e[fields[i]] = $(parent).find('#'+fields[i]);
			o[fields[i]] = e[fields[i]].val().trim();
		}

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
				if (data.hasOwnProperty('type') && data.type === 'validation') {
					for (var i = 0; i < data.error.length; i++) {
						var err = data.error[i];
						$('#'+err.field).parent().find('.err').show().html(err.message);
					}
				} else {
					$('#signup .gen-err').show().html(data.error);
				}
				return;
			}
			$('#signup .suc').show();
			for (var i = 0; i < fields.length; i++) {
				e[fields[i]].val('');
			}
			$('.err').hide().html('');
			dz.removeAllFiles( true );
			dza.removeAllFiles( true );
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
