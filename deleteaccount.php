<?php
    session_start(); 
    $userid = $_GET['userid'];

    require_once("assets/connection.php"); //connects to the database

    if(isset($_SESSION['userid']))
    {
        $sessionUserid = $_SESSION['userid'];

        if($sessionUserid == $userid)
        {
            $query = "DELETE FROM users_tbl WHERE userid = '$sessionUserid'";
            $result = mysqli_query($conn, $query) or die("Error in query: ". mysqli_error($conn));
            
            unset($_SESSION['userid']); //unsets session variable
            unset($_SESSION['role']); //unsets session variable
            session_destroy(); //destroy the session
        }

    }
    header("Location: index.php");
?>