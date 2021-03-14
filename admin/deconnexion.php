<?php
    session_start(); // Doit etre mise pour savoir qui se deconnecte
    session_destroy(); // detruit toutes les sessions
    header("Location: ../login.php"); // redirection en page de login
?>