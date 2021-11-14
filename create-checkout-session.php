<?php

require './vendor/autoload.php';
// This is your real test secret API key.
\Stripe\Stripe::setApiKey('sk_test_51JfONNAEcI2cqmHuyph6DhVKT6VBpJBNAwtAieqbryfkzFPFtYHL7Pf7OV4jpFvsxAJXVU6ZiW1qrZdWKJbyvlWn0001OOQhXu');

header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost:8080/ghitub/albastage/public';

try {
  $prices = \Stripe\Price::all([
    // retrieve lookup_key from form data POST body
    'lookup_keys' => [$_POST['lookup_key']],
    'expand' => ['data.product']
  ]);

  $checkout_session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
      'price' => $prices->data[0]->id,
      'quantity' => 1,
    ]],
    'mode' => 'subscription',
    'success_url' => $YOUR_DOMAIN . '/success.html?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
  ]);

  header("HTTP/1.1 303 See Other");
  header("Location: " . $checkout_session->url);
} catch (Error $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}