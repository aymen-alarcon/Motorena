<?php
session_start();
include 'db.php';

if(isset($_POST['update_product'])) {
    $ProductID = $_POST['ProductID'];
    $NomProduit = $_POST['NomProduit'];
    $Description = $_POST['Description'];
    $Prix = $_POST['Prix'];
    $CategoryID = $_POST['CategoryID'];
    $Statu = isset($_POST['status']) ? 1 : 0;

    try {
        $sql = "UPDATE produit SET NomProduit = :NomProduit, Description = :Description, Prix = :Prix, CategoryID = :CategoryID, Statu = :Statu WHERE ProductID = :ProductID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':NomProduit', $NomProduit);
        $stmt->bindParam(':Description', $Description);
        $stmt->bindParam(':Prix', $Prix);
        $stmt->bindParam(':CategoryID', $CategoryID);
        $stmt->bindParam(':Statu', $Statu);
        $stmt->bindParam(':ProductID', $ProductID);
        $stmt->execute();

        header("Location: admin-dashboard.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Form submission error";
}
?>
