<?php 

require_once("../php.php");



echo "<table>";
foreach (DB::query("SELECT * FROM nuwsg3szjgc7s9nc.bilder;") as $zeile) {
	echo "<tr>";

	echo "<td>".$zeile['idbilder']."</td>";
	echo "<td>".$zeile['klasse']."</td>";
	echo "<td><a href='".$zeile['url']."'>".$zeile['name']." ".$zeile['vorname']."</a></td>";
	echo "<td>".$zeile['ip']."</td>";
	
	echo "</tr>";

}

echo "</table>";
?>