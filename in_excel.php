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
    if(isset($_POST['faculty']) && $_POST['faculty'] === 'internal') {
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
        $drawingLeft->setWidth(80);
        $drawingLeft->setHeight(80);

        $drawingRight = new Drawing();
        $drawingRight->setPath($imagePathRight);
        $drawingRight->setCoordinates('I1');
        $drawingRight->setWorksheet($sheet);
        $drawingRight->setWidth(90);
        $drawingRight->setHeight(90);

        // Set fonts and sizes
        $sheet->getStyle('A1:I1')->getFont()->setName('Tahoma')->setSize(20);
        $sheet->getStyle('A2:I2')->getFont()->setName('Tahoma')->setSize(10);
        $sheet->getStyle('A3:I3')->getFont()->setName('Tahoma')->setSize(11);
        $sheet->getStyle('A4:I4')->getFont()->setName('Tahoma')->setSize(12);
        $sheet->getStyle('C5:I5')->getFont()->setName('Tahoma')->setSize(16);

        // Merge cells for the header
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        $sheet->mergeCells('A4:I4');
        $sheet->mergeCells('C5:I5');

        // Add header
        $header = "R.M.K. COLLEGE OF ENGINEERING AND TECHNOLOGY";
        $h1="(AN AUTONOMOUS INSTITUTION)";
        $h2="RSM Nagar, Puduvoyal – 601 206";
        $h3 = "End Semester Practical Examinations May/June 2024 : $fromDate to $toDate";
        $h4="DAY WISE PRACTICAL CLAIM SETTLEMENT - ABSTRACT";

        $sheet->setCellValue('A1', $header);
        $sheet->setCellValue('A2', $h1);
        $sheet->setCellValue('A3', $h2);
        $sheet->setCellValue('A4', $h3);
        $sheet->setCellValue('C5', $h4);
        
        // Set alignment for header
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:I2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:I4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C5:I5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set column headers
        $sheet->setCellValue('A6', 'S. NO');
        $sheet->setCellValue('B6', 'DATE');
        $sheet->setCellValue('C6', 'DEPT');
        $sheet->setCellValue('D6', 'SEM');
        $sheet->setCellValue('E6', 'COURSE CODE');
        $sheet->setCellValue('F6', 'COURSE NAME');
        $sheet->setCellValue('G6', 'Name of the Internal Examiner');
        $sheet->setCellValue('H6', 'Total No. of Candidates Examined');
        $sheet->setCellValue('I6', 'Amount (Rs.25/- per candidate to a min of Rs.100/-)');
        // Set text wrap for all cells in row 6
        for ($col = 'A'; $col <= 'I'; $col++) {
            $sheet->getStyle($col . '6')->getAlignment()->setWrapText(true);
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(4); // Adjust the width as needed
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(11);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(26); // Increased width for College Name
        $sheet->getColumnDimension('H')->setWidth(11);
        $sheet->getColumnDimension('I')->setWidth(11);
        

        // Apply bold to column headers
        $boldStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A6:I6')->applyFromArray($boldStyle);

        // Fetch data from the exam database for the internal faculty type based on date range
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

        // Iterate through the rows and add data to the Excel sheet
        while ($row = mysqli_fetch_assoc($result)) {
            // If date changes, merge cells for previous DATE column and insert "Total" row
            if ($prevDate != $row['date']) {
                if ($prevDate != null) {
                    $sheet->mergeCells('B' . ($rowNumber - $dateCount) . ':B' . ($rowNumber - 1));
                    $totalRowNumber = $rowNumber;
                    $sheet->insertNewRowBefore($rowNumber, 1); // Insert a new row for the Total row
                    $sheet->mergeCells('A' . $totalRowNumber . ':G' . $totalRowNumber); // Merge cells for Total row
                    $sheet->setCellValue('A' . $totalRowNumber, 'Total');
                    $sheet->getStyle('A' . $totalRowNumber . ':I' . $totalRowNumber)->applyFromArray($boldStyle);
                    // Set sums for total row
                    $sheet->setCellValue('H' . $totalRowNumber, $totalCandidates);
                    $sheet->setCellValue('I' . $totalRowNumber, $totalAmount);
                    // Reset column sums
                    $totalCandidates = 0;
                    $totalAmount = 0;
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
            $sheet->setCellValue('G' . $rowNumber, $row['in_name']);
            $sheet->setCellValue('H' . $rowNumber, $row['students']);
            $sheet->setCellValue('I' . $rowNumber, $row['in_renum']);
            $totalCandidates += $row['students'];
            $totalAmount += $row['in_renum'];
            // Set font style for the fetched data
            for ($col = 'A'; $col <= 'I'; $col++) {
                $sheet->getStyle($col . $rowNumber)->getFont()->setName('Times New Roman')->setSize(12);
                $sheet->getStyle($col . $rowNumber)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }
            
            // Increment row number
            $rowNumber++;
            $dateCount++;
        }
        if ($prevDate != null) { 
            $sheet->mergeCells('B' . ($rowNumber - $dateCount) . ':B' . ($rowNumber - 1));
            $totalRowNumber = $rowNumber;
            $sheet->insertNewRowBefore($rowNumber, 1); // Insert a new row for the Total row
            $sheet->mergeCells('A' . $totalRowNumber . ':G' . $totalRowNumber); // Merge cells for Total row
            $sheet->setCellValue('A' . $totalRowNumber, 'Total');
            $sheet->getStyle('A' . $totalRowNumber . ':I' . $totalRowNumber)->applyFromArray($boldStyle);
            // Set sums for total row
            $sheet->setCellValue('H' . $totalRowNumber, $totalCandidates);
            $sheet->setCellValue('I' . $totalRowNumber, $totalAmount);
            // Apply text wrap and vertical alignment to the Total row
            for ($col = 'A'; $col <= 'I'; $col++) {
                $sheet->getStyle($col . $totalRowNumber)->getAlignment()->setWrapText(true);
            }
        }

        // Set text wrap for all cells in the data area
        $dataRows = $rowNumber - 7; // Total number of data rows
        for ($col = 'A'; $col <= 'I'; $col++){
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
        $sheet->getStyle('A6:I' . $rowNumber)->applyFromArray($styleArray);

        // Set landscape mode
        $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

        // Set print area to fit all columns on one page
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="exam_details_internal.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Create Excel writer
        $writer = new Xlsx($spreadsheet);

        // Save the Excel file to output
        $writer->save('php://output');
        exit;
    }
}
?>
