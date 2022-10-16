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

    //add payment
    if (isset($_POST['addPayment'])) {
        $date=date("Y-m-d");
        $paymentNumber=rand(10000000,99999999);
        $id=$_POST['boarders'];
        $amount=$_POST['amount'];
        $sql="INSERT INTO payments (payment_number, boarder_id, amount, payment_date) 
            VALUES (:payment_number, :boarder_id, :amount, :payment_date)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':payment_number' => $paymentNumber,
            ':boarder_id' => $id,
            ':amount' => $amount,
            ':payment_date' => $date
        ]);
        
        $sql="SELECT amount FROM balance WHERE boarder_id=$id";
        $stmt=$conn->query($sql);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $amount=$row['amount']-$amount;

        if($amount<=0){
            $sql="UPDATE balance SET amount=:amount, status=:status WHERE boarder_id=:update_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':amount' => $amount,
                ':status' => 'PAID',
                ':update_id' => $id
            ]);
        }
        else{
            $sql="UPDATE balance SET amount=:amount, status=:status WHERE boarder_id=:update_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':amount' => $amount,
                ':status' => 'UNPAID',
                ':update_id' => $id
            ]);
        }
           
        echo '<script>alert("Payment added");</script>';
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
    <title>Payments</title>
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
                 <li class="nav-item active">
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

        <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header">
                <h5 class="d-inline">Payment Table</h5>
                <span><button class="btn btn-success" data-toggle="modal" data-target="#addPaymentModal">Add Payment</button></span>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Payment Number</th>
                            <th>Boarder ID</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="SELECT * FROM payments";
                            $stmt=$conn->query($sql);
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <td><?php echo $row['payment_id']; ?></td>
                            <td><?php echo $row['payment_number']; ?></td>
                            <td><?php echo $row['boarder_id']; ?></td>
                            <!-- <td><?php echo $row['boarder_id']; ?></td> -->
                            <td><?php echo $row['amount']; ?></td>
                            <td><?php echo $row['payment_date']; ?></td>
                            <!-- <td>
                                <button type="button" class="btn btn-primary"><i class="fas fa-pen" data-toggle="modal" data-target="#editModal"
                                    onclick="getUserDetails(<?php echo $row['boarder_id']?>,
                                    '<?php echo $row['first_name'] ?>','<?php echo $row['last_name'] ?>',
                                    '<?php echo $row['sex'] ?>','<?php echo $row['contact_number'] ?>',
                                    '<?php echo $row['username'] ?>', '<?php echo $row['password'] ?>');"></i></button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" 
                                onClick="delId(<?php echo $row['boarder_id'] ?>)"><i class="fas fa-trash"></i></button>
                            </td> -->
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!----------Modal add Payment---------->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="number" name="amount" class="form-control" placeholder="Amount" required>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="boarders">
                                <?php
                                    $sql="SELECT * FROM boarders ORDER BY last_name ASC";
                                    $stmt=$conn->query($sql);
                                    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                <option value=<?php echo $row['boarder_id'] ?>><?php echo $row['last_name']; echo ', '; echo $row['first_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="addPayment">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>