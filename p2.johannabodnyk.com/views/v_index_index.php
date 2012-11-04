
		<!-- Welcome message and sign-up button (left column) -->
		<div id="welcome">
			<h2>Welcome!</h2>
			<p>Chirper is a fun and easy way to let everyone know what you are doing at all times, whether or not they care! Create an account to get started right now!</p>
			<p class="button-link"><a href="/users/signup">Sign up!</a>
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