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

	Content for image upload form controls (iframe)
	
-------------------------------------------------------------------------------------------------*/

	public function upload 
	
/*-------------------------------------------------------------------------------------------------

	Upload image, store temporarily and create thumbnail for 
	display in uploading form
	
-------------------------------------------------------------------------------------------------*/	
	public function p_upload () {
	
/* 		$image = Upload::upload($_FILES, "/temp/", array("jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG"), "profile_image_".$this->user->user_id);
		
		# Render the view
		echo 1; */
		
		echo Debug::dump($_FILES,"Contents of FILES");
		
	}
}