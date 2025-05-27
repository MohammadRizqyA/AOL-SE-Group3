<?php
session_start();
include 'api/connect.php';
include 'exploreDropdown.php';

if (!isset($_SESSION['studentID'])) {
    die("Anda harus login terlebih dahulu.");
}
$studentID = $_SESSION['studentID'];

if (!isset($_GET['orderID']) || empty($_GET['orderID'])) {
    die("No order ID provided.");
}
$orderID = mysqli_real_escape_string($conn, $_GET['orderID']);


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



$query = "SELECT o.orderID, o.orderDate, o.tax, o.totalPrice, o.totalSave,
                 p.paymentType, p.paymentIcon,
                 c.courseID, c.courseTitle, c.courseThumbnail, c.level,
                 od.price,od.orderDetailID, c.courseCatID, cc.courseCat
          FROM `order` o
          JOIN orderdetail od ON o.orderID = od.orderID
          JOIN payment p ON o.paymentTypeID = p.paymentTypeID
          JOIN course c ON od.courseID = c.courseID
          JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
          WHERE o.orderID = '$orderID'";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    die("No data found for this order.");
}
$orderDetail = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/orderDetail.css">
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
    <div class="orderDetail">
        <div class="header-detail">
            <a href="orderHistory.php"><i class="fa-solid fa-angle-left"></i>Order Detail</a>
            <h2>ID <?= $orderDetail[0]['orderID'] ?></h2> 
            <div class="detail-top">
            <p>PAYMENT METHOD</p>
            <div class="payment">
                <img src="images/payment/<?= $orderDetail[0]['paymentIcon'] ?>">
                <h3><?= $orderDetail[0]['paymentType'] ?></h3>
            </div>
            </div>
            <div class="detail-top">
                <p>TOTAL PRICE</p>
                <h3>$<?= $orderDetail[0]['totalPrice'] ?></h3>
            </div>
            <div class="detail-top">
                <p>TAX</p>
                <h3>$<?= $orderDetail[0]['tax']?></h3>
            </div>
            <div class="detail-top">
                <div class="save">
                    <p>TOTAL SAVE</p>
                    <h3>$<?= $orderDetail[0]['totalSave']?></h3>
                </div>
            </div>
            <div class="detail-top">
                <p>ORDERED ON</p>
                <h3><?= $orderDetail[0]['orderDate'] ?></h3>
            </div>                   
        </div>
        <div class="items-content">
            <div class="detail-items">
                <?php foreach ($orderDetail as $detail): ?>
                    <div class="detail-items-wrapper">
                        
                        <img src="uploads/thumbnails/<?= htmlspecialchars($detail['courseThumbnail']) ?>" >
                        <div class="details">
                            <h2><?= htmlspecialchars($detail['courseTitle']) ?></h2>
                            <div class="id-level">
                                <h3><?= htmlspecialchars($detail['courseID']) ?></h3>
                                <p><?= htmlspecialchars($detail['courseCat']) ?></p>
                                <h3><?= htmlspecialchars($detail['level']) ?></h3>
                            </div>
                            <h4>$<?= htmlspecialchars($detail['price']) ?></h4>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>                             

</body>
</html>