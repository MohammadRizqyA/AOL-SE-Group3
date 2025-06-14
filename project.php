<?php
session_start();
include 'api/connect.php';
include 'exploreDropdown.php';

if (!isset($_SESSION['studentID'])) {
    header("Location: signin.php");
}
$studentID = $_SESSION['studentID'];
$projectID = mysqli_real_escape_string($conn, $_GET['projectID']);


$query = "SELECT p.*, c.courseID
          FROM project p
          JOIN `session` s ON p.sessionID = s.sessionID
          JOIN course c ON s.courseID = c.courseID
          WHERE projectID = '$projectID'";
$result = mysqli_query($conn, $query);
$project = mysqli_fetch_assoc($result);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_project'])) {
    $studentID = $_POST['studentID'];
    $projectID = $_POST['projectID'];

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        die("❌ File upload error.");
    }

    $uploadDir = 'submissions/';
    $originalName = basename($_FILES['file']['name']);
    $cleanedName = preg_replace("/[^A-Za-z0-9_\-\.]/", "_", $originalName); 
    $newFileName = $studentID . '_' . $projectID . '_' . $cleanedName;
    $uploadPath = $uploadDir . $newFileName;

    $checkQuery = $conn->prepare("SELECT submitedFile FROM projectattempt WHERE studentID = ? AND projectID = ?");
    $checkQuery->bind_param("ss", $studentID, $projectID);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows > 0) {
        $existing = $checkResult->fetch_assoc();
        $oldFile = $uploadDir . $existing['submitedFile'];
        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
    }
    $checkQuery->close();

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath)) {
        $status = "Waiting";
        $score = null;
        $fileNameForDB = $newFileName; 

        $stmt = $conn->prepare("REPLACE INTO projectattempt (studentID, projectID, submitedFile, score, status) 
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $studentID, $projectID, $fileNameForDB, $score, $status);
        $stmt->execute();
        $stmt->close();

        header("Location: project.php?projectID=$projectID");
        exit;
    } else {
        die("❌ Failed to save uploaded file.");
    }
}


$uploadedFileName = '';
if (isset($_SESSION['studentID']) && isset($projectID)) {
    $studentID = $_SESSION['studentID'];
    $check = $conn->prepare("SELECT submitedFile FROM projectattempt WHERE studentID = ? AND projectID = ?");
    $check->bind_param("ss", $studentID, $projectID);
    $check->execute();
    $res = $check->get_result();

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $uploadedFileName = basename($row['submitedFile']);
    }
    $check->close();
}

