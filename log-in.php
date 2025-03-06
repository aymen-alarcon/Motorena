<?php
session_start();
include "db.php";

if (isset($_POST['email']) && isset($_POST['password'])) {

    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $query = $pdo->prepare("SELECT utilisateur.UserID, utilisateur.email, utilisateur.username, utilisateurinfo.type, utilisateur.password 
                            FROM utilisateur 
                            INNER JOIN utilisateurinfo ON utilisateur.UserID = utilisateurinfo.UserID 
                            WHERE utilisateur.email = ?");
    $query->execute([$email]);
    $user = $query->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['UserID'] = $user['UserID'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['username'];
        $_SESSION['type'] = $user['type'];

        header("Location: index.php?UserID=" . $user['UserID']);
        exit();
    } else {
        $error = "Invalid email or password.";
        header("Location: login.php?error=" . urlencode($error));
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>