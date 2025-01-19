<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => true, 'message' => 'Devi effettuare il login per annullare la prenotazione.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include '../dbConnection.php';

    $data = json_decode(file_get_contents("php://input"), true);
    $idCorso = $data['idCorso'];
    $email = $_SESSION['username'];

    $stmt = $conn->prepare("DELETE FROM prenotazioni WHERE corso_id = ? AND email = ?");
    $stmt->bind_param("is", $idCorso, $email);

    if ($stmt->execute()) {
        echo json_encode(['error' => false, 'message' => 'Prenotazione annullata con successo.']);
    } else {
        echo json_encode(['error' => true, 'message' => 'Errore durante l\'annullamento della prenotazione.']);
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    echo json_encode(['error' => true, 'message' => 'Metodo non valido.']);
    exit();
}
?>
