<?php

class users_controller extends base_controller {

	public function __construct() {
		parent::__construct();
	}
	
/*-------------------------------------------------------------------------------------------------

	/users/index redirects to profile page
	
-------------------------------------------------------------------------------------------------*/
	public function index() {
		Router::redirect('/users/profile'); 
	}

	
/*-------------------------------------------------------------------------------------------------

	Sign-up form
	
-------------------------------------------------------------------------------------------------*/
	public function signup($error = NULL) {
				
		# Set up the view 
		$this->template->content = View::instance('v_users_signup');
		$this->template->title   = 'Signup';

		# Check whether form is reloading after a failed signup attempt
		# If so, send an error message to the view
		if ($error == "error") {
			$message = "<p class='message error'>All fields are required. Please try again.</p>";
		}
		else {
			$message = "";
		}
		
		# Send necessary data/variables to the view
		$this->template->content->message = $message;

		# Render the view
		echo $this->template;
	}

	
/*-------------------------------------------------------------------------------------------------

	Sign-up form processing
	
-------------------------------------------------------------------------------------------------*/	
	public function p_signup () {
		
		# Check to see whether all fields in sign-up form were filled in
		# (Ran out of time to do sophisticated error-checking here!)
		$missing_data = FALSE;
		 
		foreach ($_POST as $field) {
			if ($field == "") {
				$missing_data = TRUE;
			}
		}
		
		# If any fields are empty, redirect to sign-up form 
		# with variable to trigger error message
		if ($missing_data) {
			Router::redirect("/users/signup/error");
		}
		
		# Otherwise, create new user account
		else {
			# Add salt and hash password
			$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
			
			# Set created and modified to current time and add to POST array
			$_POST['created'] = Time::now();
			$_POST['modified'] = Time::now();
			
			# Add placeholder images and alt text to POST array
			$_POST['profile_image'] = "placeholder.png";
			$_POST['thumb_image'] = "placeholder_thumb.png";
			$_POST['alt_text'] = "Placeholder profile image";

			# Create token and add to POST array
			$token = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());
			$_POST['token'] = $token;
			
			# Create DB record for user
			DB::instance(DB_NAME)->insert('users', $_POST);
			
			# Set cookie so new user is logged in
			setcookie("token", $token, strtotime('+2 weeks'), '/');
			
			# Redirect to edit profile page with variable to trigger welcome message
			Router::redirect("/users/edit_profile/new_user");
		}
	}
	
	
/*-------------------------------------------------------------------------------------------------

	Log-in form processing (from form on logged out homepage)
	
-------------------------------------------------------------------------------------------------*/	
	public function p_login () {

		# Sanitize data entered in login form
		$_POST = DB::instance(DB_NAME)->sanitize($_POST);
		
		# Hash password and add salt
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
		
		# Try to get token from DB for submitted email/password combination
		$q = "SELECT token
			FROM users
			WHERE email = '".$_POST['email']."'
			AND password = '".$_POST['password']."'
			";
		$token = DB::instance(DB_NAME)->select_field($q);
		
	 	# If no token was returned, submitted password/combination does not exist
		# so return to homepage with variable to trigger error message
		if($token == "") {
			Router::redirect("/index/index/error");
		}
		# Otherwise, set cookie using token and redirect to landing page
		else {
			setcookie("token", $token, strtotime('+2 weeks'), '/');
			Router::redirect("/posts/stream");
		}
	
	}
	
	
/*-------------------------------------------------------------------------------------------------

	Log out
	
-------------------------------------------------------------------------------------------------*/		
	public function logout() {
		
		# Create a new token and array with field name and value of new token
		$new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());
		$data = Array("token" => $new_token);
		
		# Update DB with new token
		DB::instance(DB_NAME)->update("users", $data, "WHERE token = '".$this->user->token."'");
		
		# Unset cookie to log user out
		setcookie("token", "", strtotime('-1 year'), '/');

		# Return to homepage
		Router::redirect("/");
	}

	
