<?php
include 'api/connect.php';

function generateCustomID($conn, $prefix, $table, $idColumn) {
    // Ambil ID terakhir yang sesuai prefix, urutkan menurun, ambil 1
    $query = "SELECT $idColumn FROM $table WHERE $idColumn LIKE '$prefix%' ORDER BY $idColumn DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Ambil hanya bagian angka setelah prefix, misalnya 'C01' => '01'
        $lastNumber = (int)substr($row[$idColumn], strlen($prefix)); 
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    // Gabungkan prefix dengan angka baru, padding hingga 2 digit angka (misalnya: C01)
    $customID = $prefix . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

    return $customID;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signUp'])) {
    // Ambil dan sanitasi input
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
            $studentID = generateCustomID($conn, "S", "student", "studentID");

            $insertQuery = "INSERT INTO student (studentID, `name`, phoneNumber, DOB, gender, `status`, address, email, `password`) 
                            VALUES ('$studentID', '$name', '$phoneNumber', '$dob', '$gender', '$status', '$address', '$email', '$password')";

            if ($conn->query($insertQuery) === TRUE) {
                header("Location: signin.php");
                exit(); // Good practice
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
    <link rel="icon" href="images/logo.png" type="image/png">
    <link rel="stylesheet" href="css/signin.css">
    <title>Sign-in</title>
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