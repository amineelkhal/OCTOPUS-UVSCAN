<?php

//require_once('vendor/tecnickcom/tcpdf.php');
require_once('vendor/autoload.php');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uviscan";

$arabicToFrenchMapping = [
    'ا' => 'A',
    'ب' => 'B',
    'د' => 'D',
    'و' => 'E',
    'ط' => 'T',
    'ه' => 'H'
];

function replaceArabicWithFrench($string, $mapping) {
    return str_replace(array_keys($mapping), array_values($mapping), $string);
}


class CustomPDF extends TCPDF {
    public function Header() {
        // Logo
        $image_file = 'assets/images/brand/logo-2.png'; // Path to your new logo
        $this->Image($image_file, 15, 8, 13, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        
        // Add the texts
        $this->SetXY(30, 5); // Adjust (x, y) values as required
        $this->Cell(0, 10, 'UVSCAN Module, by OCTOPUS Hypervisor', 0, 1, 'L');
        
        $this->SetXY(30, 10);
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 10, date('H:i:s d-m-Y'), 0, 1, 'L'); // Assuming current date and time
        
        $this->SetXY(30, 15);
        $this->Cell(0, 10, 'Royal Mansour Marrakech', 0, 1, 'L');
    }

    // If you also want to customize the footer, you can override the Footer() method here.
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = $sql = "
SELECT e.*, s.name AS scanner_name, s.description AS scanner_description 
FROM entrances e 
JOIN scanner s ON e.scanner = s.id
ORDER BY entry_date DESC";
$result = $conn->query($sql);

// Create new PDF document
$pdf = new CustomPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('OCTOPUS Reporter');
$pdf->SetTitle('UVSCAN - Royal Mansour Marrakech');
$pdf->SetSubject('Entrances History');
$pdf->SetKeywords('PDF, entrances, export');

// Set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' Example', PDF_HEADER_STRING);

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 30);

/*if ($pdf->getY() > ($pdf->getPageHeight() - 50)) { // 20 is an arbitrary small margin
    $pdf->AddPage();
}*/

// Add a page
$pdf->AddPage('L');

// Set font
$pdf->SetFont('helvetica', '', 12);

$html = '
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #f1f1f1;
        padding: 8px;
    }
    th {
        background-color: #f2f2f2;
    }
</style>';

while($row = $result->fetch_assoc()) {

    $translatedData = replaceArabicWithFrench($row['plate'], $arabicToFrenchMapping);

    $html .= '<table border="1" cellspacing="0" cellpadding="5">
    <tr bgcolor="#CCCCCC">
        <th width="50">ID</th>
        <th>Scanner</th>
        <th>Plate</th>
        <th>Plate Picture</th>
        <th width="200">Scan Results</th>
        <th>Description</th>
        <th width="80">Entry Date</th>
    </tr>';

    $html .= '<tr>';
    $html .= '<td>' . $row['id'] . '</td>';
    $html .= '<td>' . $row['scanner_name'] . '<hr>' . $row['scanner_description'] . '</td>';
    $html .= '<td>' . $translatedData . '</td>';
    $html .= '<td><img style="display:block" src="' . $row['picture'] . '" height="50"><hr><img src="' . $row['picture'] . '.png" height="50"></td>';
    $html .= '<td><img style="display:block" src="' . $row['scan'] . '" height="50"></td>';
    $html .= '<td>' . $row['description'] . '<hr><div style="background-color:rgb(' . $row['color'] . '); height:30px; margin:5px; border:1px solid #f1f1f1;"></div><hr>' . $row['category'] . '<hr>' . $row['mark'] . '</td>';
    $html .= '<td>' . $row['entry_date'] . '</td>';
    $html .= '</tr>';
    $html .= '</table>';
}

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF
$pdf->Output('UVSCAN - RMM - '. date('H-i-s d-m-Y') .'.pdf', 'I');

$conn->close();

?>
