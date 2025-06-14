<?php
session_start();
include 'api/connect.php';
include 'functions.php';

if (!isset($_SESSION['lecturerID'])) {
    header("Location: loginLecturer.php");
    exit;
}
$lecturerID = $_SESSION['lecturerID'];

$query = "SELECT * FROM lecturer WHERE lecturerID = '$lecturerID'";
$result = mysqli_query($conn, $query);
$lecturer = mysqli_fetch_assoc($result);

$search = isset($_GET['searchOnly']) ? trim($_GET['searchOnly']) : '';
$level = isset($_GET['level']) ? trim($_GET['level']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$where = [];
$where[] = "c.lecturerID = '" . mysqli_real_escape_string($conn, $lecturerID) . "'";
if ($search !== '') {
    $searchSafe = mysqli_real_escape_string($conn, $search);
    $where[] = "c.courseTitle LIKE '%$searchSafe%'";
}
if ($level !== '') {
    $levelSafe = mysqli_real_escape_string($conn, $level);
    $where[] = "c.level = '$levelSafe'";
}
if ($category !== '') {
    $categorySafe = mysqli_real_escape_string($conn, $category);
    $where[] = "c.courseCatID = '$categorySafe'";
}

$whereClause = '';

if (!empty($where)) {
    $whereClause = 'WHERE ' . implode(' AND ', $where);
}

$query = "SELECT c.*, cc.courseCat, 
        COUNT(e.courseID) AS totalEnrolled, 
        COUNT(s.courseID) AS totalSession,
        COUNT(l.sessionID) AS totalLesson,
        COUNT(a.sessionID) AS totalExercise,
        COUNT(a.sessionID) AS totalProject
          FROM course c
          JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
          LEFT JOIN enrolled e ON c.courseID = e.courseID
          LEFT JOIN `session` s ON c.courseID = s.courseID
          LEFT JOIN lesson l ON s.sessionID = l.sessionID
          LEFT JOIN exercise a ON s.sessionID = a.sessionID
          LEFT JOIN project p ON s.sessionID = p.sessionID
          $whereClause
          GROUP BY c.courseID
          ORDER BY c.courseID ASC";

$result = mysqli_query($conn, $query);
$course = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT * FROM coursecategory";
$result = mysqli_query($conn, $query);
$coursecategory = mysqli_fetch_all($result, MYSQLI_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['addCourse'])) {
        $courseTitle = mysqli_real_escape_string($conn, $_POST['courseTitle']);
        $courseDescription = mysqli_real_escape_string($conn, $_POST['courseDescription']);
        $price = $_POST['price'];
        $catID = $_POST['catID'];
        $level = $_POST['level'];
        

        $courseID = generateCustomID1($conn, "C", "course", "courseID");

        if (isset($_FILES['courseThumbnail']) && $_FILES['courseThumbnail']['error'] == 0) {
            $thumbnail = basename($_FILES['courseThumbnail']['name']);
            $imageTmpName = $_FILES['courseThumbnail']['tmp_name'];
            $imagePath = "uploads/thumbnails/" . $thumbnail;

            if (move_uploaded_file($imageTmpName, $imagePath)) {
                $insertCourse = $conn->prepare("INSERT INTO course (courseID, courseCatID, lecturerID, courseTitle, courseDescription, price, courseThumbnail, level) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $insertCourse->bind_param("sssssdss", $courseID, $catID, $lecturerID, $courseTitle, $courseDescription, $price, $thumbnail, $level);


                if ($insertCourse->execute()) {
                    header("Location: lecturer.php?success=1");
                    exit;
                } else {
                    echo "Gagal menambahkan course: " . $conn->error;
                }
                $insertCourse->close();
            } else {
                echo "Failed to upload image.";
            }
        } else {
            echo "No image uploaded or an error occurred.";
        }
    }
    
}


$query = "SELECT ea.studentID, ea.exerciseID, ea.answer, ea.score, ea.status,
                 e.sessionID, s.courseID, st.email, st.name, st.studentImage, st.name
          FROM exerciseattempt ea
          JOIN student st ON ea.studentID = st.studentID
          JOIN exercise e ON ea.exerciseID = e.exerciseID
          JOIN `session` s ON e.sessionID = s.sessionID";
