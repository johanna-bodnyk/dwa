		<?=$message?>
		
		<!-- User list -->
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