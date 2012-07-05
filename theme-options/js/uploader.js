jQuery(document).ready(function() {

	jQuery('.st_upload_button').click(function() {
/* 		 formfield = jQuery('#st_upload').attr('name'); */
		 targetfield = jQuery(this).prev('.upload-url');
		 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		 return false;
	});

	window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');
		 jQuery(targetfield).val(imgurl);
		 tb_remove();
	}

});
