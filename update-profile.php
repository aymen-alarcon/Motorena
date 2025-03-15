<?php
session_start();
include "db.php";

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['UserID'];
$email = $_SESSION['email'];
$username = $_SESSION['name'];

if(isset($_POST['update_profile'])) {
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $Gender = $_POST['Gender'];
    $Adresse = $_POST['Adresse'];
    $CodePostal = $_POST['CodePostal'];
    $Bio = $_POST['Bio'];
    $birthday = $_POST['birthday'];
    $Facebook = $_POST['Facebook'];
    $Instagram = $_POST['Instagram'];
    $paypal = $_POST['paypal'];
    $Linkedin = $_POST['Linkedin'];

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_name = $_FILES['profile_picture']['name'];
        $file_dest = 'users/' . $file_name;

        if (move_uploaded_file($file_tmp, $file_dest)) {
            $sql_update_picture = "UPDATE utilisateurinfo SET profile_picture = :profile_picture WHERE UserID = :UserID";
            $stmt_update_picture = $pdo->prepare($sql_update_picture);
            $stmt_update_picture->bindParam(':profile_picture', $file_dest);
            $stmt_update_picture->bindParam(':UserID', $userID);
            $stmt_update_picture->execute();
        } else {
            echo "Failed to upload the file.";
        }
    }

    try {
        $sql = "UPDATE utilisateur u LEFT JOIN utilisateurinfo ui ON u.UserID = ui.UserID SET  u.username = :username, ui.phone = :phone, ui.Gender = :Gender, ui.Adresse = :Adresse, ui.CodePostal = :CodePostal, ui.Bio = :Bio, ui.birthday = :birthday WHERE u.UserID = :UserID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':Gender', $Gender);
        $stmt->bindParam(':Adresse', $Adresse);
        $stmt->bindParam(':CodePostal', $CodePostal);
        $stmt->bindParam(':Bio', $Bio);
        $stmt->bindParam(':birthday', $birthday);
        $stmt->bindParam(':UserID', $userID);
        $stmt->execute();

        $sql_update_links = "UPDATE social_media_links SET Facebook = :Facebook, Instagram = :Instagram, paypal = :paypal, Linkedin = :Linkedin WHERE UserID = :UserID";
        $stmt_update_links = $pdo->prepare($sql_update_links);
        $stmt_update_links->bindParam(':Facebook', $Facebook);
        $stmt_update_links->bindParam(':Instagram', $Instagram);
        $stmt_update_links->bindParam(':paypal', $paypal);
        $stmt_update_links->bindParam(':Linkedin', $Linkedin);
        $stmt_update_links->bindParam(':UserID', $userID);
        $stmt_update_links->execute();

        header("Location: profile-user.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
