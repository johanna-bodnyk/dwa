$(document).ready(function() {

	//
	// Want/Unwant button on Dish and Stream pages
	//
	
	$('a#want-button').live('click', function() {
	
		$.ajax({
			url: $(this).attr('href'),
			success: function(response) {
				console.log($(this));
				$('p#wants').html(response);
				
				
			}
		});
		
		return false;
	
	});
	
	//
	// Ajax submission for Comment forms
	//
	
	var comment_form_options = {
		clearForm: true,
		success: function(response) {
			console.log(response);
			$('#dish-comments').append(response);
		}	
	}
	
	$('#add-comment').ajaxForm(comment_form_options);
	
	//
	// Add Dish and Add Meal image uploading controls:
	//
	
	var image_input_id = 2;

	// Add a new file upload field (iframe) when 'Add another photo' button is clicked
	$('#add-another-photo').click(function() {
		$('#image-inputs').append('<div id="image-input-'+image_input_id+'"><iframe src="/photos/upload/'+image_input_id+'" class="image-upload-iframe" seamless></iframe></div>');
		
		image_input_id ++;

	});
	
	// Remove uploaded image when 'Remove' button is clicked
	$('.delete-new-image').live('click', function() {
		console.log("You want to delete me?");
		var file_name = $(this).prev().val();
		$(this).parent().hide();
		$(this).parent().html('<input type="hidden" name="deleted-images[]" value="'+file_name+'">');
	});
	

	//
	// Autocomplete for dish names on Add Meal page
	//
	$('#dish').autocomplete({
		
		delay: 500,
		
		source: '/meals/dish_list',
		
		response: function(event, ui) {
			if (ui.content.length === 0) {
				console.log("No results");
				$('#dish_id').val("0");
				$('p#add-new-dish').show();
			}
		},		
		
		// On focus (of autocomplete option), set field value to label, not value(id)
		focus: function( event, ui ) {
			$("#dish").val(ui.item.label);
			return false;
        },
		
		// On select, set field value to label, and hidden dish_id field value to option value (id), and hide fields to add new dish in case they are showing
		select: function (event, ui) {
			console.log("You chose an option");
			$('#dish').val(ui.item.label);
			$('#dish_id').val(ui.item.value);
			$('p#add-new-dish').hide();
			$('fieldset#new-dish-fields').hide();
			return false;
			
		}
	});
	
		
	//
	// Controls to create a new dish from Add Meal page 
	//
	$('p#add-new-dish input').click(function() {
		console.log("You clicked the add new dish button");
		$('fieldset#new-dish-fields').show();
	});
	
	
});

//
// Date picker for Add Meal page
//
$(function() {
        $('.datepicker').datepicker();
});

//
// Function called from image uploading iframe after image 
// uploading form is submitted, to hide iframe and add 
// display of image, hidden field with file name in main form, 
// and remove button
//
function replace_iframe(image_id, preview, original) {

	console.log("Hello I was called from the iframe with image id "+image_id);
	
	var image_html = '<div class="new-image"><img src="/temp/'+preview+'"><input type="hidden" name="images[]" value="'+original+'";><input type="button" class="delete-new-image" value="Remove"></div>';
	
	$('div#image-input-'+image_id).prepend(image_html);
	
	$('div#image-input-'+image_id+' iframe').hide();

};