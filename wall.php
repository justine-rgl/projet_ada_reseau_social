<?php
session_start();
?>
<!doctype html>
<html>
    <head>
        <?php include('_header.php'); ?>
        <title>ReSoC - Mur</title> 
    </head>
    <body>
        
        <div id="wrapper">
            <?php
            $userId =intval($_GET['user_id']);
            include('_database.php');
            ?>

            <aside>
                <?php
                $userIdRequest = "SELECT * FROM users WHERE id= '$userId' ";
                $userIdRequestInfos = $mysqli->query($userIdRequest);
                $user = $userIdRequestInfos->fetch_assoc();
                ?>

                <img src="<?php echo $user['pictures']?>" alt="Portrait de l'utilisatrice"/>
                
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias'] ?>
                        (n° <?php echo $userId ?>)
                    </p>
                    
                    <?php
                    $enCoursDeTraitement = isset($_POST['message']);
                    if ($enCoursDeTraitement)
                    {
                        $new_message = $_POST['message'];
                        $new_message = $mysqli->real_escape_string($new_message);
             
                        $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created) "
                                . "VALUES (NULL, "
                                . $_SESSION["connected_id"] . ", "
                                . "'" . $new_message . "', "
                                . "NOW());"
                                ;
                        $ok = $mysqli->query($lInstructionSql);
                        if ( ! $ok)
                        {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else
                        {
                            echo "Message posté";
                            header("location:wall.php?user_id=" . $_SESSION['connected_id']);
                            exit();
                        }
                    }
                    ?>    
                    <?php 
                    if ($_SESSION['connected_id'] == $userId)
                    { ?>
                        <form action="wall.php" method="post">
                            <div id="form">
                                <label for='message'>Écrivez votre message ici</label><br>
                                <textarea type="text" name='message' id="textArea" rows="5" cols="35"></textarea><br>
                            </div>
                            <input type='submit'>
                        </form>
                    <?php } ?>
                    <?php
                    // CHECK IF IS ALREADY FOLLOWED
                    $followingStatus = "SELECT * FROM followers WHERE followed_user_id= '$userId' AND following_user_id= '" . $_SESSION['connected_id'] . "' ";
                    $followingStatusInfos = $mysqli->query($followingStatus);
                    $isFollowing = $followingStatusInfos->fetch_assoc();
                    // FOLLOW BUTTON
                    if (isset($_SESSION['connected_id']) and $userId != $_SESSION['connected_id'] and !$isFollowing) { ?>
                        <form action="wall.php?user_id=<?php echo $userId ?>" method="post">
                            <input type='submit' name="Abonnement" value="S'abonner">
                        </form>
                    <?php
                    } else if ($isFollowing) { ?>
                            <form action="wall.php?user_id=<?php echo $userId ?>" method="post">
                                <input type='submit' name="Désabonnement" value="Se désabonner">
                            </form>
                    <?php
                    } ?>
                    <?php 
                    $enCoursDeTraitement = isset($_POST['Abonnement']);
                        if ($enCoursDeTraitement)
                        {   
                            $new_follower = $_POST['Abonnement'];
                            $new_follower = $mysqli->real_escape_string($new_follower);  
                        
                            $addNewFollower = "INSERT INTO followers "
                            . "(id, followed_user_id, following_user_id) "
                            . "VALUES (NULL, "
                            . $userId . ", "
                            . $_SESSION["connected_id"] ." );"
                            ;
                            $mysqli->query($addNewFollower);
                            header("refresh:0");
                        }

                    $enCoursDeTraitement = isset($_POST['Désabonnement']);
                        if ($enCoursDeTraitement)
                        {   
                            $deleting_follower = $_POST['Désabonnement'];
                            $deleting_follower = $mysqli->real_escape_string($deleting_follower);  
                        
                            $deleteFollower= "DELETE FROM followers 
                            WHERE followed_user_id= '$userId' AND following_user_id='" . $_SESSION['connected_id'] . "' ";
                            $deletedFollower=$mysqli->query($deleteFollower);     
                            header("refresh:0");               
                        }
                    ?> 
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
                        header("location:wall.php?user_id=" . $_SESSION['connected_id']);
                        exit();
                    }
                
                    $enCoursDeTraitement = isset($_POST['Unlike']);
                    if ($enCoursDeTraitement)
                    {   
                        $deleting_like = $_POST['Unlike'];
                        $deleting_like = $mysqli->real_escape_string($deleting_like);  
                    
                        $deleteLiked= "DELETE FROM likes 
                        WHERE user_id= '" . $_SESSION['connected_id'] . "' AND post_id= '" . $_GET['post_id'] ."' ";
                        $deletedLike=$mysqli->query($deleteLiked);     
                        header("location:wall.php?user_id=" . $_SESSION['connected_id']);
                        exit();           
                    }
            ?>
            <?php
                $messageRequest = "
                    SELECT posts.content, 
                    posts.created,
                    posts.id as post_id,
                    users.alias as author_name, 
                    users.id as user_id,
                    count(DISTINCT likes.id) as like_number, 
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist, 
                    GROUP_CONCAT(DISTINCT tags.id) AS tagidlist
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id = posts.id
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                
                $messageRequestInfos = $mysqli->query($messageRequest);
                if ( ! $messageRequestInfos)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }
                
                while ($post = $messageRequestInfos->fetch_assoc())
                {                    
                ?>                
                    
                    <article>
                        <h3>
                            <time datetime='2020-02-01 11:12:13' ><?php echo $post['created'] ?></time>
                        </h3>
                        <address>par <a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>                                            
                        <footer>
                            <small>
                                <?php 
                                    $likeStatus = "SELECT * FROM likes WHERE user_id= '" . $_SESSION['connected_id'] . "' AND post_id= '" . $post['post_id'] ."' ";
                                    $likeStatusInfos = $mysqli->query($likeStatus);
                                    $isLiked = $likeStatusInfos->fetch_assoc();

                                    if (isset($_SESSION['connected_id']) and !$isLiked) { ?>
                                        <form action="wall.php?post_id=<?php echo $post['post_id'] ?>" method="post">
                                            <input type='submit' name="Like" value="💖">
                                            <?php echo $post['like_number'] ?> 
                                        </form>
                                <?php
                                    } else if ($isLiked) { ?>
                                        <form action="wall.php?post_id=<?php echo $post['post_id'] ?>" method="post">
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
