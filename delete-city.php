<?php
    session_start();
    include 'db.php';

    if (isset($_GET['CityID'])) {
        $CityID = $_GET['CityID'];

        try {
            $sql_delete_category = "DELETE FROM cities WHERE CityID = :CityID";
            $stmt_delete_category = $pdo->prepare($sql_delete_category);
            $stmt_delete_category->bindParam(':CityID', $CityID);
            $stmt_delete_category->execute();
            
            header("Location: manage-settings-admin.php");
            exit();
        } catch (PDOException $e) {
            echo "Error deleting category: " . $e->getMessage();
        }
    } else {
        header("Location: manage-settings-admin.php");
        exit();
    }
?>
