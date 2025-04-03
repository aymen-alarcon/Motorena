<?php
session_start();
include "db.php";

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $UserID = $_SESSION['UserID'];
    $username = $_SESSION['username'];

    $Fullname = isset($_POST['Fullname']) ? $_POST['Fullname'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $Adresse = isset($_POST['Adresse']) ? $_POST['Adresse'] : '';
    $codePostal = isset($_POST['CodePostal']) ? $_POST['CodePostal'] : '';
    $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : 1;
    $Instagram = isset($_POST['Instagram']) ? $_POST['Instagram'] : '';
    $Facebook = isset($_POST['Facebook']) ? $_POST['Facebook'] : '';
    $Paypal_account = isset($_POST['Paypal_account']) ? $_POST['Paypal_account'] : '';
    $linkedin = isset($_POST['linkedin']) ? $_POST['linkedin'] : '';
    $bio = isset($_POST['Bio']) ? $_POST['Bio'] : '';

    $message = "";

    if ($type == 2) {
        $message = "Have requested to become a seller.";
    } elseif ($type == 3) {
        $message = "Have requested to become an admin.";
    }

    $initialType = 1;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurinfo WHERE UserID = ?");
        $stmt->execute([$UserID]);
        $userExists = $stmt->fetchColumn();

        if ($userExists) {
            header("Location: index.php?message=" . urlencode("There was a problem. Please start again from the beginning.") . "&success=0");
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO notifications (UserID, DateAdded, Type, EntityID, Message) VALUES (?, CURRENT_TIMESTAMP, ?, ?, ?)");
        $stmt->execute([$UserID, 'request', $UserID, $message]);

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $profilePictureTmpName = $_FILES['profile_picture']['tmp_name'];
            $profilePictureName = $_FILES['profile_picture']['name'];
            $profilePicturePath = 'users/' . $profilePictureName;

            if (!move_uploaded_file($profilePictureTmpName, $profilePicturePath)) {
                header("Location: index.php?message=" . urlencode("Failed to move uploaded file.") . "&success=0");
                exit;
            }
        } else {
            header("Location: index.php?message=" . urlencode("Error in uploading profile picture.") . "&success=0");
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO utilisateurinfo (UserID, Fullname, phone, Adresse, CodePostal, birthday, gender, Bio, type, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$UserID, $Fullname, $phone, $Adresse, $codePostal, $birthday, $gender, $bio, $initialType, $profilePicturePath]);

        $stmt = $pdo->prepare("INSERT INTO social_media_links (UserID, Facebook, Instagram, linkedin, paypal) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$UserID, $Facebook, $Instagram, $linkedin, $Paypal_account]);

        $_SESSION['type'] = $initialType;

        if ($type == 2) {
            header("Location: subscription-plans.php?user_id=$UserID");
            exit;
        } else {
            header("Location: index.php?user_id=$UserID&message=" . urlencode("Account added successfully.") . "&success=1");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: index.php?message=" . urlencode("Error: " . $e->getMessage()) . "&success=0");
        exit;
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>