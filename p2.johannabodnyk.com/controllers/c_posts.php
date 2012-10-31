<?php

class posts_controller extends base_controller {

	public function __construct() {
	
		parent::__construct();

		if(!$this->user) {
			Router::redirect('/');
			return false;
		}
	}

	public function index ($post_user_id = NULL) {
		
		if ($post_user_id = NULL) {
			$this->template->title = "Chirpstream";
			
			FIND OUT WHO THIS USER IS FOLLOWING AND BUILD QUERY TO GET THOSE POSTS
		}
		
		else if ($post_user_id = "yours") {
			$this->template->title = "Your Chirps";
		
			BUILD QUERY TO GET USERS OWN POSTS

		}
		
		else {
			$this->template->title = USERS."Chirps";
		
			BUILD QUERY TO GET A SINGLE USERS POSTS

		}
		
		$q = "SELECT 
			users.first_name,
			users.last_name,
			users.user_id,
			posts.content,
			posts.created
			FROM users
			JOIN posts USING(user_id)";

		$posts = DB::instance(DB_NAME)->select_rows($q);	
		
		# Convert timestamps to readable dates (Month XX, XXXX, XX:XXpm)
		foreach ($posts as &$post) {
			$post['created'] = date('F j, Y, g:i a', $post['created']);
		}

		$this->template->content = View::instance("v_posts_index");
		$this->template->content->posts = $posts;
		# Render the view
		echo $this->template;
	
	}
	
	
	public function add () {
		
		# Set up the view
		$this->template->content = View::instance("v_posts_add");
		$this->template->title = "Chirp!";
		
		# Render the view
		echo $this->template;
	}
	
	public function p_add() {
		$_POST['created'] = Time::now();
		$_POST['modified'] = Time::now();
		$_POST['user_id'] = $this->user->user_id;
		print_r($_POST);
		DB::instance(DB_NAME)->insert('posts', $_POST);
		echo "Your post has been added. <a href='/posts/add'>Add another.</a>";
		}
		
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
				$message = "<p class='message'>There are currently no users following you></p>";
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
				$message = "<p class='message'>You are not currently following any users. <a href='/posts/users'>Choose users to follow!</a>";
			}
			else {
				# Set condition to get data only for the users already being followed
				$condition = "IN (".$users_followed.")";
			}
		}
		
		# If $subset = NULL, we want to display all users except the current user	
		if ($subset == NULL) {
			$this->template->title = "All chirpers";
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
		
		# Render the view
		echo $this->template;
		
	}
	
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
	
	public function unfollow($user_id_followed = NULL, $source_page = NULL) {
		
		$where_condition = "WHERE
			user_id = ".$this->user->user_id."
			AND user_id_followed = ".$user_id_followed;
			
		DB::instance(DB_NAME)->delete("users_users", $where_condition);
		
		if ($source_page == "profile") {
			Router::redirect('/users/profile/'.$user_id_followed);
			Router::redirect('/users/profile/'.$user_id_followed);
		}
		else {
			Router::redirect('/posts/users/'.$source_page);
		}
	}
}