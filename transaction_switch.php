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

// Simulate forwarding to the Network Simulator
$networkSimulatorUrl = "http://127.0.0.1:8001/process_transaction"; // Replace with actual URL
$options = [
    'http' => [
        'header'  => "Content-Type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
    ],
];
$context  = stream_context_create($options);
$response = file_get_contents($networkSimulatorUrl, false, $context);

if ($response === FALSE) {
    // Log the error
    logMessage("Failed to communicate with Network Simulator");
    http_response_code(500); // Internal Server Error
    echo json_encode(["status" => "error", "message" => "Network Simulator communication failed"]);
    exit;
}

// Log the response from the Network Simulator
logMessage("Network Simulator response: $response");

// Return the response to the ATM application
echo $response;
?>
