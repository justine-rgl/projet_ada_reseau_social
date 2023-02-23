<?php
    session_start();
    include('_database.php');
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Connexion</title> 
        <meta name="author" content="Audrey, Lorena & Justine">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>
            <img src="pictures/panda-roule.png" alt="Logo de notre réseau social"/>
        </header>

        <div id="wrapper" >

            <aside>
                <h2>Hello jungle! 🐼</h2>
                <p>Bienvenue sur le réseau social des Pandas trop mignons (plus que Darmanin).</p>
            </aside>
            <main>
                <article>
                    <h2>Connexion</h2>
                    
                    <?php
                    // on vérifie qu'une adresse mail a bien été soumise via l'input
                    $enCoursDeTraitement = isset($_POST['email']);
                    if ($enCoursDeTraitement)
                    {
                        // on stocke l'email et le mdp du user dans une variable pour les réutiliser ensuite
                        $emailAVerifier = $_POST['email'];
                        $passwdAVerifier = $_POST['motdepasse'];
                        // check/traduction caractères spéciaux
                        $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                        $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
                        // cryptage mdp
                        $passwdAVerifier = md5($passwdAVerifier);
                        
                        // on requête le mail soumis dans la DB existante
                        $loginQuery = "SELECT * "
                                . "FROM users "
                                . "WHERE "
                                . "email LIKE '" . $emailAVerifier . "'"
                                ;
                        
                        // on récupère/stocke les infos
                        $loginQueryInfo = $mysqli->query($loginQuery);
                        $user = $loginQueryInfo->fetch_assoc();
                        // si la variable $user revient vide (mail inexistant dans la DB) OU password différent de celui soumis :
                        if ( ! $user OR $user["password"] != $passwdAVerifier)
                        {
                            echo "La connexion a échoué. "; 
                        } else
                        {
                            echo "Votre connexion est un succès : " . $user['alias'] . ".";
                            $_SESSION['connected_id']=$user['id'];
                            header("location:feed.php?user_id=" . $_SESSION['connected_id']);
                            exit(); 
                        }
                    }
                    ?>    

                    <form action="login.php" method="post">
                        <input type='hidden'name='login' value='login'>
                        <dl>
                            <dt><label for='email'>E-Mail</label></dt>
                            <dd><input type='email'name='email'></dd>
                            <dt><label for='motdepasse'>Mot de passe</label></dt>
                            <dd><input type='password'name='motdepasse'></dd>
                        </dl>
                        <input type='submit'>
                    </form>
                    <p>
                        <br>
                        Pas de compte?
                        <a href='registration.php'>Inscrivez-vous.</a>
                    </p>
                </article>
            </main>
        </div>
    </body>
</html>
