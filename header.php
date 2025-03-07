<?php
   include 'db.php';

   $language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
   $langFile = 'lang/' . $language . '.php';
   $translations = include $langFile;

   function __($key) {
      global $translations;
      return isset($translations[$key]) ? $translations[$key] : $key;
   }

   if (isset($_POST['language'])) {
      $_SESSION['language'] = $_POST['language'];
      $redirectUrl = $_SERVER['PHP_SELF'];
      
      if (isset($_GET['UserID'])) {
         $redirectUrl .= '?UserID=' . $_GET['UserID'];
      }

      if (isset($_GET['ProductID'])) {
         $redirectUrl .= '?ProductID=' . $_GET['ProductID'];
      }

      if (isset($_GET['category'])) {
         $redirectUrl .= '?category=' . $_GET['category'];
      }

      header("Location: $redirectUrl");
      exit();
   }

   $favorites = [];
   $profileImageUrl = 'path/to/default/avatar.jpg';

   if (isset($_SESSION['UserID'])) {
      $userID = $_SESSION['UserID'];

      $sql = "SELECT * FROM favorites WHERE UserID = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$userID]);
      $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $sql = "SELECT * FROM utilisateurinfo WHERE UserID = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$userID]);
      $utilisateurinfo = $stmt->fetchAll(PDO::FETCH_ASSOC);


      $sql = "SELECT profile_picture FROM utilisateurinfo WHERE UserID = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$userID]);
      $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($userInfo && !empty($userInfo['profile_picture'])) {
         $profileImageUrl = $userInfo['profile_picture'];
      }
   }

   $columnctiy = 'CityName';

   if ($language == 'ar') {
       $columnctiy = 'CityName_ar';
   } elseif ($language == 'fr') {
       $columnctiy = 'CityName_fr';
   }

   $stmtcity = $pdo->prepare ("SELECT CityID, $columnctiy AS city_name FROM cities");
   $stmtcity->execute();
   $cities = $stmtcity->fetchAll(PDO::FETCH_ASSOC);

   $columnproduit = 'NomCategorie';

   if ($language == 'ar') {
       $columnproduit = 'NomCategorie_ar';
   } elseif ($language == 'fr') {
       $columnproduit = 'NomCategorie_fr';
   }    
   
   $sql_categories = "SELECT CategoryID, $columnproduit AS NomCategorie FROM categorieproduit";
   $stmt_categories = $pdo->query($sql_categories);
   $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

   if (isset($_SESSION['UserID'])) {
      $UserID = $_SESSION['UserID'];
      $notificationCount = 0;
      $commandCount = 0;
      $interestCount = 0;

      $stmtNotifications = $pdo->prepare("SELECT COUNT(*) AS count FROM notifications WHERE UserID = :UserID AND is_deleted = 0");
      $stmtNotifications->execute(['UserID' => $UserID]);
      $notificationResult = $stmtNotifications->fetch(PDO::FETCH_ASSOC);
      if ($notificationResult) {
          $notificationCount = $notificationResult['count'];
      }

      $stmtCommands = $pdo->prepare("SELECT COUNT(*) AS count FROM command LEFT JOIN produit ON command.ProductID = produit.ProductID WHERE produit.UserID = :UserID AND command.is_deleted = 0 AND command.command_statu = 0");
      $stmtCommands->execute(['UserID' => $UserID]);
      $commandResult = $stmtCommands->fetch(PDO::FETCH_ASSOC);
      if ($commandResult) {
          $commandCount = $commandResult['count'];
      }

      $stmtInterests = $pdo->prepare("SELECT COUNT(*) AS count FROM product_interest WHERE BuyerID = :UserID AND is_deleted = 0");
      $stmtInterests->execute(['UserID' => $UserID]);
      $interestResult = $stmtInterests->fetch(PDO::FETCH_ASSOC);
      if ($interestResult) {
          $interestCount = $interestResult['count'];
      }

      $totalCount = $notificationCount + $commandCount + $interestCount;
   }
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
   <title>MOTORENA</title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="shortcut icon" type="image/x-icon" href="assets/img/wwal.png">
   <link rel="stylesheet" href="assets/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/css/style-index.css">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
   <link rel="stylesheet" href="assets/css/fontawesome.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <script src="https://unpkg.com/akar-icons-fonts"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</head>
