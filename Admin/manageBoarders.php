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

    //code to add account
    if(isset($_POST['add'])){
        $sql="INSERT INTO boarders (first_name, last_name, sex, birthdate, year, contact_number, contact_person, permanent_add, email_add, username, password) 
        VALUES (:fname, :lname, :sex, :birthdate, :year, :cnumber, :cperson, :address, :email, :uname, :pword)";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':fname'=>$_POST['fname'],
                        ':lname'=>$_POST['lname'],
                        ':sex'=>$_POST['sex'],
                        ':birthdate'=>$_POST['birthdate'],
                        ':year'=>$_POST['year'],
                        ':cnumber'=>$_POST['cnumber'],
                        ':cperson'=>$_POST['cperson'],
                        ':address'=>$_POST['address'],
                        ':email'=>$_POST['email'],
                        ':uname'=>$_POST['uname'],
                        ':pword'=>$_POST['pword']]);

        $sql="SELECT * FROM boarders ORDER BY boarder_id DESC LIMIT 1";
        $stmt=$conn->query($sql);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $boarder_id=$row['boarder_id'];

        $sql="INSERT INTO balance (boarder_id, amount, status) VALUES (:id, :amount, :status)";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':id'=>$boarder_id,
                        ':amount'=> 1000,
                        ':status'=> "UNPAID"]);
        
        echo '<script>alert("1 record added");</script>';
    }
    

            //code to Edit account
        if(isset($_POST['edit'])){         
            $sql="UPDATE boarders SET first_name=:fname, last_name=:lname, sex=:sex, birthdate=:birthdate,
            year=:year, contact_number=:cnumber, contact_person=:cperson, permanent_add=:address, email_add=:email, 
            username=:uname, password=:pword WHERE boarder_id=:update_id";         
            $stmt=$conn->prepare($sql);         
            $stmt->execute([':fname'=>$_POST['update_fname'],
            ':lname'=>$_POST['update_lname'],
            ':sex'=>$_POST['update_sex'],    
            ':birthdate'=>$_POST['update_birthdate'], 
            ':year'=>$_POST['update_year'],                   
            ':cnumber'=>$_POST['update_cnumber'],    
            ':cperson'=>$_POST['update_cperson'], 
            ':address'=>$_POST['update_address'], 
            ':email'=>$_POST['update_email'],                      
            ':uname'=>$_POST['update_username'],                         
            ':pword'=>$_POST['update_password'],                         
            ':update_id'=>$_POST['uid']]);
            echo '<script>alert("An account has been updated")</script>';    
            }




        // if($_POST['acct_type']=="Admin"){
        //     $sql="SELECT * FROM users WHERE acct_type='Admin'";
        //     $stmt=$conn->query($sql);
        //     $row=$stmt->fetch(PDO::FETCH_ASSOC);
        //     $count=$stmt->rowCount();
        //     if($count<3){
        //         $sql="INSERT INTO users (first_name, last_name, sex, contact_number, username, password, acct_type) VALUES (:fname, :lname, :sex, :cnumber, :uname, :pword, :acct_type)";
        //         $stmt=$conn->prepare($sql);
        //         $stmt->execute([':fname'=>$_POST['fname'],
        //                         ':lname'=>$_POST['lname'],
        //                         ':sex'=>$_POST['sex'],
        //                         ':cnumber'=>$_POST['cnumber'],
        //                         ':uname'=>$_POST['uname'],
        //                         ':pword'=>$_POST['pword'],
        //                         ':acct_type'=>$_POST['acct_type']]);
        //         echo '<script>alert("1 record added");</script>';
        //     }
        //     else{
        //         $message="3 Admins Only";
        //     }
        // }
        // else{
            
        // }
    

    // DELETE RECORD
    if(isset($_POST['delete'])){
        $sql="DELETE FROM boarders WHERE boarder_id=:uid";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':uid'=>$_POST['uid']]);
        echo '<script>alert("1 record deleted");</script>';
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
    <title>Manage Boarders</title>
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
                <li class="nav-item active">
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
    <h1></h1>
    <div class="container-fluid" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header">
                <h5 class="d-inline">List of Boarders</h5>
                <span><button class="btn btn-success" data-toggle="modal" data-target="#addModal"><i class="fas fa-user-plus"></i></button></span>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Sex</th>
                            <th>Birthdate</th>
                            <th>Year</th>
                            <th>Contact Number</th>
                            <th>Contact Person</th>
                            <th>Permanent Address</th>
                            <th>Email Address</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql="SELECT * FROM boarders";
                            $stmt=$conn->query($sql);
                            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <td><?php echo $row['boarder_id']; ?></td>
                            <td><?php echo $row['first_name']; ?></td>
                            <td><?php echo $row['last_name']; ?></td>
                            <td><?php echo $row['sex']; ?></td>
                            <td><?php echo $row['birthdate']; ?></td>
                            <td><?php echo $row['year']; ?></td>
                            <td><?php echo $row['contact_number']; ?></td>
                            <td><?php echo $row['contact_person']; ?></td>
                            <td><?php echo $row['permanent_add']; ?></td>
                            <td><?php echo $row['email_add']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo str_repeat("*",strlen($row['password'])); ?></td>
                            <td>
                                <button type="button" class="btn btn-primary"><i class="fas fa-pen" data-toggle="modal" data-target="#editModal"
                                    onclick="getUserDetails(<?php echo $row['boarder_id']?>,'<?php echo $row['first_name'] ?>','<?php echo $row['last_name'] ?>',
                                    '<?php echo $row['sex'] ?>','<?php echo $row['birthdate'] ?>','<?php echo $row['year'] ?>','<?php echo $row['contact_number'] ?>',
                                    '<?php echo $row['contact_person'] ?>','<?php echo $row['permanent_add'] ?>','<?php echo $row['email_add'] ?>',
                                    '<?php echo $row['username'] ?>', '<?php echo $row['password'] ?>');">
                                    </i></button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" 
                                onClick="delId(<?php echo $row['boarder_id'] ?>)"><i class="fas fa-trash"></i></button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <input type="text" name="fname" class="form-control" placeholder="First name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="lname" class="form-control" placeholder="Last name" required>
                        </div>
                        <div class="form-group">
                            <label for="sex" class="control-label">Sex:</label>
                            <select name="sex" id="" class="form-control" required>
                                <option value="" readonly>-------Select-------</option>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                            </select>                           
                        </div>
                        <div class="form-group">
                            <input type="date" name="birthdate" class="form-control" placeholder="birthdate" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="year" class="form-control" placeholder="year" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="cnumber" class="form-control" placeholder="Contact Number" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="cperson" class="form-control" placeholder="Contact Person" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="address" class="form-control" placeholder="Permanent Address" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="email" class="form-control" placeholder="Email Address" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="uname" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="pword" class="form-control" placeholder="Password" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="add">Save</button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
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

          <!------------------- Modal to Edit User -------------------->

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Boarder's Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" class="form-horizontal">
                    <div class="modal-body">
                        <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="control-label"> First Name:</label>
                            <input type="text" name="update_fname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label"> Last Name:</label>
                            <input type="text" name="update_lname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="sex" class="control-label">Sex:</label>
                            <select name="update_sex" id="" class="form-control" required>
                                <option value="" readonly>-------Select-------</option>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                            </select>                           
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Birthdate:</label>
                            <input type="text" name="update_birthdate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Year:</label>
                            <input type="text" name="update_year" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Contact Number:</label>
                            <input type="text" name="update_cnumber" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Contact Person:</label>
                            <input type="text" name="update_cperson" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Permanent Address:</label>
                            <input type="text" name="update_address" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Email Address:</label>
                            <input type="text" name="update_email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Username:</label>
                            <input type="text" name="update_username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Password:</label>
                            <input type="password" name="update_password" class="form-control" required>
                        </div>   
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
      



</body>
</html>

<script>
    function delId(x){
        document.getElementById("uid").value=x;
    }

    function getUserDetails(id, fn, ln, sex, bdate, year, cnum, cper, add, ema, un, pwd)
    {
    $('#upid').val(id);
    $('input[name="update_fname"]').val(fn);
    $('input[name="update_lname"]').val(ln);
    $('input[name="update_sex"]').val(sex);
    $('input[name="update_birthdate"]').val(bdate);
    $('input[name="update_year"]').val(year);
    $('input[name="update_cnumber"]').val(cnum);
    $('input[name="update_cperson"]').val(cper);
    $('input[name="update_address"]').val(add);
    $('input[name="update_email"]').val(ema);
    $('input[name="update_username"]').val(un);
    $('input[name="update_password"]').val(pwd);
    }
</script>