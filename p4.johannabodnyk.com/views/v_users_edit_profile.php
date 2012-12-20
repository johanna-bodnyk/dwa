		<h2>Edit Your Profile</h2>
				
		<!--If this is a new user's first time on this page (redirected from sign-up form), display a welcome message -->

		<?=$message?>
		
		<p>Your Display Name and Avatar image are the only things from your profile that other users can see. All other information here is private.</p>
		
		<!-- Edit profile form -->
		<form name="edit-profile" action="/users/p_edit_profile" method="POST" enctype="multipart/form-data">
			
			<label for="first_name">First name</label>
			<input type="text" name="first_name" id="first_name" class="validate[required]" value="<?=$user->first_name?>" >
		
			<label for="last_name">Last name</label>
			<input type="text" name="last_name" id="last_name" class="validate[required]" value="<?=$user->last_name?>" >
			
			<label for="display_name">Display name</label>
			<input type="text" name="display_name" id="display_name" class="validate[required]" value="<?=$user->display_name?>">			
			
			<label for="email">Email address</label>
			<input type="email" name="email" id="email" class="validate[required,custom[email]]" value="<?=$user->email?>">
			
			<!-- Not displaying current password value -->
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="validate[minSize[8]]">	
									
			<label for="password_check">Reenter password</label>
			<input type="password" name="password_check" id="password_check" class="validate[condRequired[password],equals[password]]">
					
			<!-- Image section-->
			<label for="profile_image">Avatar</label>
				
				<div id="profile-image-controls">
					<!-- If user has an existing uploaded image, display current image -->
					<div id="current-image" >
						<div id="image-previews">
						<img id="avatar-preview" src="/uploads/<?=$user->profile_image?>" alt="Avatar for <?=$user->display_name?>">
						<img id="default-avatar-preview" src="/uploads/default_avatar.png" alt="Placeholder avatar image">
						<input type="file" name="profile_image" id="profile-image">
						</div>
						<!-- File input to upload new image-->

						<? if ($user->profile_image != "default_avatar.png"): ?>
						<input type="radio" name="image_choice" id="keep" class="validate[minCheckbox[1]]" value="keep">
						<label for="keep">Keep current image</label><br>
						<? endif; ?>
						<input type="radio" name="image_choice" id="default" class="validate[minCheckbox[1]]" value="default">
						<label for="default">Use default image</label>
						<br>
						<input type="radio" name="image_choice" id="replace" class="validate[minCheckbox[1]]" value="replace">
						<label for="replace">Upload new image</label><br>

					
					
					
				</div>
			
			<input type="hidden" value="<?=$status?>" name="status">
			
			<input type="submit" value="Update Profile" id="submit-button">
					
			<!-- Cancel -->
			<p class="form-button"><a class="button" href="/meals/view/yours">Cancel</a></p>
			
		</form>

				
 		<div class=clear></div>