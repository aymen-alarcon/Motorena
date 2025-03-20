<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['notificationID']) && !empty($_POST['notificationID'])) {
        $notificationID = $_POST['notificationID'];
        
        if (isset($_POST['admin_response']) && !empty($_POST['admin_response'])) {
            $admin_response = $_POST['admin_response'];
            
            try {
                $stmt = $pdo->prepare("UPDATE notifications SET admin_response = :admin_response WHERE NotificationID = :notificationID");
                
                $stmt->bindParam(':admin_response', $admin_response);
                $stmt->bindParam(':notificationID', $notificationID);
                
                $stmt->execute();
                
                echo "Admin response has been successfully added.";
            } catch (PDOException $e) {
                echo "Error updating record: " . $e->getMessage();
            }
        } else {
            echo "Admin response is required.";
        }
    } else {
        echo "Notification ID is required.";
    }
}
?>
