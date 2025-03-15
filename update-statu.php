<?php
session_start();
include 'db.php';
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['status']) && $_POST['status'] == '1') {
        $sql = "UPDATE your_table_name SET status = 1 WHERE id = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        echo "Status updated successfully";
    } else {
        echo "Status checkbox was not checked";
    }
}
?>