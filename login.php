<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Connexion</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>
            <img src="resoc_panda_header.png" alt="Logo de notre réseau social"/>
        </header>

        <div id="wrapper" >

            <aside>
                <h2>Présentation</h2>
                <p>Bienvenue sur notre réseau social.</p>
            </aside>
            <main>
                <article>
                    <h2>Connexion</h2>
                    
                    <?php
                    $enCoursDeTraitement = isset($_POST['email']);
                    if ($enCoursDeTraitement)
                    {
                        $emailAVerifier = $_POST['email'];
                        $passwdAVerifier = $_POST['motdepasse'];
                        include('database.php');
                        $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                        $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
                        $passwdAVerifier = md5($passwdAVerifier);
                        $lInstructionSql = "SELECT * "
                                . "FROM users "
                                . "WHERE "
                                . "email LIKE '" . $emailAVerifier . "'"
                                ;
                        
                        $res = $mysqli->query($lInstructionSql);
                        $user = $res->fetch_assoc();
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
                        <input type='hidden'name='???' value='achanger'>
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
