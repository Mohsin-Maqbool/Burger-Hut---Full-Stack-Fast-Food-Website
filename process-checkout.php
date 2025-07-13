<?php
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_your_key_here'); // Replace with your Stripe secret key

session_start();

// Validate input
if (!isset($_SESSION['cart']) || empty($_SESSION['cart']) || !isset($_POST['name'], $_POST['email'])) {
    header("Location: checkout.php?error=1");
    exit;
}

$cart = $_SESSION['cart'];
$name = htmlspecialchars(trim($_POST['name']));
$email = htmlspecialchars(trim($_POST['email']));

// Calculate total price in PKR
$total_pkr = 0;
foreach ($cart as $item) {
    $total_pkr += $item['price'] * $item['qty'];
}

// Convert PKR to USD cents
$conversion_rate = 280; // 1 USD = 280 PKR (adjust as needed)
$amount_usd_cents = round(($total_pkr / $conversion_rate) * 100);

// Enforce Stripe's minimum charge ($0.50 USD)
if ($amount_usd_cents < 50) {
    $amount_usd_cents = 50;
}

// Create a description string
$product_names = array_map(fn($i) => $i['name'] . ' x' . $i['qty'], $cart);
$description = implode(', ', $product_names);

try {
    // Create Stripe Checkout session
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Burger Hut Order',
                    'description' => $description,
                ],
                'unit_amount' => $amount_usd_cents,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'customer_email' => $email,
        'success_url' => 'http://localhost/burgerhut_site/order-success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost/burgerhut_site/checkout.php?cancel=1',
    ]);

    // Store order details temporarily in session
    $_SESSION['order_data'] = [
        'name' => $name,
        'email' => $email,
        'cart' => $cart,
        'total' => $total_pkr,
    ];

    // Redirect to Stripe Checkout
    header("Location: " . $checkout_session->url);
    exit;
} catch (Exception $e) {
    echo "Stripe error: " . $e->getMessage();
    exit;
}
