<?php
session_start();
include 'header-admin.php';

if(isset($_GET['ProductID'])) {
    $ProductID = $_GET['ProductID'];

    $sql = "SELECT * FROM produit WHERE ProductID = :ProductID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ProductID', $ProductID);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="main-products-page p-4 mt-4">
    <h1 class="head-products-page pb-4">Edit Product</h1>
    <div class="form">
        <form class="form-product" action="update-product-admin.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="ProductID" value="<?php echo $product['ProductID']; ?>">
            <label class="m-2" for="status">Status</label>
            <input type="checkbox" id="status" name="status" value="1" <?php if($product['Statu'] == 1) echo 'checked'; ?>>
            <label class="m-2" for="name">Name</label>
            <input class="form-control" type="text" id="name" name="NomProduit" value="<?php echo $product['NomProduit']; ?>" required>
            <label class="m-2" for="price">Price</label>
            <input class="form-control" type="number"  min="0" id="price" name="Prix" value="<?php echo $product['Prix']; ?>" required>
            <label class="m-2" for="info">Information</label>
            <textarea class="form-control" id="info" name="Description" required><?php echo $product['Description']; ?></textarea>
            <label class="m-2" for="catégorie">Category</label>
            <select name="CategoryID" class="form-control" required>
                <option value="1" <?php if($product['CategoryID'] == 1) echo 'selected'; ?>>Motor</option>
                <option value="2" <?php if($product['CategoryID'] == 2) echo 'selected'; ?>>Accessory</option>
                <option value="3" <?php if($product['CategoryID'] == 3) echo 'selected'; ?>>Piece</option>
            </select>
            <label class="m-2" for="current_image">Current Image:</label>
            <?php
                $imageQuery = "SELECT image_url FROM ProductPictures WHERE ProductID = :ProductID";
                $imageStmt = $pdo->prepare($imageQuery);
                $imageStmt->execute(['ProductID' => $product['ProductID']]);
                $image = $imageStmt->fetch(PDO::FETCH_ASSOC);
                if ($image) {
                    echo '<img src="' . $image['image_url'] . '" alt="Current Image" style="max-width: 20%;">';
                } else {
                    echo 'No image available';
                }
            ?>
            <input class="form-control mt-3" type="submit" name="update_product" value="Save Changes">
        </form>
    </div>
</div>
<?php
    } else {
        echo 'ProductID not provided';
    }
?>