<?php
    session_start();
    
    if (!isset($_SESSION['username'])) {
        header("Location: ../Registration-login/Form.php");
        exit();
    }
    
    $_SESSION = []; 
    session_destroy(); 
    header("Location: ../Registration-login/Form.php"); 
    exit();

?>