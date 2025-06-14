<?php
session_start();
include 'api/connect.php';
include 'exploreDropdown.php';
include 'functions.php';

$studentID = $_SESSION['studentID'];






if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_cart']) && $_POST['update_cart'] === "delete") {
    $courseID = mysqli_real_escape_string($conn, $_POST['courseID']);
    $deleteQuery = "DELETE FROM cart WHERE studentID = '$studentID' AND courseID = '$courseID'";
    mysqli_query($conn, $deleteQuery);  
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

$query = "SELECT * FROM payment";
$result = mysqli_query($conn, $query);
$paymentMethods = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_POST['checkout'])) {
    $studentID = mysqli_real_escape_string($conn, $studentID);

    $tempQuery = mysqli_query($conn, "SELECT * FROM temporarycheckout WHERE studentID = '$studentID'");
    $tempData = mysqli_fetch_assoc($tempQuery);

    if (!$tempData) {
        echo "<script>alert('Data checkout tidak ditemukan.');</script>";
        return;
    }

    $subtotal = (float)$tempData['subtotal'];
    $tax = (float)$tempData['tax'];
    $paymentTypeID = mysqli_real_escape_string($conn, $tempData['paymentTypeID']);
    $paymentFee = (float)$tempData['paymentFee'];
    $totalPrice = (float)$tempData['total'];
    $totalSave = $totals['originalTotal'] - $totals['discountedTotal'];

    $orderID = generateCustomID2($conn, 'ORD', 'order', 'orderID');
    $insertOrder = mysqli_query($conn, "INSERT INTO `order` 
        (orderID, studentID, orderDate, paymentTypeID)
        VALUES 
        ('$orderID', '$studentID', NOW(), '$paymentTypeID')
    ");

    $cartQuery = mysqli_query($conn, 
                    "SELECT 
                        c.courseID
                        FROM cart c
                        JOIN course co ON c.courseID = co.courseID
                        LEFT JOIN discount d ON co.courseID = d.courseID
                        WHERE c.studentID = '$studentID'");

    while ($item = mysqli_fetch_assoc($cartQuery)) {
        $orderDetailID = generateCustomID2($conn, 'DTL', 'orderdetail', 'orderDetailID');
        $courseID = $item['courseID'];
        $price = $item['price'];

        mysqli_query($conn, "INSERT INTO orderdetail (orderDetailID, orderID, courseID)
            VALUES ('$orderDetailID', '$orderID', '$courseID')
        ");

        mysqli_query($conn, "INSERT INTO enrolled (studentID, courseID, enrollmentDate)
            VALUES ('$studentID', '$courseID', NOW())
        ");

        mysqli_query($conn, "INSERT INTO overallprogress (studentID, courseID, progress, progressStatus)
            VALUES ('$studentID', '$courseID', 0, 'On Going')
        ");

        $sessionQuery = mysqli_query($conn, "SELECT sessionID FROM `session` WHERE courseID = '$courseID'");
        while ($session = mysqli_fetch_assoc($sessionQuery)) {
            $sessionID = $session['sessionID'];
            mysqli_query($conn, "INSERT INTO learningprogress (studentID, sessionID, progressValue, sessionStatus)
                VALUES ('$studentID', '$sessionID', 0, 'On Going')
            ");
        }
    }

    mysqli_query($conn, "DELETE FROM cart WHERE studentID = '$studentID'");
    mysqli_query($conn, "DELETE FROM temporarycheckout WHERE studentID = '$studentID'");

    header("Location: success.php?orderID=$orderID");
    exit;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/cart.css">
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
    <div class="con">
        <div class="container" id="container">
            <div class="title">
                <h1>Your Bag <p class="totalItems">(<?= htmlspecialchars($totalCart) ?> Courses)</p> </h1>
                
            </div>
            <div class="content">
                <div class="cart-content">
                    <?php if (empty($cart_items)): ?>
                        <div class="empty-cart-container">
                            <div class="empty-cart">
                                <img src="images/empty-cart.jpg">
                                <p>Looks like you haven't add any courses into your bag</p>
                                <a href="homepage.php">Continue Searching</a>
                            </div>
                        </div>
                    <?php else: ?>
                    <div class="items">
                        <?php foreach ($cart_items as $cart): ?>  
                            <a href="course.php?courseID=<?= htmlspecialchars($cart['courseID']) ?>" class="item">
                                <img src="uploads/thumbnails/<?= htmlspecialchars($cart['courseThumbnail']) ?>">
                                <div class="course-attribute">                                                                    
                                    <h1><?= htmlspecialchars($cart['courseTitle']) ?></h1>
                                    <div class="sub-attribute">
                                        <h2><?= htmlspecialchars($cart['courseCat']) ?></h2> 
                                        <p><?= htmlspecialchars($cart['level']) ?></p> 
                                    </div>
                                    <div class="module-lesson">
                                        <p><?= htmlspecialchars($cart['totalLesson']) ?> Lesson</p> 
                                        <p><?= htmlspecialchars($cart['totalExercise']) ?> Exercise</p> 
                                        <p><?= htmlspecialchars($cart['totalProject']) ?> Project</p> 
                                    </div>
                                    <?php if (!empty($cart['finalPrice'])): ?>
                                        <div class="discount">
                                            <div class="discounted">
                                                <h5><span>IDR</span> <?= number_format($cart['finalPrice'], 0, ',', '.')?></h5>
                                                <i class="fa-solid fa-tag"></i>
                                            </div>
                                            <p><span>IDR</span> <?= number_format($cart['originalPrice'], 0, ',', '.') ?></p>
                                        </div>
                                    <?php else: ?>
                                        <div class="non-discount">
                                            <h5>$<?= number_format($cart['originalPrice'], 0, ',', '.') ?></h5>
                                        </div>
                                    <?php endif; ?>
                                </div> 
                                <form method="POST">
                                    <input type="hidden" name="courseID" value="<?= $cart['courseID'] ?>">
                                    <button type="submit" name="update_cart" value="delete">Remove Course</button>
                                </form>
                            </a> 
                        
                        <?php endforeach; ?>
                    </div>
                    
                </div>   

                <div class="summary">
                    <h2>Total</h2>
                    <div class="summary-price">
                        <h5><span>IDR</span> <?= number_format($totals['discountedTotal'], 0, ',', '.') ?></h5>
                        <p><span>IDR</span> <?= number_format($totals['originalTotal'], 0, ',', '.') ?></p>   
                    </div>
                    
                        <?php if ($discountPercent > 0): ?>
                        <div class="save">
                            <p>you save <h5><?= round($discountPercent, 1)?>%</h5> on this purchase</p>
                        </div>
                        <?php endif; ?>
                        
                    <button class="btn" id="checkout-button">Checkout</button> 
                    <p class="tax">Tax will charged 2% of total transacation</p>
                    
                </div>
                <?php endif; ?>
            </div>          
        </div>

        <div class="checkout" id="checkout" style="display:none;">
            <div class="payment-button" id="payment-button"><i class="fa-solid fa-angle-left"></i>Checkout</div>
            <div class="checkout-content">  
        
                <div class="checkout-wrapper">
                    <div class="left">
                        <form method="post">
                            <div class="wrap-details">
                                <div class="payment-method" id="checkoutForm">
                                    <h2>PAYMENT METHODS</h2>
                                    <div class="payments">
                                        <?php foreach ($paymentMethods as $payment): ?>
                                        <label>
                                        <input type="radio" name="paymentTypeID" value="<?= htmlspecialchars($payment['paymentTypeID'])?>" data-fee="<?= htmlspecialchars($payment['adminFee']) ?>" required>
                                        <div class="payment-wrap">
                                            <img src="images/payment/<?= htmlspecialchars($payment['paymentIcon']) ?>" width="20px">
                                            <span><?= htmlspecialchars($payment['paymentType']) ?></span>
                                            <p>| IDR <?= number_format($payment['adminFee'], 0, ',', '.') ?></p>
                                        </div>
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>           
                            </div>
                        </form>
                    </div>
                    <div class="right">
                        <h2>YOUR ORDER</h2>
                        <div class="course-checkout">
                            <?php foreach($cart_items as $items): ?>
                                <div class="course-wrapper">
                                    <img src="uploads/thumbnails/<?= htmlspecialchars($items['courseThumbnail'])?>" >
                                    <div class="course-details">
                                        <div class="first">
                                            <h3><?= htmlspecialchars($items['courseTitle'])?></h3>
                                            <p>ID <?= htmlspecialchars($items['courseID'])?></p>
                                        </div>
                                    <div class="second">
                                            <?php if (!empty($items['finalPrice'])): ?>
                                            <div class="discount-checkout">
                                                <div class="discounted-checkout">
                                                    <h5><span>IDR</span> <?= number_format($items['finalPrice'], 0, ',', '.')?></h5>
                                                    <i class="fa-solid fa-tag"></i>
                                                </div>
                                                <p><span>IDR</span> <?= number_format($items['originalPrice'], 0, ',', '.') ?></p>
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5><span>IDR</span> <?= number_format($items['originalPrice'], 0, ',', '.') ?></h5>
                                            </div>
                                            <?php endif; ?>
                                    </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>          
                        </div>
                        <div class="summary-checkout">
                            <div class="summary-label">
                                <h3>Subtotal</h3>
                                <h3>Payment Fee</h3>
                                <h3>Tax <p>(2%)</p></h3>
                                <h3>Total</h3>
                            </div>
                            <div class="summary-price-checkout">
                                <p id="subtotal">IDR <?= number_format($totals['discountedTotal'], 0, ',', '.')?></p>
                                <p id="paymentFee">IDR 0.00</p>
                                <p id="tax">IDR 0.00</p>
                                <p id="total">IDR <?= number_format($totals['discountedTotal'], 0, ',', '.') ?></p>
                            </div>
                        </div>
                        <div class="proceed-payment">
                            <form method="post">
                                <button type="submit" class="order-button" name="checkout">
                                    PURCHASE <p id="total-button">$<?= number_format($totals['discountedTotal'], 0, ',', '.') ?></p>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>                                 
        <div class="footer" id="footer">
            <h5>Founded in 2025, Academia Plus is committed to delivering high-quality online learning experiences, helping learners grow through expert-led content and a supportive digital platform.</h5>
            <div class="footer-content">
                <div class="social-media">
                    <img src="images/social_media/linkedin.png" alt="">
                    <img src="images/social_media/x.png" alt="">
                    <img src="images/social_media/facebook.png" alt="">
                    <img src="images/social_media/instagram.png" alt="">
                </div>
                <div class="logo-footer">
                    <img src="images/logos/logo-black.png" alt="">
                    <p>© 2025 Academia Plus, Inc.</p>
                </div>
                <div class="download">
                    <img src="images/social_media/appstore.png" alt="" class="appstore">
                    <img src="images/social_media/playstore.png" alt="" class="playstore">
                </div>
            </div>
        </div>
    


    <script src="js/cart.js"></script>
    <script src="js/search.js"></script>
</body>
</html>