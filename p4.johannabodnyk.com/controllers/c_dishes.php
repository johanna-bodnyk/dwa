<?php

class dishes_controller extends base_controller {

	public function __construct() {
	
		parent::__construct();

		# Authenticate
		if(!$this->user) {
			Router::redirect('/');
			return false;
		}

	}


/*-------------------------------------------------------------------------------------------------

	/dishes/index redirects to dish stream page
	
-------------------------------------------------------------------------------------------------*/		
	public function index () {
		Router::redirect('/dishes/stream'); 
	}


/*-------------------------------------------------------------------------------------------------

	Add dish form
	
-------------------------------------------------------------------------------------------------*/	
	public function add ($error = NULL) {
	
		# Set up the view
		$this->template->content = View::instance("v_dishes_add");
		$this->template->title = "Add a dish";

		# Check for error variable and set error message accordingly
		# CHANGE TO JS VALIDATION!!!!!!!!!
		if ($error == "error") {
			$message = "<p class='message error'>You forgot to type something!</p>";
		}
		else {
			$message = "";
		}

		# Send necessary data/variables to the view
		$this->template->content->message = $message;
		
		# Set variable for "current" navigation style
		$this->template->nav = "dishes";
		
		# Render the view
		echo $this->template;
	}
	
/*-------------------------------------------------------------------------------------------------

	Add dish form processing
	
-------------------------------------------------------------------------------------------------*/		
	public function p_add() {
		
		# ADD JS ERROR CHECKING
		
		# Add dish to DB with created and modified set to now	
		$_POST['created'] = Time::now();
		$_POST['modified'] = Time::now();
		$_POST['user_id'] = $this->user->user_id;
		DB::instance(DB_NAME)->insert('dishes', $_POST);
		Router::redirect('/dishes/stream/yours');
		
	}
	
/*-------------------------------------------------------------------------------------------------

	View dish page
	
-------------------------------------------------------------------------------------------------*/	
	public function view ($dish_id) {
	
		# Set up the view
		$this->template->content = View::instance("v_dishes_view");
		
		# ADD ERROR CHECKING TO MAKE SURE AN ID WAS SUBMITTED
		
		$q = "SELECT *
			FROM dishes
			WHERE dish_id = ".$dish_id;
		
		$dish = DB::instance(DB_NAME)->select_row($q);
		
		# Add display name for dish owner (or "You" if it belongs to current user) to dish array
		if ($dish['user_id'] == $this->user->user_id) {
			$dish['display_name'] = "You";
		}
		else {
			$q = "SELECT display_name
				FROM users
				WHERE users.user_id = ".$dish['user_id'];
	
			$dish['display_name'] = DB::instance(DB_NAME)->select_field($q);
		}
		
		# Convert timestamp to readable dates 
		# (Month XX, XXXX, XX:XXpm)			
		$dish['created'] = date('F j, Y', $dish['created']);
		
		# If there's a source link but no name, use link as name 
		if ($dish['source_name'] == "" && $dish['source_link'] != "") {
			$dish['source_name'] = $dish['source_link'];
		}

		# Remove any remaining empty source name or source link elements		
		if ($dish['source_link'] == "") {
			unset($dish['source_link']);
		}			
		if ($dish['source_name'] == "") {
			unset($dish['source_name']);
		}
		
		# If there's still a source link, build HTML using it as link for source name
		if ($dish['source_link']) {
			$dish['source_name'] = "<a href='".$dish['source_link']."'>".$dish['source_name']."</a>";
		}
		
		$this->template->title = $dish['name'];
	
		# Send necessary data/variables to the view
		$this->template->content->dish = $dish;
	
		# Set variable for "current" navigation style
		$this->template->nav = "dishes";
		
		# Render the view
		echo $this->template;
	}

	
}
		
	
	
	
	
	
	