<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Renting - My Account</title>
    <link rel="stylesheet" href="css/main.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="bg-light">
    <?php include("assets/nav.php")?>
    <main class="container">
        <header class="pt-3 pb-3">
            <h1 class="display-3 text-center fw-bold">My Account</h1>
        </header>
        <section class="container">
            
            <?php
                function isNameValid($name)
                {
                    if(!is_string($name))
                    {
                        echo '<p class="text-center">Invalid type of name!</p>';
                        return false;
                    }
                    if(strlen($name) > 15 )
                    {
                        echo '<p class="text-center">namecannot be longer than 15 characters!</p>';
                        return false;
                    } 
                    return true;
                }
                function displayAccountForm()
                {
                    echo '<form action="myaccount.php" enctype="multipart/form-data" method="post">
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
                            <label for="contact" class="form-label mb-0 ps-1">Contact Number</label>
                            <input type="tel" class="form-control" name="contact" id="contact">
                        </div>
                        <div class="row mb-2">
                            <label for="profilepicture" class="form-label mb-0 ps-1">Profile Picture</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                                <input type="file" class="form-control" accept="image/jpg, image/jpeg, image/png" name="profilepicture" id="profilepicture">
                            </div>
                            <div class="row">
                                <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                        </div>
                    </form>';
                }

                //checking if the user is already logged in
                if(isset($_SESSION['userid']))
                {
                    require_once("assets/connection.php"); //connects to the database
                    $userid = $_SESSION['userid'];

                    if(isset($_POST['submit'])){
                        $firstname = $_POST['firstname'];
                        $lastname = $_POST['lastname'];
                        $contactnum = $_POST['contact'];
                        $profilepicture = $_FILES['profilepicture']['name']; 
                        $successfulChange = false;

                        if(!empty($firstname))
                        {
                            if(isNameValid($firstname))
                            {
                                $firstname = trim(mysqli_real_escape_string($conn,$firstname));

                                $firstnameQuery = "UPDATE users_tbl SET firstname = '$firstname' WHERE userid = $userid";
                                $firstnameResult = mysqli_query($conn, $firstnameQuery) or die("Error in query: ". mysqli_error($conn));
                                $successfulChange = true;
                            }
                        }

                        if(!empty($lastname))
                        {
                            if(isNameValid($lastname))
                            {
                                $lastname = trim(mysqli_real_escape_string($conn,$lastname));

                                $lastnameQuery = "UPDATE users_tbl SET lastname = '$lastname' WHERE userid = $userid";
                                $lastnameResult = mysqli_query($conn, $lastnameQuery) or die("Error in query: ". mysqli_error($conn));
                                $successfulChange = true;
                            }
                        }

                        if(!empty($contactnum))
                        {
                            $contactnum = trim(mysqli_real_escape_string($conn,$contactnum));

                            //query to check if user with the same information already exists
                            $checkUserQuery = "SELECT count(*) FROM users_tbl WHERE contactnum = '$contactnum'";
                            
                            // sends the query to the database or gives us error
                            $checkResult = mysqli_query($conn, $checkUserQuery) or die("Error in query: ". mysqli_error($conn));
                            
                            //mysqli_fetch_row - Fetches one row of data  from the result set and returns it as an enumerated array
                            $row = mysqli_fetch_row($checkResult);
                            if($row[0] > 0) //need to make check to see if length is greather than 0
                            {
                                echo '<p class="text-center">A user with the same contact no already exists!</p>';
                            }
                            else
                            {
                                $contactNumQuery = "UPDATE users_tbl SET contactnum = '$contactnum' WHERE userid = $userid";
                                $contactNumResult = mysqli_query($conn, $contactNumQuery) or die("Error in query: ". mysqli_error($conn));
                                $successfulChange = true;
                            }
                        }

                        if(!empty($profilepicture))
                        {
                            $profilepicture = trim(mysqli_real_escape_string($conn,$profilepicture));

                            //explodes the file name into an array
                            $temp = explode(".", $profilepicture);
                            //sets the new file name
                            $newFileName = $userid.'.'.end($temp);

                            //combines the new file name and directory of where the image will be stored
                            $upfile = "assets/users/$userid/".$newFileName;

                            //gets the old image url
                            $getImagePathQuery = "SELECT profilepicture FROM users_tbl WHERE userid = $userid";
                            $getImageResult = mysqli_query($conn, $getImagePathQuery) or die("Error in query: ". mysqli_error($conn));
                            $getImageRow = mysqli_fetch_assoc($getImageResult);

                            //deletes the old image
                            unlink($getImageRow['profilepicture']);

                            //uploads the image
                            move_uploaded_file($_FILES['profilepicture']['tmp_name'], $upfile);
                        
                            //uploads the image path to the database
                            $uploadImagePathQuery = "UPDATE users_tbl SET profilepicture = '$upfile' WHERE userid = $userid";
                            $uploadImageResult = mysqli_query($conn, $uploadImagePathQuery) or die("Error in query: ". mysqli_error($conn));
                            $successfulChange = true;
                        }

                        if($successfulChange == true) {
                            echo '<p class="text-center">Changes have been applied successfully!</p>';
                        }
                    }

                    $getProfilePicQuery = "SELECT userid,profilepicture FROM users_tbl WHERE userid = '$userid'";

                    $profilePicResult = mysqli_query($conn, $getProfilePicQuery) or die("Error in query: ". mysqli_error($conn));
                    $row = mysqli_fetch_assoc($profilePicResult);
                    echo "<img class='rounded-circle mb-4' style='width: 150px;' src='$row[profilepicture]' alt='Your profile picture'>";
                    echo '<a class="text-center btn btn-primary d-block" style="width:150px;" href="deleteaccount.php?userid='.$userid.'">Delete Account</a>';

                    displayAccountForm();
                }
                else
                {
                    include('assets/403.php');
                }
        ?>
        </section>  
    </main>
    <?php include("assets/footer.php")?>
</body>
</html>