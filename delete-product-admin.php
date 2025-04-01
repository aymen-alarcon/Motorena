<?php
session_start();
include 'db.php';

if(isset($_GET['ProductID'])) {
    $ProductID = $_GET['ProductID'];

    try {
        $pdo->beginTransaction();

        $sql_delete_productpictures = "DELETE FROM ProductPictures WHERE ProductID = :ProductID";
        $stmt_delete_productpictures = $pdo->prepare($sql_delete_productpictures);
        $stmt_delete_productpictures->bindParam(':ProductID', $ProductID);
        $stmt_delete_productpictures->execute();

        $sql_delete_product = "DELETE FROM produit WHERE ProductID = :ProductID";
        $stmt_delete_product = $pdo->prepare($sql_delete_product);
        $stmt_delete_product->bindParam(':ProductID', $ProductID);
        $stmt_delete_product->execute();

        $pdo->commit();

        header("Location: manage-products-admin.php");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ProductID not provided";
}
?>