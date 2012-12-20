		<h2><?=$dish['name']?></h2>
		
		<a class="button top-right big" id="add-to-stream" href="#">Add to Stream</a>
		
		<div id="add-to-stream-panel">
			<h4>Add this dish to your stream as...</h4>
			<p>...a new meal <a class="button" href="/meals/add/new_meal/<?=$dish_id?>">Add new meal</a></p>
			<p>...part of your meal on 
				<select name="meal-selection" id="meal-selection">
					<? foreach ($recent_meals as $meal): ?>
						<option value="<?=$meal['meal_id']?>"><?=$meal['meal_date']?></option>
					<? endforeach; ?>
				</select>	
					<a class="button" id="add-to-meal" href="/meals/add/<?=$recent_meals['0']['meal_id']?>/<?=$dish_id?>">Add to meal</a></p>
			
		</div>
				
		<div class="images">

			<? foreach($images as $key => $image): ?>
				<? if($key == 0): ?>
					<img class="cover" src="/uploads/image-<?=$image['image_id']?>-402x300.jpg">
				<? elseif(($key)%4 == 0): ?>
					<img class="thumb last" src="/uploads/image-<?=$image['image_id']?>-93x93.jpg"><br>
				<? else: ?>
					<img class="thumb" src="/uploads/image-<?=$image['image_id']?>-93x93.jpg">	
				<? endif;?>
			<? endforeach; ?>

		</div>
				
		<div class="info">
			<h3 class="label">Added by </h3>	
			<p class="note data">
				<a href="/users/<?=$dish['user_id']?>"><?=$dish['display_name']?></a>			
				<!-- <a class="button" href="#">Edit Dish</a> -->
			</p>
			<? if (isset($dish['source_name'])): ?>
				<h3 class="label">Source</h3>
				<p class="note data"><?=$dish['source_name']?></p>
			<? endif; ?>
			<? if ($dish['note'] != ""): ?>
				<h3 class="label">Note</h3>
				<p class="note data"><?=$dish['note']?></p>
			<? endif; ?>
			<h3 class="label">Added on</h3>
			<p class="note data"><?=$dish['created']?></p>	
			<? if ($dish['last_date'] != "0"): ?>
				<h3 class="label">Last eaten on</h3>
				<p class="note data"><?=$dish['last_date']?></p>
			<? endif; ?>
			<!-- <h3 class="label">Likes</h3>
			<p class="note data">!22 <a class="button" href="#">Like</a></p> -->
			<h3 class="label">Likes</h3>
			<p class="note data" id="likes"><?=$dish['likes']?>
			
			<? if ($dish['liked'] == NULL): ?>
				<a class="button" id="like-button" href="/dishes/like/<?=$dish_id?>">Like</a>
			<? else: ?>
				<a class="button on" id="like-button" href="/dishes/unlike/<?=$dish_id?>">Unlike</a>
			<? endif; ?>
			
			<!-- <h3 class="label">Rating</h3>
			<p class="note data">***ADD RATING CONTROLS</p> -->
			
			<div class="clear"></div>

			</div> 

					
			<div class="overflow-fix">
			<!-- Comments -->
			<div class="comment-wrapper">
			
				<h3>Comments...</h3>
				<div class="comments">
					<h4>...on this dish</h4>
					<ul class="dish-comments">
						<? foreach ($dish_comments as $c): ?>
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
						<input name="referent_type" type="hidden" value="dish">
						<input name="referent_id" type="hidden" value="<?=$dish_id?>">
						<input type="submit" value="Add Comment">
					</form>

				
					<!-- ADD CHECK FOR ANY COMMENTS HERE AND ABOVE -->
					<h4>...on your meals with this dish</h4>	
						<? foreach ($meal_comments as $c): ?>
							<? if ($c['meal_id'] != $last_meal_id): ?>
								<? if ($last_meal_id != NULL): ?>
									</ul>
								<? endif; ?>
								<h5>A meal on <a href="/meals/view/<?=$c['meal_id']?>"><?=$c['meal_date']?></a>:</h5>
								<ul>
							<? $last_meal_id = $c['meal_id']?>
							<? endif; ?>
							<li class="comment">
								<img class="avatar" src="/uploads/<?=$c['profile_image']?>">
								<span class="comment-user"><a href="/meals/stream/<?=$c['user_id']?>"><?=$c['display_name']?></a></span>
								<span class="comment-text"><?=$c['comment']?></span>
								<span class="comment-date"><br><?=$c['created']?></span>
							</li>
						<? endforeach; ?>
							</ul>
				</div>
			</div>
		
			<div class="cooked-list-wrapper">
				<h3>This dish has been cooked...</h3>
				<div class="cooked-list">
					<h4>...<?=count($your_meals)?> times by you</h4>
					<ul>
						<? foreach ($your_meals as $m): ?>
							<li>On <a href="/meals/view/<?=$m['meal_id']?>"><?=$m['meal_date']?></a></li>
						<? endforeach; ?>
					<ul>
					<h4>...<?=count($other_meals)?> times by others</h4>
					<ul>
						<? foreach ($other_meals as $m): ?>
							<li>By <a href="/users/<?=$m['user_id']?>"><?=$m['display_name']?></a> on <a href="/meals/view/<?=$m['meal_id']?>"><?=$m['meal_date']?></a></li>
						<? endforeach; ?>
					<ul>
				</div>
				</div>