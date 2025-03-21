<?php
session_start();
include 'header-admin.php';
include 'db.php';

$userID = null;
$account = null;
$accountinfo = null;
$products = [];
$notifications = [];
$commands = [];
$favorites = [];
$smlinks = [];
$subscriptions = [];

if (isset($_GET['userID'])) {
    $userID = $_GET['userID'];

    $accountQuery = "SELECT * FROM utilisateur WHERE UserID = :userID";
    $accountStmt = $pdo->prepare($accountQuery);
    $accountStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $accountStmt->execute();
    $account = $accountStmt->fetch(PDO::FETCH_ASSOC);

    $accountinfoQuery = "SELECT * FROM utilisateurinfo WHERE UserID = :userID";
    $accountinfoStmt = $pdo->prepare($accountinfoQuery);
    $accountinfoStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $accountinfoStmt->execute();
    $accountinfo = $accountinfoStmt->fetch(PDO::FETCH_ASSOC);

    $productsQuery = "SELECT p.*, pp.image_url FROM produit p LEFT JOIN ProductPictures pp ON p.ProductID = pp.ProductID WHERE p.UserID = :userID AND p.is_deleted = 0 AND pp.is_deleted = 0";
    $productsStmt = $pdo->prepare($productsQuery);
    $productsStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $productsStmt->execute();
    $products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

    $notificationsQuery = "SELECT * FROM notifications WHERE UserID = :userID";
    $notificationsStmt = $pdo->prepare($notificationsQuery);
    $notificationsStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $notificationsStmt->execute();
    $notifications = $notificationsStmt->fetchAll(PDO::FETCH_ASSOC);

    $commandQuery = "SELECT * FROM command WHERE UserID = :userID";
    $commandStmt = $pdo->prepare($commandQuery);
    $commandStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $commandStmt->execute();
    $commands = $commandStmt->fetchAll(PDO::FETCH_ASSOC);

    $favoritesQuery = "SELECT * FROM favorites WHERE UserID = :userID";
    $favoritesStmt = $pdo->prepare($favoritesQuery);
    $favoritesStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $favoritesStmt->execute();
    $favorites = $favoritesStmt->fetchAll(PDO::FETCH_ASSOC);

    $smlinksQuery = "SELECT * FROM social_media_links WHERE UserID = :userID";
    $smlinksStmt = $pdo->prepare($smlinksQuery);
    $smlinksStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $smlinksStmt->execute();
    $smlinks = $smlinksStmt->fetchAll(PDO::FETCH_ASSOC);

    $subscriptionsQuery = "SELECT us.*, sp.name FROM user_subscriptions us JOIN subscription_plans sp ON us.PlanID = sp.PlanID WHERE us.UserID = :userID";
    $subscriptionsStmt = $pdo->prepare($subscriptionsQuery);
    $subscriptionsStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $subscriptionsStmt->execute();
    $subscriptions = $subscriptionsStmt->fetchAll(PDO::FETCH_ASSOC);}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Details</title>
