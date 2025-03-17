<?php
session_start();
if (isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {
    include 'db.php';
    include 'header-admin.php';

    if (isset($_GET['PlanID'])) {
        $planID = $_GET['PlanID'];

        $fetchPlanQuery = "SELECT * FROM subscription_plans WHERE PlanID = ?";
        $fetchPlanStmt = $pdo->prepare($fetchPlanQuery);
        $fetchPlanStmt->execute([$planID]);
        $plan = $fetchPlanStmt->fetch(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $plan_name = $_POST['plan_name'];
            $plan_name_fr = $_POST['plan_name_fr'];
            $plan_name_ar = $_POST['plan_name_ar'];
            $max_quantity_per_product = $_POST['max_quantity_per_product'];
            $price = $_POST['price'];
            $access_to_premium_features = isset($_POST['access_to_premium_features']) ? 1 : 0;
            $verification_mark = isset($_POST['verification_mark']) ? 1 : 0;

            $updatePlanQuery = "UPDATE subscription_plans 
                                SET name = ?, name_fr = ?, name_ar = ?, max_quantity_per_product = ?, price = ?, access_to_premium_features = ?, verification_mark = ?
                                WHERE PlanID = ?";
            $updatePlanStmt = $pdo->prepare($updatePlanQuery);
            $updatePlanStmt->execute([$plan_name, $plan_name_fr, $plan_name_ar, $max_quantity_per_product, $price, $access_to_premium_features, $verification_mark, $planID]);

            header("Location: manage-settings-admin.php");
            exit;
        }
    } else {
        header("Location: manage-settings-admin.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>
<style>
    .container.bg-white.m-5.p-4{
        border-radius: 10px;
    }
</style>
<body>
    <div class="container bg-white m-5 p-4">
        <h2>Edit Subscription Plan</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="plan_name" class="form-label">Plan Name</label>
                <input type="text" class="form-control" id="plan_name" name="plan_name" value="<?= $plan['name'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="plan_name_fr" class="form-label">Plan Name (French)</label>
                <input type="text" class="form-control" id="plan_name_fr" name="plan_name_fr" value="<?= $plan['name_fr'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="plan_name_ar" class="form-label">Plan Name (Arabic)</label>
                <input type="text" class="form-control" id="plan_name_ar" name="plan_name_ar" value="<?= $plan['name_ar'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="max_quantity_per_product" class="form-label">Max Quantity per Product</label>
                <input type="text" class="form-control" id="max_quantity_per_product" name="max_quantity_per_product" value="<?= $plan['max_quantity_per_product'] ?>">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" name="price" value="<?= $plan['price'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="access_to_premium_features" class="form-label">Access to Premium Features</label>
                <input type="checkbox" class="form-check-input" id="access_to_premium_features" name="access_to_premium_features" <?= $plan['access_to_premium_features'] ? 'checked' : '' ?>>
            </div>
            <div class="mb-3">
                <label for="verification_mark" class="form-label">Verification Mark</label>
                <input type="checkbox" class="form-check-input" id="verification_mark" name="verification_mark" <?= $plan['verification_mark'] ? 'checked' : '' ?>>
            </div>
            <button type="submit" class="btn btn-primary">Update Plan</button>
        </form>
    </div>
</body>
</html>
