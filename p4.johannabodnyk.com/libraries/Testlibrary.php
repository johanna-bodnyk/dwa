<?php

class Testlibrary {
	
	public static function test($variable) {
		
		$q = "SELECT name
			FROM dishes
			WHERE dish_id = ".$variable;
			
		$name = DB::instance(DB_NAME)->select_field($q);
		
		return "The dish name for dish id ".$variable." is ".$name." and your user id is ".$user->user_id;
	}

}