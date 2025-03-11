<?php
    session_start();
    include 'db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_category_name"]) && isset($_POST["user_category_name_ar"]) && isset($_POST["user_category_name_fr"])) {
        $user_category_name = $_POST['user_category_name'];
        $user_category_name_ar = $_POST['user_category_name_ar'];
        $user_category_name_fr = $_POST['user_category_name_fr'];

        try {
            $sql_insert_category = "INSERT INTO categorieuser (NomCategorie,NomCategorie_ar,NomCategorie_fr) VALUES (:user_category_name,:user_category_name_ar,:user_category_name_fr)";
            $stmt_insert_category = $pdo->prepare($sql_insert_category);
            $stmt_insert_category->bindParam(':user_category_name', $user_category_name);
            $stmt_insert_category->bindParam(':user_category_name_ar', $user_category_name_ar);
            $stmt_insert_category->bindParam(':user_category_name_fr', $user_category_name_fr);
            $stmt_insert_category->execute();

            header("Location: manage-settings-admin.php");
            exit();
        } catch (PDOException $e) {
            echo "Error adding category: " . $e->getMessage();
        }
    }
?>