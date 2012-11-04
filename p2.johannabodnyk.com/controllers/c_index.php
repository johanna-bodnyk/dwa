<?php

class index_controller extends base_controller {

	public function __construct() {
		parent::__construct();
	} 
	
	/*-------------------------------------------------------------------------------------------------
	Access via http://yourapp.com/index/index/
	-------------------------------------------------------------------------------------------------*/
	public function index($error = NULL) {

		# If user is not logged in, load the homepage
		if (!$this->user) {
			
			# Set up the view 
			$this->template->content = View::instance('v_index_index');
			$this->template->title = "Welcome to Chirper";
	
			# Check whether homepage is reloading after a failed login attempt
			# If so, send an error message to the view
			if ($error == "error") {
				$message = "<p class='message error'>Login failed. Please check your email and password and try again.</p>";
			}
			else {
				$message = "";
			}
			
			# Send necessary data/variables to the view
			$this->template->content->message = $message;

			# Render the view
			echo $this->template;
		
		}
		
		# If user is already logged in, redirect to /posts/stream (landing page)
		else {
			Router::redirect("/posts/stream");
		}

	}
		
}
