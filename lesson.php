<?php
session_start();
include 'api/connect.php';
include 'exploreDropdown.php';

if (!isset($_SESSION['studentID'])) {
    die("Anda harus login terlebih dahulu.");
}
$studentID = $_SESSION['studentID'];

if (!isset($_GET['lessonID']) || empty($_GET['lessonID'])) {
    die("No lesson ID provided.");
}
$lessonID = mysqli_real_escape_string($conn, $_GET['lessonID']);


$query = "SELECT l.*, c.courseID
          FROM lesson l
          JOIN `session` s ON l.sessionID = s.sessionID
          JOIN course c ON s.courseID = c.courseID
          WHERE lessonID = '$lessonID'";
$result = mysqli_query($conn, $query);
$lesson = mysqli_fetch_assoc($result);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar-learning.css">
    <link rel="stylesheet" href="css/lesson.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=DM+Serif+Text:ital@0;1&family=Oswald:wght@200..700&family=Staatliches&display=swap" rel="stylesheet">
    <link rel="icon" href="images/logo.png" type="image/png">
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
            <img class="logo" src="images/fullLogo.png" alt="">
            <div class="bottom">
                <button id="notification"><i class="fa-solid fa-bell"></i></button>
                <a href="homepage.php"><i class="fa-solid fa-house"></i></a>
            </div>
        </div>
        <div class="content" id="course-base">
            <div class="sub-header">
                <a href="study.php?courseID=<?= htmlspecialchars($lesson['courseID'])?>"><i class="fa-solid fa-chevron-left"></i></a>
                <h1>Lesson</h1>
            </div>
            <div class="lesson-content">
                <div class="frame">
                    <iframe src="<?= htmlspecialchars($lesson['videoURL'])?>" allow="autoplay" allowfullscreen></iframe>
                    
                </div>
                <div class="outline">
                    <h2><?= htmlspecialchars($lesson['lessonTitle'])?></h2>
                    <h3>Lesson Outline</h3>
                    <p><?= htmlspecialchars($lesson['description'])?></p>
                    <h4>Duration: 9 Minutes</h4>
                </div>
            </div>
        </div>
    </div>
</body>
</html>