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

    if (isset($_GET['UserID']) && isset($_GET['ProductID'])) {
        $UserID = $_GET['UserID'];
        $ProductID = $_GET['ProductID'];

        $stmt = $pdo->prepare("SELECT UserID FROM produit WHERE ProductID = ?");
        $stmt->execute([$ProductID]);
        $productOwner = $stmt->fetchColumn();

        if ($productOwner == $UserID) {
            header("Location: product.php?ProductID=" . $ProductID . "&message=" . urlencode("You cannot express interest in your own product.") . "&error=1");
            exit();
        }

        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM product_interest WHERE BuyerID = :UserID AND ProductID = :ProductID");
            $stmt->bindParam(':UserID', $UserID, PDO::PARAM_INT);
            $stmt->bindParam(':ProductID', $ProductID, PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                header("Location: product.php?ProductID=" . $ProductID . "&message=" . urlencode(__('no')) . "&error=1");
                exit();
            }

            $stmt = $pdo->prepare("SELECT UserID FROM produit WHERE ProductID = ?");
            $stmt->execute([$ProductID]);
            $sellerID = $stmt->fetchColumn();

            $stmt = $pdo->prepare("SELECT fullname, phone FROM utilisateurinfo WHERE UserID = ?");
            $stmt->execute([$UserID]);
            $buyerDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = "INSERT INTO product_interest (BuyerID, buyer_name, buyer_phone, ProductID, seller_id) 
                    VALUES (:UserID, :buyerName, :buyerPhone, :ProductID, :SellerID)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':UserID', $UserID, PDO::PARAM_INT);
            $stmt->bindParam(':buyerName', $buyerDetails['fullname'], PDO::PARAM_STR);
            $stmt->bindParam(':buyerPhone', $buyerDetails['phone'], PDO::PARAM_STR);
            $stmt->bindParam(':ProductID', $ProductID, PDO::PARAM_INT);
            $stmt->bindParam(':SellerID', $sellerID, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: product.php?ProductID=" . $ProductID . "&message=" . urlencode(__('intrestmsg')) . "&success=1");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: Buyer ID or Product ID is not set in the URL.";
    }
?>
