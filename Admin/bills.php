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

//edit month
if (isset($_POST['editMonth'])) {
    $sql = "UPDATE month SET date=:date WHERE month_id=:update_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':date' => $_POST['edit_month'],
        ':update_id' => $_POST['m_id']
    ]);
    echo '<script>alert("Month Updated")</script>';
}

//add water bills
if (isset($_POST['addBillsWater'])) {
    $sql="INSERT INTO bills (date_created, category, amount, due_date, status) 
        VALUES (:date_created, :category, :amount, :due_date, :status)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':date_created' => date("m-d-Y"),
        ':category' => $_POST['water'],
        ':amount' => $_POST['add_bills_water'],
        ':due_date' => $_POST['due_date_water'],
        ':status' => 'Pending'
    ]);
    echo '<script>alert("Water Bills Added")</script>';
}

//add electricity bills
if (isset($_POST['addBillsElectricity'])) {
    $sql="INSERT INTO bills (date_created, category, amount, due_date, status) 
        VALUES (:date_created, :category, :amount, :due_date, :status)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':date_created' => date("m-d-Y"),
        ':category' => $_POST['electricity'],
        ':amount' => $_POST['add_bills_electricity'],
        ':due_date' => $_POST['due_date_electricity'],
        ':status' => 'Pending'
    ]);
    echo '<script>alert("Electricity Bills Added")</script>';
}

if(isset($_POST['addReceiptWater'])){
    $Get_image_name=$_FILES['add_image']['name'];
    $image_path="../upload/".basename($Get_image_name);
    $tmp=$_FILES['add_image']['tmp_name'];
    move_uploaded_file($tmp, $image_path);

    $sql="UPDATE bills SET status='PAID' WHERE category='Water' AND status='Pending'";
    $stmt=$conn->prepare($sql);
    $stmt->execute();
    echo '<script>alert("Receipt Added")</script>';
}

if(isset($_POST['addReceiptElectricity'])){
    $Get_image_name=$_FILES['add_image']['name'];
    $image_path="../upload/".basename($Get_image_name);
    $tmp=$_FILES['add_image']['tmp_name'];
    move_uploaded_file($tmp, $image_path);

    $sql="UPDATE bills SET status='PAID' WHERE category='Electricity' AND status='Pending'";
    $stmt=$conn->prepare($sql);
    $stmt->execute();
    echo '<script>alert("Receipt Added")</script>';
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
                 <li class="nav-item active">
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
                <div class="col-sm-5">
                    <div class="card" style="border-left: 5px solid lightblue;">
                        <div class="card-body">
                        <?php       
                            $sql="SELECT COUNT(*) FROM bills WHERE status='Pending' AND category='Water'";
                            $stmt = $conn->prepare($sql);
                            $stmt -> execute();
                            $count=$stmt->fetchColumn();

                            if($count>=1){
                                $sql="SELECT * FROM bills ORDER BY id DESC";
                                $stmt=$conn->query($sql);
                                while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                    if($row['category']=="Water" && $row['status']=="Pending"){
                                
                            
                        ?>
                            <h3 class="d-inline card-title">Water Bills</h3>
                            <h5>Due Date: 
                                <span style="color: #dc3545"><b><?php echo $row['due_date']; ?></b></span>
                            </h5>     
                            <h1 class="card-text">
                                <b><span aria-hidden="true">&#8369;</span><?php echo $row['amount']; ?></b>
                            </h1>   
                            <span><button type="button" class="btn btn-outline-dark btn-s" data-toggle="modal" data-target="#addBillWater"><i class="fa fa-plus">Add Bills</i></button></span>
                            
                            <span><button type="button" class="btn btn-outline-dark btn-s" data-toggle="modal" data-target="#receiptBillWater"><i class="fa fa-plus">Add Receipt</i></button></span>
                        <?php 
                            break; }}}
                            else{
                         ?>
                         <h3 class="d-inline card-title">Water Bills</h3>
                            <h5>Due Date: 
                                <span style="color: #dc3545"><b></b></span>
                            </h5>     
                            <h1 class="card-text">
                                <b><span aria-hidden="true">&#8369;</span>0</b>
                            </h1>   
                            <span><button type="button" class="btn btn-outline-dark btn-s" data-toggle="modal" data-target="#addBillWater"><i class="fa fa-plus">Add Bills</i></button></span>
                            
                            <span><button type="button" class="btn btn-outline-dark btn-s" data-toggle="modal" data-target="#receiptBillWater"><i class="fa fa-plus">Add Receipt</i></button></span>
                         <?php 
                            }
                         ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="card" style="border-left: 5px solid lightblue;">
                        <div class="card-body">
                        <?php
                            $sql="SELECT COUNT(*) FROM bills WHERE status='Pending' AND category='Electricity'";
                            $stmt = $conn->prepare($sql);
                            $stmt -> execute();
                            $count=$stmt->fetchColumn();

                            if($count>=1){
                            $sql="SELECT * FROM bills ORDER BY id DESC";
                            $stmt=$conn->query($sql);
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                if($row['category']=="Electricity" && $row['status']=="Pending"){
                                
                            
                        ?>
                            <h3 class="d-inline card-title">Electricity Bills</h3>  
                            <h5>Due Date: 
                                <span style="color: #dc3545"><b><?php echo $row['due_date']; ?></b></span>
                            </h5>    
                            <h1 class="card-text">
                                <b><span aria-hidden="true">&#8369;<?php echo $row['amount']; ?></b>
                            </h1>
                            <span><button type="button" class="btn btn-outline-dark btn-s" data-toggle="modal" data-target="#addBillElectricity"><i class="fa fa-plus">Add Bills</i></button></span>
                            
                            <span><button type="button" class="btn btn-outline-dark btn-s" data-toggle="modal" data-target="#receiptBillElectricity"><i class="fa fa-plus">Add Receipt</i></button></span>
                        </div>
                        <?php
                            break; }}}
                            else{
                        ?>
                        <h3 class="d-inline card-title">Electricity Bills</h3>  
                            <h5>Due Date: 
                                <span style="color: #dc3545"><b></b></span>
                            </h5>    
                            <h1 class="card-text">
                                <b><span aria-hidden="true">&#8369;</span>0</b>
                            </h1>
                            <span><button type="button" class="btn btn-outline-dark btn-s" data-toggle="modal" data-target="#addBillElectricity"><i class="fa fa-plus">Add Bills</i></button></span>
                            
                            <span><button type="button" class="btn btn-outline-dark btn-s" data-toggle="modal" data-target="#receiptBillElectricity"><i class="fa fa-plus">Add Receipt</i></button></span>
                        <?php
                            }
                        ?>  
                    </div>
                </div>
            </div>
        </div>

