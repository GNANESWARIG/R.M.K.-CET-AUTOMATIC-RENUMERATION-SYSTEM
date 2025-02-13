<?php
require 'vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\IOFactory;


?>

<?php
include("db.php");
if(isset($_POST["uploadfile"]))
{
    $target_dir="uploads/";
    $target_file=$target_dir.basename($_FILES["myfile"]["name"]);
    if(strstr($target_file,".xlsx") || strstr($target_file,".xls"))
    {
    if(move_uploaded_file($_FILES["myfile"]["tmp_name"],$target_file))
    {
    
        //echo "<script>alert('file uploaded');</script>";
     
$excelFile = $target_file;

// Load the Excel file
$spreadsheet = IOFactory::load($excelFile);

// Get the first worksheet
$worksheet = $spreadsheet->getActiveSheet();

// Get the highest column and row numbers from the worksheet
$highestColumn = $worksheet->getHighestColumn();
$highestRow = $worksheet->getHighestRow();

// Loop through each row of the worksheet
for ($row = 2; $row <= $highestRow; ++$row) {
    $d=[];
    for ($col = 'A'; $col <= $highestColumn; ++$col) {
        // Get the value in the current cell
        $cellValue = $worksheet->getCell($col . $row)->getValue();
        
        array_push($d,$cellValue);
        
    }
    
    //print_r($d);
    if($d[1]!="")
    {
    $faculty_Id=$d[1];
    $fac_type=$d[2];
    $staff_name=$d[3];
    $designation=$d[4];
    $acc_name=$d[5];
    $acc_no=$d[6];
    $sql="insert into internals values (NULL,'$faculty_Id','$fac_type', '$staff_name', '$designation','$acc_name','$acc_no')";
        if(mysqli_query($conn,$sql))
        {

        }
    }
    echo "<br>";
}

    echo "<script>alert('Internals added successfully');window.location.replace('add_internals.php');</script>";
        //file get end
    }
    }
    else
    {
        echo "<script>alert('Please choose excel file only');window.location.replace('add_internals.php');</script>";
    }
}
?>