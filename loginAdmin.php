<?php
include 'api/connect.php';

if(isset($_POST['access'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM `admin` WHERE username='$username'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if (isset($row['password']) && password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['adminID'] = $row['adminID'];
            $_SESSION['username'] = $row['username'];
            header("Location: admin.php");
            exit();
        } else {
            echo "Password salah!";
        }
    } else {
        echo "username tidak ditemukan!";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.png" type="image/png">
    <link rel="stylesheet" href="css/admin.css">
    <title>Management System</title>
</head>
<body>
    <div class="access">
        <form method="post">
            <div class="access-input">
                <h1>Management System</h1>
                <input type="text" name="username" id="email" placeholder="Username" autocomplete="off" required> 
                <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" required>
            </div>
            <input type="submit" class="btn" value="Access" name="access">
        </form>
    </div>
    
</body>
</html>