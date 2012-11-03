		<?=$message?>

		<? if(isset($chirper_name)): ?>
		<h2><?=$chirper_name?>'s Chirps</h2>
		<? endif; ?>
		
		<? if($posts): ?>
		<ul id="chirplist">
		<? foreach($posts as $post): ?>
			<li>
				<ul>
					<li class="avatar"><a href="/users/profile/<?=$post['user_id']?>"><img src="/uploads/<?=$post['thumb_image']?>"></a></li>
					<!-- If viewing the current user's chirps, add delete buttons -->
					<? if($post['user_id'] == $user->user_id): ?>
						<li class="button-link"><a href='/posts/delete/<?=$post['post_id']?>'>Delete</a>
					<? endif; ?>
					<li class="username"><a href="/users/profile/<?=$post['user_id']?>"><?=$post['first_name']?> <?=$post['last_name']?></a></li>
					<li class="chirp"><?=$post['content']?></li>
					<li class="date"><?=$post['created']?><div class="clear"></div></li>
				</ul>
			</li>
		<? endforeach; ?>
		</ul>
		<? endif; ?>
