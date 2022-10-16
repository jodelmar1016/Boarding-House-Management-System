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

    $id=$row['boarder_id'];
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
    <title>Help</title>
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
					<span class="navbar-text" style="font-weight: bold; color:#075b68; font-size: 18px;" >Welcome, <?php echo $login_user ?></span>
				</li>
                <li class="nav-item">
                    <a class="nav-link" href="balance.php">Balance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="message.php">Suggestion</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="help.php">Help</a>
                </li>
            </ul>
            <!-- ACCOUNT-->
            <div class="dropdown" style="padding-right: 50px">
                <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100px">
                    <span class="navbar-text" style="font-size: 15px; padding:0">                                                
                    <img src=<?php echo $imgsrc==""?"../Assets/logo/user.png":$imgsrc ?> width="30" height="30" class="d-inline-block align-top" alt="" style="border-radius: 50%">                      
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
    <div class="container" style="margin-top: 50px;">
                <div class="card" style="border-left: 5px solid lightblue; margin-bottom: 20px">
                    <div class="card-body text-left">
                        <h1 class="card-title"><b>About Us</b></h1>
                        <p class="card-text">Boarding House Management System was developed to improve and provide solutions to the needs of boarding houses.</p>
                        <p class="card-text">It is an online  system that allows boarding managers to more effectively manage their boarding house related transactions and activities.</p>
                    </div>
                </div>
                <div class="card" style="border-left: 5px solid lightblue; margin-bottom: 20px">
                    <div class="card-body text-left">
                        <h1 class="card-title"><b>FAQs</b></h1>
                        <ol type="1">
                        <?php
                            $sql="SELECT * FROM faqs";
                            $stmt=$conn->query($sql);
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                            <li>
                                <p class="card-text"><b><?php echo $row['questions'] ?></b><br><?php echo $row['answers'] ?></p>
                            </li>
                        <?php } ?>
                        </ol>
                    </div>
                </div>
                <?php
                    $sql="SELECT * FROM contacts";
                    $stmt=$conn->query($sql);
                    $row=$stmt->fetch(PDO::FETCH_ASSOC)
                ?>
                <div class="card" style="border-left: 5px solid lightblue; margin-bottom: 20px">
                    <div class="card-body text-left">
                    <h1 class="card-title"><b>Have any questions or suggestions? We'd love to hear from you!</b></h1>
                    <p>Email us with any questions or inquiries or call <b><?php echo $row['mobile'] ?></b>.<br>
          We would be happy to answer your questions and set up a meeting with you.</p>
                    </div>
                </div>
                <div class="card" style="border-left: 5px solid lightblue; margin-bottom: 20px">
                    <div class="card-body text-left">
                        <h1 class="card-title"><b>Follow Us</b></h1>
                        <p class="card-text">Facebook Page:</p>
                        <p class="card-text"> <b><?php echo $row['facebook'] ?></b></p>
                        <p class="card-text">Twitter: </p>
                        <p class="card-text"> <b><?php echo $row['twitter'] ?></b></p>
                        <p class="card-text">Instagram: </p>
                        <p class="card-text"><b><?php echo $row['instagram'] ?></b></p>
                    </div>
                </div>
                
        <!-- <div class="row">
            <div class="col-sm-4">
                <div class="card" style="border-left: 5px solid lightblue;">
                    <div class="card-body text-right">
                        <h1 class="card-title"><b>###</b></h1>
                        <p class="card-text">Balance this month</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card" style="border-left: 5px solid lightblue;">
                    <div class="card-body text-right">
                        <h1 class="card-title"><b>###</b></h1>
                        <p class="card-text">Advance Payment</p>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

</body>
</html>