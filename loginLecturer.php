<?php
include 'api/connect.php';

if(isset($_POST['access'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM lecturer WHERE username='$username'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if (isset($row['password']) && password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['lecturerID'] = $row['lecturerID'];
            $_SESSION['username'] = $row['username'];
            header("Location: lecturer.php");
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
    <link rel="icon" href="images/logos/logo.png" type="image/png">
    <link rel="stylesheet" href="css/lecturer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Afacad:ital,wght@0,400..700;1,400..700&family=Bebas+Neue&family=Gabarito:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="icon" href="images/logos/logo.png" type="image/png">
    <title>LEC Academia</title>
</head>
<body>
    <div class="access">
        <form method="post">
            <div class="access-input">
                <h1>Sign In Lecturer</h1>
                <input type="text" name="username" id="email" placeholder="Username" autocomplete="off" required> 
                <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" required>
            </div>
            <input type="submit" class="btn" value="Access" name="access">
        </form>
    </div>
    
</body>
</html>