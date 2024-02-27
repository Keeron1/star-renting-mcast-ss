<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Renting - Register Account</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="bg-light">
    <?php
    include("assets/nav.php")?>
    <main class="container">
        <header class="pt-3 pb-3">
            <h1 class="display-3 text-center fw-bold">Register</h1>
        </header>
        <section class="container">
            <?php
                function isEmailValid($email)
                {
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                    {
                        echo '<p class="text-center">Invalid email address!</p>';
                        return false;
                    }
                    return true;
                }
                function isPasswordValid($password)
                {
                    if(strlen($password) < 3)
                    {
                        echo '<p class="text-center">Password must be at least 3 characters!</p>';
                        return false;
                    }
                    return true;
                }
                function isNameValid($firstname,$lastname)
                {
                    if(!is_string($firstname) || !is_string($lastname))
                    {
                        echo '<p class="text-center">Invalid type of first name and last name!</p>';
                        return false;
                    }
                    if(strlen($firstname) > 15 || strlen($lastname) > 15)
                    {
                        echo '<p class="text-center">First name and last name cannot be longer than 15 characters!</p>';
                        return false;
                    } 
                    return true;
                }
                function displayForm()
                {
                    echo '<form action="register.php" enctype="multipart/form-data" method="post">
                        <div class="col-md-5 mx-auto">
                            <div class="row mb-2">
                                <label for="firstname" class="form-label mb-0 ps-1">First Name</label>
                                <input type="text" maxlength="15" class="form-control" name="firstname" id="firstname">
                            </div>
                            <div class="row mb-2">
                                <label for="lastname" class="form-label mb-0 ps-1">Last Name</label>
                                <input type="text" maxlength="15" class="form-control" name="lastname" id="lastname">
                            </div>
                            <div class="row mb-2">
                                <label for="dob" class="form-label mb-0 ps-1">Date of Birth</label>
                                <input type="date" class="form-control" name="dob" id="dob"">
                            </div>
                            <div class="row mb-2">
                                <label for="gender" class="form-label mb-0 ps-1">Gender</label>
                                <select class="form-select" name="gender" id="gender">
                                    <option value=""></option>
                                    <option value="Male" >Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="row mb-2">
                                <label for="email" class="form-label mb-0 ps-1">Email</label>
                                <input type="email" class="form-control" name="email" id="email">
                            </div>
                            <div class="row mb-2">
                                <label for="password" class="form-label mb-0 ps-1">Password</label>
                                <input type="password" minlength="3" class="form-control" name="password" id="password">
                            </div>
                            <div class="row mb-2">
                                <label for="contact" class="form-label mb-0 ps-1">Contact Number</label>
                                <input type="tel" class="form-control" name="contact" id="contact">
                            </div>
                            <div class="row mb-2">
                            <label for="profilepicture" class="form-label mb-0 ps-1">Profile Picture</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                                <input type="file" class="form-control" accept="image/jpg, image/jpeg, image/png" name="profilepicture" id="profilepicture">
                            </div>
                            <div class="row">
                                <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
                                <a class="ps-0 text-decoration-none" href="login.php">Already have an account? Login</a>
                            </div>
                        </div>
                    </form>';
                }
                if(isset($_SESSION['userid']))
                {
                    echo '<p class="text-center">You are already logged in!</p>';
                    header("Location: index.php");
                }
                else
                {
                    //if the user submitted the form
                    if(isset($_POST['submit'])){
                        // inputs
                        $firstname = $_POST['firstname'];
                        $lastname = $_POST['lastname'];
                        $dob = $_POST['dob'];
                        $gender = $_POST['gender'];
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        $contactnum = $_POST['contact'];
                        $profilepicture = $_FILES['profilepicture']['name'];

                        //checks if values are empty or not
                        if(empty($firstname) || empty($lastname) || empty($dob) || empty($gender) || empty($email) || empty($password) || empty($contactnum) || empty($contactnum) || empty($profilepicture))
                        {
                            echo '<p class="text-center">Check all form entires and try again!</p>';
                            displayForm();
                        }
                        else
                        {   //checking if email password and name aren't valid
                            if(!isEmailValid($email) || !isPasswordValid($password) || !isNameValid($firstname,$lastname))
                            {
                                displayForm();
                            }
                            else
                            {
                                require_once("assets/connection.php"); //connects to the database
                                //remove special characters and whitespaces
                                $firstname = trim(mysqli_real_escape_string($conn,$firstname));
                                $lastname = trim(mysqli_real_escape_string($conn,$lastname));
                                $dob = trim(mysqli_real_escape_string($conn,$dob));
                                $gender = trim(mysqli_real_escape_string($conn,$gender));
                                $email = trim(mysqli_real_escape_string($conn,$email));
                                $password = trim(mysqli_real_escape_string($conn,$password));
                                $contactnum = trim(mysqli_real_escape_string($conn,$contactnum));
                                $profilepicture = trim(mysqli_real_escape_string($conn,$profilepicture));

                                //encrypting the user's password by using the BCRYPT algorithm
                                $password = password_hash($password, PASSWORD_DEFAULT);

                                //query to check if user with the same information already exists
                                $checkUserQuery = "SELECT count(*) FROM users_tbl WHERE email = '$email' or contactnum = '$contactnum'";
                                
                                // sends the query to the database or gives us error
                                $checkResult = mysqli_query($conn, $checkUserQuery) or die("Error in query: ". mysqli_error($conn));

                                //mysqli_fetch_row - Fetches one row of data  from the result set and returns it as an enumerated array
                                $row = mysqli_fetch_row($checkResult);
                                if($row[0] > 0) //need to make check to see if length is greather than 0
                                {
                                    echo '<p class="text-center">A user with the same email / contact no already exists!</p>';
                                    displayForm();
                                    exit();
                                }
                                else
                                {
                                    //query to insert new user into database
                                    $newUserQuery = "INSERT INTO users_tbl (firstname, lastname, dob, gender, email, password, contactnum, profilepicture, role, userid) VALUES ('$firstname', '$lastname', '$dob', '$gender', '$email','$password', '$contactnum', '','standard', NULL);";
                                    
                                    $getNewUserIdQuery = "SELECT userid FROM users_tbl WHERE email = '$email'";

                                    $newUserResult = mysqli_query($conn, $newUserQuery) or die("Error in query: ". mysqli_error($conn));
                                    
                                    $getNewUserIdResultQuery = mysqli_query($conn, $getNewUserIdQuery) or die("Error in query: ". mysqli_error($conn));
                                    $row = mysqli_fetch_assoc($getNewUserIdResultQuery);
                                    
                                    //creating new folder for the new user
                                    if (!mkdir("assets/users/$row[userid]", 0777, true))
                                    {
                                        die('Error creating directory');
                                    }

                                    //explodes the file name into an array
                                    $temp = explode(".", $profilepicture);
                                    //sets the new file name
                                    $newFileName = $row['userid'].'.'.end($temp);

                                    //combines the new file name and directory of where the image will be stored
                                    $upfile = "assets/users/$row[userid]/".$newFileName;
                                    //uploads the image
                                    if(move_uploaded_file($_FILES['profilepicture']['tmp_name'], $upfile))

                                    //uploads the image path to the database
                                    $uploadImagePathQuery = "UPDATE users_tbl SET profilepicture = '$upfile' WHERE userid = $row[userid]";
                                    $uploadImageResult = mysqli_query($conn, $uploadImagePathQuery) or die("Error in query: ". mysqli_error($conn));

                                    echo '<p class="text-center">New user has been created successfully!</p>';
                                }
                            }
                        }
                    }
                else //user has not yet submitted the form
                {
                    displayForm();
                }
                } 
            ?>  
        </section>  
    </main>
    <?php include("assets/footer.php")?>
</body>
</html>