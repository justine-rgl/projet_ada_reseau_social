<?php
    include('_database.php');
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Inscription</title> 
        <meta name="author" content="Audrey, Lorena & Justine">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>
            <img src="pictures/resoc_panda_header.png" alt="Logo de notre réseau social"/>
        </header>

        <div id="wrapper" >

            <aside>
                <h2>Hello jungle! 🐼</h2>
                <p>Bienvenue sur le réseau social des Pandas trop mignons (plus que le Z).</p>
            </aside>

            <main>
                <article>
                    <h2>Inscription</h2>
                    <?php
                    $enCoursDeTraitement = isset($_POST['email']);
                    if ($enCoursDeTraitement)
                    {
                        $new_email = $_POST['email'];
                        $new_alias = $_POST['pseudo'];
                        $new_passwd = $_POST['motdepasse'];
                        
                        // check caractères spéciaux + cryptage
                        $new_email = $mysqli->real_escape_string($new_email);
                        $new_alias = $mysqli->real_escape_string($new_alias);
                        $new_passwd = $mysqli->real_escape_string($new_passwd);
                        $new_passwd = md5($new_passwd);
                        
                        // on fait une requête pour insérer les inputs du user dans la DB
                        $addNewUserQuery = "INSERT INTO users (id, email, password, alias) "
                                . "VALUES (NULL, "
                                . "'" . $new_email . "', "
                                . "'" . $new_passwd . "', "
                                . "'" . $new_alias . "'"
                                . ");";

                        // envoi de la requête
                        $addNewUserQueryInfo = $mysqli->query($addNewUserQuery);
                        // si l'utilisateur existe déjà, on renvoie un message d'erreur
                        if ( ! $addNewUserQueryInfo)
                        {
                            echo "L'inscription a échoué : " . $mysqli->error;
                        } else
                        {
                            echo "Votre inscription est un succès : " . $new_alias;
                            echo " <a href='login.php'>Connectez-vous.</a>";
                        }
                    }
                    ?>                     
                    
                    <form action="registration.php" method="post">
                        <dl>
                            <dt><label for='pseudo'>Pseudo</label></dt>
                            <dd><input type='text'name='pseudo'></dd>
                            <dt><label for='email'>E-Mail</label></dt>
                            <dd><input type='email'name='email'></dd>
                            <dt><label for='motdepasse'>Mot de passe</label></dt>
                            <dd><input type='password'name='motdepasse'></dd>
                        </dl>
                        <input type='submit'>
                    </form>
                    <p>
                        <br>Vous avez déjà un compte?
                        <a href='login.php'>Connectez-vous.</a>
                    </p>
                </article>
            </main>
        </div>
    </body>
</html>
