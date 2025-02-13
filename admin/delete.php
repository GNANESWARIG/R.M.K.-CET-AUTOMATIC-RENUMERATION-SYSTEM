<?php
include("db.php");
$id = $_GET["id"];
$sql = "delete from exam where id = $id";
if(mysqli_query($conn, $sql))
{
    echo '<script>alert("Deleted Successfully");window.location.replace("dash.php");</script>';
}
else
{
    echo '<script>alert("Try Again Later");window.location.replace("dash.php");</script>';
}