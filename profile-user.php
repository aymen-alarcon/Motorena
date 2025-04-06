<?php
    session_start();
    include 'db.php';
    include 'header.php';

    $username = "";
    $phone = "";
    $Bio = "";

    if (isset($_SESSION['UserID'])) {
        $UserID = $_SESSION['UserID'];
        
        $sql = "SELECT *, AVG(rating) AS avg_rating, ui.type 
                FROM utilisateur 
                INNER JOIN utilisateurinfo ui ON utilisateur.UserID = ui.UserID 
                LEFT JOIN profile_ratings ON utilisateur.UserID = profile_ratings.RatedUserID 
                LEFT JOIN social_media_links ON utilisateur.UserID = social_media_links.UserID 
                WHERE utilisateur.UserID = :UserID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':UserID', $UserID);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $sql_products = "SELECT p.*, pp.image_url 
                        FROM produit p 
                        INNER JOIN ProductPictures pp ON p.ProductID = pp.ProductID 
                        WHERE p.UserID = :UserID AND pp.is_deleted = 0 AND p.is_deleted = 0 AND is_sold = 0 
                        ORDER BY p.DateAdded DESC 
                        LIMIT 3";
        $stmt_products = $pdo->prepare($sql_products);
        $stmt_products->bindParam(':UserID', $UserID);
        $stmt_products->execute();
        $products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

        $rating_sql = "SELECT COUNT(*) AS rating_count FROM profile_ratings WHERE RatedUserID = ?";
        $rating_stmt = $pdo->prepare($rating_sql);
        $rating_stmt->execute([$UserID]);
        $rating_row = $rating_stmt->fetch(PDO::FETCH_ASSOC);
        $rating_count = $rating_row['rating_count'];

        $social_media_sql = "SELECT * FROM social_media_links WHERE UserID = :UserID";
        $social_media_stmt = $pdo->prepare($social_media_sql);
        $social_media_stmt->bindParam(':UserID', $UserID);
        $social_media_stmt->execute();
        $social_media_links = $social_media_stmt->fetch(PDO::FETCH_ASSOC);

        $subscription_sql = "SELECT * FROM user_subscriptions WHERE UserID = :UserID AND PlanID != 1";
        $subscription_stmt = $pdo->prepare($subscription_sql);
        $subscription_stmt->bindParam(':UserID', $UserID);
        $subscription_stmt->execute();
        $subscription = $subscription_stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $username = $user['username'];
            $phone = isset($user['phone']) ? $user['phone'] : "";
            $Bio = isset($user['Bio']) ? $user['Bio'] : "";
            $Adresse = isset($user['Adresse']) ? $user['Adresse'] : "";
            $added_at = isset($user['DateAdded']) ? $user['DateAdded'] : "";
            $type = $user['type'];
            $avg_rating = $user['avg_rating'];
        }
    }
?>
<style>
    b {
        font-weight: bold !important;
        font-size: 18px;
    }

    .btn {
        border: none;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .main-body {
        padding: 15px;
    }

    .card {
        box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid rgba(0,0,0,.125);
        border-radius: .25rem;
    }

    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 1rem;
    }

    .gutters-sm {
        margin-right: -8px;
        margin-left: -1px;
    }

    .gutters-sm > .col,
    .gutters-sm > [class*=col-] {
        padding-right: 8px;
        padding-left: 8px;
    }

    .mb-3,
    .my-3 {
        margin-bottom: 1rem!important;
    }

    .bg-gray-300 {
        background-color: #e2e8f0;
    }
    
    .h-100 {
        height: 100%!important;
    }
    
    a {
        text-decoration: none;
        color: #000; 
    }
    
    .button {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: rgb(20, 20, 20);
        border: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
        cursor: pointer;
        transition-duration: .3s;
        overflow: hidden;
        position: relative;
    }

    .svgIcon {
        width: 12px;
        transition-duration: .3s;
    }

    .svgIcon path {
        fill: white;
    }

    .button:hover {
        width: 140px;
        border-radius: 50px;
        transition-duration: .3s;
        background-color: rgb(255, 69, 69);
        align-items: center;
    }

    .button:hover .svgIcon {
        width: 50px;
        transition-duration: .3s;
        transform: translateY(60%);
    }

    .button::before {
        position: absolute;
        top: -20px;
        content: "Delete";
        color: white;
        transition-duration: .3s;
        font-size: 2px;
    }

    .button:hover::before {
        font-size: 13px;
        opacity: 1;
        transform: translateY(30px);
        transition-duration: .3s;
    }

    .edit-button {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: rgb(20, 20, 20);
        border: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
        cursor: pointer;
        transition-duration: .3s;
        overflow: hidden;
        position: relative;
    }

    .edit-button .svgIcon {
        width: 12px;
        transition-duration: .3s;
    }

    .edit-button .svgIcon path {
        fill: white;
    }

    .edit-button:hover {
        width: 140px;
        border-radius: 50px;
        transition-duration: .3s;
        background-color: rgb(75, 156, 211);
        align-items: center;
    }

    .edit-button:hover .svgIcon {
        width: 50px;
        transition-duration: .3s;
        transform: translateY(60%);
    }

    .edit-button::before {
        position: absolute;
        top: -20px;
        content: "Edit";
        color: white;
        transition-duration: .3s;
        font-size: 2px;
    }

    .edit-button:hover::before {
        font-size: 13px;
        opacity: 1;
        transform: translateY(30px);
        transition-duration: .3s;
    }
