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
    
    <style>
        body {
            background-image: url("bg.webp");
            background-repeat: no-repeat;
            background-size: 100% 100%;
            height: 100vh;
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3 mt-5">
               <div class="card">
                <div class="card-body">
                    <h4 class="text-primary text-center mt-2">Choose an Excel File to Upload Internals</h4>
                    <form method="post" action="averify.php" enctype="multipart/form-data">
                        <input type="file" name="myfile" class="form-control" required>
                        <input type="submit" name="uploadfile" value="Upload" class="mt-3 btn btn-primary mx-auto px-4 d-block">
                    </form>

                    <!-- Button to download the Excel template from Google Drive -->
                    <a href="https://docs.google.com/spreadsheets/d/1fM-7EhuaRRQinNjz1CkZD8MQfwzJAipA/export?format=xlsx" class="mt-3 btn btn-success mx-auto px-4 d-block">Download Template</a>

                </div>
               </div> 
            </div>
        </div>
    </div>
</body>
</html>
