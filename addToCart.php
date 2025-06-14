<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add-to-cart'])) {
    $courseID = trim($_POST['courseID'] ?? '');

    $checkEnroll = $conn->prepare("SELECT * FROM enrolled WHERE studentID = ? AND courseID = ?");
    $checkEnroll->bind_param("ss", $studentID, $courseID);
    $checkEnroll->execute();
    $resultEnroll = $checkEnroll->get_result();

    if ($resultEnroll->num_rows > 0) {
        $_SESSION['popupMessage'] = 'You are already enrolled this course, please choose another one';
        header("Location: homepage.php");
        exit;
    }
    $checkEnroll->close();

    $stmt = $conn->prepare("SELECT * FROM cart WHERE studentID = ? AND courseID = ?");
    $stmt->bind_param("ss", $studentID, $courseID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['popupMessage'] = 'Course is already on the bag!';
        header("Location: homepage.php");
        exit;
    }
    $stmt->close();

    $priceQuery = "SELECT SUM(c.price * d.discountPercent ) AS finalPrice
                   FROM course c
                   LEFT JOIN discount d ON c.courseID = d.courseID
                   WHERE c.courseID = ?";
    $stmtPrice = $conn->prepare($priceQuery);
    $stmtPrice->bind_param("s", $courseID);
    $stmtPrice->execute();
    $res = $stmtPrice->get_result();

    if ($row = $res->fetch_assoc()) {
        $finalPrice = $row['finalPrice'];
        $insertStmt = $conn->prepare("INSERT INTO cart (studentID, courseID) VALUES (?, ?)");
        $insertStmt->bind_param("ss", $studentID, $courseID);
        $insertStmt->execute();
        $insertStmt->close();

        $_SESSION['popupMessage'] = 'Successfuly added course into your bag';
        header("Location: homepage.php");
        exit;
    }

    $stmtPrice->close();
    $conn->close();
    header("Location: homepage.php");
    exit;
}
?>