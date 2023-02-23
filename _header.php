<!doctype html>
<html lang='<?php echo $language; ?>'>
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Audrey, Lorena & Justine">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <?php include('_database.php'); ?>
        <header>
            <?php
                $usersQuery = "SELECT * FROM `users` LIMIT 50";
                $usersQueryInfo = $mysqli->query($usersQuery);
                $user = $usersQueryInfo->fetch_assoc();
            ?>
            <a href='admin.php'><img src="pictures/resoc_panda_header.png" alt="Logo de notre réseau social"/></a>
            <nav id="menu">
                <a href="news.php">NEWS</a>
                <a href="wall.php?user_id=<?php echo $_SESSION['connected_id'] ?>">MUR</a>
                <a href="feed.php?user_id=<?php echo $_SESSION['connected_id'] ?>">FEED</a>
            </nav>
            <nav id="user">
                <a href="#">PROFIL</a>
                <ul>
                    <li><a href="settings.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Paramètres</a></li>
                    <li><a href="followers.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mes abonnements</a></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                </ul>
            </nav>
        </header>
    </body>
</html>