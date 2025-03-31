<?php
    session_start();
    include 'db.php';
    include 'header.php';

    $UserID = $_SESSION['UserID'];
?> 
<style>
    .button {
        display: inline-block;
        border-radius: 4px;
        background-color: #3d405b;
        border: none;
        color: #FFFFFF;
        text-align: center;
        font-size: 17px;
        padding: 16px;
        min-width: 10rem;
        transition: all 0.5s;
        cursor: pointer;
        margin: 5px;
        white-space: nowrap;
    }

    .button a {
        cursor: pointer;
        display: inline-block;
        position: relative;
        transition: 0.5s;
        color: #fff;
    }

    .button a:after {
        content: '»';
        position: absolute;
        opacity: 0;
        top: 0;
        right: -15px;
        transition: 0.5s;
    }

    .button:hover a {
        padding-right: 15px;
    }

    .button:hover a:after {
        opacity: 1;
        right: 0;
    }

    h1{
        display: none;
    }

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

    @media screen and (max-width: 768px) {
        .all {
            padding: 3rem 0.5rem;
        }

        h1{
            display: block;
        }

        .main-head{
            display: none;
        }

        .radio-inputs {
            display: none;
        }

        .section {
            display: block;
        }
    }

    body {
        font-family: 'Roboto', sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
    }
</style>
<div class="all" style="background-color: #f9f9f9; min-height: 30.7rem;">
    <h1 class="main-head"><?php echo $translations['User Profile Settings']; ?></h1>
    <center>
      <div class="radio-inputs">
        <label class="radio">
          <input type="radio" name="radio" checked onclick="showSection('intrest')">
          <span class="name"><?php echo $translations['Intrest']; ?></span>
        </label>
        <label class="radio">
          <input type="radio" name="radio" onclick="showSection('command')">
          <span class="name"><?php echo $translations['Command']; ?></span>
        </label>
        <label class="radio">
          <input type="radio" name="radio" onclick="showSection('notifications')">
          <span class="name"><?php echo $translations['Notifications']; ?></span>
        </label>
      </div>
    </center>
    <div id="intrest" class="section active">
        <h1><?php echo $translations['Intrest']; ?></h1>
        <?php
            $stmtInterest = $pdo->prepare("SELECT pi.*, p.NomProduit 
                                            FROM product_interest pi 
                                            JOIN produit p ON pi.ProductID = p.ProductID 
                                            WHERE pi.seller_id = :UserID 
                                            AND pi.is_deleted = 0");
            $stmtInterest->execute(['UserID' => $UserID]);
            $interests = $stmtInterest->fetchAll(PDO::FETCH_ASSOC);

            if (count($interests) > 0) {
                foreach ($interests as $interest) {
                    echo "
                            <div class='interest' id='intrest'>
                                <a href='clear_notification.php?id={$interest['id']}' class='clear-notification' style='float: right;'>&times;</a>
                                <p><strong>" . __('buyer_name') . "</strong> " . htmlspecialchars($interest["buyer_name"]) . "</p>
                                <p><strong>" . __('buyer_phone') . "</strong> " . htmlspecialchars($interest["buyer_phone"]) . "</p>
                                <p><strong>" . __('p_name') . "</strong> " . htmlspecialchars($interest["NomProduit"]) . "</p>
                            </div>
                        ";
                }
            } else {
                echo "<p>No product interests found for you</p>";
            }
        ?>
    </div>
    <div id="command" class="section">
        <h1><?php echo $translations['Command']; ?></h1>
        <?php
            try {
                $stmtCommand = $pdo->prepare("SELECT command.*, produit.NomProduit FROM command LEFT JOIN produit ON command.ProductID = produit.ProductID WHERE produit.UserID = :UserID AND command.is_deleted = 0 AND command.command_statu = 0");
                $stmtCommand->execute(['UserID' => $UserID]);
                $commands = $stmtCommand->fetchAll(PDO::FETCH_ASSOC);

                if (count($commands) > 0) {
                    foreach ($commands as $command) {
                        echo "
                                <div class='command'>
                                    <a href='clear_notification.php?id={$command['CommandID']}' class='clear-notification' style='float: right;'>&times;</a>
                                    <div class='row'>
                                        <div class='col-md-6'>
                                            <p><strong>" . __('name') . "</strong> " . htmlspecialchars($command["name"]) . "</p>
                                            <p><strong>" . __('phone_number') . " :</strong> " . htmlspecialchars($command["phone_number"]) . "</p>
                                            <p><strong>" . __('address') . " :</strong> " . htmlspecialchars($command["address"]) . "</p>
                                            <p><strong>" . __('quantity') . " :</strong> " . htmlspecialchars($command["quantity"]) . "</p>
                                            <p><strong>" . __('Total_Price') . ":</strong> " . htmlspecialchars($command["total_price"]) . "</p>
                                            <p><strong>" . __('p_name') . "</strong> " . htmlspecialchars($command["NomProduit"]) . "</p>
                                        </div>
                                        <div class='col-md-6' style='display: flex; justify-content: flex-end; align-items: flex-end;'>
                                            <button class='button'>
                                                <a class='command-display' style='color: white;' href='command_details.php?CommandID={$command['CommandID']}' class='command-link'>" . __('f_info') . "</a>                                  
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ";
                    }
                } else {
                    echo "<p>No commands found for you</p>";
                }
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        ?>
    </div>
    <div id="notifications" class="section">
        <h1><?php echo $translations['Notifications']; ?></h1>
        <?php
            try {
                $stmt = $pdo->prepare("SELECT * FROM notifications WHERE UserID = :UserID AND is_deleted = 0");
                $stmt->execute(['UserID' => $UserID]);
                $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($notifications) > 0) {
                    foreach ($notifications as $notification) {
                        echo "
                                <div class='notification'>
                                    <a href='clear_notification.php?NotificationID={$notification['NotificationID']}' class='clear-notification' style='float: right;'>&times;</a>
                                    <p><strong>" . __('message') . ":</strong> " . htmlspecialchars($notification["Message"]) . "</p>
                                    <p><strong>" . __('admin_response') . "</strong> " . htmlspecialchars($notification["admin_response"]) . "</p>
                                </div>
                            ";
                    }
                } else {
                    echo "<p>No notifications found for you</p>";
                }
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        ?>
    </div>
</div>
<?php include 'footer.php';?>
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

    showSection('intrest');
  </script>