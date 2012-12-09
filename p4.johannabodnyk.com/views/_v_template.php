<!DOCTYPE html>
<html>
<head>
	<title><?=@$title; ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
	<meta name="description" content="Project 4 for Fall 2012 Harvard Extension School course CSCI E-75 &ndash; Dynamic Web Applications, by Johanna Bodnyk.">
	
	<link rel="stylesheet" type="text/css" href="/style.css" media="all" />
	<link href='http://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css'>
	
	<!-- JS -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
				
	<!-- Controller Specific JS/CSS -->
	<?=@$client_files; ?>
	
</head>

<body>	
	<div id="wrapper">
		
		<!-- Header -->
		<div id="header">
			
			<h1>Yumbook</h1>
			
			<!-- User name and log-out link (top right) -->
			<? if ($user): ?>
			<ul id="utilitynav">
				<li>Welcome, <?=$user->display_name?> |</li>
				<li><a href="/users/logout">Log Out</a></li>
			</ul>
			
			<!-- Main navigation -->
			<ul id="main-nav">
				<li><a href="#"
					<? if ($nav == "add"): ?>
						class="current"
					<? endif; ?>
				>Add a meal</a></li>
				<li><a href="#"
					<? if ($nav == "friends"): ?>
						class="current"
					<? endif; ?>	
				>Your friends</a></li>
				<li><a href="#"
					<? if ($nav == "dishes"): ?>
						class="current"
					<? endif; ?>	
				>Your dishes</a></li>
				<div class="clear"></div>
				<li><a href="#"
					<? if ($nav == "meals"): ?>
						class="current"
					<? endif; ?>	
				>Your meals</a></li>
				<li><a href="#"
					<? if ($nav == "stream"): ?>
						class="current"
					<? endif; ?>	
				>Friends' meals</a></li>
				<li><a href="#"
					<? if ($nav == "account"): ?>
						class="current"
					<? endif; ?>	
				>Your account</a></li>
			</ul>
			<? endif; ?>
			
			<div class="clear"></div>	
			
		</div>

		<div id="main">
			<?=$content;?> 
		</div>
		
		<div id="footer">
			<p>Copyright 2012 Johanna Bodnyk</p>
		</div>

</body>
</html>