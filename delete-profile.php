<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_delete"])) {
    $enteredPassword = $_POST["password"];
    $userId = $_SESSION["UserID"];
    
    $stmt = $pdo->prepare("SELECT password FROM utilisateur WHERE UserID = :UserID");
    $stmt->bindParam(':UserID', $userId);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $hashedPassword = $row["password"];

        if (password_verify($enteredPassword, $hashedPassword)) {
            $sql = "UPDATE utilisateur SET is_deleted = 1 WHERE UserID = :UserID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':UserID', $userId);
            if ($stmt->execute()) {
                session_destroy();
                header("Location: login.php");
                exit();
            } else {
                echo "Error updating record: " . $stmt->errorInfo()[2];
            }
        } else {
            echo "Incorrect password. Please try again.";
        }
    } else {
        echo "User not found.";
    }
} else {
    header("Location: delete-profile.php");
    exit();
}

$pdo = null;
?>
