<?php
session_start();
include 'db.php';

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['notificationID']) && isset($_POST['updateType'])) {
        $notificationID = $_POST['notificationID'];
        $updateType = $_POST['updateType'];

        $stmt = $pdo->prepare("SELECT UserID FROM Notifications WHERE NotificationID = :notificationID");
        $stmt->bindParam(':notificationID', $notificationID);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userID = $row['UserID'];

        if ($updateType == 'checked') {
            $type = 2;
            $admin_response = "Welcome aboard our new seller";
        } else {
            $type = 1;
            $admin_response = NULL;
        }

        $stmt = $pdo->prepare("UPDATE utilisateurinfo SET type = :type WHERE UserID = :userID");
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();

        $stmt = $pdo->prepare("UPDATE Notifications SET admin_response = :admin_response WHERE NotificationID = :notificationID");
        $stmt->bindParam(':admin_response', $admin_response);
        $stmt->bindParam(':notificationID', $notificationID);
        $stmt->execute();

        header("Location: manage-Notifications-admin.php");
        exit();
    } else {
        echo "Error: Missing fields";
        exit();
    }
} else {
    header("Location: manage-Notifications-admin.php");
    exit();
}
?>