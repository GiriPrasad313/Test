<?php
header("Content-Type: application/json");

// Log file
$logFile = 'transaction_switch.log';

// Function to log messages
function logMessage($message) {
    global $logFile;
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logFile, "$timestamp - $message\n", FILE_APPEND);
}

// Read input data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input
if (empty($data) || !isset($data['card_number']) || !isset($data['amount']) || !isset($data['transaction_type'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

// Log the incoming transaction
logMessage("Received transaction: " . json_encode($data));

// Simulate the Network Simulator response
if ($data['transaction_type'] == 'withdrawal') {
    if ($data['amount'] > 0) {
        $response = ["status" => "approved", "message" => "Transaction approved"];
    } else {
        $response = ["status" => "denied", "message" => "Invalid amount"];
    }
} elseif ($data['transaction_type'] == 'balance_inquiry') {
    $response = ["status" => "approved", "message" => "Balance inquiry successful"];
} else {
    $response = ["status" => "denied", "message" => "Invalid transaction type"];
}

// Log the response
logMessage("Simulated Network Simulator response: " . json_encode($response));

// Return the response to the ATM application
echo json_encode($response);
?>
