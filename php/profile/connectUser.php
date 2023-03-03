<?php

require '../lib/parse.env.php';
require_once '../Connexion.php';

if(isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash password using SHA256 algorithm
    $hashed_password = hash('sha256', $password);

    // Create SQL query to fetch user data from database
    $sql = "SELECT id, pseudo FROM T_Comptes WHERE email = :email AND password = :password";

    $result = $db->q($sql, array(
        array(':email', $email),
        array(':password', $hashed_password)
    ));

    // Check if the user exists in the database
    if(count($result) > 0) {
        // User was found, set session variables and redirect to homepage
        session_start();
        $_SESSION['id'] = $result[0]->id;
        $_SESSION['pseudo'] = $result[0]->pseudo;
        $_SESSION['email'] = $result[0]->email;
        header('Location: ../../index.php?connected=true');
        exit;
    }
}

// User authentication failed, redirect to homepage with error message
header('Location: ../../autres/connexion.php?connected=false');
exit;

?>