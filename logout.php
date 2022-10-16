<?php
    session_start();
    if(isset($_SESSION['access'])){
        session_destroy();
        header("location: admin.php");
    }
    if(isset($_SESSION['user'])){
        session_destroy();
        header("location: User/login.php");
    }
    
 ?>