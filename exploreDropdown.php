<?php
include 'api/connect.php';

if (!isset($_SESSION['studentID'])) {
    header("Location: signin.php");
}

$studentID = $_SESSION['studentID'];
$query = "SELECT studentImage FROM student WHERE studentID = '$studentID'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$profileImage = !empty($row['studentImage']) ? 'uploads/profilePicture/' . $row['studentImage'] : 'images/empty-profile.png';


$categories = ['PL', 'AD', 'WD', 'VD', 'VE', 'DA'];
$courseResults = [];
$totalCourses = [];

foreach ($categories as $catID) {
    $query = "SELECT courseTitle ,courseID FROM course WHERE courseCatID = '$catID' LIMIT 5";
    $courseResults[$catID] = mysqli_query($conn, $query);
    
    $query = "SELECT COUNT(courseID) as totalCourse FROM course WHERE courseCatID = '$catID'";
    $result = mysqli_query($conn, $query);
    $totalCourses[$catID] = mysqli_fetch_assoc($result)['totalCourse'];
}

$query = "SELECT name, email FROM student WHERE studentID = '$studentID'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

$query = "SELECT COUNT(courseID) AS totalCart
                      FROM cart 
                      WHERE studentID = '$studentID'";
$result = mysqli_query($conn, $query);
$totalCart = mysqli_fetch_assoc($result)['totalCart'];
$query = "SELECT 
              SUM(co.price) AS originalTotal,
              SUM(
                  CASE 
                      WHEN d.discountPercent IS NOT NULL 
                      THEN co.price - (co.price * d.discountPercent / 100)
                      ELSE co.price 
                  END
                  ) AS discountedTotal
          FROM cart c
          JOIN course co ON c.courseID = co.courseID
          LEFT JOIN discount d ON co.courseID = d.courseID
          WHERE c.studentID = '$studentID'";
$result = mysqli_query($conn, $query);
$totals = mysqli_fetch_assoc($result);

$originalTotal = (float) $totals['originalTotal'];
$discountedTotal = (float) $totals['discountedTotal'];
$discountPercent = 0;

if ($originalTotal > 0) {
    $discountPercent = round((($originalTotal - $discountedTotal) / $originalTotal) * 100);
}

$totals['originalTotal'] = $originalTotal;
$totals['discountedTotal'] = $discountedTotal;
$totals['discountPercent'] = $discountPercent;

$categoryLabels = [
    'PL' => 'Programming Languages',
    'AD' => 'Application Development',
    'WD' => 'Website Development',
    'VD' => 'Visual Design',
    'VE' => 'Video Editing',
    'DA' => 'Data Analytics'
];

$query = "SELECT 
            c.courseID,
            co.price AS originalPrice,
            co.courseThumbnail,
            co.courseTitle,
            co.level,
            cc.courseCat,
            -- Hitung harga setelah diskon
            CASE 
                WHEN d.discountPercent IS NOT NULL 
                THEN co.price - (co.price * d.discountPercent / 100)
                ELSE co.price
            END AS finalPrice,

            -- Total session & breakdown by type
            COUNT(DISTINCT se.sessionID) AS totalSession,
            SUM(CASE WHEN se.sessionType = 'Lesson' THEN 1 ELSE 0 END) AS totalLesson,
            SUM(CASE WHEN se.sessionType = 'Exercise' THEN 1 ELSE 0 END) AS totalExercise,
            SUM(CASE WHEN se.sessionType = 'Project' THEN 1 ELSE 0 END) AS totalProject,

            -- Total enrolled
            COUNT(DISTINCT e.studentID) AS totalEnrolled

        FROM cart c
        JOIN student s ON c.studentID = s.studentID
        JOIN course co ON c.courseID = co.courseID
        JOIN coursecategory cc ON co.courseCatID = cc.courseCatID
        LEFT JOIN discount d ON co.courseID = d.courseID
        LEFT JOIN `session` se ON co.courseID = se.courseID
        LEFT JOIN enrolled e ON co.courseID = e.courseID

        WHERE c.studentID = '$studentID'
        GROUP BY c.courseID";
$result = mysqli_query($conn, $query);
$cart_items = mysqli_fetch_all($result, MYSQLI_ASSOC);


?>