<?php
session_start();
?>
<!doctype html>
<html>
    <head>
        <?php include('_header.php'); ?>
        <title>ReSoC - Mes abonnements</title> 
    </head>
    <body>
        <div id="wrapper">
            <aside>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>
                        Sur cette page vous trouverez la liste des personnes dont l'utilisatrice n° <?php echo intval($_GET['user_id']) ?> suit les messages
                    </p>
                </section>
            </aside>
            
            <main class='contacts'>
                <?php
                $userId = intval($_GET['user_id']);
                include('database.php');
                
                $laQuestionEnSql = "
                    SELECT users.* 
                    FROM followers 
                    LEFT JOIN users ON users.id=followers.followed_user_id 
                    WHERE followers.following_user_id='$userId'
                    GROUP BY users.id
                    ";
                
                $lesInformations = $mysqli->query($laQuestionEnSql);
                while ($post = $lesInformations->fetch_assoc())
                {
                ?>
                
                <article>
                    <img src="user.jpg" alt="blason"/>
                    <h3><a href="wall.php?user_id=<?php echo $post['id'] ?>"><?php echo $post['alias'] ?></a></h3>
                    <p>id:<?php echo $post['id'] ?></p>                    
                </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
