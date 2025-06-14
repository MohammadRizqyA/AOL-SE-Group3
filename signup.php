<?php
include 'api/connect.php';
include 'functions.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signUp'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $phoneNumber = $conn->real_escape_string($_POST['phoneNum']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $status = $conn->real_escape_string($_POST['status']);
    $dob = $conn->real_escape_string($_POST['DOB']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($gender == "Male" || $gender == "Female") {
        $checkEmail = "SELECT studentID FROM student WHERE email = '$email'";
        $result = $conn->query($checkEmail);

        if ($result->num_rows > 0) {
            echo "Email Address Already Exists!";
        } else {
            $studentID = generateCustomID1($conn, "S", "student", "studentID");

            $insertQuery = "INSERT INTO student (studentID, `name`, phoneNumber, DOB, gender, `status`, address, email, `password`) 
                            VALUES ('$studentID', '$name', '$phoneNumber', '$dob', '$gender', '$status', '$address', '$email', '$password')";

            if ($conn->query($insertQuery) === TRUE) {
                header("Location: signin.php");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } else {
        echo "Pilihan gender tidak valid!";
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/signin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Afacad:ital,wght@0,400..700;1,400..700&family=Bebas+Neue&family=Gabarito:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="icon" href="images/logos/logo.png" type="image/png">
    <title>Academia Plus</title>
</head>
<body>
    <div class="content">
        <div class="left">
        </div>
        <div class="right">
            <div class="container" id="signUp">
                <h1 class="form-title">Register</h1>
                <h2>Create new account to start shopping!</h2>
                <form method="post">
                        <div class="nameForm">
                            <div class="input-group">
                                <input class="name" type="text" name="name" id="name" placeholder="Enter your name" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="number" name="phoneNum" id="phoneNum" placeholder="Enter your phone number" autocomplete="off" required>
                        </div>
                        <div class="addressGender">
                            <div class="input-group">
                                <textarea id="address" name="address" placeholder="Enter your address" autocomplete="off"></textarea>
                            </div>
                            <div class="selection-form">
                                <div class="input-group">
                                    <select name="gender" class="genderCat "id="genderCat" required>
                                        <option class="unselected" value="">Choose your gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <select name="status" class="genderCat "id="genderCat" required>
                                        <option class="unselected" value="">Status</option>
                                        <option value="Employee">Employee</option>
                                        <option value="Unemployed">Unemployed</option>
                                        <option value="Entrepreneur">Entrepreneur</option>
                                        <option value="University Student">University Student</option>
                                        <option value="High School Student">High School Student</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <input type="date" name="DOB" id="DOB" autocomplete="off" required>
                                </div>
                            </div>
                            
                        </div>
                        
                    
                        <div class="input-group">
                            <input type="email" name="email" id="email" placeholder="Enter your email" autocomplete="off" required>
                        </div>
                        <div class="input-group">
                            <input type="password" name="password" id="password" placeholder="Enter your password" autocomplete="off" required>
                        </div>
                        
                        
                        <input type="submit" class="btn" value="Sign Up" name="signUp">
                </form>
                <div class="links">
                    <div class="have">
                        <p>Already have an account?</p>
                        <a href="signin.php">Sign In</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</body>
</html>