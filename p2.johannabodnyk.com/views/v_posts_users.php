		<ul id="utilitynav">
			<li>Welcome, <?=$user->first_name?> |</li>
			<li><a href="/users/profile">Profile</a> |</li>
			<li><a href="/users/logout">Log Out</a></li>
		</ul>
		
		<ul id="mainnav">
			<li id="navitem1"><a href="/posts">Chirps</a>
				<ul class="subnav">
					<li><a href="/posts">Chirpstream</a></li>
					<li><a href="#">Your chirps</a></li>
				</ul>
			</li>
			<li id="navitem2" class="current"><a href="/posts/users">Chirpers</a>
				<ul class="subnav">
					<li  
 						<? if ($subset == NULL): ?>
							class="current"
						<? endif; ?>	
					><a href="/posts/users">All chirpers</a></li>
					<li 
 						<? if ($subset == "followed"): ?>
							class="current"
						<? endif; ?>	
					><a href="/posts/users/followed">Chirpers you follow</a></li>
					<li 
 						<? if ($subset == "following_you"): ?>
							class="current"
						<? endif; ?>	 
					><a href="/posts/users/following_you">Chirpers following you</a></li>
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
	
		<?=$message?>
		
		<? if($users): ?>
		<ul id="chirperlist">
			<? foreach($users as $user): ?>
				<li><ul>
					<li class="button-link">
						
						<!-- If this user is currently being followed, show button to unfollow and add li class "followed" -->
						<? if(isset($connections[$user['user_id']])): ?>
							<a href='/posts/unfollow/<?=$user['user_id']."/".$subset?>'>Stop following</a>
							<li class="username followed">
						<!-- Otherwise show link to follow and omit "followed" class -->
						<? else: ?>
							<a href="/posts/follow/<?=$user['user_id']."/".$subset?>">Follow</a></li>
							<li class="username">
						<? endif; ?>
							<a href="/users/profile/<?=$user['user_id']?>"><?=$user['first_name']?> <?=$user['last_name']?></a></li>
				</ul></li>
			<? endforeach; ?>
		</ul>
		<? endif; ?>
		
	</div>