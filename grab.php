<?php
    // Define an array of required parameters
$requiredParams = ['contrast', 'brightness', 'hist_eq_intensity', 'picturename', 'ip_address', 'duration', 'crop'];

// Initialize an array to store missing parameters
$missingParams = [];

// Check for missing parameters
foreach ($requiredParams as $param) {
    if (!isset($_REQUEST[$param])) {
        $missingParams[] = $param;
    }
}

// If there are missing parameters, return an error message
if (!empty($missingParams)) {
    echo json_encode(['status' => 'error', 'message' => 'Required parameters are missing: ' . implode(', ', $missingParams)]);
} else {
    // Retrieve parameters from the POST request
    $contrast = escapeshellarg($_REQUEST['contrast']);
    $brightness = escapeshellarg($_REQUEST['brightness']);
    $hist_eq_intensity = escapeshellarg($_REQUEST['hist_eq_intensity']);
    $picturename = escapeshellarg($_REQUEST['picturename']);
    $ip_address = escapeshellarg($_REQUEST['ip_address']);
    $duration = escapeshellarg($_REQUEST['duration']);
    $crop = escapeshellarg($_REQUEST['crop']);

    // Construct command to execute the Python script with the given parameters
    $command = "python grab.py --contrast $contrast --brightness $brightness --hist_eq_intensity $hist_eq_intensity --picturename $picturename --ip_address $ip_address --duration $duration --crop $crop";
    
    // Execute the Python script and store the output
    $output = shell_exec($command);

    // Once done, you can send back a response
    echo json_encode(['status' => 'success', 'message' => 'Processed successfully.', 'output' => $output]);
}
