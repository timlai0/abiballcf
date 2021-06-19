<?php

require_once '../php.php';


if (!Login::check()) {
	require_once('login.php');
	die();
};




// if (isset($_POST['1p1'])) {


// 	foreach ($_POST as $key => $value) {
// 		$ar_tp = explode('p', $key);

// 		$ar[$ar_tp[0]][$ar_tp[1]] = $value;
// 	};

// 	Ticket::neuePlaetze($ar);

// };
?>


<!DOCTYPE html>
<html>

<head>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

	<!-- Compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

	<!-- Compiled and minified JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>

	<title>Abiball Admin</title>


	<style type="text/css">
	.tischlister table {
		background-color: white;
	}


	.tischlister td {
		border: 1px solid black;
		width: 8.33%;
		height: 4em;
		padding: 5px;
	}

	.r {
		background-color: #ff8566;
	}

	.g {
		background-color: #adebad;
	}

	input {
		width: 3em;
		margin: 3px;
	}

	.tdbtn {
		position: -webkit-sticky;
		position: sticky;		
		width: 100%;
		height: 100%;
		top: 0;
	}

	body {
		background-color: #b0bec5;
	}

	th {
		background-color: #b0bec5;

		position: -webkit-sticky;
		position: sticky;		
		top: 0;		
	}

	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
		/* display: none; <- Crashes Chrome on hover */
		-webkit-appearance: none;
		margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
	}
</style>
</head>

<body>
	<nav class="row">
		<div class="nav-wrapper col s12">
			<a class="brand-logo">Abiball Admin</a>
			<ul class="right">
				<li><a class="waves-effect waves-light btn-large red" href="logout.php">Abmelden</a></li>
			</ul>
		</div>
	</nav>
	<div class="row">
		<div class="col s12">
<!-- 			<h1>Tische ändern</h1>

			<?php 
			require_once '../php.php';

			Admin::editTischListe();
			?> -->

			<h1>Karten</h1>
			<?php 

			Admin::kartenliste();?>

			<script type="text/javascript">

				if (location.protocol == 'http:') {
					location.href = 'https:' + window.location.href.substring(window.location.protocol.length);
				}

				<?php 
				for ($i = 0; $i < 45; $i++) {

					for ($k = 0; $k < 100; $k++) {
						$field = $i.'p'.$k;
						echo '
						$( "#'.$field.'" ).keyup(function() {
							$("#name'.$field.'").html("");
							$("#'.$field.'").css("background-color", "red");
						});';

						echo '';
					};
				};

				?>

			</script>
		</div>
	</div>

</body>

</html>
