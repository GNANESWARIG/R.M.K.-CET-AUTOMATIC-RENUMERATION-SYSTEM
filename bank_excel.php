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
    if (isset($_POST['faculty']) && $_POST['faculty'] === 'external') {

        // Create new PhpSpreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set column headers starting from row 1
        $sheet->setCellValue('A1', 'S. NO');
        $sheet->setCellValue('B1', 'Acc Holder Name');
        $sheet->setCellValue('C1', 'Mobile No');
        $sheet->setCellValue('D1', 'Acc No');
        $sheet->setCellValue('E1', 'Bank Name');
        $sheet->setCellValue('F1', 'Branch');
        $sheet->setCellValue('G1', 'IFSC');
        $sheet->setCellValue('H1', 'AMOUNT');
        // Apply wrap text to headers
        $sheet->getColumnDimension('A')->setWidth(4); // Adjust the width as needed
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(17);
        $sheet->getColumnDimension('F')->setWidth(22);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(10); // Increased width for College Name// Width for Total column

        // Apply bold to column headers
        $boldStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        
        // Fetch data from externals table
        $sql = "SELECT acc_name, mob_no,REPLACE(acc_no, ',', '') AS acc_no, bank_name, branch, ifsc FROM externals";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $rowIndex = 2; // Start from row 2
            $serialNumber = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $sheet->setCellValue('A' . $rowIndex, $serialNumber);
                $sheet->setCellValue('B' . $rowIndex, $row['acc_name']);
                $sheet->setCellValue('C' . $rowIndex, $row['mob_no']);
                $sheet->setCellValueExplicit('D' . $rowIndex, $row['acc_no'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('E' . $rowIndex, $row['bank_name']);
                $sheet->setCellValue('F' . $rowIndex, $row['branch']);
                $sheet->setCellValue('G' . $rowIndex, $row['ifsc']);
                $sumSql = "SELECT SUM(ex_tot) AS total_amount FROM exam WHERE ex_num = '{$row['mob_no']}'";
        $sumResult = mysqli_query($conn, $sumSql);
        $totalAmount = 0;
        if ($sumResult && mysqli_num_rows($sumResult) > 0) {
            $totalAmountRow = mysqli_fetch_assoc($sumResult);
            $totalAmount = $totalAmountRow['total_amount'];
        }

        $sheet->setCellValue('H' . $rowIndex, $totalAmount);
                $rowIndex++;
                $serialNumber++;
            }
        }
        
        // Apply text wrap and vertical alignment to the Total row
        $totalRowNumber = $rowIndex + 1; // Assuming you have a total row
        for ($col = 'A'; $col <= 'H'; $col++) {
            $sheet->getStyle($col . $totalRowNumber)->getAlignment()->setWrapText(true);
        }
        
        $dataRows = $rowIndex - 2; // Total number of data rows
        for ($col = 'A'; $col <= 'H'; $col++) {
            // Set vertical alignment to middle for all rows
            for ($i = 1; $i <= $rowIndex; $i++) {
                $sheet->getStyle($col . $i)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }
            // Set text wrap for all cells
            for ($i = 1; $i <= $rowIndex; $i++) {
                $sheet->getStyle($col . $i)->getAlignment()->setWrapText(true);
            }
        }
        
        // Apply borders to the cells
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A1:H' . $rowIndex)->applyFromArray($styleArray);
        
        // Set landscape mode
        $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
         
        // Set print area to fit all columns on one page
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        
        // Set headers for downloading the file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="external_bank.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Create Excel writer
        $writer = new Xlsx($spreadsheet);
        
        // Save the Excel file to output
        $writer->save('php://output');
        exit;
        
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automatic Renumeration System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-image: url("bg.webp");
            background-repeat: no-repeat;
            background-size: 100% 100%;
            height: 100vh;
            overflow-y: scroll;
            overflow-x: hidden;
        }
        .aform {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            color: white;
        }
    </style>
</head>
<body>
<?php include("header.php"); ?>
<div class="bg-dark py-2 mydiv">
    <a href="exam.php" class="btn btn-warning ml-2">back</a>
</div>
<div class="container mt-5">
    <h2 class="text-center text-white mt-1 py-3" style="background-color:rgba(0, 0, 0, 0.5);color:#000000;border-radius:30px;">Download Exam Details by Faculty Type</h2>
    <form action="in_excel.php" method="post" class="aform" id="excelForm">
        <div class="form-group col-md-6">
            <label for="faculty" class="text-white" style="font-size: 18px;font-weight:500;">Faculty</label>
            <select class="form-control" id="faculty" name="faculty" required>
                <option value="">Choose Faculty type</option>
                <option value="internal">Internal</option>
                <option value="external">External</option>
                <option value="skilled_assistant">Skilled Assistant</option>
                <option value="lab_technician">Lab Technician</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Download Excel</button>
    </form>
</div>
<script>
    // Get the form and select element
    const form = document.getElementById('excelForm');
    const selectFaculty = document.getElementById('faculty');

    // Add event listener to select element
    selectFaculty.addEventListener('change', function() {
        // Get the selected value
        const selectedValue = this.value;

        // Set the action of the form based on the selected value
        switch (selectedValue) {
            case 'external':
                form.action = 'bank_excel.php'; 
                break;
            case 'internal':
                form.action = 'in_bank.php';
                break;
            case 'lab_technician':
                form.action = 'lab_bank.php';
                break;
            default:
                form.action = ''; // Set default action or leave it empty
                break;
        }
    });
</script>
</body>
</html>
