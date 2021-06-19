<?php
require_once 'vendor/autoload.php';

// $client = new Raven_Client('https://0dc42fff24d74155837ed6da29e27462:734f0a762a714debada77edfb9a6f9c8@sentry.io/295466');
// $error_handler = new Raven_ErrorHandler($client);
// $error_handler->registerExceptionHandler();
// $error_handler->registerErrorHandler();
// $error_handler->registerShutdownFunction();

class DB {

	static function connect() {
		$database = "abiball";
		if (@$dbCon = mysqli_connect('e7qyahb3d90mletd.chr7pe7iynqr.eu-west-1.rds.amazonaws.com', '###', '###', '###')) {
		} else {
			die("DB error");
		}

		$dbCon->set_charset('utf8');

		if(mysqli_connect_errno()) {
			echo "Fehler 101 " . mysqli_connect_error;
		}

		return $dbCon;
	}
	
	public static function query($dbq, $debug = 1) {
		$dbCon = DB::connect();

		if ($db_result = mysqli_query($dbCon, $dbq)) {
			$ar_result = array();
			$i = 0;
			if (!is_bool($db_result)) {
				while($row = mysqli_fetch_assoc($db_result)) {
					$ar_result[$i] = $row;
					$i++;
				}
				return $ar_result;
			} else {
				return $db_result;
			}
		} else {
			if ($debug) {
				http_response_code(500);
				echo "ERROR: \"$dbq\"<br /><br />";
				die(mysqli_error($dbCon));
			} else {
				return false;
			}
		}
	}


	public static function multi_query($dbq) {
		$dbCon = DB::connect();
		mysqli_multi_query($dbCon, $dbq);
		while (mysqli_next_result($dbCon)) {
			if (!mysqli_more_results($dbCon)) {
				break;
			}
			
		}
	}

	public static function escape($var) {
		$dbCon = DB::connect();
		return mysqli_real_escape_string($dbCon, $var);
	}
}
#------------------------------------------------------------------------------------------------------------
class Admin {
	# macht eine Liste
	public static function kartenlisteALT() {

		$ar_tickets = DB::query("SELECT id,name FROM tickets ");
		$ar_tische = DB::query("SELECT * FROM tisch ");

		echo "<table class='highlight'><thead><th>Nr.</th><th>Name</th><th>Tisch</th></thead>";



		foreach ($ar_tickets as $ticket) {
			echo "<tr>";
			echo "<td>";
			echo $ticket['id'];
			echo "</td>";			
			echo "<td>";
			echo $ticket['name'];
			echo "</td>";

			echo "<td>";

			foreach($ar_tische as $index => $tisch) {
				foreach (array_slice($tisch, 2) as $platz) {
					if ($ticket['id'] == $platz) {
						echo $index + 1 . "  ";
					}
				}
			}
			echo "</td>";
			
			echo "</tr>";
		}

		echo "</table>";
	}


	public static function kartenliste() {

		$ar_tickets = DB::query("SELECT * FROM tickets ");

		echo "<table class='highlight'><thead><th>Nr.</th><th>Name</th><th>Tisch</th></thead>";



		foreach ($ar_tickets as $ticket) {
			echo "<tr>";
			echo "<td>";
			echo $ticket['id'];
			echo "</td>";			
			echo "<td>";
			echo $ticket['name'];
			echo "</td>";
			echo "<td>";
			echo $ticket['tisch'];
			echo "</td>";
			echo "<td>";

			if (!empty($ticket['status'])) {
				echo date('H:i', $ticket['status']);
			}



			echo "</td>";
			
			echo "</tr>";
		}

		echo "</table>";		
	}

