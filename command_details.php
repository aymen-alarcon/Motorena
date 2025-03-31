<?php
session_start();
include 'db.php';
include 'header.php';

$CommandID = $_GET['CommandID'];

$stmtCommand = $pdo->prepare("SELECT command.*, produit.NomProduit, produit.UserID as SellerID FROM command LEFT JOIN produit ON command.ProductID = produit.ProductID WHERE command.CommandID = :CommandID");
$stmtCommand->execute(['CommandID' => $CommandID]);
$command = $stmtCommand->fetch(PDO::FETCH_ASSOC);

if ($command) {
    $stmtSeller = $pdo->prepare("SELECT ui.*, u.email FROM utilisateurinfo ui LEFT JOIN utilisateur u ON ui.UserID = u.UserID WHERE ui.UserID = :UserID");
    $stmtSeller->execute(['UserID' => $command['SellerID']]);
    $seller = $stmtSeller->fetch(PDO::FETCH_ASSOC);

    echo "<div class='bg-light py-4'>
            <div class='receipt'>
                <h2>Receipt</h2>
                <div class='receipt-section'>
                    <h3>Seller Information</h3>
                    <p><strong>Name:</strong> " . htmlspecialchars($seller["fullname"]) . "</p>
                    <p><strong>Email:</strong> " . htmlspecialchars($seller["email"]) . "</p>
                    <p><strong>Email:</strong> " . htmlspecialchars($seller["phone"]) . "</p>
                </div>
                <hr>
                <div class='receipt-section'>
                    <h3>Buyer Information</h3>
                    <p><strong>Name:</strong> " . htmlspecialchars($command["name"]) . "</p>
                    <p><strong>Phone Number:</strong> " . htmlspecialchars($command["phone_number"]) . "</p>
                    <p><strong>Address:</strong> " . htmlspecialchars($command["address"]) . "</p>
                </div>
                <hr>
                <div class='receipt-section'>
                    <h3>Product Information</h3>
                    <p><strong>Product Name:</strong> " . htmlspecialchars($command["NomProduit"]) . "</p>
                    <p><strong>Quantity:</strong> " . htmlspecialchars($command["quantity"]) . "</p>
                    <p><strong>Total Price:</strong> $" . htmlspecialchars($command["total_price"]) . "</p>
                </div>
                <form method='POST' action='mark_as_sold.php'>
                    <input type='hidden' name='CommandID' value='{$command['CommandID']}'>
                    <input type='hidden' name='ProductID' value='{$command['ProductID']}'>
                    <button type='submit' class='btn btn-success'>Mark as Delivered/Sold</button>
                </form>
            </div>
         </div>
        ";
} else {
    echo "<p>Command not found</p>";
}

include 'footer.php';
?>
<style>
    .bg-light{
        display:flex;
        justify-content: center;
        align-items: center;
    }

    .btn-success {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        text-transform: uppercase;
    }

    .btn-success:hover {
        background-color: #218838;
    }
</style>