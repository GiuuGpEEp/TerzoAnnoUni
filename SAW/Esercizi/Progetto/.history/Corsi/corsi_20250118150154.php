<?php
session_start();

if(!isset($_SESSION['username']) ){
    $buttonClass = "prenotationButtonNoLogin";
}else {
    $buttonClass = "prenotationButtonLogin";
}

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
                </div>
                <div class="colonnaCorso" id="colonnaRagazzi">
                    <div class="corsoTitle">Corso Ragazzi</div>
                </div>
                <div class="colonnaCorso" id="colonnaAdulti">
                    <div class="corsoTitle">Corso Adulti</div>
                </div>
            </div>
        </div>
        <?php include '../Footer/footer.php'; ?>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const buttonClass = "<?php echo $buttonClass; ?>"; 
    fetch('corsi.php?get_course=true')
    .then(response => {
        if(!response.ok) {
            throw new Error('Errore nella richiesta');
        }
        return response.json();
    })
    .then(data => {
        const colonnaBambini = document.getElementById('colonnaBambini');
        const colonnaRagazzi = document.getElementById('colonnaRagazzi');
        const colonnaAdulti = document.getElementById('colonnaAdulti');

        data.forEach(corso => {
            const container = document.createElement('div');
            container.classList.add('containerSingoloCorso');
            container.innerHTML = `
                <div class="corsoDescrizione">
                    <p class="giornoCorso">${corso.giorno}  ${corso.calendario}</p>
                    <p class="orarioCorso">${corso.oraInizio} <br> ${corso.oraFine}</p>
                </div>
                <button class="${buttonClass}" <?php if >Prenotati</button> `;
            if(corso.categoria === 'bambini') {
                colonnaBambini.appendChild(container);
            }
            if(corso.categoria === 'ragazzi') {
                colonnaRagazzi.appendChild(container);
            }
            if(corso.categoria === 'adulti') {
                colonnaAdulti.appendChild(container);
            }
        });
    })
    .catch(error => {
        console.error("There was a problem with the fetch operation:",error);
    });
});
</script>
</html>