	public static function editTischListe() {



	// 	$ar_tische = DB::query("SELECT * FROM tisch");
	// 	$ar_tickets = DB::query("SELECT id,name FROM tickets ");
	// 	echo '<div class="row input-field">';

	// 	echo '<form class="tischlister" method="POST">';

	// 	echo '<button type="submit" class="tdbtn btn green darken-1">Speichern</button>';
	// 	echo '<table>';

	// 	foreach ($ar_tische as $tisch) {
	// 		echo "<tr>";
	// 		echo '<td class="center"><h1>'.$tisch['tisch_id'].'</h1></td>';

	// 		foreach (array_slice($tisch,2) as $key => $platz) {

	// 			if (!empty($platz)) {

	// 				foreach($ar_tickets as $ticket) {

	// 					if ($ticket['id'] == $platz) {
	// 						$n = $ticket['name'];
	// 						break;
	// 					}
	// 				}
	// 				$c = 'r';
	// 			} else {
	// 				$n = "";
	// 				$c = 'g';

	// 			}
	// 			echo "<td class=$c><table><tr><td>";
	// 			$field = $tisch['tisch_id'].$key;
	// 			echo '<input class="center-align"  type="number"  name="'.$field.'" id='.$field.' value="'.$platz.'" min="0" max="450"></input></td></tr><tr><td class="center" >';
	// 			echo '<span id="name'.$field.'">'.$n;
	// 			echo "</span></td></tr></table></td>";
	// 		}
	// 		echo "</tr>";
	// 	}
	// 	echo '</table></form></div>';
	}
}

class Ticket {

	public static function neuePlaetze($ar) {
		
		// $que = '';

		// foreach ($ar as $key => $v) {

		// 	$p1 = $v[1]; 
		// 	$p2 = $v[2];
		// 	$p3 = $v[3];
		// 	$p4 = $v[4]; 
		// 	$p5 = $v[5]; 
		// 	$p6 = $v[6]; 
		// 	$p7 = $v[7]; 
		// 	$p8 = $v[8]; 
		// 	$p9 = $v[9]; 
		// 	$p10 = $v[10];			


		// 	$que .= "UPDATE `tisch` SET 
		// 	`p1`='$p1', 
		// 	`p2`='$p2', 
		// 	`p3`='$p3', 
		// 	`p4`='$p4', 
		// 	`p5`='$p5', 
		// 	`p6`='$p6', 
		// 	`p7`='$p7', 
		// 	`p8`='$p8', 
		// 	`p9`='$p9', 
		// 	`p10`='$p10' 
		// 	WHERE `tisch_id`='$key'; 
		// 	";
		// }

		// DB::multi_query($que);

	}
	
	// public static function getTischnummerALT($id) {
	// 	if ($tn = DB::query("SELECT `tisch_id` FROM `tisch` WHERE 
	// 		p1 = $id OR 
	// 		p2 = $id OR 
	// 		p3 = $id OR 
	// 		p4 = $id OR 
	// 		p5 = $id OR 
	// 		p6 = $id OR 
	// 		p7 = $id OR 
	// 		p8 = $id OR 
	// 		p9 = $id OR 
	// 		p10 = $id"))
	// 	{

	// 		if (isset($tn[1])) {
	// 			return '- noch keine';
	// 		}

	// 		return $tn[0]['tisch_id'];
	// 	} else {
	// 		return "[noch keine]";
	// 	}
	// }









	
	public static function generate($a = 400, $b = 450) {
		#generiert neue PDFs aus alten UID

		# erstellt ein tmp Verzeichnis
		if (!file_exists('tmp')) {
			mkdir('tmp', 0777, true);		
		}

		#QR
		require_once("phpqrcode/qrlib.php");

		#PDF
		require_once('fpdf/FPDF.php');
		$pdf = new FPDF('L', 'mm', array('210','99'));

		#Erstellt ein PDF Vezeichnis
		if (!file_exists('tickets')) {
			mkdir('tickets', 0777, true);
		}

		# Seite
		$pdf->SetMargins(5, 0);
		$pdf->SetAutoPageBreak(false);
		$pdf->AddFont('helvetica','','helvetica.php ',true);

		for ($amt = $a; $amt <= $b; $amt++) {
			set_time_limit(5);
			$pdf->SetFont('helvetica','',12);

			$ar_tickets = DB::query("SELECT * FROM `tickets`");

			$uid = $ar_tickets[$amt - 1]['uid'];

			$nr = $amt;

			$validate_url = 'https://abiball.cf/?nr='.$nr.'&uid='.$uid;
			
			QRcode::png($validate_url, 'tmp/'.$nr.'.png', 'L', '4', 0, 3, 0);

			$uid_hum = substr($uid,0,4).'-'.substr($uid,4,4).'-'.substr($uid,8);

			$pdf->AddPage();

		#Position
			$pdf->SetY('11.5');

		#Felder

			$wdt = 25; 


			$pdf->Cell($wdt, 5, $nr, 1, 1, 'C');

			$pdf->Cell($wdt, $wdt, $pdf->Image('tmp/'.$nr.'.png', $pdf->GetX(), $pdf->GetY(), $wdt), 1);
			$pdf->Cell(0, $wdt, "", 0, 1);
			$pdf->SetFont('helvetica','',9);

			$pdf->Cell($wdt, 5, $uid_hum, 1, 1, 'C');

		}

		$pdf->Output('tickets/Ticket-'.$nr.'.pdf', 'F');
		$pdf->Output('Ticket-'.$nr.'.pdf', 'I');	
	}
	