<?php
   $message = isset($_GET['message']) ? $_GET['message'] : '';
   $success = isset($_GET['success']) ? $_GET['success'] : '';
   $error = isset($_GET['error']) ? $_GET['error'] : '';

   if ($message) {
      $alertType = $success ? 'alert-success' : 'alert-danger';
      $alertMessage = urldecode($message);
      echo'<div class="floating-alert alert ' . $alertType . ' alert-dismissible fade show mt-5" role="alert">
            ' . $alertMessage . '
         </div>
      ';
   }
?>
<body>
<style>
   .modal-content {
      background-color: #fff; 
      border-radius: 10px;
   }

   .modal-header {
      background-color: #5F4987;
      color: #fff; 
      border-bottom: none; 
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
   }

   .modal-title {
      font-size: 1.25rem;
      font-weight
   }

   .modal-body {
      padding: 20px;
   }

   .form-label {
      font-weight: bold;
   }

   .form-control {
      border-radius: 5px;
   }

   .btn-primary {
      background-color: #5F4987;
      border: none;
      border-radius: 5px;
      padding: 10px 20px;
      color: #fff;
      cursor: pointer;
   }

   .btn-primary:hover {
      background-color: #0056b3;
   }
   
   #main_nav {
      max-height: 0;
      overflow: auto;
      transition: max-height 0.3s ease-out;
   }

   #main_nav.open {
      max-height: 500px;
   }

   #navToggleIcon {
      display: block;
      cursor: pointer;
   }

   .btn {
      position: relative;
      cursor: pointer;
      transition: ease-out 0.5s;
      letter-spacing: 1px;
   }

   .btn:active {
      transform: scale(0.9);
   }

   .header-icons {
      display: flex;
      align-items: center;
      justify-content: space-between;
   }

   #main_nav.open .header-icons {
      display: flex;
   }

   @media (min-width: 768px) {
      #main_nav {
         max-height: none;
         overflow: visible;
      }
      
      .col-md-9{
         display: flex;
         align-items: center;
      }
   }

   @media (max-width: 992px) {
      #main_nav {
         display: block;
      }
      #navToggleIcon {
         display: block;
         cursor: pointer;
     }
   }