$query = "SELECT score FROM projectattempt WHERE studentID = '$studentID' AND projectID = '$projectID'";
$result = mysqli_query($conn, $query);
$dataAttempt = mysqli_fetch_assoc($result);
$isAttempted = $dataAttempt !== null;
$score = $isAttempted ? $dataAttempt['score'] : null;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar-learning.css">
    <link rel="stylesheet" href="css/project.css">
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
                <a href="study.php?courseID=<?= htmlspecialchars($project['courseID'])?>"><i class="fa-solid fa-chevron-left"></i></a>
                <h1>Project</h1>
            </div>
            <div class="project-content">
                <div class="col">
                    <div class="project-explanation">
                        <h2>Session Explanation</h2>
                        <p>
                            In this final session, you are asked to work on a project according to 
                            the criteria requested by the current course topic. To collect this project 
                            assignment, you can upload the file in the submission column below.
                        </p>
                    </div>
                    <div class="project-theme">
                        <h2><?= htmlspecialchars($project['projectTitle'])?></h2>
                        <h3>Detail Of This Project</h3>
                        <p><?= htmlspecialchars($project['projectDetail'])?></p>
                    </div>
                    <div class="note">
                        <h3>Attention</h3>
                        <p>
                            • This session is total weighted 40% <br> 
                            • To pass this project, you must achieve a minimum passing grade of 7.5 out of 10 <br>
                            • If the submitted file does not comply with the provisions, the score will automatically set to 0.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="submission-header">
                        <h2>Submission</h2>
                        <p><strong>File types requirement:</strong> <?= htmlspecialchars($project['fileType'])?></p>
                    </div>
                    <?php if (!$isAttempted): ?>
                    <form action="project.php?projectID=<?= htmlspecialchars($project['projectID'])?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="studentID" value="<?= htmlspecialchars($_SESSION['studentID']) ?>">
                        <input type="hidden" name="projectID" value="<?= htmlspecialchars($projectID) ?>">
                        <input type="file" name="file" id="fileInput" style="display: none;" required>
                        <div class="upload">
                            <button type="button" id="triggerFileInput"><i class="fa-solid fa-file-arrow-up"></i>Upload File</button>
                            <span id="fileName">
                                <span id="fileLabel"></span>                                
                            </span>
                        </div>
                        <br><br>
                        <button class="submit-button" type="submit" name="submit_project">Submit</button>
                    </form>
                    <?php elseif ($score === null): ?>
                        <div class="attempt">
                            <h5>Latest Submission</h5>
                            <h2><?= htmlspecialchars($uploadedFileName) ?></h2>
                            <a href="<?= htmlspecialchars($row['submitedFile']) ?>" download style="display: inline-block; margin: 10px 0; text-decoration: none; color: #2563eb;">
                                <i class="fa-solid fa-file-arrow-down"></i>
                            </a>
                            <div class="attempt-profile">
                                <img src="<?= htmlspecialchars($profileImage);  ?>" class="nav-profile-img">
                                <h2><?= htmlspecialchars($student['name'])?></h2>
                            </div>
                            <p class="submission-text">You can re-attempt this project after the assessor provides your project result</p>
                        </div>
                    <?php elseif ($score < 7.5): ?>
                        <div class="notpass">
                            <div class="notpass-project">
                                <i class="fa-solid fa-circle-xmark"></i>
                                <h2>Your answer result still does not meet the minimum pass.</h2>
                            </div>
                            <p>The result is <?= number_format($score, 1) ?> Please re-attempt your answer.</p>
                            <form action="project.php?projectID=<?= htmlspecialchars($project['projectID'])?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="studentID" value="<?= htmlspecialchars($_SESSION['studentID']) ?>">
                                <input type="hidden" name="projectID" value="<?= htmlspecialchars($projectID) ?>">
                                <input type="file" name="file" id="fileInput" style="display: none;" required>
                                <div class="upload">
                                    <button type="button" id="triggerFileInput"><i class="fa-solid fa-file-arrow-up"></i>Upload File</button>
                                    <span id="fileName">
                                        <span id="fileLabel"></span>                                
                                    </span>
                                </div>
                                <br><br>
                                <button class="submit-button" type="submit" name="submit_project">Submit</button>
                            </form>
                            <div class="attempt">
                                <h5>Latest Submission</h5>
                                <h2><?= htmlspecialchars($uploadedFileName) ?></h2>
                                <a href="<?= htmlspecialchars($row['submitedFile']) ?>" download style="display: inline-block; margin: 10px 0; text-decoration: none; color: #2563eb;">
                                    <i class="fa-solid fa-file-arrow-down"></i>
                                </a>
                                <div class="attempt-profile">
                                    <img src="<?= htmlspecialchars($profileImage);  ?>" class="nav-profile-img">
                                    <h2><?= htmlspecialchars($student['name'])?></h2>
                                </div>
                                <p class="submission-text">You can re-attempt this project after the assessor provides your project result</p>
                            </div>
                            <div class="feedback">
                                <h2>Project Feedback</h2>
                                <div class="feedback-col">
                                    <div class="acessor-response">
                                        <img src="<?= htmlspecialchars($profileImage);  ?>" class="feed-img">
                                        <p>Ur not suppose to extract that 3D Models</p>
                                    </div>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="project-submitted">
                            <div class="passed-project">
                                <i class="fa-solid fa-check-to-slot"></i>
                                <h2>Congratulations! You already passed this project.</h2>
                            </div>
                            <p>Score: <?= number_format($score, 1) ?></p>
                            <div class="attempt">
                                <h5>Latest Submission</h5>
                                <h2><?= htmlspecialchars($uploadedFileName) ?></h2>
                                <a href="<?= htmlspecialchars($row['submitedFile']) ?>" download style="display: inline-block; margin: 10px 0; text-decoration: none; color: #2563eb;">
                                    <i class="fa-solid fa-file-arrow-down"></i>
                                </a>
                                <div class="attempt-profile">
                                    <img src="<?= htmlspecialchars($profileImage);  ?>" class="nav-profile-img">
                                    <h2><?= htmlspecialchars($student['name'])?></h2>
                                </div>
                                <p class="submission-text">You can re-attempt this project after the assessor provides your project result</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.getElementById('fileInput');
    const triggerButton = document.getElementById('triggerFileInput');
    const fileLabel = document.getElementById('fileLabel');

    triggerButton.addEventListener('click', function () {
        fileInput.click();
    });

    fileInput.addEventListener('change', function () {
        if (fileInput.files.length > 0) {
            fileLabel.textContent = fileInput.files[0].name;
        } else {
            fileLabel.textContent = "No file chosen";
        }
    });
});
        
    </script>

</body>
</html>