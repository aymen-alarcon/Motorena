<?php
    session_start();
    include 'header.php';

    $ProductID = isset($_GET['ProductID']) ? $_GET['ProductID'] : null;

    $columnproduit = 'CityName';

    if ($language == 'ar') {
        $columnproduit = 'CityName_ar';
    } elseif ($language == 'fr') {
        $columnproduit = 'CityName_fr';
    }    
 

    $sql = "SELECT p.*, c.NomCategorie, pp.image_url, u.username, ui.fullname, ui.phone, ui.Bio, ui.profile_picture, sml.Facebook, sml.Instagram, sml.paypal, sml.LinkedIn, u.dateadded, $columnproduit AS CityName
                FROM produit p 
                INNER JOIN categorieproduit c ON p.CategoryID = c.CategoryID 
                LEFT JOIN ProductPictures pp ON p.ProductID = pp.ProductID AND pp.is_deleted = 0
                LEFT JOIN utilisateur u ON p.UserID = u.UserID 
                LEFT JOIN utilisateurinfo ui ON p.UserID = ui.UserID 
                LEFT JOIN social_media_links sml ON u.UserID = sml.UserID 
                LEFT JOIN cities ci ON p.CityID = ci.CityID
                WHERE p.ProductID = ? LIMIT 1
            ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ProductID]);
    $row = $stmt->fetch();

    if ($row) {
        $main_product_image_urls = [];
        $sql_images = "SELECT * FROM ProductPictures WHERE ProductID = ? AND is_deleted = 0";
        $stmt_images = $pdo->prepare($sql_images);
        $stmt_images->execute([$ProductID]);
        
        while ($row_image = $stmt_images->fetch()) {
            $main_product_image_urls[] = [
            'image_url' => $row_image['image_url'],
            'is_main' => $row_image['is_main']
        ];
    }

        $default_image_url = null;
        foreach ($main_product_image_urls as $image) {
            if ($image['is_main'] == 1) {
                $default_image_url = $image['image_url'];
                break;
            }
        }

        if (!$default_image_url && !empty($main_product_image_urls)) {
            $default_image_url = $main_product_image_urls[0]['image_url'];
        }

        $chunked_images = [];
        if (!empty($main_product_image_url)) {
            $chunked_images = array_chunk($main_product_image_url, 3);
        }

        $rating_sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS rating_count FROM profile_ratings WHERE RatedUserID = ?";
        $rating_stmt = $pdo->prepare($rating_sql);
        $rating_stmt->execute([$row['UserID']]);
        $rating_row = $rating_stmt->fetch(PDO::FETCH_ASSOC);
        $avg_rating = $rating_row['avg_rating'];
        $rating_count = $rating_row['rating_count'];

        $product = $row;

        $ProductUserID = $product['UserID'];

        $related_products_sql = "SELECT p.*, pp.image_url
                                    FROM produit p 
                                    INNER JOIN ProductPictures pp ON p.ProductID = pp.ProductID 
                                    WHERE p.CategoryID = ? 
                                    AND p.Prix BETWEEN (? - 500) AND (? + 500) 
                                    AND p.is_deleted = 0 
                                    AND is_sold = 0
                                    AND p.ProductID != ? 
                                    AND p.UserID = ?
                                    AND pp.is_main = 1
                                    LIMIT 4";
        $related_products_stmt = $pdo->prepare($related_products_sql);
        $related_products_stmt->execute([$product['CategoryID'], $product['Prix'], $product['Prix'], $ProductID, $product['UserID']]);
        $related_products = $related_products_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    if (isset($_SESSION['UserID'])) {
        $loggedInUserID = $_SESSION['UserID'];
        $userInfoStmt = $pdo->prepare("SELECT fullname, phone, Adresse FROM utilisateurinfo WHERE UserID = ?");
        $userInfoStmt->execute([$loggedInUserID]);
        $userInfo = $userInfoStmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $userInfo = [
            'fullname' => '',
            'phone' => '',
            'Adresse' => '',
        ];
    }
    
    if (isset($_SESSION['UserID'])) {
        $loggedInUserID = $_SESSION['UserID'];
        $favoriteCheckSql = "SELECT COUNT(*) FROM favorites WHERE UserID = ? AND ProductID = ?";
        $favoriteCheckStmt = $pdo->prepare($favoriteCheckSql);
        $favoriteCheckStmt->execute([$loggedInUserID, $ProductID]);
        $favoriteCount = $favoriteCheckStmt->fetchColumn();
    
        $isFavorite = ($favoriteCount > 0);
    }
        
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $loggedInUserID = $_SESSION['UserID'];
        $ProductID = $_POST['ProductID'];
        $favorite = $_POST['favorite'];
    
        if ($favorite == 1) {
            if ($favoriteCount == 0) {
                $insertFavoriteSql = "INSERT INTO favorites (UserID, ProductID) VALUES (?, ?)";
                $insertFavoriteStmt = $pdo->prepare($insertFavoriteSql);
                $insertFavoriteStmt->execute([$loggedInUserID, $ProductID]);
                echo 'Favorite added successfully';
            }
        } else {
            if ($favoriteCount > 0) {
                $deleteFavoriteSql = "DELETE FROM favorites WHERE UserID = ? AND ProductID = ?";
                $deleteFavoriteStmt = $pdo->prepare($deleteFavoriteSql);
                $deleteFavoriteStmt->execute([$loggedInUserID, $ProductID]);
                echo 'Favorite removed successfully';
            }
        }
    }
