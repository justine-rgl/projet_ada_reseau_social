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
            include('database.php');
            ?>

            <aside>
                <?php
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
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

                    <form action="wall.php" method="post">
                        <div id="form">
                            <label for='message'>Écrivez votre message ici</label><br>
                            <textarea type="text" name='message' id="textArea" rows="5" cols="35"></textarea><br>
                        </div>
                        <input type='submit'>
                    </form>
                </section>
            </aside>
            <main>

                <?php
                $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, users.id as user_id,
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist, GROUP_CONCAT(DISTINCT tags.id) AS tagidlist
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
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
                        <address>par <a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>                                            
                        <footer>
                            <small>💜 <?php echo $post['like_number'] ?></small>
                            <?php include('_tags.php'); ?>
                        </footer>
                    </article>

                <?php } ?>
            </main>
        </div>
    </body>
</html>
