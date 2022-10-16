<?php
    $servername = "localhost";
    $dbname="Boarding_House_Management_System";
    $username = "root";
    $password = "";
    try{
        $conn=new PDO("mysql:host=$servername; dbname=$dbname",$username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo "Connection failed".$e->getMessage();
    }
?>