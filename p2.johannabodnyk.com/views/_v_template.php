<!DOCTYPE html>
<html>
<head>
	<title><?=@$title; ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
	<meta name="description" content="Project 2 for Fall 2012 Harvard Extension School course CSCI E-75 &ndash; Dynamic Web Applications, by Johanna Bodnyk.">
	
	<link rel="stylesheet" type="text/css" href="/style.css" media="all" />
	
	<link href="http://fonts.googleapis.com/css?family=Lato:300,700,900" rel="stylesheet" type="text/css">
	
	<!-- Controller Specific JS/CSS - NOT USING THIS - ONLY ONE CSS FILE, HARDCODED ABOVE -->
	<? /* =@$client_files; */?>
	
</head>

<body>	

	<!-- Header -- logo and navigation (navigation displays only if user is logged in) -->
	<div id="header">
		<h1><a href="/"><img src="/logo.png">Chirper</a></h1>
		
		<!-- Secondary (top right) navigation -->
 		<? if ($user): ?>
		<ul id="utilitynav">
			<li>Welcome, <?=$user->first_name?> |</li>
			<li 
				<? if ($subnav == "profile"): ?>
					class="current"
				<? endif; ?>	
			><a href="/users/profile">Profile</a> |</li>
			<li><a href="/users/logout">Log Out</a></li>
		</ul>
		
		<!-- Main navigation -->
		<ul id="mainnav">
			<li id="navitem1" 
				<? if ($nav == "chirps"): ?>
					class="current"
				<? endif; ?>	
			><a href="/posts">Chirps</a>
				<ul class="subnav">
					<li  
 						<? if ($subnav == "chirpstream"): ?>
							class="current"
						<? endif; ?>	
					><a href="/posts">Chirpstream</a></li>
					<li  
 						<? if ($subnav == "your_chirps"): ?>
							class="current"
						<? endif; ?>	
					><a href="/posts/stream/yours">Your chirps</a></li>
				</ul>
			</li>
			<li id="navitem2" 
				<? if ($nav == "chirpers"): ?>
					class="current"
				<? endif; ?>	
			><a href="/posts/users">Chirpers</a>
				<ul class="subnav">
					<li  
 						<? if ($subnav == "all"): ?>
							class="current"
						<? endif; ?>	
					><a href="/posts/users">All chirpers</a></li>
					<li 
 						<? if ($subnav == "followed"): ?>
							class="current"
						<? endif; ?>	
					><a href="/posts/users/followed">Chirpers you follow</a></li>
					<li 
 						<? if ($subnav == "following_you"): ?>
							class="current"
						<? endif; ?>	 
					><a href="/posts/users/following_you">Chirpers following you</a></li>
				</ul>
			</li>
			<li id="navitem3" 
				<? if ($nav == "chirp"): ?>
					class="current"
				<? endif; ?>	
			><a href="/posts/add">Chirp!</a>
				<ul class="subnav">
				</ul>
		</ul>
		<? endif; ?>		
	</div>
	
	<!-- Main content area -->
	<div id="main">

		<?=$content;?> 
	
	</div>
	
	<!-- Footer arae -->
	<div id="footer">
		<p>Copyright 2012 Johanna Bodnyk</p>
	</div>

</body>
</html>