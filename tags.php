<?php
    session_start();
    include('_header.php');
    include('_database.php');
    include('_loggedUserQuery.php');
?>

<!doctype html>
<html >
    <head>
        <title>ReSoC - Les messages par mot-clé</title> 
    </head>
    <body>
        <div id="wrapper">
            <?php
                $tagId = intval($_GET['tag_id']);
                // requête pour récupérer id + label des tags
                $tagsQuery = "SELECT * FROM tags WHERE id= '$tagId' ";
                $tagsQueryInfo = $mysqli->query($tagsQuery);
                $tag = $tagsQueryInfo->fetch_assoc();
            ?>

            <main>
                <h2>Tag : <?php echo $tag ['label'] ?></h2>
                
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
                    header("location:tags.php?tag_id=" . $tagId);
                    exit();
                }
            
                $enCoursDeTraitement = isset($_POST['Unlike']);
                if ($enCoursDeTraitement)
                {   
                    $deleting_like = $_POST['Unlike'];
                    $deleting_like = $mysqli->real_escape_string($deleting_like);  
                
                    $deleteLiked= "DELETE FROM likes 
                    WHERE user_id= '" . $loggedUserId . "' AND post_id= '" . $_GET['post_id'] ."' ";
                    $deletedLike=$mysqli->query($deleteLiked);     
                    header("location:tags.php?tag_id=" . $tagId);
                    exit();    
                }

                $taggedPostsQuery = "
                    SELECT posts.content,
                    posts.created,
                    posts.id as post_id,
                    users.id as user_id,
                    users.alias as author_name,  
                    COUNT(likes.id) as like_number,  
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
                
                $taggedPostsQueryInfo = $mysqli->query($taggedPostsQuery);
                if ( ! $taggedPostsQueryInfo)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                while ($post = $taggedPostsQueryInfo->fetch_assoc())
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
                                    $likeStatus = "SELECT * FROM likes WHERE user_id= '" . $loggedUserId . "' AND post_id= '" . $post['post_id'] ."' ";
                                    $likeStatusInfos = $mysqli->query($likeStatus);
                                    $isLiked = $likeStatusInfos->fetch_assoc();

                                    if (isset($loggedUserId) and !$isLiked) { ?>
                                        <form action="tags.php?post_id=<?php echo $post['post_id'] ?>" method="post">
                                            <input type='submit' name="Like" value="💖">
                                            <?php echo $post['like_number'] ?> 
                                        </form>
                                <?php
                                    } else if ($isLiked) { ?>
                                        <form action="tags.php?post_id=<?php echo $post['post_id'] ?>" method="post">
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