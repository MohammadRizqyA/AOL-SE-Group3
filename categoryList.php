<?php

$query = "SELECT 
            c.*,
            cc.courseCat,
            c.courseThumbnail,
            c.price,
            d.discountPercent,
            ROUND(AVG(cr.rating), 1) AS rating,
            (
                SELECT COUNT(*) FROM session s WHERE s.courseID = c.courseID AND s.sessionType = 'Lesson'
            ) AS totalLesson,
            (
                SELECT COUNT(*) FROM session s WHERE s.courseID = c.courseID AND s.sessionType = 'Exercise'
            ) AS totalExercise,
            (
                SELECT COUNT(*) FROM session s WHERE s.courseID = c.courseID AND s.sessionType = 'Project'
            ) AS totalProject,
            (
                SELECT COUNT(DISTINCT e.studentID) FROM enrolled e WHERE e.courseID = c.courseID
            ) AS totalEnrolled,
            (
                SELECT COUNT(DISTINCT cr2.studentID) FROM coursereview cr2 WHERE cr2.courseID = c.courseID
            ) AS totalRatingStudent
        FROM course c
        JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
        LEFT JOIN discount d ON c.courseID = d.courseID
        LEFT JOIN coursereview cr ON cr.courseID = c.courseID
        GROUP BY c.courseID
        ORDER BY c.courseCatID, c.courseID";
$result = mysqli_query($conn, $query);
$allCourses = mysqli_fetch_all($result, MYSQLI_ASSOC);

$courseByCat = [];
foreach ($allCourses as $row) {
    $discount = $row['discountPercent'];
    $price = $row['price'];
    $row['finalPrice'] = ($discount > 0) ? $price - ($price * $discount / 100) : null;
    $catID = $row['courseCatID'];
    $courseByCat[$catID][] = $row;
}

$coursePL = array_slice($courseByCat['PL'] ?? [], 0, 4);
$courseAD = array_slice($courseByCat['AD'] ?? [], 0, 4);
$courseWD = array_slice($courseByCat['WD'] ?? [], 0, 4);
$courseVD = array_slice($courseByCat['VD'] ?? [], 0, 4);
$courseDA = array_slice($courseByCat['DA'] ?? [], 0, 4);
$courseVE = array_slice($courseByCat['VE'] ?? [], 0, 4);

?>