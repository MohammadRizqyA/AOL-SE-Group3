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