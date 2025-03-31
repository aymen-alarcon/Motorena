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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $phone = $_POST['phone_number'];
        $address = $_POST['address'];
        $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
        $ProductID = filter_var($_POST['ProductID'], FILTER_SANITIZE_NUMBER_INT);

            $UserID = $_SESSION['UserID'];

            $stmt_check_product = $pdo->prepare("SELECT COUNT(*) FROM produit WHERE ProductID = ?");
            $stmt_check_product->execute([$ProductID]);
            $product_exists = $stmt_check_product->fetchColumn();

            if ($product_exists) {
                $stmt_check_owner = $pdo->prepare("SELECT UserID FROM produit WHERE ProductID = ?");
                $stmt_check_owner->execute([$ProductID]);
                $productOwnerID = $stmt_check_owner->fetchColumn();

                if ($productOwnerID == $UserID) {
                    header("Location: index.php?ProductID=" . $ProductID . "&error=1&message=" . urlencode(__('cannot_buy_own_product')));
                    exit();
                }

                $stmt_price = $pdo->prepare("SELECT Prix FROM produit WHERE ProductID = ?");
                $stmt_price->execute([$ProductID]);
                $productPrice = $stmt_price->fetchColumn();

                $total_price = $quantity * $productPrice;

                $sql_insert = "INSERT INTO command (UserID, name, phone_number, address, quantity, total_price, ProductID) VALUES (? , ?, ?, ?, ?, ?, ?)";

                $stmt_insert = $pdo->prepare($sql_insert);
                if ($stmt_insert->execute([$UserID, $name, $phone, $address, $quantity, $total_price, $ProductID])) {
                    header("Location: index.php?ProductID=" . $ProductID . "&success=1&message=" . urlencode(__('command_success')));
                    exit();
                } else {
                    header("Location: index.php?ProductID=" . $ProductID . "&error=1&message=" . urlencode(__('insert_failed')));
                    exit();
                }
            } else {
                header("Location: index.php?ProductID=" . $ProductID . "&error=1&message=" . urlencode(__('product_not_found')));
                exit();
            }
    } else {
        header("Location: index.php?ProductID=" . $_POST['ProductID'] . "&error=1&message=" . urlencode(__('invalid_request')));
        exit();
    }
?>