<?php

class users_controller extends base_controller {

	public function __construct() {
		parent::__construct();
	}
	
	#
	# Main users page should go to profile page
	#
	public function index() {
		Router::redirect('/users/profile'); 
	}

	#
	# Sign-up form
	#
	public function signup() {
		$this->template->content = View::instance('v_users_signup');
		$this->template->title   = 'Signup';
		echo $this->template;
		}

	#
	# Sign-up form processing
	#
	public function p_signup () {
		
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
		$_POST['created'] = Time::now();
		$_POST['modified'] = Time::now();
		$_POST['profile_image'] = "placeholder.png";
		$_POST['thumb_image'] = "placeholder_thumb.png";
		$_POST['alt_text'] = "Placeholder profile image";

		
		$token = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());
		$_POST['token'] = $token;
		
		# Create DB record for user
		DB::instance(DB_NAME)->insert('users', $_POST);
		
		# Set cookie so new user is logged in
		setcookie("token", $token, strtotime('+2 weeks'), '/');
		
		# Redirect to edit profile page with variable (new_user) to trigger welcome message
		Router::redirect("/users/edit_profile/new_user");
	}
	
	#
	# Log-in processing
	#
	public function p_login () {

		$_POST = DB::instance(DB_NAME)->sanitize($_POST);
		
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
		
		$q = "SELECT token
			FROM users
			WHERE email = '".$_POST['email']."'
			AND password = '".$_POST['password']."'
			";
		$token = DB::instance(DB_NAME)->select_field($q);
		
	 	if($token == "") {
			Router::redirect("/index/index/error");
		}
		else {
			setcookie("token", $token, strtotime('+2 weeks'), '/');
			Router::redirect("/posts");
		}
	
		echo "Here is the token".$token;
	}
	
	#
	# Log out
	#
	public function logout() {
		
		# Create a new token and array with field name and value of new token
		$new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());
		$data = Array("token" => $new_token);
		
		# Update DB with new token
		DB::instance(DB_NAME)->update("users", $data, "WHERE token = '".$this->user->token."'");
		
		# Unset cookie to log user out
		setcookie("token", "", strtotime('-1 year'), '/');

		Router::redirect("/");
	}

	#
	# Profile page -- current user's and other users'
	#	
	public function profile($profile_user_id = NULL) {
		
		if(!$this->user) {
			Router::redirect('/');
			return false;
		}

		$this->template->content = View::instance('v_users_profile');
		
		# If no user ID is passed, assume the user is trying to visit their own profile
		if ($profile_user_id == NULL) {
			$profile_user_id = $this->user->user_id;
		}
		
		# Get the profile content from the DB for the user whose profile will be dislayed	
		$q = "SELECT 
			user_id,
			first_name, 
			last_name,
			profile_image,
			alt_text,
			email,
			location,
			website,
			bio
			FROM users
			WHERE user_id = ".$profile_user_id."
			";

		$profile_content = DB::instance(DB_NAME)->select_row($q);	
		
		$own_profile = FALSE;
		$following = FALSE;
		
		# If we're visiting the current user's own profile
		# set title and $own_profile for view logic
		if ($profile_user_id == $this->user->user_id) {
			$own_profile = TRUE;
			$this->template->title   = 'Your profile';
			$subnav = "profile";
		}

		# If not, set title and find out if the current user is following
		# the user whose profile will be displayed
		else {
			$this->template->title = $profile_content['first_name']."'s profile";
			
			$q = "SELECT * 
			FROM users_users
			WHERE user_id_followed = ".$profile_user_id."
			and user_id= ".$this->user->user_id;
			
			$following = DB::instance(DB_NAME)->select_row($q);
			
			$subnav = "";

		}
		
		$this->template->content->own_profile = $own_profile;
		$this->template->content->profile_content = $profile_content;
		$this->template->content->following = $following;
		
		# Set variables for "current" navigation styles
		$this->template->nav = "chirpers";
		$this->template->subnav = $subnav;
		
		echo $this->template;	
		
	}
	
	#
	# Edit profile form
	#
	public function edit_profile($status = NULL) {

		if(!$this->user) {
			Router::redirect('/');
			return false;
		}
		
		# Check for variable indicating a redirect from the sign-up form,
		# and if so set $message to show a welcome messages
		if($status == "new_user") {
			$message = "<p class='message'>Welcome to Chirper! Fill out your profile so people know who you are! <br> <a href='/posts'>(Or skip this for now.)</a></p>";
		}
		else {
			$message = "";
		}
						
		$this->template->content = View::instance('v_users_edit_profile');
		$this->template->title   = 'Edit your profile';
		$this->template->content->message = $message;
		
		# Set variables for "current" navigation styles
		$this->template->nav = "chirpers";
		$this->template->subnav = "profile";
		
		echo $this->template;
	}

	#
	# Edit profile form processing
	#	
	public function p_edit_profile() {

		# If "Delete photo" box was checked and no new image was submitted
		# add placeholder image file names to $_POST array to overwrite existing image
		if ((array_key_exists('delete_photo', $_POST)) && ($_FILES['profile_image']['name'] == "")) {
			$_POST['profile_image'] = "placeholder.png";
			$_POST['thumb_image'] = "placeholder_thumb.png";
			$_POST['alt_text'] = "Placeholder profile image";
		}
		# If an image was submitted, upload to /uploads/ as 
		# profile_image_[USERID]_original
		# and add the filename to the $_POST array
		if ($_FILES['profile_image']['name'] != "") {
		
			$profile_image = Upload::upload($_FILES, "/uploads/", array("jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG"), "profile_image_".$this->user->user_id);
			
			$imgObj = new Image(APP_PATH."uploads/".$profile_image);
			
 			$imgObj->resize(250,250,"crop");
			$imgObj->save_image(APP_PATH."uploads/".$profile_image, 100);
			
			$file_parts = pathinfo($profile_image);
			$thumb_image = "thumb_image_".$this->user->user_id.".".$file_parts['extension'];

			$imgObj->resize(75,75,"crop");
			$imgObj->save_image(APP_PATH."uploads/".$thumb_image, 100);

			$_POST['profile_image'] = $profile_image;
			$_POST['thumb_image'] = $thumb_image;
			$_POST['alt_text'] = "Profile image for ".$this->user->first_name;

		}
		
		# If "Delete photo" is unchecked and no new image was submitted,
		# do nothing -- profile_image field in DB remains as-is

		# Remove "Delete photo" checkbox from $_POST array if it exists
		if (array_key_exists('delete_photo', $_POST)) {
			unset($_POST['delete_photo']);
		}
		
		# Check to see if a password was submitted. If so, add salt and hash.
		if ($_POST['password'] != "") {
		
			$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
		}
		
		# If not, remove password element from $_POST array so existing password isn't overwritten
		else {
			unset($_POST['password']);
		}

		# Update DB with new profile information (including empty fields)
		DB::instance(DB_NAME)->update("users", $_POST, "WHERE token = '".$this->user->token."'");		
	
 		Router::redirect('/users/profile'); 

	}
	
	#
	# Delete account confirmation page
	#	
	public function delete () {
		
		if(!$this->user) {
			Router::redirect('/');
			return false;
		}
		
		# Set variables for "current" navigation styles
		$this->template->nav = "chirpers";
		$this->template->subnav = "profile";
		
		# Set up the view
		$this->template->content = View::instance("v_users_delete");
		$this->template->title = "Delete account";
		
		# Render the view
		echo $this->template;
	}
	
	#
	# Delete account processing
	#		
	public function p_delete () {
		
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

		# Remove current user from the user table
		$where_condition = "WHERE
			user_id = ".$this->user->user_id;
			
		DB::instance(DB_NAME)->delete("users", $where_condition);
		
		# Unset cookie
		setcookie("token", "", strtotime('-1 year'), '/');
		
		Router::redirect('/'); 
	}	
}