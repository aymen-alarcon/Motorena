<?php
session_start();

$userID = $_SESSION['UserID'];
$username = $_SESSION['name'];

include 'header-admin.php';
require_once 'db.php';

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;
$start = ($page - 1) * $limit;

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $commandsQuery = "SELECT * FROM command WHERE CommandID = :searchTerm LIMIT $start, $limit";
    $commandsStmt = $pdo->prepare($commandsQuery);
    $commandsStmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_INT);
    $commandsStmt->execute();
    $commands = $commandsStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $commandsQuery = "SELECT * FROM command LIMIT $start, $limit";
    $commandsStmt = $pdo->query($commandsQuery);
    $commands = $commandsStmt->fetchAll(PDO::FETCH_ASSOC);
}

$totalCommandsQuery = "SELECT COUNT(*) AS total FROM command";
$totalCommandsStmt = $pdo->query($totalCommandsQuery);
$totalCommands = $totalCommandsStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalCommands / $limit);

$prevPage = $page > 1 ? $page - 1 : 1;
$nextPage = $page < $totalPages ? $page + 1 : $totalPages;
?>
<div class="content">
    <div class="glassmorphism">
        <h3>All Orders</h3>
        <div class="row">
            <p class="col-md-8 d-flex align-items-center">Total Orders: <?= $totalCommands ?></p>
            <div class="col-md-4 search-container">
                <form action="" method="GET">
                    <input type="text" placeholder="Search by Command ID" name="search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Command ID</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Product ID</th>
                    <th>User ID</th>
                    <th>Date Created</th>
                    <th class="th-action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commands as $command): ?>
                    <tr>
                        <td><?= htmlspecialchars($command['CommandID']) ?></td>
                        <td><?= htmlspecialchars($command['name']) ?></td>
                        <td><?= htmlspecialchars($command['phone_number']) ?></td>
                        <td><?= htmlspecialchars($command['address']) ?></td>
                        <td><?= htmlspecialchars($command['quantity']) ?></td>
                        <td><?= htmlspecialchars($command['total_price']) ?></td>
                        <td><?= htmlspecialchars($command['ProductID']) ?></td>
                        <td><?= htmlspecialchars($command['UserID']) ?></td>
                        <td><?= htmlspecialchars($command['created_at']) ?></td>
                        <td class="action-icons">
                            <a href="edit-command-admin.php?CommandID=<?= $command['CommandID'] ?>">
                                <i class="fas fa-edit"></i> 
                            </a>
                            <a href="delete-command-admin.php?CommandID=<?= $command['CommandID'] ?>">
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
