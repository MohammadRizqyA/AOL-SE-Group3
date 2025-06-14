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


$query = "SELECT 
    c.courseID,
    c.courseTitle,
    c.courseThumbnail,
    c.level,
    cc.courseCat,
    COUNT(e.courseID) AS totalCourse,

    -- total lesson
    SUM(CASE WHEN s.sessionType = 'Lesson' THEN 1 ELSE 0 END) AS totalLesson,
    SUM(CASE WHEN s.sessionType = 'Exercise' THEN 1 ELSE 0 END) AS totalExercise,
    SUM(CASE WHEN s.sessionType = 'Project' THEN 1 ELSE 0 END) AS totalProject,

    -- progress dari overallprogress
    MAX(op.progress) AS progress,

    -- rating student terhadap course ini (pakai MAX karena per student 1 rating saja)
    MAX(r.rating) studentRating

FROM enrolled e
JOIN course c ON e.courseID = c.courseID
JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
LEFT JOIN session s ON c.courseID = s.courseID
LEFT JOIN overallprogress op ON c.courseID = op.courseID AND op.studentID = e.studentID
LEFT JOIN coursereview r ON c.courseID = r.courseID AND r.studentID = e.studentID

WHERE e.studentID = '$studentID'
GROUP BY c.courseID
";
$result = mysqli_query($conn, $query);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);

function getProgressColor($percent) {
    if ($percent < 50) return 'rgba(217, 224, 28, 0.8)';   
    if ($percent < 100) return 'rgba(170, 210, 49, 0.85)';     
    return 'rgba(113, 167, 71, 0.85)';                        
}

$query = "SELECT AVG(progress) AS avgProgress 
          FROM overallprogress 
          WHERE studentID = '$studentID'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$avgProgress = round($row['avgProgress'] ?? 0);

$query = "SELECT 
        COUNT(DISTINCT e.courseID) AS totalCourse,
        SUM(CASE WHEN s.sessionType = 'Lesson' THEN 1 ELSE 0 END) AS totalLesson,
        SUM(CASE WHEN s.sessionType = 'Exercise' THEN 1 ELSE 0 END) AS totalExercise,
        SUM(CASE WHEN s.sessionType = 'Project' THEN 1 ELSE 0 END) AS totalProject
    FROM enrolled e
    JOIN session s ON e.courseID = s.courseID
    WHERE e.studentID = '$studentID'";
$result = mysqli_query($conn, $query);
$summary = mysqli_fetch_assoc($result);
$summary['totalCourse']; 
$summary['totalLesson'];
$summary['totalExercise']; 
$summary['totalProject'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $uploadDir = 'uploads/profilePicture/';

    $imageFileType = strtolower(pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($imageFileType, $allowedTypes)) {
        $newFileName = $studentID . '.' . $imageFileType;
        $uploadFile = $uploadDir . $newFileName;

        $getOldImageQuery = "SELECT studentImage FROM student WHERE studentID = '$studentID'";
        $result = mysqli_query($conn, $getOldImageQuery);
        $oldImage = null;

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $oldImage = $row['studentImage'];
        }

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadFile)) {
            if (
                $oldImage && 
                $oldImage !== 'default.png' &&
                $oldImage !== $newFileName
            ) {
                $oldImagePath = $uploadDir . $oldImage;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $updateQuery = "UPDATE student SET studentImage = '$newFileName' WHERE studentID = '$studentID'";
            mysqli_query($conn, $updateQuery);

            header("Location: learningProgress.php");
            exit();
        } else {
            $error = "Gagal mengunggah file.";
        }
    } else {
        $error = "Format file tidak didukung. Harap unggah JPG, JPEG, PNG, atau GIF.";
    }
}




if (isset($_POST['updateName']) && !empty($_POST['name'])) {
    $newName = mysqli_real_escape_string($conn, $_POST['name']);
    $query = "UPDATE student SET Name = '$newName' WHERE studentID = '$studentID'";
    mysqli_query($conn, $query);
    header("Location: learningProgress.php"); 
    exit();
}

if (isset($_POST['updatePhone']) && !empty($_POST['phoneNum'])) {
    $newPhone = mysqli_real_escape_string($conn, $_POST['phoneNum']);
    $query = "UPDATE student SET phoneNumber = '$newPhone' WHERE studentID = '$studentID'";
    mysqli_query($conn, $query);
    header("Location: learningProgress.php");
    exit();
}

