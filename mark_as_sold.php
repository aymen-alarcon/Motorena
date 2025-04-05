<?php
    session_start();
    include 'db.php';

    $CommandID = $_POST['CommandID'];
    $ProductID = $_POST['ProductID'];

    try {
        $pdo->beginTransaction();

        $stmtUpdateCommand = $pdo->prepare("UPDATE command SET command_statu = 1 WHERE CommandID = :CommandID");
        $stmtUpdateCommand->execute(['CommandID' => $CommandID]);

        $stmtUpdateProduct = $pdo->prepare("UPDATE produit SET is_sold = 1 WHERE ProductID = :ProductID");
        $stmtUpdateProduct->execute(['ProductID' => $ProductID]);

        $pdo->commit();

        header("Location: notifications.php?message=" . urlencode("the receipt have been deleiverd") . "&success=1");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
?>