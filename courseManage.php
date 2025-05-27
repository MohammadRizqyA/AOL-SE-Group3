<?php
session_start();
include 'api/connect.php';

if (!isset($_SESSION['adminID'])) {
    die("Anda harus login terlebih dahulu.");
}
$adminID = $_SESSION['adminID'];

$courseID = isset($_GET['courseID']) ? $_GET['courseID'] : '';
$courseID = mysqli_real_escape_string($conn, $courseID);

$sql = "SELECT 
    c.courseID,
    c.courseTitle,
    c.courseDescription,
    c.level,
    c.price,
    c.courseCatID,
    cc.courseCat,
    COALESCE(d.finalPrice, c.price) AS finalPrice,
    c.rating,
    c.courseThumbnail,

    IFNULL(e.totalEnrolled, 0) AS totalEnrolled,
    IFNULL(s.totalSession, 0) AS totalSession,
    IFNULL(l.totalLesson, 0) AS totalLesson,
    IFNULL(a.totalExercise, 0) AS totalExercise,
    IFNULL(p.totalProject, 0) AS totalProject

FROM course c
LEFT JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
LEFT JOIN discount d ON c.courseID = d.courseID

LEFT JOIN (
    SELECT courseID, COUNT(*) AS totalEnrolled
    FROM enrolled
    GROUP BY courseID
) e ON c.courseID = e.courseID

LEFT JOIN (
    SELECT courseID, COUNT(*) AS totalSession
    FROM session
    GROUP BY courseID
) s ON c.courseID = s.courseID

LEFT JOIN (
    SELECT s.courseID, COUNT(l.lessonID) AS totalLesson
    FROM lesson l
    JOIN session s ON l.sessionID = s.sessionID
    GROUP BY s.courseID
) l ON c.courseID = l.courseID

LEFT JOIN (
    SELECT s.courseID, COUNT(a.exerciseID) AS totalExercise
    FROM exercise a
    JOIN session s ON a.sessionID = s.sessionID
    GROUP BY s.courseID
) a ON c.courseID = a.courseID

LEFT JOIN (
    SELECT s.courseID, COUNT(p.projectID) AS totalProject
    FROM project p
    JOIN session s ON p.sessionID = s.sessionID
    GROUP BY s.courseID
) p ON c.courseID = p.courseID
WHERE c.courseID = '$courseID'";
$result = mysqli_query($conn, $sql);
$course = mysqli_fetch_assoc($result);

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

