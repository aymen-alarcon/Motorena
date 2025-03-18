<?php
    session_start();
    include 'db.php';

    if (isset($_GET['CategoryID'])) {
        $CategoryID = $_GET['CategoryID'];

        try {
            $sql_delete_category = "DELETE FROM categorieuser WHERE CategoryID = :CategoryID";
            $stmt_delete_category = $pdo->prepare($sql_delete_category);
            $stmt_delete_category->bindParam(':CategoryID', $CategoryID);
            $stmt_delete_category->execute();
            
            header("Location: manage-categories-admin.php");
            exit();
        } catch (PDOException $e) {
            echo "Error deleting category: " . $e->getMessage();
        }
    } else {
        header("Location: manage-categories-admin.php");
        exit();
    }
?>
