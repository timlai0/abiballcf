<?php

$buffer = "";

if (Login::check()) {
	header('Location: .');
}

if (isset($_POST['user_pw']) AND isset($_POST['user_login'])) {

	$user = DB::escape($_POST['user_login']);
	$password = DB::escape($_POST['user_pw']);

	if ($ar_user = DB::query("SELECT * FROM `admin` WHERE `username` = '$user'")) {
		if (Login::validate_pw($password, $ar_user[0]["password"])) {
			@session_start();
			$_SESSION['abiball_user'] = $ar_user[0]["username"];
			$userid = $_SESSION['abiball_userId'] = $ar_user[0]["user_id"];
			header('Location: .');
		} else {
			$buffer = "<script>Materialize.toast('falsche Benutzerdaten')</script>";
		}
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

	<!-- Compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

	<!-- Compiled and minified JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">

	<title>AbiBall</title>

	<script type="text/javascript">
		if (location.protocol == 'http:') {
			location.href = 'https:' + window.location.href.substring(window.location.protocol.length);
		}
	</script>

	<meta name="theme-color" content="#000">
	<style type="text/css">
	.valign-wrapper {
		height: 100vh;
	}

	body {
		background-color: black;
	}
</style>


</head>

<body>
	<div class="valign-wrapper">
		<div class="row">
			<form method="POST">

				<div class="col">
					<div class="card blue-grey darken-1">
						<div class="card-content white-text">
							<span class="card-title"><h1>Login</h1></span>
							<label for="user">Username</label>

							<input name="user_login" id="user" type="text" autofocus>

							<label for="password">Passwort</label>

							<input name="user_pw" id="password" type="password">

						</div>
						<div class="card-action">
							<button class="btn" type="submit">Anmelden</button>

						</div>
					</div>
				</div>
			</form>

		</div>

		<?php echo $buffer;?>
	</body>

	</html>
