<?php

class photos_controller extends base_controller {

	public function __construct() {
		parent::__construct();
	}

	
/*-------------------------------------------------------------------------------------------------

	Content for image upload form controls (iframe)
	
-------------------------------------------------------------------------------------------------*/

	public function upload ($id) {

	$this->iframe_template->content = View::instance('v_photos_upload');
	
	$this->iframe_template->content->id = $id;
	
	echo $this->iframe_template;

	}
	

/*-------------------------------------------------------------------------------------------------

	Upload image, store temporarily and create thumbnail for 
	display in uploading form
	
-------------------------------------------------------------------------------------------------*/	
	public function p_upload () {
	
		$image_id = $_POST['photo-id'];
/* 		echo Debug::dump($_FILES,"Contents of FILES"); */
		$image = Upload::upload($_FILES, "/temp/", array("jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG"), "temp-original-".$image_id);
		$imgObj = new Image(APP_PATH."temp/".$image);
		
		# Resize and save thumbnail version of image
		$file_parts = pathinfo($image);
		$preview = "temp-preview-".$image_id.".".$file_parts['extension'];
		$imgObj->resize(125,125,"crop");
		$imgObj->save_image(APP_PATH."temp/".$preview, 100);

		
		$this->iframe_template->content = View::instance('v_photos_p_upload');
 		$this->iframe_template->content->original = $image; 	
 		$this->iframe_template->content->preview = $preview; 	
 		$this->iframe_template->content->image_id = $image_id; 	
		
		# Load client files
		// $client_files = Array(
						// "/js/image-iframe.js"
	                    // );
	    
	    // $this->iframe_template->client_files = Utils::load_client_files($client_files);  

		echo $this->iframe_template;

	}
}