</style>
   <!-- Top Nav -->
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
   <nav class="navbar navbar-expand-lg navbar-light shadow pb-0">
      <div class="container-fluid">
         <!-- Sidebar -->
         <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="index.php"><i class="bi bi-house"></i></i> <?php echo __('home'); ?></a>
            <?php 
               if(isset($_SESSION['UserID'])) {
                  if ($_SESSION['type'] == '3') {
                     echo '<a href="admin-dashboard.php"><i class="ai-dashboard"></i> ' . __('admin_dashboard') . '</a>';
                  } else {
                     echo '<a href="profile-user.php"><i class="ai-person"></i> ' . __('profile') . '</a>';
                  }
               }
            ?>
            <?php 
               if(isset($_SESSION['UserID'])) {
                  if ($_SESSION['type'] == '2') {
                     echo '<a href="history.php"><i class="bi bi-clock-history"></i> ' . __('history') . '</a>';
                  }
               } else {
                  echo '';
               }
            ?>            
            <a id="cat_hd" onclick="onClick(this)">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop-window" viewBox="0 0 16 16">
                  <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5"/>
               </svg> 
               <?php echo __('categories'); ?>
               <i class="ai-chevron-down-small ps-2"></i>
            </a>                        
            <div class="sub-menu">
               <ul>
                  <?php foreach ($categories as $category): ?>
                     <li>
                        <a href="all-categories.php?category=<?php echo urlencode($category['NomCategorie']); ?>"><?php echo $category['NomCategorie']; ?></a>
                     </li>
                  <?php endforeach; ?>
               </ul>
            </div>
            <?php
               if(isset($_SESSION['UserID'])) {
                  if ($_SESSION['type'] == '2') {
                     echo '<a href="profile_products.php"><i class="ai-cart"></i> ' .  __('products') . '</a>';
                  }
               } else {
                  echo '';
               }
            ?>
            <?php
               if(isset($_SESSION['UserID'])) {
                  if ($_SESSION['type'] == '2') {
                     echo '<a href="favorites.php"><i class="bi bi-heart"></i> ' .  __('favorits') . '</a>';
                  }
               } else {
                  echo '';
               }
            ?>            
            <?php 
               if(isset($_SESSION['UserID'])) {
                  if ($_SESSION['type'] == '2') {
                     echo '<a href="add-new-product.php"><i class="bi bi-plus-circle"></i> ' . __('add_new_product') . '</a>';
                  } else {
                     echo '';
                  }
               }
            ?>
            <a href="about.php"><i class="bi bi-info-square"></i></i> <?php echo __('about_us'); ?></a>
            </div>
            <center class="mb-2 d-flex">
               <span class="navbar-toggler-icon" id="navToggleBtn" onclick="openNav()"></span>
               <a class="navbar-brand text-success logo h1 align-self-center  mx-4" href="index.php">
                  <img src="assets/img/logo.png" alt="Logo" />
               </a>
               <li class="nav-item dropdown dropdown-hover" style="list-style: none;">
                  <a class="btn nav-link" style="color: black; font-weight: bold;" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                     <?php echo __('categories'); ?>
                     <i class="ai-chevron-down-small ps-2"></i>
                  </a>
                  <ul class="dropdown-menu">
                     <?php foreach ($categories as $category): ?>
                        <li>
                           <a class="dropdown-item" href="all-categories.php?category=<?php echo urlencode($category['NomCategorie']); ?>"><?php echo $category['NomCategorie']; ?></a>
                        </li>
                     <?php endforeach; ?>
                  </ul>
               </li>
               <a href="about.php" class="btn nav-link" style="color: black; font-weight: bold;"> <?php echo __('about_us'); ?></a>
            </center>
            <!-- Sidebar -->
            <!-- Navbar collapse content -->
            <div id="navToggleIcon" class="d-lg-none">
               <i class="fa fa-chevron-down"></i>
            </div>
            <div id="main_nav">
               <div class="flex-fill">
                  <div class="row justify-content-end">
                     <div class="col-md-9">
                        <!-- Search bar -->
                        <form action="search.php" method="get">
                           <div class="input-group">
                              <input type="text" name="query" class="form-control" id="inputMobileSearch" placeholder="<?php echo __('search'); ?>">
                              <button type="submit" class="input-group-text" id="searchButton"><i class="fa fa-fw fa-search text-dark mr-2"></i></button>
                              <div class="input-group-text" id="advancedSearchToggle" data-bs-toggle="modal" data-bs-target="#advancedSearchModal">
                                 <i class="bi bi-funnel-fill"></i>
                              </div>
                           </div>
                        </form>
                        <div class="header-icons">
                           <!-- Language Switcher -->
                           <div class="language-switcher">
                              <form method="post" id="languageForm">
                                 <select name="language" id="languageSelect" class="p-2 mb-2 mx-4 ">
                                    <option value="en" <?php echo ($language == 'en') ? 'selected' : ''; ?>>English</option>
                                    <option value="ar" <?php echo ($language == 'ar') ? 'selected' : ''; ?>>العربية</option>
                                    <option value="fr" <?php echo ($language == 'fr') ? 'selected' : ''; ?>>français</option>
                                 </select>
                              </form>
                           </div>
                           <!-- favorites icon -->
                           <?php
                              if (isset($_SESSION['UserID'])) {
                                 echo'<a class="nav-icon position-relative text-decoration-none" href="favorites.php">
                                          <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                                          <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light">' . count($favorites) . '</span>
                                       </a>      
                                 ';
                              } else {
                                 echo '<a class="nav-icon position-relative text-decoration-none" href="javascript:void(0);" onclick="alert(\'' . __('must_login') . '\')">
                                          <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                                         <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light">0</span>
                                       </a>
                                 ';
                                }
                           ?>
                           <!-- Login log-out icon -->
                           <div class="nav-icons-container">
                              <div class="dropdown">
                                 <a class="nav-icon position-relative text-decoration-none" href="#" id="userDropdown">
                                    <?php if(isset($_SESSION['UserID'])) { 
                                       if ($userInfo && $userInfo['profile_picture']) {
                                          echo '<img src="' . $userInfo['profile_picture'] . '" class="user-profile-pic" alt="Profile Picture">';
                                       } else {
                                          echo '<i class="fa fa-fw fa-user text-dark mr-3"></i>';
                                       }
                                    } else { ?>
                                       <i class="fa fa-fw fa-user text-dark mr-3"></i>
                                    <?php } ?>
                                 </a>
                                 <div class="dropdown-menu dropdown-menu-end"  aria-labelledby="userDropdown" id="loginLogoutSection">
                                    <legend class="ms-2"><?php echo __('start_menu'); ?></legend>
                                    <?php
                                       if(isset($_SESSION['UserID'])) {
                                          if ($_SESSION['type'] == '2') {
                                             echo'<a class="dropdown-item" href="notifications.php"><i class="bi bi-bell"></i> ' .  __('notifications') . '
                                                   <span class="position-absolute left-33 translate-middle badge rounded-pill">' . $totalCount . '</span>
                                                </a>
                                             ';
                                          }
                                       } else {
                                          echo '';
                                       }
                                    ?>
                                    <?php
                                       if(isset($_SESSION['UserID'])) {
                                             echo '<a class="dropdown-item" href="profile-settings.php"><i class="bi bi-gear"></i> ' . __('settings') . '</a>';
                                       } else {
                                          echo '';
                                       }
                                    ?>
                                    <?php if(isset($_SESSION['UserID'])) { ?>
                                       <a class="dropdown-item" href="log-out.php"><i class="bi bi-box-arrow-in-right"></i> <?php echo __('log_out'); ?></a>
                                    <?php } else { ?>
                                       <a class="dropdown-item" href="login.php"><i class="bi bi-box-arrow-in-right"></i> <?php echo __('log_in_register'); ?></a>
                                    <?php } ?>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </nav>
      <!-- Close Header -->
      <div class="modal fade" id="advancedSearchModal" tabindex="-1" aria-labelledby="advancedSearchModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="advancedSearchModalLabel" style="color:white;"><?php echo __('search'); ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <form action="search.php" method="get">
                     <div class="mb-3">
                        <label for="query" class="form-label"><?php echo __('brand_name'); ?></label>
                        <input type="text" class="form-control" id="query" name="query" placeholder="<?php echo __('brand_name'); ?>">
                     </div>
                     <div class="mb-3">
                        <label for="price" class="form-label"><?php echo __('price'); ?></label>
                        <div class="input-group">
                              <span class="input-group-text">DH</span>
                              <input type="number"  min="0" class="form-control" id="price" name="price" placeholder="<?php echo __('price'); ?>">
                        </div>
                     </div>
                     <div class="mb-3">
                        <label for="seller" class="form-label"><?php echo __('seller'); ?></label>
                        <input type="text" class="form-control" id="seller" name="seller" placeholder="<?php echo __('seller'); ?>">
                     </div>
                     <div class="mb-3">
                        <label for="moroccan-cities" class="form-label"><?php echo __('choose_city'); ?></label>
                        <select class="form-control" id="moroccan-cities" name="moroccan-cities">
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
                     </div>
                     <div class="text-center mt-2">
                        <button type="submit" class="btn btn-primary"><?php echo __('search_button'); ?></button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <script>
      $(document).ready(function() {
         $('#languageSelect').change(function() {
            $('#selectedLanguage').val($(this).val());
            $('#languageForm').submit();
         });
      });

      document.addEventListener('DOMContentLoaded', function() {
         const navToggleIcon = document.getElementById('navToggleIcon');
         const mainNav = document.getElementById('main_nav');

         navToggleIcon.addEventListener('click', function() {
               if (mainNav.classList.contains('open')) {
                  mainNav.classList.remove('open');
                  setTimeout(function() {
                     mainNav.style.maxHeight = '0';
                  }, 300);
               } else {
                  mainNav.classList.add('open');
                  mainNav.style.maxHeight = mainNav.scrollHeight + 'px';
               }
         });
      });
      
      setTimeout(function() {
         var alert = document.querySelector('.floating-alert');
         alert.classList.add('fade-out');
         setTimeout(function() {
               alert.remove();
         }, 1000);
      }, 3000);

      const subMenus = document.querySelectorAll(".sub-menu"),
      buttons = document.querySelectorAll(".sidebar ul button");

      const onClick = (item) => {
      subMenus.forEach((menu) => (menu.style.height = "0px"));
      buttons.forEach((button) => button.classList.remove("active"));

      if (!item.nextElementSibling) {
         item.classList.add("active");
         return;
      }

      const subMenu = item.nextElementSibling,
         ul = subMenu.querySelector("ul");

      if (!subMenu.clientHeight) {
         subMenu.style.height = `${ul.clientHeight}px`;
         item.classList.add("active");
      } else {
         subMenu.style.height = "0px";
         item.classList.remove("active");
      }
      };

      var dropdownButton = document.getElementById('userDropdown');
      var dropdownMenu = document.getElementById('loginLogoutSection');

      document.addEventListener('click', function(event) {
         if (!dropdownMenu.contains(event.target) && !dropdownButton.contains(event.target)) {
            dropdownMenu.classList.remove('show');
         }
      });
   </script>