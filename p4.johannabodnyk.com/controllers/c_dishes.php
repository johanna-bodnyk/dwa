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
		
		echo Debug::dump($_POST,"Contents of POST");			

		# Move image files names, if set, out of POST array to process seperately
		if (isset($_POST['images'])) {
			$images = $_POST['images'];
			unset($_POST['images']);
		}
		if (isset($_POST['deleted-images'])) {
			$deleted_images = $_POST['deleted-images'];
			unset($_POST['deleted-images']);
		}
		
		# Set created and modified times for use in dishes and images inserts	
		$created = Time::now();
		$modified = Time::now();
		
		# Add created, modified, and user id to POST array
		$_POST['created'] = $created;
		$_POST['modified'] = $modified;
		$_POST['user_id'] = $this->user->user_id;		
		
		echo Debug::dump($_POST,"Contents of POST");
		
		$dish_id = DB::instance(DB_NAME)->insert('dishes', $_POST);
		
		# If images were submitted, resize and move to uploads folder
		if ($images != "") {
			
			foreach ($images as $file_name) {
				
				# Create new row in photos table and capture image id to use in file names
				$data['created'] = $created;
				$data['modified'] = $modified;
				$data['referent_type'] = "dish";
				$data['referent_id'] = $dish_id;
				
				$image_id = DB::instance(DB_NAME)->insert('images', $data);
				
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
		if ($deleted_images != "") {
			
			foreach ($deleted_images as $file_name) {
				
				# Set variable with temporary preview image file name based on temporary original image file name
				$temp_preview = str_replace("original", "preview", $file_name);
		
				# Delete full size and preview versions
				unlink(APP_PATH."temp/".$file_name);
				unlink(APP_PATH."temp/".$temp_preview);
		
			}
		}
		
		Router::redirect('/dishes/view/'.$dish_id);

	}
	
/*-------------------------------------------------------------------------------------------------

	View dish page
	
-------------------------------------------------------------------------------------------------*/	
	public function view ($dish_id) {
	
		# ADD ERROR CHECKING TO MAKE SURE AN ID WAS SUBMITTED
		
		# Get information for this dish from dishes table
		$q = "SELECT *
			FROM dishes
			WHERE dish_id = ".$dish_id;
		
		$dish = DB::instance(DB_NAME)->select_row($q);
		
		#
		#	IMAGES
		#
		
		# Get images for this dish
		$q = "SELECT image_id
			FROM images
			WHERE referent_type = 'dish'
			AND referent_id = ".$dish_id;
			
		$images = DB::instance(DB_NAME)->select_rows($q);	
		
		#
		# ADDED BY
		#
		
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
				
		#
		# SOURCE
		#
		
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
		
		#
		# DATES (ADDED AND LAST EATEN)
		#
		
		# Add date of last meal featuring this dish to dish array
		$q = "SELECT MAX(meals.meal_date) 
			FROM meals, meals_dishes 
			WHERE meals.meal_id = meals_dishes.meal_id 
			AND meals_dishes.dish_id = ".$dish_id;
		
		$dish['last_date'] = DB::instance(DB_NAME)->select_field($q);		
		
		# Convert timestamps to readable dates 	
		if ($dish['last_date'] != 0) {
			$dish['last_date'] = date('F j, Y', $dish['last_date']);
		}
		$dish['created'] = date('F j, Y', $dish['created']);
		
		#
		# WANTS
		#
				
		# Add number of wants to dish array
		$q = "SELECT count(want_id) 
			FROM wants
			WHERE dish_id = ".$dish_id;
			
		$wants = DB::instance(DB_NAME)->select_field($q);	
		
		$dish['wants'] = $wants;
		
		# Check to see whether current user already wanted dish
		$q = "SELECT want_id
			FROM wants
			WHERE user_id = ".$this->user->user_id."
			AND dish_id = ".$dish_id;
			
		$wanted = DB::instance(DB_NAME)->select_field($q);
		
		# Create and pass HTML for want or unwant buttons accordingly 
				# This is presentation in the controller, but it's so many variables -- the URL, the "on" class, the link text -- that it seemed worth hard-coding here,  and below in the want and unwant functions)
		if (!$wanted) {
			$want_button = '<a class="button" id="want-button" href="/dishes/want/'.$dish_id.'">Add to Want to Cook list</a>';
		}
		else {
			$want_button = '<a class="button on" id="want-button" href="/dishes/unwant/'.$dish_id.'">Remove from Want to Cook list</a>';
		}
		
		#
		# COMMENTS...
		
		$q = 'SELECT c.comment, c.created, u.user_id, u.display_name
			FROM comments c, users u
			WHERE c.user_id = u.user_id
			AND c.referent_type = "dish"
			AND c.referent_id ='.$dish_id;
			
		$dish_comments = DB::instance(DB_NAME)->select_rows($q);
		
		foreach ($dish_comments as &$comment) {
			$comment['created'] = date('F j, Y \a\t g:ia', $comment['created']);
		}
		
		$q = 'SELECT c.comment, c.created, u.user_id, u.display_name, m.meal_id, m.meal_date
			FROM comments c, users u, meals m, meals_dishes md
			WHERE c.referent_id = md.meal_dish_id
			AND md.meal_id = m.meal_id
			AND c.user_id = u.user_id
			AND c.referent_type = "meal_dish"
			AND md.dish_id ='.$dish_id.
			' ORDER BY m.meal_date, m.meal_id, c.created';
		
		$meal_comments = DB::instance(DB_NAME)->select_rows($q);
		
		foreach ($meal_comments as &$comment) {
			$comment['created'] = date('F j, Y \a\t g:ia', $comment['created']);
			$comment['meal_date'] = date('F j, Y', $comment['meal_date']);
		}

		#
		# THIS DISH HAS BEEN COOKED... 
		#
		
		$q = "SELECT m.meal_id, m.meal_date 
			FROM meals m, meals_dishes md 
			WHERE md.meal_id = m.meal_id
			AND md.dish_id = ".$dish_id.
			" AND user_id = ".$this->user->user_id;
			
		$your_meals = DB::instance(DB_NAME)->select_rows($q);
						
		foreach ($your_meals as &$your_meal) {
			$your_meal['meal_date'] = date('F j, Y', $your_meal['meal_date']);
		}
		
		$q = "SELECT m.meal_id, m.meal_date, u.display_name, u.user_id
			FROM meals m, meals_dishes md, users u
			WHERE md.meal_id = m.meal_id
			AND m.user_id = u.user_id
			AND md.dish_id = ".$dish_id.
			" AND m.user_id != ".$this->user->user_id;
			
		$other_meals = DB::instance(DB_NAME)->select_rows($q);
		
		foreach ($other_meals as &$other_meal) {
			$other_meal['meal_date'] = date('F j, Y', $other_meal['meal_date']);
		}

		
		#
		# VIEW SETUP
		#

		# Set up the view
		$this->template->content = View::instance("v_dishes_view");
		
		# Set page title
		$this->template->title = $dish['name'];
	
		# Send necessary data/variables to the view
		$this->template->content->dish_id = $dish_id;
		$this->template->content->dish = $dish;
		$this->template->content->want_button = $want_button;
		$this->template->content->images = $images;
		$this->template->content->dish_comments = $dish_comments;
		$this->template->content->meal_comments = $meal_comments;
		$this->template->content->last_meal_id = NULL;
		$this->template->content->your_meals = $your_meals;
		$this->template->content->other_meals = $other_meals;

		# Set variable for "current" navigation style
		$this->template->nav = "dishes";
		
		echo Debug::dump($meal_comments,"Contents of meal comments");
		
		# Render the view
		echo $this->template;
	}

/*-------------------------------------------------------------------------------------------------

	Add comment
	
-------------------------------------------------------------------------------------------------*/	
	public function add_comment () {
		
		$_POST['created'] = Time::now();
		$_POST['modified'] = Time::now();
		$_POST['user_id'] = $this->user->user_id;

		$comment_id = DB::instance(DB_NAME)->insert('comments', $_POST);
		
		$q = "SELECT c.comment, c.created, u.user_id, u.display_name
			FROM comments c, users u
			WHERE c.user_id = u.user_id
			AND c.comment_id = ".$comment_id;
			
		$comment = DB::instance(DB_NAME)->select_row($q);
		
		$comment['created'] = date('F j, Y \a\t g:ia', $comment['created']);

		$comment_html = '<li class="comment">
							<img class="avatar" src="http://placekitten.com/40/40">
							<span class="comment-user"><a href="/meals/stream/'.$comment['user_id'].'">'.$comment['display_name'].'</a></span>
							<span class="comment-text">'.$comment['comment'].'</span>
							<span class="comment-date"><br>'.$comment['created'].'</span>
						</li>';
		
		echo $comment_html;
	}
	

	
	/*-------------------------------------------------------------------------------------------------

	Add dish to Want to Cook list
	
-------------------------------------------------------------------------------------------------*/	
	public function want ($dish_id) {
		
	
		# Check to make sure user does not already want this dish (to prevent ballot stuffing!)
		$q = "SELECT want_id
			FROM wants
			WHERE dish_id = ".$dish_id."
			AND user_id = ".$this->user->user_id;
			
		$wanted = DB::instance(DB_NAME)->select_field($q);
		
		# If not, sanitize by ensuring $dish_id is numeric, then add new row to wants table		
		if (!$wanted && is_numeric($dish_id)) {
		
			$data['created'] = Time::now();
			$data['dish_id'] = $dish_id;
			$data['user_id'] = $this->user->user_id;
			$wanted = DB::instance(DB_NAME)->insert("wants", $data);
			
			# Get updated number of wants
			$q = "SELECT count(want_id) 
			FROM wants
			WHERE dish_id = ".$dish_id;
			
			$wants = DB::instance(DB_NAME)->select_field($q);

		}	
		
		echo ($wants.'<a class="button on" id="want-button" href="/dishes/unwant/'.$dish_id.'">Remove from Want to Cook list</a>');
		
	}
/*-------------------------------------------------------------------------------------------------

	Remove dish from Want to Cook list
	
-------------------------------------------------------------------------------------------------*/		
	public function unwant ($dish_id) {
	
		# Sanitize by ensuring $dish_id is numeric 
		if (is_numeric($dish_id)) {
			
			$where_condition = "WHERE
				user_id = ".$this->user->user_id."
				AND dish_id = ".$dish_id;
				
			DB::instance(DB_NAME)->delete("wants", $where_condition);
			
			# Get updated number of wants
			$q = "SELECT count(want_id) 
			FROM wants
			WHERE dish_id = ".$dish_id;
			
			$wants = DB::instance(DB_NAME)->select_field($q);

		}	
		
		echo ($wants.'<a class="button" id="want-button" href="/dishes/want/'.$dish_id.'">Add to Want to Cook list</a>');
	
	}
	
}
		
	
	
	
	
	
	