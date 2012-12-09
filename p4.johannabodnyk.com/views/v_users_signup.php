		<h2>Sign Up</h2>
		
		<!-- Sign up form -->
		<form name="signup" action="/users/p_signup" method="POST">
			
			<?=$message?>			
			
			<label for="first_name">First name</label>
			<input type="text" name="first_name" id="first_name">
		
			<label for="last_name">Last name</label>
			<input type="text" name="last_name" id="last_name">
			
			<label for="email">Email address</label>
			<input type="email" name="email" id="email">
			
			<label for="password">Password</label>
			<input type="password" name="password" id="password">
						
			<label for="password_check">Reenter password</label>
			<input type="password" name="password_check" id="password_check">
			
			<input type="submit" value="Create account" id="submit-button">
		</form>
		
 		<div class=clear></div>
