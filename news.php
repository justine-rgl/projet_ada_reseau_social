<?php
session_start();
?>
<!doctype html>
<html>
    <head>
        <?php include('_header.php'); ?>
        <title>ReSoC - Actualités</title> 
        
    </head>
    <body>
        <div id="wrapper">
    
            <?php
            include('database.php');
            $userId = intval($_GET['user_id']);
            ?>

            <aside>
                <?php
                $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                ?>

                <img src="resoc_panda.png" alt="Portrait de l'utilisatrice"/>
                
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les derniers messages de
                        tous les utilisatrices du site.</p>
                </section>
            </aside>
            <main>  

            <?php 
                $enCoursDeTraitement = isset($_POST['Like']);
                    if ($enCoursDeTraitement)
                    {   
                        $new_like = $_POST['Like'];
                        $new_like = $mysqli->real_escape_string($new_like);  
                                        
                        $addNewLike = "INSERT INTO likes "
                            . "(id, user_id, post_id) "
                            . "VALUES (NULL, "
                            . $_SESSION["connected_id"] .", "
                            . $_GET['post_id'] ." );"
                            ;
                        $mysqli->query($addNewLike);
                        header("refresh:0");
                    }
            ?>

                <?php
                $messageRequest = "
                    SELECT posts.content,
                    posts.created,
                    posts.id as post_id,
                    users.id as user_id,
                    users.alias as author_name, 
                    COUNT(DISTINCT likes.id) as like_number,   
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                    GROUP_CONCAT(DISTINCT tags.id) AS tagidlist
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id = posts.id
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    LIMIT 500
                    ";
                
                $messageRequestInfos = $mysqli->query($messageRequest);

                if ( ! $messageRequestInfos)
                {
                    echo "<article>";
                    echo("Échec de la requete : " . $mysqli->error);
                    echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$messageRequest</code></p>");
                    exit();
                }
                while ($post = $messageRequestInfos->fetch_assoc())
                {
                ?>

                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>
                        <address>par <a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                        
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>
                        <footer>
                            <small>
                                <form action="news.php?post_id=<?php echo $post['post_id'] ?>" method="post">
                                    <input type='submit' name="Like" value="💜">
                                    <?php echo $post['like_number'] ?> 
                                </form>  
                            </small>
                            <?php include('_tags.php'); ?>
                        </footer>
                    </article>
                    <?php } ?>                    
            </main>
        </div>
    </body>
</html>
