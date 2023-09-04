<?php

// Include autoloader
require 'vendor/autoload.php';

// Initialize dompdf class
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set("isRemoteEnabled", true);

$dompdf = new Dompdf($options);

$html = '
<link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
<h1 style="color:red;" class="btn btn-primary">Welcome to dompdf 22</h1><p>This is a test document. http://localhost:8000/assets/plugins/bootstrap/css/bootstrap.min.css</p>
';
$dompdf->loadHtml($html);

// (Optional) Set up the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Stream the file (i.e. send it to browser or download)
$dompdf->stream("sample.pdf", array("Attachment" => false)); // This will open the PDF directly in the browser

exit(0);
