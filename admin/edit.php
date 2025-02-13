<?php
include("db.php");
$id = $_GET["id"] ?? null;

$sql = "SELECT * FROM exam WHERE id = $id";
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
            background-image: url("../image/re.jpg"); 
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
                    <h4 class="text-center text-white" style="border-bottom: 1px solid #ffffff;padding-bottom:10px;">Exam Details</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="" class="text-white" style="font-size: 18px;font-weight:500;">Exam Date</label>
                            <input type="date" class="form-control" name="date" required id="examDate" value="<?= htmlspecialchars($row["date"]) ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="select1" class="text-white" style="font-size: 18px;font-weight:500;">Session</label>
                            <select class="form-control" id="select1" name="ex_session" required >
                            <option value="">Choose session</option>
                            <option value="Forenoon" <?= ($row["ex_session"] === "Forenoon") ? "selected" : "" ?>>Forenoon</option>
                            <option value="Afternoon" <?= ($row["ex_session"] === "Afternoon") ? "selected" : "" ?>>Afternoon</option>
                            <option value="Both" <?= ($row["ex_session"] === "Both") ? "selected" : "" ?>>Both</option>
                            </select>
                        </div>
                    </div>
                    <!-- ------------------------------------------------------- -->
                    <div class="form-row mt-2">
                        <div class="form-group col-md-6">
                            <label for="select2" class="text-white" style="font-size: 18px;font-weight:500;">Department</label>
                            <select class="form-control" id="department" name="department" onchange="fun2()" required>
                                <option value="">Choose Department</option>
                                <option value="CSE" <?= ($row["department"] === "CSE") ? "selected" : "" ?>>CSE</option>
                                <option value="AI-DS" <?= ($row["department"] === "AI-DS") ? "selected" : "" ?>>AI-DS</option>
                                <option value="CSE(CS)" <?= ($row["department"] === "CSE(CS)") ? "selected" : "" ?>>CSE(CS)</option>
                                <option value="ECE" <?= ($row["department"] === "ECE") ? "selected" : "" ?>>ECE</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="select1" class="text-white" style="font-size: 18px;font-weight:500;">Semester</label>
                            <select class="form-control" id="semester" onchange="fun(this.value)" name="semester" required>
                                <option value="">Choose Semester</option>
                                <option value="4" <?= ($row["semester"] === "3") ? "selected" : "" ?>>III Sem</option>
                                <option value="6" <?= ($row["semester"] === "5") ? "selected" : "" ?>>V Sem</option>
                                <option value="8" <?= ($row["semester"] === "7") ? "selected" : "" ?>>VII Sem</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- ------------------------------------------------------- -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="select2" class="text-white" style="font-size: 18px;font-weight:500;">Subject Name & Code</label>
                            <select class="form-control" id="subject" name="sub_name" onchange="fun3(this.value)" required>
                            <option value="<?= htmlspecialchars($row["sub_name"]) ?>" selected><?= htmlspecialchars($row["sub_name"]) ?></option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="select1" class="text-white" style="font-size: 18px;font-weight:500;">Subject Code</label>
                            <input class="form-control" id="code" name="sub_code" readonly required placeholder="Select a Subject Name" value="<?= htmlspecialchars($row["sub_code"]) ?>">
                        </div>
                    </div>
                    <!-- ------------------------------------------------------- -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="select1" class="text-white" style="font-size: 18px;font-weight:500;">Students Registered</label>
                            <input type="number" name="student_reg" class="form-control"  value="<?= $row["student_reg"] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="select1" class="text-white" style="font-size: 18px;font-weight:500;">Students Appeared</label>
                            <input type="number" name="students" class="form-control" value="<?= $row["students"] ?>">
                        </div>
                    </div>
                    <!----------- ------------------------------------->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="labCategory" class="text-white" style="font-size: 18px;font-weight:500;">Lab Category</label>
                            <select class="form-control" id="lab_category" name="lab_category" required>
                                <option value="">Choose Lab Category</option>
                                <option value="regular" <?= ($row["lab_category"] === "regular") ? "selected" : "" ?>>Regular</option>
                                <option value="project" <?= ($row["lab_category"] === "project") ? "selected" : "" ?>>Project</option>
                            </select>
                            </div>
                            <div class="form-group col-md-6">
                        <label for="select1" class="text-white" style="font-size: 18px;font-weight:500;">No.of batches </label>
                            <input type="text" id="batch_no" class="form-control" name="batch_no"  value="<?= $row["batch_no"] ?>">
                        </div>
    </div>
                        <center>
                        <input type="submit" id="submitBtn" name="form" class="btn btn-danger alog mr-2" value="Submit">
                       <!-- <a href="../index.php" class="btn btn-warning text-white">Back</a>-->
                    </center>
                    
                </form>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST["form"])) {
    // Retrieve form data
    $date = $_POST["date"];
    $ex_session = $_POST["ex_session"];
    $department = $_POST["department"];
    $semester = $_POST["semester"];
    $sub_name = $_POST["sub_name"];
    $sub_code = $_POST["sub_code"];
    $students = $_POST["students"];
    $student_reg = $_POST["student_reg"];
    $lab_category = $_POST["lab_category"];
    $batch_no = isset($_POST["batch_no"]) ? $_POST["batch_no"] : null;

    // Adjusting renum values based on lab_category
   // Adjusting renum values based on lab_category
if ($lab_category === 'project') {
    $in_renum = $batch_no * 100;
    $sk_renum =null;
    $lab_renum = null;
    $ox_s=null;
    $ex_renum = $batch_no * 100; // Calculate ex_renum for project category
} else {
    $in_renum = $students * 25;
    $sk_renum = $students * 25;
    $lab_renum = $students * 2;
    $ex_renum = $students * 25; // Calculate ex_renum for non-project category
    $ox_s=$students*10;
}

// Setting totals
$in_tot = $in_renum;
$sk_tot = $sk_renum;
$lab_tot = $lab_renum;

// Calculating ex_tot
$ex_lump = ($ex_session === 'Both') ? 550 : 500;
$ex_tot = $ex_renum + $ex_lump;
// Prepare the SQL statement
$sql = "UPDATE exam SET 
        date = '$date',
        ex_session = '$ex_session',
        department = '$department',
        semester = '$semester',
        sub_name = '$sub_name',
        sub_code = '$sub_code',
        students = '$students',
        student_reg = '$student_reg',
        ex_lump = '$ex_lump',
        ox_s = '$ox_s',
        ex_tot = '$ex_tot',
        in_tot = '$in_tot',
        in_renum = '$in_renum',
        lab_renum = '$lab_renum',
        lab_tot = '$lab_tot',
        sk_renum = '$sk_renum',
        sk_tot = '$sk_tot',
        lab_category = '$lab_category',
        batch_no = '$batch_no',
        ex_renum = '$ex_renum'
        WHERE id = '$id'";

if (mysqli_query($conn, $sql)){
    echo '<script>alert("Exam Details Modified Succesfully");window.location.replace("dash.php");</script>';
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
    }
?>
    <script>
    $(document).ready(function(){
        $('select[name="lab_category"]').change(function(){
            togglelab_categoryField();
        });

        function togglelab_categoryField() {
            var selectedOption = $('select[name="lab_category"]').val();
            if(selectedOption === 'project') {
                $('#batch_no').closest('.form-group').show();
            } else {
                $('#batch_no').closest('.form-group').hide();
            }
        }

        // Call the function initially to set the visibility of the field based on the initial value
        togglelab_categoryField();
    });
</script>
<script>
    $(document).ready(function(){
    $('form.aform').submit(function(event) {
        var registered = parseInt($('input[name="student_reg"]').val());
        var appeared = parseInt($('input[name="students"]').val());

        if (appeared > registered) {
            event.preventDefault(); // Prevent form submission
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Students Appeared must be less than or equal to Students Registered!',
            });
        }
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        document.getElementById('examDate').setAttribute('value', today);
    });
</script>

<script>
        function fun3(b)
        {
            const xhttp=new XMLHttpRequest();
            xhttp.onload=function(){
                                //alert(this.responseText);
            document.getElementById("code").value=this.responseText;
            }
            xhttp.open("GET","ajax2.php?subject="+b,true);
            xhttp.send();
        }
        function fun2()
        {
            var g=`
            <option value="">Choose Semester</option>
            <option value="3">III Sem</option>
            <option value="5">V Sem</option>
            <option value="7">VII Sem</option>
            `;
            document.getElementById("semester").innerHTML=g;
        }
                        function fun(a)
                        {
                            dep=document.getElementById("department").value;
                            const xhttp=new XMLHttpRequest();
                            xhttp.onload=function(){
                                //alert(this.responseText);
                               document.getElementById("subject").innerHTML=this.responseText;
                            }
                            xhttp.open("GET","ajax.php?sem="+a+"&dep="+dep,true);
                            xhttp.send();
                        }
    </script>
</body>
</html>
