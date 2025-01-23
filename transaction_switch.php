// Simulate forwarding to the Network Simulator
$networkSimulatorUrl = "http://<NETWORK_SIMULATOR_URL>/process_transaction"; // Replace with actual URL
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
