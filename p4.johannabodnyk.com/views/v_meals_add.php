	<? if (isset($meal_id)): ?>
		
		<h2>Add another dish...</h2>
		
		<p class="intro">...to your meal on <?=$meal_date?>.</p>
		
		<!-- Add dish form -->

		<form name="add-meal" action="/meals/p_add" method="POST" enctype="multipart/form-data">
		
		<input type="hidden" name="meal_id" value="<?=$meal_id?>">
			
	<? else: ?>
		
		<h2>Add a meal...</h2>
		
		<!-- Add dish form -->

		<form name="add-meal" action="/meals/p_add" method="POST" enctype="multipart/form-data">

		
			<label for="date">Date &amp; Time of Meal</label>
			<input type="text" name="date" id="date" class="datepicker" value="MM/DD/YYYY">
			<input type="text" name="time" id="time" value="eg. 9:30am">
			
	<? endif; ?>
			
			<label for="dish">Dish name</label>
			<input type="text" name="dish" id="dish" class="validate[required]" value="<?=$dish_name?>">
			<input type="hidden" name="dish_id" id="dish_id" value="<?=$dish_id?>">
			<p id="add-new-dish">No matching dishes found. Create a new dish with this name?
				<input type="button" value="Create new dish">
			</p>
			<fieldset id="new-dish-fields">
				<h3>New Dish Information</h3>
					<fieldset id="recipe-source">
					<h3>Recipe Source</h3>
					<label for="source_name">Source Name</label>
					<input type="text" name="source_name" id="source_name" value="">
					<p class="instructions">Name of website, restaurant, or cookbook that recipe or dish is from.</p>
					<label for="source_link">Source Link</label>	
					<input type="text" name="source_link" id="source_link" value="">
					<p class="instructions">Link to online recipe, restaurant website, or cookbook.</p>
					</fieldset>
				<label for="dish-note">Dish Notes</label>
				<textarea name="dish-note" id="dish-note" rows="5" cols="63"></textarea>
				<p class="instructions">Notes entered here will appear on the dish information page. Notes entered below about this particular meal will appear in your stream.</p>
				
			</fieldset>
			
			<!-- Image section-->
			<label>Photos</label>
			
			<div id="image-inputs">
				<div id="image-input-1">
					<iframe src="/photos/upload/1" class="image-upload-iframe" seamless>
					</iframe>
				</div>
			</div>
			<input type="button" id="add-another-photo" value="Add another photo">
				
			<label for="note">Notes</label>
			<textarea name="note" id="note" rows="10" cols="68"></textarea>	
			
			<input type="submit" value="Save" name="save-submit" id="submit-button">
			<input type="submit" value="Save &amp; Add Another Dish to This Meal" name="save-add-submit" id="submit-button">
						
			<p class="form-button"><a class="button" href="/meals/view/yours">Cancel</a>
			
		</form>
		
 		<div class=clear></div>