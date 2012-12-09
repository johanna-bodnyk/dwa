		<h2>Edit Your Profile</h2>
		
		<!--If this is a new user's first time on this page (redirected from sign-up form), display a welcome message -->

		<?=$message?>
		
		<!-- Edit profile form -->
		<form name="edit-profile" action="/users/p_edit_profile" method="POST" enctype="multipart/form-data">
			
			<label for="first_name">First name</label>
			<input type="text" name="first_name" id="first_name" value="<?=$user->first_name?>">
		
			<label for="last_name">Last name</label>
			<input type="text" name="last_name" id="last_name" value="<?=$user->last_name?>">
			
			<label for="display_name">Display name</label>
			<input type="text" name="display_name" id="display_name" value="<?=$user->display_name?>">			
			
			<label for="email">Email address</label>
			<input type="email" name="email" id="email" value="<?=$user->email?>">
			
			<!-- Not displaying current password value -->
			<label for="password">Password</label>
			<input type="password" name="password" id="password">	
									
			<label for="password_check">Reenter password</label>
			<input type="password" name="password_check" id="password_check">
					
			<!-- Image section-->
			<label for="profile_image">Photo</label>
				
				<!-- If user has an existing uploaded image, display current image -->
				<? if ($user->profile_image != "placeholder.png"): ?>
					<p class="instructions">Current photo:</p>
					<img src="/uploads/<?=$user->profile_image?>" alt="Profile image for <?=$user->display_name?>">
					<p class="instructions">To replace the current photo, choose a new file from your computer:</p>
				<? endif; ?>
				
				<!-- File input to upload new image-->
				<input type="file" name="profile_image" id="profile_image">
				
				<!-- If user has an existing uploaded image, display checkbox to delete it-->
				<? if ($user->profile_image != "placeholder.png"): ?>
					<p class="instructions">To delete the current photo without replacing it, check here:</p>
					<input type="checkbox" name="delete_photo" id="delete_photo">
					<label class="check-label" for="delete_photo">Delete current photo</label>
				<? endif; ?>
				
			
			<input type="submit" value="Update Profile" id="submit-button">
					
			<!-- Cancel -->
			<p class="form-button"><a class="button" href="/users/profile">Cancel</a></p>
			
			<!-- Delete account -->
			<p class="form-button"><a class="button delete" href="/users/delete">Delete Account</a></p>
			
		</form>

				
 		<div class=clear></div>