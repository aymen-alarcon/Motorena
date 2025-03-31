<?php
session_start();
include 'header.php';

if (isset($_SESSION['UserID'])) {
    $userID = $_SESSION['UserID'];
} else {
    $userID = null;
}

$categoryType = isset($_GET['category']) ? $_GET['category'] : null;

$sql = "SELECT DISTINCT produit.*, ProductPictures.image_url FROM produit LEFT JOIN ProductPictures ON produit.ProductID = ProductPictures.ProductID WHERE produit.CategoryID = (SELECT CategoryID FROM categorieproduit WHERE NomCategorie = ?) AND produit.is_deleted = 0  AND is_sold = 0  AND ProductPictures.is_main = 1 ORDER BY RAND()";
$stmt = $pdo->prepare($sql);
$stmt->execute([$categoryType]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="bg-light">
    <div class="container py-5" style="min-height: 47vh;">
        <div class="row text-center py-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1"><b><?php echo $categoryType; ?></b></h1>
            </div>
        </div>
        <div class="row">
            <?php foreach ($products as $row): ?>
                <div class="col-12 col-md-3 mb-4">
                    <div class="card h-100">
                        <a href="product.php?ProductID=<?php echo $row['ProductID']; ?>">
                            <img class="card-img-top" src="<?php echo $row['image_url']; ?>">
                        </a>
                        <div class="card-body">
                            <div class="first-row">
                                <div class="h2 text-decoration-none text-dark  w-50"><?php echo $row['NomProduit']; ?></div>
                                <ul class="list-unstyled d-flex justify-content-between">
                                    <li class="text-muted text-right"><?php echo $row['Prix']; ?>DH </li>
                                </ul>
                            </div>
                            <p class="card-text"><?php echo $row['Description']; ?></p>
                            <a  class="fw-bold" href="product.php?ProductID=<?php echo $row['ProductID']; ?>"><?php echo __('see_more'); ?></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>
</body>
</html>
