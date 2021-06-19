<?php

if (isset($_GET['key']) AND $_GET['key'] == "pksodjfoisduhf0943oriefjklsnlk") {
	session_start();
	$_SESSION['abiball_admin'] = 1;

} else {
	session_start();

	$_SESSION['abiball_admin'] = 0;
	die("loged out");

}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>
	<h1>eingelogt</h1>
	<a href="supersecretlogin.php?logout">Logout</a>
</body>
</html>