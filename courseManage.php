<?php
session_start();
include 'api/connect.php';
include 'functions.php';

if (!isset($_SESSION['lecturerID'])) {
    header("Location: loginLecturer.php");
}
$lecturerID = $_SESSION['lecturerID'];

$courseID = isset($_GET['courseID']) ? $_GET['courseID'] : '';
$courseID = mysqli_real_escape_string($conn, $courseID);

$query = "SELECT * FROM lecturer WHERE lecturerID = '$lecturerID'";
$result = mysqli_query($conn, $query);
$lecturer = mysqli_fetch_assoc($result);

$sql = "SELECT 
    c.courseID,
    c.courseTitle,
    c.courseDescription,
    c.level,
    c.price,
    c.courseCatID,
    cc.courseCat,
    d.discountPercent,
    ROUND(AVG(cr.rating), 1) AS rating,
    COUNT(DISTINCT cr.studentID) AS totalReview,
    c.courseThumbnail,

    IFNULL(e.totalEnrolled, 0) AS totalEnrolled,
    IFNULL(s.totalSession, 0) AS totalSession,
    IFNULL(l.totalLesson, 0) AS totalLesson,
    IFNULL(a.totalExercise, 0) AS totalExercise,
    IFNULL(p.totalProject, 0) AS totalProject

FROM course c
LEFT JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
LEFT JOIN discount d ON c.courseID = d.courseID
LEFT JOIN coursereview cr ON c.courseID = cr.courseID

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


$discount = $course['discountPercent'];
$price = $course['price'];
$course['finalPrice'] = ($discount > 0) ? $price - ($price * $discount / 100) : null;

