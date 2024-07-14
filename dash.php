<?php

session_start();
if(!isset($_SESSION["id"]))
{
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

    <style>
        body
        {
            background-image: url("bg.webp");
            background-repeat: no-repeat;
            background-size: 100% 100vh;
            height:auto;
            overflow-y:scroll;
            overflow-x:hidden;
        }
    </style>
</head>
<body>
    <?php
        include("header.php");
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-5">
                <table class="table table-sm" style="background-color: rgba(0,0,0,0.7);">
                    <thead>
                        <tr style="color: #ffffff;">
                            <th>Exam Date</th>
                            <th>Department</th>
                            <th>Semester</th>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Students Registered</th>
                            <th>Students Appeared</th>
                           
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "select * from exam order by id desc";
                        $result = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($result) > 0)
                        {
                            while($row = mysqli_fetch_assoc($result))
                            {
                                ?>
                                <tr style="color: #ffffff;">
                                    <td><?=$row["date"]?></td>
                                    <td><?=$row["department"]?></td>
                                    <td><?=$row["semester"]?></td>
                                    <td><?=$row["sub_name"]?></td>
                                    <td><?=$row["sub_code"]?></td>
                                    <td><?=$row["student_reg"]?></td>
                                    <td><?=$row["students"]?></td>
                                   
                                    <td>
                                        <a href="edit.php?id=<?=$row["id"]?>" class="btn btn-success">Edit</a>
                                        <a href="delete.php?id=<?=$row["id"]?>" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>