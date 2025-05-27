<?php
session_start();
include 'api/connect.php';

if (!isset($_SESSION['adminID'])) {
    die("Anda harus login terlebih dahulu.");
}
$adminID = $_SESSION['adminID'];

$search = isset($_GET['searchOnly']) ? trim($_GET['searchOnly']) : '';
$level = isset($_GET['level']) ? trim($_GET['level']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$where = [];

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
          GROUP BY c.courseID";

$result = mysqli_query($conn, $query);
$course = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT * FROM courseCategory";
$result = mysqli_query($conn, $query);
$courseCategory = mysqli_fetch_all($result, MYSQLI_ASSOC);

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

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['addCourse'])) {
        $courseTitle = mysqli_real_escape_string($conn, $_POST['courseTitle']);
        $courseDescription = mysqli_real_escape_string($conn, $_POST['courseDescription']);
        $price = $_POST['price'];
        $catID = $_POST['catID'];
        $level = $_POST['level'];
        

        $courseID = generateCustomID($conn, "C", "course", "courseID");

        if (isset($_FILES['courseThumbnail']) && $_FILES['courseThumbnail']['error'] == 0) {
            $thumbnail = basename($_FILES['courseThumbnail']['name']);
            $imageTmpName = $_FILES['courseThumbnail']['tmp_name'];
            $imagePath = "uploads/thumbnails/" . $thumbnail;

            if (move_uploaded_file($imageTmpName, $imagePath)) {
                $insertCourse = $conn->prepare("INSERT INTO course (courseID, courseCatID, courseTitle, courseDescription, price, courseThumbnail, level) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
                $insertCourse->bind_param("ssssdss", $courseID, $catID, $courseTitle, $courseDescription, $price, $thumbnail, $level);


                if ($insertCourse->execute()) {
                    header("Location: admin.php?success=1");
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
    
    else if (isset($_POST['removeCourse'])) {
        if (!isset($conn)) {
            die("Database connection error.");
        }

        $courseID = $_POST['courseID'];

        $removeCourse = $conn->prepare("DELETE FROM course WHERE courseID = ?");
        $removeCourse->bind_param("s", $courseID);

        if ($removeCourse->execute()) {
            header("Location: admin.php?deleted=1");
            exit;
        } else {
            echo "Failed to delete product: " . $conn->error;
        }

        $removeCourse->close();
    }
}

$query = "SELECT ea.studentID, ea.exerciseID, ea.answer, ea.score, ea.status,
                 e.sessionID, s.courseID
          FROM exerciseattempt ea
          JOIN exercise e ON ea.exerciseID = e.exerciseID
          JOIN session s ON e.sessionID = s.sessionID";
$result = mysqli_query($conn, $query);
$attempts = mysqli_fetch_all($result, MYSQLI_ASSOC);

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

    $stmt = $conn->prepare("SELECT progress FROM overallprogress WHERE studentID = ? AND courseID = ?");
    $stmt->bind_param("ss", $studentID, $courseID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $newProgress = min(100, $row['progress'] + 20);
        $stmt = $conn->prepare("UPDATE overallprogress SET progress = ? WHERE studentID = ? AND courseID = ?");
        $stmt->bind_param("dss", $newProgress, $studentID, $courseID);
    } else {
        $newProgress = 20;
        $progressStatus = null;
        $stmt = $conn->prepare("INSERT INTO overallprogress (studentID, courseID, progress, progressStatus) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $studentID, $courseID, $newProgress, $progressStatus);
    }
    $stmt->execute();
    $stmt->close();

    echo "<p>✅ Score berhasil dimasukkan untuk student $studentID (exercise $exerciseID)</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/admin.css">
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
            <div class="content" id="dashboard"  style="display:none;">
                <h1>Dashboard</h1>
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
                                <?php foreach ($courseCategory as $category): ?>
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
                    </div>
                </div>
                
            </div>
            <div class="content"id="addCourse" >
                <h1>Exercise Attempt</h1>
                <div class="main">
                    <?php foreach ($attempts as $a): ?>
                        <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
                            <p><strong>Answer:</strong> <?= htmlspecialchars($a['answer']) ?></p>
                            <p><strong>Status:</strong> <?= $a['status'] ?></p>
                            <p><?= is_null($a['score']) ? 'Belum dinilai' : $a['score'] ?></p>
                            <form method="post">
                                <input type="hidden" name="studentID" value="<?= $a['studentID'] ?>">
                                <input type="hidden" name="exerciseID" value="<?= $a['exerciseID'] ?>">
                                <input type="hidden" name="sessionID" value="<?= $a['sessionID'] ?>">
                                <input type="hidden" name="courseID" value="<?= $a['courseID'] ?>">
                                <input type="number" name="score" min="0" max="100" step="0.1" required>
                                <button type="submit" name="submit_score">Submit Score</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="js/admin.js"></script>
</body>
</html>


<!-- 
<?php foreach ($attempts as $a): ?>
    <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
        <p><strong>Student ID:</strong> <?= $a['studentID'] ?></p>
        <p><strong>Exercise ID:</strong> <?= $a['exerciseID'] ?></p>
        <p><strong>Answer:</strong> <?= htmlspecialchars($a['answer']) ?></p>
        <p><strong>Status:</strong> <?= $a['status'] ?></p>
        <p><strong>Score Saat Ini:</strong> <?= is_null($a['score']) ? 'Belum dinilai' : $a['score'] ?></p>

        <form method="post">
            <input type="hidden" name="studentID" value="<?= $a['studentID'] ?>">
            <input type="hidden" name="exerciseID" value="<?= $a['exerciseID'] ?>">
            <input type="hidden" name="sessionID" value="<?= $a['sessionID'] ?>">
            <input type="hidden" name="courseID" value="<?= $a['courseID'] ?>">

            <label>Masukkan Score (0-10):</label><br>
            <input type="number" name="score" min="0" max="10" step="0.1" required>
            <button type="submit" name="submit_score">Submit Score</button>
        </form>
    </div>
<?php endforeach; ?> -->