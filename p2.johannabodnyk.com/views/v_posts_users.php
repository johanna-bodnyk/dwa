<? foreach($users as $user): ?>

<?=$user['first_name']?>  
<?=$user['last_name']?> 
<a href='/posts/follow/<?=$user["user_id"]?>'>Follow</a>
<br><br>

<? endforeach; ?>