</style>
<section style="background-color: #eee;">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <?php if ($subscription): ?>
                            <i class="bi bi-patch-check-fill badge-icon"></i>
                        <?php endif; ?>
                        <?php $avatarURL = $user['profile_picture'] ?? 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp'; ?>
                        <center>
                            <img src="<?php echo $avatarURL; ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px; height:150px;">
                        </center>
                        <h5 class="my-3"><?php echo $username; ?></h5>
                        <p class="text-muted mb-1"><?php echo $Bio; ?></p>
                    </div>
                </div>
                <div class="card mb-4 mb-lg-0">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush rounded-3">
                            <?php if (!empty($social_media_links['Linkedin'])): ?>
                                <li class="list-group-item d-flex align-items-center p-3" style="justify-content: flex-start">
                                    <a class="d-flex" href="<?php echo $social_media_links['Linkedin']; ?>" target="_blank">
                                        <i class="fab fa-linkedin fa-lg" style="color: #0e76a8;"></i>
                                        <p class="mb-0 mx-3 ">Linkedin</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (!empty($social_media_links['Facebook'])): ?>
                                <li class="list-group-item d-flex align-items-center p-3" style="justify-content: flex-start">
                                    <a class="d-flex" href="<?php echo $social_media_links['Facebook']; ?>" target="_blank">
                                        <i class="fab fa-facebook fa-lg" style="color: #3b5998;"></i>
                                        <p class="mb-0 mx-3">Facebook</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (!empty($social_media_links['Instagram'])): ?>
                                <li class="list-group-item d-flex align-items-center p-3" style="justify-content: flex-start">
                                    <a class="d-flex" href="<?php echo $social_media_links['Instagram']; ?>" target="_blank">
                                        <i class="fab fa-instagram fa-lg" style="color: #d6249f;"></i>
                                        <p class="mb-0 mx-3">Instagram</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (!empty($social_media_links['Twitter'])): ?>
                                <li class="list-group-item d-flex align-items-center p-3" style="justify-content: flex-start">
                                    <a class="d-flex" href="<?php echo $social_media_links['Twitter']; ?>" target="_blank">
                                        <i class="fab fa-twitter fa-lg" style="color: #1DA1F2;"></i>
                                        <p class="mb-0 mx-3">Twitter</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0"><?php echo __('full_name'); ?></p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo $user['fullname']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0"><?php echo __('phone'); ?></p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo $user['phone']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0"><?php echo __('bio'); ?></p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo $Bio; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                            <p class="mb-0"><?php echo __('address'); ?></p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo $Adresse; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0"><?php echo __('member_since'); ?> </p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"> <?php echo date('d/m/Y', strtotime($added_at)); ?></</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gutters-sm">
                    <div class="col-sm-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="d-flex align-items-center mb-3"><?php echo __('rating'); ?></h6>
                                <small><?php echo __('overall_rating'); ?> <?php echo number_format($avg_rating, 2); ?> (<?php echo $rating_count; ?> <?php echo __('rating'); ?>)</small>
                                <div class="progress mb-3" style="height: 5px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo (($avg_rating - 1) * 25); ?>%;" aria-valuenow="<?php echo $avg_rating; ?>" aria-valuemin="0" aria-valuemax="5"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small>1</small>
                                    <small>2</small>
                                    <small>3</small>
                                    <small>4</small>
                                    <small>5</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>