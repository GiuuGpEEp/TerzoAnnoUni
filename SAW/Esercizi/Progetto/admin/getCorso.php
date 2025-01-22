<?php
include '../dbConnection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM Corsi WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $corso = $result->fetch_assoc();
    echo json_encode($corso);
    $stmt->close();
}

$conn->close();
?>
