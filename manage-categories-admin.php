<?php
session_start();
include 'header-admin.php';

if (isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {
    $categorieProduitQuery = "SELECT * FROM categorieproduit";
    $categorieProduitStmt = $pdo->prepare($categorieProduitQuery);
    $categorieProduitStmt->execute();
    $categorieProduits = $categorieProduitStmt->fetchAll(PDO::FETCH_ASSOC);

    $categorieUserQuery = "SELECT * FROM categorieuser";
    $categorieUserStmt = $pdo->prepare($categorieUserQuery);
    $categorieUserStmt->execute();
    $categorieUsers = $categorieUserStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="row">
    <div class="col-md-6 p-2">
        <h3>Categorie Produit</h3>
        <table class="responsive-table">
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Name</th>
                    <th>Name in French</th>
                    <th>Name in Arabic</th>
                    <th class="action-icons">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorieProduits as $categorieProduit): ?>
                    <tr>
                        <td><?= $categorieProduit['CategoryID'] ?></td>
                        <td><?= $categorieProduit['NomCategorie'] ?></td>
                        <td><?= $categorieProduit['NomCategorie_fr'] ?></td>
                        <td><?= $categorieProduit['NomCategorie_ar'] ?></td>
                        <td class="action-icons">
                            <a href="edit-category-produit.php?CategoryID=<?= $categorieProduit['CategoryID'] ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete-category-produit.php?CategoryID=<?= $categorieProduit['CategoryID'] ?>">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductCategoryModal" style="float: right;">
                    Add Category
                </button>
            </div>
        </div>
    </div>
    <div class="modal modal fade" id="addProductCategoryModal" tabindex="-1" aria-labelledby="addProductCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductCategoryModalLabel">Add a Product Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add-category-produit.php" method="POST">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_name_fr" class="form-label">Category Name (French)</label>
                            <input type="text" class="form-control" id="category_name_fr" name="category_name_fr" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_name_ar" class="form-label">Category Name (Arabic)</label>
                            <input type="text" class="form-control" id="category_name_ar" name="category_name_ar" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 p-2">
        <h3>Categorie User</h3>
        <table class="responsive-table">
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Name</th>
                    <th>Name in French</th>
                    <th>Name in Arabic</th>
                    <th class="action-icons">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorieUsers as $categorieUser): ?>
                    <tr>
                        <td><?= $categorieUser['CategoryID'] ?></td>
                        <td><?= $categorieUser['NomCategorie'] ?></td>
                        <td><?= $categorieUser['NomCategorie_fr'] ?></td>
                        <td><?= $categorieUser['NomCategorie_ar'] ?></td>
                        <td class="action-icons">
                            <a href="edit-category-user.php?CategoryID=<?= $categorieUser['CategoryID'] ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete-category-user.php?CategoryID=<?= $categorieUser['CategoryID'] ?>">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserCategoryModal" style="float: right;">
                    Add Category
                </button>
            </div>
        </div>
    </div>
    <div class="modal modal fade" id="addUserCategoryModal" tabindex="-1" aria-labelledby="addUserCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserCategoryModalLabel">Add a User Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add-category-user.php" method="POST">
                        <div class="mb-3">
                            <label for="user_category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="user_category_name" name="user_category_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="user_category_name_fr" class="form-label">Category Name (French)</label>
                            <input type="text" class="form-control" id="user_category_name_fr" name="user_category_name_fr" required>
                        </div>
                        <div class="mb-3">
                            <label for="user_category_name_ar" class="form-label">Category Name (Arabic)</label>
                            <input type="text" class="form-control" id="user_category_name_ar" name="user_category_name_ar" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    } else {
        header("Location: login.php");
        exit;
    }
?>