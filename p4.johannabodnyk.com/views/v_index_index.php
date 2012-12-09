
		<!-- Welcome message and sign-up button (left column) -->
		<div id="welcome">
			<h2>Welcome!</h2>
			<p>Do you love food? Keep track of your favorite meals and share with your friends using Yumbook. You can post photos and notes about special dishes, and get inspiration from friends for meal planning. Join today for a yummier world!</p>
			<p><a class="button big" href="/users/signup">Sign up!</a>
		</div>
		
		<!-- Login form (right column) -->
		<div id="login">
			<h2>Sign in!</h2>
			<?=$message?>
			<form name="login" action="/users/p_login" method="POST">
							
				<label for="email">Email address</label>
				<input type="email" name="email" id="email">
				
				<label for="password">Password</label>
				<input type="password" name="password" id="password">
							
				<input type="submit" value="Sign in" id="submit-button">
			</form>
			
		</div>
				
		<div class="clear"></div>