	public static function generateNew($anzahl = 50) {
		
		die();

	#Generiet anzahl an Tickets in der Datenbank

		# erstellt ein tmp Verzeichnis
		if (!file_exists('tmp')) {
			mkdir('tmp', 0777, true);
		}

		#QR
		require("phpqrcode/qrlib.php");

        #PDF
		require_once('fpdf/FPDF.php');
		$pdf = new FPDF('L', 'mm', array('210','99'));

		#Erstellt ein Backup Vezeichnis
		if (!file_exists('tickets')) {
			mkdir('tickets', 0777, true);
		}

		# Seite
		$pdf->SetMargins(5, 0);
		$pdf->SetAutoPageBreak(false);
		$pdf->AddFont('helvetica','','helvetica.php ',true);
		$pdf->SetFont('helvetica','',12);

		for ($amt=0 ; $amt < $anzahl; $amt++) {
			$uid = random_int(100000000 , 999999999);

			$ar_tickets = DB::query("SELECT * FROM `tickets`");

			$nr = count($ar_tickets) + 1;

			DB::query("INSERT INTO `tickets` (`id`, `uid`, `status`) VALUES (NULL, '$uid', '0');");
			$validate_url = 'abiball.cf/?nr='.$nr.'&uid='.$uid;			
			QRcode::png($validate_url, 'tmp/'.$nr.'.png', 'L', '4', 0, 3, 0);

			$uid_hum = substr($uid,0,4).'-'.substr($uid,4,4).'-'.substr($uid,8);

			$pdf->AddPage();

		#Position von unten
			$pdf->SetY('20');

		#Felder
			$pdf->Cell(30, 5, $nr, 1, 1, 'C');

			$pdf->Cell(30, 30, $pdf->Image('tmp/'.$nr.'.png', $pdf->GetX(), $pdf->GetY(), 30), 1);
			$pdf->Cell(0, 30, "", 0, 1);
			$pdf->Cell(30, 5, $uid_hum, 1, 1, 'C');

		}

		//$pdf->Cell(0, 15, $comment, 'TRB', 0, 'C');
		$pdf->Output('tickets/Ticket-'.$nr.'.pdf', 'F');
		$pdf->Output('Ticket-'.$nr.'.pdf', 'I');	
	}

	public static function getInfo($uid, $nr) {

		$uid = DB::escape($uid);
		$nr = DB::escape($nr);

		if ($ticket = DB::query("SELECT * FROM `tickets` WHERE `id` = $nr")) {

			if ($uid == $ticket[0]['uid']) {
				return array($ticket[0]['tisch'], $ticket[0]['name']);
			} else {
				echo "INVALIDES TICKET";
				die();
				return false;
			}
		}
	}

}

class Login {
	public static function check()	{
		@session_start();
		if (!empty($_SESSION['abiball_user'])) {
			return true;
		} 

		return false;
	}
	//von http://php.net/manual/en/function.crypt.php
	static function generate_hash($password, $cost=11){ 
		$salt=substr(base64_encode(openssl_random_pseudo_bytes(17)),0,22);
		$salt=str_replace("+",".",$salt);
		$param='$'.implode('$',array(
					"2y", //select the most secure version of blowfish (>=PHP 5.3.7)
					str_pad($cost,2,"0",STR_PAD_LEFT), //add the cost in two digits
					$salt //add the salt
				));

		return crypt($password,$param);
	}

	static function validate_pw($password, $hash){
		return crypt($password, $hash)==$hash;
	}
}
?>
