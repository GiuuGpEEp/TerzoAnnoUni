$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Bozzadb";

$conn = mysqli_connect("localhost", "root", "", "bozzadb");
   
if (!$conn) {
    die("Connessione al database fallita: " . mysqli_connect_error())
}

