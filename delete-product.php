<?php
session_start();
include 'db.php';

if(isset($_GET['ProductID'])) {
    $ProductID = $_GET['ProductID'];

    try {
        $pdo->beginTransaction();

        $sql_insert_deleted_product = "INSERT INTO deleted_products (ProductID ) VALUES (:ProductID)";
        $stmt_insert_deleted_product = $pdo->prepare($sql_insert_deleted_product);
        $stmt_insert_deleted_product->bindParam(':ProductID', $ProductID);
        $stmt_insert_deleted_product->execute();

        $sql_update_product = "UPDATE produit SET is_deleted = 1 WHERE ProductID = :ProductID";
        $stmt_update_product = $pdo->prepare($sql_update_product);
        $stmt_update_product->bindParam(':ProductID', $ProductID);
        $stmt_update_product->execute();

        $sql_delete_favorite = "DELETE FROM favorites WHERE ProductID = :ProductID";
        $stmt_delete_favorite = $pdo->prepare($sql_delete_favorite);
        $stmt_delete_favorite->bindParam(':ProductID', $ProductID);
        $stmt_delete_favorite->execute();

        $pdo->commit();

        header("Location: profile_products.php");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ProductID not provided";
}
?>