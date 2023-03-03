<?php

require '../lib/parse.env.php';
require_once '../Connexion.php';



if(isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['password'])) {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash password using SHA256 algorithm
    $hashed_password = hash('sha256', $password);

    // Create SQL query to insert user data into database
    $sql = "INSERT INTO T_Comptes (pseudo, email, password) VALUES (:pseudo, :email, :password)";
    
    $result = $db-> q($sql, array(
        array(':pseudo', $pseudo),
        array(':email', $email),
        array(':password', $hashed_password)
    ));

    // Check if the user account was successfully added to the database

        
    // get back the user id
    session_start();
    $sql = "SELECT * FROM T_Comptes WHERE email = :email";
    $result = $db->q($sql, array(
        array(':email', $email)
    ));
    $_SESSION['id'] = $result[0]->id;
    $_SESSION['pseudo'] = $result[0]->pseudo;
    $_SESSION['email'] = $result[0]->email;
    
    header('Location: ../../index.php?registered=true');
    exit;
   

}

// Required fields are missing
header('Location: ../../autres/inscription.php?registered=false');
exit;




?>