</head>
<body>
    <!-- Content -->
    <div class="content">
        <div class="glassmorphism">
            <div class="user-info-container">
                <h2>User Information</h2>
                <div class="row user-info">
                    <div class="col-md-4 user-details">
                        <ul>
                            <li><strong>UserID:</strong> <?= htmlspecialchars($account['UserID']) ?></li>
                            <li><strong>Full Name:</strong> <?= htmlspecialchars($accountinfo['fullname']) ?></li>
                            <li><strong>Phone:</strong> <?= htmlspecialchars($accountinfo['phone']) ?></li>
                            <li><strong>Email:</strong> <?= htmlspecialchars($account['email']) ?></li>
                            <li><strong>Address:</strong> <?= htmlspecialchars($accountinfo['Adresse']) ?></li>
                        </ul>
                    </div>
                    <div class="col-md-8 user-picture">
                        <?php if (!empty($accountinfo['profile_picture'])): ?>
                            <img src="<?= htmlspecialchars($accountinfo['profile_picture']) ?>" alt="User Picture" class="img-fluid">
                        <?php else: ?>
                            <img src="default_user_image.jpg" alt="Default User Picture" class="img-fluid">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="user-info-container">
                <h2>Products Posted</h2>
                <div class="card-container">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <a href="product-details-admin.php?productID=<?= htmlspecialchars($product['ProductID']) ?>" class="custom-card">
                                <img src="<?= htmlspecialchars($product['image_url']) ?>" class="custom-card-img-top" alt="Product Image">
                                <div class="custom-card-body">
                                    <h5 class="custom-card-title"><?= htmlspecialchars($product['NomProduit']) ?></h5>
                                    <p class="custom-card-text"><?= htmlspecialchars($product['Description']) ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No products posted by the user</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="user-info-container">
                <h2>Notifications</h2>
                <?php if (!empty($notifications)): ?>
                    <ul>
                        <?php foreach ($notifications as $notification): ?>
                            <li>
                                <?php if ($notification['Type'] === 'report'): ?>
                                    <strong>Report:</strong> 
                                    <?= htmlspecialchars($notification['Message']) ?> - 
                                    <span>Reporter: 
                                        <?php
                                            $reporterQuery = "SELECT fullname FROM utilisateurinfo WHERE UserID = :userID";
                                            $reporterStmt = $pdo->prepare($reporterQuery);
                                            $reporterStmt->bindParam(':userID', $notification['UserID'], PDO::PARAM_INT);
                                            $reporterStmt->execute();
                                            $reporter = $reporterStmt->fetch(PDO::FETCH_ASSOC);
                                            echo htmlspecialchars($reporter['fullname']);
                                        ?>
                                    </span>
                                <?php else: ?>
                                    <strong>Notification:</strong> <?= htmlspecialchars($notification['Message']) ?>
                                <?php endif; ?>
                                <?php if (!empty($notification['admin_response'])): ?>
                                    <div><strong>Admin Response:</strong> <?= htmlspecialchars($notification['admin_response']) ?></div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No notifications for the user.</p>
                <?php endif; ?>
            </div>
            <div class="user-info-container">
                <h2>Commands</h2>
                <?php if (!empty($commands)): ?>
                    <ul>
                        <?php foreach ($commands as $command): ?>
                            <li><strong>Command:</strong> <?= htmlspecialchars($command['command_details']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No commands for the user.</p>
                <?php endif; ?>
            </div>
            <div class="user-info-container">
                <h2>Favorites</h2>
                <?php if (!empty($favorites)): ?>
                    <ul>
                        <?php foreach ($favorites as $favorite): ?>
                            <li><strong>Favorite Product ID:</strong> <?= htmlspecialchars($favorite['ProductID']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No favorites for the user.</p>
                <?php endif; ?>
            </div>
            <div class="user-info-container">
                <h2>Social Media Links</h2>
                <?php if (!empty($smlinks)): ?>
                    <ul>
                        <?php foreach ($smlinks as $link): ?>
                            <li><strong>Facebook Link:</strong> <?= htmlspecialchars($link['Facebook']) ?></li>
                            <li><strong>Instagram Link:</strong> <?= htmlspecialchars($link['Instagram']) ?></li>
                            <li><strong>paypal Link:</strong> <?= htmlspecialchars($link['paypal']) ?></li>
                            <li><strong>Linkedin Link:</strong> <?= htmlspecialchars($link['Linkedin']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No social media links for the user.</p>
                <?php endif; ?>
            </div>
            <div class="user-info-container">
                <h2>Subscriptions</h2>
                <?php if (!empty($subscriptions)): ?>
                    <ul>
                        <?php foreach ($subscriptions as $subscription): ?>
                            <li>
                                <strong>Subscription Plan:</strong> <?= htmlspecialchars($subscription['name']) ?> <br>
                                <strong>Status:</strong> <?= $subscription['status'] == 1 ? 'Active' : 'Inactive' ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No subscriptions for the user.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        function toggleMenu() {
            var sidebar = document.getElementById('sidebar');
            sidebar.style.left = sidebar.style.left === '-250px' ? '0px' : '-250px';
            var content = document.querySelector('.content');
            content.style.marginLeft = content.style.marginLeft === '0px' ? '250px' : '0px';
        }
    </script>
</body>
</html>
