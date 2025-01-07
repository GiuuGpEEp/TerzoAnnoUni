<?php

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $email =htmlentities(trim($_POST["email"]));
    $password = htmlentities(trim($_POST["pass"]));

    $errors = [];

    if (empty($email)){ $errors[] = "L'email è obbligatoria.";}
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){ $errors[] = "Email non valida.";}
    if (empty($password)){ $errors[] = "La password è obbligatoria.";}
    if (strlen($password) < 8){ $errors[] = "La password deve contenere almeno 8 caratteri.";}

    $conn = mysqli_connect("localhost", "root", "", "bozzadb");
    if (!$conn) {
        die("Connessione al database fallita: " . mysqli_connect_error());
    }

    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $stmt = $conn->prepare("SELECT email FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    if(!($stmt->execute())){
        $errors[] = "Errore nell'esecuzione...";
    }
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt = $conn->prepare("SELECT password FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            echo "Login effettuato con successo.";
        } else {
            $errors[] = "Password errata.";
        }
    } else {
        $errors[] = "Email non trovata.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    
</body>
</html>