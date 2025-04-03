<?php
session_start();
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

function generateVerificationCode() {
    return rand(100000, 999999);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();                                    
            $mail->Host       = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth   = true;
            $mail->Username   = '08e1d15b97d811';               
            $mail->Password   = 'ad358f5ee75e46';               
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 2525;

            $mail->setFrom($email, 'Sender Name');
            $mail->addAddress($email);

            $verificationCode = generateVerificationCode();
            $_SESSION['verification_code'] = $verificationCode;
            $_SESSION['email'] = $email;

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Verification Code';   
            $mail->Body    = 'Your verification code is: ' . $verificationCode; 

            $mail->send();

                header("Location: enter_verification_code.php?UserID=" . $user['UserID']);
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo $translations['email_not_found'];
    }
}

include 'header.php';
?>
    <div class="main-products-page pt-5">
        <h1 class="head-products-page pb-4 "><?php echo $translations['forgot_password']; ?></h1>
        <div class="form">
            <form class="form-product" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="email"><?php echo $translations['send_verification_code']; ?></label><br>
                <input class="form-control" type="email" id="email" name="email" placeholder="<?php echo $translations['email_placeholder']; ?>" required><br><br>
                <input class="form-control" type="submit" value="<?php echo __('submit'); ?>">
            </form>
        </div>
    </div>
<?php include 'footer.php';?>
