<!DOCTYPE html>
<head>
<?
$boxes = "";
for($i = 1; $i <= 99; $i++) {
	$size = rand(10,100);
	$boxes = $boxes."<div style='width:".$size."px; height:".$size."px; float:left; margin:4px; background-color:red'></div>";
}
?>
	
</head>
<body>
<?=$boxes?>
</body>
</html>