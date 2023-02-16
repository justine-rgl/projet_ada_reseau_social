<?php
session_start();
?>
<!doctype html>
<html >
    <head>
        <?php include('_header.php'); ?>
        <title>ReSoC - Les messages par mot-clé</title> 
    </head>
    <body>
        <div id="wrapper">
            <?php
            include('database.php');
            $tagId = intval($_GET['tag_id']);
            ?>

            <aside>
                <?php
                $laQuestionEnSql = "SELECT * FROM tags WHERE id= '$tagId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $tag = $lesInformations->fetch_assoc();
                ?>
                
                <img src="resoc_panda.png" alt="Portrait de l'utilisatrice"/>
                
                <section>
                    <h3>Présentation</h3>
                        <p>
                            Sur cette page vous trouverez les derniers messages comportant le mot-clé <?php echo $tag ['label']?> (n° <?php echo $tagId ?>)
                        </p>
                </section>
            </aside>
            
            <main>
                <?php
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    users.id as user_id,
                    users.alias as author_name,  
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                    GROUP_CONCAT(DISTINCT tags.id) AS tagidlist 
                    FROM posts_tags as filter 
                    JOIN posts ON posts.id=filter.post_id
                    JOIN users ON users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE filter.tag_id = '$tagId' 
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
                            <time datetime='2020-02-01 11:12:13' >31 février 2010 à 11h12</time>
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