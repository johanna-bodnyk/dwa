<h2><?=$h2?></h2>
		
		<?=$message?>
		
		<ul id="yumstream">
		
		<? if(isset($meals)): ?>
		<? foreach ($meals as $meal_id => $dishes): ?>
			<li class="meal">		
					<ul class="dishes">			
					<? foreach ($dishes as $meal_dish_id => $dish): ?>
						<li class="dish <? if ($dish['dish_number'] != 0): ?>closed<? endif; ?>">
						<div class="images">
							<? foreach($dish['images'] as $key => $image): ?>
								<? if($key == 0): ?>
									<img class="cover toggle" src="/uploads/image-<?=$image['image_id']?>-402x300.jpg">
									<img class="small-cover" src="/uploads/image-<?=$image['image_id']?>-194x125.jpg" height="123" width="192" style="border: 1px solid black">
								<? elseif(($key)%4 == 0): ?>
									<img class="thumb last toggle" src="/uploads/image-<?=$image['image_id']?>-93x93.jpg"><br>
								<? else: ?>
									<img class="thumb toggle" src="/uploads/image-<?=$image['image_id']?>-93x93.jpg">	
								<? endif;?>
							<? endforeach; ?>
						</div>
						<div class="info">
							<h3 class="dish-title"><a href="/dishes/view/<?=$dish['dish_id']?>"><?=$dish['name']?></a></h3>
							<? if ($dish['note'] != ""): ?>
								<p class="note toggle"><?=$dish['note']?></p>
							<? endif; ?>
							<p class="user toggle"><a href="/meals/stream/user/<?=$dish['user_id']?>"><?=$dish['display_name']?></a> <span class="date">| <?=$dish['meal_date']?></span></p>
						<div class="buttons toggle">
							<h3 class="label">Likes:</h3>
							<p class="note data" id="likes"><?=$dish['likes']?>							
							<? if ($dish['liked'] == NULL): ?>
								<a class="button" id="like-button" href="/dishes/like/<?=$dish['dish_id']?>">Like</a>
							<? else: ?>
								<a class="button on" id="like-button" href="/dishes/unlike/<?=$dish['dish_id']?>">U</a>
							<? endif; ?>
							
							</p>
						<h3 class="label">Comments:</h3>
						<div class="clear"></div>
						</div>
						<div class="comments toggle">
						
							<ul class="dish-comments">
							<? foreach ($dish['comments'] as $c): ?>
								<li class="comment">
									<img class="avatar" src="/uploads/<?=$c['profile_image']?>">
									<span class="comment-user"><a href="/meals/view/user/<?=$c['user_id']?>"><?=$c['display_name']?></a></span>
									<span class="comment-text"><?=$c['comment']?></span>
									<span class="comment-date"><br><?=$c['created']?></span>
								</li>
							<? endforeach; ?>
							</ul>
							<form class="add-comment" action="/dishes/add_comment" method="POST">
								<textarea name="comment" rows="2" cols="53"></textarea>
								<input name="referent_type" type="hidden" value="meal_dish">
								<input name="referent_id" type="hidden" value="<?=$meal_dish_id?>">
								<input type="submit" value="Add Comment">
							</form>
						</div>
					</div>
					
					<? if ($dish['dish_number'] != 0): ?>
						<a class="button dish-toggle" href="#">Expand</a>
					<? endif; ?>
					</li>
					<? endforeach; ?>
					</ul>
			</li>
		<? endforeach; ?>
		<? endif; ?>
		
		</ul>