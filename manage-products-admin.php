<?php
session_start();
include 'header-admin.php';

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['UserID'];

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$start = ($page - 1) * $limit;

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $productsQuery = "SELECT p.*, pp.image_url, ui.fullname AS posted_by 
                      FROM produit p 
                      LEFT JOIN ProductPictures pp ON p.ProductID = pp.ProductID 
                      LEFT JOIN utilisateurinfo ui ON p.UserID = ui.UserID
                      WHERE p.ProductID = :searchTerm AND pp.is_main = 1";
    $productsStmt = $pdo->prepare($productsQuery);
    $productsStmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_INT);
    $productsStmt->execute();
    $products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $productsQuery = "SELECT p.*, pp.image_url, ui.fullname AS posted_by 
                      FROM produit p 
                      LEFT JOIN ProductPictures pp ON p.ProductID = pp.ProductID 
                      LEFT JOIN utilisateurinfo ui ON p.UserID = ui.UserID WHERE pp.is_main = 1
                      ORDER BY p.ProductID DESC
                      LIMIT :start, :limit";
    $productsStmt = $pdo->prepare($productsQuery);
    $productsStmt->bindValue(':start', $start, PDO::PARAM_INT);
    $productsStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $productsStmt->execute();
    $products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);
}

$totalProductsQuery = "SELECT COUNT(DISTINCT ProductID) AS total FROM produit";
$totalProductsStmt = $pdo->query($totalProductsQuery);
$totalProducts = $totalProductsStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalProducts / $limit);

$prevPage = $page > 1 ? $page - 1 : 1;
$nextPage = $page < $totalPages ? $page + 1 : $totalPages;
?>

<div class="content">
    <div class="glassmorphism">
        <h3>All Products</h3>
        <div class="row">
            <p class="col-md-8 d-flex align-items-center">Total Products: <?= $totalProducts ?></p>
            <div class="col-md-4 search-container">
                <form action="" method="GET">
                    <input type="text" placeholder="Search by Product ID" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Posted By</th>
                    <th>Image</th>
                    <th class="th-action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><a href="product-details-admin.php?productID=<?= htmlspecialchars($product['ProductID']) ?>"><?= htmlspecialchars($product['ProductID']) ?></a></td>
                        <td><a href="product-details-admin.php?productID=<?= htmlspecialchars($product['ProductID']) ?>"><?= htmlspecialchars($product['NomProduit']) ?></a></td>
                        <td><a href="product-details-admin.php?productID=<?= htmlspecialchars($product['ProductID']) ?>"><?= htmlspecialchars($product['Description']) ?></a></td>
                        <td><a href="product-details-admin.php?productID=<?= htmlspecialchars($product['ProductID']) ?>"><?= htmlspecialchars($product['Prix']) ?></a></td>
                        <td><a href="product-details-admin.php?productID=<?= htmlspecialchars($product['ProductID']) ?>"><?= htmlspecialchars($product['posted_by']) ?></a></td>
                        <td><a href="product-details-admin.php?productID=<?= htmlspecialchars($product['ProductID']) ?>"><img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Product Image" width="100"></a></td>
                        <td class="action-icons">
                            <a href="edit-product-admin.php?ProductID=<?= htmlspecialchars($product['ProductID']) ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete-product-admin.php?ProductID=<?= htmlspecialchars($product['ProductID']) ?>">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $prevPage ?>" tabindex="-1">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $nextPage ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
</body>
</html>