<?php
include("db.php");
$ex_num = $_GET["ex_num"] ?? null;
$id = $_GET["id"] ?? null;
$lab_category = $_GET["lab_category"]??null;
$facultyType = $_GET["facultyType"] ?? null; 
if ($ex_num === null) {
    echo "Error: phone number is missing.";
    exit();
}

$sql = "SELECT * FROM externals WHERE mob_no = $ex_num";
$result = mysqli_query($conn, $sql);
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
                    <h4 class="text-center text-white" style="border-bottom: 1px solid #ffffff;padding-bottom:10px;">Your Details</h4>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>External Name:</label>
                        <input type="text" class="form-control" name="staff_name" value="<?= $row["staff_name"] ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Designation:</label>
                        <select class="form-control" name="designation">
    <option value="">Select Designation</option>
    <option value="PROFESSOR" <?= ($row["designation"] === "PROFESSOR") ? "selected" : "" ?>>PROFESSOR</option>
    <option value="ASSISTANT PROFESSOR" <?= ($row["designation"] === "ASSISTANT PROFESSOR") ? "selected" : "" ?>>ASSISTANT PROFESSOR</option>
    <option value="ASSOCIATE PROFESSOR" <?= ($row["designation"] === "ASSOCIATE PROFESSOR") ? "selected" : "" ?>>ASSOCIATE PROFESSOR</option>
</select>
                    </div>
                    </div>
                    <div class="form-row mt-2">
                    <div class="form-group col-md-6">
    <label>college Name:</label>
    <select class="form-control select2-college" name="clg_name" value="<?= $row["clg_name"] ?>">
    <option><?= $row["clg_name"] ?></option>
        <?php
       $collegeNames = [
        "VTHT"=>"Vel Tech High Tech Dr. Rangarajan Dr. Sakunthala Engineering College",
        "PEC"=>"Panimalar Engineering College",
        "SAEC"=>"S.A Engineering College",
        "VEC"=>"Velammal Engineering College",
        "VTMT"=>"Vel Tech Multi Tech Dr. Rangarajan Dr. Sakunthala Engineering College"
      ];
      // Generating options for bank names
                              
                              // Generating options for college names
                              foreach ($collegeNames as $shortName => $fullName) {
                                  echo "<option value=\"$shortName\">$fullName</option>";
                              }
                              ?>
  </select>
</div>
                        <div class="form-group col-md-6">
                            <label>Account Holder Name:</label>
                            <input type="text" class="form-control" name="acc_name" value="<?= $row["acc_name"] ?>">
                        </div>
                    </div>
                        <div class="form-row mt-2">
                        <div class="form-group col-md-6">
                            <label>Account Number:</label>
                            <input type="text" class="form-control" name="acc_no" value="<?= $row["acc_no"] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Bank Name:</label>
                            <select class="form-control select2-bank" name="bank_name" value="<?= $row["bank_name"] ?>">
                                <option><?= $row["bank_name"] ?></option>
                                <?php
                                // List of bank names
                                $bankNames = [
                                    "State Bank of India (SBI)",
                                    "Punjab National Bank (PNB)",
                                    "HDFC Bank",
                                    "ICICI Bank",
                                    "Axis Bank",
                                    "Bank of Baroda (BoB)",
                                    "Canara Bank",
                                    "Union Bank of India",
                                    "Bank of India (BOI)",
                                    "IDBI Bank",
                                    "Indian Bank",
                                    "Central Bank of India",
                                    "Kotak Mahindra Bank",
                                    "Yes Bank",
                                    "IndusInd Bank",
                                    "Federal Bank",
                                    "Karnataka Bank",
                                    "South Indian Bank",
                                    "RBL Bank",
                                    "Punjab & Sind Bank"
                                ];
                                // Generating options for bank names
                                foreach ($bankNames as $bankName) {
                                    echo "<option value=\"$bankName\">$bankName</option>";
                                }
                                ?>
                            </select>
                            <script>
    // Initialize Select2 for the college name dropdown
    $(document).ready(function() {
        $('.select2-college').select2({
            placeholder: 'Search for a college name',
            allowClear: true
        });
        
        // Initialize Select2 for the bank name dropdown
        $('.select2-bank').select2({
            placeholder: 'Search for a bank name',
            allowClear: true
        });
    });
</script>
                        </div>
                    </div>
                        <div class="form-row mt-2">
                        <div class="form-group col-md-6">
                            <label>Branch:</label>
                            <input type="text" class="form-control" name="branch" value="<?= $row["branch"] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label>IFSC Code:</label>
                            <input type="text" class="form-control" name="ifsc" value="<?= $row["ifsc"] ?>">
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
        $staff_name = $_POST["staff_name"];
    $designation = $_POST["designation"];
    $clg_name = $_POST["clg_name"];
        $acc_name = $_POST["acc_name"];
        $acc_no = $_POST["acc_no"];
        $bank_name = $_POST["bank_name"];
        $branch = $_POST["branch"];
        $ifsc = $_POST["ifsc"];

        // Update bank details in the externals table
        $sql_externals = "UPDATE externals SET  staff_name = '$staff_name',designation = '$designation',clg_name = '$clg_name',acc_name = '$acc_name', acc_no = '$acc_no', bank_name = '$bank_name', branch = '$branch', ifsc = '$ifsc' WHERE mob_no = '$ex_num'";

        $sql_exam = "UPDATE exam SET ex_name = '$staff_name',ex_designation = '$designation',ex_college = '$clg_name', ex_acc_name = '$acc_name', ex_acc_no = '$acc_no', ex_bank_name = '$bank_name', ex_branch = '$branch', ex_ifsc = '$ifsc' WHERE ex_num = '$ex_num'";

        if (mysqli_query($conn, $sql_externals) && mysqli_query($conn, $sql_exam)) {
            echo "<script>window.location.replace('ex_bank.php?ex_num=$ex_num&id=$id&lab_category=$lab_category&facultyType=$facultyType');</script>";
        } else {
            echo "<script>alert('Error updating bank details: " . mysqli_error($conn) . "');window.location.replace('ex_bank_edit.php?ex_num=$ex_num');</script>'";
        }
    }
    ?>
</body>
</html>