<h1></h1>
    <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header">
                <?php
                    // $sql="SELECT * FROM month";
                    // $stmt=$conn->query($sql);
                    // while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                ?>
                
                <h5 class="d-inline ">Recent Bills
                </h5>
                </div>   
                <?php  ?>
            <div class="card-body">
                <table class="table table-hover">                    
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data Created</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="SELECT * FROM bills ORDER BY id DESC";
                            $stmt=$conn->query($sql);
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['date_created']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['amount']; ?></td>
                            <td><?php echo $row['due_date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!----------Modal add bills Water---------->
<div class="modal fade" id="addBillWater" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Bills</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="text" name="add_bills_water" class="form-control" placeholder="Amount" required>
                    </div>
                    <div class="form-group">
                        <input type="date" name="due_date_water" class="form-control" placeholder="Due Date" required>
                    </div>
                    <input type="text" name="water" value="Water" hidden>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="addBillsWater">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <input type="hidden" name="bid" id="billsid">
                </form>
            </div>
            </div>
        </div>
    </div>


<!----------Modal add Receipt Water---------->
<div class="modal fade" id="receiptBillWater" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Receipt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <label>Upload Image</label>
                    <input type="file" name="add_image" id="image" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="addReceiptWater">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>


<!----------Modal add bills electricity---------->
<div class="modal fade" id="addBillElectricity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Bills</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="text" name="add_bills_electricity" class="form-control" placeholder="Amount" required>
                    </div>
                    <div class="form-group">
                        <input type="date" name="due_date_electricity" class="form-control" placeholder="Due Date" required>
                    </div>
                    <input type="text" name="electricity" value="Electricity" hidden>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="addBillsElectricity">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <input type="hidden" name="bid" id="billsid">
                </form>
            </div>
            </div>
        </div>
    </div>


<!----------Modal add Receipt electricity---------->
<div class="modal fade" id="receiptBillElectricity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Receipt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <label>Upload Image</label>
                    <input type="file" name="add_image" id="image" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="addReceiptElectricity">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>

<!----------Modal  Month---------->
<div class="modal fade" id="monthModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Month</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="month" name="edit_month" class="form-control" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="editMonth">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <input type="hidden" name="m_id" id="monthid">
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<script>
    function getMonthDetails(id, month) { //edit month
            $('input[name="edit_month"]').val(month);
            $('#monthid').val(id);
    }

    function getWaterDetails(wid, wbill, wdue) { //edit water bills
        $('input[name="add_bills"]').val(wbill);
        $('input[name="add_due"]').val(wdue);
        $('#billsid').val(wid);
    }

    function getElectricityDetails(eid, ebill, edue) { //edit electricity bills
        $('input[name="add_bills"]').val(ebill);
        $('input[name="add_due"]').val(edue);
        $('#billsid').val(eid);
    }

</script>