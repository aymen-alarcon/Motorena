<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $Email = isset($_POST['Email']) ? $_POST['Email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $checkQuery = "SELECT COUNT(*) AS count FROM utilisateur WHERE Email = ?";
    $stmtCheck = $pdo->prepare($checkQuery);
    $stmtCheck->execute([$Email]);
    $count = $stmtCheck->fetchColumn();
    
    if ($count == 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO utilisateur (username, Email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $Email, $hashedPassword]);
            
            $userId = $pdo->lastInsertId();

            $_SESSION['UserID'] = $userId;
            $_SESSION['username'] = $username;


            echo '<script>alert("Account added successfully.");</script>';
            header("Location: dashboard-user.php?user_id=$userId");
            exit;
        } catch (PDOException $e) {
            echo "Error inserting user: " . $e->getMessage();
        }
    } else {
        header("Location: login.php");
        echo '<script>alert("Account already exists.");</script>';
    }

    $stmtCheck->closeCursor();
}
?>
