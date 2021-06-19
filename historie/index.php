<?php 
require_once '../aws/aws-autoloader.php';
require_once '../php.php';

use Aws\S3\S3Client;

if (isset($_FILES['userfile'])) {

//Bild überprüfen	

	if ($_FILES['userfile']['error'] !== 0) {
		die('Datei ist zu groß. Bitte eine Datei kleiner als 20MB wählen.');
	}

//IP aufzeichnen	

	if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$client_ip = DB::escape($_SERVER['REMOTE_ADDR']);
	}
	else {
		$client_ip = DB::escape($_SERVER['HTTP_X_FORWARDED_FOR']);
	}

	if(@is_array(getimagesize($_FILES['userfile']['tmp_name']))) {
		$image = true;
	} else {
		die('kein Bild oder Fehler beim Upload.');
	}

//Capaptcha Verifizieren

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
			'secret' => '6Lc7m0cUAAAAAF0goBLGNPt5e_681hCRk18lCxSN',
			'response' => $_POST['g-recaptcha-response'],
		)
	));

	$resp = curl_exec($curl);
	curl_close($curl);

	if(strpos($resp, '"success": true') == FALSE) {
		echo "reCAPTCHA Falsch - Bitte erneut versuchen.";
		die();
	}

//Für die Hochladen/Datenbank vorbereiten


	$klasse = DB::escape($_POST['klasse']);

	$jahr = DB::escape($_POST['jahr']);

//Hochladen zu S3

	$bucket = 'abipic';
	$keyname = 'historie/'.$jahr.'/'.$klasse.' - '.md5_file($_FILES['userfile']['tmp_name']);

	$tags = http_build_query(['jahr' => $jahr, 'klasse' => $klasse]);
	
	$result = S3Client::factory(['region'  => 'eu-central-1', 'version' => 'latest'])->putObject(array(
		'ContentType'  => $_FILES['userfile']['type'],
		'Bucket'       => $bucket,
		'Key'          => $keyname,
		'SourceFile'   => $_FILES['userfile']['tmp_name'],
		'ACL'          => 'public-read',
		'Tagging' 	   =>  $tags,
		'ServerSideEncryption' => 'AES256'
	));

	if ($result['statusCode']) {
		print_r($result);
		die('<h1>Fehler</h1>'.$result['statusCode']);
	}

	$url = $result['ObjectURL'];

//In Datenbank schrieben	

	if (DB::query("INSERT INTO `historie` (`url`, `klasse`, `Jahr`, `ip`) VALUES ('$url', '$klasse', '$jahr', '$client_ip');")) {
		echo "Das Bild wurde erfolgreich hochgeladen";
		die();
	} 

} else {

//Es wird kein Bild hochgeladen

	if (($amt = DB::query("SELECT count(idbilder) 'a' FROM historie;")) !== false) {
		$amt = $amt[0]['a'];
	} else {
		die('Datenbank fehler. Bitte Tim kontaktieren.');
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>

	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<title>Bildersammler</title>
	<script src='https://www.google.com/recaptcha/api.js'></script>

	<script type="text/javascript">
		if (location.protocol != 'https:') {
			location.href = 'https:' + window.location.href.substring(window.location.protocol.length);
		}	


		$(document).ready(function() {
			$('select').material_select();
		});


	</script>

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

</style>
</head>
<body>

	<nav>
		<div class="nav-wrapper bbs">
			<a href="#" class="brand-logo center">Abiball Bild 2</a>
		</div>
	</nav>
	<main>
		<div class="container">
			<noscript>
				<p class="center">Diese Seite benötigt JavaScript. Bitte aktiviere Javescript oder frage einen Techniker.</p>
			</noscript>
			<p class="center">

				Die Bilder, die hier hochgeladen werden, sind für das Rückschau Video.
			</p>
			<p class="center">
				Es wurden schon <?php echo $amt?> Bilder eingereicht.
			</p>

			<div class="card green darken-3">
				<div class="card-content white-text">

					<form enctype="multipart/form-data" method="POST">

						<div class="row">
							<div class="input-field col s12">
								<select name="klasse" required="required">
									<option value="" disabled selected>Klasse</option>
									<option value="FT18A">FT18A</option>
									<option value="FT18B">FT18B</option>
									<option value="FT18C">FT18C</option>
									<option value="FW18A">FW18A</option>
									<option value="FW18B">FW18B</option>
									<option value="FW18C">FW18C</option>
									<option value="FW18D">FW18D</option>
									<option value="FW18E">FW18E</option>
								</select>
								<label>Klasse</label>
							</div>     				

							<div class="input-field col s12">
								<select name="jahr" required="required">
									<option value="" disabled selected>Jahr</option>
									<option value="1">11</option>
									<option value="2">12</option>
									<option value="3">13</option>
								</select>
								<label>Jahr</label>
							</div> 


							<div class="input-field col s12 ">
								<div class="file-field input-field">
									<div class="btn bbs">
										Foto auswählen<input name="userfile" type="file" />
									</div>
									<div class="file-path-wrapper">
										<input class="file-path validate" type="text">
									</div>
								</div>
							</div>

							<div class="input-field col s12">
								<div class="g-recaptcha" data-sitekey="6Lc7m0cUAAAAANx3Qzbzv1P5SIesxbjo-8pfokGd"></div>
							</div>

							<div class="input-field col s12">
								<button class="bbs btn" type="submit">
									Foto Hochladen
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</main>
	<footer class="page-footer bbs">
		<div class="footer-copyright">
			<div class="container">
				<p class="left">© 2018 Tim Lai</p>
				<p class="right">
					Die Bilder werden serverseitig Verschlüsselt auf AWS gespeichert.
				</p>
			</div>
		</div>
	</footer>	
</body>
</html>