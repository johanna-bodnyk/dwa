<?php

class users_controller extends base_controller {

	public function __construct() {
		parent::__construct();
#		echo 'hello world </br>';
	}
	
	public function index() {
		echo 'welcome to the users department';
	}

	public function signup() {
		echo 'display the signup page';
		$this->template->content = View::instance('v_users_signup');
		$this->template->title   = 'Signup';
		echo $this->template;
		}
		
	public function p_signup () {
		print_r($_POST);
		
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
		$_POST['created'] = Time::now();
		$_POST['modified'] = Time::now();		
		$_POST['token'] = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());
		
		DB::instance(DB_NAME)->insert('users', $_POST);
	}
	public function login() {
		echo 'display the login page';
		$this->template->content = View::instance('v_users_login');
		$this->template->title   = 'Login';
		echo $this->template;
	}
	
	public function p_login () {
		print_r($_POST);
		
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
		
		$q = "SELECT token
			FROM users
			WHERE email = '".$_POST['email']."'
			AND password = '".$_POST['password']."'
			";
		$token = DB::instance(DB_NAME)->select_field($q);
		
		if($token == "") {
			Router::redirect("/users/login");
		}
		//echo $this->user->first_name;
		else {
			setcookie("token", $token, strtotime('+2 weeks'), '/');
			Router::redirect("/");
		}
	
		echo "Here is the token".$token;
	}
	public function logout() {
		echo 'display the logout page';
	}
	
	public function profile($username = NULL, $color = NULL) {
		if(!$this->user) {
			echo "Members only. <a href='/users/login'>Please login.</a>";
			return false;
		
		}
		if ($username == NULL) {
			echo 'You did not specify a user';
		}
		else {
			echo 'This is the profile of '.$username.' and her favorite color is '.$color;
	}
	}
}