<?php

require('functions.php');
$bdd = pdo_connect_mysql();
//Validation de notre formulaire

//echo "Vous etes inscrit";
if(isset($_POST['submit'])){

    //verifier si l'utilisateur a bien completer les champs

    
    if(!empty($_POST['pseudo']) AND !empty($_POST['lastname']) AND !empty($_POST['firstname']) AND !empty($_POST['password']));
    //Les donnees de l'utilisateur
    
    $lastname = htmlspecialchars($_POST['lastname']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $user_pseudo = htmlspecialchars($_POST['pseudo']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    //Verifier si l'utilisateur existe deja sur le site

    $checkIfUserAlreadyExists = $bdd->prepare('SELECT pseudo FROM users WHERE pseudo = ?');
    $checkIfUserAlreadyExists->execute(array($user_pseudo));

    if($checkIfUserAlreadyExists->rowCount() == 0){
        

    //inserr l'utilisateur dans le bdd
             $insertUserOnWebsite = $bdd->prepare('INSERT INTO users(lastname, firstname, pseudo, password)VALUES(?, ?, ?, ?)');
            $insertUserOnWebsite->execute(array( $lastname, $firstname, $user_pseudo, $password));
             
             //Recuperation des informations de l'utilisateur

            $getInfosOfThisUserReq = $bdd->prepare('SELECT id, lastname, firstname, pseudo FROM users WHERE lastname = ? AND firstname = ? AND pseudo = ?');
            $getInfosOfThisUserReq->execute(array($lastname, $firstname, $user_pseudo));

            $userInfos = $getInfosOfThisUserReq->fetch();
            
            //Authentification de l'utilisateur sur le site et recuperer ses donneees des variables globales de session
var_dump($userInfos);
            $_SESSION['auth'] = true;
            $_SESSION['id'] = $userInfos['id'];
            $_SESSION['pseudo'] = $userInfos['pseudo'];
            $_SESSION['lastname'] = $userInfos['lastname'];
            $_SESSION['firstname'] = $userInfos['firstname'];

            //Rediriger l'utilisateur vers la page d'accueil

           header('Location: login.php');


    }else{
           $errorMsg = "L'utilisateur existe deja sur le site";
    }
}else{
    $errorMsg = "Veuillez completer tous les champs....";
}