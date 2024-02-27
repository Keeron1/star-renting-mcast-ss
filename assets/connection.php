<?php
    $conn = mysqli_connect("localhost", "root", "", "starrenting_db");
    if (mysqli_connect_errno()){
    echo "Error: Could not connect to database. Please try again later";
    exit;
    } 
?>