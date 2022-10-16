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

    // ADMIN
    //code to add admin
    if(isset($_POST['add'])){
        $sql="INSERT INTO admin (username, password) VALUES (:uname, :pword)";
        $stmt=$conn->prepare($sql);
        $stmt->execute([
                        ':uname'=>$_POST['uname'],
                        ':pword'=>$_POST['pword']]);
        
        echo '<script>alert("1 record added");</script>';
    }
    // DELETE RECORD
    if(isset($_POST['delete'])){
        $sql="DELETE FROM admin WHERE admin_id=:uid";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':uid'=>$_POST['uid']]);
        echo '<script>alert("1 record deleted");</script>';
    }

    //code to Edit admin
    if(isset($_POST['edit'])){         
        $sql="UPDATE admin SET username=:uname, password=:pword WHERE admin_id=:update_id";         
        $stmt=$conn->prepare($sql);         
        $stmt->execute([':uname'=>$_POST['update_username'],                         
                        ':pword'=>$_POST['update_password'],                         
                        ':update_id'=>$_POST['uid']]);
        echo '<script>alert("An account has been updated")</script>';    
    }

    // FAQS
    //code to add faqs
    if(isset($_POST['addFaqs'])){
        $sql="INSERT INTO faqs (questions, answers) VALUES (:questions, :answers)";
        $stmt=$conn->prepare($sql);
        $stmt->execute([
                        ':questions'=>$_POST['question'],
                        ':answers'=>$_POST['answer']]);
        
        echo '<script>alert("1 record added");</script>';
    }
    // DELETE RECORD
    if(isset($_POST['deleteFaqs'])){
        $sql="DELETE FROM faqs WHERE id=:idfaqs";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':idfaqs'=>$_POST['idfaqs']]);
        echo '<script>alert("1 record deleted");</script>';
    }

    //code to Edit faqs
    if(isset($_POST['editFaqs'])){         
        $sql="UPDATE faqs SET questions=:questions, answers=:answers WHERE id=:update_id";         
        $stmt=$conn->prepare($sql);         
        $stmt->execute([':questions'=>$_POST['update_question'],                         
                        ':answers'=>$_POST['update_answer'],                         
                        ':update_id'=>$_POST['upidFaqs']]);
        echo '<script>alert("1 record updated")</script>';    
    }

    //code to Edit contacts
    if(isset($_POST['btnSaveContacts'])){         
        $sql="UPDATE contacts SET email=:email, facebook=:facebook, twitter=:twitter, instagram=:insta, mobile=:mobile_num WHERE contact_id=:update_id";         
        $stmt=$conn->prepare($sql);         
        $stmt->execute([':email'=>$_POST['email'],                         
                        ':facebook'=>$_POST['facebook'],                         
                        ':twitter'=>$_POST['twitter'],
                        ':insta'=>$_POST['insta'],
                        ':mobile_num'=>$_POST['mobile_num'],
                        ':update_id'=>1]);
        echo '<script>alert("1 record updated")</script>';    
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
    <title>Admin</title>
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
                    <a class="nav-link" href="maintenance.php">Maintenance</a>
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
    <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header">
                <h5 class="d-inline">List of Admins</h5>
                <span><button class="btn btn-success" data-toggle="modal" data-target="#addModal"><i class="fas fa-user-plus"></i></button></span>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="SELECT * FROM admin";
                            $stmt=$conn->query($sql);
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <td><?php echo $row['admin_id']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo str_repeat("*",strlen($row['password'])); ?></td>
                            <td>
                                <button type="button" class="btn btn-primary"><i class="fas fa-pen" data-toggle="modal" data-target="#editModal"
                                    onclick="getUserDetails(<?php echo $row['admin_id']?>,
                                    '<?php echo $row['username'] ?>', '<?php echo $row['password'] ?>');"></i></button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" 
                                onClick="delId(<?php echo $row['admin_id'] ?>)"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-----------Add----------->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add account</h5>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="uname" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="pword" class="form-control" required>
                            </div>
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="add">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>


    <!-----------Delete----------->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Record</h5>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="uid" id="uid">
                        <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                    <button type="submit" class="btn btn-primary" name="delete">YES</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!------------------- Modal to Edit Admin -------------------->

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Admin's Details</h5>
                </div>
                <form action="" method="POST" class="form-horizontal">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="update_username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="update_password" class="form-control" required>
                        </div>   
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="edit">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="hidden" name="uid" id="upid">
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!---------- FAQs----------->
    <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header">
                <h5 class="d-inline">Frequently Asked Questions</h5>
                <span><button class="btn btn-success" data-toggle="modal" data-target="#addFaqsModal"><i class="fas fa-plus"></i></button></span>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="SELECT * FROM faqs";
                            $stmt=$conn->query($sql);
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <td><?php echo $row['id'] ?></td>
                            <td><?php echo $row['questions'] ?></td>
                            <td><?php echo $row['answers'] ?></td>
                            <td>
                                <button type="button" class="btn btn-primary"><i class="fas fa-pen" data-toggle="modal" data-target="#editFaqsModal"
                                    onclick="getFaqsDetails(<?php echo $row['id']?>,
                                    '<?php echo $row['questions'] ?>', '<?php echo $row['answers'] ?>');"></i></button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteFaqsModal" 
                                onClick="delIdfaqs(<?php echo $row['id'] ?>)"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!---------- Add FAQs  -------->
    <div class="modal fade" id="addFaqsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Question</h5>
                </div>
                <form action="" method="POST" class="form-horizontal">
                    <div class="modal-body">
                            <div class="form-group">
                                <label>Question</label>
                                <input type="text" name="question" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Answer</label>
                                <input type="text" name="answer" class="form-control" required>
                            </div>
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="addFaqs">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!---- Edit FAQs------>
    <div class="modal fade" id="editFaqsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Question</h5>
                </div>
                <form action="" method="POST" class="form-horizontal">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Question</label>
                            <input type="text" name="update_question" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Answer</label>
                            <input type="text" name="update_answer" class="form-control" required>
                        </div>   
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="editFaqs">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="hidden" name="upidFaqs" id="upidFaqs">
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!----------- Delete FAQs----------->
    <div class="modal fade" id="deleteFaqsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Question</h5>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="idfaqs" id="idfaqs">
                        <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                    <button type="submit" class="btn btn-primary" name="deleteFaqs">YES</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

    <?php
        $sql="SELECT * FROM contacts";
        $stmt=$conn->query($sql);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        $email=$row['email'];
        $facebook=$row['facebook'];
        $twitter=$row['twitter'];
        $instagram=$row['instagram'];
        $mobile=$row['mobile'];
    ?>

    <!---------- CONTACTS----------->
    <div class="container " style="margin-top: 20px; margin-bottom: 10px ">
            <div class="card" style="border-left: 7px solid lightblue;">
            <form class="form" style="padding: 20px 20px 0px 20px;">
                    <div class="card-header">                    
                        <div class="form-group text-center">                            
                            <h3 style="color: #17a2b8">CONTACTS</h3>   
                        </div>
                    </div>
                    <div class="card-body">   
                        <div class="form-group">
                            <label>Email</label>
                            <input disabled type="text" class="form-control" name="email" value=<?php echo $email ?>>
                        </div>
                        <div class="form-group">
                            <label>Facebook</label>
                            <input disabled type="text" class="form-control" name="facebook" value=<?php echo $facebook ?>>
                        </div>
                        <div class="form-group">
                            <label>Twitter</label>
                            <input disabled type="text" class="form-control" name="twitter" value=<?php echo $twitter ?>>
                        </div>
                        <div class="form-group">
                            <label>Instagram</label>
                            <input disabled type="text" class="form-control" name="instagram" value=<?php echo $instagram ?>>
                        </div>
                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input disabled type="text" class="form-control" name="mobile_num" value=<?php echo $mobile ?>>
                        </div>
                        
                        <br>
                        <div class="form-group text-center">
                            <button type="button" name="editProfile" class="btn btn-outline-info w-50" data-toggle="modal" data-target="#editContactModal" >Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <!-- Modal Edit Contacts-->
    <div class="modal fade" id="editContactModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Contacts</h5>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control" value=<?php echo $email ?> required>
                        </div>
                        <div class="form-group">
                            <label>Facebook</label>
                            <input type="text" name="facebook" class="form-control" value=<?php echo $facebook ?> required>
                        </div>
                        <div class="form-group">
                            <label>Twitter</label>
                            <input type="text" name="twitter" class="form-control" value=<?php echo $twitter ?> required>
                        </div>
                        <div class="form-group">
                            <label>Instagram</label>
                            <input type="text" name="insta" class="form-control" value=<?php echo $instagram ?> required>
                        </div>
                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input type="text" name="mobile_num" class="form-control" value=<?php echo $mobile ?> required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="btnSaveContacts">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    function delId(x){
        document.getElementById("uid").value=x;
    }
    function delIdfaqs(x){
        document.getElementById("idfaqs").value=x;
    }

    function getUserDetails(id, un, pwd){
        $('#upid').val(id);
        $('input[name="update_username"]').val(un);
        $('input[name="update_password"]').val(pwd);
    }

    function getFaqsDetails(id, un, pwd){
        $('#upidFaqs').val(id);
        $('input[name="update_question"]').val(un);
        $('input[name="update_answer"]').val(pwd);
    }
</script>