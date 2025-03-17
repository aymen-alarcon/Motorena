<?php
session_start();
include 'header-admin.php';

if(isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {
    if(isset($_GET['partner_id'])) {
        $partner_id = $_GET['partner_id'];
        $partnerQuery = "SELECT * FROM partners WHERE partner_id = :partner_id";
        $partnerStmt = $pdo->prepare($partnerQuery);
        $partnerStmt->execute(['partner_id' => $partner_id]);
        $partner = $partnerStmt->fetch(PDO::FETCH_ASSOC);
        
        if($partner) {
?>
<style>
    .content {
        max-width: 600px;
        margin: auto;
        padding: 20px;
        background-color: white;
        margin: 2rem;
        border-radius: 10px;
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="text"],
    input[type="email"],
    input[type="file"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .image-preview {
        max-width: 100%;
        margin-top: 10px;
    }
    
    form{
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
</style>
<center>
    <div class="content">
        <h2>Edit Partner</h2>
        <form action="update-partner.php" method="POST" enctype="multipart/form-data">
            <input class="form-control" type="hidden" name="partner_id" value="<?= $partner_id ?>">
            <label for="brand_name">Brand Name:</label>
            <input class="form-control" type="text" name="brand_name" id="brand_name" value="<?= $partner['brand_name'] ?>" required><br><br>
            <label for="email">Email :</label>
            <input class="form-control" type="email" name="email" id="email" value="<?= $partner['email'] ?>" required><br><br>
            <label for="website_link">Website Link:</label>
            <input class="form-control" type="text" name="website_link" id="website_link" value="<?= $partner['website_link'] ?>" required><br><br>
            <label for="logo">Logo :</label>
            <input class="form-control" type="file" name="logo" id="logo"><br>
            <div class="image-preview">
                <img src="<?= $partner['logo'] ?>" alt="Partner Logo">
            </div>
            <br>
            <input class="form-control" type="submit" value="Update Partner">
        </form>
    </div>
</center>
<?php
        } else {
            echo "Partner not found.";
        }
    } else {
        echo "Partner ID not provided.";
    }
} else {
    header("Location: login.php");
    exit;
}
?>
