<?php
    session_start();
    
    if (!isset($_SESSION['username'])) {
        header("Location: ../Registration-login/Form.html");
        exit();
    }
    
    $_SESSION = []; // Reset dell'array di sessione
    
    session_destroy(); 
    header('Location: https://site.tld'); 
    exit; // Fine script

    
?>