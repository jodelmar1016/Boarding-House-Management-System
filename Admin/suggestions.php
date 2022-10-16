<?php
    session_start();
    if(!isset($_SESSION['access'])){
        header("location: ../admin.php");
    }

    include("../config.php");
    include("ago.php");

    // query for session
    $user_check=$_SESSION['access'];
    $sql="SELECT * FROM admin WHERE username='$user_check'";
    $stmt=$conn->query($sql);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $login_user=$row['username'];

    // update as read notif
    if(isset($_POST['update'])){
        $id=$_POST['update_id'];
        $sql="UPDATE suggestions SET status='read' WHERE s_id='$id'";
        $stmt=$conn->query($sql);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="stylesheet" href="suggestion.css">

    <script src="../bootstrap/jquery/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
    <title>Suggestions</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
        <a class="navbar-brand" href="#">
            <img src="../Assets/logo/main-logo.png" class="d-inline-block align-top" alt="">
            <img src="../Assets/logo/main-logo-text.png" class="d-inline-block align-top" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
					<span class="navbar-text" style="font-weight: bold; color:#075b68; font-size: 18px;">Welcome, <?php echo $login_user ?></span>
				</li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manageBoarders.php">Manage Boarders</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="payments.php">Payments</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="bills.php">Bills</a>
                </li>
                <?php
                    $sql="SELECT * FROM suggestions WHERE status='unread'";
                    $stmt=$conn->query($sql);
                    $row=$stmt->fetch(PDO::FETCH_ASSOC);
                    $num=$stmt->rowCount();
                ?>
                 <li class="nav-item active">
                    <a class="nav-link" href="suggestions.php">Suggestions <?php if($num>0) echo "<span class='badge badge-danger'>$num</span>" ?></a>
                </li>
            </ul>
            <!-- ACCOUNT-->
            <div class="dropdown" style="padding-right: 50px">
                <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100px">
                    <span class="navbar-text" style="font-size: 15px; padding:0">                                                
                    <img src="../Assets/logo/user.png" width="30" height="30" class="d-inline-block align-top" alt="" style="border-radius: 50%">                      
                    </span>
                </button>
                
				<div class="dropdown-menu w-50" style="padding: 6px">
					<a class="dropdown-item" href="info.php" style="color: #17a2b8; font-size:18px">Admin</a>
                    <hr>
					<a class="dropdown-item" href="../logout.php" style="color: red; font-size:18px">Logout</a>
				</div>
			</div>
        </div>
    </nav>

    <div class="container" style="margin-top: 20px">
        <?php
            $sql="SELECT * FROM suggestions ORDER BY s_id DESC";
            $stmt=$conn->query($sql);
            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        ?>
        <button class="text-left notif btn" data-toggle="modal" data-target="#read" style="background-color: <?php if($row['status']=="unread") echo '#c2cfd6' ?>; border-color: <?php if($row['status']=="unread") echo '#343a40' ?>; margin-bottom: 10px; border-radius: 20px;" onclick="getData(<?php echo $row['s_id'] ?>,'<?php echo $row['message'] ?>','<?php echo $row['title'] ?>','<?php echo $row['date'] ?>')">
            <div class="content">
                <h5 class="d-inline" style="font-weight: <?php if($row['status']=="unread") echo 'bold' ?>;"><?php echo $row['title'] ?></h5>
                <div class="ago d-inline float-right"><?php echo time_elapsed_string($row['date']) ?></div>
                <p style="font-weight: <?php if($row['status']=="unread") echo 'bold' ?>;"><?php echo substr($row['message'],0,15)."..."; ?></p>
            </div>
        </button>
        <?php
            }
        ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="read" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
        </div>
        <div class="modal-body">
            <p id="showMessage"></p>
            <br>
            <p id="date"></p>
        </div>
        <div class="modal-footer">
            <form action="" method="POST">
                <input type="hidden" id="update_id" name="update_id">
                <button type="submit" class="btn btn-secondary" name="update">Close</button>
            </form>
        </div>
        </div>
    </div>
    </div>

</body>
</html>

<script>
    function getData(id,message,title,date){
        document.getElementById("update_id").value=id;
        document.getElementById("showMessage").innerHTML=message.replaceAll("/","<br>");
        document.getElementById("exampleModalLabel").innerHTML=title;
        document.getElementById("date").innerHTML="Date: "+date;
    }
</script>