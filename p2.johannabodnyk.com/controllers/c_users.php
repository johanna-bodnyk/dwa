<?php

class users_controller extends base_controller {

	public function __construct() {
		parent::__construct();
		echo "hello world </br>";
	}
	
	public function index() {
		echo "welcome to the users department";
	}

	public function signup() {
		echo "display the signup page";
	}
	public function login() {
		echo "display the login page";
	}
	public function logout() {
		echo "display the logout page";
	}
	
	public function profile($username = NULL, $color = NULL) {
	if ($username == NULL) {
		echo "You did not specify a user";
	}
	else {
		echo "This is the profile of ".$username." and her favorite color is ".$color;
	}
	}
}