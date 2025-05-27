<?php
include 'api/connect.php';

if(isset($_POST['signIn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM student WHERE email='$email'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if (isset($row['password']) && password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['studentID'] = $row['studentID'];
            $_SESSION['email'] = $row['email'];
            header("Location: homepage.php");
            exit();
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Email tidak ditemukan!";
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
            <div class="container">
                <h1>Sign In</h1>
                <h2>Please sign in to continue your study</h2>
                <form method="post">
                    <div class="input-group"> 
                        <input type="email" name="email" id="email" placeholder="Enter your email" autocomplete="off" required> 
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" id="password" placeholder="Enter your password" autocomplete="off" required>
                    </div>
                    <input type="submit" class="btn" value="Sign In" name="signIn">
                </form>
                <div class="links">
                    <div class="have">
                        <p>Don't have an accoount?</p>
                        <a href="signup.php">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</body>
</html>