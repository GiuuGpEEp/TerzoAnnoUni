session_start();
if (!isset($_SESSION['username'])) {
    window.alert("Effettua il login per accedere a questa pagina.");
    header("Location: ../Registration-login/Form.php");
    exit();
}