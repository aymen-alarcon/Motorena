<?php
  session_start();
  include 'header.php';
  if (!isset($_SESSION['UserID'])) {
      header("Location: login.php");
      exit();
  }

  $userID = $_SESSION['UserID'];
  $username = $_SESSION['name'];

  $sql = "SELECT u.*, ui.Adresse, ui.phone, ui.Adresse, ui.CodePostal, ui.Gender, ui.Bio, ui.birthday, ui.profile_picture, sml.Facebook, sml.Instagram, sml.paypal, sml.LinkedIn 
          FROM utilisateur u 
          LEFT JOIN utilisateurinfo ui ON u.UserID = ui.UserID 
          LEFT JOIN social_media_links sml ON u.UserID = sml.UserID 
          WHERE u.UserID = :UserID";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':UserID', $userID);
  if (!$stmt->execute()) {
      die("Error executing query: " . $stmt->errorInfo()[2]);
  }
  $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

  $subscriptionQuery = "SELECT * FROM user_subscriptions WHERE UserID = :UserID";
  $subscriptionStmt = $pdo->prepare($subscriptionQuery);
  $subscriptionStmt->bindParam(':UserID', $userID, PDO::PARAM_INT);
  $subscriptionStmt->execute();
  $subscriptionInfo = $subscriptionStmt->fetch(PDO::FETCH_ASSOC);

  $commandQuery = "SELECT c.*, p.NomProduit AS product_name, u.username AS username
                  FROM command c
                  LEFT JOIN produit p ON c.ProductID = p.ProductID
                  LEFT JOIN utilisateur u ON c.UserID = u.UserID
                  WHERE c.UserID = :userID";
  $commandStmt = $pdo->prepare($commandQuery);
  $commandStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
  $commandStmt->execute();
  $command = $commandStmt->fetchAll(PDO::FETCH_ASSOC);

  $subscriptionQuery = "SELECT us.*, sp.name AS plan_name , sp.price AS plan_price
                        FROM user_subscriptions us
                        JOIN subscription_plans sp ON us.planid = sp.PlanID
                        WHERE us.UserID = :UserID";
  $subscriptionStmt = $pdo->prepare($subscriptionQuery);
  $subscriptionStmt->bindParam(':UserID', $userID, PDO::PARAM_INT);
  $subscriptionStmt->execute();
  $subscriptionInfo = $subscriptionStmt->fetch(PDO::FETCH_ASSOC);

  if ($subscriptionInfo['end_date'] < date('Y-m-d')) {
    $subscriptionInfo['status'] = 0;
  } else {
    $subscriptionInfo['status'] = 1;
  }

  $endDate = strtotime($subscriptionInfo['end_date']);
  $currentDate = time();
  $remainingTime = $endDate - $currentDate;
?>

<style>
  .all {
    padding: 3rem;
  }

  .section-link.active {
    background-color: #0056b3;
  }

  .section {
    display: none;
    margin-top: 20px;
  }

  .section.active {
    display: block;
  }

  .sidebar {
    position: sticky;
    top: 20px;
  }

  .form-section {
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 30px;
  }

  .sidebar a.nav-link {
    color: #000000;
  }

  .modal-content {
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
  }

  .modal-header {
    background-color: #f8f9fa;
    border-bottom: none;
  }

  .modal-title {
    color: #343a40;
  }

  .modal-body {
    padding: 1.5rem;
  }

  .modal-footer {
    border-top: none;
  }

  .btn-danger {
    background-color: #dc3545;
    color: #fff;
    border: none;
  }
  
  .btn-danger:hover {
    background-color: #c82333;
  }

  #subscriptionInfo{
    background-color: #fff;
    border: 0.3px solid;
    border-radius: 10px;
    padding: 2rem;
  }

  .button {
    all: unset;
    display: flex;
    align-items: center;
    position: relative;
    padding: 0.6em 2em;
    border: mediumspringgreen solid 1px;
    border-radius: 0.25em;
    font-size: 1em;
    font-weight: 600;
    cursor: pointer;
    overflow: hidden;
    transition: border 300ms, color 300ms;
    user-select: none;
  }

  .button span {
    z-index: 1;
    opacity: 1;
    position: relative;
    color: #000;
  }

  .button:hover {
    color: #212121;
  }

  .button:active {
    border-color: teal;
  }

  .button::after, .button::before {
    content: "";
    position: absolute;
    width: 9em;
    aspect-ratio: 1;
    background: mediumspringgreen;
    opacity: 50%;
    color: #000;
    border-radius: 50%;
    transition: transform 500ms, background 300ms;
  }

  .button::before {
    left: 0;
    transform: translateX(-8em);
  }

  .button::after {
    right: 0;
    transform: translateX(8em);
  }

  .button:hover:before {
    transform: translateX(-1em);
  }

  .button:hover:after {
    transform: translateX(1em);
  }

  .button:active:before,
  .button:active:after {
    background: teal;
  }

  @media screen and (max-width: 768px) {
    .all {
      padding: 3rem 0.5rem;
    }
  }
