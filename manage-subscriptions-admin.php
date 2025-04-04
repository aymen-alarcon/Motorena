<?php
    session_start();
    include 'header-admin.php';

    if (isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {
        $allSubscriptionPlansQuery = "SELECT * FROM subscription_plans";
        $allSubscriptionPlansStmt = $pdo->query($allSubscriptionPlansQuery);
        $allSubscriptionPlansStmt->execute();
        $allSubscriptionPlans = $allSubscriptionPlansStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .row {
        display: flex;
        align-items: center;
    }

    .modal-backdrop{
        display: none;
    }
</style>

<div class="content">
    <div class="row">
        <div class="col-md-12 p-4">
            <h3>Subscription Plans</h3>
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th>Plan ID</th>
                        <th>Name</th>
                        <th>Max Quantity per Product</th>
                        <th>Price</th>
                        <th>Access to Premium Features</th>
                        <th>Verification Mark</th>
                        <th class="action-icons">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allSubscriptionPlans as $plan): ?>
                        <tr>
                            <td><?= $plan['PlanID'] ?></td>
                            <td><?= $plan['name'] ?></td>
                            <td><?= $plan['max_quantity_per_product'] ?></td>
                            <td><?= $plan['price'] ?></td>
                            <td><?= $plan['access_to_premium_features'] ? 'Yes' : 'No' ?></td>
                            <td><?= $plan['verification_mark'] ? 'Yes' : 'No' ?></td>
                            <td class="action-icons">
                                <a href="edit-subscription-plan.php?PlanID=<?= $plan['PlanID'] ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete-subscription-plan.php?PlanID=<?= $plan['PlanID'] ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubscriptionPlanModal" style="float: right;">
                        Add Subscription Plan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal fade" id="addSubscriptionPlanModal" tabindex="-1" aria-labelledby="addSubscriptionPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubscriptionPlanModalLabel">Add a Subscription Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add-subscription-plan.php" method="POST">
                        <div class="mb-3">
                            <label for="plan_name" class="form-label">Plan Name</label>
                            <input type="text" class="form-control" id="plan_name" name="plan_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="plan_name_fr" class="form-label">Plan Name (French)</label>
                            <input type="text" class="form-control" id="plan_name_fr" name="plan_name_fr" required>
                        </div>
                        <div class="mb-3">
                            <label for="plan_name_ar" class="form-label">Plan Name (Arabic)</label>
                            <input type="text" class="form-control" id="plan_name_ar" name="plan_name_ar" required>
                        </div>
                        <div class="mb-3">
                            <label for="max_quantity_per_product" class="form-label">Max Quantity per Product</label>
                            <input type="text" class="form-control" id="max_quantity_per_product" name="max_quantity_per_product">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="access_to_premium_features" class="form-label">Access to Premium Features</label>
                            <input type="checkbox" class="form-check-input" id="access_to_premium_features" name="access_to_premium_features">
                        </div>
                        <div class="mb-3">
                            <label for="verification_mark" class="form-label">Verification Mark</label>
                            <input type="checkbox" class="form-check-input" id="verification_mark" name="verification_mark">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Subscription Plan</button>
                    </form>
                </div>
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