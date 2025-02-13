<?php
session_start();
if (!isset($_SESSION["id"])) {
    echo '<script>window.location.replace("index.php");</script>';
}
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automatic Renumeration System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-image: url("bg.webp");
            background-repeat: no-repeat;
            background-size: 100% 100%;
            height: 100vh;
            overflow-y: scroll;
            overflow-x: hidden;
        }
    </style>
</head>
<body>
<?php include("header.php"); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12  mt-1">
            <div class="card">
                <div class="card-body">
                    <form action="" method="post" class="aform" autocomplete="off">
                        <h4 class="text-primary text-center mt-2">Exam details</h4>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="" class="text-white" style="font-size: 18px;font-weight:500;">Exam Date</label>
                                <input type="date" class="form-control" name="date" required id="examDate">
                            </div>
                            <div class="form-group col-md-4">
                            <label for="labCategory" class="text-white" style="font-size: 18px;font-weight:500;">Lab Category</label>
                            <select class="form-control" id="lab_category" name="lab_category" required>
                                <option value="">Choose Lab Category</option>
                                <option value="regular">Regular</option>
                                <option value="project">Project</option>
                            </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="submit" name="submit" value="Search" class="mx-auto d-block btn btn-primary px-4 mt-3">
                        </div>
                    </form>
                    <div class="col-md-12">
                        <?php
                        if (isset($_POST["submit"])) {
                            $date = $_POST["date"];
                            $lab_category = $_POST["lab_category"];
                            $sql = "SELECT * FROM exam WHERE date='$date' AND lab_category='$lab_category'";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                ?>
                                <table class="table table-bordered mt-2 table-sm">
                                    <thead>
                                    <tr>
                                        <th colspan="11">Exam Date: <?= $date ?></th>
                                    </tr>
                                    <tr>
                                        <th>Subject code</th>
                                        <th>Department</th>  
                                        <th>No of candidates Regd</th>
                                        <th>No of candidates examined</th>
                                        <?php if ($lab_category === 'project') { ?>
                                            <th>No of batches</th>
                                        <?php } ?>
                                        <th>View</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td><?= $row["sub_code"] ?></td>
                                            <td><?= $row["department"] ?></td>
                                            <td><?= $row["student_reg"] ?></td>
                                            <td><?= $row["students"] ?></td>
                                            <?php if ($lab_category === 'project') { ?>
                                                <td><?= $row["batch_no"] ?></td>
                                            <?php } ?>
                                            <td><a href="exam_details.php?date=<?=$row['date']?>&sub_code=<?= $row['sub_code'] ?>&department=<?= $row['department'] ?>&lab_category=<?= $row['lab_category'] ?>">View</a></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <!-- View All Details Button -->
                                <div class="text-center">
                                    <a href="view_details.php?date=<?= $date ?>&lab_category=<?= $lab_category ?>" class="btn btn-primary">View All Details</a>
                                </div>
                                <?php
                            } else {
                                echo "<p>No records found for the selected date.</p>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
</body>
</html>
