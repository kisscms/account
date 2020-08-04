<?php
//
?>
<form action="<?=url("account/login")?>" method="POST">
	<input type="text" name="email" placeholder="Email address">
	<input type="password" name="password" placeholder="Password">
	<input type="hidden" name="return_link" value="<?= ( array_key_exists('return', $_GET) ) ? $_GET['return'] : "/";?>">
	<input type="submit">
</form>
