<?php

if(isset($_GET['currentScan']) && isset($_GET['previousScan'])) {
    $currentScan = $_GET['currentScan'];
    $previousScan = $_GET['previousScan'];
    $entryDate = $_GET['entryDate'];
    $previousEntryDate = $_GET['previousEntryDate'];
    
    // Assuming compare.py is in the same directory. Adjust the path as necessary.
    $command = escapeshellcmd('py compare-3.py ' . $previousScan . ' ' . $currentScan . ' "' . $entryDate . '" "' . $previousEntryDate .'"');
    
    $output = shell_exec($command);
    
    // You can return the output to the frontend or process it as needed
    echo $output;
} else {
    echo "Required parameters are missing!";
}

?>
