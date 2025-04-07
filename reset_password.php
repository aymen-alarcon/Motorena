<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['verification_code'])) {
    header("Location: forgot-password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['new_password'];

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);


    $email = $_SESSION['email'];
    $sql = "UPDATE utilisateur SET password = ? WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$hashedPassword, $email])) {
        $successMessage = "Your password has been reset successfully. Please log in with your new password.";
        header("Location: login.php");
        unset($_SESSION['email']);
        unset($_SESSION['verification_code']);
    } else {
        $errorMessage = "Failed to reset password. Please try again.";
    }
}
include 'header.php';
?>
<div class="main-products-page pt-5">
    <h1 class="head-products-page pb-4 "></h1>
    <div class="form">
        <form class="form-product" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="Enter_new_password"><?php echo __('Enter_new_password'); ?></label><br/>
            <input class="form-control my-3 " type="password" name="new_password" id="new_password" placeholder="<?php echo __('Enter_new_password') ?>" required>
            <label for="Confirm_new_password"><?php echo __('Confirm_new_password'); ?></label>
            <input class="form-control my-3" type="password" name="confirm_password" id="confirm_password" placeholder="<?php echo __('Confirm_new_password') ?>" required>
            <input class="form-control" type="submit" value="<?php echo __('submit'); ?>" id="submitBtn">
        </form>
        <?php
        if (isset($successMessage)) {
            echo "<p style='color: green;'>$successMessage</p>";
        } elseif (isset($errorMessage)) {
            echo "<p style='color: red;'>$errorMessage</p>";
        }
        ?>
    </div>
</div>

<script>
    document.getElementById('submitBtn').addEventListener('click', function(event) {
        var newPassword = document.getElementById('new_password').value;
        var confirmPassword = document.getElementById('confirm_password').value;
        if (newPassword !== confirmPassword) {
            alert('Passwords do not match');
            event.preventDefault();
        }
    });
</script>
<?php include 'footer.php'; ?>