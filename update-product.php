<?php
    session_start();
    include 'db.php';

    if (isset($_POST['update_product'])) {
        $ProductID = $_POST['ProductID'];
        $NomProduit = $_POST['NomProduit'];
        $Description = $_POST['Description'];
        $Prix = $_POST['Prix'];
        $kilometrage = isset($_POST['kilometrage']) ? $_POST['kilometrage'] : '';
        $annee = isset($_POST['annee']) ? $_POST['annee'] : '';
        $puissance_fiscale = isset($_POST['puissance_fiscale']) ? $_POST['puissance_fiscale'] : '';
        $couleur = isset($_POST['couleur']) ? $_POST['couleur'] : '';
        $vehicule_dedouane = isset($_POST['vehicule_dedouane']) ? $_POST['vehicule_dedouane'] : '';
        $is_sold = isset($_POST['sold']) ? 1 : 0;

        try {
            $sql_update = "UPDATE produit SET NomProduit = :NomProduit, Description = :Description, Prix = :Prix, Kilometrage = :kilometrage, Annee = :annee, Puissance_fiscale = :puissance_fiscale, Couleur = :couleur, Vehicule_dedouane = :vehicule_dedouane, is_sold = :is_sold WHERE ProductID = :ProductID";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindParam(':NomProduit', $NomProduit);
            $stmt_update->bindParam(':Description', $Description);
            $stmt_update->bindParam(':Prix', $Prix);
            $stmt_update->bindParam(':kilometrage', $kilometrage);
            $stmt_update->bindParam(':annee', $annee);
            $stmt_update->bindParam(':puissance_fiscale', $puissance_fiscale);
            $stmt_update->bindParam(':couleur', $couleur);
            $stmt_update->bindParam(':vehicule_dedouane', $vehicule_dedouane);
            $stmt_update->bindParam(':is_sold', $is_sold);
            $stmt_update->bindParam(':ProductID', $ProductID);
            $stmt_update->execute();

            if (isset($_POST['delete_images'])) {
                foreach ($_POST['delete_images'] as $imageID) {
                    $sql_delete_image = "UPDATE ProductPictures SET is_deleted = 1 WHERE PictureID = ? ";
                    $stmt_delete_image = $pdo->prepare($sql_delete_image);
                    $stmt_delete_image->execute([$imageID]);
                }
            }

            if (isset($_FILES['product_images']) && !empty($_FILES['product_images']['name'][0])) {
                if(isset($_FILES['product_images']) && !empty($_FILES['product_images']['name'][0])) {
                    $images = $_FILES['product_images'];
                    $uploaded_images = array();
        
                    for($i = 0; $i < count($images['name']); $i++) {
                        $image_name = $images['name'][$i];
                        $image_tmp_name = $images['tmp_name'][$i];
                        $image_type = $images['type'][$i];
                        $image_size = $images['size'][$i];
                        $image_error = $images['error'][$i];
        
                        $file_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                        $extensions = array("jpeg", "jpg", "png");
        
                        if (in_array($file_ext, $extensions)) {
                            $image_new_name = uniqid('', true) . "." . $file_ext;
                            $image_destination = "uploads/" . $image_new_name;
        
                            if (move_uploaded_file($image_tmp_name, $image_destination)) {
                                $uploaded_images[] = $image_destination;
        
                                $sql_insert_image = "INSERT INTO ProductPictures (ProductID, image_url, is_deleted) VALUES (?, ?, ?)";
                                $stmt_insert_image = $pdo->prepare($sql_insert_image);
                                $stmt_insert_image->execute([$ProductID, $image_destination, 0]);
                            } else {
                                echo "Failed to upload file.";
                            }
                        } else {
                            echo "Invalid file extension.";
                        }
                    }
                }
                }

            $successMessage = urlencode('Product updated successfully.');
            header("Location: profile_products.php?success=true&message={$successMessage}");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Form submission error";
    }
?>