<?php
//session_start();
FUNCTION pdo_connect_mysql() {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'contacts';
    try {
        return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
        // S'il y a une erreur avec la connexion, arrêtez le script et affichez l'erreur.
        exit('Impossible de se connecter à la base de données!');
    }
}
function template_header($title) {
echo <<<EOT
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>$title</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    </head>
    <body>
    <nav class="navtop">
        <div>
            <h1>siteWeb </h1>
            <a href="index.php"><i class="fas fa-home"></i>Accueil</a>
            <a href="read.php"><i class="fas fa-address-book"></i>Contacts</a>
            <a href=""><i class="bi bi-person-circle"></i>users</a>
            <a href="login.php"><i class="bi bi-power"></i>DECONNEXION</a>
        </div>
    </nav>
EOT;
}
function template_footer() {
echo <<<EOT
    </body>
</html>
EOT;
}
?>