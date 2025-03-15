<?php
    session_start();
    include 'db.php';

    if(isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $userID = $_POST['UserID'];
            $username = $_POST['username'];
            $email = $_POST['email'];

            $sql = "UPDATE utilisateur SET username = ?, email = ? WHERE UserID = ?";
            $stmt= $pdo->prepare($sql);
            if($stmt->execute([$username, $email, $userID])) {
                echo "User updated successfully.";
                header("Location: admin-dashboard.php");
            } else {
                echo "Error updating user.";
            }
        } else {
            echo "Form submission method not allowed.";
        }
    } else {
        header("Location: login.php");
        exit;
    }
?>
