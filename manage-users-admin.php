<?php
    session_start();

    $userID = $_SESSION['UserID'];
    $username = $_SESSION['name'];

    include 'header-admin.php';

    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 10;
    $start = ($page - 1) * $limit;

    if(isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = $_GET['search'];
        $AccountsQuery = "SELECT * FROM utilisateur WHERE UserID = :searchTerm LIMIT $start, $limit";
        $AccountsStmt = $pdo->prepare($AccountsQuery);
        $AccountsStmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_INT);
        $AccountsStmt->execute();
        $Accounts = $AccountsStmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $AccountsQuery = "SELECT * FROM utilisateur LIMIT $start, $limit";
        $AccountsStmt = $pdo->query($AccountsQuery);
        $Accounts = $AccountsStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    $totalAccountsQuery = "SELECT COUNT(*) AS total FROM utilisateur";
    $totalAccountsStmt = $pdo->query($totalAccountsQuery);
    $totalAccounts = $totalAccountsStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalAccounts / $limit);

    $prevPage = $page > 1 ? $page - 1 : 1;
    $nextPage = $page < $totalPages ? $page + 1 : $totalPages;
?>
    <div class="content">
        <div class="glassmorphism">
        <h3>All Accounts</h3>
            <div class="row">
                <p class="col-md-8 d-flex align-items-center">Total Accounts: <?= $totalAccounts ?></p>
                <div class="col-md-4 search-container">
                    <form action="" method="GET">
                        <input type="text" placeholder="Search by User ID" name="search">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Date Added</th>
                            <th class="th-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($Accounts as $account): ?>
                            <tr>
                                <td><a href="account-info-admin.php?userID=<?= $account['UserID'] ?>"><?= $account['UserID'] ?></a></td>
                                <td><a href="account-info-admin.php?userID=<?= $account['UserID'] ?>"><?= $account['username'] ?></a></td>
                                <td><a href="account-info-admin.php?userID=<?= $account['UserID'] ?>"><?= $account['email'] ?></a></td>
                                <td><a href="account-info-admin.php?userID=<?= $account['UserID'] ?>"><?= $account['DateAdded'] ?></a></td>
                                <td class="action-icons">
                                    <a href="edit-user-admin.php?UserID=<?= $account['UserID'] ?>">
                                        <i class="fas fa-edit"></i> 
                                    </a>
                                    <a href="delete-user-admin.php?UserID=<?= $account['UserID'] ?>">
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
    </div>
</body>
</html>
