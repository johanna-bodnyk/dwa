<?php

class meals_controller extends base_controller {

	public function __construct() {
	
		parent::__construct();

		# Authenticate
		if(!$this->user) {
			Router::redirect('/');
			return false;
		}

	}


/*-------------------------------------------------------------------------------------------------

	/dishes/index redirects to meal stream page
	
-------------------------------------------------------------------------------------------------*/		
	public function index () {
		Router::redirect('/meals/stream'); 
	}


/*-------------------------------------------------------------------------------------------------

	Add meal form
	
-------------------------------------------------------------------------------------------------*/	
	public function add ($meal_id = NULL) {
	
		# Set up the view
		$this->template->content = View::instance("v_meals_add");
		$this->template->title = "Add a meal";

		if ($meal_id != "") {
			$q = "SELECT meal_date
				FROM meals
				WHERE meal_id = ".$meal_id;
			$meal_date = DB::instance(DB_NAME)->select_field($q);
			$meal_date = date('F j, Y \a\t g:ia', $meal_date);
			$this->template->content->meal_date = $meal_date;
		}
		
		# Send necessary data/variables to the view
		$this->template->content->meal_id = $meal_id;
				
		# Set variable for "current" navigation style
		$this->template->nav = "meals";
		
		# Render the view
		echo $this->template;
	}
	
	public function dish_list() {
	
		# SANITIZE QUERY STRING !!!!!!!!!!!!
		
		$q = "SELECT name AS label, 
			dish_id AS value
			FROM dishes
			WHERE name LIKE '%".$_GET["term"]."%'";
			
		$dish_list = DB::instance(DB_NAME)->select_rows($q);
		
		echo json_encode($dish_list);
	
	}
	
/*-------------------------------------------------------------------------------------------------

	Add dish form processing
	
-------------------------------------------------------------------------------------------------*/		
	public function p_add($add_another = NULL) {
		
		# ADD JS ERROR CHECKING
		
		echo Debug::dump($_POST,"Contents of POST");

		# Set created and modified times for use in various inserts	
		$created = Time::now();
		$modified = Time::now();
		
		# Check to see if meal id was passed (from "add another" version for add meal form)
		if (isset($_POST['meal_id'])) {
			$meal_id = $_POST['meal_id'];
		}
		
		# If not, create new meal
		else {
			# Create array with user id and created and modified times
			$meal['user_id'] = $this->user->user_id;	
			$meal['created'] = $created;
			$meal['modified'] = $modified;
		
			# Add meal date (date of meal, rather than when db record was created) to meal array as timestamp
			$meal['meal_date'] = strtotime($_POST['date']." ".$_POST['time']);
			
			echo Debug::dump($meal,"Contents of meal");
			
			$meal_id = DB::instance(DB_NAME)->insert('meals', $meal);
		}
		
		# Create new dish, if needed, and set dish_id with id of new dish
		if ($_POST['dish_id'] == 0) {
		
			$dish['created'] = $created;
			$dish['modified'] = $modified;		
			$dish['user_id'] = $this->user->user_id;
			$dish['name'] = $_POST['dish'];
			$dish['source_type'] = $_POST['source_type'];
			$dish['source_name'] = $_POST['source_name'];
			$dish['source_link'] = $_POST['source_link'];
			$dish['note'] = $_POST['dish-note'];
			
			echo Debug::dump($dish,"Contents of dish");
			
			$meal_dish['dish_id'] = DB::instance(DB_NAME)->insert('dishes', $dish);
		}
		else {
			$meal_dish['dish_id'] = $_POST['dish_id'];
		}		
		
		$meal_dish['created'] = $created;
		$meal_dish['note'] = $_POST['note'];
		$meal_dish['meal_id'] = $meal_id;
		
		echo Debug::dump($meal_dish,"Contents of meal_dish");
		
		$meal_dish_id = DB::instance(DB_NAME)->insert('meals_dishes', $meal_dish);
			
		# If images were submitted, resize and move to uploads folder
		if (isset($_POST['images'])) {
			
			$images = $_POST['images'];
			
			foreach ($images as $file_name) {
				
				# Create new row in photos table and capture image id to use in file names
				$image['created'] = $created;
				$image['modified'] = $modified;
				$image['referent_type'] = "meal_dish";
				$image['referent_id'] = $meal_dish_id;
				
				$image_id = DB::instance(DB_NAME)->insert('images', $image);
				
				# Build file names for various sizes of this image
				$full = "image-".$image_id."-full.jpg";
				$stream = "image-".$image_id."-402x300.jpg";
				$preview = "image-".$image_id."-125x125.jpg";
				$thumb = "image-".$image_id."-93x93.jpg";
				
				# Generate temporary preview image file name based on temporary original image file name
				$temp_preview = str_replace("original", "preview", $file_name);
				
				# Create 402x300 and 93x93 versions of original and save in uploads folder
				$imgObj = new Image(APP_PATH."temp/".$file_name);
				
				$imgObj->resize(402,300,"crop");
				$imgObj->save_image(APP_PATH."uploads/".$stream, 100);	

				$imgObj->resize(93,93,"crop");
				$imgObj->save_image(APP_PATH."uploads/".$thumb, 100);
				
				# Rename and move full size and preview versions to uploads folder
				rename(APP_PATH."temp/".$file_name, APP_PATH."uploads/".$full);
				rename(APP_PATH."temp/".$temp_preview, APP_PATH."uploads/".$preview);
				
			}
			
		}
		
		# If deleted images were submitted, remove from temp folder
		if (isset($_POST['deleted-images'])) {
			
			$deleted_images = $_POST['deleted-images'];
			
			foreach ($deleted_images as $file_name) {
				
				# Set variable with temporary preview image file name based on temporary original image file name
				$temp_preview = str_replace("original", "preview", $file_name);
		
				# Delete full size and preview versions
				unlink(APP_PATH."temp/".$file_name);
				unlink(APP_PATH."temp/".$temp_preview);
		
			}
		}
		
		if (isset($_POST['save-add-submit'])) {
			Router::redirect('/meals/add/'.$meal_id);
		}
		else {
			Router::redirect('/meals/stream/yours');
		}

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
		
		$q = "SELECT image_id
			FROM images
			WHERE referent_type = 'dish'
			AND referent_id = ".$dish_id;
			
		$images = DB::instance(DB_NAME)->select_rows($q);	
		
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
		if (isset($dish['source_link'])) {
			$dish['source_name'] = "<a href='".$dish['source_link']."'>".$dish['source_name']."</a>";
		}
		
		# Set page title
		$this->template->title = $dish['name'];
	
		# Send necessary data/variables to the view
		$this->template->content->dish = $dish;
		$this->template->content->images = $images;
	
		# Set variable for "current" navigation style
		$this->template->nav = "dishes";
		
		# Render the view
		echo $this->template;
	}

	
}
		
	
	
	
	
	
	