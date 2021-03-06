	<h2>Choose who to follow...</h2>
		<!-- User list -->
		<? if($users): ?>
		<ul id="userlist">
			<? foreach($users as $user): ?>
				<li>
					<img src="/uploads/<?=$user['profile_image']?>">
					<a href="/meals/view/user/<?=$user['user_id']?>"
					<? if(isset($connections[$user['user_id']])): ?>
						class="followed"
					<? endif; ?>
					
					
					><?=$user['display_name']?></a>
								
					<!-- If this user is currently being followed, show button to unfollow and add li class "followed" -->
					<? if(isset($connections[$user['user_id']])): ?>
						<a class="unfollow-button" href='/users/unfollow/<?=$user['user_id']?>'>Stop following</a>
					<!-- Otherwise show link to follow and omit "followed" class -->
					<? else: ?>
						<a class="follow-button" href="/users/follow/<?=$user['user_id']?>">Follow</a>
					<? endif; ?>
				</li>
			<? endforeach; ?>
		</ul>
		<? endif; ?>