<?php
    // on stocke l'id de la personne connectée dans une variable pour pouvoir le réutiliser
    $loggedUserId = $_SESSION["connected_id"];
    
    // on définit la requête
    $usersQuery = "SELECT * FROM `users` WHERE id= '$loggedUserId' ";
    // on envoie la requête
    $usersQueryInfo = $mysqli->query($usersQuery);
    // on récupère les données sous forme de tableau qu'on stocke dans une variable
    $user = $usersQueryInfo->fetch_assoc();
?>