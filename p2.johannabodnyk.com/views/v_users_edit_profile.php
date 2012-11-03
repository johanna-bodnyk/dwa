		<h2>Edit Your Profile</h2>
		
		<!--If this is a new user's first time on this page (redirected from sign-up form), display a welcome message -->
			
		<?=$message?>
		
		<form name="edit-profile" action="/users/p_edit_profile" method="POST" enctype="multipart/form-data">
			
			<label for="first_name">First name</label>
			<input type="text" name="first_name" id="first_name" value="<?=$user->first_name?>">
		
			<label for="last_name">Last name</label>
			<input type="text" name="last_name" id="last_name" value="<?=$user->last_name?>">
			
			<label for="email">Email address</label>
			<input type="email" name="email" id="email" value="<?=$user->email?>">
			
			<label for="password">Password</label>
			<input type="password" name="password" id="password">	
			
			<label for="location">Location</label>
			<input type="text" name="location" id="location" value="<?=$user->location?>">
						
			<label for="website">Website</label>
			<input type="url" name="website" id="website" value="<?=$user->website?>">
							
			<label for="profile_image">Photo</label>
			<? if ($user->profile_image): ?>
				<p class="instructions">Current photo:</p>
				<img src="/uploads/<?=$user->profile_image?>">
				<p class="instructions">To replace the current photo, choose a new file from your computer:</p>
			<? endif; ?>
			<input type="file" name="profile_image" id="profile_image">
			<? if ($user->profile_image): ?>
				<p class="instructions">To delete the current photo without replacing it, check here:</p>
				<input type="checkbox" name="delete_photo" id="delete_photo">
				<label class="check-label" for="delete_photo">Delete current photo</label>
			<? endif; ?>
			<label for="bio">Bio</label>
			<textarea name="bio" id="bio" rows="15" cols="82"><?=$user->bio?></textarea>	
			
			<input type="submit" value="Update Profile" id="submit-button">
		</form>
		
		<p class="button-link"><a href="/users/profile">Cancel</a>
		<p class="button-link delete"><a href="/users/delete">Delete Account</a>
		
		
 		<div class=clear></div>