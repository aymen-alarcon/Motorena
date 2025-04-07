<?php
session_start();
include 'header-admin.php';

if(isset($_GET['CategoryID'])) {
    $CategoryID = $_GET['CategoryID'];

    $_SESSION['CategoryID'] = $CategoryID;

    $sql = "SELECT * FROM categorieuser WHERE CategoryID = :CategoryID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':CategoryID', $CategoryID);
    $stmt->execute();
    $Category = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<div class="main-products-page my-5 ">
    <h1 class="head-products-page">Edit Category User</h1>
    <div class="form">
        <form class="form-product" action="edit-user-table.php" method="post" enctype="multipart/form-data">
            <label class="m-2" for="name">Name</label>
            <input class="form-control" type="text" id="name" name="NomCategorie" value="<?php echo $Category['NomCategorie']; ?>" required>
            <label class="m-2" for="name">Name in arabic</label>
            <input class="form-control" type="text" id="name" name="NomCategorie_ar" value="<?php echo $Category['NomCategorie_ar']; ?>" required>
            <label class="m-2" for="name">Name in french</label>
            <input class="form-control" type="text" id="name" name="NomCategorie_fr" value="<?php echo $Category['NomCategorie_fr']; ?>" required>
            <input type="hidden" name="CategoryID" value="<?php echo $CategoryID; ?>">
            <input class="form-control mt-3" type="submit">
        </form>
    </div>
</div>