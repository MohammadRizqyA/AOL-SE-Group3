<?php
session_start();
include 'api/connect.php';
include 'exploreDropdown.php';

if (!isset($_SESSION['studentID'])) {
    header("Location: signin.php");
}
$studentID = $_SESSION['studentID'];

if (!isset($_GET['exerciseID']) || empty($_GET['exerciseID'])) {
    die("No lesson ID provided.");
}
$exerciseID = mysqli_real_escape_string($conn, $_GET['exerciseID']);


$query = "SELECT e.*, c.courseID
          FROM exercise e
          JOIN `session` s ON e.sessionID = s.sessionID
          JOIN course c ON s.courseID = c.courseID
          WHERE exerciseID = '$exerciseID'";
$result = mysqli_query($conn, $query);
$exercise = mysqli_fetch_assoc($result);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $answer = $_POST['answer'];
    $status = 'Unchecked';

    $check = $conn->prepare("SELECT * FROM exerciseattempt WHERE studentID = ? AND exerciseID = ?");
    $check->bind_param("ss", $studentID, $exerciseID);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE exerciseattempt SET answer = ?, score = NULL, status = ? WHERE studentID = ? AND exerciseID = ?");
        $stmt->bind_param("ssss", $answer, $status, $studentID, $exerciseID);
    } else {
        $stmt = $conn->prepare("INSERT INTO exerciseattempt (studentID, exerciseID, answer, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $studentID, $exerciseID, $answer, $status);
    }

    $stmt->execute();
    $stmt->close();
    $check->close();

    header("Location: exercise.php?exerciseID=$exerciseID");
    exit;
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar-learning.css">
    <link rel="stylesheet" href="css/lesson.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Afacad:ital,wght@0,400..700;1,400..700&family=Bebas+Neue&family=Gabarito:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="icon" href="images/logos/logo.png" type="image/png">
    <title>Academia Plus</title>
</head>
<body>
    <div class="container">
        <div class="navbar">
            <div class="menu">
                <div class="pp" id="pp">
                    <img src="<?= htmlspecialchars($profileImage);  ?>" class="nav-profile-img">
                </div>
                <a href="learningProgress.php?view=profile"><i class="fa-solid fa-graduation-cap"></i></a>
                <a href="learningProgress.php?view=courses"><i class="fa-solid fa-laptop-file"></i></button>
                <a id="cs"><i class="fa-solid fa-headset"></i></a>    
            </div>
            <img class="logo" src="images/logos/fullLogo.png" alt="">
            <div class="bottom">
                <button id="notification"><i class="fa-solid fa-bell"></i></button>
                <a href="homepage.php"><i class="fa-solid fa-house"></i></a>
            </div>
        </div>
        <div class="content" id="course-base">
            <div class="sub-header">
                <a href="study.php?courseID=<?= htmlspecialchars($exercise['courseID'])?>"><i class="fa-solid fa-chevron-left"></i></a>
                <h1>Exercise</h1>
            </div>
            <div class="lesson-content">
                <div class="outline">
                    <h2>Question</h2>
                    <h2><?= htmlspecialchars($exercise['question'])?></h2>
                </div>
                <form action="" method="post">
                        <label for="answer">Your Answer</label><br>
                        <textarea name="answer" rows="4" cols="50" required></textarea><br><br>
                        <input type="submit" name="submit" value="Finalize">
                </form>
            </div>
        </div>
    </div>
</body>
</html>