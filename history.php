<?php
session_start();
include 'header.php';

if (isset($_SESSION['UserID'])) {
    $userID = $_SESSION['UserID'];

    $productsQuery = "SELECT p.*, 
                            pp.image_url,
                            CASE 
                                WHEN p.is_deleted = 1 THEN 'Deleted' 
                                WHEN p.is_sold = 1 THEN 'Sold' 
                                ELSE 'Posted' 
                            END AS status,
                            CASE
                                WHEN f.ProductID IS NOT NULL THEN 1
                                ELSE 0
                            END AS is_sponsored,
                            dp.datedeleted AS DateDeleted,
                            CASE 
                                WHEN pi.buyerID IS NOT NULL THEN 'Interested' 
                                ELSE NULL 
                            END AS interest_status
                      FROM produit p 
                      LEFT JOIN (
                            SELECT ProductID, image_url
                            FROM ProductPictures
                            WHERE is_main = 1
                      ) pp ON p.ProductID = pp.ProductID 
                      LEFT JOIN favorites f ON p.ProductID = f.ProductID AND f.UserID = ?
                      LEFT JOIN deleted_products dp ON p.ProductID = dp.ProductID
                      LEFT JOIN product_interest pi ON p.ProductID = pi.ProductID AND pi.buyerID = ?
                      WHERE p.UserID = ? OR pi.buyerID = ?
                      GROUP BY p.ProductID
                      ORDER BY p.DateAdded DESC";

    $productsStmt = $pdo->prepare($productsQuery);
    $productsStmt->execute([$userID, $userID, $userID, $userID]);
    $products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
    body {
        font-family: Arial, sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .product-card {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .product-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .product-card img {
        height: 15rem;
        width: 100%;
        border-radius: 5px;
    }

    .product-card .product-info {
        margin-bottom: 10px;
    }

    .product-card .product-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .product-card .product-meta {
        font-size: 14px;
        color: #666666;
    }
</style>
<div class="p-5">
    <div class="header">
        <h1><?php echo $translations['account_history']; ?></h1>
    </div>
    <center>
        <form class="filter-form col-md-4 mb-4" method="GET">
            <label for="start_date"><?php echo $translations['date_start']; ?></label>
            <input type="date" id="start_date" name="start_date">
            <label for="end_date"><?php echo $translations['date_end']; ?></label>
            <input type="date" id="end_date" name="end_date">
            <input type="submit" class="form-control" value="<?php echo $translations['apply_filter']; ?>">
        </form>
    </center>
</div>   
<div class="row mx-1">
    <?php foreach ($products as $product): ?>
        <?php 
            if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
                $startDate = $_GET['start_date'];
                $endDate = $_GET['end_date'];
                
                $productDate = strtotime($product['DateAdded']);
                $startTimestamp = strtotime($startDate);
                $endTimestamp = strtotime($endDate);
                
                if ($productDate < $startTimestamp || $productDate > $endTimestamp) {
                    continue;
                }
            }
        ?>
        <div class="col-md-3 mb-4">
            <div class="product-card m-0">
                <div class="status-indicator">
                    <?php
                        if (isset($product['interest_status']) && $product['interest_status'] === 'Interested') {
                            echo $translations['interested'];
                        } elseif ($product['is_sold'] == 1) {
                            echo $translations['sold'];
                        } elseif ($product['is_deleted'] == 1) {
                            echo $translations['deleted'];
                        } else {
                            echo $translations['posted'];
                        }
                    ?>
                </div>
                <?php if ($product['is_sponsored'] == 1 && $product['is_deleted'] != 1 && $product['is_sold'] != 1): ?>
                    <div class="sponsorship-indicator"><?php echo $translations['sponsored']; ?></div>
                <?php endif; ?>
                <img src="<?php echo $product['image_url']; ?>" class="img-fluid mb-2" alt="<?php echo $product['NomProduit']; ?>">
                <div class="product-info">
                    <h3 class="product-title"><?php echo $product['NomProduit']; ?></h3>
                    <p class="product-meta"><?php echo $translations['product_id']; ?>: <?php echo $product['ProductID']; ?></p>
                    <p class="product-meta"><?php echo $translations['added']; ?>: <?php echo date('d M Y', strtotime($product['DateAdded'])); ?></p>
                    <?php if ($product['status'] == 'Deleted'): ?>
                        <p class="product-meta"><?php echo $translations['deleted_date']; ?>: <?php echo date('d M Y', strtotime($product['DateDeleted'])); ?></p>
                    <?php endif; ?>
                    <?php 
                        $watchlistQuery = "SELECT COUNT(*) AS watchlistAdds FROM favorites WHERE ProductID = ?";
                        $watchlistStmt = $pdo->prepare($watchlistQuery);
                        $watchlistStmt->execute([$product['ProductID']]);
                        $watchlistResult = $watchlistStmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <p class="product-meta"><?php echo $translations['watchlist_adds']; ?>: <?php echo $watchlistResult['watchlistAdds']; ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
include 'footer.php';
} else {
    header("Location: login.php");
    exit;
}
?>