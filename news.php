<?php
    session_start();
    include('_header.php');
    include('_database.php');
    include('_loggedUserQuery.php');
?>

<!doctype html>
<html>
    <head>
        <title>ReSoC - Actualités</title> 
    </head>
    <body>
        <div id="wrapper">
             <aside>
                <img src="pictures/resoc_panda.png" alt="Portrait de l'utilisatrice"/>
                
                <section>
                    <h3>Jungle news</h3>
                    <p>Sur cette page vous trouverez les dernières news de la jungle.</p>
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
                            . $loggedUserId .", "
                            . $_GET['post_id'] ." );"
                            ;
                        $mysqli->query($addNewLike);
                        header("refresh:0");
                    }
                
                    $enCoursDeTraitement = isset($_POST['Unlike']);
                    if ($enCoursDeTraitement)
                    {   
                        $deleting_like = $_POST['Unlike'];
                        $deleting_like = $mysqli->real_escape_string($deleting_like);  
                    
                        $deleteLiked= "DELETE FROM likes 
                        WHERE user_id= '" . $loggedUserId . "' AND post_id= '" . $_GET['post_id'] ."' ";
                        $deletedLike=$mysqli->query($deleteLiked);     
                        header("refresh:0");     
                    }
            ?>
            
            <?php
                // on requête tous les messages (et leurs infos relatives) postés par les users du réseau
                $messageQuery = "
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
                
                // on envoie la requête
                $messageQueryInfos = $mysqli->query($messageQuery);

                // check requête
                if ( ! $messageQueryInfos)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                    exit();
                }

                // on injecte les différentes infos dans le HTML
                while ($post = $messageQueryInfos->fetch_assoc())
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
                                <?php 
                                    $likeStatus = "SELECT * FROM likes WHERE user_id= '" . $loggedUserId . "' AND post_id= '" . $post['post_id'] ."' ";
                                    $likeStatusInfos = $mysqli->query($likeStatus);
                                    $isLiked = $likeStatusInfos->fetch_assoc();

                                    if (isset($loggedUserId) and !$isLiked) { ?>
                                        <form action="news.php?post_id=<?php echo $post['post_id'] ?>" method="post">
                                            <input type='submit' name="Like" value="💖">
                                            <?php echo $post['like_number'] ?> 
                                        </form>
                                <?php
                                    } else if ($isLiked) { ?>
                                        <form action="news.php?post_id=<?php echo $post['post_id'] ?>" method="post">
                                            <input type='submit' name="Unlike" value="💖">
                                            <?php echo $post['like_number'] ?> 
                                        </form>
                                    <?php } ?>
                            </small>
                            <?php include('_tags.php'); ?>
                        </footer>
                    </article>
                <?php } ?>                    
            </main>
        </div>
    </body>
</html>
