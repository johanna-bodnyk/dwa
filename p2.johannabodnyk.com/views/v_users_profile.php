		<ul id="utilitynav">
			<li>Welcome, <?=$user->first_name?> |</li>
			<li <? if($own_profile) { echo 'class="current"'; } ?> >
				<a href="/users/profile">Profile</a> |
			</li>
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
					<li><a href="/posts/users">All chirpers</a></li>
					<li><a href="/posts/users/followed">Chirpers you follow</a></li>
					<li><a href="/posts/users/following_you">Chirpers following you</a></li>
				</ul>
			</li>
			<li id="navitem3"><a href="#">Chirp!</a>
				<ul class="subnav">
				</ul>
			</li>
		</ul>
	</div>
	<div id="main">
	
		<h2>
			<? if($own_profile) { 
					echo 'Your Profile'; 
				}
				else {
					echo $profile_content['first_name'].' '.$profile_content['last_name'];
				}
			?>
		</h2>
		
		<p class="profile-image"><img src="/uploads/<?=$profile_content['profile_image']?>"></p>
		
		<!--Need to add logic for start/stop following button-->
		<? if(!$own_profile): ?>
			<? if ($following): ?>
			<p class="button-link"><a href="/posts/unfollow/<?=$profile_content['user_id']?>/profile">Stop following <?=$profile_content['first_name']?></a>
			<? else: ?>
			<p class="button-link"><a href="/posts/follow/<?=$profile_content['user_id']?>/profile">Follow <?=$profile_content['first_name']?></a>			
			<? endif; ?>
			<p class="button-link"><a href="#">View <?=$profile_content['first_name']?>'s chirps</a>
		
		<? else: ?>
			<p class="button-link"><a href="/users/edit_profile">Edit your profile</a>
		<? endif; ?>
		
		<p class="profile-label">Email:</p><p class="profile-content"><?=$profile_content['email']?></p>
		
		<? if($profile_content['location']): ?>
			<p class="profile-label">Location:</p><p class="profile-content"><?=$profile_content['location']?></p>
		<? endif; ?>
		
		<? if($profile_content['website']): ?>
			<p class="profile-label">Website:</p><p class="profile-content"><?=$profile_content['website']?></p>
		<? endif; ?>

		<div class=clear></div>
		
		<? if($profile_content['bio']): ?>
			<p class="bio-label">Bio:</p><p class="bio-content"><?=$profile_content['bio']?></p>
		<? endif; ?>
		
 		<div class=clear></div>

	</div>