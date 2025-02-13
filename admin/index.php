<?php

session_start();

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

    <!-- style CSS -->
    <style>
        body{
            background-image: url("../image/re.jpg");
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
        <h2 class="text-center text-white mt-3 py-3" style="background-color:rgba(0, 0, 0, 0.5);color:#000000;border-radius:30px;">Automatic Renumeration System</h2>
        <div class="row">
            <div class="col-md-4 offset-md-4 mt-5">
                <form action="" method="post" class="aform" autocomplete="off">
                    <h4 class="text-center text-white" style="border-bottom: 1px solid #ffffff;padding-bottom:10px;">Admin Login</h4>
                    <div class="form-group mt-4">
                        <label for="" class="text-white" style="font-size: 18px;font-weight:500">Email:</label>
                        <input type="email" class="form-control" placeholder="Enter your Email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="" class="text-white" style="font-size: 18px;font-weight:500">Password:</label>
                        <input type="password" class="form-control" placeholder="Enter your Password" name="password">
                    </div>
                    <center>
                        <input type="submit" name="login" class="btn btn-danger alog" value="Login">
                        <a href="../index.php" class="btn btn-warning text-white ml-2">Back</a>
                    </center>
                    <br>
                    <!-- <center>
                        <a href="../index.php" class="btn btn-warning">Back</a>
                    </center> -->
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php

// database connection
include("db.php");

// submission process
if(isset($_POST["login"]))
{
    $email = $_POST["email"];
    $password = $_POST["password"];
    $sql = "select * from admin where email = '$email' and Password = '$password'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0)
    { 
        $row=mysqli_fetch_assoc($result);  
        $_SESSION["id"] = $row["id"];
        echo '<script>alert("Admin Login Successfull");window.location.replace("dash.php");</script>';
    }
    else
    {
        echo '<script>alert("Email or Password Incorrect");</script>';
    }
}

?>