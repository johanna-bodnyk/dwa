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
		
		<? if(!$own_profile): ?>
			<? if ($following): ?>
			<p class="button-link"><a href="/posts/unfollow/<?=$profile_content['user_id']?>/profile">Stop following <?=$profile_content['first_name']?></a>
			<? else: ?>
			<p class="button-link"><a href="/posts/follow/<?=$profile_content['user_id']?>/profile">Follow <?=$profile_content['first_name']?></a>			
			<? endif; ?>
			<p class="button-link"><a href="/posts/stream/<?=$profile_content['user_id']?>">View <?=$profile_content['first_name']?>'s chirps</a>
		
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
