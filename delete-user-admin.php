<?php
session_start();
include 'db.php';

if(isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {
    if(isset($_GET['UserID'])) {
        $userID = $_GET['UserID'];

        $sql = "DELETE FROM utilisateur WHERE UserID = ?";
        $stmt = $pdo->prepare($sql);
        if($stmt->execute([$userID])) {
            echo '<script>alert("User deleted successfully.");</script>';
            header("Location: manage-users-admin.php");

        } else {
            echo '<script>alert("Error deleting user.");</script>';
        }
    } else {
        echo "UserID parameter not set.";
    }
} else {
    header("Location: login.php");
    exit;
}
?>