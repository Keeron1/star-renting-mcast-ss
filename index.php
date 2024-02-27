<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Renting</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/main.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>  
    <!-- Bootstrap DatePicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="bg-light">
    <?php include("assets/nav.php")?>
    <main class="container">
        <header class="pt-3 pb-3">
            <h1 class="display-3 text-center fw-bold">Star Renting</h1>
        </header>
        <section class="row">
            <div class="col-sm-7 ">
                <img class="rounded img-fluid mx-auto d-block" src="assets/imgs/maisonette-img.webp" alt="Picture of the main room of the maisonette">
                <div>
                    <p class="text-center desc-text mt-1">
                        Our maisonette comes with a modern design including, 2 double beds, 2 sofa-beds, 2 bathrooms, an open plan kitchen with a living room and a small outdoor area. The place is quiet with friendly neighbours, close to bus stops, restaurants, stores and the seafront. Price per night is € 149.
                    </p>
                    <div class="mx-auto">
                        <ul id="amenities">
                            <li>Fully Functional Kitchen</li>
                            <li>Air Conditioning</li>
                            <li>Washing Machine</li>
                            <li>Street Parking</li>
                            <li>Television</li>
                            <li>Heating</li>
                            <li>Hot Water</li>
                            <li>WiFi</li>
                        </ul>
                    </div>
                </div>
            </div> 
            <div class="col-sm-5">
                <div class="container" style="margin: 0 auto;">
                    <p id="PricePerNight" class="fs-5 text-center">€ 149 Night</p>
                    <?php
                        function displayBookingForm()
                        {
                            echo '                    
                            <form name="booking-form" action="index.php" method="post"">
                                <div class="mb-3 mx-auto" style="max-width: 200px;">
                                    <label for="payment" class="form-label mb-0 ps-1">Payment Type</label>
                                    <select class="form-select" name="payment" id="payment">
                                        <option value=""></option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Revolut">Revolut</option>
                                        <option value="Paypal">Paypal</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>
                                <div class="mb-3 mx-auto" id="check-in-out" style="max-width: 200px;">
                                    <label class="form-label mb-0 ps-1">Check-in/Out</label>
                                    <br>
                                    <div class="input-daterange mx-auto" id="datepicker">
                                        <div class="input-group mb-1">
                                            <input type="text" class="input-sm form-control date-input" id="StartDate" name="StartDate"/>
                                            <span class="input-group-append input-group-text bg-white d-block">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                        <div class="input-group">
                                            <input type="text" class="input-sm form-control date-input" id="EndDate" name="EndDate"/>
                                            <span class="input-group-append input-group-text bg-white d-block">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p id="TotalPrice"></p>
                                    <button type="submit" name="submit" class="btn btn-dark">Book</button>
                                </div>
                            </form>
                        ';
                        }
                        
                    //user logged in
                     if(isset($_SESSION['userid'])) 
                     {
                        function getBookedDates($conn,$dateformat)
                        {
                            //gets all the unavailable dates
                            $disabledDates = [];

                            $newPaymentQuery = "SELECT check_in,check_out FROM bookings_tbl";
                            $newPaymentResult = mysqli_query($conn, $newPaymentQuery) or die("Error in query: ". mysqli_error($conn));
                            while ($row = mysqli_fetch_assoc($newPaymentResult)){
                                $startDate = strtotime($row['check_in']);
                                $endDate = strtotime($row['check_out']);
                                for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                                                
                                    $date = date($dateformat, $currentDate);
                                    array_push($disabledDates, $date);
                                }
                            }
                            return $disabledDates;
                        }

                        function setDisabledDates($disabledDates)
                        {
                            echo "<script>
                                    window.addEventListener('load', (event) => {
                                        $('#StartDate').datepicker('setDatesDisabled',".json_encode($disabledDates).")
                                        $('#EndDate').datepicker('setDatesDisabled',".json_encode($disabledDates).")
                                        });
                                </script>
                            ";
                        }

                        if(isset($_POST['submit'])){
                            $paymenttype = $_POST['payment'];
                            $startDate = $_POST['StartDate'];
                            $endDate = $_POST['EndDate'];
                            $userid = $_SESSION['userid'];

                            if(empty($paymenttype) || empty($startDate) || empty($endDate)){
                                echo '<p class="text-center">Please fill in all the boxes!</p>';
                                require_once("assets/connection.php"); //connects to the database
                                setDisabledDates(getBookedDates($conn,'d-m-Y'));
                                displayBookingForm();
                            }
                            else
                            {
                                require_once("assets/connection.php"); //connects to the database
                                $disabledDates = getBookedDates($conn,'Y-m-d');

                                $paymenttype = trim(mysqli_real_escape_string($conn,$paymenttype));
                                $startDate = trim(mysqli_real_escape_string($conn,$startDate));
                                $endDate = trim(mysqli_real_escape_string($conn,$endDate));

                                //chaing dates format to fit in dbs
                                $startDate = date("Y-m-d", strtotime($startDate));
                                $endDate = date("Y-m-d", strtotime($endDate));

                                //checking if the date is already booked or not
                                $notBooked = false;
                                foreach($disabledDates as $date)
                                {
                                    if($date == $startDate || $date == $endDate)
                                    {
                                        //date is already booked
                                        $notBooked = true;
                                    }
                                }
                                if($notBooked == true)
                                {
                                    echo '<p class="text-center">One of the dates has already been booked, Try again!</p>';
                                    setDisabledDates(getBookedDates($conn,'d-m-Y'));
                                    displayBookingForm();
                                }
                                else
                                {
                                    //inserts the new booking
                                    $newBookingQuery = "INSERT INTO bookings_tbl (check_in, check_out, bookingid, userid) VALUES ('$startDate','$endDate',NULL,'$userid');";
                                    $newBookingResult = mysqli_query($conn, $newBookingQuery) or die("Error in query: ". mysqli_error($conn));
                                    
                                    //gets the new booking's id
                                    $getBookingidQuery = "SELECT bookingid FROM bookings_tbl WHERE check_in = '$startDate' AND check_out = '$endDate';";
                                    $getBookingidResult = mysqli_query($conn, $getBookingidQuery) or die("Error in query: ". mysqli_error($conn));
                                    $row = mysqli_fetch_assoc($getBookingidResult);

                                    //get's the amount the guest will have to pay
                                    $dateDiff = strtotime($endDate) - strtotime($startDate);
                                    $days = abs($dateDiff / (60 * 60) / 24);
                                    $payment = $days * 149;

                                    //inserts the new booking's payment information
                                    $newPaymentQuery = "INSERT INTO payments_tbl (payment_type, payment, userid, bookingid) VALUES ('$paymenttype','$payment',$userid,'$row[bookingid]');";
                                    $newPaymentResult = mysqli_query($conn, $newPaymentQuery) or die("Error in query: ". mysqli_error($conn));

                                    echo '<div class="text-center">
                                            <p class="fs-4">Successful booking!</p>
                                            <p class="mb-1">Enjoy your stay</p> 
                                            <a class=" text-decoration-none" href="bookings.php">Check your booking information here</a>
                                        </div>
                                    ';
                                } 
                            }
                        }
                        else
                        {
                            require_once("assets/connection.php"); //connects to the database
                            setDisabledDates(getBookedDates($conn,'d-m-Y'));
                            displayBookingForm();
                        }
                     }
                     else //user is not logged in
                     {
                        echo '<div class="text-center">
                                <p class="fs-4">Interested in booking?</p>
                                <a class=" text-decoration-none mb-1 d-block" href="register.php">Make an account and book!</a>  
                                <a class=" text-decoration-none" href="login.php">Already have an account? Login</a>
                            </div>';
                     }
                    ?>
                </div>
            </div>
        </section>
    </main>
    <?php include("assets/footer.php")?>
    <script type="text/javascript">
        $(function() {
            $('#datepicker').datepicker(
                {
                    inputs: $('#StartDate, #EndDate'),
                    todayHighlight: true,
                    format: "dd-mm-yyyy",
                    startDate: '0d',
                    // datesDisabled:["06-06-2023"]
                })
        });
    </script> 
</body>
</html>