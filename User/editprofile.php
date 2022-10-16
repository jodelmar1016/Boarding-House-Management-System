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

    $uid=$row['boarder_id'];
    $fname=$row['first_name'];
    $lname=$row['last_name'];
    $sex=$row['sex'];
    $birthdate=$row['birthdate'];
    $year=$row['year'];
    $cnumber=$row['contact_number'];
    $cperson=$row['contact_person'];
    $address=$row['permanent_add'];
    $email=$row['email_add'];
    $uname=$row['username'];
    $pword=$row['password'];
    $imgname=$row['imgname'];
    $imgsrc=$row['imgsrc'];

    if(isset($_POST['btnEdit'])){
		$sql="UPDATE boarders SET first_name=:fname, last_name=:lname, sex=:sex, birthdate=:birthdate,
            year=:year, contact_number=:cnumber, contact_person=:cperson, permanent_add=:address, email_add=:email, 
        username=:uname, password=:pword WHERE boarder_id=:uid";
		$stmt=$conn->prepare($sql);
		$stmt->execute([':fname'=>$_POST['fname'],
						':lname'=>$_POST['lname'],
						':sex'=>$_POST['sex'],
                        ':birthdate'=>$_POST['update_birthdate'], 
                        ':year'=>$_POST['update_year'],                   
                        ':cnumber'=>$_POST['update_cnumber'],    
                        ':cperson'=>$_POST['update_cperson'], 
                        ':address'=>$_POST['update_address'], 
                        ':email'=>$_POST['update_email'],  
						':uname'=>$_POST['uname'],
						':pword'=>$_POST['pword'],
						':uid'=>$uid]);
		header("location: editprofile.php");
		//echo '<script>alert("Profile Updated");</script>';
	}

    if(isset($_POST['savePicture'])){
        $target="../upload/".basename($_FILES['update_picture']['name']);
        $tmp=$_FILES['update_picture']['tmp_name'];

        $sql="UPDATE boarders SET imgname=:imgname, imgsrc=:imgsrc WHERE boarder_id=:uid";
        $stmt=$conn->prepare($sql);
		$stmt->execute([':imgname'=>$_FILES['update_picture']['name'],
						':imgsrc'=>$target,
						':uid'=>$uid]);

        move_uploaded_file($tmp, $target);
        header("location: editprofile.php");
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
                    <a class="nav-link" href="balance.php">Balance</a>
                </li>
                <li class="nav-item">
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
    
        <div class="container w-50" style="margin-top: 20px; margin-bottom: 10px ">
            <div class="card" style="border-left: 7px solid lightblue;">
            <form class="form" style="padding: 20px 20px 0px 20px;">
                    <div class="card-header">                    
                        <div class="form-group text-center">                    
                            <img src=<?php echo $imgsrc==""?"../Assets/logo/user.png":$imgsrc ?> style="width:120px; height:120px; display:block; margin-left:auto; margin-right:auto"  alt="">           
                            <a data-toggle="modal" data-target="#editPic" href="#" style="color: #17a2b8; font-size:16px">Edit Display Picture</a>   
                        </div>
                    </div>
                    <div class="card-body">   
                        <div class="form-group">
                            <label for="">First Name</label>
                            <input disabled type="text" class="form-control" name="name" value=<?php echo $fname ?>>
                        </div>
                        <div class="form-group">
                            <label for="">Last Name</label>
                            <input disabled type="text" class="form-control" name="name" value=<?php echo $lname ?>>
                        </div>
                        <div class="form-group">
                            <label for="">Sex</label>
                            <input disabled type="text" class="form-control" name="name" value=<?php echo $sex ?>>
                        </div>
                        <div class="form-group">
                            <label for="">Birthday</label>
                            <input disabled type="text" class="form-control" name="name" value=<?php echo $birthdate ?>>
                        </div>
                        <div class="form-group">
                            <label for="">Year Level</label>
                            <input disabled type="text" class="form-control" name="name" value=<?php echo $year ?>>
                        </div>
                        <div class="form-group">
                            <label for="">Mobile Number</label>
                            <input disabled type="text" class="form-control" name="name" value=<?php echo $cnumber ?>>
                        </div>
                        <div class="form-group">
                            <label for="">Contact Person</label>
                            <input disabled type="text" class="form-control" name="name" value=<?php echo $cperson ?>>
                        </div>
                        <div class="form-group">
                            <label for="">Address</label>
                            <input disabled type="text" class="form-control" name="name" value=<?php echo $address ?>>
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input disabled type="text" class="form-control" name="name" value=<?php echo $email ?>>
                        </div>
                        <div class="form-group">
                            <label for="">Username</label>
                            <input disabled type="email" class="form-control" name="email" value=<?php echo $uname ?>>
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input disabled type="password" class="form-control" name="password" value=<?php echo $pword ?>>
                        </div>
                        <br>
                        <div class="form-group text-center">
                            <button type="button" name="editProfile" class="btn btn-outline-info w-50" data-toggle="modal" data-target="#editModal" >Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- Modal For Uploading Display Picture -->
    <div class="modal fade" id="editPic" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Display Picture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Upload Image</label>
                                <input type="file" name="update_picture" id="image" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="savePicture">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>

<!-- Modal Profile Details-->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Profile Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="">First Name</label>
                            <input type="text" name="fname" class="form-control" placeholder="First name" value=<?php echo $fname ?> required>
                        </div>
                        <div class="form-group">
                            <label for="">Last Name</label>
                            <input type="text" name="lname" class="form-control" placeholder="Last name" value=<?php echo $lname ?> required>
                        </div>
                        <div class="form-check">
                            <label for="">Sex</label><br>
                            <input type="radio" name="sex" class="form-check-input" value="Male" required>
                            <label for="" class="form-check-label">Male</label><br>
                            <input type="radio" name="sex" class="form-check-input" value="Female" required>
                            <label for="" class="form-check-label">Female</label>
                        </div>
                        <div class="form-group">
                            <label for="">Birthday</label>
                            <input type="text" name="birthdate" class="form-control" placeholder="Birtdate" value=<?php echo $birthdate ?> required>
                        </div>
                        <div class="form-group">
                            <label for="">Year Level</label>
                            <input type="text" name="year" class="form-control" placeholder="Year" value=<?php echo $year ?> required>
                        </div>
                        <div class="form-group">
                            <label for="">Contact Number</label>
                            <input type="text" name="cnumber" class="form-control" placeholder="Contact Number" value=<?php echo $cnumber ?> required>
                        </div>
                        <div class="form-group">
                            <label for="">Contact Person</label>
                            <input type="text" name="cperson" class="form-control" placeholder="Contact Person" value=<?php echo $cperson ?> required>
                        </div>
                        <div class="form-group">
                            <label for="">Address</label>
                            <input type="text" name="address" class="form-control" placeholder="Permanent Address" value=<?php echo $address ?> required>
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" name="email" class="form-control" placeholder="Email Address" value=<?php echo $email ?> required>
                        </div>
                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" name="uname" class="form-control" placeholder="Username" value=<?php echo $uname ?> required>
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" name="pword" class="form-control" placeholder="Password" value=<?php echo $pword ?> required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="btnEdit">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>