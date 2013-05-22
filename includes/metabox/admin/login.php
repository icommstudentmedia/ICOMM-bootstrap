<?php
$pageTitle = "BYU-Idaho Icomm - Login";
$focus_variable = "document.getElementById('email').focus();";
$bodyclass = 'class="loginpage"';
require_once($_SERVER['DOCUMENT_ROOT']."/includes/session.php");
require_once($_SERVER['DOCUMENT_ROOT']."/includes/header.php");
if(empty($msg)){
	$msg = mysql_real_escape_string(strip_tags($_REQUEST['msg']));
}
?>
<h1 class="adminheader">Login to I-Comm Student Media</h1>
<?php
if($msg == 'Username'){
print '<div id="error_message">It seems that you are already registered, please login to view your account</div><br />';
}
if($msg == 'Registered'){
print '<div id="success_message">You have Successfuly Registered, Please login with your email and password</div><br />';
}
if($msg == 'logout_complete'){
print '<div id="success_message">You have Successfuly Logged out</div><br />';
}
if($msg == 'login_failed'){
print '<div id="error_message">Login Failed, Please check your username and password and try again</div><br />';
}
if($msg == 'MustLogin'){
print '<div id="error_message">The Page you attempted to reach requires you to be logged in, please login and try again</div><br />';
}
?>
<form class="regform" id="adminform" method="post" action="/process/login.php">
<fieldset>
<ol>
<li><label for="email">Email: </label><input size="35" tabindex="1" accesskey="u" name="email" class="text" type="text" maxlength="100" id="email" value="<?php print $email; ?>" /></li>
<li><label for="passwd">Password: </label><input size="35" tabindex="2" accesskey="u" name="passwd" class="text" type="password" maxlength="100" id="passwd" /></li>
</ol>
<input class="submit" tabindex="3" type="submit"  value="Login" />  <a href="/register.php">Not Registered?</a>

</fieldset>
</form>
<?php
require_once($_SERVER['DOCUMENT_ROOT']."/includes/footer.php");
?>