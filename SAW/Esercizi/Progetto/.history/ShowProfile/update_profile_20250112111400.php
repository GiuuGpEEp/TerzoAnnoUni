<?php
session_start();
if (!isset($_SESSION['username'])) {
    window.alert("Effettua il login per accedere a questa pagina.");
    header("Location: ../Registration-login/Form.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $errors = [];

    $firstname =htmlentities(trim($_POST["firstname"]));
    $lastname = htmlentities(trim($_POST["lastname"]));
    $email = htmlentities(trim($_POST["email"]));
    $età = htmlentities(trim($_POST["età"]));
    $genere = htmlentities(trim($_POST["genere"]));
    $descrizione = htmlentities(trim($_POST["descrizione"]));

    if(empty($firstname)){ $errors[] = "Il nome è obbligatorio.";}
    if (empty($lastname)){ $errors[] = "Il cognome è obbligatorio.";}
    if (empty($email)){ $errors[] = "L'email è obbligatoria.";}
    if ("empty($email) &&!filter_var($email, FILTER_VALIDATE_EMAIL)){ $errors[] = "Email non valida.";}
    
    if(empty ($errors)){

        $conn = mysqli_connect("localhost", "root", "", "bozzadb");
        if (!$conn) {
            die("Connessione al database fallita: " . mysqli_connect_error());
        }
        
        $firstname = mysqli_real_escape_string($conn, $firstname);
        $lastname = mysqli_real_escape_string($conn, $lastname);
        $email = mysqli_real_escape_string($conn, $email);
        $età = mysqli_real_escape_string($conn, $età);
        $genere = mysqli_real_escape_string($conn, $genere);
        $descrizione = mysqli_real_escape_string($conn, $descrizione);

        $username = $_SESSION['username'];
        $stmt = $conn->prepare("UPDATE Users SET firstname= ?, lastname= ?, email= ?, descrizione= ?, età= ?, genere= ? WHERE email = ?");
        $stmt->bind_param("ssssiss", $firstname, $lastname, $email, $descrizione, $età, $genere, $username);

        if(!($stmt->execute())){
            $errors[] = "Registrazione non riuscita riprova...";
        }

        $_SESSION['username'] = $email;

        $stmt->close();
        $conn->close();
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<h1 class='error'>$error</h1>";
        }
        echo "<script>
            window.alert('Si è verificato un errore, nella richiesta');
         </script>";   
        header("Refresh:2, url=update_page.php");
    }else {
        echo "<h1>Aggiornamento del profilo avvenuto con successo!</h1>";
        header("Refresh:2, url=../ShowProfile/show_profile.php");
    }


} else {
    echo "<script>
            window.alert('Si è verificato un errore nella richiesta.');
         </script>";   
    header("Location: Form.php");
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
