<?php
    session_start();
    include 'header.php';

    if (isset($_SESSION['UserID'])) {
        $userID = $_SESSION['UserID'];
    } else {
        $userID = null;
    }
?>
<div>
    <!-- Start Banner Hero -->
    <?php
        $sql = "SELECT DISTINCT produit.*, ProductPictures.image_url  
                    FROM produit  
                    LEFT JOIN ProductPictures ON produit.ProductID = ProductPictures.ProductID 
                    WHERE produit.statu = 1  AND produit.is_deleted = 0 AND produit.is_sold = 0 
                    AND ProductPictures.is_main = 1
                ";
        $result = $pdo->query($sql);

        if ($result->rowCount() > 0) {
            echo '<div id="slide-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">';
            $active = true;
            $slideIndex = 0;
            while ($row = $result->fetch()) {
                echo '<div class="carousel-item ' . ($active ? 'active' : '') . '">
                        <div class="container carousel-item-container">
                            <div class="row py-5">
                                <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                                    <a class="row-img" href="product.php?ProductID=' . $row['ProductID'] . '">
                                        <img class="img rounded-circle" src="' . $row['image_url'] . '">
                                    </a>
                                </div>
                                <div class="col-lg-6 mb-0 d-flex align-items-center justify-content-center">
                                    <div class="under-text">
                                        <h1 class="h1"><b>' . $row['NomProduit'] . '</b></h1>
                                        <div class="product-details">
                                            <p>' . $row['Description'] . '</p>
                                            <div class="banner_taital">' .  __('price') . '' . $row['Prix'] . '' .  __('DH') . ' <br></div>
                                        </div>
                                        <div class="btn-n">';
                                            if (isset($_SESSION['UserID'])) {
                                                echo'
                                                    <a href="add-favorites.php?ProductID=' . $row['ProductID'] . '&UserID=' . $userID . '" class="fw-bold">
                                                        ' . __('Add_to_watchlist') . '
                                                    </a>
                                                ';
                                            } else {
                                                echo'
                                                    <a href="#" onclick="showAlert()" class="fw-bold">
                                                        ' . __('Add_to_watchlist') . '
                                                    </a>
                                                ';
                                            }
                                            echo '
                                            <a href="product.php?ProductID=' . $row['ProductID'] . '" class="fw-bold ">' . __('see_more') . '</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                $active = false;
                $slideIndex++;
            }
            echo '</div> 
                    <a class="carousel-control-prev text-decoration-none w-auto ps-3" href="#slide-carousel" role="button" data-bs-slide="prev">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <a class="carousel-control-next text-decoration-none w-auto pe-3" href="#slide-carousel" role="button" data-bs-slide="next">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div style="text-align:center">';
            
            for ($i = 0; $i < $slideIndex; $i++) {
                echo '<span class="dot" data-bs-target="#slide-carousel" data-bs-slide-to="' . $i . '"' . ($i == 0 ? ' class="active"' : '') . '></span> ';
            }
            echo '</div>';
        } else {
            echo "No data found for the carousel.";
        }
    ?>
    <!-- End Banner Hero -->
</div>
<!-- Start Products -->
<?php
    $sql = "SELECT produit.*, ProductPictures.image_url, categorieproduit.NomCategorie
                FROM (
                    SELECT *,
                        ROW_NUMBER() OVER (PARTITION BY CategoryID ORDER BY RAND()) as row_num
                    FROM produit
                    WHERE is_deleted = 0 AND is_sold = 0
                ) 
                AS produit
                LEFT JOIN ProductPictures ON produit.ProductID = ProductPictures.ProductID 
                LEFT JOIN categorieproduit ON produit.CategoryID = categorieproduit.CategoryID
                WHERE produit.row_num <= 4
                AND ProductPictures.is_main = 1
                ORDER BY produit.CategoryID, produit.row_num";
    $result = $pdo->query($sql);
    $currentCategory = null;
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch()) {
            if ($row['NomCategorie'] != $currentCategory) {
                if ($currentCategory !== null) {
                    echo '</div></div></section>';
                }
                echo '<section class="bg-light">
                        <div class="container py-5">
                            <div class="row text-center py-3">
                                <div class="col-lg-6 m-auto">
                                    <h1 class="h1"><b>' . $row['NomCategorie'] . '</b></h1>
                                </div>
                            </div>
                            <div class="row">';
                $currentCategory = $row['NomCategorie'];
            }
            echo'<div class="col-12 col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="image-container position-relative">
                            <a href="product.php?ProductID=' . $row['ProductID'] . '">
                                <img class="card-img-top" src="' . $row['image_url'] . '">
                                <div class="product-overlay d-flex align-items-center justify-content-center">
                                    <ul class="list-unstyled">
                                        <li>
                                            <a class="btn btn-danger text-white" href="product.php?ProductID=' . $row['ProductID'] . '">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        </li>';
                                        if (isset($_SESSION['UserID'])) {
                                            echo '<li>
                                                <a class="btn btn-danger text-white mt-2" href="add-favorites.php?ProductID=' . $row['ProductID'] . '&UserID=' . $userID . '">
                                                    <i class="far fa-heart"></i>
                                                </a>
                                            </li>';
                                        } else {
                                            echo '<li>
                                                <a href="#" onclick="showAlert()" class="btn btn-danger text-white mt-2">
                                                    <i class="far fa-heart"></i>
                                                </a>
                                            </li>';
                                        }
                                    echo '</ul>
                                </div>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="h2 text-decoration-none text-dark">' . $row['NomProduit'] . '</div>
                            <ul class="list-unstyled d-flex justify-content-between">
                                <li class="text-muted text-right">' . $row['Prix'] . 'DH </li>
                            </ul>
                            <a href="product.php?ProductID=' . $row['ProductID'] . '" class="fw-bold ">' . __('see_more') . '</a>
                        </div>
                    </div>
                </div>
            ';
        }
            echo '</div></div></section>';
        }
        include 'footer.php';
    ?>
<script>
    function showAlert() {
        alert("<?php echo __('must_login'); ?>");
    }
</script> 
</body>
</html>