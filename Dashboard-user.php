<?php
session_start();
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$langFile = 'lang/' . $language . '.php';
$translations = include $langFile;

function __($key) {
    global $translations;
    return isset($translations[$key]) ? $translations[$key] : $key;
}

if (isset($_POST['language'])) {
    $_SESSION['language'] = $_POST['language'];
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

include "db.php";

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['UserID'];
$username = $_SESSION['username'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>MOTORENA</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo-off.jpg">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style-index.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .body {
            background: -webkit-gradient(linear, left bottom, right top, from(#fc2c77), to(#6c4079));
            background: -webkit-linear-gradient(bottom left, #fc2c77 0%, #6c4079 100%);
            background: -moz-linear-gradient(bottom left, #fc2c77 0%, #6c4079 100%);
            background: -o-linear-gradient(bottom left, #fc2c77 0%, #6c4079 100%);
            background: linear-gradient(to top right, #fc2c77 0%, #6c4079 100%);
        }
    </style>
</head>
<body>
    <!-- Start Top Nav -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="nav_top">
        <div class="container text-light">
            <div class="w-100 d-flex justify-content-between">
                <div class="header">
                    <i class="fa fa-envelope mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none">alarcon442002@gmail.com</a>
                    <i class="fa fa-phone mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none">+212 629 474 030</a>
                </div>
                <div>
                    <a class="text-light" href="https://www.facebook.com/profile.php?id=100034950181394" target="_blank" rel="sponsored"><i class="fab fa-facebook-f fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.instagram.com/alar_con44/" target="_blank"><i class="fab fa-instagram fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://twitter.com/alarcon_44" target="_blank"><i class="fab fa-twitter fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.linkedin.com/in/aymen-oumaalla-676b46268/" target="_blank"><i class="fab fa-linkedin fa-sm fa-fw"></i></a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Close Top Nav -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container-fluid" style=" display: flex; align-items: center; justify-content: center;">
            <center>
                <a class="navbar-brand text-success logo h1 align-self-center  mx-4" href="index.php">
                    <img src="assets/img/logo.png" alt="Logo"/>
                </a>
            </center>
        </div>
    </nav>
    <div class="body">
        <div class="p-5">
            <div class="wrapper wrapper--w680">
                <div class="card card-4">
                    <div class="card-body">
                        <h2 class="title"><?php echo __('registration_form'); ?></h2>
                        <form action="insert-dashboard.php" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3 ">
                                        <label class="label"><?php echo __('full_name'); ?></label>
                                        <input class="input--style-4" type="text" name="Fullname" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3 ">
                                        <label class="label"><?php echo __('phone_number'); ?></label>
                                        <input class="input--style-4" type="text" name="phone" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3 ">
                                        <label class="label"><?php echo __('birthday'); ?></label>
                                        <input class="input--style-4" type="date" name="birthday" max="2005-12-31">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5><?php echo __('gender'); ?></h5>
                                    <div class="row">
                                        <div class="col-md-2" style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
                                            <label class="radio-container"><?php echo __('male'); ?></label>
                                            <input type="radio" name="gender" value="Male">
                                        </div>
                                        <div class="col-md-2"style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
                                            <label class="radio-container"><?php echo __('female'); ?></label>
                                            <input type="radio" name="gender" value="Female">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <label class="label"><?php echo __('address'); ?></label>
                                        <input class="input--style-4" type="text" name="Adresse" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3 ">
                                        <label class="label"><?php echo __('postal_code'); ?></label>
                                        <input class="input--style-4" type="text" name="CodePostal" required>
                                    </div>
                                </div>
                            </div>
                            <div class="m-3" id="profile_type">
                                <center>
                                    <select class="input--style-4 p-3" name="type">
                                        <option disabled="disabled" selected="selected"><?php echo __('choose_type'); ?></option>
                                        <option value="1"><?php echo __('buyer'); ?></option>
                                        <option value="2"><?php echo __('seller'); ?></option>
                                        <option value="3"><?php echo __('admin'); ?></option>
                                    </select>
                                    <div class="select-dropdown"></div>
                                </center>
                            </div>
                            <div id="links_row">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3 ">
                                            <label class="label">Facebook</label>
                                            <input class="input--style-4" type="text" name="Facebook">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group mb-3 ">
                                            <label class="label">Paypal</label>
                                            <input class="input--style-4" type="email" name="Paypal_account" placeholder="Enter your email not link">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3 ">
                                            <label class="label">Instagram</label>
                                            <input class="input--style-4" type="text" name="Instagram">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group mb-3 ">
                                            <label class="label">linkedin</label>
                                            <input class="input--style-4" type="text" name="linkedin">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3 ">
                                        <label class="label">Bio</label>
                                        <input class="input--style-4" type="text" name="Bio">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="m-2" for="profile_picture"><?php echo __('profile_photo'); ?></label>
                                    <input class="form-control" type="file" name="profile_picture" required>
                                </div>
                            </div>
                            <div class="p-t-15">
                                <input class="form-control mt-3" type="submit" value="<?php echo __('update_profile'); ?>">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var productTypeSelect = document.querySelector('select[name="type"]');
        var additionalFields = document.getElementById('links_row');

        function toggleAdditionalFieldsVisibility() {
            additionalFields.style.display = (productTypeSelect.value === '2') ? 'block' : 'none';
        }

        productTypeSelect.addEventListener('change', toggleAdditionalFieldsVisibility);

        toggleAdditionalFieldsVisibility();
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>

