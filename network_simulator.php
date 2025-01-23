<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log file for network simulator
$logFile = 'network_simulator.log';

// Function to log messages to the log file
function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

// Handle incoming POST request from the Transaction Switch
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $postData = file_get_contents('php://input');
    $transactionData = json_decode($postData, true);

    // Log the incoming transaction
    logMessage("Received transaction from Transaction Switch: " . print_r($transactionData, true));

    // Simulate automatic approval
    $response = [
        'status' => 'approved',
        'transactionId' => uniqid(),
        'message' => 'Transaction approved successfully'
    ];

    // Log the response
    logMessage("Sending response: " . print_r($response, true));

    // Return the response to the Transaction Switch
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>
