<?php
session_start();
include 'header-admin.php';

if (isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {

    $allPartnersQuery = "SELECT * FROM partners WHERE is_deleted = 0";
    $allPartnersStmt = $pdo->query($allPartnersQuery);
    $allPartnersStmt->execute();
    $allPartners = $allPartnersStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="row">
        <div class="col-md-12 p-4">
            <h3>Partners</h3>
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th>Partner ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date Added</th>
                        <th class="action-icons">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allPartners as $partner): ?>
                        <tr>
                            <td><?= $partner['partner_id'] ?></td>
                            <td><?= $partner['brand_name'] ?></td>
                            <td><?= $partner['email'] ?></td>
                            <td><?= $partner['date_timestamp'] ?></td>
                            <td class="action-icons">
                                <a href="edit-partner.php?partner_id=<?= $partner['partner_id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete-partner.php?partner_id=<?= $partner['partner_id'] ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartnerModal" style="float: right;">
                        Add Partner
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal fade" id="addPartnerModal" tabindex="-1" aria-labelledby="addPartnerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPartnerModalLabel">Add a Partner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add-new-partner-table.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="brand_name" class="form-label">Partner Name</label>
                            <input type="text" class="form-control" name="brand_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="website_link" class="form-label">Website URL</label>
                            <input type="text" class="form-control" name="website_link" required>
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" name="logo" required>
                        </div>
                        <input type="submit" class="form-control">
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
    } else {
        header("Location: login.php");
        exit;
    }
?>