/*-------------------------------------------------------------------------------------------------

	Profile page -- for current user and other users
	
-------------------------------------------------------------------------------------------------*/	
	public function profile($profile_user_id = NULL) {
		
		# Authenticate, return to homepage if failed
		if(!$this->user) {
			Router::redirect('/');
			return false;
		}
		
		# Set up the view 
		$this->template->content = View::instance('v_users_profile');
		
		# If no user ID is passed, or a non-numeric ID is passed,
		# assume the user is trying to visit their own profile
		# and set $profile_user_id to current user's ID
		if (($profile_user_id == NULL) OR (!is_numeric($profile_user_id))) {
			$profile_user_id = $this->user->user_id;
		}
		
		# Get the profile content from the DB based on $profile_user_id
		$q = "SELECT *
			FROM users
			WHERE user_id = ".$profile_user_id;

		$profile_content = DB::instance(DB_NAME)->select_row($q);	
		
		$following = FALSE;
		
		# If displaying the current user's own profile set title and 
		# subnav accordingly and $own_profile for view logic
		if ($profile_user_id == $this->user->user_id) {
			$own_profile = TRUE;
			$this->template->title   = 'Your profile';
			$subnav = "profile";
		}

		# If displaying another user's profile, set title and subnav 
		# and set $following to TRUE or FALSE depending on whether
		# the current user is following that user
		else {
			$own_profile = FALSE;
			
			$this->template->title = $profile_content['first_name']."'s profile";
			
			$q = "SELECT * 
				FROM users_users
				WHERE user_id_followed = ".$profile_user_id."
				and user_id= ".$this->user->user_id;
			
			$following = DB::instance(DB_NAME)->select_row($q);
			
			$subnav = "";
		}
		
		# Send necessary data/variables to the view
		$this->template->content->own_profile = $own_profile;
		$this->template->content->profile_content = $profile_content;
		$this->template->content->following = $following;
		
		# Set variables for "current" navigation styles
		$this->template->nav = "chirpers";
		$this->template->subnav = $subnav;
		
		# Render the view
		echo $this->template;	
		
	}
	
/*-------------------------------------------------------------------------------------------------

	Edit profile form
	
-------------------------------------------------------------------------------------------------*/	
	public function edit_profile($status = NULL) {

		# Authenticate, return to homepage if failed
		if(!$this->user) {
			Router::redirect('/');
			return false;
		}

		# Set up the view 
		$this->template->content = View::instance('v_users_edit_profile');
		$this->template->title   = 'Edit your profile';
		
		# Check for variable indicating a redirect from the sign-up form,
		# and if so set $message to show a welcome messages
		if($status == "new_user") {
			$message = "<p class='message'>Welcome to Chirper! Fill out your profile so people know who you are! <br> <a href='/posts'>(Or skip this for now.)</a></p>";
		}
		else {
			$message = "";
		}
						
		# Send necessary data/variables to the view
		$this->template->content->message = $message;
		
		# Set variables for "current" navigation styles
		$this->template->nav = "chirpers";
		$this->template->subnav = "profile";

		# Render the view
		echo $this->template;
	}

	
