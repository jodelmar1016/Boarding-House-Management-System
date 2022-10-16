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

    if(isset($_POST['send'])){
        $sql="INSERT INTO suggestions (title, message) VALUES (:title, :message)";
        $stmt=$conn->prepare($sql);
        $stmt->execute([
                        ':title'=>$_POST['subject'],
                        ':message'=>$_POST['message']]);

        echo '<script>alert("Message sent");</script>';
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
    <title>Suggestion</title>
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
                <li class="nav-item active">
                    <a class="nav-link" href="message.php">Suggestion</a>
                </li>
                <li class="nav-item">
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
    
    
<!--- Compose Message or Suggestions---------------->
    <div class="container " style="margin-top: 20px; margin-bottom: 10px ">
            <div class="card" style="border-left: 7px solid lightblue;">
                    <form action="" method="POST" class="form" style="padding: 20px 20px 0px 20px;">
                    <div class="card-header">                    
                        <div class="form-group text-center">                            
                            <h4 style="color: #17a2b8">Compose Message or Suggestions</h3>   
                        </div>
                    </div>
                    <div class="card-body">   
                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" class="form-control" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label>Message or Suggestions</label>
                            <textarea class="form-control" name="message" rows="5" style="resize:none" required></textarea>
                        </div>
                        <br>
                        <div class="form-group ">
                            <button type="reset" name="reset" class="btn btn-outline-secondary" data-toggle="modal" data-target="#sendModal">Clear</button>
                            <button type="submit" name="send" class="btn btn-outline-primary">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</body>
</html>

<!-- <script>
    function messageSent(){
        alert ("Your message was sent succesfully");
    }
</script> -->