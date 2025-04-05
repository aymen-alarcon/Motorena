<?php
session_start();
include 'db.php';

// Retrieve the buyer's email address from the form
$buyer_email = isset($_POST['email']) ? $_POST['email'] : '';

// PayPal settings
$paypal_email = 'alarcon442002@gmail.com'; // Replace with your actual PayPal email
$return_url = 'http://localhost/payment_success.php';
$cancel_url = 'http://localhost/payment_cancel.php';
$notify_url = 'http://localhost/ipn_listener.php';

$item_name = 'Test Item';
$item_amount = isset($_POST['amount']) ? $_POST['amount'] : 5.00;

// Construct PayPal query string
$querystring = '';

$querystring .= "?business=" . urlencode($paypal_email) . "&";
$querystring .= "item_name=" . urlencode($item_name) . "&";
$querystring .= "amount=" . urlencode($item_amount) . "&";

// Loop for posted values and append to querystring (if needed)
// Here, we are only redirecting to PayPal, so no need to include posted values

// Append PayPal return addresses
$querystring .= "return=" . urlencode(stripslashes($return_url)) . "&";
$querystring .= "cancel_return=" . urlencode(stripslashes($cancel_url)) . "&";
$querystring .= "notify_url=" . urlencode($notify_url);

// Redirect to PayPal IPN
header('location:https://www.sandbox.paypal.com/cgi-bin/webscr' . $querystring);
exit();
?>
