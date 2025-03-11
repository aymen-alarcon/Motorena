<?php
    session_start();
    include 'header.php';

    $userID = $_SESSION['UserID'];
    $subscription_sql = "SELECT PlanID FROM user_subscriptions WHERE UserID = :UserID";
    $subscription_stmt = $pdo->prepare($subscription_sql);
    $subscription_stmt->bindParam(':UserID', $userID);
    $subscription_stmt->execute();
    $subscription = $subscription_stmt->fetch(PDO::FETCH_ASSOC);

    $PlanID = isset($subscription['PlanID']) ? $subscription['PlanID'] : null;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['productType'])) {
        $_SESSION['productType'] = $_POST['productType'];
    }
?>
<div class="main-products-page pt-5">
    <h1 class="head-products-page pb-4"><?php echo __('add_product'); ?></h1>
    <div class="form">
        <form class="form-product" action="new-product.php" method="post" enctype="multipart/form-data">
            <label class="m-2" for="name"><?php echo __('name'); ?></label>
            <input class="form-control" type="text" id="name" name="NomProduit" required>

            <label class="m-2" for="price"><?php echo __('price'); ?></label>
            <input class="form-control" type="number"  min="0" id="price" name="Prix" required>

            <label class="m-2" for="info"><?php echo __('information'); ?></label>
            <textarea class="form-control" id="info" name="Description" required></textarea>

            <label class="m-2" for="moroccan-cities"><?php echo __('choose_city'); ?></label>
            <select class="form-control" id="moroccan-cities" name="moroccan-cities" required>
                <option value="" disabled selected><?php echo __('choose_city'); ?></option>
                <?php
                    if (!empty($cities)) {
                        foreach($cities as $city) {
                            echo '<option value='.$city['CityID'].'>'.$city['city_name'].'</option>';
                        }
                    } else {
                        echo "<option value=''>No cities available</option>";
                    }
                ?>
            </select>           
            <label class="m-2" for="productType"><?php echo __('select_product_type'); ?></label>
            <select class="form-control" id="productType" name="productType" required>
                <?php
                    if (!empty($categories)) {
                        foreach ($categories as $category) {
                            echo '<option value="' . $category['CategoryID'] . '">' . $category['NomCategorie'] . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>' . __('no_categories_found') . '</option>';
                    }
                ?>
            </select>
            <div id="additionalFields" style="display: none;">
                <label class="m-2" for="kilométrage"><?php echo __('kilometerage'); ?></label>
                <input class="form-control" type="number"  min="0" name="kilometrage">

                <label class="m-2" for="année"><?php echo __('year'); ?></label>
                <input class="form-control" type="date" max="2024-12-31" name="annee">

                <label class="m-2" for="puissance_fiscale"><?php echo __('fiscal_power'); ?></label>
                <input class="form-control" type="number"  min="0" name="puissance_fiscale">

                <div class="d-flex p-2">
                    <label class="m-2" for="couleur"><?php echo __('color'); ?></label>
                    <input class="form-control" type="color" name="couleur">
                </div>

                <label class="m-2" for="vehicule_dedouane"><?php echo __('vehicule_dedouane'); ?></label>
                <input class="form-control" type="date" max="2024-12-31" name="vehicule_dedouane">
            </div>
            <div id="add_field" class="ordering-options">
                <label><?php echo __('Ordering_Options'); ?></label>
                <div class="d-flex">
                    <label>
                        <?php echo __('enable_ordering'); ?>
                        <input type="radio" name="command_statu" value="1">
                    </label>
                    <label>
                        <?php echo __('Disable_Ordering'); ?>
                        <input type="radio" name="command_statu" value="0" checked>
                    </label>
                </div>
            </div>
            <div id="quantityField" class=" p-2" style="display: none;">
                <label class="m-2" for="quantity"><?php echo __('quantity'); ?></label>
                <input class="form-control" type="number"  min="0" name="quantity">
            </div>
            <label class="m-2" for="not_main"><?php echo __('additional_images'); ?></label>
            <input class="form-control" type="file" name="not_main[]" accept="image/*" multiple>
            <label class="m-2" for="is_main"><?php echo __('image'); ?></label>
            <input class="form-control" type="file" name="is_main" accept="image/*" required>
            
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="commissionAgreement" name="commissionAgreement" required>
                <label class="form-check-label" for="commissionAgreement" style="font-size: 10px;">
                    <?php echo __('I agree to the commission percentage agreed upon in my subscription plan'); ?>
                </label>
            </div>
            <input class="form-control mt-3" type="submit" value="<?php echo __('submit'); ?>">
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
<script>
    var productTypeSelect = document.getElementById('productType');
    var additionalFields = document.getElementById('additionalFields');
    var quantityField = document.getElementById('quantityField');
    var quantityInput = document.querySelector('input[name="quantity"]');
    var planId = <?php echo $PlanID; ?>;

    function toggleAdditionalFieldsVisibility() {
        additionalFields.style.display = (productTypeSelect.value === '1') ? 'block' : 'none';
    }

    function toggleQuantityFieldVisibility() {
        if (productTypeSelect.value === '2' || productTypeSelect.value === '3') {
            quantityField.style.display = 'block';
            if (planId === 1) {
                quantityInput.max = 10;
            } else if (planId === 2) {
                quantityInput.max = 20;
            } else if (planId === 3) {
                quantityInput.max = 50;
            } else {
                quantityInput.removeAttribute('max');
            }
        } else {
            quantityField.style.display = 'none';
        }
    }

    // Call visibility functions initially and when productType changes
    productTypeSelect.addEventListener('change', function() {
        toggleAdditionalFieldsVisibility();
        toggleQuantityFieldVisibility();
    });

    // Ensure initial state matches the productType value on page load
    document.addEventListener("DOMContentLoaded", function() {
        toggleAdditionalFieldsVisibility(); // Adjust visibility on load
        toggleQuantityFieldVisibility(); // Adjust quantity field visibility
    });
</script>
