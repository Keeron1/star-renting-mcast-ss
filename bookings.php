<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Renting - Bookings</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="bg-light">
    <?php include("assets/nav.php")?>
    <main class="container">
        <header class="pt-3 pb-3">
            <h1 class="display-3 text-center fw-bold">Bookings</h1>
        </header>
        <section class="container">
            <?php

                //checking if the user is already logged in
                if(isset($_SESSION['userid']))
                {
                    $userid = $_SESSION['userid'];
                    require_once("assets/connection.php"); //connects to the database
                    if($_SESSION['role'] == 'admin')
                    {
                        echo "<table class='table'>
                        <tr>
                            <th>User ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Booking ID</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Payment Type</th>
                            <th>Payment</th>
                            <th>Delete</th>
                        </tr>";

                        $bookingsQuery = "SELECT * FROM bookings_tbl";
                        $bookingsResult = mysqli_query($conn, $bookingsQuery) or die("Error in query: ". mysqli_error($conn));

                        while ($row = mysqli_fetch_assoc($bookingsResult)){
                            $userQuery = "SELECT * FROM users_tbl WHERE userid = $row[userid]";
                            $userResult = mysqli_query($conn, $userQuery) or die("Error in query: ". mysqli_error($conn));
                            $userRow = mysqli_fetch_assoc($userResult);

                            $paymentQuery = "SELECT * FROM payments_tbl WHERE bookingid = $row[bookingid]";
                            $paymentResult = mysqli_query($conn, $paymentQuery) or die("Error in query: ". mysqli_error($conn));
                            $paymentRow = mysqli_fetch_assoc($paymentResult);

                            echo "<tr><td>$userRow[userid]</td><td>$userRow[firstname]</td> <td>$userRow[lastname]</td>  <td>$row[bookingid]</td><td>$row[check_in]</td><td>$row[check_out]</td><td>$paymentRow[payment_type]</td><td>€ $paymentRow[payment]</td> <td><a href='deletebooking.php?userid=$userRow[userid]&bookingid=$row[bookingid]'>Delete</a></td></tr>";
                        }
                    }
                    elseif($_SESSION['role'] == 'standard')
                    {
                        echo "<table class='table'>
                        <tr>
                            <th>User ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Booking ID</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Payment Type</th>
                            <th>Payment</th>
                        </tr>";

                        $bookingsQuery = "SELECT * FROM bookings_tbl WHERE userid = $userid";
                        $bookingsResult = mysqli_query($conn, $bookingsQuery) or die("Error in query: ". mysqli_error($conn));

                        while ($row = mysqli_fetch_assoc($bookingsResult)){
                            $userQuery = "SELECT * FROM users_tbl WHERE userid = $row[userid]";
                            $userResult = mysqli_query($conn, $userQuery) or die("Error in query: ". mysqli_error($conn));
                            $userRow = mysqli_fetch_assoc($userResult);

                            $paymentQuery = "SELECT * FROM payments_tbl WHERE bookingid = $row[bookingid]";
                            $paymentResult = mysqli_query($conn, $paymentQuery) or die("Error in query: ". mysqli_error($conn));
                            $paymentRow = mysqli_fetch_assoc($paymentResult);

                            echo "<tr><td>$userRow[userid]</td><td>$userRow[firstname]</td> <td>$userRow[lastname]</td>  <td>$row[bookingid]</td><td>$row[check_in]</td><td>$row[check_out]</td><td>$paymentRow[payment_type]</td><td>€ $paymentRow[payment]</td> <td><a href='deletebooking.php?userid=$userRow[userid]&bookingid=$row[bookingid]'>Delete</a></td></tr>";
                        }
                    }
                }
                else
                {
                    include('assets/403.php');
                    include("assets/footer.php");
                }
        ?>
        </section>  
    </main>
</body>
</html>