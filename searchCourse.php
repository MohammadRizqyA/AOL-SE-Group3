<?php
include 'api/connect.php';

$keyword = trim($_GET['q'] ?? '');
$data = [];

if ($keyword !== '') {
    $stmt = $conn->prepare("SELECT courseID, courseThumbnail,courseTitle FROM course WHERE courseTitle LIKE CONCAT('%', ?, '%') LIMIT 10");
    $stmt->bind_param("s", $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}
header('Content-Type: application/json');
echo json_encode($data);

?>