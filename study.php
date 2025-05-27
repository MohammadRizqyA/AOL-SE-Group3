<?php
session_start();
include 'api/connect.php';

if (!isset($_SESSION['studentID'])) {
    die("Anda harus login terlebih dahulu.");
}
$studentID = $_SESSION['studentID'];

$query = "SELECT studentImage FROM student WHERE studentID = '$studentID'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$profileImage = !empty($row['studentImage']) ? 'uploads/profilePicture/' . $row['studentImage'] : 'images/empty-profile.jpg';

$query = "SELECT * FROM student
          WHERE studentID = '$studentID'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);


if (!isset($_GET['courseID']) || empty($_GET['courseID'])) {
    die("No order ID provided.");
}
$courseID = mysqli_real_escape_string($conn, $_GET['courseID']);

$query = "SELECT 
        s.sessionID,
        s.sessionType,

        l.lessonID,
        l.videoURL,
        l.description AS lessonDescription,

        e.exerciseID,
        e.question AS exerciseQuestion,

        p.projectID,
        p.projectTitle AS projectTitle

    FROM session s
    LEFT JOIN lesson l ON s.sessionID = l.sessionID
    LEFT JOIN exercise e ON s.sessionID = e.sessionID
    LEFT JOIN project p ON s.sessionID = p.sessionID

    WHERE s.courseID = '$courseID'
    ORDER BY s.sessionID ASC
";
$result = mysqli_query($conn, $query);
$sessions = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT c.*, cc.*
            FROM course c
            JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
            WHERE courseID = '$courseID'";
$result = mysqli_query($conn, $query);
$course = mysqli_fetch_assoc($result);

$query = "SELECT progress, progressStatus 
    FROM overallProgress 
    WHERE studentID = '$studentID' AND courseID = '$courseID'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

$progress = isset($data['progress']) ? (int)$data['progress'] : 0;
$status = strtolower($data['progressStatus'] ?? '');

$statusLabel = ($progress >= 100 || $status === 'passed') ? 'Passed' : 'On Going';

$query = "SELECT 
        SUM(CASE WHEN s.sessionType = 'Lesson' THEN 1 ELSE 0 END) AS totalLesson,
        SUM(CASE WHEN s.sessionType = 'Exercise' THEN 1 ELSE 0 END) AS totalExercise,
        SUM(CASE WHEN s.sessionType = 'Project' THEN 1 ELSE 0 END) AS totalProject
    FROM session s
    WHERE s.courseID = '$courseID'";
$result = mysqli_query($conn, $query);
$totalSessions = mysqli_fetch_assoc($result);

$totalLesson   = $totalSessions['totalLesson'] ?? 0;
$totalExercise = $totalSessions['totalExercise'] ?? 0;
$totalProject  = $totalSessions['totalProject'] ?? 0;

$queryLesson = "SELECT 
        s.sessionID,
        s.sessionType,
        l.lessonID,
        l.videoURL,
        l.description
    FROM `session` s
    JOIN lesson l ON s.sessionID = l.sessionID
    WHERE s.courseID = '$courseID' AND s.sessionType = 'Lesson'
    ORDER BY s.sessionID ASC";
$resultLesson = mysqli_query($conn, $queryLesson);
$lessons = mysqli_fetch_all($resultLesson, MYSQLI_ASSOC);

$queryExercise = "SELECT 
        s.sessionID,
        s.sessionType,
        e.exerciseID,
        e.question 
    FROM `session` s
    JOIN exercise e ON s.sessionID = e.sessionID
    WHERE s.courseID = '$courseID' AND s.sessionType = 'Exercise'
    ORDER BY s.sessionID ASC
    LIMIT 1";
$resultExercise = mysqli_query($conn, $queryExercise);
$exercise = mysqli_fetch_assoc($resultExercise);

$queryExercise = "SELECT 
        s.sessionID,
        s.sessionType,
        p.projectID,
        p.projectTitle,
        p.projectDetail 
    FROM `session` s
    JOIN project p ON s.sessionID = p.sessionID
    WHERE s.courseID = '$courseID' AND s.sessionType = 'Project'
    ORDER BY s.sessionID ASC
    LIMIT 1";
