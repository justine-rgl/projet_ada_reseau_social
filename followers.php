<?php
    session_start();
    include('_header.php');
    include('_database.php');
    include('_loggedUserQuery.php');
?>

<!doctype html>
<html>
    <head>
        <title>ReSoC - Mes abonnés </title>
    </head>
    <body>
        <div id="wrapper">          
            <aside>                
                <img src="<?php echo $user['pictures']?>" alt="Portrait de l'utilisatrice"/>               
                
                <section>
                    <h3><?php echo $user['alias'] ?>'s followers</h3>
                    <p>Sur cette page vous trouverez la liste des Pandas qui
                        suivent vos messages.</p>
                </section>
            </aside>
            
            <main class='contacts'>
                <?php         
                // on requête toutes les infos des users concernés pour les afficher en tant que followers       
                $myFollowersQuery = "
                    SELECT users.*
                    FROM followers
                    LEFT JOIN users ON users.id=followers.following_user_id
                    WHERE followers.followed_user_id='$loggedUserId'
                    GROUP BY users.id
                    ";
                
                // on envoie la requête
                $myFollowersQueryInfo = $mysqli->query($myFollowersQuery);
                
                // on récupère les infos dans un tableau qu'on stocke dans une variable
                while ($follower = $myFollowersQueryInfo->fetch_assoc())
                {
                ?>
                
                    <article>
                        <img src="<?php echo $follower['pictures'] ?>" alt="blason"/>
                        <h3><a href="wall.php?user_id=<?php echo $follower['id'] ?>"><?php echo $follower['alias'] ?></a></h3>
                    </article>
                <?php } ?> 
            </main>
        </div>
    </body>
</html>
