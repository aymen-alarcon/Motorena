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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : '';
    $name = isset($_POST['NomProduit']) ? $_POST['NomProduit'] : '';
    $Prix = isset($_POST['Prix']) ? $_POST['Prix'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
    $info = isset($_POST['Description']) ? $_POST['Description'] : '';
    $kilometrage = isset($_POST['kilometrage']) ? $_POST['kilometrage'] : ''; 
    $année = isset($_POST['annee']) ? $_POST['annee'] : '';
    $puissance = isset($_POST['puissance_fiscale']) ? $_POST['puissance_fiscale'] : '';
    $couleur = isset($_POST['couleur']) ? $_POST['couleur'] : '';
    $productType = isset($_POST['productType']) ? $_POST['productType'] : '';
    $vehicule = isset($_POST['vehicule_dedouane']) ? $_POST['vehicule_dedouane'] : '';
    $orderingOption = isset($_POST['command_statu']) ? $_POST['command_statu'] : '';
    $CityID = isset($_POST['moroccan-cities']) ? $_POST['moroccan-cities'] : null;

    $subscription_sql = "SELECT PlanID FROM user_subscriptions WHERE UserID = :UserID";
    $subscription_stmt = $pdo->prepare($subscription_sql);
    $subscription_stmt->bindParam(':UserID', $user_id);
    $subscription_stmt->execute();
    $subscription = $subscription_stmt->fetch(PDO::FETCH_ASSOC);
    $PlanID = $subscription['PlanID'];

    $Statu = ($PlanID == 3 || $PlanID == 4) ? 1 : 0;

    if (isset($_POST['productType']) && $_POST['productType'] == 1) {
        $sql = "INSERT INTO produit (NomProduit, Prix, Description, UserID, CategoryID, kilometrage, annee, puissance_fiscale, couleur, vehicule_dedouane, Statu, CityID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$name, $Prix, $info, $user_id, $productType, $kilometrage, $année, $puissance, $couleur, $vehicule, $Statu, $CityID];
    } else {
        $sql = "INSERT INTO produit (NomProduit, Prix, quantity, Description, UserID, CategoryID, command_statu, Statu, CityID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$name, $Prix, $quantity, $info, $user_id, $productType, $orderingOption, $Statu, $CityID];
    }

    try {
        $stmt = $pdo->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Error in statement preparation: " . $pdo->errorInfo()[2]);
        }

        for ($i = 0; $i < count($params); $i++) {
            $stmt->bindValue($i + 1, $params[$i]);
        }

        if ($stmt->execute()) {
            $product_id = $pdo->lastInsertId();

            if (isset($_FILES['is_main']) && $_FILES['is_main']['error'] === UPLOAD_ERR_OK) {
                $main_image_name = $_FILES['is_main']['name'];
                $main_image_tmp = $_FILES['is_main']['tmp_name'];
                $main_image_path = 'uploads/' . $main_image_name;
                
                if (move_uploaded_file($main_image_tmp, $main_image_path)) {
                    $stmt_main_image = $pdo->prepare("INSERT INTO ProductPictures (ProductID, image_url, is_main) VALUES (?, ?, ?)");
                    $stmt_main_image->execute([$product_id, $main_image_path, 1]);
                }
            }

            if (isset($_FILES['not_main']) && is_array($_FILES['not_main']['name'])) {
                foreach ($_FILES['not_main']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['not_main']['error'][$key] === UPLOAD_ERR_OK) {
                        $image_name = $_FILES['not_main']['name'][$key];
                        $image_tmp = $_FILES['not_main']['tmp_name'][$key];
                        $image_path = 'uploads/' . $image_name;
                        
                        if (move_uploaded_file($image_tmp, $image_path)) {
                            $is_main = 0;
                            $stmt_additional_image = $pdo->prepare("INSERT INTO ProductPictures (ProductID, image_url) VALUES (?, ?)");
                            $stmt_additional_image->execute([$product_id, $image_path]);
                        }
                    }
                }
            }

            header("Location: index.php?message=" . urlencode(__('success')) . "&success=1");
            exit();
        } else {
            throw new Exception("Error in executing insert query: " . $stmt->errorInfo()[2]);
        }
    } catch (Exception $e) {
        header("Location: index.php?message=" . urlencode(__('err') . $e->getMessage()) . "&success=0");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>