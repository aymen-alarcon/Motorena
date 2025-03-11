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

    if (isset($_GET['ProductID']) && isset($_GET['UserID'])) {
        $UserID = $_GET['UserID'];
        $ProductID = $_GET['ProductID'];

        try {
            $productID = htmlspecialchars($_GET['ProductID']);
            $userID = htmlspecialchars($_GET['UserID']);

            $sql_check = "SELECT * FROM favorites WHERE ProductID = ? AND UserID = ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$productID, $userID]);

            if ($stmt_check->rowCount() > 0) {
                header("Location: product.php?ProductID=" . $ProductID . "&message=" . urlencode(__('alrd')) . "&error=1");
                exit();
            } else {
                $sql_insert = "INSERT INTO favorites (ProductID, UserID) VALUES (?, ?)";
                $stmt_insert = $pdo->prepare($sql_insert);
                if ($stmt_insert->execute([$productID, $userID])) {
                    header("Location: product.php?ProductID=" . $ProductID . "&message=" . urlencode(__('addedmsg')) . "&success=1");
                    exit();
                } else {
                    header("Location: product.php?ProductID=" . $ProductID . "&message=" . urlencode(__('addedmsg')) . "&success=1");
                    exit();
                }
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "ProductID or UserID not set.";
    }
?>