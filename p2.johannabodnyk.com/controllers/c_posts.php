<?php

class posts_controller extends base_controller {

	public function __construct() {
	
		parent::__construct();

		if(!$this->user) {
			Router::redirect('/');
			return false;
		}

	}
	
	#
	# Main posts page is really posts/stream page
	#
	public function index () {
		Router::redirect('/posts/stream'); 
	}
		
	#
	# List of posts -- of all users being followed, one user, or current user
	#
	public function stream ($post_subset = NULL) {
		
		# Set up the view 
		$this->template->content = View::instance("v_posts_stream");

		# By default we'll get posts to display
		$get_posts = TRUE;
		$message = "";


		# If subset variable is "yours" show the current user's posts
		if ($post_subset == "yours") {
			$this->template->title = "Your Chirps";
			$subnav = "your_chirps";
			
			#Set the posts query condition to only get posts with the current user's ID
			$condition = "=".$this->user->user_id;			

		}
		
		# If subset variable is a number, display posts matching that user ID
 		else if (is_numeric($post_subset)) {
			
			# Get the first name for that user for the page title and h2
			$q = "SELECT first_name
				FROM users
				WHERE user_id = ".$post_subset;
			$chirper_name = DB::instance(DB_NAME)->select_field($q);
			$this->template->title = $chirper_name."'s Chirps";
			
			# Pass the first name in for the h2
 			$this->template->content->chirper_name = $chirper_name;
 			$subnav = "";

			#Set the posts query condition to only get posts with that user's ID
			$condition = "=".$post_subset;			
		} 
		
		# Otherwise (if no subset variable is defined or something else is used),
		# show all posts from users the current user is following
		else {
						
			$this->template->title = "Chirpstream";
			$subnav = "chirpstream";

			# Query DB to figure out who the user is following
			$q = "SELECT * 
			FROM users_users
			WHERE user_id = ".$this->user->user_id;
			
			$connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');
			
			# From results of query, create a comma-separated list 
			# of the user ids current user is following
			$users_followed = "";
			
			foreach ($connections as $key => $value) {
				$users_followed .= $key;
				$users_followed .= ",";
			}
			
			# Remove the trailing comma
			$users_followed = rtrim($users_followed, ",");
			
			# If no users are followed yet, don't query the DB for posts,
			# and will display a message instead
			if ($users_followed == "") {
				$get_posts = FALSE;
				$message = "<p class='message'>You are not currently following any users. <a href='/posts/users'>Choose users to follow!</a></p>";
				$posts = "";
			}
			# If users are being followed, set the posts query condition
			# to only get posts with those users' IDs
			else {
 				$condition = "IN (".$users_followed.")";			
			}
		}
		
		# Unless we set get_posts to FALSE 
		# (because there are no users being followed),
		# query the DB to get the posts to be displayed
		if ($get_posts == TRUE) {
			# Build query for users using the condition
			$q = "SELECT 
			users.first_name,
			users.last_name,
			users.user_id,
			users.thumb_image,
			users.alt_text,
			posts.post_id,
			posts.content,
			posts.created
			FROM users
			JOIN posts USING(user_id)
			WHERE user_id ".$condition." 
			ORDER BY created DESC
			";
			
			$posts = DB::instance(DB_NAME)->select_rows($q);
	
			# If we're going to the current user's list of chirps
			# and the posts array is empty (they haven't chirped yet), 
			# display a message instead of the list of chirps
			if (($post_subset == "yours") && (!$posts)) {
				$message = "<p class='message'>You haven't chirped yet! <a href='/posts/add'>Say something!</a>";
				$posts = "";
			}
			else {
				# Convert timestamps to readable dates (Month XX, XXXX, XX:XXpm)
				foreach ($posts as &$post) {
					$post['created'] = date('F j, Y, g:i a', $post['created']);
				}
			}
		}
	
		
		# Set variables for "current" navigation styles
		$this->template->nav = "chirps";
		$this->template->subnav = $subnav;
		
		# Set up the view
		$this->template->content->posts = $posts;
		$this->template->content->message = $message;

		# Render the view
		echo $this->template;
	}

	#
	# Add post form
	#
	public function add ($error = NULL) {
		
		# Check for error variable and set error message accordingly
		if ($error == "error") {
			$message = "<p class='message error'>You forgot to type something!</a></p>";
		}
		else {
			$message = "";
		}

		# Set up the view
		$this->template->content = View::instance("v_posts_add");
		$this->template->title = "Chirp!";
		$this->template->content->message = $message;
		
		# Set variables for "current" navigation styles
		$this->template->nav = "chirp";
		$this->template->subnav = "";
		
		# Render the view
		echo $this->template;
	}
	
	#
	# Add post form processing
	#
	public function p_add() {
		
		# Check to see if content was entered
		# If not, redirect to add post page with variable to trigger error message
		if ($_POST['content'] == "") {
			Router::redirect('/posts/add/error');
		}
		
		# If so, add to DB with created and modified set to now
		else {
			$_POST['created'] = Time::now();
			$_POST['modified'] = Time::now();
			$_POST['user_id'] = $this->user->user_id;
			DB::instance(DB_NAME)->insert('posts', $_POST);
			Router::redirect('/posts/stream/yours');
		}
	}	
		
