<?php
session_start();

header('Content-Type: application/json'); // Restituisci sempre JSON

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => true, 'message' => 'Effettua il login per accedere a questa pagina.']);
    http_response_code(401); // Non autorizzato
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];

    // Decodifica i dati JSON inviati
    $data = json_decode(file_get_contents("php://input"), true);
    $idCorso = $data['idCorso'] ?? null; // Usa null se non è presente
    $email = $_SESSION['username'];
    
    // Validazione dei dati
    if (empty($idCorso)) {
        $errors[] = "Errore: id corso non specificato.";
    }

    if (empty($errors)) {
        include '../dbConnection.php';

        // Controlla se la prenotazione esiste già
        $checkDuplicateQuery = $conn->prepare("SELECT * FROM prenotazioni WHERE corso_id = ? AND email = ?");
        $checkDuplicateQuery->bind_param("is", $idCorso, $email);
        $checkDuplicateQuery->execute();
        $duplicateResult = $checkDuplicateQuery->get_result();

        if ($duplicateResult->num_rows > 0) {
            echo json_encode(['error' => true, 'message' => 'Sei già registrato a questo corso.']);
            http_response_code(409); // Conflitto
            exit();
        }

        // Controlla se il corso esiste
        $checkCourseQuery = $conn->prepare("SELECT * FROM corsi WHERE id = ?");
        $checkCourseQuery->bind_param("i", $idCorso);
        if (!$checkCourseQuery->execute()) {
            echo json_encode(['error' => true, 'message' => 'Errore durante il controllo del corso.']);
            http_response_code(500); // Errore del server
            exit();
        }

        if ($checkCourseQuery->get_result()->num_rows > 0) {
            // Inserisci la prenotazione
            $stmt = $conn->prepare("INSERT INTO prenotazioni (corso_id, email) VALUES (?, ?)");
            $stmt->bind_param("is", $idCorso, $email);
            if (!$stmt->execute()) {
                echo json_encode(['error' => true, 'message' => 'Errore durante la prenotazione.']);
                http_response_code(500); // Errore del server
                exit();
            }

            // Prenotazione avvenuta con successo
            echo json_encode(['error' => false, 'message' => 'Prenotazione effettuata con successo!']);
            exit();
        } else {
            echo json_encode(['error' => true, 'message' => 'Il corso non esiste.']);
            http_response_code(404); // Non trovato
            exit();
        }
    } else {
        // Errore nella validazione dei dati
        echo json_encode(['error' => true, 'message' => implode(', ', $errors)]);
        http_response_code(400); // Richiesta errata
        exit();
    }
} else {
    // Metodo HTTP non valido
    echo json_encode(['error' => true, 'message' => 'Metodo HTTP non valido.']);
    http_response_code(405); // Metodo non consentito
    exit();
}
?>
