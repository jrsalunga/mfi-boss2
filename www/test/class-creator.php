<?php
include_once('../../lib/initialize.php');




$sql = "DESCRIBE apledger";
$rows = $database->query($sql);

while($row = $database->fetch_row($rows)) {

	echo "'".$row[0]."' ,";
}

echo"<br><br>";


$rows = $database->query($sql);

while($row = $database->fetch_row($rows)) {
	echo "public $".$row[0].";<br>";

}

?>