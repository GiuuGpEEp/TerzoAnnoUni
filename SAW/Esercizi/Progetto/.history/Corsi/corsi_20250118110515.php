<?php
session_start();

if(isset($_GET['get_course'])){
    
    include '../dbConnection.php';

    $stmt = $conn->prepare("SELECT * FROM Corsi");
    if($stmt->execute()){
        $result = $stmt->get_result();
        $corsi = [];                  
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $corsi[] = $row;
            }
        }
    }
    $stmt->close();
    $conn->close();

     // Restituisci i dati come JSON
     header('Content-Type: application/json');
     echo json_encode($corsi);
     exit; // Termina il codice per non visualizzare il resto della pagina HTML
}    
?>

<!DOCTYPE html>
<html>
<head>
    <title>PA - Corsi</title>
    <link rel="icon" href="../Logo32.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="corsiStyle.css">
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <?php include '../Navbar/navbar.php'; ?>
        </header>
        <div class="content">
            <div class="title">
                <h1>Corsi</h1>
                <p>Scopri i nostri corsi e scegli quello che fa per te. <br></p>
            </div>
            <div class="tabellaCorsi">
                <div class="colonnaCorso" id="colonnaBambini">
                    <div class="corsoTitle">Corso Bambini</div>
                    <div class="containerSingoloCorso">
                        <div class="corsoDescrizione">
                            <p class="giornoCorso">Lunedì</p>
                            <p class="orarioCorso">16:00 <br> 17:00</p>
                        </div>
                        <button class="prenotationButton">Prenotati</button>
                    </div>
                    <div class="containerSingoloCorso">
                        <div class="corsoDescrizione">
                            <p class="giornoCorso">Lunedì</p>
                            <p class="orarioCorso">16:00 <br> 17:00</p>
                        </div>
                        <button class="prenotationButton">Prenotati</button>
                    </div>
                </div>
                <div class="colonnaCorso">
                    <div class="corsoTitle">Corso Ragazzi</div>
                    <div class="containerSingoloCorso">
                        <div class="corsoDescrizione">
                            <p class="giornoCorso">Martedì</p>
                            <p class="orarioCorso">16:00 <br> 17:00</p>
                        </div>
                        <button class="prenotationButton">Prenotati</button>
                    </div>
                </div>
                <div class="colonnaCorso" id="colonnaAdulti">
                    <div class="corsoTitle">Corso Adulti</div>
                    <div class="containerSingoloCorso">
                        <div class="corsoDescrizione">
                            <p class="giornoCorso">Mercoledì</p>
                            <p class="orarioCorso">16:00 <br> 17:00</p>
                        </div>
                        <button class="prenotationButton">Prenotati</button>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../Footer/footer.php'; ?>
    </div>
</body>
</html>