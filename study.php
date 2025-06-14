<?php
session_start();
include 'api/connect.php';

if (!isset($_SESSION['studentID'])) {
    header("Location: signin.php");
}
$studentID = $_SESSION['studentID'];

$query = "SELECT studentImage FROM student WHERE studentID = '$studentID'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$profileImage = !empty($row['studentImage']) ? 'uploads/profilePicture/' . $row['studentImage'] : 'images/empty-profile.png';

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
    FROM overallprogress 
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

$query = "SELECT 
        s.sessionID,
        s.sessionType,
        s.sessionSeq,
        l.lessonID,
        l.videoURL,
        l.description
    FROM `session` s
    JOIN lesson l ON s.sessionID = l.sessionID
    WHERE s.courseID = '$courseID' AND s.sessionType = 'Lesson'
    ORDER BY s.sessionID ASC";
$result = mysqli_query($conn, $query);
$lessons = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT 
        s.sessionID,
        s.sessionType,
        s.sessionSeq,
        e.exerciseID,
        e.question 
    FROM `session` s
    JOIN exercise e ON s.sessionID = e.sessionID
    WHERE s.courseID = '$courseID' AND s.sessionType = 'Exercise'
    ORDER BY s.sessionID ASC
    LIMIT 1";
$result = mysqli_query($conn, $query);
$exercise = mysqli_fetch_assoc($result);
$exerciseID = $exercise['exerciseID'];

$query = "SELECT score FROM exerciseattempt WHERE studentID = '$studentID' AND exerciseID = '$exerciseID'";
$result = mysqli_query($conn, $query);
$dataAttempt = mysqli_fetch_assoc($result);
$isAttempted = $dataAttempt !== null;
$score = $isAttempted ? $dataAttempt['score'] : null;

$query = "SELECT 
        s.sessionID,
        s.sessionType,
        s.sessionSeq,
        p.projectID,
        p.projectTitle,
        p.projectDetail 
    FROM `session` s
    JOIN project p ON s.sessionID = p.sessionID
    WHERE s.courseID = '$courseID' AND s.sessionType = 'Project'
    ORDER BY s.sessionID ASC
    LIMIT 1";
$result = mysqli_query($conn, $query);
$project = mysqli_fetch_assoc($result);

$query = "SELECT
          r.requirementID,
          r.courseID,
          r.requirementName,
          r.downloadLink
          FROM requirement r
          JOIN course c ON r.courseID = c.courseID
          WHERE r.courseID = '$courseID'";
