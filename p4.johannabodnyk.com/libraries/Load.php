<?php

//Frequently used DB queries -- for meals, dishes, comments

class Load {

	public static function meal ($meal_id, $user_id) {
	
		# Get array of meal_dish ids for the meal
		$q = "SELECT meal_dish_id
			FROM meals_dishes
			WHERE meal_id = ".$meal_id;
			
		$meal_dish_ids = DB::instance(DB_NAME)->select_array($q, 'meal_dish_id');
		
		$meal_dishes = "";
		
		$count = 0;
		
		foreach ($meal_dish_ids as $meal_dish_id => $value) {
			
			$q = "SELECT d.name, d.dish_id, md.note, u.display_name, u.user_id, m.meal_date
				FROM dishes d, meals_dishes md, users u, meals m
				WHERE d.dish_id = md.dish_id
				AND md.meal_id = m.meal_id
				AND m.user_id = u.user_id
				AND md.meal_dish_id = ".$meal_dish_id;
			
			$meal_dishes[$meal_dish_id] = DB::instance(DB_NAME)->select_row($q);	

			$meal_dishes[$meal_dish_id]['meal_date'] = date('F j, Y \a\t g:ia', $meal_dishes[$meal_dish_id]['meal_date']);			
			
			# Add images to array
			$meal_dishes[$meal_dish_id]['images'] = Load::dish_images('meal_dish', $meal_dish_id);
			
 			#
			# WANTS
			#
					
			# Add number of wants to array
			$q = "SELECT count(want_id) 
				FROM wants
				WHERE dish_id = ".$meal_dishes[$meal_dish_id]['dish_id'];
				
			$meal_dishes[$meal_dish_id]['wants'] = DB::instance(DB_NAME)->select_field($q);
			
			# Check to see whether current user already wanted dish
			$q = "SELECT want_id
				FROM wants
				WHERE user_id = ".$user_id."
				AND dish_id = ".$meal_dishes[$meal_dish_id]['dish_id'];
				
			$meal_dishes[$meal_dish_id]['wanted'] = DB::instance(DB_NAME)->select_field($q);
			
			# Comment count (for comments on this particular dish in this meal)
			$q = "SELECT count(comment_id) 
				FROM comments
				WHERE referent_type = 'meal_dish' 
				AND referent_id = ".$meal_dish_id;
				
			$meal_dishes[$meal_dish_id]['comment_count'] = DB::instance(DB_NAME)->select_field($q);
			
			# Comments (for this particular dish in this meal)
			$meal_dishes[$meal_dish_id]['comments'] = Load::meal_comments("unused", $meal_dish_id);
			
			# Add count to array
			$meal_dishes[$meal_dish_id]['dish_number'] = $count;
		
			$count ++;
		
		}
		
		return $meal_dishes;
		
	}
	
	
	
	
	
	public static function dish ($dish_id, $user_id) {
	
		$q = "SELECT *
			FROM dishes
			WHERE dish_id = ".$dish_id;
			
		$dish = DB::instance(DB_NAME)->select_row($q);
		
		#
		# ADDED BY
		#
		
		# Add display name for dish owner to dish array
		# (or "You" if it belongs to current user)
		if ($dish['user_id'] == $user_id) {
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
			
		$dish['wants'] = DB::instance(DB_NAME)->select_field($q);
		
		# Check to see whether current user already wanted dish
		$q = "SELECT want_id
			FROM wants
			WHERE user_id = ".$user_id."
			AND dish_id = ".$dish_id;
			
		$dish['wanted'] = DB::instance(DB_NAME)->select_field($q);
				
		return $dish;
	
	}
	
	
	public static function dish_images ($referent_type, $referent_id) {
	
		$q = "SELECT image_id
			FROM images
			WHERE referent_type = '".$referent_type."'
			AND referent_id = ".$referent_id;
			
		return DB::instance(DB_NAME)->select_rows($q);	
	
	}
	
	public static function dish_comments ($dish_id) {
	
		$q = 'SELECT c.comment, c.created, u.user_id, u.display_name, u.profile_image
			FROM comments c, users u
			WHERE c.user_id = u.user_id
			AND c.referent_type = "dish"
			AND c.referent_id ='.$dish_id;
			
		$dish_comments = DB::instance(DB_NAME)->select_rows($q);
		
		foreach ($dish_comments as &$comment) {
			$comment['created'] = date('F j, Y \a\t g:ia', $comment['created']);
		}
		
		return $dish_comments;
		
	}
	
	public static function meal_comments ($dish_id, $meal_dish_id) {
	
		$q = 'SELECT c.comment, c.created, u.user_id, u.display_name, u.profile_image, m.meal_id, m.meal_date
			FROM comments c, users u, meals m, meals_dishes md
			WHERE c.referent_id = md.meal_dish_id
			AND md.meal_id = m.meal_id
			AND c.user_id = u.user_id
			AND c.referent_type = "meal_dish"';
			
		if ($meal_dish_id != "all") {
			$q = $q." AND c.referent_id = ".$meal_dish_id;
		}
		else {
			$q = $q." AND md.dish_id = ".$dish_id;
		}
		
		$q = $q.' ORDER BY m.meal_date, m.meal_id, c.created';
		
		$meal_comments = DB::instance(DB_NAME)->select_rows($q);
		
		foreach ($meal_comments as &$comment) {
			$comment['created'] = date('F j, Y \a\t g:ia', $comment['created']);
			$comment['meal_date'] = date('F j, Y', $comment['meal_date']);
		}
		
		return $meal_comments;
	
	}



}