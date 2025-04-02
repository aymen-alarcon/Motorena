<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['verification_code'])) {
    header("Location: forgot-password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userVerificationCode = trim($_POST['verification_code']);
    $sessionVerificationCode = trim($_SESSION['verification_code']);

    if ($userVerificationCode === $sessionVerificationCode) {
        header("Location: reset_password.php");
        exit();
    } else {
        $error = "Verification code is incorrect. Input: $userVerificationCode, Session: $sessionVerificationCode";
    }
}

include 'header.php';
?>
    <div class="main-products-page pt-5">
        <h1 class="head-products-page pb-4 "><?php echo __('Enter_Verification_Code') ?></h1>
        <div class="form">
            <form class="form-product" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label><?php echo __('An_email_containing') ?> <?php echo $_SESSION['email']; ?><br><?php echo __('Please enter the code below') ?> </label>
                <input class="form-control my-3 " type="text" name="verification_code" placeholder="<?php echo __('Enter_Code_here') ?>" required>
                <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
                <input class="form-control" type="submit" value="<?php echo __('submit'); ?>">
            </form>
        </div>
    </div>
<?php include 'footer.php'; ?>