$result = mysqli_query($conn, $query);
$exerciseattempts = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT pa.studentID, pa.projectID, pa.submitedFile, pa.score, pa.status,
                 p.sessionID, s.courseID, st.email, st.name, st.studentImage, st.name
          FROM projectattempt pa
          JOIN student st ON pa.studentID = st.studentID
          JOIN project p ON pa.projectID = p.projectID
          JOIN `session` s ON p.sessionID = s.sessionID";
$result = mysqli_query($conn, $query);
$projectattempts = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_score'])) {
    $studentID = $_POST['studentID'];
    $exerciseID = $_POST['exerciseID'];
    $sessionID = $_POST['sessionID'];
    $courseID = $_POST['courseID'];
    $score = floatval($_POST['score']);
    $status = "Checked";

    $stmt = $conn->prepare("UPDATE exerciseattempt SET score = ?, status = ? WHERE studentID = ? AND exerciseID = ?");
    $stmt->bind_param("dsss", $score, $status, $studentID, $exerciseID);
    $stmt->execute();
    $stmt->close();

    $sessionStatus = ($score >= 7.5) ? "Passed" : "Not Pass";
    $check = $conn->prepare("SELECT * FROM learningprogress WHERE studentID = ? AND sessionID = ?");
    $check->bind_param("ss", $studentID, $sessionID);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE learningprogress SET progressValue = ?, sessionStatus = ? WHERE studentID = ? AND sessionID = ?");
        $stmt->bind_param("dsss", $score, $sessionStatus, $studentID, $sessionID);
    } else {
        $stmt = $conn->prepare("INSERT INTO learningprogress (studentID, sessionID, progressValue, sessionStatus) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $studentID, $sessionID, $score, $sessionStatus);
    }
    $stmt->execute();
    $stmt->close();

    if ($score >= 7.5) {
        $stmt = $conn->prepare("SELECT progress FROM overallprogress WHERE studentID = ? AND courseID = ?");
        $stmt->bind_param("ss", $studentID, $courseID);
        $stmt->execute();
        $result = $stmt->get_result();

        $currentProgress = 0;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentProgress = (int)$row['progress'];
        }

        $newProgress = min(100, $currentProgress + 20);

        if ($result->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE overallprogress SET progress = ? WHERE studentID = ? AND courseID = ?");
            $stmt->bind_param("dss", $newProgress, $studentID, $courseID);
        } else {
            $progressStatus = null;
            $stmt = $conn->prepare("INSERT INTO overallprogress (studentID, courseID, progress, progressStatus) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $studentID, $courseID, $newProgress, $progressStatus);
        }
        $stmt->execute();
        $stmt->close();

        if ($newProgress >= 100) {
            $stmt = $conn->prepare("UPDATE overallprogress SET progressStatus = 'Passed' WHERE studentID = ? AND courseID = ?");
            $stmt->bind_param("ss", $studentID, $courseID);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE lp FROM learningprogress lp 
                                    JOIN session s ON lp.sessionID = s.sessionID 
                                    WHERE lp.studentID = ? AND s.courseID = ?");
            $stmt->bind_param("ss", $studentID, $courseID);
            $stmt->execute();
            $stmt->close();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_score_project'])) {
    $studentID = $_POST['studentID'];
    $projectID = $_POST['projectID'];
    $sessionID = $_POST['sessionID'];
    $courseID = $_POST['courseID'];
    $feedback = $_POST['feedback'];
    $score = floatval($_POST['score']);
    $status = "Checked";

    $stmt = $conn->prepare("UPDATE projectattempt SET score = ?, status = ?, projectFeedback = ? WHERE studentID = ? AND projectID = ?");
    $stmt->bind_param("dssss", $score, $status, $feedback, $studentID, $projectID);
    $stmt->execute();
    $stmt->close();

    $sessionStatus = ($score >= 7.5) ? "Passed" : "Not Pass";
    $check = $conn->prepare("SELECT * FROM learningprogress WHERE studentID = ? AND sessionID = ?");
    $check->bind_param("ss", $studentID, $sessionID);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE learningprogress SET progressValue = ?, sessionStatus = ? WHERE studentID = ? AND sessionID = ?");
        $stmt->bind_param("dsss", $score, $sessionStatus, $studentID, $sessionID);
    } else {
        $stmt = $conn->prepare("INSERT INTO learningprogress (studentID, sessionID, progressValue, sessionStatus) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $studentID, $sessionID, $score, $sessionStatus);
    }
    $stmt->execute();
    $stmt->close();

    if ($score >= 7.5) {
        $stmt = $conn->prepare("SELECT progress FROM overallprogress WHERE studentID = ? AND courseID = ?");
        $stmt->bind_param("ss", $studentID, $courseID);
        $stmt->execute();
        $result = $stmt->get_result();

        $currentProgress = 0;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentProgress = (int)$row['progress'];
        }

        $newProgress = min(100, $currentProgress + 40);

        if ($result->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE overallprogress SET progress = ? WHERE studentID = ? AND courseID = ?");
            $stmt->bind_param("dss", $newProgress, $studentID, $courseID);
        } else {
            $progressStatus = null;
            $stmt = $conn->prepare("INSERT INTO overallprogress (studentID, courseID, progress, progressStatus) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $studentID, $courseID, $newProgress, $progressStatus);
        }
        $stmt->execute();
        $stmt->close();

        if ($newProgress >= 100) {
            $stmt = $conn->prepare("UPDATE overallprogress SET progressStatus = 'Passed' WHERE studentID = ? AND courseID = ?");
            $stmt->bind_param("ss", $studentID, $courseID);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE lp FROM learningprogress lp 
                                    JOIN session s ON lp.sessionID = s.sessionID 
                                    WHERE lp.studentID = ? AND s.courseID = ?");
            $stmt->bind_param("ss", $studentID, $courseID);
            $stmt->execute();
            $stmt->close();
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/lecturer.css">
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
                <button class="home" id="dash"><i class="fa-solid fa-house"></i></button>
                <button id="add"><i class="fa-solid fa-book-medical"></i></button>
                <button id="score"><i class="fa-solid fa-laptop-file"></i></button> 
            </div>
            <img src="images/logos/fullLogo-black.png" class="imglogo">
            <button class="cs" id="cs"><i class="fa-solid fa-headset"></i></button>
        </div>
        <div class="right">
            <div class="content" id="dashboard">
                <div class="content-header">
                    <h1>Dashboard</h1>
                    <h6>Welcome, Mr. <?= htmlspecialchars($lecturer['lecturerName'])?></h6>                    
                </div>
                <div class="main">
                    <div class="main-header">
                        <form method="get">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input  type="text" name="searchOnly" placeholder="Search" value="<?= isset($_GET['searchOnly']) 
                            ? htmlspecialchars($_GET['searchOnly']) : '' ?>" autocomplete="off">
                            <div class="select-group">
                                <select name="level">
                                    <option value="">All Levels</option>
                                    <option value="Beginner" <?= (isset($_GET['level']) && $_GET['level'] === 'Beginner') ? 'selected' : '' ?>>Beginner</option>
                                    <option value="Intermediate" <?= (isset($_GET['level']) && $_GET['level'] === 'Intermediate') ? 'selected' : '' ?>>Intermediate</option>
                                    <option value="Advanced" <?= (isset($_GET['level']) && $_GET['level'] === 'Advanced') ? 'selected' : '' ?>>Advanced</option>
                                </select>

                                <select name="category">
                                    <option value="">All Categories</option>
                                    <option value="PL" <?= (isset($_GET['category']) && $_GET['category'] === 'PL') ? 'selected' : '' ?>>Programming Language</option>
                                    <option value="AD" <?= (isset($_GET['category']) && $_GET['category'] === 'AD') ? 'selected' : '' ?>>Application Development</option>
                                    <option value="WD" <?= (isset($_GET['category']) && $_GET['category'] === 'WD') ? 'selected' : '' ?>>Website Development</option>
                                    <option value="VD" <?= (isset($_GET['category']) && $_GET['category'] === 'VD') ? 'selected' : '' ?>>Visual Design</option>
                                    <option value="VE" <?= (isset($_GET['category']) && $_GET['category'] === 'VE') ? 'selected' : '' ?>>Video Editing</option>
                                    <option value="DM" <?= (isset($_GET['category']) && $_GET['category'] === 'DM') ? 'selected' : '' ?>>Data Analysis</option>
                                </select>
                                <button type="submit">Filter</button>
                            </div>
 
                        </form>
                    </div>
                    
                    <div class="main-content">
                        <div class="course-list">
                            <?php if (empty($course)): ?>
                                <p>No courses found.</p>
                            <?php else: ?>
                                <?php foreach ($course as $c):?>
                                        <a href="courseManage.php?courseID=<?= htmlspecialchars($c['courseID'])?>" class="course-wrapper">
                                            <img src="uploads/thumbnails/<?= htmlspecialchars($c['courseThumbnail'])?>" alt="">
                                            <div class="attribute">
                                                <div class="first-attribute">
                                                    <h2><?= htmlspecialchars($c['courseTitle'])?></h2>
                                                    <div class="cat-session">
                                                        <p class="cat"><?= htmlspecialchars($c['courseCat'])?></p>
                                                        <p>Total Session <?= htmlspecialchars($c['totalSession'])?></p>
                                                    </div>
                                                    <div class="dc">
                                                        <div class="dt">
                                                            <div class="ds">
                                                                <div class="enroll-level">
                                                                    <p class="enroll"><?= htmlspecialchars($c['totalEnrolled'])?> Enrolled</p>
                                                                    <p>Level <?= htmlspecialchars($c['level'])?></p>
                                                                </div>  
                                                            </div>
                                                            <div class="module-lesson">
                                                                <p><?= htmlspecialchars($c['totalLesson'])?> Lesson</p>
                                                                <tr></tr>
                                                                <p><?= htmlspecialchars($c['totalExercise'])?> Excercise</p>
                                                                <tr></tr>
                                                                <p><?= htmlspecialchars($c['totalProject'])?> Project</p>
                                                                <tr></tr>
                                                            </div>
                                                        </div>
                                                        <h2>ID <?= htmlspecialchars($c['courseID'])?></h2>
                                                    </div>
                                                    

                                                </div>
                                            </div>
                                        </a>
                                <?php endforeach;?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div id="successPopup" class="popup">
                        <div class="popup-content">
                            <p>Course successfully added!</p>
                            <button onclick="closePopupp()">Close</button>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
                    <div id="deletedPopup" class="popup">
                        <div class="popup-content">
                            <p>Course has been successfully deleted!</p>
                            <button onclick="closePopup()">Close</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="content"id="addCourse" style="display:none;">
                <h1>Add Course</h1>
                <div class="main">
                    <div class="add-container">
                        <form method="post" enctype="multipart/form-data">
                            <div class="inputs">
                            <div class="category">
                                <h3>Field Of Learning</h3>
                                <?php foreach ($coursecategory as $category): ?>
                                <label>
                                    <input type="radio" name="catID" value="<?= htmlspecialchars($category['courseCatID'])?>" required>
                                    <div class="category-wrap">
                                    
                                    <span><?= htmlspecialchars($category['courseCat']) ?></span>
                                    </div>
                                </label><br>
                                <?php endforeach; ?>
                            </div>
                            <div class="input-form">
                                <h3>Level</h3>
                                <div class="category">
                                    <?php
                                    $levels = ['Beginner', 'Intermediate', 'Advanced'];
                                    foreach ($levels as $lvl):
                                    ?>
                                    <label>
                                        <input type="radio" name="level" value="<?= $lvl ?>" required hidden>
                                        <div class="category-wrap">
                                            <span><?= $lvl ?></span>
                                        </div>
                                    </label><br>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                                    
                                <div class="detail-form">
                                    <h3>Product Details</h3>
                                    <div class="input-form">
                                    <input type="text" name="courseTitle" placeholder="Course Title" required>
                                    </div>
                                    <div class="input-form">
                                 <input type="number" name="price" placeholder="Price" required>
                                    </div>
                                <div class="input-form">
                                    <textarea id="address" name="courseDescription" placeholder="Description"></textarea>
                                </div>
                            </div>
                            <div class="image-input">
                                <input type="file" id="courseThumbnail" name="courseThumbnail" accept="image/*" required onchange="previewImage(event)" hidden>
                                <label for="courseThumbnail" class="custom-file-label">Choose Thumbnail</label>
                                <img id="preview">
                            </div>
                            </div>
                                <button type="submit" name="addCourse">Add Course</button>
                        </form>
                        <div class="addcourse-note">
                            <h5>Note</h5>
                            <p>After adding new course, you can manage additional course details in manage course</p>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            <div class="content"id="addScore" style="display:none;">
                <div class="scoring">
                    <div class="exerciseattempt">
                        <h1>Exercise Attempt</h1>
                        <div class="exercise-list">
                            <?php foreach ($exerciseattempts as $a): ?>
                                <div class="answer-wrapper">
                                    <div class="student-exercise">
                                        <img src="uploads/profilePicture/<?= htmlspecialchars($a['studentImage']) ?>" alt="">
                                        <p><?= htmlspecialchars($a['name']) ?></p>
                                    </div>
                                    <p>Exercise ID <?= htmlspecialchars($a['exerciseID']) ?></p>
                                    <h2>Answer</h2>
                                    <p><?= htmlspecialchars($a['answer']) ?></p>
                                    <div class="status-answer">
                                        <h2>Current Status: </h2>
                                        <p><?= $a['status'] ?></p>
                                    </div>
                                    <p><strong>Score: </strong> <?= is_null($a['score']) ? 'Not Rated' : $a['score'] ?></p>
                                    <form method="post">
                                        <input type="hidden" name="studentID" value="<?= $a['studentID'] ?>">
                                        <input type="hidden" name="exerciseID" value="<?= $a['exerciseID'] ?>">
                                        <input type="hidden" name="sessionID" value="<?= $a['sessionID'] ?>">
                                        <input type="hidden" name="courseID" value="<?= $a['courseID'] ?>">
                                        <input type="number" name="score" min="0" max="10" step="0.1" required>
                                        <button type="submit" name="submit_score">Submit Score</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                        <div class="projectattempt">
                            <h1>Project Attempt</h1>
                            <?php foreach ($projectattempts as $pa): ?>
                                <div class="projects-wrapper">
                                    <div class="student-exercise">
                                        <img src="uploads/profilePicture/<?= htmlspecialchars($pa['studentImage']) ?>" alt="">
                                        <p><?= htmlspecialchars($pa['name']) ?></p>
                                    </div>
                                    <p>Project ID <?= htmlspecialchars($pa['projectID']) ?></p>
                                    <h5>Submission File</h5>
                                    <div class="file">
                                        <h2><?= htmlspecialchars($pa['submitedFile']) ?></h2>
                                        <a href="submissions/<?= htmlspecialchars($pa['submitedFile']) ?>" download>
                                            Download File
                                        </a>
                                    </div>
                                    <p><strong>Score: </strong> <?= is_null($pa['score']) ? 'Not Rated' : $pa['score'] ?></p>
                                    <form method="post">
                                        <input type="hidden" name="studentID" value="<?= $pa['studentID'] ?>">
                                        <input type="hidden" name="projectID" value="<?= $pa['projectID'] ?>">
                                        <input type="hidden" name="sessionID" value="<?= $pa['sessionID'] ?>">
                                        <input type="hidden" name="courseID" value="<?= $pa['courseID'] ?>">
                                        <h2>Feedback</h2>
                                        <input type="text" name="feedback" required>
                                        <h2>Score</h2>
                                        <input type="number" name="score" min="0" max="10" step="0.1" required>
                                        <button type="submit" name="submit_score_project">Submit</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                </div>
            </div>
    </div>    

    <script src="js/lecturer.js"></script>
</body>
</html>