<?php
include '../config/database.php';

// bKash Payment Callback
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paymentID = $_POST['paymentID'];
    $transactionID = $_POST['transactionID'];
    $amount = $_POST['amount'];
    $user_id = $_POST['user_id'];
    $package_id = $_POST['package_id'];
    
    // Verify payment with bKash API
    $bkash_response = verify_bkash_payment($paymentID, $transactionID, $amount);
    
    if ($bkash_response['statusCode'] == '0000') {
        // Update order status
        $expiry_date = date('Y-m-d', strtotime('+30 days'));
        $stmt = $pdo->prepare("UPDATE orders SET status = 'active', expiry_date = ? 
                              WHERE user_id = ? AND package_id = ? AND status = 'pending'");
        $stmt->execute([$expiry_date, $user_id, $package_id]);
        
        // Send WhatsApp notification
        send_whatsapp_notification($user_id, "Payment successful! Your VPN package is now active.");
        
        // Send email confirmation
        send_email_confirmation($user_id, $package_id);
        
        echo "Payment successful! Your VPN package is now active.";
    } else {
        echo "Payment verification failed: " . $bkash_response['statusMessage'];
    }
}

function verify_bkash_payment($paymentID, $transactionID, $amount) {
    // bKash API verification code here
    $url = "https://checkout.pay.bka.sh/v1.2.0-beta/checkout/payment/verify";
    $data = [
        'paymentID' => $paymentID,
        'trxID' => $transactionID
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer YOUR_BKASH_TOKEN',
        'X-APP-Key: YOUR_BKASH_APP_KEY'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}
?>