<?php
    session_start();

    include 'db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['NomCategorie']) && !empty($_POST['NomCategorie']) && isset($_POST['CategoryID'])) {
            $NomCategorie = $_POST['NomCategorie'];
            $NomCategorie_ar = $_POST['NomCategorie_ar'];
            $NomCategorie_fr = $_POST['NomCategorie_fr'];
            $CategoryID = $_POST['CategoryID'];

            try {
                $sql_update_category = "UPDATE categorieuser SET NomCategorie = :NomCategorie , NomCategorie_ar = :NomCategorie_ar , NomCategorie_fr = :NomCategorie_fr WHERE CategoryID = :CategoryID";
                $stmt_update_category = $pdo->prepare($sql_update_category);
                $stmt_update_category->bindParam(':NomCategorie', $NomCategorie);
                $stmt_update_category->bindParam(':NomCategorie_ar', $NomCategorie_ar);
                $stmt_update_category->bindParam(':NomCategorie_fr', $NomCategorie_fr);
                $stmt_update_category->bindParam(':CategoryID', $CategoryID);
                $stmt_update_category->execute();

                header("Location: manage-categories-admin.php");
                exit();
            } catch (PDOException $e) {
                echo "Error updating category: " . $e->getMessage();
            }
        } else {
            echo "Category name is required.";
        }
    } else {
        header("Location: manage-categories-admin.php");
        exit();
    }
?>