if (isset($_POST['addLesson'])) {
    $courseID = isset($_GET['courseID']) ? $_GET['courseID'] : '';
    $courseID = mysqli_real_escape_string($conn, $courseID);

    $videoURL = mysqli_real_escape_string($conn, $_POST['videoURL']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if (!empty($courseID) && !empty($videoURL) && !empty($title) && !empty($description)) {
        // 1. Generate sessionID
        $sessionID = generateCustomID($conn, "S", "session", "sessionID");

        // 2. Insert ke tabel session
        $insertSession = mysqli_query($conn, "INSERT INTO `session` (sessionID, courseID, sessionType) VALUES ('$sessionID', '$courseID', 'Lesson')");

        if ($insertSession) {
            // 3. Generate lessonID
            $lessonID = generateCustomID($conn, "L", "lesson", "lessonID");

            // 4. Insert ke tabel lesson
            $insertLesson = mysqli_query($conn, "INSERT INTO lesson (lessonID, sessionID, videoURL, lessonTitle ,description) 
                                                 VALUES ('$lessonID', '$sessionID', '$videoURL', '$title', '$description')");
            if ($insertLesson) {
                echo "<script>alert('Lesson berhasil ditambahkan!'); window.location.href='courseManage.php?courseID=$courseID';</script>";
            } else {
                echo "<script>alert('Gagal menambahkan ke tabel lesson');</script>";
            }
        } else {
            echo "<script>alert('Gagal menambahkan ke tabel session');</script>";
        }
    } else {
        echo "<script>alert('Semua field wajib diisi');</script>";
    }
}

if (isset($_POST['addExercise'])) {
    $courseID = isset($_GET['courseID']) ? $_GET['courseID'] : '';
    $courseID = mysqli_real_escape_string($conn, $courseID);

    $question = mysqli_real_escape_string($conn, $_POST['question']);

    if (!empty($courseID) && !empty($question)) {
        // 1. Generate sessionID
        $sessionID = generateCustomID($conn, "S", "session", "sessionID");

        // 2. Insert ke tabel session (type: Exercise)
        $insertSession = mysqli_query($conn, "INSERT INTO session (sessionID, courseID, sessionType) 
                                              VALUES ('$sessionID', '$courseID', 'Exercise')");

        if ($insertSession) {
            // 3. Generate exerciseID
            $exerciseID = generateCustomID($conn, "E", "exercise", "exerciseID");

            // 4.Insert ke tabel exercise
            $insertExercise = mysqli_query($conn, "INSERT INTO exercise (exerciseID, sessionID, question) 
                                                    VALUES ('$exerciseID', '$sessionID', '$question')");

            if ($insertExercise) {
                echo "<script>alert('Exercise berhasil ditambahkan!'); window.location.href='courseManage.php?courseID=$courseID';</script>";
            } else {
                echo "<script>alert('Gagal menambahkan ke tabel exercise');</script>";
            }
        } else {
            echo "<script>alert('Gagal menambahkan session untuk exercise');</script>";
        }
    } else {
        echo "<script>alert('Pertanyaan exercise wajib diisi');</script>";
    }
}

if (isset($_POST['addProject'])) {
    $courseID = isset($_GET['courseID']) ? $_GET['courseID'] : '';
    $courseID = mysqli_real_escape_string($conn, $courseID);

    $projectTitle = mysqli_real_escape_string($conn, $_POST['projectTitle']);
    $projectDetail = mysqli_real_escape_string($conn, $_POST['projectDetail']);

    if (!empty($courseID) && !empty($projectTitle) && !empty($projectDetail)) {
        // 1. Generate sessionID
        $sessionID = generateCustomID($conn, "S", "session", "sessionID");

        // 2. Insert ke tabel session (type: Project)
        $insertSession = mysqli_query($conn, "INSERT INTO session (sessionID, courseID, sessionType) 
                                              VALUES ('$sessionID', '$courseID', 'Project')");

        if ($insertSession) {
            // 3. Generate projectID
            $projectID = generateCustomID($conn, "P", "project", "projectID");

            // 4. Insert ke tabel project
            $insertProject = mysqli_query($conn, "INSERT INTO project (projectID, sessionID, projectTitle, projectDetail) 
                                                  VALUES ('$projectID', '$sessionID', '$projectTitle', '$projectDetail')");

            if ($insertProject) {
                echo "<script>alert('Project berhasil ditambahkan!'); window.location.href='courseManage.php?courseID=$courseID';</script>";
            } else {
                echo "<script>alert('Gagal menambahkan ke tabel project');</script>";
            }
        } else {
            echo "<script>alert('Gagal menambahkan ke tabel session');</script>";
        }
    } else {
        echo "<script>alert('Semua field wajib diisi');</script>";
    }
}

$query = "SELECT 
            s.sessionID,
            s.sessionType,
            l.lessonID,
            e.exerciseID,
            p.projectID
          FROM session s
          LEFT JOIN lesson l ON s.sessionID = l.sessionID
          LEFT JOIN exercise e ON s.sessionID = e.sessionID
          LEFT JOIN project p ON s.sessionID = p.sessionID
          WHERE s.courseID = '$courseID'
          ORDER BY s.sessionID";

$result = mysqli_query($conn, $query); // gunakan $query, bukan $sql

$sessions = [];
if ($result && mysqli_num_rows($result) > 0) {
    $sessions = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Afacad:ital,wght@0,400..700;1,400..700&family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/courseManage.css">
    <link rel="icon" href="images/logo.png" type="image/png">
    <title>Management System</title>
</head>
<body>
    <div class="container">
        <div class="left">
            <div class="menu">
                <button class="home" id="dash"><i class="fa-solid fa-house"></i></button>
                <button id="add"><i class="fa-solid fa-book-medical"></i></button>
                <button id="user"><i class="fa-solid fa-user-group"></i></button>
                <button id="chart"><i class="fa-solid fa-chart-column"></i></button>    
            </div>
            <img src="images/fullLogo-black.png" alt="">
            <button class="cs" id="cs"><i class="fa-solid fa-headset"></i></button>
        </div>
        <div class="right">
            <div class="content" id="dashboard">
                <div class="head">
                    <a href="admin.php"><i class="fa-solid fa-chevron-left"></i></a>
                    <h1>Manage Course</h1>
                    <h2>ID <?= htmlspecialchars($course['courseID']) ?></h2>
                    <h3>Active</h3>
                </div>
                <div class="main">
                    <div class="course">
                        <img src="uploads/thumbnails/<?= htmlspecialchars($course['courseThumbnail']) ?>" alt="">
                        <div class="details">
                            <div class="first">
                                <h2><?= htmlspecialchars($course['courseTitle']) ?></h2>
                                <div class="first-child">
                                    <div class="second">
                                        <h3>Sessions</h3>
                                        <p><?= htmlspecialchars($course['totalLesson'])?> Lesson</p>
                                        <p><?= htmlspecialchars($course['totalExercise'])?> exercise</p>
                                        <p><?= htmlspecialchars($course['totalProject'])?> Project</p>
                                    </div>
                                    <div class="desc-enroll">
                                        <p><?= htmlspecialchars($course['courseDescription'])?></p>
                                        <h3><?= htmlspecialchars($course['totalEnrolled'])?> Enrolled</h3>
                                    </div>
                                </div>
                                <div class="pricing-discount">                                    
                                    <h5>$<?= htmlspecialchars($course['finalPrice'])?></h5>
                                    <p>$<?= htmlspecialchars($course['price'])?></p>
                                    <button>Manage Discount</button>
                                </div>  
                            </div>
                        </div>
                    </div>
                    <div class="manage">
                        <div class="add-session">   
                            <div class="buttons" >
                                <h2>Add New Session</h2>
                                <button class="add-session-button" id="lesson-button">Lessons</button>
                                <button class="add-session-button" id="exercise-button">Exercise</button>
                                <button class="add-session-button" id="project-button">Project</button>
                            </div>
                            <div class="form-add">
                                <form method="post" id="lesson" style="display:none;">
                                    <div class="header-add">
                                        <h2>Lesson</h2>
                                        <p>Add new session type lesson into this course</p>
                                    </div>
                                    <label>Video-Based Learning URL</label>
                                    <input type="text" name="videoURL" required>
                                    <label>Title</label>
                                    <input type="text" name="title" required>
                                    <label>Lesson Outline</label>
                                    <textarea name="description" required></textarea>
                                    <button type="submit" name="addLesson">Add Lesson</button>
                                </form>
                                 <form method="post" id="exercise" style="display:none;">
                                    <div class="header-add">
                                        <h2>Exercise</h2>
                                        <p>Add new session type exercise into this course</p>
                                    </div>
                                    <label>Exercise Question</label>
                                    <textarea name="question" required></textarea>
                                    <button type="submit" name="addExercise">Add Exercise</button>
                                </form>
                                <form method="post" id="project" style="display:none;">
                                    <div class="header-add">
                                        <h2>Project</h2>
                                        <p>Add new session type project into this course</p>
                                    </div>
                                    <label>Project Title</label>
                                    <input type="text" name="projectTitle" required>
                                    <label>Project Detail</label>
                                    <textarea name="projectDetail" required></textarea>
                                    <button type="submit" name="addProject">Add Project</button>
                                </form>
                            </div>
                        </div>
                       <div class="session-list" id="session-record">
                            <div class="list-label">
                                <p>Sessions</p>
                                <p>Type</p>
                                <p>ID</p>
                            </div>
                            <?php foreach ($sessions as $session): ?>
                                <div class="session-wrap">
                                    <?php if (isset($session['sessionID'])): ?>
                                        <h3><?= htmlspecialchars($session['sessionID']) ?></h3>
                                    <?php endif; ?>
                                    <?php if (isset($session['sessionType'])): ?>
                                        <h4><?= htmlspecialchars($session['sessionType']) ?></h4>
                                    <?php endif; ?>
                                    <?php
                                    if (isset($session['sessionType'])) {
                                        if ($session['sessionType'] === 'Lesson' && !empty($session['lessonID'])) {
                                            echo '<p>' . htmlspecialchars($session['lessonID']) . '</p>';
                                        } elseif ($session['sessionType'] === 'Exercise' && !empty($session['exerciseID'])) {
                                            echo '<p>' . htmlspecialchars($session['exerciseID']) . '</p>';
                                        } elseif ($session['sessionType'] === 'Project' && !empty($session['projectID'])) {
                                            echo '<p>' . htmlspecialchars($session['projectID']) . '</p>';
                                        }
                                    }
                                    ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/courseManage.js"></script>
</body>
</html>