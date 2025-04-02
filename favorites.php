<?php
session_start();
include 'header.php';

if(isset($_SESSION['UserID'])) {
    $sql = "SELECT f.*, p.*, pp.image_url FROM favorites f 
            INNER JOIN produit p ON f.ProductID = p.ProductID 
            INNER JOIN ProductPictures pp ON p.ProductID = pp.ProductID 
            WHERE f.UserID = :UserID AND p.is_deleted = 0 AND pp.is_main = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':UserID', $_SESSION['UserID']);
    $stmt->execute();
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($favorites)) {
        echo "<div class='message d-flex'>
                <div class='col-md-12'>
                    <center>
                        <p>{$translations['no_products_watchlist']}</p>
                    </center>
                </div>
            </div>";
    } else {
        echo "<div class=' bg-light row m-0' style='min-height: 30.7rem;'>";
        foreach ($favorites as $favorite) {
?>
            <div class="col-md-4">
                <div class="card m-4 product-wap rounded-0">
                    <div class="card rounded-0">
                        <img class="card-img rounded-0 img-fluid" src="<?php echo $favorite['image_url']; ?>">
                        <div class="product-overlay d-flex align-items-center justify-content-center">
                            <ul class="list-unstyled">
                                <li>
                                    <a class="btn btn-danger text-white mb-2" href="product.php?ProductID=<?php echo $favorite['ProductID']; ?>">
                                        <i class="far fa-eye"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="btn btn-danger text-white" href="delete-favorites.php?ProductID=<?php echo $favorite['ProductID']; ?>">
                                        <i class="far fa-heart"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="h3 text-decoration-none"><?php echo $favorite['NomProduit']; ?></p>
                        <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                        </ul>
                        <p class="text-center mb-0"><?php echo '$' . $favorite['Prix']; ?></p>
                    </div>
                </div>
            </div>
        <?php
        }
        echo "</div>"; 
    }
} else {
    echo "<div class='message d-flex'>
            <div class='col-md-12'>
                <center>
                    <p>{$translations['login_to_view_favorites']}</p>
                </center>
            </div>
        </div>";
}
?>
<?php include 'footer.php'; ?>
</body>
</html>
