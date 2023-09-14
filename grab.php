<?php
    // Ensure the necessary parameters are sent in the request
    if (isset($_POST['contrast'], $_POST['brightness'], $_POST['hist_eq_intensity'], $_POST['picturename'], $_POST['ip_address'], $_POST['duration'], $_POST['crop'])) {

        // Retrieve parameters from the POST request
        $contrast = escapeshellarg($_POST['contrast']);
        $brightness = escapeshellarg($_POST['brightness']);
        $hist_eq_intensity = escapeshellarg($_POST['hist_eq_intensity']);
        $picturename = escapeshellarg($_POST['picturename']);
        $ip_address = escapeshellarg($_POST['ip_address']);
        $duration = escapeshellarg($_POST['duration']);
        $crop = escapeshellarg($_POST['crop']);

        // Construct command to execute the Python script with the given parameters
        $command = "python grab.py --contrast $contrast --brightness $brightness --hist_eq_intensity $hist_eq_intensity --picturename $picturename --ip_address $ip_address --duration $duration --crop $crop";
        
        // Execute the Python script and store the output
        $output = shell_exec($command);

        // Once done, you can send back a response
        echo json_encode(['status' => 'success', 'message' => 'Processed successfully.', 'output' => $output]);
    } else {
        // In case not all parameters are sent in the request
        echo json_encode(['status' => 'error', 'message' => 'Required parameters are missing.']);
    }
?>
