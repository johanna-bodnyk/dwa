		<!-- H2 depends on whether this is current user's profile or another user's profile -->
		<h2>
			<? if($own_profile) { 
					echo 'Your Profile'; 
				}
				else {
					echo $profile_content['first_name'].' '.$profile_content['last_name'];
				}
			?>
		</h2>
		
		<!-- Profile image -->
		<p class="profile-image"><img src="/uploads/<?=$profile_content['profile_image']?>" alt="<?=$profile_content['alt_text']?>"></p>
		
		<!-- If displaying another user's profile, display follow or unfollow button and view chirps button -->
		<? if(!$own_profile): ?>
		
			<!-- Follow or unfollow button -->
			<? if ($following): ?>
				<p class="button-link"><a href="/posts/unfollow/<?=$profile_content['user_id']?>/profile">Stop following <?=$profile_content['first_name']?></a>
				<? else: ?>
				<p class="button-link"><a href="/posts/follow/<?=$profile_content['user_id']?>/profile">Follow <?=$profile_content['first_name']?></a>			
			<? endif; ?>
			
			<!-- View chirps button -->
			<p class="button-link"><a href="/posts/stream/<?=$profile_content['user_id']?>">View <?=$profile_content['first_name']?>'s chirps</a>
		
		<!-- If displaying current user's profile, display button to edit profile -->
		<? else: ?>
			<p class="button-link"><a href="/users/edit_profile">Edit your profile</a>
		<? endif; ?>
		
		<!-- Profile content -->
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
