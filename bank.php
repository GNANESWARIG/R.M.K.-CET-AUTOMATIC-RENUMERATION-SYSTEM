<?php
session_start();
include("db.php");

$facultyId = $_GET["facultyId"] ?? null;
$facultyType = $_GET["facultyType"] ?? null; // Retrieve faculty type from fac.php
$id = $_GET["id"] ?? null;
$lab_category = $_GET["lab_category"] ?? null;
$bankDetails = [];

if (!empty($facultyId)) { 
    $query = "SELECT * FROM internals WHERE faculty_Id = '$facultyId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $bankDetails = mysqli_fetch_assoc($result);
    } else {
        // Show alert if no facultyId is found in the database
        echo "<script>alert('This facultyId is not available in the database.'); window.location.href = 'fac.php?id=$id&lab_category=$lab_category';</script>";
        exit();
    }
} else {
    echo "Error: facultyId parameter is missing in the URL.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    if (isset($_POST["confirm"])) {
        $staff_name = $_POST["staff_name"] ?? '';
        $designation = $_POST["designation"] ?? '';
        $acc_name = $_POST["acc_name"] ?? '';
        $acc_no = $_POST["acc_no"] ?? '';

        // Update bank details in the internals table
        $sql_internals = "UPDATE internals SET acc_name = '$acc_name', acc_no = '$acc_no', staff_name = '$staff_name', designation = '$designation' WHERE faculty_Id = '$facultyId'";

        // Prepare and execute the SQL statement
        if (mysqli_query($conn, $sql_internals)) {
            // Update bank details in the exam table based on faculty type
            $sql_exam = "";

            // Construct the SQL query based on faculty type
            switch ($facultyType) {
                case 'internal':
                    $sql_exam = "UPDATE exam SET in_acc_name=?, in_acc_no=?, in_name=?, in_designation=?, in_college=? WHERE id=?";
                    break;
                case 'lab_technician':
                    $sql_exam = "UPDATE exam SET lab_acc_name=?, lab_acc_no=?, lab_name=?, lab_designation=?, lab_college=? WHERE id=?";
                    break;
                case 'skilled_assistent':
                    $sql_exam = "UPDATE exam SET sk_acc_name=?, sk_acc_no=?, sk_name=?, sk_designation=?, sk_college=? WHERE id=?";
                    break;
            }

            // Prepare and execute the SQL statement
            $stmt_exam = mysqli_prepare($conn, $sql_exam);
            $college = "RMKCET";
            mysqli_stmt_bind_param($stmt_exam, "sssssi", $acc_name, $acc_no, $staff_name, $designation, $college, $id);

            if (mysqli_stmt_execute($stmt_exam)) {
                if ($lab_category === 'regular') {
                    // Check if any fields are empty for regular category
                    $sql_check_fields = "SELECT * FROM exam WHERE id = '$id' AND (in_Id = '' OR lab_Id = '' OR sk_Id = '' OR ex_num = '')";
                } elseif ($lab_category === 'project') {
                    // Check if any fields are empty for project category
                    $sql_check_fields = "SELECT * FROM exam WHERE id = '$id' AND (in_Id = '' OR ex_num = '')";
                }
                $result_check_fields = mysqli_query($conn, $sql_check_fields);

                if ($result_check_fields && mysqli_num_rows($result_check_fields) > 0) {
                    // If any of the fields are empty, navigate to fac.php
                    echo "<script>alert('Your Details are saved successfully!');window.location.replace('fac.php?id=$id&lab_category=$lab_category');</script>";
                    exit();
                } else {
                    // If all fields are not empty, navigate to index.php
                    echo "<script>alert('All faculty Details are saved successfully!');window.location.replace('index.php');</script>";
                    exit();
                }
            } else {
                echo '<script>alert("Error updating bank details!");</script>';
            }
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } elseif (isset($_POST["modify"])) {
        // Redirect to bank_edit.php with facultyId and facultyType parameters
        echo "<script>window.location.replace('bank_edit.php?facultyId=$facultyId&facultyType=$facultyType&id=$id&lab_category=$lab_category');</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank-details Verification Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 16px;
        }

        .container, .bank_details {
            max-width: 800px;
            margin: 20px auto;
            background: #e8f0fe;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .bank_details p {
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
        input[type="button"] {
            background-color: #f0ad4e; /* Orange color for a warning button */
            border-color: #eea236;
        }
        input[type="submit"]:focus, input[type="button"]:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, .5);
        }
        @media (max-width: 768px) {
            .container, .bank_details {
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
    <div class="col-md-6 offset-md-3">
        <div class='bank_details'>
            <?php if (!empty($bankDetails)): ?>
                <h2>Check Your Details:</h2>
                <p><strong>Your Name:</strong> <?php echo htmlspecialchars($bankDetails['staff_name']); ?></p>
                <p><strong>Designation:</strong> <?php echo htmlspecialchars($bankDetails['designation']); ?></p>
                <p><strong>Account Holder Name:</strong> <?php echo htmlspecialchars($bankDetails['acc_name']); ?></p>
                <p><strong>Account Number:</strong> <?php echo htmlspecialchars($bankDetails['acc_no']); ?></p>
            <?php endif; ?>
        </div>
        <form method="post">
            <?php foreach ($bankDetails as $key => $value): ?>
                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>">
            <?php endforeach; ?>
            <input type="submit" name="confirm" value="Confirm">
            <input type="submit" name="modify" value="Modify">
        </form>
    </div>
</body>
</html>
