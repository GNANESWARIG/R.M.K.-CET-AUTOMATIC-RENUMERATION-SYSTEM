<?php
include("db.php");
$facultyId = $_GET["facultyId"] ?? null;
$facultyType = $_GET["facultyType"] ?? null; 
$id = $_GET["id"] ?? null; 
$lab_category = $_GET["lab_category"]??null;
if ($facultyId === null) {
    echo "Error: Faculty ID is missing.";
    exit();
}
$sql = "SELECT * FROM internals WHERE faculty_Id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $facultyId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automatic Renumeration System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        body {
            background-repeat: no-repeat;
            background-size: 100vw 100%;
            padding-bottom: 40px;
        }

        .aform {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
        }

        .alog {
            width: 100px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3 mt-4">
                <form action="" method="post" class="aform">
                    <h4 class="text-center text-white" style="border-bottom: 1px solid #ffffff;padding-bottom:10px;">Bank Details</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Account Holder Name:</label>
                            <input type="text" class="form-control" name="acc_name" value="<?= $row["acc_name"] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Account Number:</label>
                            <input type="text" class="form-control" name="acc_no" value="<?= $row["acc_no"] ?>">
                        </div>
    </div>
                    <center>
                        <input type="submit" name="form" class="btn btn-danger alog mr-2" value="Edit">
                    </center>
                </form>
            </div>
        </div>
    </div>
    <?php
    // Form submission process
    if (isset($_POST["form"])) {
        $acc_name = $_POST["acc_name"];
        $acc_no = $_POST["acc_no"];
    

        // Update bank details in the internals table
        $sql_internals = "UPDATE internals SET acc_name = '$acc_name', acc_no = '$acc_no' WHERE faculty_Id = '$facultyId'";

        $sql_exam = "";

        // Update the exam table based on faculty type
        switch ($facultyType) {
            case 'internal':
                $sql_exam = "UPDATE exam SET in_acc_name = '$acc_name', in_acc_no = '$acc_no' WHERE in_Id = '$facultyId'";
                break;
            case 'skilled_assistent':
                $sql_exam = "UPDATE exam SET sk_acc_name = '$acc_name', sk_acc_no = '$acc_no' WHERE sk_Id = '$facultyId'";
                break;
            case 'lab_technician':
                $sql_exam = "UPDATE exam SET lab_acc_name = '$acc_name', lab_acc_no = '$acc_no'WHERE lab_Id = '$facultyId'";
                break;
            default:
                // Handle other cases if needed
                break;
        }
        if (mysqli_query($conn, $sql_internals) && mysqli_query($conn, $sql_exam)) {
            echo "<script>window.location.replace('bank.php?facultyId=$facultyId&facultyType=$facultyType&id=$id&lab_category=$lab_category');</script>";
        } else {
            echo "<script>alert('Error updating bank details: " . mysqli_error($conn) . "');window.location.replace('bank_edit.php?facultyId=$facultyId&facultyType=$facultyType&id=$id&lab_category=$lab_category ');</script>";
        }
    }
    ?>
</body>
</html>
