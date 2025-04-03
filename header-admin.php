<?php include 'db.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/wwal.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style-admin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://unpkg.com/akar-icons-fonts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <title>Admin Dashboard</title>
</head>
<body>
    <div>
        <header class="row">
            <div class="col-md-2" id="burger-menu" onclick="toggleMenu()">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
            <h1 class="col-md-9">Admin Dashboard</h1>
        </header>
        <nav class="sidebar" id="sidebar">
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Back To Site</a></li>
                <li><a href="Admin-dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage-users-admin.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="manage-products-admin.php"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="manage-notifications-admin.php"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="manage-orders-admin.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="manage-categories-admin.php"><i class="fas fa-list"></i> Categories</a></li>
                <li><a href="manage-partners-admin.php"><i class="fa fa-handshake-o" aria-hidden="true"></i> Partners</a></li>
                <li><a href="manage-cities-admin.php"><i class="fa fa-map-pin" aria-hidden="true"></i> Cities</a></li>                
                <li><a href="manage-subscriptions-admin.php"><i class="fa fa-credit-card" aria-hidden="true"></i> Subscriptions</a></li>
            </ul>
        </nav>
<script>
    window.addEventListener('scroll', function() {
        var header = document.querySelector('header');
        var sidebar = document.getElementById('sidebar');

        if (window.scrollY > 0) {
            header.style.position = 'fixed';
            header.style.top = '0';
            sidebar.style.marginTop = '5rem';
        } else {
            header.style.position = 'sticky';
            header.style.top = 'auto';
            sidebar.style.marginTop = '0';
        }
    });

    function toggleMenu() {
        var sidebar = document.getElementById('sidebar');
        sidebar.style.left = sidebar.style.left === '0px' ? '-250px' : '0px';
        var content = document.querySelector('.content');
        content.style.marginLeft = content.style.marginLeft === '250px' ? '0px' : '250px';
    }
</script>