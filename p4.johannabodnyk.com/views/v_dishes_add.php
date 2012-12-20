		<h2>Add a dish...</h2>
		
		<!-- Add dish form -->

		<form name="add-dish" action="/dishes/p_add" method="POST" enctype="multipart/form-data">

			<label for="name">Name of dish or food</label>
			<input type="text" name="name" id="name" class="validate[required]" value="">
			
			<!-- Image section-->
			<label>Photos</label>
			
			<div id="image-inputs">
				<div id="image-input-1">
					<iframe src="/photos/upload/1" class="image-upload-iframe" seamless>
					</iframe>
				</div>
			</div>
			<input type="button" id="add-another-photo" value="Add another photo">
			
			<fieldset id="recipe-source">
				<h3>Recipe Source</h3>
				<label for="source_name">Source Name</label>
				<input type="text" name="source_name" id="source_name" value="">
				<p class="instructions">Name of website, restaurant, or cookbook that recipe or dish is from.</p>
				<label for="source_link">Source Link</label>
				<input type="text" name="source_link" id="source_link" value="">
				<p class="instructions">Link to recipe or restaurant.</p>
			</fieldset>


				
			<label for="note">Notes</label>
			<textarea name="note" id="note" rows="10" cols="68"></textarea>	
			
			<input type="submit" value="Save Dish" id="submit-button">
			
			<p class="form-button"><a class="button" href="/meals/view/yours">Cancel</a>

		</form>
		
 		<div class=clear></div>