</style>

<div class="all" style="background-color: #f9f9f9; min-height: 25.6rem;">
  <h1><?php echo $translations['User Profile Settings']; ?></h1>
  <center>
    <div class="radio-inputs">
      <label class="radio">
        <input type="radio" name="radio" checked="" onclick="showSection('updateProfile')">
        <span class="name"><?php echo $translations['Update Profile']; ?></span>
      </label>
      <label class="radio">
        <input type="radio" name="radio" onclick="showSection('deleteProfile')">
        <span class="name"><?php echo $translations['Delete Profile']; ?></span>
      </label>
      <label class="radio">
        <input type="radio" name="radio" onclick="showSection('subscriptionInfo')">
        <span class="name"><?php echo $translations['subscription_info']; ?></span>
      </label>
    </div>
  </center>
  <!-- Subscription Info Section -->
  <div class="section" id="subscriptionInfo">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2 class="subscription-heading"><?php echo $translations['subscription_info']; ?></h2>
          <?php if ($subscriptionInfo): ?>
            <div class="row subscription-details">
              <div class="col-md-6">
                <p><?php echo $translations['subscription_plan']; ?>: <?php echo $subscriptionInfo['plan_name']; ?></p>
                <p><?php echo $translations['subscription_status']; ?>: <?php echo $subscriptionInfo['status'] == 1 ? 'Active' : 'Inactive'; ?></p>
              </div>
              <div class="col-md-6">
                <p><?php echo $translations['subscription_price']; ?>: <?php echo $subscriptionInfo['plan_price']; ?></p>
                <p><?php echo $translations['end_date']; ?>: <?php echo date('Y-m-d', $endDate); ?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 d-flex justify-content-center">
                <button class="button"><span><?php echo $translations['cancel_sub']; ?></span></button>
              </div>
              <div class="col-md-6 d-flex justify-content-center">
                <button class="button" onclick="window.location.href='manage-subscription.php';"><span><?php echo $translations['manage_sub']; ?></span></button>
              </div>
            </div>
          <?php else: ?>
            <p><?php echo $translations['no_active_subscription']; ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <!-- Update Profile Section -->
  <div class="section" id="updateProfile">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <div class="sidebar">
            <ul class="nav ms-0 me-5">
              <li class="nav-item"><a class="nav-link section-link" href="#personal" data-section="personal"><?php echo $translations['Personal']; ?></a></li>
              <li class="nav-item"><a class="nav-link section-link" href="#address" data-section="address"><?php echo $translations['Address']; ?></a></li>
              <li class="nav-item"><a class="nav-link section-link" href="#links" data-section="links"><?php echo $translations['Links']; ?></a></li>
              <li class="nav-item"><a class="nav-link section-link" href="#profile-picture" data-section="profile-picture"><?php echo $translations['Profile Picture']; ?></a></li>
            </ul>
          </div>
        </div>
        <div class="col-md-9">
          <form action="update-profile.php" method="post" enctype="multipart/form-data">
            <h1><?php echo $translations['welcome']; ?> <?php echo $username; ?> <?php echo $translations['to_dashboard']; ?></h1>
            <input type="hidden" name="UserID" value="<?php echo $userID; ?>">
            <!-- Personal -->
            <div id="personal" class="form-section">
              <h2><?php echo $translations['Personal']; ?></h2>
              <div class="form-group mb-2">
                <label for="username"><?php echo $translations['Username']; ?></label>
                <input class="form-control" type="text" name="username" value="<?php echo $userInfo['username']; ?>" required>
              </div>
              <div class="form-group mb-2">
                <label for="phone"><?php echo $translations['phone']; ?></label>
                <input class="form-control" type="text" name="phone" value="<?php echo $userInfo['phone']; ?>" required>
              </div>
              <div class="form-group mb-2">
                <label for="Gender"><?php echo $translations['gender']; ?></label>
                <input class="form-control" type="text" name="Gender" value="<?php echo $userInfo['Gender']; ?>">
              </div>
              <div class="form-group mb-2">
                <label for="birthday"><?php echo $translations['birthday']; ?></label>
                <input class="form-control" type="date" max="2005-12-31" name="birthday" value="<?php echo $userInfo['birthday']; ?>">
              </div>
              <div class="form-group mb-2">
                <label for="Bio"><?php echo $translations['bio']; ?></label>
                <input class="form-control" type="text" name="Bio" value="<?php echo $userInfo['Bio']; ?>">
              </div>
            </div>
            <!-- Address -->
            <div id="address" class="form-section">
              <h2><?php echo $translations['Address']; ?></h2>
              <div class="form-group mb-2">
                <label for="Adresse"><?php echo $translations['address']; ?></label>
                <input class="form-control" type="text" name="Adresse" value="<?php echo $userInfo['Adresse']; ?>" required>
              </div>
              <div class="form-group mb-2">
                <label for="CodePostal"><?php echo $translations['postal_code']; ?></label>
                <input class="form-control" type="text" name="CodePostal" value="<?php echo $userInfo['CodePostal']; ?>" required>
              </div>
            </div>
            <!-- Links -->
            <div id="links" class="form-section">
              <h2><?php echo $translations['Links']; ?></h2>
              <div class="form-group mb-2">
                <label for="Facebook">Facebook</label>
                <input class="form-control" type="text" name="Facebook" value="<?php echo $userInfo['Facebook']; ?>">
              </div>
              <div class="form-group mb-2">
                <label for="X">X</label>
                <input class="form-control" type="text" name="paypal" value="<?php echo $userInfo['paypal']; ?>">
              </div>
              <div class="form-group mb-2">
                <label for="Instagram">Instagram</label>
                <input class="form-control" type="text" name="Instagram" value="<?php echo $userInfo['Instagram']; ?>">
              </div>
              <div class="form-group mb-2">
                <label for="Linkedin">Linkedin</label>
                <input class="form-control" type="text" name="Linkedin" value="<?php echo $userInfo['LinkedIn']; ?>">
              </div>
            </div>
            <!-- Profile Picture -->
            <div id="profile-picture" class="form-section">
              <h2><?php echo $translations['Profile Picture']; ?></h2>
              <div class="form-group mb-2">
                <label for="profile_picture"><?php echo $translations['profile_photo']; ?></label>
                <?php if ($userInfo && !empty($userInfo['profile_picture'])): ?>
                  <img src="<?php echo $userInfo['profile_picture']; ?>" alt="Profile Picture" style="width: 150px; height: 150px;">
                <?php else: ?>
                  <p>No picture available</p>
                <?php endif; ?>
                <input class="form-control" type="file" name="profile_picture">
              </div>
            </div>
            <input class="btn btn-primary mt-3" type="submit" name="update_profile" value="<?php echo $translations['update_profile']; ?>">
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Delete Profile Section -->
  <div class="section" id="deleteProfile">
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-md-6 my-5">
        <b><?php echo $translations['stop_message']; ?></b>
      </div>
    </div>
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-md-6">
        <center>
          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"><?php echo $translations['Delete Profile']; ?></button>
        </center>
      </div>
    </div>
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteModalLabel"><?php echo $translations['Confirm Profile Deletion']; ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><?php echo $translations['Are you sure you want to delete your profile?']; ?></p>
            <form id="deleteProfileForm" method="POST" action="delete-profile.php">
              <input type="password" name="password" class="form-control" placeholder="<?php echo $translations['Enter your password']; ?>">
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $translations['cancel']; ?></button>
                <button type="submit" name="confirm_delete" class="btn btn-danger"><?php echo $translations['confirm']; ?></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include('footer.php'); ?>
<script>
  function showSection(sectionId) {
    var sections = document.getElementsByClassName('section');
    for (var i = 0; i < sections.length; i++) {
      sections[i].classList.remove('active');
    }

    var links = document.getElementsByClassName('section-link');
    for (var i = 0; i < links.length; i++) {
      links[i].classList.remove('active');
    }

    document.getElementById(sectionId).classList.add('active');

    var activeLink = document.querySelector('.section-link[data-section="' + sectionId + '"]');
    activeLink.classList.add('active');
  }

  showSection('updateProfile');
</script>