if (isset($_POST['updateAddress']) && !empty($_POST['address'])) {
    $newAddress = mysqli_real_escape_string($conn, $_POST['address']);
    $query = "UPDATE student SET Address = '$newAddress' WHERE studentID = '$studentID'";
    mysqli_query($conn, $query);
    header("Location: learningProgress.php");
    exit();
}

if (isset($_POST['updatePassword'])) {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
      
    $checkQuery = "SELECT `password` FROM student WHERE studentID = '$studentID'";
    $result = mysqli_query($conn, $checkQuery);
    $row = mysqli_fetch_assoc($result);

    if (password_verify($oldPassword, $row['password'])) {
        $updateQuery = "UPDATE student SET password = '$newPassword' WHERE studentID = '$studentID'";
        mysqli_query($conn, $updateQuery);
        header("Location: learningProgress.php");
        exit();
    } else {
        $error = "Password lama salah.";
    }
}

if (isset($_POST['updateEmail'])) {
    $passwordConfirm = $_POST['passwordConfirm'];
    $newEmail = $_POST['email'];

    $checkQuery = "SELECT password FROM student WHERE studentID = '$studentID'";
    $result = mysqli_query($conn, $checkQuery);
    $row = mysqli_fetch_assoc($result);

    if (password_verify($passwordConfirm, $row['password'])) {
        $updateQuery = "UPDATE student SET email = '$newEmail' WHERE studentID = '$studentID'";
        mysqli_query($conn, $updateQuery);
        header("Location: learningProgress.php");
        exit();
    } else {
        $error = "Password konfirmasi salah.";
    }
}

$query = "SELECT 
        c.courseTitle,
        cert.certificateName,
        cert.image,
        cert.issuer
        FROM overallprogress op
        JOIN course c ON op.courseID = c.courseID
        JOIN certification `cert` ON c.courseID = cert.courseID
        WHERE op.studentID = '$studentID'
        AND op.progress = 100";
