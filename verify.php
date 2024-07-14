<?php

include("db.php");

$id = $_GET["id"] ?? null;

// Fetching exam details based on the provided ID
$sql = "SELECT * FROM exam WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if a record exists with the provided ID
if ($row = mysqli_fetch_assoc($result)) {
    // Assign fetched values to variables
    $date = $row['date'];
    $ex_session = $row['ex_session'];
    $department = $row['department'];
    $semester = $row['semester'];
    $sub_name = $row['sub_name'];
    $sub_code = $row['sub_code'];
    $students = $row['students'];
    $student_reg = $row['student_reg'];
    $lab_category = $row['lab_category'];
    $batch_no = $row['batch_no'];
}


// Submission process
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Retrieve form data
    if (isset($_POST["confirm"])) {
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
    $sk_renum = null;
    $lab_renum = null;
    $ex_renum = $batch_no * 100; 
    $ox_s = null; // Calculate ex_renum for project category
} else {
    if ($students < 4) {
        $in_renum = 100;
        $ex_renum = 100;
        $lab_renum = $students*2;
        $sk_renum=75;
        $ox_s = 100;
    } else if ($students < 7) {
        $in_renum = $students * 25;
        $ex_renum = $students * 25;
        $lab_renum = $students * 2;
        $sk_renum = 75;
        $ox_s = 100;
    } else if ($students < 10) {
        $ox_s = 100;
        $in_renum = $students * 25;
        $ex_renum = $students * 25;
        $lab_renum = $students * 2;
        $sk_renum=$students*12;

    } else {
        $in_renum = $students * 25;
        $ex_renum = $students * 25;
        $sk_renum = $students * 12;
        $lab_renum = $students * 2;
        $ox_s = $students * 10;
    }
    // Calculate ox_s for non-project category
    
    // Calculate ox_s for non-project category
}


// Setting totals
$in_tot = $in_renum;
$sk_tot = $sk_renum;
$lab_tot = $lab_renum;

// Calculating ex_tot
$ex_lump = ($ex_session === 'Both') ? 550 : 500;
$ex_tot = $ex_renum + $ex_lump;
// Construct the SQL query
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
    echo "<script>window.location.replace('fac.php?id=$id&lab_category=$lab_category');</script>";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

    }
elseif (isset($_POST["modify"])) {
    echo "<script>window.location.replace('index_edit.php?id=$id');</script>";
    exit();
}
}

?>


<!-- Your HTML content for verification page goes here -->

<!-- Your HTML content for verification page goes here -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>

body {
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
    padding: 20px;
    font-size: 16px;
}

.container,.entered-details {
    max-width: 800px;
    margin: 20px auto;
    background: #e8f0fe;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
h2  {
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}
.entered-details p {
font-size: 16px;
color: #333;
line-height: 1.5;
}

.buttons-container, form {
    display: flex;
    justify-content: center; /* Center-aligns the buttons within the container */
    gap: 10px; /* Adds space between the buttons */
    margin-top: 20px;
}
input[type="submit"], input[type="button"] {
    padding: 10px 20px;
    color: white;
/* Primary button color */
    border: none;
    border-radius: 3px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
input[type="submit"] {
    background-color: #5cb85c;
}
input[type="button"]{
    background-color: #f0ad4e; /* Orange color for a warning button */
border-color: #eea236;
}
input[type="submit"]:focus, input[type="button"]:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(0,123,255,.5);
}
@media (max-width: 768px) {
    .container, .entered-details {
        width: 95%;
        margin: 10px auto;
    }
    input[type="submit"], input[type="button"] {
        padding: 8px 15px;
        font-size: 14px;
    }
}
</style>
</head>
<body>
    <div class='col-md-6 offset-md-3'>
        <div class='entered-details'>
            <h2>Entered Details:</h2>
            <p><strong>Date</strong>: <?php echo htmlspecialchars($date); ?></p>
            <p><strong>Session</strong>: <?php echo htmlspecialchars($ex_session); ?></p>
            <p><strong>Department</strong>: <?php echo htmlspecialchars($department); ?></p>
            <p><strong>Semester</strong>: <?php echo htmlspecialchars($semester); ?></p>
            <p><strong>Subject Name</strong>: <?php echo htmlspecialchars($sub_name); ?></p>
            <p><strong>Subject Code</strong>: <?php echo htmlspecialchars($sub_code); ?></p>
            <p><strong>Students Appeared</strong>: <?php echo htmlspecialchars($students); ?></p>
            <p><strong>Students Registered</strong>: <?php echo htmlspecialchars($student_reg); ?></p>
            <p><strong>Lab Category</strong>: <?php echo htmlspecialchars($lab_category); ?></p>
            <?php if ($lab_category === 'project') : ?>
                <p><strong>Batch Number</strong>: <?php echo htmlspecialchars($batch_no); ?></p>
            <?php endif; ?>
        </div>

        <?php
        echo '<form method="post">';
        echo '<input type="hidden" name="date" value="' . htmlspecialchars($date) . '">';
        // Include other form fields similarly...
        echo '<input type="hidden" name="ex_session" value="' . htmlspecialchars($ex_session) . '">';
        echo '<input type="hidden" name="department" value="' . htmlspecialchars($department) . '">';
        echo '<input type="hidden" name="semester" value="' . htmlspecialchars($semester) . '">';
        echo '<input type="hidden" name="sub_name" value="' . htmlspecialchars($sub_name) . '">';
        echo '<input type="hidden" name="sub_code" value="' . htmlspecialchars($sub_code) . '">';
        echo '<input type="hidden" name="students" value="' . htmlspecialchars($students) . '">';
        echo '<input type="hidden" name="student_reg" value="' . htmlspecialchars($student_reg) . '">';
        echo '<input type="hidden" name="lab_category" value="' . htmlspecialchars($lab_category) . '">';
        if ($lab_category === 'project') {
            echo '<input type="hidden" name="batch_no" value="' . htmlspecialchars($batch_no) . '">';
        }
        echo '<input type="submit" name="confirm" value="Confirm">';
        echo '<input type="submit" value="Modify" name="modify">';

        // JavaScript function to go back
        echo '</form>';
        ?>
    </div>
</body>

</html>