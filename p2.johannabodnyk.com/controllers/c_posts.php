<?php

class posts_controller extends base_controller {

	public function __construct() {
	
		parent::__construct();

		if(!$this->user) {
			die("Members only. <a href='/users/login'>Please log in</a>");
		}
	}

	public function index () {
		# Set up the view
		$this->template->content = View::instance("v_posts_index");
		$this->template->title = "All the posts";
		
		$q = "SELECT *
			FROM posts
			JOIN users USING(user_id)";
		
		$posts = DB::instance(DB_NAME)->select_rows($q);
				
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
		
		$q = "SELECT * FROM users";
		
		$users = DB::instance(DB_NAME)->select_rows($q);
				
		$this->template->content->users = $users;
		
		
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