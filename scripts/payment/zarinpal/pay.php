<?php

// https://fardahost.com/pay.php?amount=100000&description=order_id

$pay_request_url = "https://api.zarinpal.com/pg/v4/payment/request.json";
$pay_start_url = "https://www.zarinpal.com/pg/StartPay/";

$amount = $_GET['amount'];
$description = $_GET['description'];
$reason = $_GET['reason'];
$callbackUrl = $_GET['callbackUrl'] ?? "https://fardahost.com/verify.php?order_id=$description&amount=$amount&reason=$reason";
$email = $_GET['email'];
$mobile = $_GET['mobile'];

$data = array(
  "merchant_id" => "a2a9f941-c1d2-4563-a791-2e1d2ed4699a",
  "amount" => $amount,
  "callback_url" => $callbackUrl,
  "description" => $description,
);

if ($email || $mobile) {
  $data['metadata'] = [];
  if ($email) {
    $data['metadata']['email'] = $email;
  }
  if ($mobile) {
    $data['metadata']['mobile'] = $mobile;
  }
}

$jsonData = json_encode($data);
$ch = curl_init($pay_request_url);
curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-Type: application/json',
  'Content-Length: ' . strlen($jsonData)
));

$result = curl_exec($ch);
$err = curl_error($ch);
$result = json_decode($result, true, JSON_PRETTY_PRINT);
curl_close($ch);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  if (empty($result['errors'])) {
    if ($result['data']['code'] == 100) {
      header('Location: ' . $pay_start_url . $result['data']["authority"]);
    }
  } else {
    echo'Error Code: ' . $result['errors']['code'];
    echo'message: ' .  $result['errors']['message'];
  }
}

?>
