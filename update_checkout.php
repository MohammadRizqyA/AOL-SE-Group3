<?php
session_start();
include 'api/connect.php';

header("Content-Type: application/json");

$studentID = $_SESSION['studentID'];

$paymentTypeID = $_POST['paymentTypeID'] ?? '';
$paymentFee = floatval($_POST['paymentFee'] ?? 0);

// Ambil subtotal
$query = "SELECT SUM(COALESCE(d.finalPrice, co.price)) AS subtotal
          FROM cart c
          JOIN course co ON c.courseID = co.courseID
          LEFT JOIN discount d ON co.courseID = d.courseID
          WHERE c.studentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $studentID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$subtotal = floatval($row['subtotal'] ?? 0);

$tax = $subtotal * 0.02;
$total = $subtotal + $paymentFee + $tax;

// REPLACE data di tabel temporarycheckout
$query = "REPLACE INTO temporarycheckout (studentID, subtotal,paymentTypeID, paymentFee, tax, total)
          VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sdsddd", $studentID, $subtotal,$paymentTypeID, $paymentFee, $tax, $total);
$success = $stmt->execute();

if ($success) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