/*-------------------------------------------------------------------------------------------------

	Edit profile form processing
	
-------------------------------------------------------------------------------------------------*/		
	public function p_edit_profile() {

		# If "Delete photo" box was checked and no new image was submitted
		# add placeholder images to $_POST array to overwrite existing image
		if ((array_key_exists('delete_photo', $_POST)) && ($_FILES['profile_image']['name'] == "")) {
			$_POST['profile_image'] = "placeholder.png";
			$_POST['thumb_image'] = "placeholder_thumb.png";
			$_POST['alt_text'] = "Placeholder profile image";
		}
		
		# If an image was submitted, upload to server,
		# resize and save profile (250x250) and thumb (75x75) versions,
		# and add the filenames to the $_POST array
		if ($_FILES['profile_image']['name'] != "") {
		
			# Upload submitted image to server
			$profile_image = Upload::upload($_FILES, "/uploads/", array("jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG"), "profile_image_".$this->user->user_id);
			
			# Instantiate new image object using uploaded file
			$imgObj = new Image(APP_PATH."uploads/".$profile_image);
			
			# Resize and save profile version of image
 			$imgObj->resize(250,250,"crop");
			$imgObj->save_image(APP_PATH."uploads/".$profile_image, 100);
			
			# Resize and save thumbnail version of image
			$file_parts = pathinfo($profile_image);
			$thumb_image = "thumb_image_".$this->user->user_id.".".$file_parts['extension'];
			$imgObj->resize(75,75,"crop");
			$imgObj->save_image(APP_PATH."uploads/".$thumb_image, 100);

			# Add image filenames and alt text to POST array
			$_POST['profile_image'] = $profile_image;
			$_POST['thumb_image'] = $thumb_image;
			$_POST['alt_text'] = "Profile image for ".$this->user->first_name;

		}
		
		# (If "Delete photo" is unchecked and no new image was submitted,
		# do nothing -- image fields in DB remains as-is)

		# Remove "Delete photo" checkbox from $_POST array if it exists
		if (array_key_exists('delete_photo', $_POST)) {
			unset($_POST['delete_photo']);
		}
		
		# Check to see if a password was submitted. If so, add salt and hash.
		if ($_POST['password'] != "") {
			$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
		}
		
		# If not, remove password element from POST array so existing password isn't overwritten
		else {
			unset($_POST['password']);
		}

		# First name, last name, and email are required.
		# If those array elements are blank, remove from POST array
		# so existing values are not overwritten
		# (Ran out of time to do sophisticated error-checking on required fields - sorry!)
		if ($_POST['first_name'] == "") {
			unset($_POST['first_name']);
		}
		if ($_POST['last_name'] == "") {
			unset($_POST['last_name']);
		}
		if ($_POST['email'] == "") {
			unset($_POST['email']);
		}
		
		# Update DB with new profile information (including empty fields)
		DB::instance(DB_NAME)->update("users", $_POST, "WHERE token = '".$this->user->token."'");		
	
 		Router::redirect('/users/profile'); 

	}

/*-------------------------------------------------------------------------------------------------

	Delete account confirmation page (from button Edit Profile form)
	
-------------------------------------------------------------------------------------------------*/		
	public function delete () {
		
		# Authenticate, return to homepage if failed
		if(!$this->user) {
			Router::redirect('/');
			return false;
		}
		
		# Set up the view
		$this->template->content = View::instance("v_users_delete");
		$this->template->title = "Delete account";
		
		# Set variables for "current" navigation styles
		$this->template->nav = "chirpers";
		$this->template->subnav = "profile";
		
		# Render the view
		echo $this->template;
	}


/*-------------------------------------------------------------------------------------------------

	Delete account processing
	
-------------------------------------------------------------------------------------------------*/		
	public function p_delete () {
		
		# Authenticate, return to homepage if failed
		if(!$this->user) {
			Router::redirect('/');
			return false;
		}
		
		# Delete all connections involving current user
		$where_condition = "WHERE
			user_id = ".$this->user->user_id."
			OR user_id_followed = ".$this->user->user_id;
			
		DB::instance(DB_NAME)->delete("users_users", $where_condition);

		# Delete all of current user's posts
		$where_condition = "WHERE
			user_id = ".$this->user->user_id;
			
		DB::instance(DB_NAME)->delete("posts", $where_condition);

		# Remove current user from the users table
		$where_condition = "WHERE
			user_id = ".$this->user->user_id;
			
		DB::instance(DB_NAME)->delete("users", $where_condition);
		
		# Unset cookie
		setcookie("token", "", strtotime('-1 year'), '/');
		
		# Return to homepage
		Router::redirect('/'); 
	}	
}