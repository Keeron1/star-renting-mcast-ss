<?php
    session_start();

    unset($_SESSION['userid']); //unsets session variable
    unset($_SESSION['role']); //unsets session variable
    session_destroy(); //destroy the session

    //redirect back to home page
    header("Location: index.php");
?>

