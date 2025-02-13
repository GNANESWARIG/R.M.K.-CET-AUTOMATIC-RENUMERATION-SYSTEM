<?php
require 'vendor/autoload.php'; // Include the Composer autoloader

use PhpOffice\PhpSpreadsheet\IOFactory;

// Specify the path to your Excel file
$excelFile = 'uploads/emp.xlsx';

// Load the Excel file
$spreadsheet = IOFactory::load($excelFile);

// Get the first worksheet
$worksheet = $spreadsheet->getActiveSheet();

// Get the highest column and row numbers from the worksheet
$highestColumn = $worksheet->getHighestColumn();
$highestRow = $worksheet->getHighestRow();

// Loop through each row of the worksheet
for ($row = 0; $row <= $highestRow; ++$row) {
    $rowData = [];
    // Loop through each column
    for ($col = 'A'; $col <= $highestColumn; ++$col) {
        // Get the value in the current cell
        $cellValue = $worksheet->getCell($col . $row)->getValue();
        // Add the cell value to the row data array
        $rowData[] = $cellValue;
    }
    // Print the row data
    print_r($rowData);
    echo "<br>"; // Add a line break after each row
}
?>
