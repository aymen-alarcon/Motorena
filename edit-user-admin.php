<?php
session_start();
include 'header-admin.php';

if(isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {
    if(isset($_GET['UserID'])) {
        $userID = $_GET['UserID'];

        $sql = "SELECT * FROM utilisateur WHERE UserID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userID]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user) {
            ?>
            <div class="main-products-page p-4 mt-4">
                <h1 class="head-products-page pb-4 ">Edit User</h1>
                <div class="form">
                    <form class="form-product" action="update-user.php" method="POST">
                        <input type="hidden" name="UserID" value="<?php echo $user['UserID']; ?>">
                        <center>
                            <div class="profile-picture">
                                <?php
                                    $profilePictureQuery = "SELECT profile_picture FROM utilisateurinfo WHERE UserID = ?";
                                    $profilePictureStmt = $pdo->prepare($profilePictureQuery);
                                    $profilePictureStmt->execute([$userID]);
                                    $profilePicture = $profilePictureStmt->fetch(PDO::FETCH_ASSOC);
                                    if ($profilePicture && !empty($profilePicture['profile_picture'])) {
                                        echo '<img src="' . $profilePicture['profile_picture'] . '" alt="Profile Picture" style="width: 150px; height: 150px; border-radius: 50%;">';
                                    } else {
                                        echo 'No picture available';
                                    }
                                ?>
                            </div>
                        </center>
                        <label class="m-2" for="name">Username</label>
                        <input class="form-control" type="text" name="username" value="<?php echo $user['username']; ?>"><br><br>
                        <label class="m-2" for="name">E-Mail</label>
                        <input class="form-control" type="text" name="email" value="<?php echo $user['email']; ?>"><br><br>
                        <input class="form-control mt-3" type="submit">
                    </form>
                </div>
            </div>
            <?php
        } else {
            echo 'User not found';
        }
    } else {
        echo 'User ID not set';
    }
} else {
    header("Location: login.php");
    exit;
}
?>
