<?php

class posts_controller extends base_controller {

	public function __construct() {
	
		parent::__construct();

		if(!$this->user) {
			Router::redirect('/');
			return false;
		}
	}

	public function index () {
		$this->template->content = View::instance("v_posts_index");
		$this->template->title = "Chirper | Your Chirpstream";
		
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

		$this->template->content->posts = $posts;
		
		# Render the view
		echo $this->template;
	
	}
	
	
	public function add () {
		
		# Set up the view
		$this->template->content = View::instance("v_posts_add");
		$this->template->title = "Add a new post";
		
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
		
	public function users() {
		# Set up the view
		$this->template->content = View::instance("v_posts_users");
		$this->template->title = "Choose who to follow";
		
		$q = "SELECT 
			user_id,
			first_name,
			last_name
			FROM `users` 
			WHERE user_id <> ".$this->user->user_id;
		
		$users = DB::instance(DB_NAME)->select_rows($q);
		
			$q = "SELECT * 
			FROM users_users
			WHERE user_id = ".$this->user->user_id;
		
			$connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');
				
		$this->template->content->users = $users;
		$this->template->content->connections = $connections;
		# Render the view
		echo $this->template;
	}
	
	public function follow($user_id_followed = NULL) {
		$data['created'] = Time::now();
		$data['user_id'] = $this->user->user_id;
		$data['user_id_followed'] = $user_id_followed;
		
		DB::instance(DB_NAME)->insert("users_users", $data);
		
		Router::redirect('/posts/users');
	}
	
}