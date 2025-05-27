<?php

$query = "SELECT 
            c.*,
            cc.courseCat,
            c.price,
            d.finalPrice,

            -- Total session & breakdown by type
            COUNT(DISTINCT s.sessionID) AS totalSession,
            SUM(CASE WHEN s.sessionType = 'Lesson' THEN 1 ELSE 0 END) AS totalLesson,
            SUM(CASE WHEN s.sessionType = 'Exercise' THEN 1 ELSE 0 END) AS totalExercise,
            SUM(CASE WHEN s.sessionType = 'Project' THEN 1 ELSE 0 END) AS totalProject,

            -- Total enrolled
            COUNT(DISTINCT e.studentID) AS totalEnrolled

        FROM course c
        JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
        LEFT JOIN discount d ON c.courseID = d.courseID
        LEFT JOIN `session` s ON c.courseID = s.courseID
        LEFT JOIN enrolled e ON c.courseID = e.courseID

        WHERE c.courseCatID = 'PL'
        GROUP BY c.courseID
        LIMIT 4";
$result = mysqli_query($conn, $query);
$coursePL = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT 
            c.*,
            cc.courseCat,
            c.price,
            d.finalPrice,

            -- Total session & breakdown by type
            COUNT(DISTINCT s.sessionID) AS totalSession,
            SUM(CASE WHEN s.sessionType = 'Lesson' THEN 1 ELSE 0 END) AS totalLesson,
            SUM(CASE WHEN s.sessionType = 'Exercise' THEN 1 ELSE 0 END) AS totalExercise,
            SUM(CASE WHEN s.sessionType = 'Project' THEN 1 ELSE 0 END) AS totalProject,

            -- Total enrolled
            COUNT(DISTINCT e.studentID) AS totalEnrolled

        FROM course c
        JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
        LEFT JOIN discount d ON c.courseID = d.courseID
        LEFT JOIN `session` s ON c.courseID = s.courseID
        LEFT JOIN enrolled e ON c.courseID = e.courseID

        WHERE c.courseCatID = 'AD'
        GROUP BY c.courseID
        LIMIT 4";
$result = mysqli_query($conn, $query);
$courseAD = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT 
            c.*,
            cc.courseCat,
            c.price,
            d.finalPrice,

            -- Total session & breakdown by type
            COUNT(DISTINCT s.sessionID) AS totalSession,
            SUM(CASE WHEN s.sessionType = 'Lesson' THEN 1 ELSE 0 END) AS totalLesson,
            SUM(CASE WHEN s.sessionType = 'Exercise' THEN 1 ELSE 0 END) AS totalExercise,
            SUM(CASE WHEN s.sessionType = 'Project' THEN 1 ELSE 0 END) AS totalProject,

            -- Total enrolled
            COUNT(DISTINCT e.studentID) AS totalEnrolled

        FROM course c
        JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
        LEFT JOIN discount d ON c.courseID = d.courseID
        LEFT JOIN `session` s ON c.courseID = s.courseID
        LEFT JOIN enrolled e ON c.courseID = e.courseID

        WHERE c.courseCatID = 'WD'
        GROUP BY c.courseID
        LIMIT 4";
$result = mysqli_query($conn, $query);
$courseWD = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT 
            c.*,
            cc.courseCat,
            c.price,
            d.finalPrice,

            -- Total session & breakdown by type
            COUNT(DISTINCT s.sessionID) AS totalSession,
            SUM(CASE WHEN s.sessionType = 'Lesson' THEN 1 ELSE 0 END) AS totalLesson,
            SUM(CASE WHEN s.sessionType = 'Exercise' THEN 1 ELSE 0 END) AS totalExercise,
            SUM(CASE WHEN s.sessionType = 'Project' THEN 1 ELSE 0 END) AS totalProject,

            -- Total enrolled
            COUNT(DISTINCT e.studentID) AS totalEnrolled

        FROM course c
        JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
        LEFT JOIN discount d ON c.courseID = d.courseID
        LEFT JOIN `session` s ON c.courseID = s.courseID
        LEFT JOIN enrolled e ON c.courseID = e.courseID

        WHERE c.courseCatID = 'VD'
        GROUP BY c.courseID
        LIMIT 4";
$result = mysqli_query($conn, $query);
$courseVD = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT 
            c.*,
            cc.courseCat,
            c.price,
            d.finalPrice,

            -- Total session & breakdown by type
            COUNT(DISTINCT s.sessionID) AS totalSession,
            SUM(CASE WHEN s.sessionType = 'Lesson' THEN 1 ELSE 0 END) AS totalLesson,
            SUM(CASE WHEN s.sessionType = 'Exercise' THEN 1 ELSE 0 END) AS totalExercise,
            SUM(CASE WHEN s.sessionType = 'Project' THEN 1 ELSE 0 END) AS totalProject,

            -- Total enrolled
            COUNT(DISTINCT e.studentID) AS totalEnrolled

        FROM course c
        JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
        LEFT JOIN discount d ON c.courseID = d.courseID
        LEFT JOIN `session` s ON c.courseID = s.courseID
        LEFT JOIN enrolled e ON c.courseID = e.courseID

        WHERE c.courseCatID = 'VE'
        GROUP BY c.courseID
        LIMIT 4";
$result = mysqli_query($conn, $query);
$courseVE = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT 
            c.*,
            cc.courseCat,
            c.price,
            d.finalPrice,

            -- Total session & breakdown by type
            COUNT(DISTINCT s.sessionID) AS totalSession,
            SUM(CASE WHEN s.sessionType = 'Lesson' THEN 1 ELSE 0 END) AS totalLesson,
            SUM(CASE WHEN s.sessionType = 'Exercise' THEN 1 ELSE 0 END) AS totalExercise,
            SUM(CASE WHEN s.sessionType = 'Project' THEN 1 ELSE 0 END) AS totalProject,

            -- Total enrolled
            COUNT(DISTINCT e.studentID) AS totalEnrolled

        FROM course c
        JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
        LEFT JOIN discount d ON c.courseID = d.courseID
        LEFT JOIN `session` s ON c.courseID = s.courseID
        LEFT JOIN enrolled e ON c.courseID = e.courseID

        WHERE c.courseCatID = 'DM'
        GROUP BY c.courseID
        LIMIT 4";
$result = mysqli_query($conn, $query);
$courseDM = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>