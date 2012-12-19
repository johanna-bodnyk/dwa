<h2>Your friends are eating...</h2>
		
		<ul id="yumstream">
		
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
							<h3 class="dish-title"><?=$dish['name']?></h3>
							<? if ($dish['note'] != ""): ?>
								<p class="note toggle"><?=$dish['note']?></p>
							<? endif; ?>
							<p class="user toggle"><a href="/meals/stream/user<?=$dish['user_id']?>"><?=$dish['display_name']?></a> <span class="date">| <?=$dish['meal_date']?></span></p>
						<div class="buttons toggle">
							<p class="wants" id="wants">
							<span class="count">Wants: <?=$dish['wants']?> </span>							
							<? if ($dish['wanted'] == NULL): ?>
								<a class="button" id="want-button" href="/dishes/want/<?=$dish['dish_id']?>">Add to Want to Cook list</a>
							<? else: ?>
								<a class="button on" id="want-button" href="/dishes/unwant/<?=$dish['dish_id']?>">Remove from Want to Cook list</a>
							<? endif; ?>
							
							</p>
							<p class="comment-count">
								<span class="count">Comments: <?=$dish['comment_count']?></span>
							</p>
						</div>
						<div class="comments toggle">
							<ul class="dish-comments">
							<? foreach ($dish['comments'] as $c): ?>
								<li class="comment">
									<img class="avatar" src="/uploads/<?=$c['profile_image']?>">
									<span class="comment-user"><a href="/meals/stream/<?=$c['user_id']?>"><?=$c['display_name']?></a></span>
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
		
		</ul>