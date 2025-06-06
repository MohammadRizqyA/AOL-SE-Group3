<?php
session_start();
include 'api/connect.php';
include 'exploreDropdown.php';

if (!isset($_SESSION['studentID'])) {
    die("Anda harus login terlebih dahulu.");
}
$studentID = $_SESSION['studentID'];


function formatTanggalIndo($tanggal) {
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

$filter = $_GET['filter_months'] ?? 'all';
$filterOptions = [
    'all' => 'ALL TIME',
    '1m' => 'LAST 1 MONTH',
    '3m' => 'LAST 3 MONTH',
    '6m' => 'LAST 6 MONTH',
    '1y' => 'LAST 1 YEAR'
];

$intervalSQL = '';
switch ($filter) {
    case '1m': $intervalSQL = '1 MONTH'; break;
    case '3m': $intervalSQL = '3 MONTH'; break;
    case '6m': $intervalSQL = '6 MONTH'; break;
    case '1y': $intervalSQL = '1 YEAR'; break;
}

if ($intervalSQL) {
    $query = "
        SELECT o.orderID, o.orderDate
        FROM `order` o
        WHERE o.studentID = ?
        AND o.orderDate >= DATE_SUB(NOW(), INTERVAL $intervalSQL)
        ORDER BY o.orderDate DESC
    ";
} else {
    $query = "
        SELECT o.orderID, o.orderDate
        FROM `order` o
        WHERE o.studentID = ?
        ORDER BY o.orderDate DESC
    ";
}
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $studentID);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orderID = $row['orderID'];
    $orderDate = $row['orderDate'];

    $courseQuery = "
        SELECT c.courseThumbnail
        FROM course c
        JOIN orderdetail od ON c.courseID = od.courseID
        WHERE od.orderID = ?
    ";
    $courseStmt = $conn->prepare($courseQuery);
    $courseStmt->bind_param("s", $orderID);
    $courseStmt->execute();
    $courseResult = $courseStmt->get_result();

    $courseImages = [];
    while ($courseRow = $courseResult->fetch_assoc()) {
        $courseImages[] = $courseRow['courseThumbnail'];
    }

    $orders[] = [
        'orderDate'     => $orderDate,
        'courseImages'  => $courseImages,
        'orderID'       => $orderID
    ];
}




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/orderHistory.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=DM+Serif+Text:ital@0;1&family=Oswald:wght@200..700&family=Staatliches&display=swap" rel="stylesheet">
    <link rel="icon" href="images/logo.png" type="image/png">
    <title>Academia Plus</title>
