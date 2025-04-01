<?php
session_start();
include 'db.php';

if(isset($_GET['NotificationID'])) {
    $NotificationID = $_GET['NotificationID'];

    try {
        $pdo->beginTransaction();

        $sql_delete_Notification = "DELETE FROM Notifications WHERE NotificationID = :NotificationID";
        $stmt_delete_Notification = $pdo->prepare($sql_delete_Notification);
        $stmt_delete_Notification->bindParam(':NotificationID', $NotificationID);
        $stmt_delete_Notification->execute();

        $pdo->commit();

        if ($stmt_delete_Notification->rowCount() > 0) {
            header("Location: manage-Notifications-admin.php");
            exit();
        } else {
            echo "No notifications found with NotificationID: $NotificationID"; 
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error deleting notification: " . $e->getMessage();
    }
} else {
    echo "NotificationID not provided";
}
?>
