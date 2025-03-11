<?php
    session_start();
    include 'db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["city_name"])) {
        $city_name = $_POST['city_name'];

        try {
            $sql_insert_category = "INSERT INTO cities (CityName) VALUES (:city_name)";
            $stmt_insert_category = $pdo->prepare($sql_insert_category);
            $stmt_insert_category->bindParam(':city_name', $city_name);
            $stmt_insert_category->execute();

            header("Location: manage-settings-admin.php");
            exit();
        } catch (PDOException $e) {
            echo "Error adding category: " . $e->getMessage();
        }
    }
?>