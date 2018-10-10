$(document).ready(function(){

	var fields = window.FIELDS;

	var $input_fields = $('.input-field input, .input-field textarea');

    $input_fields.on('focus', function(e){
        var $field = $(this).parent();
        $field.addClass('focus');
    }).on('blur', function(e){
        var $this = $(this);
        var $field = $this.parent();
        $field.removeClass('focus');

        var val = $this.val().trim();

        if (val === '') {
            $field.removeClass('active');
        } else {
            $field.addClass('active');
        }
    }).on('change', function(e){
        var $this = $(this);
        var $field = $this.parent();
        var val = $this.val().trim();

        if (val === '') {
            $field.removeClass('active');
        } else {
            $field.addClass('active');
        }
    });

    $input_fields.each(function(){
        $(this).trigger('change');
    });

    $('.input-field.file.file-image input[type="file"]').on('change', function(e) {
        if (e.target.files.length) {
            var file = e.target.files[0].name;
            var $field = $(this).closest('.input-field');
            $field.find('.file-mock').val(file);
            $field.addClass('active');
			var $form = $field.closest('form');
			$form.addClass('loading');
			$form.find('.image-display').slideDown();

		    $.ajax({
		      url: $form.attr('action'),
		      type: $form.attr('method').toUpperCase(),
		      data: new FormData($form[0]),
			  contentType: false,
		      cache: false,
		      processData: false,
			  dataType: 'json',
		      success: function (msg) {
				  $form.removeClass('loading');

				  if (msg.error) {
					  $form.find('.err').html(msg.error);
				  } else {
					  $form.find('.hidden').val(msg.image);
					  $form.find('.image-box').css('background-image', 'url(uploads/'+msg.image+')');
				  }
			  }
		    });
        }
    }).trigger('change');

    $('.lens-selector .lens').on('click', function(){
        var $this = $(this);
        var $ls = $this.closest('.lens-selector');
        var $field = $this.closest('.form').find('#lens');
        var value = $this.data('value');
        var already_active = $this.hasClass('active');

        $ls.find('.active').removeClass('active').find('i').removeClass('red');
        $ls.find('.inactive').removeClass('inactive');

        if (!already_active) {
            $this.addClass('active').removeClass('inactive').find('i').addClass('red');
            $ls.find('.lens').not($this).addClass('inactive');
            $field.val(value);
        } else {
            $field.val('');
        }
    });

    $('.checkbox').on('click', function(){
        var $this = $(this);
        $this.toggleClass('active');
        var $input = $this.find('input');
        $input.val(0);

        if ($this.hasClass('active')) {
            $input.val(1);
			$('.err').hide();
        }
    });

    function expandableBlock() {
        var $this = $(this).closest('.expandable-block');
        $this.toggleClass('expanded');
        $this.find('.to-expand').slideToggle();
    };

    $('.expandable-block .expand-preview, .expandable-block .icon-right').on('click', expandableBlock);

	$('input[type="submit"]').click(function(ev){
		ev.preventDefault();
		if ($(this).hasClass('load')) {
			return;
		}

		if (!$('.checkbox').hasClass('active')) {
			$('#gen-err').show().html('You have to agree to Terms & Conditions in order to submit your photo.');
			return;
		}

		var $parent = $(this).closest('.form');

		var e = {};
		var o = {};
		for (var i = 0; i < fields.length; i++) {
			e[fields[i].name] = $parent.find('#'+fields[i].name);

			if (fields[i].type === 'image') {
				o[fields[i].name] = e[fields[i].name].closest('form').find('.hidden').val().trim();
			} else {
				o[fields[i].name] = e[fields[i].name].val().trim();
			}
		}

		var self = this;
		function onSuccess(data) {
			$(self).removeClass('load');
			$('body').removeClass('form-loading');
			if (data.error) {
				if (data.hasOwnProperty('type') && data.type === 'validation') {
					for (var i = 0; i < data.error.length; i++) {
						var err = data.error[i];
						$('.err-'+err.field).show().html(err.message);
					}
				} else {
					$('#gen-err').show().html(data.error);
				}
				return;
			}

			$('#suc').show();

			for (var i = 0; i < fields.length; i++) {
				e[fields[i].name].val('');

				if (fields[i].type === 'image') {
					var $form = e[fields[i].name].closest('form');
					$form.find('.hidden').val('');
					$form.find('.image-display').slideUp();
					$form.find('.file-mock').val('');
				}
			}
			$('.lens-selector').find('.active').removeClass('active');
			$('.lens-selector').find('.red').removeClass('red');
			$('.checkbox').removeClass('active');
			$('.image-box').css('background-image', 'none');

			$('.err').hide().html('');
		}

		$(this).addClass('load');
		$('body').addClass('form-loading');
		$('#suc').hide();

		$.ajax({
			type: "POST",
			url: 'process.php',
			data: o,
			success: onSuccess,
			dataType: 'json'
		});
	});
});
