<?php
    session_start();
    include "db.php";

    $language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
    $langFile = 'lang/' . $language . '.php';
    $translations = include $langFile;

    function __($key) {
        global $translations;
        return isset($translations[$key]) ? $translations[$key] : $key;
    }

    if (!isset($_SESSION["UserID"])) {
        header("Location: login.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $reason = $_POST["reason"];
        $reportedProductID = $_POST["reportedProductID"];
        $reportedUserID = $_POST["reportedUserID"];
        $reporterUserID = $_SESSION["UserID"];

        $notificationType = "";
        $entityID = null;
        if (!empty($reportedProductID)) {
            $notificationType = "report";
            $entityID = $reportedProductID;
        } elseif (!empty($reportedUserID)) {
            $notificationType = "report";
            $entityID = $reportedUserID;
        }

        if (!$reporterUserID) {
            echo '<script>alert("Error: User ID not set.");';
            echo 'window.location.href = "index.php";</script>';
            exit;
        }

        $sql_check_notification = "SELECT COUNT(*) FROM Notifications WHERE UserID = ? AND EntityID = ? AND Type = ?";
        $stmt_check_notification = $pdo->prepare($sql_check_notification);
        $stmt_check_notification->execute([$reporterUserID, $reportedProductID, $notificationType]);
        $existing_notification_count = $stmt_check_notification->fetchColumn();

        if ($existing_notification_count > 0) {
            header("Location: product.php?ProductID=" . $reportedProductID . "&message=" . urlencode(__('already_reported')) . "&success=0");
            exit;
        }

        $sql = "INSERT INTO Notifications (UserID, Type, EntityID, message) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$reporterUserID, $notificationType, $entityID, $reason]);

        if ($stmt->rowCount() > 0) {
            header("Location: product.php?message=" . urlencode(__('rep_submit')) . "&success=1");
            exit;
        } else {
            header("Location: product.php?ProductID=" . $reportedProductID . "&message=" . urlencode(__('err_submit_rep')) . "&success=0");
            exit;
        }
    } else {
        header("Location: index.php");
        exit;
    }
?>