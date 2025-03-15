<?php
session_start();
include 'db.php';

if(isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $partner_id = $_POST['partner_id'];
        $brand_name = $_POST['brand_name'];
        $email = $_POST['email'];
        $website_link = $_POST['website_link'];
        
        $targetDirectory = "partners/";

        if ($_FILES["logo"]["error"] !== UPLOAD_ERR_OK) {
            echo "Error uploading file: " . $_FILES["logo"]["error"];
            exit;
        }

        $targetFile = $targetDirectory . basename($_FILES["logo"]["name"]);
        
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["logo"]["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }

        $logo = $targetFile;
        
        echo "Target File: " . $targetFile . "<br>";
        echo "Logo Variable: " . $logo . "<br>";

        $updateQuery = "UPDATE partners SET brand_name = :brand_name, email = :email, website_link = :website_link, logo = :logo WHERE partner_id = :partner_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute(['brand_name' => $brand_name, 'email' => $email, 'website_link' => $website_link, 'logo' => $logo, 'partner_id' => $partner_id]);
        
        if ($updateStmt->rowCount() > 0) {
            echo "Database update successful.";
        } else {
            echo "No rows were updated.";
        }

        header("Location: manage-partners-admin.php");
        exit;
    } else {
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>
