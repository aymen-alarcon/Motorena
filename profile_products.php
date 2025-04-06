<?php
    session_start();
    include 'header.php';

    if (isset($_SESSION['UserID'])) {
        $userID = $_SESSION['UserID'];
    } else {
        $userID = null;
    }

    $sql = "SELECT produit.*, ProductPictures.image_url, categorieproduit.NomCategorie
                FROM produit
                LEFT JOIN ProductPictures ON produit.ProductID = ProductPictures.ProductID 
                LEFT JOIN categorieproduit ON produit.CategoryID = categorieproduit.CategoryID
                WHERE produit.is_deleted = 0 AND produit.is_sold = 0 AND produit.UserID = :UserID AND ProductPictures.is_main = 1
                ORDER BY produit.CategoryID, produit.ProductID
            ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':UserID' => $userID]);
    $result = $stmt->fetchAll();

    if (empty($result)) {
        $noProductsMessage = [
            'en' => 'No products available.',
            'ar' => 'لا تتوفر منتجات.',
            'fr' => 'Aucun produit disponible.'
        ];

        $language = 'en';

        echo '<section class="bg-light" style="min-height: 25.6rem;">
                <div class="container py-5">
                <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                <h1 class="h1"><b>' . $noProductsMessage[$language] . '</b></h1>
                </div>
                </div>
                </div>
             </section>
        ';
    } else {
?>
<style>
    .card {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }
</style>
<section class="bg-light" style="min-height: 25.6rem;">
    <div class="container py-5">
        <div class="row text-center py-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1"><b><?php echo __('my_products'); ?></b></h1>
            </div>
        </div>
        <div class="row">
            <?php foreach ($result as $product): ?>
                <div class="col-sm-4 mb-3">
                    <div class="card">
                        <a class="row-img" href="product.php?ProductID=<?php echo $product['ProductID'] ; ?>">
                            <img class="card-img-top" src="<?php echo $product['image_url'] ; ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['NomProduit']; ?></h5>
                            <p class="card-text"><?php echo $product['Description']; ?></p>
                            <p class="card-text"><?php echo $product['Prix']; ?> USD</p>
                            <div class="product-actions">
                                <button class="btn_delete">
                                    <span class="paragraph"><?php echo __('edit'); ?></span>
                                    <span class="icon-wrapper">
                                        <a style="color: #000;" href="edit-product.php?ProductID=<?php echo $product['ProductID']; ?>">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                    </span>
                                </button>                                    
                                <button class="btn_delete">
                                    <span class="paragraph"><?php echo __('delete'); ?></span>
                                    <span class="icon-wrapper">
                                        <a href="delete-product.php?ProductID=<?php echo $product['ProductID']; ?>">
                                            <svg class="icon" width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6 7V18C6 19.1046 6.89543 20 8 20H16C17.1046 20 18 19.1046 18 18V7M6 7H5M6 7H8M18 7H19M18 7H16M10 11V16M14 11V16M8 7V5C8 3.89543 8.89543 3 10 3H14C15.1046 3 16 3.89543 16 5V7M8 7H16" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </a>
                                    </span>
                                </button>                                    
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php } ?>
<?php include 'footer.php'; ?>
