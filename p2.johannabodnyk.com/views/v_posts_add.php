		<h2>Say something!</h2>
		
		<!-- Add post form -->

		<form name="add-chirp" action="/posts/p_add" method="POST">
			
			<?=$message?>
			
			<textarea name="content" rows="5" cols="82"></textarea>	
			
			<input type="submit" value="Chirp!" id="submit-button">
		</form>
		
 		<div class=clear></div>