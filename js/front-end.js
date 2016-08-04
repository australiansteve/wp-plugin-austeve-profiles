jQuery( document ).ready(function(){
	
	//On the edit profile page, move the preview image to just after the actual image field
	var previewDiv = jQuery('#profile-picture-preview');
	var picDiv = jQuery("div[data-name='profile-picture']");
	picDiv.after(previewDiv);

});
