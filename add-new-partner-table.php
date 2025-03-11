<?php
    session_start();
    include 'header-admin.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $partnerName = htmlspecialchars($_POST['brand_name']);
        $partnerEmail = htmlspecialchars($_POST['email']);
        $partnerWebsite = htmlspecialchars($_POST['website_link']);

        if ($_FILES['logo']['name']) {
            $targetDir = "partners/";
            $fileName = basename($_FILES['logo']['name']);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilePath)) {
                    $insertQuery = "INSERT INTO partners (brand_name, logo, email, website_link) 
                                    VALUES (?, ?, ?, ?)";
                    $insertStmt = $pdo->prepare($insertQuery);
                    $insertStmt->bindParam(1, $partnerName);
                    $insertStmt->bindParam(2, $targetFilePath);
                    $insertStmt->bindParam(3, $partnerEmail);
                    $insertStmt->bindParam(4, $partnerWebsite);
                    
                    if ($insertStmt->execute()) {
                        echo '<script>alert("Partner added successfully.");</script>';
                        header("Location: manage-settings-admin.php");
                    } else {
                        echo "Error adding partner.";
                    }
                } else {
                    echo "Error uploading file.";
                }
            } else {
                echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
            }
        } else {
            echo "Please select a file.";
        }
    }
?>