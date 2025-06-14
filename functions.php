<?php

function generateCustomID1($conn, $prefix, $table, $idColumn) {
    $query = "SELECT $idColumn FROM $table WHERE $idColumn LIKE '$prefix%' ORDER BY $idColumn DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastNumber = (int)substr($row[$idColumn], strlen($prefix)); 
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    $customID = $prefix . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

    return $customID;
}

function generateCustomID2($conn, $prefix, $table, $idColumn) {
    $query = "SELECT $idColumn FROM `$table` WHERE $idColumn LIKE '$prefix%' ORDER BY $idColumn DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $prefixLength = strlen($prefix);
        $lastNumber = (int)substr($row[$idColumn], $prefixLength);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    $customID = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    return $customID;
}

function formatDate($tanggal) {
    $monthNames = [
        '01' => 'January', '02' => 'February', '03' => 'March',
        '04' => 'April',   '05' => 'May',      '06' => 'June',
        '07' => 'July',    '08' => 'August',   '09' => 'September',
        '10' => 'October', '11' => 'November', '12' => 'December'
    ];
    $timestamp = strtotime($tanggal);
    $day   = date('d', $timestamp);
    $month = $monthNames[date('m', $timestamp)];
    $year  = date('Y', $timestamp);
    return $day . ' ' . $month . ' ' . $year;
}



?>