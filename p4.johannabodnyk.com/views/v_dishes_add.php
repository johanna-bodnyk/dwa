		<h2>Add a dish...</h2>
		
		<!-- Add dish form -->

		<form name="add-dish" action="/dishes/p_add" method="POST" enctype="multipart/form-data">
			
			<?=$message?>

			<label for="name">Name of dish or food</label>
			<input type="text" name="name" id="name" value="">
			
			<!-- Image section-->
			<h3>Photos</h3>
			
			<div id="image-inputs">
				<iframe src="/photos/upload/1" class="image-upload-iframe">
				</iframe>
			</div>
			<input type="button" id="add-another-photo" value="Add another photo">
			
			<fieldset>
				<h3>Recipe Source</h3>
				<label for="source_type">Source Type</label>
				<select name="source_type" id="source_type">
					<option value="online">Online Recipe</option>
					<option value="cookbook">Cookbook</option>
					<option value="restaurant">Restaurant Meal</option>
					<option value="original">Original Recipe</option>
				</select>			
				<label for="source_name">Source Name</label>
				<input type="text" name="source_name" id="source_name" value="">
				<label for="source_link">Source Link</label>	
				<span class="instructions">Link to online recipe, restaurant website, or cookbook.</span>
				<input type="text" name="source_link" id="source_link" value="">
			</fieldset>
			<label for="default_meal_type">Default Meal
			</label>
			<select name="default_meal_type" id="default_meal_type">
				<option value="breakfast">Breakfast</option>
				<option value="lunch">Lunch</option>
				<option value="dinner">Dinner</option>
				<option value="snack">Snack</option>
			</select>	

				
			<label for="note">Notes</label>
			<textarea name="note" id="note" rows="10" cols="68"></textarea>	
			
			<input type="submit" value="Save Dish" id="submit-button">
			
			<p class="form-button"><a class="button" href="#">Cancel</a>
			
			<p class="form-button"><a class="button delete" href="/dishes/delete">Delete Dish</a>
		</form>
		
 		<div class=clear></div>