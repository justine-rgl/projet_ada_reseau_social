<?php
session_start();
?>
<!doctype html>
<html>
    <head>
        <?php include('_header.php'); ?>
        <title>ReSoC - Flux</title>         

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

                <img src="<?php echo $user['pictures']?>" alt="Portrait de l'utilisatrice"/>
                
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message des utilisatrices
                        auxquel est abonnée l'utilisatrice <?php echo $user['alias'] ?>
                        (n° <?php echo $userId ?>)
                    </p>
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
                        header("location:feed.php?user_id=" . $_SESSION['connected_id']);
                        exit();
                    }
            ?>
            
                <?php
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    posts.id as post_id,
                    users.alias as author_name, 
                    users.id as user_id, 
                    count(DISTINCT likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                    GROUP_CONCAT(DISTINCT tags.id) AS tagidlist 
                    FROM followers 
                    JOIN users ON users.id=followers.followed_user_id
                    JOIN posts ON posts.user_id=users.id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE followers.following_user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                while ($post = $lesInformations->fetch_assoc())
                {
                ?>   
        
                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13' ><?php echo $post['created'] ?></time>
                    </h3>
                    <address>par <a href="wall.php?user_id=<?php echo $post ['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                   
                    <div>
                        <p><?php echo $post['content']?></p>
                    </div>                                            
                    <footer>
                        <small>
                            <form action="feed.php?post_id=<?php echo $post['post_id'] ?>" method="post">
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