if (isset($_POST['addLesson'])) {
    $courseID = mysqli_real_escape_string($conn, $courseID);

    $videoURL = mysqli_real_escape_string($conn, $_POST['videoURL']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if (!empty($courseID) && !empty($videoURL) && !empty($title) && !empty($description)) {

        $sessionID = generateCustomID1($conn, "S", "session", "sessionID");
        
        $queryMaxSeq = "SELECT MAX(sessionSeq) AS maxSeq FROM session WHERE courseID = '$courseID'";
        $resultMaxSeq = mysqli_query($conn, $queryMaxSeq);
        $dataMaxSeq = mysqli_fetch_assoc($resultMaxSeq);
        $sessionSeq = ($dataMaxSeq['maxSeq'] !== null) ? $dataMaxSeq['maxSeq'] + 1 : 1;

        $insertSession = mysqli_query($conn, "INSERT INTO `session` (sessionID, courseID, sessionSeq, sessionType) VALUES 
                                    ('$sessionID', '$courseID', '$sessionSeq', 'Lesson')");

        if ($insertSession) {
            $lessonID = generateCustomID1($conn, "L", "lesson", "lessonID");

            $insertLesson = mysqli_query($conn, "INSERT INTO lesson (lessonID, sessionID, videoURL ,description) 
                                                 VALUES ('$lessonID', '$sessionID', '$videoURL', '$description')");
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
    $courseID = mysqli_real_escape_string($conn, $courseID);    
    $question = mysqli_real_escape_string($conn, $_POST['question']);

    if (!empty($courseID) && !empty($question)) {
        $sessionID = generateCustomID1($conn, "S", "session", "sessionID");
        $queryMaxSeq = "SELECT MAX(sessionSeq) AS maxSeq FROM session WHERE courseID = '$courseID'";
        $resultMaxSeq = mysqli_query($conn, $queryMaxSeq);
        $dataMaxSeq = mysqli_fetch_assoc($resultMaxSeq);
        $sessionSeq = ($dataMaxSeq['maxSeq'] !== null) ? $dataMaxSeq['maxSeq'] + 1 : 1;

        $insertSession = mysqli_query($conn, "INSERT INTO session (sessionID, courseID, sessionType, sessionSeq) 
                                              VALUES ('$sessionID', '$courseID', 'Exercise', '$sessionSeq')");
        if ($insertSession) {
            $exerciseID = generateCustomID1($conn, "E", "exercise", "exerciseID");

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
    $courseID = mysqli_real_escape_string($conn, $courseID);

    $projectTitle = mysqli_real_escape_string($conn, $_POST['projectTitle']);
    $projectDetail = mysqli_real_escape_string($conn, $_POST['projectDetail']);

    if (!empty($courseID) && !empty($projectTitle) && !empty($projectDetail)) {
        $sessionID = generateCustomID1($conn, "S", "session", "sessionID");

        $queryMaxSeq = "SELECT MAX(sessionSeq) AS maxSeq FROM session WHERE courseID = '$courseID'";
        $resultMaxSeq = mysqli_query($conn, $queryMaxSeq);
        $dataMaxSeq = mysqli_fetch_assoc($resultMaxSeq);
        $sessionSeq = ($dataMaxSeq['maxSeq'] !== null) ? $dataMaxSeq['maxSeq'] + 1 : 1;

        $insertSession = mysqli_query($conn, "INSERT INTO session (sessionID, courseID, sessionSeq, sessionType) 
                                              VALUES ('$sessionID', '$courseID', '$sessionSeq', 'Project')");

        if ($insertSession) {
            $projectID = generateCustomID1($conn, "P", "project", "projectID");

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


if (isset($_POST['addRequirement'])) {
    $courseID = mysqli_real_escape_string($conn, $courseID);
    $requirement = mysqli_real_escape_string($conn, $_POST['software']);
    $downloadLink = mysqli_real_escape_string($conn, $_POST['downloadLink']);
    $requirementID = generateCustomID1($conn, "R", "requirement", "requirementID");

    $insertRequirement = mysqli_query($conn ,"INSERT INTO requirement (requirementID, courseID, requirementName, downloadLink) VALUES
                          ('$requirementID', '$courseID', '$requirement', '$downloadLink')");
}

if (isset($_POST['addCertification'])) {
    $certificateName = mysqli_real_escape_string($conn, $_POST['certificateTitle']);
    $certificateID = generateCustomID2($conn, "CE", "certification", "certificateID");

    $courseID = mysqli_real_escape_string($conn, $_GET['courseID'] ?? '');
    $issuer = $lecturer['universityOrigin'] ?? 'Unknown';

    if (!isset($_FILES['certificateImage']) || $_FILES['certificateImage']['error'] !== UPLOAD_ERR_OK) {
        die("❌ File upload error.");
    }

    $uploadDir = 'uploads/certificate/';
    $originalName = basename($_FILES['certificateImage']['name']);
    $cleanedName = preg_replace("/[^A-Za-z0-9_\-\.]/", "_", $originalName);
    $newFileName = $certificateID . '_' . $cleanedName;
    $uploadPath = $uploadDir . $newFileName;

    if (move_uploaded_file($_FILES['certificateImage']['tmp_name'], $uploadPath)) {
        $stmt = $conn->prepare("INSERT INTO certification (certificateID, courseID, certificateName, issuer, image) 
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $certificateID, $courseID, $certificateName, $issuer, $newFileName);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('✅ Certificate uploaded successfully!'); window.location.href='courseManage.php?courseID=$courseID';</script>";
        exit;
    } else {
        die("❌ Gagal menyimpan file sertifikat.");
    }
}

$certificateData = null;
$certQuery = $conn->prepare("SELECT * FROM certification WHERE courseID = ?");
$certQuery->bind_param("s", $courseID);
$certQuery->execute();
$certResult = $certQuery->get_result();
if ($certResult && $certResult->num_rows > 0) {
    $certificateData = $certResult->fetch_assoc();
}
$certQuery->close();


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

$result = mysqli_query($conn, $query);

$sessions = [];
if ($result && mysqli_num_rows($result) > 0) {
    $sessions = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if (isset($_POST['removeCourse'])) {
        $courseID = mysqli_real_escape_string($conn, $courseID);
        $removeCourse = $conn->prepare("DELETE FROM course WHERE courseID = ?");
        $removeCourse->bind_param("s", $courseID);

        if ($removeCourse->execute()) {
            header("Location: lecturer.php?deleted=1");
            exit;
        } else {
            echo "Failed to delete course: " . $conn->error;
        }

        $removeCourse->close();    
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['addDiscount'])) {
        $courseID = mysqli_real_escape_string($conn, $courseID);
        $discount = $_POST['discount'];
        
        $addDiscount = $conn->query("SELECT price FROM course WHERE courseID = '$courseID'");
        
        if ($addDiscount->num_rows > 0) {
            $row = $addDiscount->fetch_assoc();
            $originalPrice = $row['price'];
            
            $finalPrice = $originalPrice - ($originalPrice * ($discount / 100));
            
            $checkExist = $conn->query("SELECT * FROM discount WHERE courseID = '$courseID'");
            
            if ($checkExist->num_rows > 0) {
                $conn->query("UPDATE discount SET discountPercent = '$discount', finalPrice = '$finalPrice' WHERE courseID = '$courseID'");
            } else {
                $conn->query("INSERT INTO discount (courseID, discountPercent, finalPrice) VALUES ('$courseID', '$discount', '$finalPrice')");
            }
            
            header("Location: courseManage.php?courseID=$courseID");
        } else {
            echo "Product not found.";
        }
    }
}

$query = "SELECT discountPercent, price FROM course c LEFT JOIN discount d ON c.courseID = d.courseID  WHERE c.courseID = '$courseID'";
$result = mysqli_query($conn, $query);
$checkDiscount = mysqli_fetch_assoc($result);

$discountCheck = $checkDiscount['discountPercent'];
$price = $checkDiscount['price'];
$checkDiscount['finalPrice'] = ($discount > 0) ? $price - ($price * $discount / 100) : null;


if (isset($_POST['removeDiscount'])) {
        $courseID = mysqli_real_escape_string($conn, $courseID);
        $removeDiscount = $conn->prepare("DELETE FROM discount WHERE courseID = ?");
        $removeDiscount->bind_param("s", $courseID);

        if ($removeDiscount->execute()) {
            header("Location: courseManage.php?courseID=$courseID");
            exit;
        } 
        $removeDiscount->close();    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/courseManage.css">
    <link rel="stylesheet" href="css/lecturer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Afacad:ital,wght@0,400..700;1,400..700&family=Bebas+Neue&family=Gabarito:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="icon" href="images/logos/logo.png" type="image/png">
    <title>LEC Academia</title>
</head>
<body>
    <div class="container">
        <div class="left">
            <div class="menu">
                <img src="uploads/profilePicture/<?= htmlspecialchars($lecturer['image'])?>" class="lecImage">
                <a class="home" href="lecturer.php"><i class="fa-solid fa-house"></i></a>
                <a href="lecturer.php"><i class="fa-solid fa-book-medical"></i></a>
                <a href="lecturer.php"><i class="fa-solid fa-user-group"></i></a>
                <a href="lecturer.php"><i class="fa-solid fa-chart-column"></i></a>    
            </div>
            <img src="images/logos/fullLogo-black.png" alt="" class="imglogo">
            <a class="cs" id="cs"><i class="fa-solid fa-headset"></i></a>
        </div>
        <div class="right">
            <div class="content" id="dashboard">
                <div class="head">
                    <a href="lecturer.php"><i class="fa-solid fa-chevron-left"></i></a>
                    <h1>Manage Course</h1>
                    <h2>ID <?= htmlspecialchars($course['courseID']) ?></h2>
                    <h3>Active</h3>
                    <form method="post">             
                        <button type="submit" name="removeCourse">Remove This Course</button>
                    </form>
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
                                 <?php if (!$checkDiscount): ?>
                                    <div class="pricing-non-discount">
                                        <h5><span>IDR</span> <?= number_format($course['price'], 0, ',', '.')?></h5>
                                        <button id="discount-button" class="button-discount">Manage Discount</button>
                                        <div id="discount" class="manage-discount" style="display:none;">
                                            <form method="post">
                                                <input type="number" min="10" max="95" name="discount">
                                                <button type="submit" name="addDiscount">Set Discount</button>
                                            </form>
                                        </div>
                                     </div>
                                <?php else: ?>
                                    <div class="pricing-discount">                                    
                                        <h5><span>IDR</span> <?= number_format($course['finalPrice'], 0, ',', '.')?></h5>
                                        <p><span>IDR</span> <?= number_format($course['price'], 0, ',', '.')?></p>
                                        <div id="discount" class="manage-discount">
                                            <form method="post">
                                                <button type="submit" name="removeDiscount">Remove Discount</button>
                                            </form>
                                        </div>
                                    </div> 
                                <?php endif; ?> 
                            </div>
                           
                        </div>
                    </div>
                    <div class="manage">
                        <div class="add-session">   
                            <div class="buttons" >
                                <div class="add-session-col">
                                    <button class="add-session-button" id="lesson-button">Add Lessons</button>
                                    <button class="add-session-button" id="exercise-button">Add Exercise</button>
                                    <button class="add-session-button" id="project-button">Add Project</button>
                                </div>
                                <div class="certi-req">
                                    <button class="add-requirement-button" id="certification-button">Add Certification</button>
                                    <button class="add-requirement-button" id="requirement-button">Add Software Requirement</button>
                                </div>
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
                                <form method="post" id="requirement" style="display:none;">
                                    <div class="header-add">
                                        <h2>Software Requirement</h2>
                                        <p>Add software recomendation for user to use in this course</p>
                                    </div>
                                    <label>Software</label>
                                    <input type="text" name="software" required>
                                    <label>Download Link</label>
                                    <textarea name="downloadLink" required></textarea>
                                    <button type="submit" name="addRequirement">Add Requirement</button>
                                </form>
                                <div id="certification" style="display:none;">
                                    <?php if ($certificateData): ?>
                                        <div class="header-add">
                                            <h2>Course Certification</h2>
                                            <p>Certificate already issued for this course:</p>
                                        </div>
                                        <div class="certificate-preview">
                                            <h4><?= htmlspecialchars($certificateData['certificateName']) ?></h4>
                                            <p><?= htmlspecialchars($certificateData['issuer']) ?></p>
                                            <img src="uploads/certificate/<?= htmlspecialchars($certificateData['image']) ?>" alt="Certificate" class="preview-certif">
                                        </div>
                                    <?php else: ?>
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="header-add">
                                                <h2>Course Certification</h2>
                                                <p>Add course certificate issued by your university</p>
                                            </div>
                                            <label>Certificate Title</label>
                                            <input type="text" name="certificateTitle" required>
                                            <label>Certificate Image</label>
                                            <input type="file" name="certificateImage" id="fileInput" style="display: none;" required>
                                            <div class="upload">
                                                <button type="button" id="triggerFileInput">
                                                    <i class="fa-solid fa-file-arrow-up"></i>Upload File
                                                </button>
                                                <span id="fileName" style="display: none;"><span id="fileLabel"></span></span>
                                            </div>
                                            <img id="imagePreview" alt="Preview" class="preview-certif">
                                            <button type="submit" name="addCertification">Add Certification</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                       <div class="session-list" id="session-record" >
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


