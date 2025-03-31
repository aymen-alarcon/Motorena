<?php
    session_start();
    include 'header-admin.php';

    $lastFiveAccountsQuery = "SELECT u.*, ui.type 
                            FROM utilisateur AS u 
                            INNER JOIN utilisateurinfo AS ui 
                            ON u.UserID = ui.UserID 
                            ORDER BY u.DateAdded DESC 
                            LIMIT 5";

    $lastFiveAccountsStmt = $pdo->prepare($lastFiveAccountsQuery);
    $lastFiveAccountsStmt->execute();
    $lastFiveAccounts = $lastFiveAccountsStmt->fetchAll(PDO::FETCH_ASSOC);

    $lastFiveProductsQuery = "SELECT * FROM produit WHERE is_deleted = 0 ORDER BY DateAdded DESC LIMIT 5";
    $lastFiveProductsStmt = $pdo->prepare($lastFiveProductsQuery);
    $lastFiveProductsStmt->execute();
    $lastFiveProducts = $lastFiveProductsStmt->fetchAll(PDO::FETCH_ASSOC);

    $lastFiveNotificationsQuery = "SELECT * FROM notifications ORDER BY DateAdded DESC LIMIT 5";
    $lastFiveNotificationsStmt = $pdo->prepare($lastFiveNotificationsQuery);
    $lastFiveNotificationsStmt->execute();
    $lastFiveNotifications = $lastFiveNotificationsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
        <div class="content">
            <div class="glassmorphism">
                <h3>Last 5 Accounts</h3>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Type</th>
                            <th>Email</th>
                            <th>Date Added</th>
                            <th class="action-icons">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lastFiveAccounts as $account): ?>
                            <tr>
                                <td><a href="account-info-admin.php?userID=<?= $account['UserID'] ?>"><?= $account['UserID'] ?></a></td>
                                <td><a href="account-info-admin.php?userID=<?= $account['UserID'] ?>"><?= $account['username'] ?></a></td>
                                <td><a href="account-info-admin.php?userID=<?= $account['UserID'] ?>"><?= $account['type'] ?></a></td>
                                <td><a href="account-info-admin.php?userID=<?= $account['UserID'] ?>"><?= $account['email'] ?></a></td>
                                <td><?= $account['DateAdded'] ?></td>
                                <td class="action-icons">
                                    <a href="edit-user-admin.php?UserID=<?=  $account['UserID'] ?>">
                                        <i class="fas fa-edit"></i> 
                                    </a>
                                    <a href="delete-user-admin.php?UserID=<?=  $account['UserID'] ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="glassmorphism">
                <h3>Last 5 Products</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Date Added</th>
                            <th class="action-icons">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lastFiveProducts as $product): ?>
                            <tr>
                                <td><a href="product-details-admin.php?productID=<?= $product['ProductID'] ?>"><?= $product['ProductID'] ?></a></td>
                                <td><a href="product-details-admin.php?productID=<?= $product['ProductID'] ?>"><?= $product['NomProduit'] ?></a></td>
                                <td><?= $product['Prix'] ?></td>
                                <td><?= $product['DateAdded'] ?></td>
                                <td class="action-icons">
                                    <a href="edit-product-admin.php?ProductID=<?=$product['ProductID'] ?>">
                                        <i class="fas fa-edit"></i> 
                                    </a>
                                    <a href="delete-product-admin.php?ProductID=<?=$product['ProductID'] ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="glassmorphism glassmorphism-dark">
                <h3>Last 5 Notifications</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Notification ID</th>
                            <th>User ID</th>
                            <th>Date Added</th>
                            <th>Type</th>
                            <th>Entity ID</th>
                            <th>Message</th>
                            <th class="action-icons">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lastFiveNotifications as $notification): ?>
                            <tr>
                                <td><?= $notification['NotificationID'] ?></a></td>
                                <td><a href="account-info-admin.php?userID=<?= $notification['UserID'] ?>"><?= $notification['UserID'] ?></a></td>
                                <td><?= $notification['DateAdded'] ?></td>
                                <td><?= $notification['Type'] ?></td>
                                <td>
                                    <?php if ($notification['Type'] === 'report'): ?>
                                        <a href="product-details-admin.php?productID=<?= $notification['EntityID'] ?>"><?= $notification['EntityID'] ?></a>
                                    <?php else: ?>
                                        <a href="account-info-admin.php?userID=<?= $notification['EntityID'] ?>"><?= $notification['EntityID'] ?></a>
                                    <?php endif; ?>
                                </td>
                                <td><?= $notification['Message'] ?></td>
                                <td class="action-icons">
                                    <?php if ($notification['Type'] === 'request'){ ?>
                                        <form id="updateForm<?= $notification['NotificationID'] ?>" action="update-userinfo.php" method="POST">
                                            <input type="hidden" name="notificationID" value="<?= $notification['NotificationID'] ?>">
                                            <input type="hidden" name="updateType" value="unchecked">
                                            <input type="checkbox" name="updateTypeCheckbox" class="switch" data-notification-id="<?= $notification['NotificationID'] ?>" <?= !is_null($notification['admin_response']) ? 'checked' : '' ?>>
                                        </form>
                                        <a href="delete-notification-admin.php?NotificationID=<?= htmlspecialchars($notification['NotificationID']) ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php } else { ?>
                                        <a href="respond-notification-admin.php?NotificationID=<?= htmlspecialchars($notification['NotificationID']) ?>">
                                            <i class="bi bi-chat-left-text"></i>
                                        </a>
                                        <a href="delete-notification-admin.php?NotificationID=<?= htmlspecialchars($notification['NotificationID']) ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var checkboxes = document.querySelectorAll('.switch');

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var notificationID = this.dataset.notificationId;
                var updateForm = document.getElementById('updateForm' + notificationID);

                if (this.checked) {
                    if (confirm("Are you sure you want to update the type?")) {
                        updateForm.updateType.value = 'checked';
                        updateForm.submit();
                    } else {
                        this.checked = false;
                    }
                } else {
                    if (confirm("Are you sure you want to remove the update?")) {
                        updateForm.updateType.value = 'unchecked';
                        updateForm.submit();
                    } else {
                        this.checked = true;
                    }
                }
            });
        });
    });
</script>
</body>
</html>