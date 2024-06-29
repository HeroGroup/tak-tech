<?php

$verify_url = "https://api.zarinpal.com/pg/v4/payment/verify.json";

$amount = $_GET['amount'];
$order_id = $_GET['order_id'];
$reason = $_GET['reason'];
$_authority = $_GET['Authority'];
$status = $_GET['Status'];

if ($reason == "renew") {
  $base_redirect = "https://meionite.eu/renew/payResult?order_id=$order_id&status=$status";
} else if ($reason == "wallet") {
  $base_redirect = "https://meionite.eu/wallet/payResult?order_id=$order_id&status=$status";
} else {
  $base_redirect = "https://meionite.eu/payResult?order_id=$order_id&status=$status";
}

if ($status == "NOK") {
  header("Location: $base_redirect&message=پرداخت ناموفق");
  return;
}

$data = array(
  "merchant_id" => "a2a9f941-c1d2-4563-a791-2e1d2ed4699a", 
  "authority" => $_authority, 
  "amount" => $amount,
);

$jsonData = json_encode($data);
$ch = curl_init($verify_url);
curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-Type: application/json',
  'Content-Length: ' . strlen($jsonData)
));

$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result, true);
if ($err) {
  echo "cURL Error #:" . $err;
} else {
  if ($result['data']['code'] == 100) {
    $ref_id = $result['data']['ref_id'];
    header("Location: $base_redirect&ref_id=$ref_id");
  } else {
    $message = $result['errors']['message'];
    header("Location: $base_redirect&message=$message");
  }
  die();
}

?>
