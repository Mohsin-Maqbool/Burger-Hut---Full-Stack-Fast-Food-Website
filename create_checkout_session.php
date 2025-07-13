<?php
require 'vendor/autoload.php';
session_start();

\Stripe\Stripe::setApiKey('sk_test_your_key_here');

// 1. Validate inputs
if (
    !isset($_SESSION['cart']) || empty($_SESSION['cart']) ||
    !isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address'])
) {
    header("Location: checkout.php?error=1");
    exit;
}

// 2. Sanitize and assign
$cart = $_SESSION['cart'];
$name = htmlspecialchars(trim($_POST['name']));
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$phone = htmlspecialchars(trim($_POST['phone']));
$address = htmlspecialchars(trim($_POST['address']));

// 3. Calculate total
$total_pkr = 0;
foreach ($cart as $item) {
    $total_pkr += (float)$item['price'] * (int)$item['qty'];
}

// 4. Convert to Stripe USD cents
$conversion_rate = 280; // 1 USD = 280 PKR
$amount_usd_cents = round(($total_pkr / $conversion_rate) * 100);
if ($amount_usd_cents < 50) $amount_usd_cents = 50;

// 5. Order description for Stripe display
$product_names = array_map(fn($i) => $i['name'] . ' x' . $i['qty'], $cart);
$description = implode(', ', $product_names);

// 6. Encode metadata (secure way to pass order info)
$metadata = [
    'name' => $name,
    'phone' => $phone,
    'address' => $address,
    'total_pkr' => $total_pkr,
    'cart' => json_encode($cart)
];

try {
    // 7. Create Stripe Checkout Session
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
        'metadata' => $metadata,
        'success_url' => 'http://localhost/burgerhut_site/order-success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost/burgerhut_site/checkout.php?cancel=1',
    ]);

    // 8. Redirect to Stripe Checkout page
    header("Location: " . $checkout_session->url);
    exit;

} catch (Exception $e) {
    echo "Stripe error: " . $e->getMessage();
}
