<?php
session_start();
include("db.php"); 
$id = $_GET["id"];
$lab_category = $_GET["lab_category"];
    // Your existing code for handling form submission goes here
if (isset($_POST["form"])) {
    $faculty = $_POST["faculty"] ?? ''; // Retrieve the selected faculty type

    // Handle form submission based on the selected faculty type
    switch ($faculty) {
        case 'internal':
        case 'lab_technician':
        case 'skilled_assistent':
            $facultyId = "";
            switch ($faculty) {
                case 'internal':
                    
                    $facultyId = isset($_POST["facultyId"]) ? $_POST["facultyId"] : '';
                   
                    $fieldToUpdate = 'in_Id';
                    break;
                case 'lab_technician':
                    
                    $facultyId = isset($_POST["facultyId"]) ? $_POST["facultyId"] : '';
                   
                    $fieldToUpdate = 'lab_Id';
                    break;
                case 'skilled_assistent':
                    
                    $facultyId = isset($_POST["facultyId"]) ? $_POST["facultyId"] : '';
                   
                    $fieldToUpdate = 'sk_Id';
                    break;
            }

            // Update the exam table with facultyId and session
            // Update the exam table with facultyId and session
$sql = "UPDATE exam SET $fieldToUpdate = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
// Use "ss" instead of "ssi" if $fieldToUpdate is a string
mysqli_stmt_bind_param($stmt, "si", $facultyId, $id);

            if (mysqli_stmt_execute($stmt)) {
                // Pass faculty type to bank.php along with facultyId
                echo "<script>window.location.replace('bank.php?id=$id&facultyId=$facultyId&facultyType=$faculty&lab_category=$lab_category');</script>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
            exit();

            case 'external':
                $ex_num = isset($_POST["ex_num"]) ? $_POST["ex_num"] : '';
    
                // Update the exam table with ex_num and ex_session
                // Update the exam table with ex_num and session
$sql = "UPDATE exam SET ex_num = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
// Use "si" instead of "ssi"
mysqli_stmt_bind_param($stmt, "si", $ex_num, $id);

    
                if (mysqli_stmt_execute($stmt)) {
                    // Check if details exist in externals table
                   // Check if details exist in externals table
                    $checkExNumSql = "SELECT * FROM externals WHERE mob_no = ?";
                    $checkExNumStmt = mysqli_prepare($conn, $checkExNumSql);
                    mysqli_stmt_bind_param($checkExNumStmt, "s", $ex_num);
                    mysqli_stmt_execute($checkExNumStmt);
                    mysqli_stmt_store_result($checkExNumStmt);

                    if (mysqli_stmt_num_rows($checkExNumStmt) > 0) {
                        echo  "<script>window.location.replace('ex_bank.php?ex_num=$ex_num&id=$id&lab_category=$lab_category');</script>";
                    } else {
                        echo  "<script>window.location.replace('external.php?ex_num=$ex_num&id=$id&lab_category=$lab_category');</script>";
                    }

                    mysqli_stmt_close($checkExNumStmt);

                } else {
                    echo "Error: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
                exit();

        default:
            // Handle other cases if needed
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automatic Renumeration System</title>

    <!-- bootstrap 4 cdn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- style CSS -->
    <style>
        body{
            background-image: url("image/re.jpg");
            background-repeat: no-repeat;
            background-size: 100vw 100vh;
        }
        .aform{
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
        }
        .alog{
            width: 150px;    
        } 
    </style>
 
</head>
<body>
    <div class="container-fluid">
        <h2 class="text-center text-white mt-3 py-3" style="background-color:rgba(0, 0, 0, 0.5);color:#000000;border-radius:30px;">Automatic Renumeration System </h2>
        <div class="row">
        <div class="col-md-4 offset-md-4 mt-5">
                <form action=" " method="post" class="aform" autocomplete="off">
                    <h4 class="text-center text-white" style="border-bottom: 1px solid #ffffff;padding-bottom:10px;">faculty Details</h4>
                        <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="select1" class="text-white" style="font-size: 18px;font-weight:500;">Faculty</label>
                            <select class="form-control" id="select1" name="faculty" required>
                            <option value="">Choose Faculty type</option>
                            <?php
                            if ($lab_category === 'project') {
                                // Display only 'Internal' and 'External' options for project lab_category
                                echo '<option value="internal">Internal</option>';
                                echo '<option value="external">External</option>';
                            } else {
                                // Display all options for other lab_category types
                                echo '<option value="internal">Internal</option>';
                                echo '<option value="external">External</option>';
                                echo '<option value="skilled_assistent">Skilled Assistent</option>';
                                echo '<option value="lab_technician">Lab Technician</option>';
                            }
                            ?>
                            </select>
                            </div>
                        <div class="form-group col-md-12">
                        <label for="select1" class="text-white" style="font-size: 18px;font-weight:500;">Faculty ID</label>>
                            <input type="text" id="facultyId" class="form-control" name="facultyId" >
                        </div>
                        <div class="form-group col-md-12">
                        <label for="select1" class="text-white" style="font-size: 18px;font-weight:500;">PhoneNumber </label>>
                            <input type="text" id="ex_num" class="form-control" name="ex_num" >
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
    $(document).ready(function(){
        $('select[name="faculty"]').change(function(){
            toggleFacultyIdField();
        });

        function toggleFacultyIdField() {
            var selectedOption = $('select[name="faculty"]').val();
            if(selectedOption === 'internal' || selectedOption === 'skilled_assistent' || selectedOption === 'lab_technician') {
                $('#facultyId').closest('.form-group').show();
            } else {
                $('#facultyId').closest('.form-group').hide();
            }
        }

        // Call the function initially to set the visibility of the field based on the initial value
        toggleFacultyIdField();
    });
</script>

<script>
    $(document).ready(function(){
        $('select[name="faculty"]').change(function(){
            toggleFacultyEx_numField();
        });

        function toggleFacultyEx_numField() {
            var selectedOption = $('select[name="faculty"]').val();
            if(selectedOption === 'external') {
                $('#ex_num').closest('.form-group').show();
            } else {
                $('#ex_num').closest('.form-group').hide();
            }
        }

        // Call the function initially to set the visibility of the field based on the initial value
        toggleFacultyEx_numField();
    });
</script>
</body>
</html>
