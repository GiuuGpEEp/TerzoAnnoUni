<?php
include '../dbConnection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id = $data['id'];
    $categoria = $data['categoria'];
    $giorno = $data['giorno'];
    $calendario = $data['calendario'];
    $oraInizio = $data['oraInizio'];
    $oraFine = $data['oraFine'];

    $stmt = $conn->prepare("UPDATE Corsi SET categoria = ?, giorno = ?, calendario = ?, oraInizio = ?, oraFine = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $categoria, $giorno, $calendario, $oraInizio, $oraFine, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}

$conn->close();
?>
