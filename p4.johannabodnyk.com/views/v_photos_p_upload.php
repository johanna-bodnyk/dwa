<script>

$(document).ready(function() {

	console.log("hello I'm in the iframe");
	
	window.parent.replace_iframe(<?=$image_id?>, "<?=$preview?>", "<?=$original?>");
	
});

</script>