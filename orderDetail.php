<?php
session_start();
include 'api/connect.php';
include 'exploreDropdown.php';
include 'functions.php';

if (!isset($_SESSION['studentID'])) {
    header("Location: signin.php");
}
$studentID = $_SESSION['studentID'];

if (!isset($_GET['orderID']) || empty($_GET['orderID'])) {
    die("No order ID provided.");
}
$orderID = mysqli_real_escape_string($conn, $_GET['orderID']);

$query = "SELECT 
        o.*,
        od.*,
        c.*,
        cc.*,
        p.*,
        IFNULL(d.discountPercent, 0) AS discountPercent,
        ROUND(c.price * IFNULL(d.discountPercent, 0) / 100) AS courseDiscount,
        (c.price - ROUND(c.price * IFNULL(d.discountPercent, 0) / 100)) AS discountedPrice,
        os.totalSave,
        os.totalAfterDiscount,
        os.tax,
        (os.totalAfterDiscount + os.tax + p.adminFee) AS totalPrice

    FROM `order` o
    JOIN orderdetail od ON o.orderID = od.orderID
    JOIN payment p ON o.paymentTypeID = p.paymentTypeID
    JOIN course c ON od.courseID = c.courseID
    LEFT JOIN discount d ON c.courseID = d.courseID
    JOIN coursecategory cc ON c.courseCatID = cc.courseCatID

    JOIN (
        SELECT 
            od.orderID,
            SUM(ROUND(c.price * IFNULL(d.discountPercent, 0) / 100)) AS totalSave,
            SUM(c.price - ROUND(c.price * IFNULL(d.discountPercent, 0) / 100)) AS totalAfterDiscount,
            ROUND(SUM(c.price - ROUND(c.price * IFNULL(d.discountPercent, 0) / 100)) * 0.02) AS tax
        FROM orderdetail od
        JOIN course c ON od.courseID = c.courseID
        LEFT JOIN discount d ON c.courseID = d.courseID
        GROUP BY od.orderID
    ) AS os ON os.orderID = o.orderID

    WHERE o.orderID = '$orderID';";

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Afacad:ital,wght@0,400..700;1,400..700&family=Bebas+Neue&family=Gabarito:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="icon" href="images/logos/logo.png" type="image/png">
    <title>Academia Plus</title>
</head>
<body>
    <div class="navbar" id="header">
        <a href="homepage.php" class="logo"><img src="images/logos/fullLogo.png"></a>
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
            <form method="get" autocomplete="off">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchOnly" name="searchOnly" placeholder="Want to learn something?" value="<?= isset($_GET['searchOnly']) ? htmlspecialchars($_GET['searchOnly']) : '' ?>">
                <div id="searchDropdown" class="search-dropdown" style="display:none;"></div>
            </form>
        </div>

        <div class="right-navbar">
            <a href="learningProgress.php?view=courses" class="progress-navbar">Learning Dashboard</a>
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
                    
                                                            <span>IDR <?= number_format($cart['finalPrice'], 0, ',', '.') ?></span>
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="price">IDR <?= number_format($cart['originalPrice'], 0, ',', '.') ?></p>
                                                    <?php endif; ?>
                                                </div> 
                                            </div> 
                                        <?php endforeach; ?>
                                        <div class="total-price">
                                            <p><span>IDR</span> <?= number_format($totals['discountedTotal'], 0, ',', '.') ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="notification.php" class="notif"><img src="images/notification.png"></a>

                <div class="profile-dropdown">
                    
                    <img src="<?= htmlspecialchars($profileImage);  ?>" class="nav-profile-img">
                    <div class="profile-wrapper">
                        <div class="profile-drop">
                            <div class="student-info">
                                <h1> <?= htmlspecialchars($student['name']) ?> </h1>
                                <h2><?= htmlspecialchars($student['email']) ?></a></h2>
                            </div>
                            <div class="drop-list">
                                <a href="learningProgress.php?view=profile"><p>Profile</p></a> 
                                <a href="learningProgress.php?view=courses"><p>My Courses</p></a>
                                <a href="orderHistory.php"><p>Order History</p></a>
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
                <h3><span>IDR</span> <?= number_format($orderDetail[0]['totalPrice'], 0, ',', '.') ?></h3>
            </div>
            <div class="detail-top">
                <p>TAX</p>
                <h3><span>IDR</span> <?= number_format($orderDetail[0]['tax'], 0, ',', '.') ?></h3>
            </div>
            <div class="detail-top">
                <div class="save">
                    <p>TOTAL SAVE</p>
                    <h3><span>IDR</span> <?= number_format($orderDetail[0]['totalSave'], 0, ',', '.') ?></h3>
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
                            <?php if (!empty($detail['discountedPrice'])): ?>
                                <div class="discounted-item">
                                    <h4><span>IDR</span> <?= number_format($detail['discountedPrice'], 0, ',', '.') ?></h4>
                                    <p><span>IDR</span> <?= number_format($detail['price'], 0, ',', ',') ?></p>
                                </div>
                            <?php else: ?>
                                <div class="non-discount-item">
                                    <h4><span>IDR</span> <?= number_format($detail['price'], 0, ',', '.') ?></h4>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>                             
    <script src="js/search.js"></script>
</body>
</html>