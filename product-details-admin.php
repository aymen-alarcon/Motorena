<?php
    session_start();
    include 'header-admin.php'; 

    $productID = $_GET['productID'] ?? null;
    $product = null;

    if ($productID) {
        $productQuery = "SELECT p.*, pp.image_url, u.username AS posted_by, f.UserID AS watchlist_user, a.UserID AS account_userID FROM produit p  LEFT JOIN ProductPictures pp ON p.ProductID = pp.ProductID  LEFT JOIN utilisateur u ON p.UserID = u.UserID LEFT JOIN favorites f ON p.ProductID = f.ProductID LEFT JOIN utilisateurinfo a ON p.UserID = a.UserID WHERE p.ProductID = :productID";
        $productStmt = $pdo->prepare($productQuery);
        $productStmt->bindParam(':productID', $productID, PDO::PARAM_INT);
        $productStmt->execute();
        $product = $productStmt->fetch(PDO::FETCH_ASSOC);

        $reportsQuery = "SELECT n.*, u.username AS reporter_username FROM notifications n LEFT JOIN utilisateur u ON n.UserID = u.UserID WHERE n.EntityID = :productID AND n.Type = 'report'";
        $reportsStmt = $pdo->prepare($reportsQuery);
        $reportsStmt->bindParam(':productID', $productID, PDO::PARAM_INT);
        $reportsStmt->execute();
        $reports = $reportsStmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>
        <div class="content">
            <?php if ($product): ?>
                <div class="product-container">
                    <div class="product-card">
                        <img src="<?= $product['image_url'] ?>" alt="Product Image">
                        <div class="product-info">
                            <h2><?= $product['NomProduit'] ?></h2>
                            <p>Price: <?= $product['Prix'] ?></p>
                        </div>
                    </div>
                    <div class="product-details">
                        <h3>Product Details</h3>
                        <p>Description: <?= $product['Description'] ?></p>
                        <p>Posted By: <a href="account-info-admin.php?userID=<?= $product['account_userID'] ?>"><?= $product['posted_by'] ?></a></p>
                        <p>Posted At: <?= $product['DateAdded'] ?></p>
                        <?php if ($product['watchlist_user']): ?>
                            <p>Added to Watchlist By: <?= $product['watchlist_user'] ?></p>
                        <?php endif; ?>
                        <?php if (isset($product['is_deleted']) && $product['is_deleted']): ?>
                            <p>This product has been deleted.</p>
                        <?php else: ?>
                            <p>This product is active.</p>
                        <?php endif; ?>
                        <?php if (isset($product['statu']) && $product['statu'] == 1): ?>
                            <p>This product is sponsored.</p>
                        <?php else: ?>
                            <p>This product is not sponsored.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <p>Product not found.</p>
            <?php endif; ?>
            <div class="product-notification my-4 ms-0">
                <h3>Product Reports</h3>
                <?php
                    if ($reports) {
                        foreach ($reports as $report) {
                            echo'<div class="report">
                                    <p><strong>Reported By:</strong> ' . $report['reporter_username'] . '</p>
                                    <p><strong>Message:</strong> ' . $report['Message'] . '</p>
                                </div>
                            ';
                        }
                    } else {
                        echo '<p>No reports for this product.</p>';
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>