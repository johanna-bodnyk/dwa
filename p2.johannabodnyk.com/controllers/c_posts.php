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
	# Main posts page should go to posts/stream page (need variables for stream page, 
	# otherwise it could be the index)
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

		# If no subset variable is defined, show all posts 
		# from users the current user is following
		if ($post_subset == NULL) {
						
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
			
			# If no users are followed yet, we won't query the DB for posts,
			# and will display a message instead
			if ($users_followed == "") {
				$get_posts = FALSE;
				$message = "<p class='message'>You are not currently following any users. <a href='/posts/users'>Choose users to follow!</a>";
				$posts = "";
			}
			# If users are being followed, we'll set the posts query condition
			# to only get posts with those users' IDs
			else {
 				$condition = "IN (".$users_followed.")";			
			}
		}
			

		# If subset variable is "yours" show the current user's posts
		else if ($post_subset == "yours") {
			$this->template->title = "Your Chirps";
			$subnav = "your_chirps";
			
			#Set the posts query condition to only get posts with the current user's ID
			$condition = "=".$this->user->user_id;			

		}
		
		# Otherwise -- if there's a subset variable and it's not "yours" --
		# it should be a user ID, so we'll display posts matching that ID
 		else {
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
		
		# Unless we set get_posts to FALSE (because there are no users being followed),
		# query the DB to get the posts to be displayed
		if ($get_posts == TRUE) {
			# Build query for users using the condition
			$q = "SELECT 
			users.first_name,
			users.last_name,
			users.user_id,
			users.thumb_image,
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
	public function add () {
		
		# Set variables for "current" navigation styles
		$this->template->nav = "chirp";
		$this->template->subnav = "";
		
		# Set up the view
		$this->template->content = View::instance("v_posts_add");
		$this->template->title = "Chirp!";
		
		# Render the view
		echo $this->template;
	}
	
	#
	# Add post form processing
	#
	public function p_add() {
		$_POST['created'] = Time::now();
		$_POST['modified'] = Time::now();
		$_POST['user_id'] = $this->user->user_id;
		DB::instance(DB_NAME)->insert('posts', $_POST);
		Router::redirect('/posts/stream/yours');
		}
		
	#
	# Delete a post
	#
	public function delete($post_id = NULL) {
		
		# Post to be deleted must match on post_id and user_id of current user
		# to prevent users from altering URL to delete other people's posts
		$where_condition = "WHERE
			user_id = ".$this->user->user_id."
			AND post_id = ".$post_id;
			
		DB::instance(DB_NAME)->delete("posts", $where_condition);
		
		Router::redirect('/posts/stream/yours');
	}

	#
	# List of users -- all, being followed, and following current user
	#		
	public function users($subset = NULL) {
		
		# Set up the view
		$this->template->content = View::instance("v_posts_users");
		
		$get_users = TRUE;
		
		# Get the list of user_ids this user is following,  
		# for the view and in case $subset = "followed"
		$q = "SELECT * 
		FROM users_users
		WHERE user_id = ".$this->user->user_id;
	
		$connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');
		
		# If $subset = following_you, we want to display only those 
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
			
		# If $subset = followed, we want to display only those 
		# users who the current user is already following
		if ($subset == "followed") {
			
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
		
		# If $subset = NULL, we want to display all users except the current user	
		if ($subset == NULL) {
			$this->template->title = "All chirpers";
			$subnav = "all";
			$condition = "<> ".$this->user->user_id;			
		}
		
		if ($get_users == TRUE) {
			# Build query for users using condition based on subset
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
		$data['created'] = Time::now();
		$data['user_id'] = $this->user->user_id;
		$data['user_id_followed'] = $user_id_followed;
		
		DB::instance(DB_NAME)->insert("users_users", $data);
		
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
		
		$where_condition = "WHERE
			user_id = ".$this->user->user_id."
			AND user_id_followed = ".$user_id_followed;
			
		DB::instance(DB_NAME)->delete("users_users", $where_condition);
		
		if ($source_page == "profile") {
			Router::redirect('/users/profile/'.$user_id_followed);
		}
		else {
			Router::redirect('/posts/users/'.$source_page);
		}
	}
}