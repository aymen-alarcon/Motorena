<?php
    session_start();
    include 'db.php';

    $language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
    $langFile = 'lang/' . $language . '.php';
    $translations = include $langFile;

    function __($key) {
        global $translations;
        return isset($translations[$key]) ? $translations[$key] : $key;
    }


    if (isset($_GET['NotificationID'])) {
        $notificationID = $_GET['NotificationID'];

        $sql = "UPDATE notifications SET is_deleted = 1 WHERE NotificationID = :notificationID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':notificationID', $notificationID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: notifications.php?success=1&message=" . urlencode(__('clear_notification')));
            exit();
        } else {
            header("Location: notifications.php?error=1&message=" . urlencode(__('cant_clear_notification')));
        }
    } elseif (isset($_GET['CommandID'])) {
        $commandID = $_GET['CommandID'];

        $sql = "UPDATE command SET is_deleted = 1 WHERE CommandID = :commandID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':commandID', $commandID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: notifications.php?success=1&message=" . urlencode(__('clear_command')));
            exit();
        } else {
            header("Location: notifications.php?error=1&message=" . urlencode(__('cant_clear_command')));
        }
    } elseif (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "UPDATE product_interest SET is_deleted = 1 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: notifications.php?success=1&message=" . urlencode(__('clear_intrest')));
            exit();
        } else {
            header("Location: notifications.php?error=1&message=" . urlencode(__('cant_clear_intrest')));
        }
    } else {
        echo "ID is missing.";
    }
?>