<?php $this->user->viewModelErrors(); ?>
<form method="post" action="">
<label for="email">E-mail</label>
<p><input type="email" name="email" value="<?php echo $this->user->email; ?>" /></p>
<label for="password">Password</label>
<p><input type="password" name="password" /></p>
<p><input type="submit" name="register" value="Register" /></p>
</form>
<hr>