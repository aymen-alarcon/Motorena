<?php
session_start();
include 'db.php';

if(isset($_SESSION['UserID'])) {
    if(isset($_GET['ProductID'])) {
        try {
            $sql = "DELETE FROM favorites WHERE ProductID = :ProductID AND UserID = :UserID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':ProductID', $_GET['ProductID']);
            $stmt->bindParam(':UserID', $_SESSION['UserID']);
            
            if($stmt->execute()) {
                header("Location: index.php?message=" . urlencode(__('deletedmsg')) . "&success=1");
                exit();
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    header("Location: login.php");
    exit();
}
?>
