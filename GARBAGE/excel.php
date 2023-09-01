<?php

// Include PHPExcel library
include 'libs/PHPExcel/PHPExcel.php';

// Connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'uviscan');

// Retrieve data from the database
$result = mysqli_query($conn, 'SELECT * FROM scanner');

// Create a new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set the active sheet index to the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Populate the first row of the sheet with the database column names
$i = 0;
while ($fieldinfo = mysqli_fetch_field($result)) {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, 1, $fieldinfo->name);
    $i++;
}

// Populate the rest of the sheet with the database data
$row = 2;
while ($row_data = mysqli_fetch_array($result)) {
    $col = 0;
    foreach ($row_data as $cell) {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $cell);
        $col++;
    }
    $row++;
}

// Save the Excel file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('file.xlsx');