$result = mysqli_query($conn, $query);
$certification = mysqli_fetch_all($result, MYSQLI_ASSOC);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $courseID = mysqli_real_escape_string($conn, $_POST['courseID']);
    $studentID = mysqli_real_escape_string($conn, $_POST['studentID']);
    $rating = intval($_POST['rate']);
    $reviewText = mysqli_real_escape_string($conn, $_POST['reviewText']);
    $reviewDate = date('Y-m-d');

    $check = mysqli_query($conn, "SELECT * FROM coursereview WHERE courseID = '$courseID' AND studentID = '$studentID'");
    if (mysqli_num_rows($check) > 0) {
        $query = "UPDATE coursereview 
                  SET rating = '$rating', review = '$reviewText', reviewDate = '$reviewDate'
                  WHERE courseID = '$courseID' AND studentID = '$studentID'";
    } else {
        $query = "INSERT INTO coursereview (courseID, studentID, rating, review, reviewDate)
                  VALUES ('$courseID', '$studentID', '$rating', '$reviewText', '$reviewDate')";
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Thanks for your review!');</script>";
    } else {
        echo "<script>alert('Error saving review.');</script>";
    }
}




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar-learning.css">
    <link rel="stylesheet" href="css/learningProgress.css">
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
                <button id="courses-all"><i class="fa-solid fa-graduation-cap"></i></button>
                <button id="cs"><i class="fa-solid fa-headset"></i></button>    
            </div>
            <img class="logo" src="images/logos/fullLogo.png" alt="">
            <div class="bottom">
                <button id="notification"><i class="fa-solid fa-bell"></i></button>
                <a href="homepage.php"><i class="fa-solid fa-house"></i></a>
            </div>
        </div>
        <div class="content">
            <div class="sub" id="courses" style="display:none;">
                <div class="sub-header">
                    <h1>Your Courses</h1>
                </div>
                <div class="sub-content">
                    <div class="courses">
                        <?php foreach ($courses as $course): ?>
                            <div class="course-wrapper">
                                <img src="uploads/thumbnails/<?= htmlspecialchars($course['courseThumbnail']) ?>">
                                <div class="details">
                                    <h3><?= htmlspecialchars($course['courseTitle']) ?></h3>
                                    <div class="level-progress">
                                        <div class="level-session">
                                            <div class="level-cat">
                                                <p class="cat"><?= $course['courseCat'] ?></p>
                                                <p><?= htmlspecialchars($course['level']) ?></p>
                                            </div>
                                            <div class="sessions"> 
                                                <p><?= $course['totalLesson'] ?> Lessons</p>
                                                <p><?= $course['totalExercise'] ?> Exercises</p>
                                                <p><?= $course['totalProject'] ?> Projects</p>
                                            </div>
                                        </div>
                                        <div class="progress"> 
                                            <?php 
                                                $progress = (int)$course['progress'];
                                                $status   = $course['progressStatus'] ?? '';
                                            ?>
                                            <?php if ($progress === 0 || $progress === null): ?>
                                                <h5>Learning Dashboard</h5>
                                                <div class="not-started">
                                                    <h5>You have not started studying this course yet</h5>
                                                </div>
                                                <a class="notStarted-link" href="study.php?courseID=<?= htmlspecialchars($course['courseID']) ?>">Start Learning</a>
                                            <?php elseif ($progress >= 100 || strtolower($status) === 'passed'): ?>
                                                <div class="rating-container">
                                                    <div class="course-passed">
                                                        <h5>Course Passed</h5>
                                                    </div>
                                                
                                                    <?php if (empty($course['studentRating'])): ?>
                                                        <form method="POST" class="rating-form" >
                                                            <input type="hidden" name="courseID" value="<?= htmlspecialchars($course['courseID']) ?>">
                                                            <input type="hidden" name="studentID" value="<?= htmlspecialchars($studentID) ?>">
                                                            <div class="review-input">
                                                                <input name="reviewText" placeholder="Write your review here..." required></input>
                                                                <div class="star-rating">
                                                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                                                        <input type="radio" id="star<?= $course['courseID'] . $i ?>" name="rate" value="<?= $i ?>" required />
                                                                        <label for="star<?= $course['courseID'] . $i ?>">&#9733;</label>
                                                                    <?php endfor; ?>
                                                                </div>
                                                            </div>
                                                            <button type="submit" name="submit_review">Submit Review</button>
                                                        </form>
                                                    <?php else: ?>
                                                        <div class="rating-display">
                                                            <h4>Rated</h4>
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <span style="color: <?= $i <= (int)$course['studentRating'] ? 'gold' : 'lightgray' ?>">&#9733;</span>
                                                            <?php endfor; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <a class="passed-link" href="study.php?courseID=<?= htmlspecialchars($course['courseID']) ?>">Check Course</a>
                                            <?php else: ?>
                                                <div class="aWrap">
                                                <div class="progress-ongoing">
                                                    <h5>Learning Dashboard</h5>
                                                    <div class="progress-container">                           
                                                        <div class="progress-fill" style="width: <?= $progress ?>%; background-color: <?= getProgressColor($progress) ?>;"></div>
                                                    </div>
                                                    <div class="progress-text"><?= $progress ?>%</div>
                                                </div>
                                                <a href="study.php?courseID=<?= htmlspecialchars($course['courseID']) ?>">Continue Learning</a>
            
                                                </div>
                                            <?php endif; ?>
                                        </div>                                                
                                    </div>                                 
                                </div>
                            </div>
                        <?php endforeach; ?>  
                    </div>
                    <div class="statistic">
                        <div class="circle-wrapper">
                            <svg class="progress-ring" width="200" height="200">
                                <g transform="rotate(-90 100 100)">
                                    <circle class="progress-ring__bg" cx="100" cy="100" r="80" />
                                    <circle class="progress-ring__circle" 
                                            cx="100" cy="100" r="80"
                                            style="stroke-dashoffset: <?= 502 - (502 * $avgProgress / 100) ?>;" />
                                </g>
                                <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" class="circle-text">
                                    <?= $avgProgress ?>%
                                </text>
                            </svg>
                            
                        </div>
                        <div class="detail-overall">
                            <h3>Overall Learning Progress</h3>
                            <div class="enroll-summary">
                                <h4><?= $summary['totalCourse'] ?> Courses Enrolled</h4>
                                <h5>Including total</h5>
                                <p><?= $summary['totalLesson'] ?> Lesson</p>
                                <p><?= $summary['totalExercise'] ?> Exercise</p>
                                <p><?= $summary['totalProject'] ?> Project</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sub" id="profile" style="display:none;">
                <div class="sub-header">
                    <h1>Profile</h1>
                </div>
                <div class="sub-content"> 
                    <div class="profile-container" id="profile-p">
                        <div class="profile-details">
                            <div class="image-setting">
                                <img src="<?= htmlspecialchars($profileImage); ?>" class="profile-img" alt="Profile Image">
                                <i class="fa-solid fa-camera" onclick="triggerFileInput()"></i>
                                <form id="uploadForm" enctype="multipart/form-data" style="display: none;">
                                    <input type="file" name="profileImage" id="fileInput" accept="image/*">
                                </form>
                            </div>
                            <div class="data-profile">
                                <div class="data-first">
                                    <h1><?= htmlspecialchars($student['name']) ?></h1>
                                    <p><?= htmlspecialchars($student['email']) ?></p>
                                </div>
                                <div class="data-second">
                                    <div class="dataa">
                                        <div class="second-f">
                                            <p><strong>Contact: </strong> +62 <?= htmlspecialchars($student['phoneNumber']) ?></p> 
                                            <p><strong>Birth Date: </strong><?= htmlspecialchars($student['DOB']) ?></p>
                                        </div>
                                        <div class="second-s">
                                            <p><strong>Gender: </strong> <?= htmlspecialchars($student['gender']) ?></p>
                                            <p><strong>Occupation: </strong> <?= htmlspecialchars($student['status']) ?></p>
                                        </div>
                                    </div>
                                        <p class="address"><strong>Address:</strong> <?= htmlspecialchars($student['address']) ?></p>
                                </div>
                            </div>
                            
                        </div>
                        <div class="certification">
                            <h1>Your Certification</h1>
                            <div class="cer-list">
                                <?php foreach($certification as $certif): ?>
                                    <div class="certification-wrapper">
                                        <h3><?= htmlspecialchars($certif['certificateName']) ?></h3>
                                        <p>Issuer: <?= htmlspecialchars($certif['issuer']) ?></p>
                                        <img src="uploads/certificate/<?= htmlspecialchars($certif['image']) ?>" alt="">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="account-setting" id="setting-p" style="display:none;">
                        <div class="setting-list">
                            <h1>Personalize Your Profile</h1>
                            <div class="first-form">
                                <form method="post">
                                    <h2>Name</h2>
                                    <div class="input-group">
                                        <input class="in" type="text" name="name" placeholder="<?= htmlspecialchars($student['name']) ?>"  autocomplete="off"required>
                                        <input type="submit" class="btn" value="Submit" name="updateName">
                                    </div>
                                        
                                </form>
                                <form method="post">
                                    <h2>Phone Number</h2>
                                    <div class="input-group">
                                        <input class="in" type="number" name="phoneNum" placeholder="+62 | <?= htmlspecialchars($student['phoneNumber']) ?>" autocomplete="off" required>
                                        <input type="submit" class="btn" value="Submit" name="updatePhone">
                                    </div>       
                                </form>
                            </div>
                            <form method="post">
                                <h2>Address</h2>
                                <div class="input-group">
                                    <textarea name="address" placeholder="<?= htmlspecialchars($student['address']) ?>"  autocomplete="off" required></textarea>
                                    <input type="submit" class="btn" value="Submit" name="updateAddress">
                                </div>
                            </form>
                        </div>
                        <div class="email">
                            <h1>Change Email</h1>
                            <form method="post">
                                <div class="form-wrap">
                                    <div class="input-group">
                                        <input type="password" name="passwordConfirm" placeholder="Enter your password" required>
                                    </div>
                                    <div class="input-group">
                                        <input type="email" name="email" placeholder="Enter your new email" autocomplete="off" required>
                                    </div>
                                    <input type="submit" class="btn" value="Change Email" name="updateEmail">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="account-setting" id="PAS" style="display:none;">
                        <div class="setting-list">
                            <h1>Settings And Security</h1>
                            <form method="post">
                                <div class="form-wrap">
                                    <div class="input-group">
                                        <input type="password" name="oldPassword" placeholder="Enter your old password" required>
                                    </div>
                                    <div class="input-group">
                                        <input type="password" name="newPassword" placeholder="Enter your new password" required>
                                    </div>
                                    <input type="submit" class="btn" value="Change Password" name="updatePassword">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="panel">
                        <h1>Configuration Panel</h1>
                        <div class="buttons">
                            <button id="profile-p-button">Profile</button>
                            <button id="setting-p-button">Account Settings</button>
                            <button id="PAS-button">Privacy And Security</button>
                            <button id="Dlt">Delete Account</button>
                        </div>
                        <a href="api/logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
                                              
    </div>

    <script src="js/learningProgress.js"></script>
</body>
</html>

