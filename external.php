<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>External Details Submission</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        body {
            background-image: url("image/re.jpg");
            background-repeat: no-repeat;
            background-size: 100vw 100vh;
            padding-bottom: 40px;
        }
        .aform {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            color: white;
        }
        .alog {
            width: 150px;    
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h2 class="text-center text-white mt-1 py-3" style="background-color:rgba(0, 0, 0, 0.5);">Automatic Renumeration System</h2>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form action="" method="post" class="aform">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <h4 class="text-center">External Details</h4>
                    <hr>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>External Name:</label>
                            <input type="text" class="form-control" name="ex_name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Designation:</label>
                            <select class="form-control" name="ex_designation" required>
                                <option value="">Select Designation</option>
                                <option value="PROFESSOR">PROFESSOR</option>
                                <option value="ASSISTANT PROFESSOR">ASSISTANT PROFESSOR</option>
                                <option value="ASSOCIATE PROFESSOR">ASSOCIATE PROFESSOR</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <div class="form-group col-md-6">
                            <label>College Name:</label>
                            <select class="form-control select2-college" name="ex_college" required>
                                <option value="">Select College Name</option>
                                <?php
                                $collegeNames = [
                                    "VTHT" => "Vel Tech High Tech Dr. Rangarajan Dr. Sakunthala Engineering College",
                                    "PEC" => "Panimalar Engineering College",
                                    "SAEC" => "S.A Engineering College",
                                    "VEC"=>"Velammal Engineering College",
                                    "VTMT" => "Vel Tech Multi Tech Dr. Rangarajan Dr. Sakunthala Engineering College"
                                ];
                                foreach ($collegeNames as $shortName => $fullName) {
                                    echo "<option value=\"$shortName\">$fullName</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Account Holder Name:</label>
                            <input type="text" class="form-control" name="ex_acc_name" required>
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <div class="form-group col-md-6">
                            <label>Account Number:</label>
                            <input type="text" class="form-control" name="ex_acc_no" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Bank Name:</label>
                            <select class="form-control select2-bank" id="bankSelect" name="ex_bank_name" required>
                                <option value="">Select Bank Name</option>
                                <?php
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
                                foreach ($bankNames as $bankName) {
                                    echo "<option value=\"$bankName\">$bankName</option>";
                                }
                                ?>
                                <option value="Other">Other</option> <!-- Add the "Other" option -->
                            </select>
                        </div>
                    </div>
                    <div id="otherBankField" style="display: none;"> <!-- Hidden input field for manual input -->
                        <div class="form-group col-md-6">
                            <label>Other Bank Name:</label>
                            <input type="text" class="form-control" id="otherBankInput" name="other_bank_name">
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <div class="form-group col-md-6">
                            <label>Branch:</label>
                            <input type="text" class="form-control" name="ex_branch" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>IFSC Code:</label>
                            <input type="text" class="form-control" name="ex_ifsc" required>
                        </div>
                    </div>
                    <center>
                        <input type="submit" id="submitBtn" name="form" class="btn btn-danger alog mr-2" value="Submit">
                    </center>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2-college').select2({
                placeholder: 'Search for a college name',
                allowClear: true
            });

            $('.select2-bank').select2({
                placeholder: 'Search for a bank name',
                allowClear: true
            });

            $('#bankSelect').change(function() {
                if ($(this).val() === 'Other') {
                    $('#otherBankField').show();
                } else {
                    $('#otherBankField').hide();
                }
            });
        });
    </script>
</body>
</html>

<?php
include("db.php");
$id = $_GET["id"] ?? null;
$ex_num = $_GET["ex_num"] ?? null;
$lab_category = $_GET["lab_category"] ?? null;

if ($ex_num === null) {
    echo "Error: phone number is missing.";
    exit();
}

// submission process
if (isset($_POST["form"])) {
    $ex_name = $_POST["ex_name"];
    $ex_designation = $_POST["ex_designation"];
    $ex_college = $_POST["ex_college"];
    $ex_acc_name = $_POST["ex_acc_name"];
    $ex_acc_no = $_POST["ex_acc_no"];
    $ex_branch = $_POST["ex_branch"];
    $ex_ifsc = $_POST["ex_ifsc"];

    // Determine the bank name
    $ex_bank_name = $_POST["ex_bank_name"];
    if ($ex_bank_name === "Other") {
        $other_bank_name = $_POST["other_bank_name"];
        $bank_name = $other_bank_name;
    } else {
        $bank_name = $ex_bank_name;
    }

    // Insert data into the database
    $sql_externals = "INSERT INTO externals (mob_no, staff_name, designation, clg_name, acc_name, acc_no, bank_name, branch, ifsc) 
    VALUES ('$ex_num', '$ex_name', '$ex_designation', '$ex_college', '$ex_acc_name', '$ex_acc_no', '$bank_name', '$ex_branch', '$ex_ifsc')";

    if (mysqli_query($conn, $sql_externals)) {
        // Update exam table
        $sql_exam = "UPDATE exam SET 
            ex_name = '$ex_name', 
            ex_designation = '$ex_designation', 
            ex_college = '$ex_college', 
            ex_acc_name = '$ex_acc_name', 
            ex_acc_no = '$ex_acc_no', 
            ex_bank_name = '$bank_name', 
            ex_branch = '$ex_branch', 
            ex_ifsc = '$ex_ifsc' 
            WHERE ex_num = '$ex_num'";

        if (mysqli_query($conn, $sql_exam)) {
            // Redirect to some page after successful submission
            echo "<script>window.location.replace('ex_bank.php?ex_num=$ex_num&id=$id&lab_category=$lab_category');</script>";
            exit(); // Make sure to exit after redirection
        } else {
            echo "Error updating exam table: " . mysqli_error($conn);
        }
    } else {
        echo "Error updating externals table: " . mysqli_error($conn);
    }
}
?>
