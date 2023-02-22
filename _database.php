<?php

// connexion à la DB : serveur local, utilisateur, mdp, nom de la DB
$mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
    // vérification connexion
    if ($mysqli->connect_error)
    {
        echo("Échec de la connexion : " . $mysqli->connect_error);
        exit();
    }
    
?>