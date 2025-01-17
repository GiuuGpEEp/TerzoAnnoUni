<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $messaggio = htmlspecialchars($_POST['message']);

    $errors = [];

    // Validazione dei campi
    if(empty($nome)){ $errors[] = "Il nome è obbligatorio.";}
    if (empty($email)){ $errors[] = "L'email è obbligatoria.";}
    if (empty($messaggio)){ $errors[] = "Il messaggio è obbligatorio.";}

    if(empty($errors)){
        
        $conn = mysqli_connect("localhost", "root", "", "bozzadb");
        if (!$conn) {
            die("Connessione al database fallita: " . mysqli_connect_error());
        }

        $data = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("INSERT INTO mails (nome, email, messaggio, dataInvio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $email, $messaggio, $data);

        if(!$stmt->execute()){
            $errors[] = "Errore durante l'invio del messaggio. Riprova più tardi.";
        }

        $stmt->close();
        $conn->close();
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<h1 class='error'>$error</h1>";
        }
        echo "<script>
            window.alert('Si è verificato un errore nella richiesta, alcuni campi potrebbero essere vuoti, o potrebbero esserci altre problematiche');
         </script>"; 
        header("Refresh:2, url=contact.php");
    }else{
        echo "<h1>Messaggio inviato con successo!</h1>";
        echo "<p> Grazie per il messaggio, provvederemo a rispondere appena possibile. <br> </p>";
        echo "<p>Se tra meno di 10 secondi non vieni reindirizzato alla pagina, clicca <a href='contact.php'>qui</a> </p>";
        header("Refresh:10, url=contact.php");
    }    
}else {
    echo "<script>
            window.alert('Si è verificato un errore nella richiesta, metodo non valido.');
         </script>"; 
    header("Location: contact.php");
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
