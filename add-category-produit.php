<?php
    session_start();
    include 'db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["category_name"]) && isset($_POST["category_name_ar"]) && isset($_POST["category_name_fr"])) {
        $category_name = $_POST['category_name'];
        $category_name_ar = $_POST['category_name_ar'];
        $category_name_fr = $_POST['category_name_fr'];

        try {
            $sql_insert_category = "INSERT INTO categorieproduit (NomCategorie,NomCategorie_ar,NomCategorie_fr) VALUES (:category_name,:category_name_ar,:category_name_fr)";
            $stmt_insert_category = $pdo->prepare($sql_insert_category);
            $stmt_insert_category->bindParam(':category_name', $category_name);
            $stmt_insert_category->bindParam(':category_name_ar', $category_name_ar);
            $stmt_insert_category->bindParam(':category_name_fr', $category_name_fr);
            $stmt_insert_category->execute();

            header("Location: manage-settings-admin.php");
            exit();
        } catch (PDOException $e) {
            echo "Error adding category: " . $e->getMessage();
        }
    }
?>
