<?php
require_once 'Connexion.php';

if(isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['password'])) {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash password using SHA256 algorithm
    $hashed_password = hash('sha256', $password);

    // Create SQL query to insert user data into database
    $sql = "INSERT INTO T_Comptes (pseudo, email, password) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pseudo, $email, $hashed_password]);

    // Check if the user account was successfully added to the database
    if ($stmt->rowCount() > 0) {
        // User account was added successfully
        session_start();
        $_SESSION['userId'] = $pdo->lastInsertId();
        header('Location: ../index.php?connected=true');
        exit;
    } else {
        // User account was not added
        header('Location: ../index.php?connected=false');
        exit;
    }
} else {
    // Required fields are missing
    header('Location: ../index.html?connected=false');
    exit;
}
?>