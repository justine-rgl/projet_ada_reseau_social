<?php
    session_start();
    include('_header.php');
    include('_database.php');
    include('_loggedUserQuery.php');
?>

<!doctype html>
<html >
    <head>
        <title>ReSoC - Paramètres</title> 
    </head>
    <body>
        <div id="wrapper" class='profile'>

            <aside>
                <img src="<?php echo $user['pictures']?>" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Paramètres</h3>
                    <p>Sur cette page vous trouverez vos informations personnelles.</p>
                </section>
            </aside>
            
            <main>
                <?php
                // requête des infos concernant le user
                $loggedUserQuery = "
                    SELECT users.*, 
                    COUNT(DISTINCT posts.id) as totalpost, 
                    COUNT(DISTINCT given.post_id) as totalgiven, 
                    COUNT(DISTINCT recieved.user_id) as totalrecieved 
                    FROM users 
                    LEFT JOIN posts ON posts.user_id=users.id 
                    LEFT JOIN likes as given ON given.user_id=users.id 
                    LEFT JOIN likes as recieved ON recieved.post_id=posts.id 
                    WHERE users.id = '$loggedUserId' 
                    GROUP BY users.id
                    ";

                // envoi de la requête
                $loggedUserQueryInfo = $mysqli->query($loggedUserQuery);
                if ( ! $loggedUserQueryInfo)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }
                
                // on récupère (tableau) et on stocke (variable) les infos de la DB
                $user = $loggedUserQueryInfo->fetch_assoc();
                ?>                
                
                <article class='parameters'>
                    <dl>
                        <dt>Pseudo</dt>
                        <dd><?php echo $user['alias'] ?></dd>
                        <dt>Email</dt>
                        <dd><?php echo $user['email'] ?></dd>
                        <dt>Nombre de messages postés</dt>
                        <dd><?php echo $user['totalpost'] ?></dd>
                        <dt>Nombre de "J'aime" donnés </dt>
                        <dd><?php echo $user['totalgiven'] ?></dd>
                        <dt>Nombre de "J'aime" reçus</dt>
                        <dd><?php echo $user['totalrecieved'] ?></dd>
                    </dl>
                </article>
            </main>
        </div>
    </body>
</html>
