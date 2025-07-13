jQuery('body').delegate('.dt-upload-media-item-button', 'click', function(e){
	

	var file_frame = null;
	var item_clicked = jQuery(this);
	var multiple = false;
	var button_text = "Insert Image";

	if(item_clicked.hasClass('multiple')) {
		multiple = true;
		button_text = "Insert Image(s)";
	}

	file_frame = wp.media.frames.file_frame = wp.media({
		multiple: multiple,
		title : "Upload / Select Media",
		button :{
			text : button_text
		}
	});

	// When an image is selected, run a callback.
	file_frame.on( 'select', function() {

		var attachments = file_frame.state().get('selection').toJSON();

		if(item_clicked.hasClass('multiple')) {

			var items = '';
			jQuery.each( attachments, function(key, value) {

				var id = value.id;
				var title = value.title;
				var image_url = '';

				if(jQuery.type(value.sizes.thumbnail) != 'undefined') {
					image_url =  value.sizes.thumbnail.url;
				} else {
					image_url =  value.sizes.full.url;
				}

				items += '<li>'+
							'<img src="'+image_url+'"/>'+
							'<input name="dt_media_attachment_ids[]" type="hidden" class="uploadfieldid hidden" readonly value="'+id+'"/>'+
							'<input name="dt_media_attachment_titles[]" type="text" class="media-attachment-titles" value="'+title+'"/>'+
							'<span class="dt-remove-media-item"><span class="fas fa-times"></span></span>'+
							'<span class="dt-featured-media-item"><span class="far fa-user"></span></span>'+
						'</li>';

			});

			if(item_clicked.parents('.dt-upload-media-items-container').find('.dt-upload-media-items').length) {
				item_clicked.parents('.dt-upload-media-items-container').find('.dt-upload-media-items').append(items);
			} else {
				var data = '<div class="dt-upload-media-items-holder">';
						data += '<ul class="dt-upload-media-items">';
						data += items;
						data += '</ul>';
					data += '</div>';
				item_clicked.parents('.dt-upload-media-items-container').prepend(data);
			}


		} else {

			var id = attachments[0].id;
			var url = attachments[0].url;

			item_clicked.parents('.dt-upload-media-items-container').find('.uploadfieldurl').val(url);
			item_clicked.parents('.dt-upload-media-items-container').find('.uploadfieldid').val(id);

			if(item_clicked.hasClass('show-preview')) {
				item_clicked.parents('.dt-upload-media-items-container').find('.dt-image-preview-tooltip img').attr('src', url);
			} else if(item_clicked.hasClass('show-image-holder')) {
				item_clicked.parents('.dt-upload-media-items-container').find('.dt-image-holder img').attr('src', url);
			}

		}

	});

	// Finally, open the modal
	file_frame.open();

});

jQuery('body').delegate('.dt-upload-media-item-reset', 'click', function(e) {

	var item_clicked = jQuery(this);

	if(item_clicked.parents('.dt-upload-media-items-container').find('.dt-upload-media-item-button').hasClass('multiple')) {

		item_clicked.parents('.dt-upload-media-items-container').find('.dt-upload-media-items').html('');

	} else {

		item_clicked.parents('.dt-upload-media-items-container').find('.uploadfieldurl').val('');
		item_clicked.parents('.dt-upload-media-items-container').find('.uploadfieldid').val('');

		if(item_clicked.parents('.dt-upload-media-items-container').find('.dt-upload-media-item-button').hasClass('show-preview')) {
			var $noimage = item_clicked.parents('.dt-upload-media-items-container').find('.dt-image-preview-tooltip img').attr('data-default');
			item_clicked.parents('.dt-upload-media-items-container').find('.dt-image-preview-tooltip img').attr('src', $noimage);
		} else if(item_clicked.parents('.dt-upload-media-items-container').find('.dt-upload-media-item-button').hasClass('show-image-holder')) {
			var $noimage = item_clicked.parents('.dt-upload-media-items-container').find('.dt-image-holder img').attr('data-default');
			item_clicked.parents('.dt-upload-media-items-container').find('.dt-image-holder img').attr('src', $noimage);
		}

	}

	e.preventDefault();

});