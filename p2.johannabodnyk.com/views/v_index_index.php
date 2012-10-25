<? if(!$user): ?>
	Welcome stranger<br>
<? else: ?>
	Welcome back <?= $user->first_name ?><br>
<? endif; ?>