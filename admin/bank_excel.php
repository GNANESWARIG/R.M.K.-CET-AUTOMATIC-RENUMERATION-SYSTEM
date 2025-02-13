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
        $dateFrom = $_POST['date_from'] ?? '';

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
        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(17);
        $sheet->getColumnDimension('F')->setWidth(22);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(10);

        // Apply bold to column headers
        $boldStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        // Prepare the SQL query
        $stmt = $conn->prepare("SELECT ex_acc_name, ex_num, REPLACE(ex_acc_no, ',', '') AS ex_acc_no, ex_bank_name, ex_branch, ex_ifsc 
                                FROM exam 
                                WHERE date >= ?
                                 GROUP BY ex_num");
        $stmt->bind_param('s', $dateFrom);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $rowIndex = 2; // Start from row 2
            $serialNumber = 1;
            while ($row = $result->fetch_assoc()) {
                $sheet->setCellValue('A' . $rowIndex, $serialNumber);
                $sheet->setCellValue('B' . $rowIndex, $row['ex_acc_name']);
                $sheet->setCellValue('C' . $rowIndex, $row['ex_num']);
                $sheet->setCellValueExplicit('D' . $rowIndex, $row['ex_acc_no'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('E' . $rowIndex, $row['ex_bank_name']);
                $sheet->setCellValue('F' . $rowIndex, $row['ex_branch']);
                $sheet->setCellValue('G' . $rowIndex, $row['ex_ifsc']);

                $sumStmt = $conn->prepare("SELECT SUM(ex_tot) AS total_amount FROM exam WHERE ex_num = ? AND date >= ?");
                $sumStmt->bind_param('ss', $row['ex_num'], $dateFrom); // Bind both parameters
                $sumStmt->execute();
                $sumResult = $sumStmt->get_result();
                $totalAmount = 0;
                if ($sumResult && $sumResult->num_rows > 0) {
                    $totalAmountRow = $sumResult->fetch_assoc();
                    $totalAmount = $totalAmountRow['total_amount'];
                }

                $sheet->setCellValue('H' . $rowIndex, $totalAmount);
                $rowIndex++;
                $serialNumber++;
            }
        }

        // Apply text wrap and vertical alignment to the Total row
        $totalRowNumber = $rowIndex + 1;
        for ($col = 'A'; $col <= 'H'; $col++) {
            $sheet->getStyle($col . $totalRowNumber)->getAlignment()->setWrapText(true);
        }

        $dataRows = $rowIndex - 2;
        for ($col = 'A'; $col <= 'H'; $col++) {
            for ($i = 1; $i <= $rowIndex; $i++) {
                $sheet->getStyle($col . $i)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }
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
        <div class="form-group col-md-6">
            <label for="date_from" class="text-white" style="font-size: 18px;font-weight:500;">Date From</label>
            <input type="date" class="form-control" id="date_from" name="date_from" required>
        </div>
        <button type="submit" class="btn btn-primary">Download Excel</button>
    </form>
</div>
<script>
    // Get the form, select element, and date input
    const form = document.getElementById('excelForm');
    const selectFaculty = document.getElementById('faculty');
    const dateInput = document.getElementById('date_from');

    // Add event listener to select element
    selectFaculty.addEventListener('change', function() {
        // Get the selected value
        const selectedValue = this.value;

        // Get the date value and encode it
        const dateFrom = encodeURIComponent(dateInput.value);

        // Set the action of the form based on the selected value and include dateFrom
        switch (selectedValue) {
            case 'external':
                form.action = 'bank_excel.php?date_from=' + dateFrom;
                break;
            case 'internal':
                form.action = 'in_bank.php?date_from=' + dateFrom;
                break;
            case 'lab_technician':
                form.action = 'lab_bank.php?date_from=' + dateFrom;
                break;
            default:
                form.action = ''; // Set default action or leave it empty
                break;
        }
    });

    // Ensure the date is sent on form submission
    form.addEventListener('submit', function(event) {
        if (selectFaculty.value === '') {
            event.preventDefault(); // Prevent form submission if no faculty type is selected
            alert('Please select a faculty type.');
        }
    });
</script>

</body>
</html>
