<?php
session_start();
if (!isset($_SESSION["id"])) {
    echo '<script>window.location.replace("index.php");</script>';
}
include("db.php");
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['faculty'])&& $_POST['faculty'] === 'lab_technician') {
        $dateFrom = $_POST['date_from'] ?? '';
         // Create new PhpSpreadsheet object
         $spreadsheet = new Spreadsheet();
         $sheet = $spreadsheet->getActiveSheet();
         $sheet->setCellValue('A1', 'S. NO');
         $sheet->setCellValue('B1', 'Faculty_Id');
        $sheet->setCellValue('C1', 'Acc Holder Name');
        $sheet->setCellValue('D1', 'Acc No');
        $sheet->setCellValue('E1', 'AMOUNT');
        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(10); // Adjust the width as needed
        $sheet->getColumnDimension('C')->setWidth(22);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(10);
        $boldStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $stmt = $conn->prepare("SELECT lab_acc_name, lab_Id, REPLACE(lab_acc_no, ',', '') AS lab_acc_no 
        FROM exam 
        WHERE date >= ?
         GROUP BY lab_Id");
$stmt->bind_param('s', $dateFrom);
$stmt->execute();
        if ($result && mysqli_num_rows($result) > 0) {
            $rowIndex = 2; // Start from row 2
            $serialNumber = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $sheet->setCellValue('A' . $rowIndex, $serialNumber);
                $sheet->setCellValue('B' . $rowIndex, $row['lab_Id']);
                $sheet->setCellValue('C' . $rowIndex, $row['lab_acc_name']);
                $sheet->setCellValueExplicit('D' . $rowIndex, $row['lab_acc_no'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sumStmt = $conn->prepare("SELECT SUM(lab_tot) AS total_amount FROM exam WHERE lab_Id = ? AND date >= ?");
                $sumStmt->bind_param('ss', $row['lab_Id'], $dateFrom); // Bind both parameters
                $sumStmt->execute();
                $sumResult = $sumStmt->get_result();
        $totalAmount = 0;
        if ($sumResult && mysqli_num_rows($sumResult) > 0) {
            $totalAmountRow = mysqli_fetch_assoc($sumResult);
            $totalAmount = $totalAmountRow['total_amount'];
        }

        $sheet->setCellValue('E' . $rowIndex, $totalAmount);
                $rowIndex++;
                $serialNumber++;
            }
    }
    $totalRowNumber = $rowIndex + 1; // Assuming you have a total row
        for ($col = 'A'; $col <= 'E'; $col++) {
            $sheet->getStyle($col . $totalRowNumber)->getAlignment()->setWrapText(true);
        }
        $dataRows = $rowIndex - 2; // Total number of data rows
        for ($col = 'A'; $col <= 'E'; $col++) {
            // Set vertical alignment to middle for all rows
            for ($i = 1; $i <= $rowIndex; $i++) {
                $sheet->getStyle($col . $i)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }
            // Set text wrap for all cells
            for ($i = 1; $i <= $rowIndex; $i++) {
                $sheet->getStyle($col . $i)->getAlignment()->setWrapText(true);
            }
        }
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A1:E' . $rowIndex)->applyFromArray($styleArray);
        
        // Set landscape mode
        $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="lab_technician_bank.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Create Excel writer
        $writer = new Xlsx($spreadsheet);
        
        // Save the Excel file to output
        $writer->save('php://output');
        exit;
}
}
?>