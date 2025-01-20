<?php
session_start();

// Controlla se l'utente è loggato e imposta la classe del bottone
if (!isset($_SESSION['username'])) {
    $buttonClass = "prenotationButtonNoLogin";
} else {
    $buttonClass = "prenotationButtonLogin";
}

if (isset($_GET['get_course']) || isset($_GET['search'])) {
    include '../dbConnection.php';

    // Ottieni l'email dell'utente se loggato
    $email = isset($_SESSION['username']) ? $_SESSION['username'] : null;

    // Prepara la query per i corsi
    $query = "SELECT * FROM Corsi";
    $params = [];
    $types = "";

    // Se è presente un parametro di ricerca, aggiungi un filtro alla query
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = "%" . $_GET['search'] . "%";
        $query .= " WHERE nomeCorso LIKE ?";
        $params[] = $searchTerm;
        $types .= "s";
    }

    // Esegui la query
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $corsi = [];
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $corsi[] = $row;
            }
        }
    }

    // Ottieni gli id dei corsi per cui l'utente è già registrato
    $userCourses = [];
    if ($email) {
        $checkQuery = $conn->prepare("SELECT corso_id FROM prenotazioni WHERE email = ?");
        $checkQuery->bind_param("s", $email);
        if ($checkQuery->execute()) {
            $result = $checkQuery->get_result();
            while ($row = $result->fetch_assoc()) {
                $userCourses[] = $row['corso_id'];
            }
        }
        $checkQuery->close();
    }

    $stmt->close();
    $conn->close();

    // Combina le informazioni sui corsi e le prenotazioni dell'utente
    $response = [
        'corsi' => $corsi,
        'userCourses' => $userCourses
    ];

    // Restituisci i dati come JSON
    header('Content-Type: application/json');
    echo json_encode($response);
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
            <form id="searchBar" action="search.php" method="get">
                    <div class="searchContainer">
                        <input type="text" placeholder="Cerca..." name="search" required>
                        <button type="submit" id="searchButton">Cerca</button>        
                    </div>
                </form>
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
    
</script>
</html>
