<?php
session_start();
include 'api/connect.php';

header("Content-Type: application/json");

$studentID = $_SESSION['studentID'];
$paymentTypeID = $_POST['paymentTypeID'] ?? '';
$paymentFee = floatval($_POST['paymentFee'] ?? 0);

$query = "SELECT 
        SUM(
            CASE 
                WHEN d.discountPercent IS NOT NULL 
                THEN co.price - (co.price * d.discountPercent / 100)
                ELSE co.price
            END
        ) AS subtotal
    FROM cart c
    JOIN course co ON c.courseID = co.courseID
    LEFT JOIN discount d ON co.courseID = d.courseID
    WHERE c.studentID = '$studentID'";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$subtotal = floatval($row['subtotal'] ?? 0);

$tax = $subtotal * 0.02;
$total = $subtotal + $paymentFee + $tax;

$query = "
    REPLACE INTO temporarycheckout (studentID, subtotal, paymentTypeID, paymentFee, tax, total)
    VALUES (?, ?, ?, ?, ?, ?)
";
$stmt = $conn->prepare($query);
$stmt->bind_param("sdsddd", $studentID, $subtotal, $paymentTypeID, $paymentFee, $tax, $total);
$success = $stmt->execute();


if ($success) {
    echo json_encode([
        "status" => "success",
        "subtotal" => $subtotal,
        "paymentFee" => $paymentFee,
        "tax" => $tax,
        "total" => $total
    ]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
