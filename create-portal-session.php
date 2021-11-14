<?php

require './vendor/autoload.php';
// This is your real test secret API key.
\Stripe\Stripe::setApiKey('sk_test_51JfONNAEcI2cqmHuyph6DhVKT6VBpJBNAwtAieqbryfkzFPFtYHL7Pf7OV4jpFvsxAJXVU6ZiW1qrZdWKJbyvlWn0001OOQhXu');

header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost:8080/ghitub/albastage/public';

try {
  $checkout_session = \Stripe\Checkout\Session::retrieve($_POST['session_id']);
  $return_url = $YOUR_DOMAIN;

  // Authenticate your user.
  $session = \Stripe\BillingPortal\Session::create([
    'customer' => $checkout_session->customer,
    'return_url' => $return_url,
  ]);
  header("HTTP/1.1 303 See Other");
  header("Location: " . $session->url);
} catch (Error $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}