</head>
<body>
    <div class="navbar" id="header">
        <a href="homepage.php" class="logo"><img src="images/fullLogo.png"></a>
        <div class="courses-wrapper">
            <div class="courses">
                <p>Explore Courses</p>
                <i class="fa-solid fa-angle-down"></i>
            </div>
            <div class="course-dropdown">
                <div class="dropdown">
                    <div class="categories-wrapper">
                        <?php foreach ($categoryLabels as $catID => $label): ?>
                        <div class="categories">
                            <a href="category.php?courseCatID=<?= $catID ?>"><?= $label ?></a>
                            <div class="course-list-wrapper">
                                <?php foreach ($courseResults[$catID] as $course): ?>
                                <div class="course-list">
                                    <a href="course.php?courseID=<?= htmlspecialchars($course['courseID']) ?>">
                                        <p><?= htmlspecialchars($course['courseTitle']) ?></p>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                                <div class="view-more">
                                    <a href="category.php?courseCatID=<?= $catID ?>">View <?= $totalCourses[$catID] ?>+ more...</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="search">
            <form method="get">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input  type="text" name="searchOnly" placeholder="Want to learn something?" value="<?= isset($_GET['searchOnly']) 
                ? htmlspecialchars($_GET['searchOnly']) : '' ?>" autocomplete="off">
            </form> 
        </div>

        <div class="right-navbar">
            <a href="progress.php" class="progress-navbar">Learning Progress</a>
            <div class="right-navbar-group">
                <div class="cart-dropdown">
                    <a href="cart.php"><img src="images/bag.png"></a>
                    <div class="cart-wrapper">
                        <div class="cart-drop">
                            <div class="cart-header">
                                <h1>My Bag <p>(<?php echo $totalCart; ?>)</p></h1>
                                <a href="cart.php">View Cart</a>
                            </div>
                            <div class="cart-items">
                                <?php if (empty($cart_items)): ?>
                                    <div class="empty-cart-drop">
                                        <img src="images/empty-cart.jpg">
                                        <p>Your bag is still empty</p>
                                        <a href=""></a>
                                    </div>
                                <?php else: ?>
                                    <div class="course-item">
                                        <?php foreach ($cart_items as $cart): ?>  
                                            <div class="image-name">
                                                <img src="uploads/thumbnails/<?= htmlspecialchars($cart['courseThumbnail']) ?>">
                                                <div class="item-wrapper">                                                                        
                                                    <h2><?= htmlspecialchars(mb_strimwidth($cart['courseTitle'], 0, 35, "...")) ?></h2>
                                                    <h1><?= htmlspecialchars($cart['courseCat']) ?></h1> 
                                                     <?php if (!empty($cart['finalPrice'])): ?>
                                                        <div class="discounted">
                    
                                                            <span>$<?= htmlspecialchars($cart['finalPrice']) ?></span>
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="price">$<?= htmlspecialchars($cart['originalPrice']) ?></p>
                                                    <?php endif; ?>
                                                </div> 
                                            </div> 
                                        <?php endforeach; ?>
                                        <div class="total-price">
                                            <p>Total: $<?= htmlspecialchars($totals['discountedTotal']) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="notification.php"><img src="images/notification.png"></a>

                <div class="profile-dropdown">
                    
                    <img src="<?= htmlspecialchars($profileImage);  ?>" class="nav-profile-img">
                    <div class="profile-wrapper">
                        <div class="profile-drop">
                            <div class="student-info">
                                <h1> <?= htmlspecialchars($student['name']) ?> </h1>
                                <h2><?= htmlspecialchars($student['email']) ?></a></h2>
                            </div>
                            <div class="drop-list">
                                <a href="profile.php"><p>Profile</p></a>
                                <a href="purchases.php"><p>My Purchases</p></a>
                                <a href="settings.php"><p>Settings</p></a>
                                <a href="help.php"><p>Help Center</p></a>
                                <a href="api/logout.php"><p>Log Out</p></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div> 
    <div class="content">
        <div class="left-content">
            <a href="homepage.php"><i class="fa-solid fa-angle-left"></i>Homepage</a>
            <div class="text">   
                <i class="fa-regular fa-clock"></i>
                <h1>ORDER HISTORY</h1>
            </div>
            <form method="GET">
                <select name="filter_months" onchange="this.form.submit()">
                    <?php foreach ($filterOptions as $key => $label): ?>
                        <option value="<?= $key ?>" <?= ($filter == $key) ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
            
        </div>

        <div class="right-content">
            <div class="order-history">
                <?php foreach ($orders as $order): ?>
                    <?php 
                        $formattedDate = formatTanggalIndo($order['orderDate']);
                        $totalCourses = count($order['courseImages']);
                    ?>
                    <div class="order">
                        <div class="order-info">
                            <h3>Ordered <?= $formattedDate ?></h3> 
                            <h3>ID: <?= htmlspecialchars($order['orderID']) ?></h3> 
                            <h3><?= $totalCourses ?> Course<?= $totalCourses > 1 ? 's' : '' ?></h3>
                        </div>
                        <div class="product-images">
                            <?php 
                                $images = $order['courseImages'];
                                foreach (array_slice($images, 0, 3) as $index => $img): ?>
                                    <div class="wrap">
                                        <img  src="uploads/thumbnails/<?= htmlspecialchars($img) ?>" alt="Course Thumbnail">
                                        <?php if ($index == 2 && count($images) > 3): ?>
                                            <div class="more-image">
                                                +<?= count($images) - 3 ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                            <?php endforeach; ?>
                            <div class="buttons">
                                <a href="orderDetail.php?orderID=<?= htmlspecialchars($order['orderID']) ?>">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
                                
</body>
</html>