<?php
    session_start();
    include 'header-admin.php';
    include 'db.php';

    if (!isset($_SESSION['UserID'])) {
        header("Location: login.php");
        exit();
    }

    $userID = $_SESSION['UserID'];

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 5;
    $start = ($page - 1) * $limit;

    $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'NotificationID';
    $sortOrder = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';
    $allowedColumns = ['NotificationID', 'type', 'UserID', 'EntityID', 'Message', 'DateAdded'];
    if (!in_array($sortColumn, $allowedColumns)) {
        $sortColumn = 'NotificationID';
    }

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = $_GET['search'];
        $notificationsQuery = "SELECT * FROM Notifications WHERE NotificationID = :searchTerm LIMIT $start, $limit";
        $notificationsStmt = $pdo->prepare($notificationsQuery);
        $notificationsStmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_INT);
        $notificationsStmt->execute();
        $notifications = $notificationsStmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $notificationsQuery = "SELECT * FROM Notifications ORDER BY $sortColumn $sortOrder LIMIT $start, $limit";
        $notificationsStmt = $pdo->prepare($notificationsQuery);
        $notificationsStmt->execute();
        $notifications = $notificationsStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $totalNotificationsQuery = "SELECT COUNT(*) AS total FROM Notifications";
    $totalNotificationsStmt = $pdo->query($totalNotificationsQuery);
    $totalNotifications = $totalNotificationsStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalNotifications / $limit);

    $prevPage = $page > 1 ? $page - 1 : 1;
    $nextPage = $page < $totalPages ? $page + 1 : $totalPages;
?>
<div class="content">
    <div class="glassmorphism">
        <h3>All Notifications</h3>
        <div class="row">
            <p class="col-md-8 d-flex align-items-center">Total Notifications: <?= $totalNotifications ?></p>
            <div class="col-md-4 search-container">
                <form action="" method="GET">
                    <input type="text" placeholder="Search by Notification ID" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th><a href="?sort=NotificationID&order=<?= $sortColumn === 'NotificationID' && $sortOrder === 'ASC' ? 'desc' : 'asc' ?>">Notification ID</a></th>
                    <th><a href="?sort=type&order=<?= $sortColumn === 'type' && $sortOrder === 'ASC' ? 'desc' : 'asc' ?>">Type</a></th>
                    <th><a href="?sort=UserID&order=<?= $sortColumn === 'UserID' && $sortOrder === 'ASC' ? 'desc' : 'asc' ?>">User ID</a></th>
                    <th><a href="?sort=EntityID&order=<?= $sortColumn === 'EntityID' && $sortOrder === 'ASC' ? 'desc' : 'asc' ?>">Entity ID</a></th>
                    <th><a href="?sort=Message&order=<?= $sortColumn === 'Message' && $sortOrder === 'ASC' ? 'desc' : 'asc' ?>">Message</a></th>
                    <th><a href="?sort=DateAdded&order=<?= $sortColumn === 'DateAdded' && $sortOrder === 'ASC' ? 'desc' : 'asc' ?>">Date Added</a></th>
                    <th class="th-action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notifications as $notification): ?>
                    <tr>
                        <td class="<?= is_null($notification['admin_response']) ? 'no-response-row' : 'response-row' ?>"></td>
                        <td><?= htmlspecialchars($notification['NotificationID']) ?></td>
                        <td><?= htmlspecialchars($notification['Type']) ?></td>
                        <td><a href="account-info-admin.php?userID=<?= htmlspecialchars($notification['UserID']) ?>"><?= htmlspecialchars($notification['UserID']) ?></a></td>
                        <td>
                            <?php if ($notification['Type'] === 'report'): ?>
                                <a href="product-details-admin.php?productID=<?= htmlspecialchars($notification['EntityID']) ?>"><?= htmlspecialchars($notification['EntityID']) ?></a>
                            <?php else: ?>
                                <a href="account-info-admin.php?userID=<?= htmlspecialchars($notification['EntityID']) ?>"><?= htmlspecialchars($notification['EntityID']) ?></a>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($notification['Message']) ?></td>
                        <td><?= htmlspecialchars($notification['DateAdded']) ?></td>
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
                                <a href="respond-notification-admin.php?notificationID=<?= htmlspecialchars($notification['NotificationID']) ?>">
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var checkboxes = document.querySelectorAll('.switch');

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var notificationID = this.dataset.notificationId;
                var updateForm = document.getElementById('updateForm' + notificationID);

                updateForm.querySelector('input[name="updateType"]').value = this.checked ? 'checked' : 'unchecked';
                updateForm.submit();
            });
        });
    });
</script>
</body>
</html>