<?php
session_start();
if (!isset($_SESSION['username'])) {
    window.alert("Effettua il login per accedere a questa pagina.");
    header("Location: ../Registration-login/Form.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $errors = [];

    $data = json_decode(file_get_contents("php://input"), true);
    $idCorso = $data['idCorso'];
    $email = $_SESSION['username'];
    
    if(empty($corso_id)){
        $errors[] = "Errore: id corso non specificato";
    }
    
    if(empty($errors)){
        include '../dbConnection.php';
        $checkQuery = $conn->prepare("SELECT * FROM corsi WHERE id = ? ");
        $checkQuery->bind_param("i", $idCorso);
        if(!$checkQuery->execute()){
            $errors[] = "Errore: impossibile eseguire la query";
            break;
        }
        if($checkQuery->get_result()->num_rows > 0){
            $stmt = $conn->prepare("INSERT INTO prenotazioni (corso_id, email) VALUES (?, ?)");
            $stmt->bind_param("is", $idCorso, $email);
            if(!$stmt->execute()){
                $errors[] = "Errore: impossibile eseguire la query";
                break;
            }
            $stmt->close();
        }
    }
    
    $checkQuery->close();
    $conn->close();

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<h1 class='error'>$error</h1>";
        }
        echo "<script>
            window.alert('Si è verificato un errore nella richiesta');
         </script>"; 
        header("Refresh:2, url=contact.php");
    }else{
        echo "<h1>Prenotazione effettuata con successo!</h1>";
        echo "<p>Se tra meno di 10 secondi non vieni reindirizzato alla pagina, clicca <a href='contact.php'>qui</a> </p>";
        header("Refresh:10, url=contact.php");
    }    
}else {
    echo "<script>
            window.alert('Si è verificato un errore nella richiesta, metodo non valido.');
         </script>"; 
    header("Location: contact.php");
}
