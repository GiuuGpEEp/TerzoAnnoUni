<?php
$servername = "localhost";
$username = "s5581676";
$password = "ServerS4w_Pr0gettoFinaLe24-25";
$dbname = "s5581676";

$conn = mysqli_connect($servername, $username, $password, $dbname);
   
if (!$conn) {
    die("Connessione al database fallita: " . mysqli_connect_error());
}
?>

