<?php

class users_controller extends base_controller {

	public function __construct() {
		parent::__construct();
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
		# CHANGE TO JS VALIDATION
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
		# CHANGE TO JS VALIDATION!!!!!!!!!!!
		$missing_data = FALSE;
		 
		foreach ($_POST as $field) {
			if ($field == "") {
				$missing_data = TRUE;
			}
		}
		
		# If any fields are empty, redirect to sign-up form 
		# with variable to trigger error message
		# CHANGE TO JS VALIDATION!!!!!!!!!!!
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
			
			# Add default avatar to POST array
			$_POST['profile_image'] = "default_avatar.png";

			# Create token and add to POST array
			$token = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());
			$_POST['token'] = $token;
			
			# Set display name to first name plus last name
			$_POST['display_name'] = $_POST['first_name']." ".$_POST['last_name'];
			
			# Unset duplicate password element
			unset($_POST['password_check']);
			
			# Create DB record for user
			DB::instance(DB_NAME)->insert('users', $_POST);
			
			# Set cookie so new user is logged in
			setcookie("token", $token, strtotime('+2 weeks'), '/');
			
			# Redirect to profile editing page
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
			Router::redirect("/meals/view/stream");
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
			$message = "<p>Welcome to Yumbook! Please review your Display Name below, and upload an Avatar image (optional).</p>";
		}
		else {
			$message = "";
		}
						
		# Send necessary data/variables to the view
		$this->template->content->message = $message;
		$this->template->content->status = $status;
		
		# Set variable for "current" navigation style
		$this->template->nav = "account";

		# Render the view
		echo $this->template;
	}

	
/*-------------------------------------------------------------------------------------------------

	Edit profile form processing
	
-------------------------------------------------------------------------------------------------*/		
	public function p_edit_profile() {		
		
		# If "Default" image option was checked, add placeholder filename to $_POST array
		if ($_POST['image_choice'] == "default") {
			$_POST['profile_image'] = "default_avatar.png";
		}
		
		
		# If "Replace" was checked, upload submitted to server,
		# resize to 40x40 and add the filename to the $_POST array
		if ($_POST['image_choice'] == "replace") {
		
			$filename = "avatar-user-".$this->user->user_id;
			
			# Upload submitted image to server
			$profile_image = Upload::upload($_FILES, "/uploads/", array("jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG"), $filename);
			
			# Instantiate new image object using uploaded file
			$imgObj = new Image(APP_PATH."uploads/".$profile_image);
			
			# Resize and resave
 			$imgObj->resize(50,50,"crop");
			$imgObj->save_image(APP_PATH."uploads/".$filename.'.jpg', 100);
			
			# Add image filename to POST array
			$_POST['profile_image'] = $filename.'.jpg';

		}
		
		# If "Keep" image option was checked, do nothing -- image field in DB remains as-is)

		# Remove image choice $_POST array i
		unset($_POST['image_choice']);
		
		# Check to see if a password was submitted. If so, add salt and hash.
		if ($_POST['password'] != "") {
			$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
		}
		
		# If not, remove password element from POST array so existing password isn't overwritten
		else {
			unset($_POST['password']);
		}
		
		# Unset duplicate password element
		unset($_POST['password_check']);		
		
		$status = $_POST['status'];
		unset($_POST['status']);		
		
		# First name, last name, and email are required.
		
		# If those array elements are blank, remove from POST array
		# so existing values are not overwritten
		# (Ran out of time to do sophisticated error-checking on required fields - sorry!)
		
		# CHANGE TO JS VALIDATION!!!!!!!!!!!!!!
		
		if ($_POST['display_name'] == "") {
			unset($_POST['display_name']);
		}
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

		 if ($status == "new_user") {
			Router::redirect('/users/about/new_user'); 
		 }
		 else {
			Router::redirect('/meals/view/stream'); 
		 }
	
	}

/*-------------------------------------------------------------------------------------------------

	Welcome/Instructions page
	
-------------------------------------------------------------------------------------------------*/		
	public function about($status = NULL) {
	
		# Authenticate, return to homepage if failed
		if(!$this->user) {
			Router::redirect('/');
			return false;
		}

		# Set up the view 
		$this->template->content = View::instance('v_users_about');
		$this->template->title   = 'Welcome to Yumbook';
		
		# Check for variable indicating a redirect from the edit profile form after sign-up,
		# and if so set $message to show a welcome messages
		if($status == "new_user") {
			$message = "<p>Welcome to Yumbook!</p>";
		}
		else {
			$message = "";
		}
						
		# Send necessary data/variables to the view
		$this->template->content->message = $message;
		
		# Set variable for "current" navigation style
		$this->template->nav = "about";

		# Render the view
		echo $this->template;

	

	}
/*-------------------------------------------------------------------------------------------------

	User list
	
-------------------------------------------------------------------------------------------------*/		
	public function userlist() {		
	
		# Authenticate, return to homepage if failed
		if(!$this->user) {
			Router::redirect('/');
			return false;
		}
		
		$this->template->content = View::instance("v_users_userlist");
			
		$q = "SELECT user_id, display_name, profile_image
			FROM users
			WHERE user_id != ".$this->user->user_id;
			
		$users = DB::instance(DB_NAME)->select_rows($q);
		
		# Get the list of user_ids this user is following
		$q = "SELECT * 
			FROM users_users
			WHERE user_id = ".$this->user->user_id;
	
		$connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');
		
		$this->template->content->users = $users;
		$this->template->content->connections = $connections;
		
		# Set variable for "current" navigation style
		$this->template->nav = "friends";

		echo $this->template;
	}
	
/*-------------------------------------------------------------------------------------------------

	Follow a user
	
-------------------------------------------------------------------------------------------------*/
	public function follow($user_id_followed = NULL) {
		
		# Sanitize by ensuring $user_id_followed is numeric 
		if (is_numeric($user_id_followed)) {
		
			# If so, add entry to users_users table
			$data['created'] = Time::now();
			$data['user_id'] = $this->user->user_id;
			$data['user_id_followed'] = $user_id_followed;
			
			DB::instance(DB_NAME)->insert("users_users", $data);
			
			echo "1";
		}

		else {
			echo "0";
			return false;
		}
		
	}


/*-------------------------------------------------------------------------------------------------

	Stop following a user
	
-------------------------------------------------------------------------------------------------*/	
	public function unfollow($user_id_followed = NULL, $source_page = NULL) {

		# Sanitize by ensuring $user_id_followed is numeric 
		if (is_numeric($user_id_followed)) {
			
			# If so, remove entry from users_users table
			$where_condition = "WHERE
				user_id = ".$this->user->user_id."
				AND user_id_followed = ".$user_id_followed;
				
			DB::instance(DB_NAME)->delete("users_users", $where_condition);
		}

		else {
			echo "0";
			return false;		
		}
		

	}
}