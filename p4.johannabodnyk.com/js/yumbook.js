$(document).ready(function() {

	// Image uploading controls
	
	// Add a new file upload field when 'Add another photo' button is clicked
	$('#add-another-photo').click(function() {
		$('#image-inputs').append('<br><input type="file" id="image-input-1"><input type="button" class="image-upload"id="1" value="Upload">');
	});
	
	$('.image-upload').click(function() {
		console.log('You clicked me');
		console.log($("#image-input-1").attr('value'));
	});
	
	$('#name').keyup(function() {
		console.log($(this).val());
	});
	
	console.log($("#image-input-1").attr('value'));
	
});