$result = mysqli_query($conn, $query);
$requirement = mysqli_fetch_all($result, MYSQLI_ASSOC);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['openLesson'])) {
    $sessionID = mysqli_real_escape_string($conn, $_POST['sessionID']);
    $lessonID = mysqli_real_escape_string($conn, $_POST['lessonID']);

    $check = mysqli_query($conn, "SELECT progressValue FROM learningprogress 
                                  WHERE studentID = '$studentID' AND sessionID = '$sessionID'");
    
    $alreadyCompleted = false;
    if ($row = mysqli_fetch_assoc($check)) {
        if ((int)$row['progressValue'] === 10) {
            $alreadyCompleted = true;
        }
    }

    if ($alreadyCompleted === false) {
        $checkExist = mysqli_num_rows($check);
        if ($checkExist > 0) {
            mysqli_query($conn, "UPDATE learningprogress 
                                 SET progressValue = 10, sessionStatus = 'Passed' 
                                 WHERE studentID = '$studentID' AND sessionID = '$sessionID'");
        } else {
            mysqli_query($conn, "INSERT INTO learningprogress (studentID, sessionID, progressValue, sessionStatus) 
                                 VALUES ('$studentID', '$sessionID', 10, 'Passed')");
        }

        $getCourse = mysqli_query($conn, "SELECT courseID FROM session WHERE sessionID = '$sessionID'");
        $courseRow = mysqli_fetch_assoc($getCourse);
        $courseID = $courseRow['courseID'];

        mysqli_query($conn, "UPDATE overallprogress 
                            SET progress = LEAST(progress + 10, 100)
                            WHERE studentID = '$studentID' AND courseID = '$courseID'");

        $checkProgress = mysqli_query($conn, "SELECT progress FROM overallprogress 
                                            WHERE studentID = '$studentID' AND courseID = '$courseID'");
        $progressRow = mysqli_fetch_assoc($checkProgress);
        $currentProgress = (int)$progressRow['progress'];

        $progressStatus = ($currentProgress >= 100) ? 'Passed' : 'On Going';
        mysqli_query($conn, "UPDATE overallprogress 
                            SET progressStatus = '$progressStatus'
                            WHERE studentID = '$studentID' AND courseID = '$courseID'");

        if ($currentProgress >= 100) {
            mysqli_query($conn, "DELETE lp FROM learningprogress lp 
                                JOIN session s ON lp.sessionID = s.sessionID 
                                WHERE lp.studentID = '$studentID' AND s.courseID = '$courseID'");
        }

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
                <a href="learningProgress.php?view=courses"><i class="fa-solid fa-graduation-cap"></i></a>
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
                    <div class="session" id="session" >
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
                        <div class="requirement">
                                <h2>Requirement</h2>
                                <div class="req">
                                    <div class="spec">
                                        <h5>Specification</h5>
                                        <p>
                                            <strong>OS: </strong>Windows 11 64-bit<br>
                                            <strong>Processor: </strong> Intel® Core™ i5-9600K <br>
                                            <strong>Memory: </strong> 16 GB RAM <br>
                                            <strong>Graphic Card: </strong> GeForce® RTX 3060 (8GB VRAM) <br>
                                            <strong>Storage: </strong> 50 GB free space <br>
                                            <strong>Audio Card: </strong> DirectX 10 Compatible
                                        </p>
                                    </div>
                                    <div class="soft">
                                        <h5>Software</h5>
                                        <?php foreach($requirement as $req): ?>
                                            <h4><?= htmlspecialchars($req['requirementName'])?></h4>
                                            <div class="download-link">
                                                <a href="<?= htmlspecialchars($req['downloadLink'])?>"><?= htmlspecialchars(mb_strimwidth($req['downloadLink'], 0, 100, "..."))?></a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="session" id="lesson" style="display:none;">
                        <div class="header-session">
                            <button id="back-button1"><i  class="fa-solid fa-chevron-left"></i></button>
                            <div>
                                <h2>Lessons</h2>
                                <p class="session-desc">
                                    Lesson session is a Video-Based Learning (VBL) module where you will explore course materials 
                                    through structured and engaging video content designed to build your understanding step by step.
                                </p>
                            </div>
                        </div>
                        <div class="session-list-session">
                            <?php foreach ($lessons as $lesson): ?>
                                <form method="POST">
                                    <input type="hidden" name="sessionID" value="<?= htmlspecialchars($lesson['sessionID']) ?>">
                                    <input type="hidden" name="lessonID" value="<?= htmlspecialchars($lesson['lessonID']) ?>">
                                    <button type="submit" name="openLesson" class="lesson-button">Session <?= htmlspecialchars($lesson['sessionSeq']) ?></button>
                                </form>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="session" id="exercise" style="display:none;">
                        <div class="header-session">
                            <button id="back-button2"><i class="fa-solid fa-chevron-left"></i></button>
                            <div>
                                <h2>Exercise</h2>
                                <p class="session-desc">
                                    Exercise session provides a set of tasks or questions related to the lesson content. 
                                    These are meant to help you reinforce what you’ve learned.
                                </p>
                            </div>
                            
                        </div>
                        <h2 class="sessionSeq">Session <?= htmlspecialchars($exercise['sessionSeq']) ?></h2>
                        <div class="session-list">
                            <?php if (!$isAttempted): ?>
                                <div class="start-attempt">
                                    <a class="exercise-link" href="exercise.php?exerciseID=<?= urlencode($exerciseID) ?>">
                                    <i class="fa-solid fa-file-pen"></i> Start Attempt
                                    </a>
                                </div>

                            <?php elseif ($score === null): ?>
                                <div class="exercise-submitted">
                                    <div class="waiting-exercise">
                                        <i class="fa-solid fa-hourglass-half"></i>
                                        <h2>You have already submitted your answer</h2>
                                    </div>
                                    <p>Please wait a moment for the assessor to check and provide the results of your answers.</p>
                                </div>

                            <?php elseif ($score < 7.5): ?>
                                <div class="exercise-submitted">
                                    <div class="notpass-exercise">
                                        <i class="fa-solid fa-file-circle-xmark"></i>
                                        <h2>Your answer result still does not meet the minimum pass.</h2>
                                    </div>
                                    <p>The result is <?= number_format($score, 1) ?> Please re-attempt your answer.</p>
                                    <a href="exercise.php?exerciseID=<?= urlencode($exerciseID) ?>">Re-Attempt your submission</a>
                                </div>

                            <?php else: ?>
                                <div class="exercise-submitted">
                                    <div class="passed-exercise">
                                        <i class="fa-solid fa-file-circle-check"></i>
                                        <h2>Congratulations! You already passed this exercise.</h2>
                                    </div>
                                    <p>Score: <?= number_format($score, 1) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                    <div class="session" id="project" style="display:none;">
                        <div class="header-session">
                            <button id="back-button3"><i class="fa-solid fa-chevron-left"></i></button>
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
                            <div class="start-attempt">
                                <h2>Session <?= htmlspecialchars($project['sessionSeq']) ?></h2>
                                <a class="exercise-link" href="project.php?projectID=<?= urlencode($project['projectID']) ?>">
                                    Final Implementation Test
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="js/study.js"></script>
</body>
</html>