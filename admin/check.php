<?php
// Permet de verifier des injections
// This function check the data to prevent SQL Injection by removing the slash, special char  et also triming

function checkInput($valeur){ 
    $valeur = trim($valeur);
    $valeur = stripslashes($valeur);
    $valeur = htmlspecialchars($valeur);
    return $valeur;
}
?>