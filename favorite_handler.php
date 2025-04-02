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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $loggedInUserID = $_SESSION['UserID'];
        $ProductID = $_POST['ProductID'];
        $favorite = isset($_POST['favorite']) ? $_POST['favorite'] : 0;

        if (!$loggedInUserID || !$ProductID) {
            throw new Exception("UserID or ProductID not set correctly.");
        }

        $favoriteCheckSql = "SELECT COUNT(*) FROM favorites WHERE UserID = ? AND ProductID = ?";
        $favoriteCheckStmt = $pdo->prepare($favoriteCheckSql);
        $favoriteCheckStmt->execute([$loggedInUserID, $ProductID]);
        $favoriteCount = $favoriteCheckStmt->fetchColumn();

        if ($favorite == 1) {
            if ($favoriteCount == 0) {
                $insertFavoriteSql = "INSERT INTO favorites (UserID, ProductID) VALUES (?, ?)";
                $insertFavoriteStmt = $pdo->prepare($insertFavoriteSql);
                $insertFavoriteStmt->execute([$loggedInUserID, $ProductID]);

                header("Location: product.php?ProductID=" . $ProductID . "&message=" . urlencode(__('addedmsg')) . "&success=1");
            }
        } else {
            if ($favoriteCount > 0) {
                $deleteFavoriteSql = "DELETE FROM favorites WHERE UserID = ? AND ProductID = ?";
                $deleteFavoriteStmt = $pdo->prepare($deleteFavoriteSql);
                $deleteFavoriteStmt->execute([$loggedInUserID, $ProductID]);

                header("Location: product.php?ProductID=" . $ProductID . "&message=" . urlencode(__('deletedmsg')) . "&success=1");
            }
        }

        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
