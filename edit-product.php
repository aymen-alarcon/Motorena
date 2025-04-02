<?php
    session_start();
    include 'header.php';

    if (isset($_GET['ProductID'])) {
        $ProductID = $_GET['ProductID'];

        $sql = "SELECT * FROM produit WHERE ProductID = :ProductID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ProductID', $ProductID);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql_images = "SELECT * FROM ProductPictures WHERE ProductID = ? AND is_deleted = 0";
        $stmt_images = $pdo->prepare($sql_images);
        $stmt_images->execute([$ProductID]);
        $product_images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
    .image-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .image-box {
        position: relative;
        margin: 0.5rem;
        display: inline-block;
        overflow: hidden;
    }

    .image-box img {
        display: block;
        border: 2px solid transparent;
        transition: border-color 0.3s ease-in-out;
        max-width: 10rem;
    }

    .image-box input[type="checkbox"] {
        position: absolute;
        top: 5px;
        right: 5px;
        transform: scale(1.5);
        cursor: pointer;
        border-radius: 50%;
        opacity: 0;
        z-index: 1;
    }

    .image-box input[type="checkbox"] + label {
        display: block;
        position: relative;
        cursor: pointer;
    }

    .image-box input[type="checkbox"]:checked + label img {
        border-color: red;
        box-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
    }
</style>
<div class="main-products-page p-4 bg-light">
    <h1 class="head-products-page pb-4"><?php echo $translations['edit_product']; ?></h1>
    <div class="form">
        <form class="form-product" action="update-product.php" method="post" enctype="multipart/form-data" id="productForm">
            <input type="hidden" name="ProductID" value="<?php echo $product['ProductID']; ?>">
            <label class="m-2" for="status"><?php echo $translations['sold_label']; ?></label>
            <label class="checkbox-btn" title="<?php echo $translations['sold_tooltip']; ?>">
                <input id="checkbox" type="checkbox" name="sold" value="1" <?php echo $product['is_sold'] ? 'checked' : ''; ?>>
                <span class="checkmark"></span>
            </label>
            <label class="m-2" for="name"><?php echo $translations['name_label']; ?></label>
            <input class="form-control" type="text" id="name" name="NomProduit" value="<?php echo $product['NomProduit']; ?>" required>
            <label class="m-2" for="price"><?php echo $translations['price_label']; ?></label>
            <input class="form-control" type="number"  min="0" id="price" name="Prix" value="<?php echo $product['Prix']; ?>" required>
            <label class="m-2" for="info"><?php echo $translations['information_label']; ?></label>
            <textarea class="form-control" id="info" name="Description" required><?php echo $product['Description']; ?></textarea>
            <div class="mt-3">
                <h4><?php echo $translations['image']; ?></h4>
                <div class="image-container">
                    <?php foreach ($product_images as $image): ?>
                        <div class="image-box">
                            <input type="checkbox" name="delete_images[]" value="<?php echo $image['PictureID']; ?>" id="delete_<?php echo $image['PictureID']; ?>">
                            <label for="delete_<?php echo $image['PictureID']; ?>">
                                <img src="<?php echo $image['image_url']; ?>" alt="Product Image" class="img-thumbnail" style="max-width: 10rem;">
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mt-3">
                <h4><?php echo $translations['upload_new_images']; ?></h4>
                <input class="form-control" type="file" name="product_images[]" multiple class="form-control-file">
            </div>
            <input class="form-control mt-3" type="submit" name="update_product" value="<?php echo $translations['save_changes']; ?>">
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('input[name="delete_images[]"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const img = this.parentNode.querySelector('img');
                if (this.checked) {
                    img.style.borderColor = 'red';
                } else {
                    img.style.borderColor = 'transparent';
                }
            });
        });
    });
</script>
<?php
    include 'footer.php';
    } else {
        echo $translations['product_id_not_provided'];
    }
?>