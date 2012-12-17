<form name="upload-photo" action="/photos/p_upload" method="POST" enctype="multipart/form-data">
						
	<input type="file" name="image">
	<input type="hidden" name="photo-id" value="<?=$id?>">
	<input type="submit" value="Upload">

</form>