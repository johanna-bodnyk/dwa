		<h2><?=$dish['name']?></h2>
		
		<a class="button top-right big" href="#">Add to Stream</a>
				
		<div class="images">
			<img class="cover" src="/images/roast-beef-402.jpg">
			<img class="thumb" src="/images/roast-beef-2-93.jpg">
			<img class="thumb" src="/images/roast-beef-3-93.jpg">
			<img class="thumb" src="/images/roast-beef-4-93.jpg">
			<img class="thumb last" src="/images/roast-beef-5-93.jpg">
		</div>
				
		<div class="info">
			<h3 class="label">Added by </h3>	
			<p class="note data">
				<a href="#"><?=$dish['display_name']?></a>			
				<a class="button" href="#">Edit Dish</a>
			</p>
			<? if ($dish['source_name']): ?>
				<h3 class="label">Source</h3>
				<p class="note data"><?=$dish['source_name']?></p>
			<? endif; ?>
			<h3 class="label">Note</h3>
			<p class="note data"><?=$dish['note']?></p>
			<h3 class="label">Added on</h3>
			<p class="note data"><?=$dish['created']?></p>	<h3 class="label">Last cooked on</h3>
			<p class="note data">!December 26, 2012</p>
			<h3 class="label">Likes</h3>
			<p class="note data">!22 <a class="button" href="#">Like</a></p>
			<h3 class="label">Wants </h3>
			<p class="note data">!6 <a class="button" href="#">Want to Cook</a></p>
			<h3 class="label">Rating</h3>
			<p class="note data">ADD RATING CONTROLS</p>
			
			<div class="clear"></div>

			</div> 

					
			<!-- Comments -->
			<div class="comment-wrapper">
			
				<h3>Comments...</h3>
				<div class="comments">
					<h4>...on this dish</h4>
					<ul>
						<li class="comment">
							<img class="avatar" src="http://placekitten.com/40/40">
							<span class="comment-user">Matthew Christian</span> said:
							<span class="comment-text">This looks so good!</span>
							<span class="comment-date"><br>12/26/2011 &ndash; 11:49pm</span>
						</li>							
						<li class="comment">
							<img class="avatar" src="http://placekitten.com/40/40">
							<span class="comment-user">Matthew Christian</span> said:
							<span class="comment-text">Will you make this for me?</span>
							<span class="comment-date"><br>12/26/2011 &ndash; 11:49pm</span>
						</li>
						<li class="comment">
							<img class="avatar" src="http://placekitten.com/40/40">
							<span class="comment-user">Kate Johannes</span>
							<span class="comment-text">Wish I'd been there. I might call your mom for that roast beef recipe!</span>
							<span class="comment-date"><br>12/27/2011 &ndash; 6:00pm</span>
						</li>
						</ul>
						<a class="add-comment button" href="#">Add comment</a>
				
					<h4>...on your meals with this dish</h4>
					
					<h5>A meal on <a href="">December 26, 2012</a>:</h5>
					<ul>
					
						<li class="comment">
							<img class="avatar" src="http://placekitten.com/40/40">
							<span class="comment-user">Matthew Christian</span> said:
							<span class="comment-text">Will you make this for me?</span>
							<span class="comment-date"><br>12/26/2011 &ndash; 11:49pm</span>
						</li>
						<li class="comment">
							<img class="avatar" src="http://placekitten.com/40/40">
							<span class="comment-user">Kate Johannes</span>
							<span class="comment-text">Wish I'd been there. I might call your mom for that roast beef recipe!</span>
							<span class="comment-date"><br>12/27/2011 &ndash; 6:00pm</span>
						</li>
					</ul>
					
					<h5>A meal on <a href="">March 13, 2012</a>:</h5>						<ul>
					
						<li class="comment">
							<img class="avatar" src="http://placekitten.com/40/40">
							<span class="comment-user">Kate Johannes</span>
							<span class="comment-text">Wish I'd been there. I might call your mom for that roast beef recipe!</span>
							<span class="comment-date"><br>12/27/2011 &ndash; 6:00pm</span>
						</li>
					</ul>
				</div>
			</div>
		
			<div class="cooked-list-wrapper">
				<h3>This dish has been cooked...</h3>
				<div class="cooked-list">
					<h4>...5 times by you</h4>
					<ul>
						<li>On <a href="">March 13, 2012</a></li>						<li>On <a href="">December 26, 2012</a></li>
						<li>On <a href="">March 13, 2012</a></li>						<li>On <a href="">December 26, 2012</a></li>
						<li>On <a href="">March 13, 2012</a></li>

					<ul>
					<h4>...23 times by others</h4>
					<ul>
						<li>By <a href="#">Kate Johannes</a> on <a href="">March 13, 2012</a></li>					<li>By <a href="#">Matthew Christian</a> on <a href="">December 26, 2012</a></li>
						<li>By <a href="#">Laura Cunningham</a> on <a href="">December 26, 2012</a></li>
						<li>By <a href="#">Kate Johannes</a> on <a href="">December 26, 2012</a></li>
						<li>By <a href="#">Matthew Christian</a> on <a href="">December 26, 2012</a></li>
						<li>By <a href="#">Kate Johannes</a> on <a href="">December 26, 2012</a></li>
						<li>By <a href="#">Kate Johannes</a> on <a href="">March 13, 2012</a></li>					<li>By <a href="#">Matthew Christian</a> on <a href="">December 26, 2012</a></li>
						<li>By <a href="#">Laura Cunningham</a> on <a href="">December 26, 2012</a></li>
						<li>By <a href="#">Kate Johannes</a> on <a href="">December 26, 2012</a></li>
						<li>By <a href="#">Matthew Christian</a> on <a href="">December 26, 2012</a></li>
						<li>By <a href="#">Kate Johannes</a> on <a href="">December 26, 2012</a></li>

					<ul>
				</div>