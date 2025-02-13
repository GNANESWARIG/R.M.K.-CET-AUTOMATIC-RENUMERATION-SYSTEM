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
    if(isset($_POST['faculty']) && $_POST['faculty'] === 'external') {
        $fromDate = $_POST['from_date'];
        $toDate = $_POST['from_date'];
        // Create new PhpSpreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header image
        $imagePathLeft = 'images/1.png';
        $imagePathRight = 'images/2.jpeg';
        $drawingLeft = new Drawing();
        $drawingLeft->setPath($imagePathLeft);
        $drawingLeft->setCoordinates('A1');
        $drawingLeft->setWorksheet($sheet);
        $drawingLeft->setWidth(90);
        $drawingLeft->setHeight(90);

        $drawingRight = new Drawing();
        $drawingRight->setPath($imagePathRight);
        $drawingRight->setCoordinates('L1');
        $drawingRight->setWorksheet($sheet);
        $drawingRight->setWidth(90);
        $drawingRight->setHeight(90);

        // Set fonts and sizes
        $sheet->getStyle('A1:L1')->getFont()->setName('Tahoma')->setSize(20);
        $sheet->getStyle('A2:L2')->getFont()->setName('Tahoma')->setSize(10);
        $sheet->getStyle('A3:L3')->getFont()->setName('Tahoma')->setSize(11);
        $sheet->getStyle('A4:L4')->getFont()->setName('Tahoma')->setSize(12);
        $sheet->getStyle('C5:K5')->getFont()->setName('Tahoma')->setSize(16);

        // Merge cells for the header
        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');
        $sheet->mergeCells('A3:L3');
        $sheet->mergeCells('A4:L4'); 
        $sheet->mergeCells('C5:K5');

        // Add header
        $header = "R.M.K. COLLEGE OF ENGINEERING AND TECHNOLOGY";
        $h1="(AN AUTONOMOUS INSTITUTION)";
        $h2="RSM Nagar, Puduvoyal – 601 206";
        $h3 = "End Semester Practical Examinations May/June 2024 : $fromDate ";
        $h4="DAY WISE PRACTICAL CLAIM SETTLEMENT - ABSTRACT";

        $sheet->setCellValue('A1', $header);
        $sheet->setCellValue('A2', $h1);
        $sheet->setCellValue('A3', $h2);
        $sheet->setCellValue('A4', $h3);
        $sheet->setCellValue('C5', $h4);
        
        // Set alignment for header
        $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:L2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:L3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:L4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C5:K5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set column headers
        $sheet->setCellValue('A6', 'S. NO');
        $sheet->setCellValue('B6', 'DATE');
        $sheet->setCellValue('C6', 'DEPT');
        $sheet->setCellValue('D6', 'SEM');
        $sheet->setCellValue('E6', 'COURSE CODE');
        $sheet->setCellValue('F6', 'COURSE NAME');
        $sheet->setCellValue('G6', 'Name of the External Examiner');
        $sheet->setCellValue('H6', 'College Name');
        $sheet->setCellValue('I6', 'Total No. of Candidates Examined');
        $sheet->setCellValue('J6', 'Amount (Rs.25/- per candidate to a min of Rs.100/-)');
        $sheet->setCellValue('K6', 'Lumpsum (Rs.550/- per day to a min of Rs.500/-)');
        $sheet->setCellValue('L6', 'Total Amount (Rs.)');

        // Set text wrap for all cells in row 6
        for ($col = 'A'; $col <= 'L'; $col++) {
            $sheet->getStyle($col . '6')->getAlignment()->setWrapText(true);
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(4); // Adjust the width as needed
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(22);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(8); // Increased width for College Name
        $sheet->getColumnDimension('I')->setWidth(11);
        $sheet->getColumnDimension('J')->setWidth(11); // Increased width for Amount
        $sheet->getColumnDimension('K')->setWidth(11); // Increased width for Lumpsum
        $sheet->getColumnDimension('L')->setWidth(9);// Width for Total column

        // Apply bold to column headers
        $boldStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A6:L6')->applyFromArray($boldStyle);

        // Fetch data from the exam database for the external faculty type based on date range
        $sql = "SELECT * FROM exam WHERE date BETWEEN '$fromDate' AND '$toDate' ORDER BY date, FIELD(department, 'CSE', 'ECE', 'AI-DS', 'CSE(CS)', 'MECH'), 
        CASE 
            WHEN semester = '4' THEN 1
            WHEN semester = '6' THEN 2
            WHEN semester = '8' THEN 3
            ELSE 4
        END";
    
    

        $result = mysqli_query($conn, $sql);
        $rowNumber = 7; // Start from row 7 to leave space for headers

        // Initialize variables for merging DATE cells
        $prevDate = null;
        $dateCount = 0;

        // Initialize variables for column sums
        $totalCandidates = 0;
        $totalAmount = 0;
        $totalLumpsum = 0;

        // Iterate through the rows and add data to the Excel sheet
        while ($row = mysqli_fetch_assoc($result)) {
            // If date changes, merge cells for previous DATE column and insert "Total" row
            if ($prevDate != $row['date']) {
                if ($prevDate != null) {
                    $sheet->mergeCells('B' . ($rowNumber - $dateCount) . ':B' . ($rowNumber - 1));
                    $totalRowNumber = $rowNumber;
                    $sheet->insertNewRowBefore($rowNumber, 1); // Insert a new row for the Total row
                    $sheet->mergeCells('A' . $totalRowNumber . ':H' . $totalRowNumber); // Merge cells for Total row
                    $sheet->setCellValue('A' . $totalRowNumber, 'Total');
                    $sheet->getStyle('A' . $totalRowNumber . ':L' . $totalRowNumber)->applyFromArray($boldStyle);
                    // Set sums for total row
                    $sheet->setCellValue('I' . $totalRowNumber, $totalCandidates);
                    $sheet->setCellValue('J' . $totalRowNumber, $totalAmount);
                    $sheet->setCellValue('K' . $totalRowNumber, $totalLumpsum);
                    $sheet->setCellValue('L' . $totalRowNumber, $totalAmount + $totalLumpsum);
                    // Reset column sums
                    $totalCandidates = 0;
                    $totalAmount = 0;
                    $totalLumpsum = 0;
                    $rowNumber++;
                }
                $prevDate = $row['date'];
                $dateCount = 0;
                $sno = 1; // Reset S.No
            }

            // Set cell values
            $sheet->setCellValue('A' . $rowNumber, $sno++);
            $sheet->setCellValue('B' . $rowNumber, $row['date']);
            $sheet->setCellValue('C' . $rowNumber, $row['department']);
            $sheet->setCellValue('D' . $rowNumber, $row['semester']);
            $sheet->setCellValue('E' . $rowNumber, $row['sub_code']);
            $sheet->setCellValue('F' . $rowNumber, $row['sub_name']);
            $sheet->setCellValue('G' . $rowNumber, $row['ex_name']);
            $sheet->setCellValue('H' . $rowNumber, $row['ex_college']);
            $sheet->setCellValue('I' . $rowNumber, $row['students']);
            $sheet->setCellValue('J' . $rowNumber, $row['ex_renum']);
            $sheet->setCellValue('K' . $rowNumber, $row['ex_lump']);
            $sheet->setCellValue('L' . $rowNumber, $row['ex_tot']);

            // Update column sums
            $totalCandidates += $row['students'];
            $totalAmount += $row['ex_renum'];
            $totalLumpsum += $row['ex_lump'];

            // Set font style for the fetched data
            for ($col = 'A'; $col <= 'L'; $col++) {
                $sheet->getStyle($col . $rowNumber)->getFont()->setName('Times New Roman')->setSize(12);
                $sheet->getStyle($col . $rowNumber)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }
            
            // Increment row number
            $rowNumber++;
            $dateCount++;
        }

        // Merge cells for the last set of DATE column and insert "Total" row
        if ($prevDate != null) { 
            $sheet->mergeCells('B' . ($rowNumber - $dateCount) . ':B' . ($rowNumber - 1));
            $totalRowNumber = $rowNumber;
            $sheet->insertNewRowBefore($rowNumber, 1); // Insert a new row for the Total row
            $sheet->mergeCells('A' . $totalRowNumber . ':H' . $totalRowNumber); // Merge cells for Total row
            $sheet->setCellValue('A' . $totalRowNumber, 'Total');
            $sheet->getStyle('A' . $totalRowNumber . ':L' . $totalRowNumber)->applyFromArray($boldStyle);
            // Set sums for total row
            $sheet->setCellValue('I' . $totalRowNumber, $totalCandidates);
            $sheet->setCellValue('J' . $totalRowNumber, $totalAmount);
            $sheet->setCellValue('K' . $totalRowNumber, $totalLumpsum);
            $sheet->setCellValue('L' . $totalRowNumber, $totalAmount + $totalLumpsum);
            // Apply text wrap and vertical alignment to the Total row
            for ($col = 'A'; $col <= 'L'; $col++) {
                $sheet->getStyle($col . $totalRowNumber)->getAlignment()->setWrapText(true);
            }
        }
        

        // Set text wrap for all cells in the data area
        $dataRows = $rowNumber - 7; // Total number of data rows
        for ($col = 'A'; $col <= 'L'; $col++){
            // Set vertical alignment to middle for all rows
            for ($i = 7; $i <= $rowNumber; $i++) {
                $sheet->getStyle($col . $i)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }
            // Set text wrap for all cells
            for ($i = 7; $i <= $rowNumber; $i++) {
                $sheet->getStyle($col . $i)->getAlignment()->setWrapText(true);
            }
        }

        // Apply borders to all cells with a thinner border
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ], 
        ];
        $sheet->getStyle('A6:L' . $rowNumber)->applyFromArray($styleArray);

        // Set landscape mode
        $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

        // Set print area to fit all columns on one page
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);

        // Redirec t output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="exam_details_external.xlsx"');
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
            <label for="from_date" class="text-white" style="font-size: 18px;font-weight:500;">Date</label>
            <input type="date" class="form-control" id="from_date" name="from_date" required> 
        </div>
        <button type="submit" class="btn btn-primary" id="Dexcel">Download Excel</button>
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
                form.action = 'fac_excel.php'; 
                break;
            case 'internal':
                form.action = 'in_excel.php';
                break;
            case 'skilled_assistant':
                form.action = 'sk_excel.php';
                break;
            case 'lab_technician':
                form.action = 'lab_excel.php';
                break;
            default:
                form.action = ''; // Set default action or leave it empty
                break;
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        document.getElementById('from_date').setAttribute('value', today);
    });
</script>
     


</body>
</html>
