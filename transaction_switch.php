<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log file for transaction switch
$logFile = 'transaction_switch.log';

// Network Simulator URL (replace with the actual URL of the Network Simulator)
$networkSimulatorUrl = 'http://network-simulator-device/network_simulator.php';

// Function to log messages to the log file
function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

// Handle incoming POST request from the ATM
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $postData = file_get_contents('php://input');
    $transactionData = json_decode($postData, true);

    // Log the incoming transaction
    logMessage("Received transaction from ATM: " . print_r($transactionData, true));

    // Forward the transaction to the Network Simulator
    $ch = curl_init($networkSimulatorUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Log the response from the Network Simulator
    logMessage("Response from Network Simulator: HTTP $httpCode - $response");

    // Return the response to the ATM
    header('Content-Type: application/json');
    echo $response;
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>
