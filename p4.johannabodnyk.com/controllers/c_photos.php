<?php

class photos_controller extends base_controller {

	public function __construct() {
		parent::__construct();
	}

	
/*-------------------------------------------------------------------------------------------------

	Content for image upload form controls (iframe)
	
-------------------------------------------------------------------------------------------------*/

	public function upload ($id) {

		echo '<!DOCTYPE html>
			<html>
				<head>
					<link rel="stylesheet" type="text/css" href="/style.css" media="all" />
				</head>	

				<body id="iframe" seamless>
						
						<form name="upload-photo" action="/photos/p_upload" method="POST" enctype="multipart/form-data">
						
							<input type="file" id="image-input-'.$id.'">
							<input type="submit" class="image-upload" id="'.$id.'" value="Upload">

						</form>
						
				</body>
			</html>';

	}
	
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