$resultExercise = mysqli_query($conn, $queryExercise);
$project = mysqli_fetch_assoc($resultExercise);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['openLesson'])) {
    $sessionID = mysqli_real_escape_string($conn, $_POST['sessionID']);
    $lessonID = mysqli_real_escape_string($conn, $_POST['lessonID']);

    $check = mysqli_query($conn, "SELECT progressValue FROM learningProgress 
                                  WHERE studentID = '$studentID' AND sessionID = '$sessionID'");
    
    $alreadyCompleted = false;
    if ($row = mysqli_fetch_assoc($check)) {
        if ((int)$row['progressValue'] === 100) {
            $alreadyCompleted = true;
        }
    }
    if ($alreadyCompleted === false) {
        $checkExist = mysqli_num_rows($check);
        if ($checkExist > 0) {
            mysqli_query($conn, "UPDATE learningProgress 
                                 SET progressValue = 100, sessionStatus = 'Completed' 
                                 WHERE studentID = '$studentID' AND sessionID = '$sessionID'");
        } else {
            mysqli_query($conn, "INSERT INTO learningProgress (studentID, sessionID, progressValue, sessionStatus) 
                                 VALUES ('$studentID', '$sessionID', 100, 'Completed')");
        }

        $getCourse = mysqli_query($conn, "SELECT courseID FROM session WHERE sessionID = '$sessionID'");
        $courseRow = mysqli_fetch_assoc($getCourse);
        $courseID = $courseRow['courseID'];

        mysqli_query($conn, "UPDATE overallProgress 
                             SET progress = LEAST(progress + 10, 100),
                                 progressStatus = CASE 
                                     WHEN progress + 10 >= 100 THEN 'Passed'
                                     ELSE 'On Going'
                                 END
                             WHERE studentID = '$studentID' AND courseID = '$courseID'");
    }

    header("Location: lesson.php?lessonID=" . urlencode($lessonID));
    exit;
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar-learning.css">
    <link rel="stylesheet" href="css/study.css">
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
                <a href="learningProgress.php?view=courses"><i class="fa-solid fa-chevron-left"></i></a>
                <h1>Learning</h1>
            </div>
            <div class="main-course">
                <div class="flex">
                    <div class="course">
                        <div class="course-detail">
                            <img src="uploads/thumbnails/<?= htmlspecialchars($course['courseThumbnail']) ?>" alt="">
                            <div class="sub-details">
                                <h2><?= htmlspecialchars($course['courseTitle']) ?></h2>
                                <div class="level-id">
                                    <div class="level-cat">
                                        <p><?= htmlspecialchars($course['level']) ?></p>
                                        <p><?= htmlspecialchars($course['courseCat']) ?></p>
                                    </div>
                                    <h3><?= htmlspecialchars($course['courseID']) ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="course-outline">
                            <h2>Course Outline</h2>
                            <p><?= htmlspecialchars($course['courseDescription']) ?></p>
                        </div>
                        <div class="progress"> 
                            <div class="course-status-label <?= strtolower($statusLabel) ?>">
                                <h3>Status</h3>
                                <p><?= $statusLabel ?></p>
                            </div>  
                            <div class="progression">   
                                <div class="circle-wrapper">
                                    <svg class="progress-ring" width="200" height="200">
                                        <g transform="rotate(-90 100 100)">
                                            <circle class="progress-ring__bg" cx="100" cy="100" r="80" />
                                            <circle class="progress-ring__circle" 
                                                    cx="100" cy="100" r="80"
                                                    style="stroke-dashoffset: <?= 502 - (502 * $progress / 100) ?>;" />
                                        </g>
                                        <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" class="circle-text">
                                            <?= $progress == 0 ? '0%' : ($progress >= 100 || $status === 'passed' ? 'Passed' : "$progress%") ?>
                                        </text>
                                    </svg>
                                    <h2>Overall Progress</h2>
                                    <p>for this course</p>
                                </div> 
                                <div class="total-session">
                                        <h4>Progress Including</h4>
                                        <h5><?= $totalLesson ?> Lesson</h5>
                                        <h5><?= $totalExercise ?> Exercise</h5>
                                        <h5><?= $totalProject ?> Project</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="session" id="session">
                        <h2>Learning Sessions</h2>
                        <p class="session-desc">The upcoming learning modules that you need to study and do to be able to understand the material in this course.</p>
                        <div class="session-list">
                            <div id="lesson-button" class="lesson">
                                <div class="top"><i class="fa-solid fa-book"></i></div>
                                <div class="bot">
                                    <h3>LESSONS SESSION</h3>
                                    <p>
                                        Lesson session is a Video-Based Learning (VBL) course materials 
                                        through structured and engaging video content designed to build your understanding step by step.
                                    </p>
                                </div>
                            </div>
                            <div id="exercise-button" class="exercise">
                                <div class="top"><i class="fa-solid fa-file-circle-exclamation"></i></div>
                                <div class="bot">
                                    <h3>EXERCISE SESSION</h3>
                                    <p>
                                        Exercise session provides a set of tasks or questions related to the lesson content. 
                                        These are meant to help you reinforce what you’ve learned.
                                    </p>
                                </div>
                            </div>
                            <div id="project-button" class="project">
                                <div class="top"><i class="fa-solid fa-laptop-file"></i></div>
                                <div class="bot">
                                    <h3>PROJECT SESSION</h3>
                                    <p>
                                        Final assignment that challenges you to apply the skills and concepts 
                                        from the course. Involves solving complex problem submitting your work file based on 
                                        the provided project criteria.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <a class="allsession" href="allSession.php">Show all session</a>
                    </div>

                    <div class="session" id="lesson" style="display:none;">
                        <div class="header-session">
                            <button><i id="back-button1" class="fa-solid fa-chevron-left"></i></button>
                            <div>
                                <h2>Lessons</h2>
                                <p class="session-desc">
                                    Lesson session is a Video-Based Learning (VBL) module where you will explore course materials 
                                    through structured and engaging video content designed to build your understanding step by step.
                                </p>
                            </div>
                        </div>
                        <div class="session-list-session">
                            <?php $no = 1; ?>
                            <?php foreach ($lessons as $lesson): ?>
                                <form method="POST" style="margin-bottom: 10px;">
                                    <input type="hidden" name="sessionID" value="<?= htmlspecialchars($lesson['sessionID']) ?>">
                                    <input type="hidden" name="lessonID" value="<?= htmlspecialchars($lesson['lessonID']) ?>">
                                    <button type="submit" name="openLesson" class="lesson-button">Lesson <?= $no ?></button>
                                </form>
                            <?php $no++; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="session" id="exercise" style="display:none;">
                        <div class="header-session">
                            <button><i id="back-button2" class="fa-solid fa-chevron-left"></i></button>
                            <div>
                                <h2>Exercise</h2>
                                <p class="session-desc">
                                    Exercise session provides a set of tasks or questions related to the lesson content. 
                                    These are meant to help you reinforce what you’ve learned.
                                </p>
                            </div>
                        </div>
                        <div class="session-list">
                            <a class="exercise-link" href="exercise.php?exerciseID=<?= urlencode($exercise['exerciseID']) ?>">
                                Exercise <p>comprehension test</p>
                            </a>
                        </div>
                    </div>

                    <div class="session" id="project" style="display:none;">
                        <div class="header-session">
                            <button><i id="back-button3" class="fa-solid fa-chevron-left"></i></button>
                            <div>
                                <h2>Project</h2>
                                <p class="session-desc">
                                    Final assignment that challenges you to apply the skills and concepts 
                                        from the course. Involves solving complex problem submitting your work file based on 
                                        the provided project criteria. 
                                </p>
                            </div>
                        </div>
                        <div class="session-list">
                            <a class="exercise-link" href="project.php?projectID=<?= urlencode($project['projectID']) ?>">
                                Project <p>Final Implementation Test</p>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="js/study.js"></script>
</body>
</html>