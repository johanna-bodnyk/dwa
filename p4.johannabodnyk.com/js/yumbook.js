$(document).ready(function() {

	//
	// Avatar image uploading radio controls on Edit Profile form
	//
	
	$('input#keep').click(function () {
		$('input#profile-image').hide();
		$('img#avatar-preview').show();
		$('img#default-avatar-preview').hide();
	});
	
	$('input#default').click(function () {
		$('input#profile-image').hide();
		$('img#avatar-preview').hide();
		$('img#default-avatar-preview').show();
	});
	
	$('input#replace').click(function () {
		$('input#profile-image').show();
		$('img#avatar-preview').hide();
		$('img#default-avatar-preview').hide();
	});
	
	//
	// Add to Stream button and panel on Dish pages
	//
	
	// Add to Stream button	- opens and closes Add to Meal panel, changes colors
	$('#add-to-stream').click(function() {
		$('div#add-to-stream-panel').slideToggle();
		$(this).toggleClass('open');
		return false;
	});
	
	// Changes meal id in Add to Meal button href on change of meal date select element
	$('#meal-selection').change(function() {
		var href = $('#add-to-meal').attr('href');
		console.log(href);	
		var dish_id = href.substr(href.length-1);
		console.log(dish_id);	
		var meal_id = $(this).val();
		console.log(meal_id);
		$('#add-to-meal').attr('href', '/meals/add/'+meal_id+'/'+dish_id);
	});
	
	
	//
	// Like/Unlike button on Dish and Stream pages
	//
	
	$('a#like-button').live('click', function() {
	
		$.ajax({
			url: $(this).attr('href'),
			success: function(response) {
				console.log($(this));
				$('p#likes').html(response);
				
				
			}
		});
		
		return false;
	
	});	
	
	//
	// Follow/Unfollow button on User list page
	//
	
	$('a.follow-button').live('click', function() {
	
		var href = $(this).attr('href');
	
		$.ajax({
			url: href,	
		});
		
		$(this).text('Stop following');
		$(this).removeClass('follow-button').addClass('unfollow-button');
		
		var user_id = href.substr(href.length-1);
		$(this).attr('href', '/users/unfollow/'+user_id);	
		
		$(this).prev('a').addClass('followed');
		
		return false;
	
	});
	
	$('a.unfollow-button').live('click', function() {
	
		var href = $(this).attr('href');
	
		$.ajax({
			url: href,	
		});
		
		$(this).text('Follow');
		$(this).removeClass('unfollow-button').addClass('follow-button');
		
		var user_id = href.substr(href.length-1);
		$(this).attr('href', '/users/follow/'+user_id);	
		
		$(this).prev('a').removeClass('followed');

		
		return false;
	
	});
	
	
	
	//
	// Ajax submission for Comment forms
	//
	
	var comment_form_options = {
		clearForm: true,
		success: function(response, statusText, xhr, $form) {
			console.log($form);
			$($form).prev('ul').append(response);
		}	
	}
	
	$('.add-comment').ajaxForm(comment_form_options);
	
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
		console.log("You like to delete me?");
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
	
	//
	//	Expand closed dishes on Stream page
	//
	
	$('a.dish-toggle').click(function() {
	
		$(this).parent().toggleClass('closed');
		if ($(this).text() === "Expand") {
			$(this).text('Close');
			$(this).addClass('on');
		}
		else {
			$(this).text('Expand');
			$(this).removeClass('on');
		}
		return false;
		
	});
	
	// ValidationEngine form validation plugin
	$('form').validationEngine();
	
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
// display image, hidden field with file name in main form, 
// and remove button
//
function replace_iframe(image_id, preview, original) {

	console.log("Hello I was called from the iframe with image id "+image_id);
	
	var image_html = '<div class="new-image"><img src="/temp/'+preview+'"><input type="hidden" name="images[]" value="'+original+'" ><input type="button" class="delete-new-image" value="Remove"></div>';
	
	$('div#image-input-'+image_id).prepend(image_html);
	
	$('div#image-input-'+image_id+' iframe').hide();

};