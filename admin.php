<?php
    session_start();
    include('_header.php');
    include('_database.php');
?>

<!doctype html>
<html>
    <head>
        <title>ReSoC - Administration</title> 
    </head>
    <body>
        <div id="wrapper" class='admin'>
            <aside>
                <h2>Mots-clés</h2>
                
                <?php
                // définition de la requête
                $tagsQuery = "SELECT * FROM `tags` LIMIT 50";
                // envoi de la requête
                $tagsQueryInfo = $mysqli->query($tagsQuery);
                // check fonctionnement de la requête
                if ( ! $tagsQueryInfo)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                    exit();
                }

                // récupération des données sous forme de tableau / boucle pour les intégrer dans la page
                while ($tag = $tagsQueryInfo->fetch_assoc())
                {
                ?>
                    <article>
                        <h3><?php echo $tag['label'] ?></h3>
                        <p>id:<?php echo $tag['id'] ?></p>
                        <nav>
                            <a href="tags.php?tag_id=<?php echo $tag['id'] ?>">Messages</a>
                        </nav>
                    </article>
                <?php } ?>
            </aside>
            
            <main>
                <h2>Utilisatrices</h2>
                
                <?php
                // définition de la requête
                $usersQuery = "SELECT * FROM `users` LIMIT 50";
                // envoi de la requête
                $usersQueryInfo = $mysqli->query($usersQuery);
                // check fonctionnement de la requête
                if ( ! $usersQueryInfo)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                    exit();
                }

                // récupération des données sous forme de tableau / boucle pour les intégrer dans la page
                while ($user = $usersQueryInfo->fetch_assoc())
                {
                ?>
                    
                    <article>
                        <h3><?php echo $user['alias'] ?></h3>
                        <p>id:<?php echo $user['id'] ?></p>
                        <nav>
                            <a href="wall.php?user_id=<?php echo $user['id'] ?>">Mur</a>
                            | <a href="feed.php?user_id=<?php echo $user['id'] ?>">Flux</a>
                            | <a href="settings.php?user_id=<?php echo $user['id'] ?>">Paramètres</a>
                            | <a href="followers.php?user_id=<?php echo $user['id'] ?>">Suiveurs</a>
                            | <a href="subscriptions.php?user_id=<?php echo $user['id'] ?>">Abonnements</a>
                        </nav>
                    </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
