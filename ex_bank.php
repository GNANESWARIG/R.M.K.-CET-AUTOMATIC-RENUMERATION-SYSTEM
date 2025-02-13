<?php
session_start();
include("db.php");

$id = $_GET["id"] ?? null;
$ex_num = $_GET["ex_num"] ?? null;
$facultyType = $_GET["facultyType"] ?? null;
$lab_category = $_GET["lab_category"] ?? null;
$bankDetails = [];

if (!empty($ex_num)) {
    $query = "SELECT * FROM externals WHERE mob_no = '$ex_num'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $bankDetails = mysqli_fetch_assoc($result);
    }
} else {
    echo "Error: ex_num parameter is missing in the URL.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    if (isset($_POST["confirm"])) {
        // Retrieve bank details from the form
        $staff_name = $_POST["staff_name"] ?? '';
        $designation = $_POST["designation"] ?? '';
        $clg_name = $_POST["clg_name"] ?? '';
        $acc_name = $_POST["acc_name"] ?? '';
        $acc_no = $_POST["acc_no"] ?? '';
        $bank_name = $_POST["bank_name"] ?? '';
        $branch = $_POST["branch"] ?? '';
        $ifsc = $_POST["ifsc"] ?? '';

        // Update bank details in the externals table
        $sql_externals = "UPDATE externals SET staff_name=?, designation=?, clg_name=?, acc_name=?, acc_no=?, bank_name=?, branch=?, ifsc=? WHERE mob_no=?";
        $stmt_externals = mysqli_prepare($conn, $sql_externals);
        mysqli_stmt_bind_param($stmt_externals, "ssssssssi", $staff_name, $designation, $clg_name, $acc_name, $acc_no, $bank_name, $branch, $ifsc, $ex_num);
        if (!mysqli_stmt_execute($stmt_externals)) {
            echo '<script>alert("Error updating externals table!");</script>';
            exit();
        }
        mysqli_stmt_close($stmt_externals);

        // Update bank details in the exam table
        $sql_exam = "UPDATE exam SET  ex_acc_name = ?, ex_acc_no = ?, ex_bank_name = ?, ex_branch = ?, ex_ifsc = ?, ex_name = ?, ex_designation = ?, ex_college = ? WHERE ex_num = ?";
        $stmt_exam = mysqli_prepare($conn, $sql_exam);
        mysqli_stmt_bind_param($stmt_exam, "ssssssssi", $acc_name, $acc_no, $bank_name, $branch, $ifsc, $staff_name, $designation, $clg_name, $ex_num);
        if (mysqli_stmt_execute($stmt_exam)) {
            if ($lab_category === 'regular') {
                // Check if any fields are empty for regular category
                $sql_check_fields = "SELECT * FROM exam WHERE id = '$id' AND (in_Id = '' OR lab_Id = '' OR sk_Id = '' OR ex_num = '')";
            } elseif ($lab_category === 'project') {
                // Check if any fields are empty for project category
                $sql_check_fields = "SELECT * FROM exam WHERE id = '$id' AND (in_Id = '' OR ex_num = '')";
            } 
             // Default case if lab_category is neither regular nor project
            

            if (!empty($sql_check_fields)) { // Ensure the query is not empty
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
                echo '<script>alert("Lab category is not defined correctly.");</script>';
            }
        } else {
            echo '<script>alert("Error updating bank details!");</script>';
        }
    } elseif (isset($_POST["modify"])) {
        echo "<script>window.location.replace('ex_bank_edit.php?ex_num=$ex_num&id=$id&facultyType=$facultyType&lab_category=$lab_category');</script>";
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
            justify-content: center;
            /* Center-aligns the buttons within the container */
            gap: 10px;
            /* Adds space between the buttons */
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
            background-color: #f0ad4e;
            /* Orange color for a warning button */
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
            <p><strong>college Name:</strong> <?php echo htmlspecialchars($bankDetails['clg_name']); ?></p>
            <p><strong>Account Holder Name:</strong> <?php echo htmlspecialchars($bankDetails['acc_name']); ?></p>
            <p><strong>Account Number:</strong> <?php echo htmlspecialchars($bankDetails['acc_no']); ?></p>
            <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($bankDetails['bank_name']); ?></p>
            <p><strong>Branch:</strong> <?php echo htmlspecialchars($bankDetails['branch']); ?></p>
            <p><strong>IFSC Code:</strong> <?php echo htmlspecialchars($bankDetails['ifsc']); ?></p>
        <?php endif; ?>
    </div>
    <form method="post">
        <?php foreach ($bankDetails as $key => $value): ?>
            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>">
        <?php endforeach; ?>
        <input type="hidden" name="ex_num" value="<?php echo htmlspecialchars($ex_num); ?>">
        <input type="submit" name="confirm" value="Confirm">
        <input type="submit" name="modify" value="Modify">
    </form>
</div>
</body>
</html>
