<!DOCTYPE html>
<html>
<head>
	<title><?=@$title; ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
	<meta name="description" content="Project 4 for Fall 2012 Harvard Extension School course CSCI E-75 &ndash; Dynamic Web Applications, by Johanna Bodnyk.">
	
	<link rel="stylesheet" type="text/css" href="/css/yumbook.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/css/jquery/jquery-ui-1.9.2.custom.css" media="all" />
	<link href='http://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="/css/validationEngine.jquery.css" type="text/css"/>
	
	<!-- JS -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script src="/js/jquery.form.js"></script>
	<script src="/js/jquery.iframe.js"></script>
	<script src="/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
	</script>
<script src="/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8">
	</script>
	<script src="/js/yumbook.js"></script>
				
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
				<li><a href="/meals/add"
					<? if ($nav == "add_meal"): ?>
						class="current"
					<? endif; ?>
				>Add a meal</a></li>
				<li><a href="/users/userlist"
					<? if ($nav == "friends"): ?>
						class="current"
					<? endif; ?>	
				>Your friends</a></li>
				<li><a href="/dishes/add"
					<? if ($nav == "add_dish"): ?>
						class="current"
					<? endif; ?>	
				>Add a <br>dish</a></li>
				<div class="clear"></div>
				<li><a href="/meals/view/yours"
					<? if ($nav == "meals"): ?>
						class="current"
					<? endif; ?>	
				>Your meals</a></li>
				<li><a href="/meals/view/stream"
					<? if ($nav == "stream"): ?>
						class="current"
					<? endif; ?>	
				>Friends' meals</a></li>
				<li><a href="/users/edit_profile"
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