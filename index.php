<?php
if (!empty($_GET['uid']) and !empty($_GET['nr']) ) {
	require_once("php.php");
	$ticketinfo = Ticket::getInfo($_GET['uid'], $_GET['nr']);
	$kartennummer = $_GET['nr'];
	$uid = $_GET['uid'];
	$uid_hum = substr($uid,0,4).'-'.substr($uid,4,4).'-'.substr($uid,8);
	


	//validierungs Prozess
	@session_start();

	if(isset($_SESSION['abiball_admin']) AND $_SESSION['abiball_admin'] === 1) {

		$timestamp = time();
		DB::query("UPDATE `nuwsg3szjgc7s9nc`.`tickets` SET `status`='$timestamp' WHERE `id`='$kartennummer';");
		echo '<h1>ok</h1>';
		echo "<h1>".$ticketinfo[1].'</h1>';
		echo "<h1>Tisch ".$ticketinfo[0].'</h1>';
		die();
	}



} else {
	require_once "anderes.php";
	die();
}
?>
<!DOCTYPE html>
<html>

<head>
	<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

	<!-- Compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

	<!-- Compiled and minified JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>

	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>BBS Burgdorf Abiball 2018</title>

	<meta name="theme-color" content="#005797">

	<style type="text/css">
	.bbs {
		background-color: #005797;
	}


	body {
		display: flex;
		min-height: 100vh;
		flex-direction: column;
	}

	main {
		flex: 1 0 auto;
	}

	.tisch {
		max-height: 50vh;
	}
</style>

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-96562127-2"></script>
<script type="text/javascript">
	window.dataLayer = window.dataLayer || [];

	function gtag() {
		dataLayer.push(arguments);
	}
	gtag('js', new Date());
	gtag('config', 'UA-96562127-2');

</script>
</head>

<body>
	<nav>
		<div class="nav-wrapper bbs">
			<a href="#" class="brand-logo center">Karte <?php echo $kartennummer?></a>
		</div>
	</nav>
	<main>

		<div class="row container">
			<div class="col s12">
				<div class="card green darken-1">
					<div class="card-content white-text">
						<span class="card-title center"><?php echo $ticketinfo[1]?></span>
					</div>
				</div>			

				<div class=" card blue-grey darken-1 center">
					<div class="card-content white-text">
						<span class="card-title">Tischnummer <?php echo $ticketinfo[0]?></span>
						<a href="tp.jpg" target="_blank"><img class=" tisch responsive-img" src="tp.jpg"></a>
					</div>
				</div>

				<div class="card green darken-1 center">
					<div class="card-content white-text">
						<span class="card-title">Anderes</span>
						<a class="btn green" href="https://www.google.de/maps/dir//StadtHaus+Burgdorf,+Sorgenser+Str.+31,+31303+Burgdorf/" target="_blank">Google Maps</a>
					</div>
				</div>
			</div>
		</div>
	</main>
	<footer class="page-footer bbs">
		<div class="container">
			<p>Nur in Verbindung mit den Papierticket gültig. Versenden Sie niemanden diese Seite. Die Seite benutzt Google Anayltics.</p>
			<p>Bei Fragen kontaktieren Sie bitte abiball@timlai.de</p>
		</div>
		<div class="footer-copyright">
			<div class="container">
				<p class="left">© 2018 Tim Lai</p>
				<p class="right">
					<?php echo $uid_hum?>
				</p>
			</div>
		</div>
	</footer>

</body>

</html>
