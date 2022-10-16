<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header("location: login.php");
    }

    include("../config.php");

    // query for session
    $user_check=$_SESSION['user'];
    $sql="SELECT * FROM boarders WHERE username='$user_check'";
    $stmt=$conn->query($sql);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $login_user=$row['username'];

    $imgsrc=$row['imgsrc'];


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
    <title>My Payment</title>
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
					<span class="navbar-text">Welcome, <?php echo $login_user ?></span>
				</li>
                <li class="nav-item">
                    <a class="nav-link" href="balance.php">Balance</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="myPayment.php">My Payment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="help.php">Help</a>
                </li>
            </ul>
            <!-- ACCOUNT-->
            <div class="dropdown" style="padding-right: 50px">
                <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100px">
                    <span class="navbar-text" style="font-size: 15px; padding:0">                                                
                    <img src=<?php echo $imgsrc==""?"../Assets/logo/user.png":$imgsrc ?> width="30" height="30" class="d-inline-block align-top" alt="">                      
                    </span>
                </button>
                
				<div class="dropdown-menu w-50" style="padding: 6px">
					<a class="dropdown-item" href="editprofile.php" style="color: #17a2b8; font-size:18px">My Account</a>
                    <hr>
					<a class="dropdown-item" href="../logout.php" style="color: red; font-size:18px">Logout</a>
				</div>
			</div>
        </div>
    </nav>
    <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header">
                <h5 class="d-inline">My Payment</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Payment Number</th>
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
                            <td><?php echo $row['payment_number']; ?></td>
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

</body>
</html>