	#
	# Delete a post
	#
	public function delete($post_id = NULL) {
		
		# Sanitize by ensuring $post_id is numeric 
		if (is_numeric($post_id)) {
			
			# Ensure to be deleted matches on post_id AND user_id of current user
			# to prevent users from altering URL to delete other people's posts
			$where_condition = "WHERE
				user_id = ".$this->user->user_id."
				AND post_id = ".$post_id;
				
			DB::instance(DB_NAME)->delete("posts", $where_condition);
			
			Router::redirect('/posts/stream/yours');
		}
		else {
			Router::redirect('/posts/stream/yours');
			return false;
		}
		
	}

	#
	# List of users -- all, being followed, and following current user
	#		
	public function users($subset = NULL) {
		
		# Set up the view
		$this->template->content = View::instance("v_posts_users");
		
		$get_users = TRUE;
		
		# Get the list of user_ids this user is following,  
		# for the view and to check against if $subset = "followed"
		$q = "SELECT * 
		FROM users_users
		WHERE user_id = ".$this->user->user_id;
	
		$connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');
		
		# If $subset = following_you, display only those 
		# users who are following the current user
		if ($subset == "following_you") {

			$this->template->title = "Chirpers following you";
			$subnav = "following_you";

			# Get the user_ids of the users following the current user
			$q = "SELECT user_id 
			FROM users_users
			WHERE user_id_followed = ".$this->user->user_id;

			$array = DB::instance(DB_NAME)->select_rows($q);
			
			# Create a comma separated list of those user_ids
			$users_following_you = "";
			
 			foreach ($array as $user) {
				$users_following_you .= $user['user_id'];
				$users_following_you .= ",";
			}
			
			# Remove the trailing comma
			$users_following_you = rtrim($users_following_you, ",");
			
			# If no one is following current user, don't query the DB for users
			if ($users_following_you == "") {
				$get_users = FALSE;
				$message = "<p class='message'>There are currently no users following you.</p>";
			}
			else {
				# Set condition to get data only for those users who are 
				# following the current user
				$condition = "IN (".$users_following_you.")";
			}
			
		}
			
		# If $subset = followed, display only those 
		# users who the current user is already following
		elseif ($subset == "followed") {
			
			$this->template->title = "Chirpers you follow";
			$subnav = "followed";

			# Create a comma separated list of the user_ids of
			# the users already followed by current user
			$users_followed = "";
			
 			foreach ($connections as $key => $value) {
				$users_followed .= $key;
				$users_followed .= ",";
			}
			
			# Remove the trailing comma
			$users_followed = rtrim($users_followed, ",");
			
			# If no users are followed yet, don't query the DB for users
			if ($users_followed == "") {
				$get_users = FALSE;
				$message = "<p class='message'>You are not currently following any users. <a href='/posts/users'>Choose users to follow!</a></p>";
			}
			else {
				# Set condition to get data only for the users already being followed
				$condition = "IN (".$users_followed.")";
			}
		}
		
		# If $subset = NULL (or anything else) display all users 
		# except the current user	
		else {
			$this->template->title = "All chirpers";
			$subnav = "all";
			$condition = "<> ".$this->user->user_id;			
		}
		
		# Unless the $subset is following_you or followed 
		# and there are no users to display
		# (in which case $get_users should be set to FALSE),
		# build query for $users using condition based on subset
		if ($get_users == TRUE) {
			$q = "SELECT 
				user_id,
				first_name,
				last_name
				FROM `users` 
				WHERE user_id ".$condition;
			$users = DB::instance(DB_NAME)->select_rows($q);
 			$message = "";		
 		}
		else {
			$users = "";
		}
		
		$this->template->content->users = $users;
		$this->template->content->connections = $connections;
		$this->template->content->subset = $subset;
 		$this->template->content->message = $message;
 		
		# Set variables for "current" navigation styles
		$this->template->nav = "chirpers";
		$this->template->subnav = $subnav;		
		
		# Render the view
		echo $this->template;
		
	}
	
	#
	# Follow a user
	#
	public function follow($user_id_followed = NULL, $source_page = NULL) {
		
		# Sanitize by ensuring $user_id_followed is numeric 
		if (is_numeric($user_id_followed)) {
			$data['created'] = Time::now();
			$data['user_id'] = $this->user->user_id;
			$data['user_id_followed'] = $user_id_followed;
			
			DB::instance(DB_NAME)->insert("users_users", $data);
		}
		else {
			Router::redirect('/posts/users/'.$source_page);
			return false;
		}
		
		if ($source_page == "profile") {
			Router::redirect('/users/profile/'.$user_id_followed);
		}
		else {
			Router::redirect('/posts/users/'.$source_page);
		}
	}
	
	#
	# Stop following a user
	#
	public function unfollow($user_id_followed = NULL, $source_page = NULL) {

		# Sanitize by ensuring $user_id_followed is numeric 
		if (is_numeric($user_id_followed)) {
			$where_condition = "WHERE
				user_id = ".$this->user->user_id."
				AND user_id_followed = ".$user_id_followed;
				
			DB::instance(DB_NAME)->delete("users_users", $where_condition);
		}
		else {
			Router::redirect('/posts/users/'.$source_page);
			return false;
		}
		
		if ($source_page == "profile") {
			Router::redirect('/users/profile/'.$user_id_followed);
		}
		else {
			Router::redirect('/posts/users/'.$source_page);
		}
	}
}