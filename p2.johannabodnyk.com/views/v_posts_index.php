		<ul id="utilitynav">
			<li>Welcome, <?=$user->first_name?> |</li>
			<li><a href="#">Profile</a> |</li>
			<li><a href="users/logout">Log Out</a></li>
		</ul>
		
		<ul id="mainnav">
			<li id="navitem1" class="current"><a href="#">Chirps</a>
				<ul class="subnav">
					<li class="current"><a href="/posts">Chirpstream</a></li>
					<li><a href="#">Your chirps</a></li>
				</ul>
			</li>
			<li id="navitem2"><a href="#">Chirpers</a>
				<ul class="subnav">
					<li class="current"><a href="#">Chirpers you follow</a></li>
					<li><a href="#">Chirpers following you</a></li>
					<li><a href="#">All chirpers</a></li>
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
	
		<ul id="chirplist">
		<? foreach($posts as $post): ?>
			<li>
				<ul>
					<li class="avatar"><a href="users/profile/<?=$post['user_id']?>"><img src="/matthew.jpg"></a></li>
					<li class="username"><a href="users/profile/<?=$post['user_id']?>"><?=$post['first_name']?> <?=$post['last_name']?></a></li>
					<li class="chirp"><?=$post['content']?></li>
					<li class="date"><?=$post['created']?><div class="clear"></div></li>
				</ul>
			</li>
		<? endforeach; ?>
		
		</ul>
	</div>