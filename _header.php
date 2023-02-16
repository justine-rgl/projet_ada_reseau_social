<!doctype html>
<html lang='<?php echo $language; ?>'>
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <?php include('database.php'); ?>
        <header>
            <?php
                $laQuestionEnSql = "SELECT * FROM `users` LIMIT 50";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
            ?>
            <a href='admin.php'><img src="resoc_panda_header.png" alt="Logo de notre réseau social"/></a>
            <nav id="menu">
                <a href="news.php">ACTUALITÉS</a>
                <a href="wall.php?user_id=<?php echo $_SESSION['connected_id'] ?>">MUR</a>
                <a href="feed.php?user_id=<?php echo $_SESSION['connected_id'] ?>">FLUX</a>
                <a href="tags.php?tag_id=A CHANGER">MOTS-CLÉS</a>
            </nav>
            <nav id="user">
                <a href="#">PROFIL</a>
                <ul>
                    <li><a href="settings.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Paramètres</a></li>
                    <li><a href="followers.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mes abonnements</a></li>
                </ul>
            </nav>
        </header>
    </body>
</html>