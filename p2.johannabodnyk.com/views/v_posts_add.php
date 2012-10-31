<ul id="utilitynav">
			<li>Welcome, Johanna |</li>
			<li class="current"><a href="#">Profile</a> |</li>
			<li><a href="#">Log Out</a></li>
		</ul>
		
		<ul id="mainnav">
			<li id="navitem1"><a href="#">Chirps</a>
				<ul class="subnav">
					<li class="current"><a href="#">Chirpstream</a></li>
					<li><a href="#">Your chirps</a></li>
				</ul>
			</li>
			<li id="navitem2"><a href="#">Chirpers</a>
				<ul class="subnav">
					<li><a href="#">Chirpers you follow</a></li>
					<li><a href="#">Chirpers following you</a></li>
					<li><a href="#">All chirpers</a></li>
				</ul>
			</li>
			<li id="navitem3" class="current"><a href="#">Chirp!</a>
				<ul class="subnav">
				</ul>
			</li>
				<ul
		</ul>
	</div>
	<div id="main">
	
		<h2>Say something!</h2>
		
		<form name="add-chirp" action="/posts/p_add" method="POST">
			
			<textarea name="content" rows="5" cols="82"></textarea>	
			
			<input type="submit" value="Chirp!" id="submit-button">
		</form>
		
 		<div class=clear></div>

	</div>