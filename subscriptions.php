<?php
    session_start();
    include('_header.php');
    include('_database.php');
    include('_loggedUserQuery.php');
?>

<!doctype html>
<html>
    <head>
        <title>ReSoC - Mes abonnements</title> 
    </head>
    <body>
        <div id="wrapper">
            <aside>
                <img src="<?php echo $user['pictures']?>" alt="Portrait de l'utilisatrice"/>
                
                <section>
                    <h3>Présentation</h3>
                    <p>
                        Sur cette page vous trouverez la liste des personnes dont l'utilisatrice n° <?php echo intval($_GET['user_id']) ?> suit les messages
                    </p>
                </section>
            </aside>
            
            <main class='contacts'>
                <?php
                // on requête toutes les infos des users concernés pour les afficher en tant que personnes suivies
                $mySubscriptionsQuery = "
                    SELECT users.* 
                    FROM followers 
                    LEFT JOIN users ON users.id=followers.followed_user_id 
                    WHERE followers.following_user_id='$loggedUserId'
                    GROUP BY users.id
                    ";
                

                $mySubscriptionsQueryInfo = $mysqli->query($mySubscriptionsQuery);
                while ($followed = $mySubscriptionsQueryInfo->fetch_assoc())
                {
                ?>
                
                <article>
                    <img src="<?php echo $followed['pictures'] ?>" alt="blason"/>
                    <h3><a href="wall.php?user_id=<?php echo $followed['id'] ?>"><?php echo $followed['alias'] ?></a></h3>
                </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
