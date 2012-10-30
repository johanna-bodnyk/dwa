		<ul id="utilitynav">
			<li>Welcome, Johanna |</li>
			<li><a href="#">Profile</a> |</li>
			<li><a href="#">Log Out</a></li>
		</ul>
		
		<ul id="mainnav">
			<li id="navitem1"><a href="#">Chirps</a>
				<ul class="subnav">
					<li class="current"><a href="#">Chirpstream</a></li>
					<li><a href="#">Your chirps</a></li>
				</ul>
			</li>
			<li id="navitem2" class="current"><a href="#">Chirpers</a>
				<ul class="subnav">
					<li><a href="#">Chirpers you follow</a></li>
					<li><a href="#">Chirpers following you</a></li>
					<li class="current"><a href="#">All chirpers</a></li>
				</ul>
			</li>
			<li id="navitem3"><a href="#">Chirp!</a>
				<ul class="subnav">
				</ul>
			</li>
				<ul
		</ul>
	</div>
	<div id="main">
	
		<ul id="chirperlist">
			<? foreach($users as $user): ?>
				<li><ul>
					<li class="button-link"><a href="/posts/follow/<?=$user["user_id"]?>">Follow</a></li>
					<li class="username followed"><a href="#"><?=$user['first_name']?> <?=$user['last_name']?></a></li>
				</ul></li>
			<? endforeach; ?>
		</ul>
	</div>