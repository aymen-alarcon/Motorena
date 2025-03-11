<?php
    session_start();
    if (isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {
        include 'db.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $plan_name = $_POST['plan_name'];
            $plan_name_fr = $_POST['plan_name_fr'];
            $plan_name_ar = $_POST['plan_name_ar'];
            $max_quantity_per_product = $_POST['max_quantity_per_product'];
            $price = $_POST['price'];
            $access_to_premium_features = isset($_POST['access_to_premium_features']) ? 1 : 0;
            $verification_mark = isset($_POST['verification_mark']) ? 1 : 0;

            $addPlanQuery = "INSERT INTO subscription_plans (name, name_fr, name_ar, max_quantity_per_product, price, access_to_premium_features, verification_mark) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $addPlanStmt = $pdo->prepare($addPlanQuery);
            $addPlanStmt->execute([$plan_name, $plan_name_fr, $plan_name_ar, $max_quantity_per_product, $price, $access_to_premium_features, $verification_mark]);

            header("Location: admin-dashboard.php");
            exit;
        }
    } else {
        header("Location: login.php");
        exit;
    }
?>