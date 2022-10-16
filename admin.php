<?php
    session_start();

    include("config.php");

    if(isset($_POST['submit'])){
        $sql="SELECT * FROM admin WHERE username=:un AND password=:pw";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':un'=>$_POST['name'], ':pw'=>$_POST['pass']]);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $count=$stmt->rowCount();

        if($count==1){
            $_SESSION['access']=$_POST['name'];
            header("location: Admin/dashboard.php");
            
        }
        else{
            $error="Invalid username and/or password";
        }
        // $name=$_POST['name'];
        // $pass=$_POST['pass'];
        // if($name=="Admin"&&$pass=="admin123"){
        //     $_SESSION['access']=$name;
        //     header("location: Admin/dashboard.php");
        // }
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">

    <script src="bootstrap/jquery/jquery.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
    <title>Admin login</title>

    <style>
        .container{
            margin-top: 30px;
            padding: 10px;
            border-radius: 20px;
            width: 100%;
        }
        @media all and (min-width: 768px){
            .container{
                width: 50%;
            }
        }
        @media all and (min-width: 1200px){
            .container{
                width: 30%;
            }
        }
    </style>
</head>
<body>
    <div class="container bg-dark text-white">
        <form action="" method="POST">
            <h3 class="text-center font-weight-bold">Admin Login</h3>
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input type="password" class="form-control" name="pass" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-secondary form-control" name="submit">Login</button>
            </div>
        </form>
        <?php if(isset($error)){ ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error ?>
        </div>
        <?php } ?>
    </div>
</body>
</html>