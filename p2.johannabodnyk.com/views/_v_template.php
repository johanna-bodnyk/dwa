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

	<div id="header">
		<h1><img src="/logo.png">Chirper</h1>

		<?=$content;?> 
	
	<div id="footer">
		<p>Copyright 2012 Johanna Bodnyk</p>
	</div>

</body>
</html>