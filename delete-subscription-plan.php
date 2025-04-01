<?php
    session_start();
    if (isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {
        include 'db.php';

        if (isset($_GET['PlanID'])) {
            $planID = $_GET['PlanID'];

            $deletePlanQuery = "DELETE FROM subscription_plans WHERE PlanID = ?";
            $deletePlanStmt = $pdo->prepare($deletePlanQuery);
            $deletePlanStmt->execute([$planID]);

            header("Location: admin-dashboard.php");
            exit;
        }
    } else {
        header("Location: login.php");
        exit;
    }
?>