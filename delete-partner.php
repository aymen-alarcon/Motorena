<?php
session_start();
include 'db.php';

if(isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {
    if(isset($_GET['partner_id'])) {
        $partner_id = $_GET['partner_id'];
        
        $softDeleteQuery = "UPDATE partners SET is_deleted = 1 WHERE partner_id = :partner_id";
        $softDeleteStmt = $pdo->prepare($softDeleteQuery);
        $softDeleteStmt->execute(['partner_id' => $partner_id]);
        
        header("Location: manage-partners-admin.php");
        exit;
    } else {
        echo "Partner ID not provided.";
    }
} else {
    header("Location: login.php");
    exit;
}
?>
