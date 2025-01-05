<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $firstname =htmlentities(trim($_POST["firstname"]));
    $lastname = htmlentities(trim($_POST["lastname"]));
    $email = htmlentities(trim($_POST["email"]));
    $password = htmlentities(trim($_POST["pass"]));
    $confirm = htmlentities(trim($_POST["confirm"]));

    $errors = [];

    // Validazione dei campi
    if(empty($firstname)){ $errors[] = "Il nome è obbligatorio.";}
    if (empty($lastname)){ $errors[] = "Il cognome è obbligatorio.";}
    if (empty($email)){ $errors[] = "L'email è obbligatoria.";}
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){ $errors[] = "Email non valida.";}
    if (empty($password)){ $errors[] = "La password è obbligatoria.";}
    if (strlen($password) < 8){ $errors[] = "La password deve contenere almeno 8 caratteri.";}
    if ($password !== $confirm){ $errors[] = "Le password non corrispondono.";}

    $hash = password_hash($password, PASSWORD_BCRYPT);

    // Connessione al database
    $conn = mysqli_connect("localhost", "GiuuG", "Samubruttatestadicazzo1", "BozzaProgetto");
    if (!$conn) {
        die("Connessione al database fallita: " . mysqli_connect_error());
    }

    $firstname = mysqli_real_escape_string($conn, $firstname);
    $lastname = mysqli_real_escape_string($conn, $lastname);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    $confirm = mysqli_real_escape_string($conn, $confirm);

    $stmt = $conn->prepare("INSERT INTO Users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstname, $lastname, $email, $hash);
    
    
    if(!($stmt->execute())){
        $errors[] = "Registrazione non riuscita riprova...";
    }
    
    

    $stmt->close();
    mysqli_close($conn);

    // Mostra errori o messaggio di successo
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<h1 class='error'>$error</p>";
        }
    } else {
        echo "<h1>Registrazione avvenuta con successo!</h1>";
    }
    header("Refresh:2, url=Form.html");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: green;
            text-align: center;
            margin-top: 20px;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div>
        
    </div>
</body>
</html>
