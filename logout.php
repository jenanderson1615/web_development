<?php
	session_start();
	unset($_SESSION["test_value"]);
?>
	<form action = "login.php" method = "POST">
		<div><input type = "submit" value = "login"></div>
	</form>
	<?php
?>
