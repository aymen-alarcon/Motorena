<?php
    session_start();
    include 'header.php';

    try {
        $stmt = $pdo->query("SELECT * FROM subscription_plans");
        $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
?>
<body>
    <div class="p-4 d-flex justify-content-space-between align-items-center" style="--bs-gutter-x: 0rem;">
        <?php foreach ($plans as $plan) : ?>
            <form method="POST" action="subscribe.php">
                <input type="hidden" name="PlanID" value="<?php echo htmlspecialchars($plan['PlanID']); ?>">
                <input type="hidden" name="plan_name" value="<?php 
                    if ($language == 'ar') {
                        echo htmlspecialchars($plan['name_ar']);
                    } elseif ($language == 'fr') {
                        echo htmlspecialchars($plan['name_fr']);
                    } else {
                        echo htmlspecialchars($plan['name']);
                    }
                ?>">
                <input type="hidden" name="plan_price" value="<?php echo htmlspecialchars($plan['price']); ?>">
                
                <div class="pack-container">
                    <div class="header">
                        <p class="title">
                            <?php 
                                if ($language == 'ar') {
                                    echo htmlspecialchars($plan['name_ar']);
                                } elseif ($language == 'fr') {
                                    echo htmlspecialchars($plan['name_fr']);
                                } else {
                                    echo htmlspecialchars($plan['name']);
                                }
                            ?>                
                        </p>
                        <div class="price-container p-4">
                            <span>DH</span><?php echo htmlspecialchars($plan['price']); ?>
                            <span>/mo</span>
                        </div>
                    </div>
                    <div>
                        <ul class="lists">
                            <li class="list">
                                <span>
                                    <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.5 12.75l6 6 9-13.5" stroke-linejoin="round" stroke-linecap="round"></path>
                                    </svg>
                                </span>
                                <p><?php echo __('max_quantity_per_product'); ?> <?php echo htmlspecialchars($plan['max_quantity_per_product']); ?></p>
                            </li>
                            <li class="list">
                                <span>
                                    <?php if ($plan['verification_mark'] == 1) : ?>
                                        <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.5 12.75l6 6 9-13.5" stroke-linejoin="round" stroke-linecap="round"></path>
                                        </svg>
                                    <?php else : ?>
                                        <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.75 4.75l14.5 14.5m0-14.5L4.75 19.25" stroke-linejoin="round" stroke-linecap="round"></path>
                                        </svg>
                                    <?php endif; ?>
                                </span>
                                <p><?php echo __('verification_mark'); ?></p>
                            </li>
                            <li class="list">
                                <span>
                                    <?php if ($plan['access_to_premium_features'] == 1) : ?>
                                        <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.5 12.75l6 6 9-13.5" stroke-linejoin="round" stroke-linecap="round"></path>
                                        </svg>
                                    <?php else : ?>
                                        <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.75 4.75l14.5 14.5m0-14.5L4.75 19.25" stroke-linejoin="round" stroke-linecap="round"></path>
                                        </svg>
                                    <?php endif; ?>
                                </span>
                                <p><?php echo __('access_to_premium_features'); ?></p>
                            </li>
                        </ul>
                    </div>
                    <div class="button-container">
                        <button type="submit"><?php echo __('choose_plan'); ?></button>
                    </div>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
