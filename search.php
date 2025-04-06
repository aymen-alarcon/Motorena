<?php
session_start();
include 'header.php';

echo '<div class="row m-4" style="min-height: 22.6rem;">';

if (isset($_GET['query']) || isset($_GET['price']) || isset($_GET['seller']) || isset($_GET['moroccan-cities'])) {
    $search_query = isset($_GET['query']) ? $_GET['query'] : '';
    $price = isset($_GET['price']) ? $_GET['price'] : '';
    $seller = isset($_GET['seller']) ? $_GET['seller'] : '';
    $city_id = isset($_GET['moroccan-cities']) ? $_GET['moroccan-cities'] : '';

    try {
        $sql = "SELECT p.*, pp.image_url 
                FROM produit p 
                INNER JOIN ProductPictures pp ON p.ProductID = pp.ProductID 
                WHERE 1=1";
        
        $params = [];

        if (!empty($search_query)) {
            $sql .= " AND p.NomProduit LIKE ?";
            $params[] = "%$search_query%";
        }

        if (!empty($price) && is_numeric($price)) {
            $sql .= " AND p.Prix <= ?";
            $params[] = $price;
        }

        if (!empty($seller)) {
            $sql .= " AND EXISTS (SELECT 1 FROM utilisateur u WHERE u.UserID = p.UserID AND (u.username LIKE ?))";
            $params[] = "%$seller%";
        }

        if (!empty($city_id)) {
            $sql .= " AND p.CityID = ?";
            $params[] = $city_id;
        }

        $sql .= " ORDER BY p.Prix DESC";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute($params);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($results)) {
            echo '<div class="col-12"><p>No products found matching your criteria.</p></div>';
        } else {
            foreach ($results as $product) {
                echo '<div class="col-12 col-md-4 mb-4">
                        <div class="card h-100">
                            <a href="product.php?ProductID=' . $product['ProductID'] . '">
                                <img class="card-img-top" src="' . $product['image_url'] . '">
                            </a>
                            <div class="card-body">
                                <div>
                                    <ul class="list-unstyled d-flex justify-content-between">
                                        <h2 class="text-muted text-right">' . $product['NomProduit'] . '</h2>
                                        <li class="text-muted text-right" style="float: right;">' . $product['Prix'] . 'DH </li>
                                    </ul>
                                </div>
                                <p class="card-text">' . $product['Description'] . '</p>
                                <div class="btn-n">
                                    <a href="product.php?ProductID=' . $product['ProductID'] . '">' . __('see_more') . '</a>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

echo '</div>';
include 'footer.php';
?>