?>
<style>
    .carousel-control-prev, .d-block {
        width: 25rem;
        border-radius: 10px;
        max-height: 30rem;
    }

    .modal-content {
        width: 800px;
        margin: auto;
        min-height: 35rem;
    }

    .carousel-item img {
        max-width: 100%;
    }

    .carousel-control-prev,
    .carousel-control-next {
        position: fixed;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;    
    }

    .card.p-4{
        width: 350px;
        border: none;
        cursor: pointer;
        transition: all 0.5s;
    }

    .image img {
        transition: all 0.5s
    }

    .card:hover .image img {
        transform: scale(1.5)
    }

    .card {
        overflow: hidden;
        position: relative;
    }

    .card-img.rounded-0.img-fluid {
        width: 100%;
        transform: scale(1.1);
        transition: transform 0.3s ease-in-out;
    }

    .card-img.rounded-0.img-fluid:hover {
        transform: scale(1);
    }
</style>
    <div class="modal fade" id="buyNowModal" tabindex="-1" role="dialog" aria-labelledby="buyNowModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buyNowModalLabel"><?php echo $translations['Purchase']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="command.php" method="post">
                        <legend style="font-weight: bold;"><?php echo $translations['cmd_now']; ?></legend>
                        <div class="form-group">
                            <label for="name"><?php echo $translations['name']; ?></label>
                            <input type="text" name="name" id="name" class="form-control" required value="<?php echo htmlspecialchars($userInfo['fullname']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone_number"><?php echo $translations['phone_number']; ?></label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control" required value="<?php echo htmlspecialchars($userInfo['phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="address"><?php echo $translations['address']; ?></label>
                            <input type="text" name="address" id="address" class="form-control" required value="<?php echo htmlspecialchars($userInfo['Adresse']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="quantity"><?php echo $translations['Quantity']; ?></label>
                            <input type="number"  min="0" name="quantity" id="quantity" class="form-control" required onchange="calculateTotal()">
                        </div>
                        <input type="hidden" name="ProductID" value="<?php echo htmlspecialchars($ProductID); ?>">
                        <div class="form-group">
                            <label for="total_price"><?php echo $translations['Total_Price']; ?>(DH)</label>
                            <input type="text" name="total_price" id="total_price" class="form-control" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3"><?php echo $translations['submit']; ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Open Content -->
    <section class="bg-light pb-5">
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-5 mt-5">
                    <div class="card mb-3">
                        <img class="card-img img-fluid" src="<?php echo $default_image_url; ?>" alt="Card image cap" id="product-detail">
                    </div>
                    <div class="row">
                        <?php if (count($main_product_image_urls) > 3) : ?>
                            <div class="col-1 align-self-center">
                                <a href="#multi-item-example" role="button" data-bs-slide="prev">
                                    <i class="text-dark fas fa-chevron-left"></i>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </div>
                            <div id="multi-item-example" class="col-10 carousel slide carousel-multi-item" data-bs-ride="carousel">
                                <div class="carousel-inner product-links-wap" role="listbox">
                                    <?php
                                        $chunked_images = array_chunk($main_product_image_urls, 3);
                                        foreach ($chunked_images as $index => $chunk) :
                                    ?>
                                        <div class="carousel-item <?php echo ($index === 0 ? ' active' : '');?>">
                                            <div class="row">
                                                <?php foreach ($chunk as $image) : ?>
                                                    <div class="col-4">
                                                        <a href="#">
                                                            <img class="card-img img-fluid" src="<?php echo $image['image_url']; ?>" alt="Product Image">
                                                        </a>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="col-1 align-self-center">
                                <a href="#multi-item-example" role="button" data-bs-slide="next">
                                    <i class="text-dark fas fa-chevron-right"></i>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        <?php else : ?>
                            <?php foreach ($main_product_image_urls as $image) : ?>
                                <div class="col-4  product-links-wap">
                                    <a href="#">
                                        <img class="card-img img-fluid" src="<?php echo $image['image_url']; ?>" alt="Product Image 1">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-7 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8" style="display: flex; justify-content: flex-start;">
                                    <h1 class="h2"><?php echo $row['NomProduit']; ?></h1>
                                </div>
                                <div class="col-md-4">
                                    <?php if (isset($_SESSION['UserID'])): ?>
                                        <form id="favoriteForm" action="favorite_handler.php" method="POST">
                                            <input type="hidden" id="ProductID" name="ProductID" value="<?= htmlspecialchars($ProductID) ?>">
                                            <input type="hidden" id="UserID" name="UserID" value="<?= htmlspecialchars($_SESSION['UserID']) ?>">
                                            
                                            <input type="hidden" name="updateType" value="<?= $isFavorite ? '0' : '1' ?>">
                                            
                                            <input type="checkbox" class="switch" id="favorite" name="favorite" data-notification-id="<?= $ProductID ?>"
                                                value="1" <?php echo $isFavorite ? 'checked' : ''; ?>>
                                            
                                            <label class="contain" for="favorite">
                                                <svg class="feather feather-heart" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="currentColor" fill="none" viewBox="0 0 24 24" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                                </svg>
                                                <div class="action">
                                                    <span class="option-1"><?php echo htmlspecialchars($translations['Add_to_watchlist']); ?></span>
                                                    <span class="option-2"><?php echo htmlspecialchars($translations['Added_to_favorites']); ?></span>
                                                </div>
                                            </label>
                                        </form>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const checkbox = document.getElementById('favorite');
                                            checkbox.addEventListener('change', function() {
                                                document.getElementById('favoriteForm').submit();
                                            });
                                        });
                                    </script>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="py-2"><?php echo $row['Prix']; ?> DH</p>
                                    <?php if (!empty($row['CityName'])): ?>
                                        <ul class="list-inline">
                                            <li class="list-inline-item">
                                                <h6><?php echo $translations['City']; ?>:</h6>
                                            </li>
                                            <li class="list-inline-item">
                                                <p class="text-muted"><strong><?php echo $row['CityName']; ?></strong></p>
                                            </li>
                                        </ul>
                                    <?php endif; ?>

                                    <ul class="list-inline">
                                        <li class="list-inline-item">
                                            <h6><?php echo $translations['Brand']; ?>:</h6>
                                        </li>
                                        <li class="list-inline-item">
                                            <p class="text-muted"><strong><?php echo $row['NomCategorie']; ?></strong></p>
                                        </li>
                                    </ul>
                                    <h6><?php echo $translations['Description']; ?>:</h6>
                                    <p><?php echo $row['Description']; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <?php if ($row['CategoryID'] == 1): ?>
                                        <?php if ($row['CategoryID'] == 1): ?>
                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <h6><?php echo $translations['Color']; ?></h6>
                                                </li>
                                                <li class="list-inline-item">
                                                    <p class="text-muted"><strong><?php echo $row['couleur']; ?></strong></p>
                                                </li>
                                            </ul>
                                        <?php endif; ?>
                                        <h6><?php echo $translations['Specification']; ?>:</h6>
                                        <ul class="list-unstyled" name="Specification">
                                            <li>
                                                <b class="category-title"><?php echo $translations['Mileage']; ?></b>
                                                <span class="color"><?php echo $row['kilometrage']; ?></span>
                                            </li>
                                            <li>
                                                <b class="category-title"><?php echo $translations['Year']; ?></b>
                                                <span class="color"><?php echo $row['annee']; ?></span>
                                            </li>
                                            <li>
                                                <b class="category-title"><?php echo $translations['fiscal_power']; ?></b>
                                                <span class="color"><?php echo $row['puissance_fiscale']; ?></span>
                                            </li>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <center>
                                <?php if ($row['command_statu'] == 1): ?>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buyNowModal" data-productid="<?php echo $ProductID; ?>">
                                        <?php echo $translations['Buy_Now']; ?>
                                    </button>
                                <?php endif; ?>
                            </center>
                            <div class="btn-product">
                                <?php if (isset($_SESSION['UserID'])) {
                                    echo'<div>
                                            <a href="declare_intrest.php?ProductID=' . $row['ProductID'] . '&UserID=' . $_SESSION['UserID'] . '"class="btn_">' . $translations['interested'] . '</a>
                                        </div>
                                    ';
                                    } else {
                                    echo'<div class="btn_">
                                            <span onclick="alert(\'' . __('must_login') . '\')">' . $translations['interested'] . '</span>
                                        </div>
                                    ';
                                    }
                                ?>
                                <?php
                                    if (isset($_SESSION['UserID'])) {
                                        echo '<div class="d-flex justify-content-end">
                                                <span class="btn_" onclick="prepareReportForm(' . $row['ProductID'] . ', null)">' . $translations['Report Product'] . '</span>
                                            </div>';

                                    } else {
                                        echo'<div class="d-flex justify-content-end">
                                                <span class="btn_" onclick="alert(\'' . __('must_login') . '\')">' . $translations['Report Product'] . '</span>
                                            </div>
                                        ';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <center>
            <div class="col-sm-6 mb-4 d-flex justify-content-center">
                <div class="my-4 py-4 bg-white" style="width: 80%;">
                    <div class="image">
                        <div class="flex-column justify-content-center align-items-center">
                            <img class="profile-picture" src="<?php echo $row['profile_picture']; ?>" alt="<?php echo $translations['Seller Profile Picture']; ?>">
                            <div class="name mt-3"><?php echo $row['fullname']; ?></div>
                            <div class="idd">@ <?php echo $row['username']; ?></div>
                            <div class="d-flex flex-row justify-content-center align-items-center gap-2 my-3"> 
                                <span><i class="fa fa-mobile" aria-hidden="true"></i></span> 
                                <span class="idd1"><?php echo $row['phone']; ?></span>
                            </div>
                        <div>
                        <a href="profile-watch.php?UserID=<?php echo $row['UserID']; ?>" class="btn btn-gradient"><?php echo $translations['Visit Profile']; ?></a>
                    </div>
                    <div class="text">
                        <span><?php echo $row['Bio']; ?></span> 
                    </div> 
                    <div class=" px-2 mt-4 date "> 
                        <span class="join"><?php echo date('d/m/Y', strtotime( $row['dateadded'])); ?></span>
                    </div>
                </div>
            </div>            
        </center>
        <center style="margin: 0rem 1rem;">
            <div class="col-sm-12">
                <section class="py-4 bg-white">
                    <div class="container">
                        <div class="text-left p-2 pb-3">
                            <center>
                                <h4><?php echo $translations['Related Products']; ?></h4>
                            </center>
                        </div>
                        <div class="row">
                            <?php foreach ($related_products as $related_product): ?>
                                <div class="col-md-3">
                                    <div class="product-wap card rounded-0">
                                        <div class="card rounded-0">
                                            <a href="product.php?ProductID=<?php echo $related_product['ProductID']; ?>">
                                                <img class="card-img rounded-0 img-fluid" src="<?php echo $related_product['image_url']; ?>">
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <span class="h3 text-decoration-none"><?php echo $related_product['NomProduit']; ?></span>
                                            <p class="text-center mb-0"><?php echo $related_product['Prix']; ?> <?php echo $translations['DH']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            </div>
        </center>
    </section>
    <!-- Close Content -->
    <!-- Start Article -->
    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content report" style="min-height: 0rem;">
                <div class="modal-header report">
                    <h5 class="modal-title report p-2" id="reportModalLabel" style="color:white;"><?php echo $translations['Report Product'] ?> : <?php echo $ProductID; ?></h5>
                </div>
                <div class="modal-body report" style="background-color: #fff;">
                    <form action="report.php" method="POST" id="reportForm">
                        <div class="form-group">
                            <label for="reason"><?php echo $translations['Reason for Report']; ?></label>
                            <textarea class="form-control py-5 my-5" style="background-color: aliceblue;" id="reason" name="reason" rows="3" required></textarea>
                        </div>
                        <input type="hidden" id="reportedProductID" name="reportedProductID">
                        <input type="hidden" id="reportedUserID" name="reportedUserID">
                        <input type="submit" id="submit-report">
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php include 'footer.php'; ?>
<script>
    $('#carousel-related-product').slick({
        infinite: true,
        arrows: false,
        slidesToShow: 4,
        slidesToScroll: 3,
        dots: true,
        responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 3
                }
            }
        ]
    });

    function calculateTotal() {
        var pricePerUnit = <?php echo $product['Prix']; ?>;
        var quantity = parseInt(document.getElementById('quantity').value);
        var totalPrice = pricePerUnit * quantity;
        document.getElementById('total_price').value = totalPrice.toFixed(2);
    }

    $('#buyNowModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var ProductID = button.data('productid');
        var modal = $(this);
        modal.find('#product_id').val(ProductID);
    });

    document.addEventListener("DOMContentLoaded", function() {
        var checkboxes = document.querySelectorAll('.switch');

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var notificationID = this.dataset.notificationId;
                var updateForm = document.getElementById('favoriteForm');

                updateForm.querySelector('input[name="updateType"]').value = this.checked ? 'checked' : 'unchecked';
                updateForm.submit();
            });
        });
    });
</script>