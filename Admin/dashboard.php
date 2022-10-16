<?php
    session_start();
    if(!isset($_SESSION['access'])){
        header("location: ../admin.php");
    }

    include("../config.php");

    // query for session
    $user_check=$_SESSION['access'];
    $sql="SELECT * FROM admin WHERE username='$user_check'";
    $stmt=$conn->query($sql);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $login_user=$row['username'];

     // reset amount
    if(isset($_POST['reset'])){
        $sql="SELECT * FROM balance";
        $stmt=$conn->query($sql);
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            $id=$row['boarder_id'];
            $amount=intval($row['amount'])+1000;
            $status="UNPAID";
            if($amount<=0){
                $status="PAID";
            }
            $temp="UPDATE balance SET amount=:amount, status=:status WHERE boarder_id=$id";
            $stmt2=$conn->prepare($temp);         
            $stmt2->execute([':amount'=>$amount,
                            ':status'=> $status]);
        }
        echo '<script>alert("Amount reset successfully")</script>';
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

    <script src="../bootstrap/jquery/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
    <title>Dashboard</title>
</head>
<body>
    <?php if(isset($test)){ ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $test ?>
    </div>
    <?php } ?>
    <?php if(isset($try)){ ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $try ?>
    </div>
    <?php } ?>
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
                <li class="nav-item active">
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
                <li class="nav-item">
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
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-sm-4">
                <div class="card" style="border-left: 5px solid lightblue;">
                    <div class="card-body text-right">
                        <h1 class="card-title"><b>
                            <?php   
                                $sql="SELECT COUNT(*) FROM boarders";
                                $stmt = $conn->prepare($sql);
                                $stmt -> execute();
                                $count=$stmt->fetchColumn();
                                echo $count;
                            ?>
                        </b></h1>
                        <p class="card-text">Total boarders</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card" style="border-left: 5px solid lightblue;">
                <div class="card-body text-right">
                    <h1 class="card-title"><b>
                        <?php   
                            $sql="SELECT SUM(amount) FROM bills WHERE status='Pending'";
                            $stmt = $conn->prepare($sql);
                            $stmt -> execute();
                            $count=$stmt->fetchColumn();
                            if($count>0){
                                echo $count;
                            }
                            else{
                                echo '0';
                            }
                            
                        ?>
                    </b></h1>
                    <p class="card-text">Total Unpaid Bills</p>
                </div>
                </div>
            </div>
            <!-- <div class="col-sm-4">
                <div class="card" style="border-left: 5px solid lightblue;">
                <div class="card-body text-right">
                    <h1 class="card-title"><b>###</b></h1>
                    <p class="card-text">Sample</p>
                </div>
                </div>
            </div> -->
        </div>

        <div class="card" style="margin-top: 50px">
            <div class="card-header">
                <h5 class="d-inline">Balance for the month of  <?php echo date('F') ?></h5>
                <span><button class='btn btn-warning' data-toggle='modal' data-target='#resetAmount'>Reset</button></span>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="SELECT * FROM balance";
                            $stmt=$conn->query($sql);
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                $id=$row['boarder_id'];
                                $q="SELECT first_name, last_name FROM boarders WHERE boarder_id=$id";
                                $stmt1=$conn->query($q);
                                while($data=$stmt1->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <td><?php echo $data['first_name']; ?></td>
                            <td><?php echo $data['last_name']; ?></td>
                            <?php } ?>
                            <td><?php echo $row['amount']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal to reset amount -->
<div class="modal fade" id="resetAmount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Bills</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to add bills for the month?</p>
                <form action="" method="POST" enctype="multipart/form-data">
                <input type='text' hidden>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="reset">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>