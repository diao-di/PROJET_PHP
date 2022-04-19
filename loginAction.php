<?php

require('functions.php');
$bdd = pdo_connect_mysql();

//Validation de notre formulaire

if(isset($_POST['submit'])){

    //verifier si l'utilisateur a bien completer les champs

    if(!empty($_POST['pseudo']) AND !empty($_POST['password'])){

    //Les donnees de l'utilisateur (si le pseudo est correct)
    $user_pseudo = htmlspecialchars($_POST['pseudo']);
    $password = htmlspecialchars($_POST['password']);

      //Verifier si l'utilisateur existe
    $checkIfUserExists = $bdd->prepare('SELECT * FROM users WHERE pseudo = ?');
    $checkIfUserExists->execute(array($user_pseudo));

    if($checkIfUserExists->rowCount() > 0){

        //Recuperer les donnees de l'utilisateur

         $userInfos = $checkIfUserExists->fetch();

         //Verifier si le mot de passe est correct
        if(password_verify($password, $userInfos['password'])){
            
               //Authentification de l'utilisateur sur le site et recuperer ses donneees des variables globales de session

            $_SESSION['auth'] = true;
            $_SESSION['id'] = $userInfos['id'];
            $_SESSION['pseudo'] = $userInfos['pseudo'];
            $_SESSION['lastname'] = $userInfos['lastname'];
            $_SESSION['firstname'] = $userInfos['firstname'];

            //Rediriger l'utilisateur vers la page d'accueil

            header('Location: index.php');

        }else{
            $errorMsg = "Votre mot de passe est incorrect....";
        }

    }else{
        $errorMsg = "Votre pseudo est incorrect....";
    }

    }
}else{
    $errorMsg = "Veuillez completer tous les champs....";
}

?>