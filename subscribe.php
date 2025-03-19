<?php
    session_start();
    include 'db.php';

    $language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
    $langFile = 'lang/' . $language . '.php';
    $translations = include $langFile;

    function __($key) {
        global $translations;
        return isset($translations[$key]) ? $translations[$key] : $key;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_SESSION['UserID']) && isset($_POST['PlanID'])) {
            $userId = $_SESSION['UserID'];
            $planId = $_POST['PlanID'];
            $startDate = time();
            $endDate = strtotime('+1 month', $startDate); 

            try {
                $checkStmt = $pdo->prepare("SELECT * FROM user_subscriptions WHERE UserID = :UserID");
                $checkStmt->bindParam(':UserID', $userId, PDO::PARAM_INT);
                $checkStmt->execute();

                if ($checkStmt->rowCount() > 0) {
                    header("Location: index.php?message=" . urlencode(__('alr_sub')) . "&success=1");
                } else {
                    $stmt = $pdo->prepare("INSERT INTO user_subscriptions (UserID, PlanID, start_date, end_date) VALUES (:UserID, :PlanID, :start_date, :end_date)");
                    $stmt->bindParam(':UserID', $userId, PDO::PARAM_INT);
                    $stmt->bindParam(':PlanID', $planId, PDO::PARAM_INT);
                    $stmt->bindParam(':start_date', date('Y-m-d H:i:s', $startDate));
                    $stmt->bindParam(':end_date', date('Y-m-d H:i:s', $endDate));

                    if ($stmt->execute()) {
                        $updateStmt = $pdo->prepare("UPDATE utilisateurinfo SET subscribed = 1, type = 2 WHERE UserID = :UserID");
                        $updateStmt->bindParam(':UserID', $userId, PDO::PARAM_INT);

                        if ($updateStmt->execute()) {
                            $notificationStmt = $pdo->prepare("UPDATE notifications SET admin_response = :response WHERE UserID = :UserID");
                            $welcomeMessage = 'Welcome aboard our new seller!';
                            $notificationStmt->bindParam(':response', $welcomeMessage, PDO::PARAM_STR);
                            $notificationStmt->bindParam(':UserID', $userId, PDO::PARAM_INT);

                            if ($notificationStmt->execute()) {
                                header("Location: index.php?message=" . urlencode(__('sub_suc')) . "&success=1");
                                exit();
                            } else {
                                header("Location: index.php?message=" . urlencode(__('sub_suc_ad_f')) . "&success=0");
                            }
                        } else {
                            header("Location: index.php?message=" . urlencode(__('sub_suc_fail')) . "&error=1");
                        }
                    } else {
                        header("Location: index.php?message=" . urlencode(__('sub_fail')) . "&error=1");
                    }
                }
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
        } else {
            echo 'Invalid request. Please make sure you are logged in and a plan is selected.';
        }
    } else {
        echo 'Invalid request method.';
    }
?>