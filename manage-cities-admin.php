<?php
session_start();
include 'header-admin.php';

if (isset($_SESSION['UserID']) && $_SESSION['type'] == '3') {
    $allcitiesQuery = "SELECT * FROM cities";
    $allcitiesStmt = $pdo->query($allcitiesQuery);
    $allcitiesStmt->execute();
    $allcities = $allcitiesStmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="row">
        <div class="col-md-12 p-4">
            <h3>Cities</h3>
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th>City ID</th>
                        <th>City Name</th>
                        <th class="action-icons">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allcities as $city): ?>
                        <tr>
                            <td><?= $city['CityID'] ?></td>
                            <td><?= $city['CityName'] ?></td>
                            <td class="action-icons">
                                <a href="edit-city.php?CityID=<?= $city['CityID'] ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete-city.php?CityID=<?= $city['CityID'] ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCityModal" style="float: right;">
                        Add City
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal fade" id="addCityModal" tabindex="-1" aria-labelledby="addCityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCityModalLabel">Add a City</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add-city.php" method="POST">
                        <div class="mb-3">
                            <label for="city_name" class="form-label">City Name</label>
                            <input type="text" class="form-control" id="city_name" name="city_name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add City</button>
                    </form>
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