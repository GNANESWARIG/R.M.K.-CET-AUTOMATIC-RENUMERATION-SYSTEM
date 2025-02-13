<?php
session_start();
if (!isset($_SESSION["id"])) {
    echo '<script>window.location.replace("index.php");</script>';
}
include("db.php");

function get_designation($type) {
    switch ($type) {
        case 'ex':
            return 'External';
        case 'in':
            return 'Internal';
        case 'lab':
            return 'Lab Technician';
        case 'sk':
            return 'Skilled Assistant';
        case 'ox':
            return 'Other Expenses';
        default:
            return ucfirst($type);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automatic Renumeration System</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <style>
    body {
        background-repeat: no-repeat;
        background-size: 100% 100%;
        height: 100vh;
        overflow-y: auto; /* Allow vertical scrolling */
        overflow-x: hidden; /* Hide horizontal scrolling */
        transform: scale(0.95); /* Zoom out effect */
        transform-origin: top; /* Set the origin of the zoom to the top left corner */
    }

    @media print {
            body {
                margin:0;
                overflow: visible; /* Allow overflow for printing */
                transform: scale(1); /* Reset zoom for printing */
            }
            .table-sm th,
            .table-sm td {
                font-size: 10px; /* Reduce font size for printing */
            }
            @page {
                size: landscape;
                margin: 0; /* Set page to landscape orientation */
            }
            .mydiv{
                display: none;
            }
          
            .page-break {
                page-break-before: always;
            }
        }
</style>

</head>
<body>
    <div class="bg-dark py-2 mydiv">
        <a href="exam.php" class="btn btn-warning ml-2">back</a>
        <button id="convertBtn" class="btn btn-danger float-right mr-2">Save as pdf</button>
        <button id="convertBtn2" class="btn btn-primary float-right mr-2">Print</button>
    </div>
    <div id="myprint" class="mt-3">
        <table width="100%">
            <tr>
                <th style="width:10%">
                    <img src="images/1.png" style="height:120px;">
                </th>
                <td width="80%">
                    <center>
                        <h3>R.M.K COLLEGE OF ENGINEERING AND TECHNOLOGY</h3>
                        <p class="mt-n2"><i>(An Autonomous Institution)</i></p>
                        <h4 class="mt-n2 font-weight-bold">OFFICE OF THE CONTROLLER OF THE EXAMINATIONS</h4>
                        <h5 class=" text-center mt-2 text-uppercase"><u>claim form for examiners & other expenses</u></h5>
                    </center>
                </td>
                <th style="width:10%">
                    <img src="images/2.jpeg" style="height:120px;">
                </th>
            </tr>
        </table>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mt-1">
                    <h5 class=" text-center mt-2 text-uppercase"><u>practical examination: april/may 2024</u></h5>
                    <div class="col-md-12">
                        <?php
                        if (isset($_GET["date"]) && isset($_GET["lab_category"])) {
                            $date = $_GET["date"];
                            $lab_category = $_GET["lab_category"];
                            $sql2 = "select * from exam where date='$date' AND lab_category='$lab_category' ORDER BY FIELD(department, 'CSE', 'ECE', 'AI-DS', 'CSE(CS)', 'MECH')";
                            $result2 = mysqli_query($conn, $sql2);
                            $j = mysqli_num_rows($result2); // Get the number of rows directly
                            mysqli_data_seek($result2, 0); // Reset the result set pointer
                            $result = $result2;
                            if (mysqli_num_rows($result) > 0) {
                                if($lab_category==='regular'){
                                    ?>
                                    <table class="table table-bordered mt-2 table-sm" style="font-size:13px;">
                                        <tr>
                                            <th style="width:80px;">Date</th>
                                            <th>Session</th>
                                            <th>Department</th>
                                            <th>Subject code</th>
                                            <th>No of candidates Regd</th>
                                            <th>No of candidates examined</th>
                                            <th>Name & designation <br>External / Internal / Lab technician / Skilled Assistent</th>
                                            <th>Faculty Id</th>
                                            <th>College name</th>
                                            <th>Remun <br>Rupees</th>
                                            <th>Lump <br>Sum </th>
                                            <th>Other  <br> expenses</th>
                                            <th>Total</th>
                                            <th>Signature in full</th>
                                        </tr>
                                        <?php
                                        $types = ['ex', 'in', 'sk','lab','ox'];
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td rowspan="6"><?= $row["date"] ?></td>
                                            <td rowspan="6"><?= $row[ "ex_session"] ?></td>
                                            <td rowspan="6"><?= $row["department"] ?></td>
                                            <td rowspan="6"><?= $row["sub_code"] ?></td>
                                            <td rowspan="6"><?= $row["student_reg"] ?></td>
                                            <td rowspan="6"><?= $row["students"] ?></td>
                                        </tr>
                                        <?php
                                            foreach ($types as $type) {
                                        ?>
                                        <tr>
                                        <td>
                                                <?= ($type == 'ox') ? '<strong>OTHER EXPENSES</strong>' : $row[$type . "_name"]."/"  ?>
                                                <?= ($type == 'ox') ? '' : $row[$type . "_designation"] ?><br>
                                                <?= ($type == 'ox') ? '' : get_designation($type) ?>
                                            </td>

                                            <td><?= ($type == 'ox' || $type == 'ex') ? '' : $row[$type . "_Id"] ?></td>
                                            <td><?= ($type == 'ox') ? '':$row[$type . "_college"] ?></td>
                                            <td><?=($type == 'ox' ) ? '' : $row[$type . "_renum"] ?></td>
                                            <td><?= ($type == 'ex') ? (($row["ex_session"] == 'Both') ? '550' : '500'): '' ?></td>
                                            <td><?= ($type == 'ox') ?  $row[$type . "_s"] :'' ?></td>
                                            <td><?=($type == 'ox' ) ? '' : $row[$type . "_tot"] ?></td>
                                            <td><?= ($type == $type) ? '' : '' ?></td>
                                        </tr>
                                        <?php
                                        }
                                    }
                                        ?>
                                    </table>
                                    <?php
                                }
                            else{
                                ?>
                                <table class="table table-bordered mt-2 table-sm" style="font-size:13px;">
                                    <tr>
                                        <th style="width:80px;">Date</th>
                                        <th>Session</th>
                                        <th>Department</th>
                                        <th>Subject code</th>
                                        <th>No of candidates Regd</th>
                                        <th>No of candidates examined</th>
                                        <th>No of Batches</th>
                                        <th>Name & designation <br>External / Internal </th>
                                        <th>Faculty Id</th>
                                        <th>College name</th>
                                        <th>Remun <br>Rupees</th>
                                        <th>Lump <br>Sum </th>
                                        <th>Total</th>
                                        <th>Signature in full</th>
                                    </tr> 
                                    <?php
                                        $types = ['ex', 'in'];
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                          <tr>
                                            <td rowspan="3"><?= $row["date"] ?></td>
                                            <td rowspan="3"><?= $row[ "ex_session"] ?></td>
                                            <td rowspan="3"><?= $row["department"] ?></td>
                                            <td rowspan="3"><?= $row["sub_code"] ?></td>
                                            <td rowspan="3"><?= $row["student_reg"] ?></td>
                                            <td rowspan="3"><?= $row["students"] ?></td>
                                            <td rowspan="3"><?= $row["batch_no"] ?></td>
                                        </tr>
                                        <?php
                                            foreach ($types as $type) {
                                        ?>
                                       <td>
                                            <?= $row[$type . "_name"] ?><br>
                                            <?= $row[$type . "_designation"] ?><br>
                                            <?= get_designation($type) ?>
                                        </td>

                                        <td><?= ($type == 'ex') ? '' : $row[$type . "_Id"] ?></td>
                                        <td><?= $row[$type . "_college"] ?></td>
                                        <td><?= $row[$type . "_renum"] ?></td>
                                        <td><?= ($type == 'in') ? '' : (($row["ex_session"] == 'Both') ? '550' : '500') ?></td>
                                        <td><?= $row[$type . "_tot"] ?></td>
                                        <td><?= ($type == $type) ? '' : '' ?></td>
                                       
                                        </tr>
                                        <?php
                                        }
                                    }
                                        ?>
                                    </table>
                                    <?php
                                }
                            }
                                
                             else {
                                echo "<p class='text-center text-primary mt-4'>No results found</p>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
       $(document).ready(function () {
    $("#convertBtn").click(function () {
        var element = document.getElementById("myprint");
        var opt = {
            margin: 10,
            filename: 'exam_document.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' },
            font: { size: 8 } // Set landscape orientation
        };
        html2pdf().from(element).set(opt).save();
    });
    $("#convertBtn2").click(function () {
        $(".mydiv").hide();
        window.print();
        $(".mydiv").show();
    });
});

    </script>
</body>
</html>