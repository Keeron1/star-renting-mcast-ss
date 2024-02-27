<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Renting - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Validiate.js -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>
    <script src="scripts/validate.js"></script>
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="bg-light">
    <?php include("assets/nav.php")?>
    <main class="container">
        <header class="pt-3 pb-3">
            <h1 class="display-3 text-center fw-bold">Login</h1>
        </header>
        <section class="container">
            <?php
            function displayLogin()
            {
                echo'<form action="login.php" method="post">
                        <div class="col-md-5 mx-auto">
                            <div class="row mb-0 form-check form-switch">
                                <input class="form-check-input" name="useEmail" type="checkbox" id="useEmail">
                                <label for="useEmail" class="form-check-label mb-0 ps-1">Login using Email / Contact No</label>
                            </div>
                            <div class="row mb-2">
                                <input type="text" name="email" class="form-control" id="email">
                            </div>
                            <div class="row mb-2">
                                <label for="password" class="form-label mb-0 ps-1">Password</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                            <div class="row">
                                <button type="submit" name="submit" class="btn btn-primary">Sign in</button>
                                <a class="ps-0 text-decoration-none" href="register.php">Don\'t have an account? Register</a>
                            </div>
                            
                        </div>
                    </form>';
            }
            function isPasswordValid($password)
            {
                if(strlen($password) < 3)
                {
                    echo '<p class="text-center">Invalid Credentials!</p>';
                    return false;
                }
                return true;
            }
            function isEmailValid($email)
            {
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    echo '<p class="text-center">Invalid email address!</p>';
                    return false;
                }
                return true;
            }
            
            //checking if the user is already logged in
            if(isset($_SESSION['userid']))
            {
                echo '<p class="text-center">You are already logged in!</p>';
                header("Location: index.php");
            }
            else
            {
                if(isset($_POST['submit'])){
                    $emailContact = $_POST['email']; //email / contact input
                    $password = $_POST['password'];

                    //if it is set it means it is checked = the user wants to login with mobile
                    if(isset($_POST['useEmail']))
                    {
                        $useEmail = false;
                    }
                    else
                    {
                        $useEmail = true;
                    }
    
                    if(empty($password) || empty($emailContact))
                    {
                        echo '<p class="text-center">Both entries need to be filled up!</p>';
                        displayLogin();
                    }
                    else
                    {
                        if(!isPasswordValid($password))
                        {
                            displayLogin();
                        }
                        else
                        {
                            if($useEmail == true)
                            {
                                if(!isEmailValid($emailContact))
                                {
                                    displayLogin();
                                    include("assets/footer.php");
                                    exit();
                                }
                                $loginQuery = "SELECT count(*),userid,password,role FROM users_tbl WHERE email = '$emailContact'";
                            }
                            else
                            {
                                $loginQuery = "SELECT count(*),userid,password,role FROM users_tbl WHERE contactnum = '$emailContact'";
                            }
                            require_once("assets/connection.php"); //connects to the database

                            $emailContact = trim(mysqli_real_escape_string($conn,$emailContact));
                            $password = trim(mysqli_real_escape_string($conn,$password));

                            $loginResult = mysqli_query($conn, $loginQuery) or die("Error in query: ". mysqli_error($conn));
                            $row = mysqli_fetch_row($loginResult);
                            if($row[0] > 0)
                            {
                                if(password_verify($password, $row[2]))
                                {
                                    echo '<p class="text-center">You have been logged in!</p>';
                                    $_SESSION['userid'] = $row[1];
                                    $_SESSION['role'] = $row[3];
                                    header("Location: index.php");
                                }
                                else
                                {
                                    echo '<p class="text-center">Invalid Credentials!</p>';
                                    displayLogin();
                                }
                            }
                            else
                            {
                                echo '<p class="text-center">Invalid Credentials!</p>';
                                displayLogin();
                            }
                        }
                    }
                }
                else
                {
                    displayLogin();
                }
            }
        ?>
        </section>  
    </main>
    <?php include("assets/footer.php")?>
</body>
</html>