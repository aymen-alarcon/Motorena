<?php
session_start();
include 'header-admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET['NotificationID']) && !empty($_GET['NotificationID'])) {
        $NotificationID = $_GET['NotificationID'];
        
        if (isset($_POST['admin_response']) && !empty($_POST['admin_response'])) {
            $admin_response = $_POST['admin_response'];
            
            try {
                $stmt = $pdo->prepare("UPDATE notifications SET admin_response = :admin_response WHERE NotificationID = :NotificationID");
                $stmt->bindParam(':admin_response', $admin_response);
                $stmt->bindParam(':NotificationID', $NotificationID);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    echo '<script>alert("Admin response has been successfully added.");</script>';
                    header("Location: manage-notifications-admin.php");
                }
            } catch (PDOException $e) {
                echo "Error updating record: " . $e->getMessage();
            }
        } else {
            echo "Admin response is required.";
        }
    } else {
        echo "Notification ID is required.";
    }
}

?>
<style>        
        .container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
        }
        
        h2 {
            margin-top: 0;
            text-align: center;
        }
        
        form {
            margin-top: 20px;
        }
        
        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
            box-sizing: border-box;
        }
        
        button {
            background-color: #5F4987;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        button:hover {
            background-color: #c82333;
        }
    </style>
    <div class="container my-5">
        <h2>Admin Message Page</h2>
        <form method="post" action="">
            <input type="hidden" name="NotificationID" value="<?= isset($_GET['NotificationID']) ? $_GET['NotificationID'] : '' ?>">
            <textarea name="admin_response" placeholder="Type your response here..."></textarea>
            <button type="submit">Send Response</button>
        </form>
    </div>
</body>
</html>
