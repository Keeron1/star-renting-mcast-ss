<?php
    session_start(); 
    $userid = $_GET['userid'];
    $bookingid = $_GET['bookingid'];

    require_once("assets/connection.php"); //connects to the database

    if(isset($_SESSION['userid']))
    {
        $sessionUserid = $_SESSION['userid'];
        $sessionRole = $_SESSION['role'];

        if($sessionUserid == $userid)
        {
            $query = "DELETE FROM bookings_tbl WHERE bookingid = '$bookingid' and userid = '$sessionUserid'";
            $result = mysqli_query($conn, $query) or die("Error in query: ". mysqli_error($conn));
        }
        else if($sessionRole == "admin")
        {
            $query = "DELETE FROM bookings_tbl WHERE bookingid = '$bookingid' and userid = '$userid'";
            $result = mysqli_query($conn, $query) or die("Error in query: ". mysqli_error($conn));
        }

    }
    header("Location: bookings.php");
?>