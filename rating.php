<?php
include 'db.php';
session_start();

$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$langFile = 'lang/' . $language . '.php';
$translations = include $langFile;

function __($key) {
    global $translations;
    return isset($translations[$key]) ? $translations[$key] : $key;
}

if (!isset($_SESSION['UserID'])) {
    header("Location: index.php?error=not_logged_in&message=" . urlencode(__('not_logged_in')));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rating'])) {
    $RatedUserID = $_GET['RatedUserID'];
    $RatingUserID = $_SESSION['UserID'];

    if ($RatedUserID == $RatingUserID) {
        header("Location: profile-watch.php?UserID=$RatedUserID&error=self_rate&message=" . urlencode(__('self_rate')));
        exit();
    }

    $check_sql = "SELECT * FROM profile_ratings WHERE RatedUserID = :RatedUserID AND RatingUserID = :RatingUserID";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->bindParam(':RatedUserID', $RatedUserID, PDO::PARAM_INT);
    $check_stmt->bindParam(':RatingUserID', $RatingUserID, PDO::PARAM_INT);
    $check_stmt->execute();

    if ($check_stmt->rowCount() > 0) {
        header("Location: profile-watch.php?UserID=$RatedUserID&error=already_rated&message=" . urlencode(__('already_rated')));
        exit();
    }

    $rating = intval($_POST['rating']);
    if ($rating <= 0) {
        header("Location: profile-watch.php?UserID=$RatedUserID&error=empty_rating&message=" . urlencode(__('empty_rating')));
        exit();
    }

    $insert_sql = "INSERT INTO profile_ratings (RatedUserID, RatingUserID, rating) VALUES (:RatedUserID, :RatingUserID, :rating)";
    $insert_stmt = $pdo->prepare($insert_sql);
    $insert_stmt->bindParam(':RatedUserID', $RatedUserID, PDO::PARAM_INT);
    $insert_stmt->bindParam(':RatingUserID', $RatingUserID, PDO::PARAM_INT);
    $insert_stmt->bindParam(':rating', $rating, PDO::PARAM_INT);

    if ($insert_stmt->execute()) {
        header("Location: profile-watch.php?UserID=$RatedUserID&success=1&message=" . urlencode(__('rating_success')));
    } else {
        header("Location: profile-watch.php?UserID=$RatedUserID&error=db_error&message=" . urlencode(__('db_error')));
    }
    exit();
}
?>
