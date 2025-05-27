<?php
session_start();
include 'api/connect.php';
include 'exploreDropdown.php';

$studentID = $_SESSION['studentID'];

$query = "SELECT 
              SUM(co.price) AS originalTotal,
              SUM(COALESCE(d.finalPrice, co.price)) AS discountedTotal
          FROM cart c
          JOIN course co ON c.courseID = co.courseID
          LEFT JOIN discount d ON co.courseID = d.courseID
          WHERE c.studentID = '$studentID'";
$result = mysqli_query($conn, $query);
$totals = mysqli_fetch_assoc($result);

// Hitung persentase potongan
$discountPercent = 0;
if ($totals['originalTotal'] > 0) {
    $discountPercent = (($totals['originalTotal'] - $totals['discountedTotal']) / $totals['originalTotal']) * 100;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_cart']) && $_POST['update_cart'] === "delete") {
    $courseID = mysqli_real_escape_string($conn, $_POST['courseID']);
    $deleteQuery = "DELETE FROM cart WHERE studentID = '$studentID' AND courseID = '$courseID'";
    mysqli_query($conn, $deleteQuery);  
    // Redirect agar mencegah resubmit form saat refresh
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

    $orderID = generateCustomID($conn, 'ORD', 'order', 'orderID');
    $insertOrder = mysqli_query($conn, "INSERT INTO `order` 
        (orderID, studentID, orderDate, paymentTypeID, paymentFee, tax, totalSave, totalPrice)
        VALUES 
        ('$orderID', '$studentID', NOW(), '$paymentTypeID', $paymentFee, '$tax', '$totalSave', '$totalPrice')
    ");

    $cartQuery = mysqli_query($conn, "SELECT c.courseID, 
                        COALESCE(d.finalPrice, co.price) AS price
                        FROM cart c
                        JOIN course co ON c.courseID = co.courseID
                        LEFT JOIN discount d ON co.courseID = d.courseID
                        WHERE c.studentID = '$studentID'");

    while ($item = mysqli_fetch_assoc($cartQuery)) {
        $orderDetailID = generateCustomID($conn, 'DTL', 'orderdetail', 'orderDetailID');
        $courseID = $item['courseID'];
        $price = $item['price'];

        // 1. Insert ke orderdetail
        mysqli_query($conn, "INSERT INTO orderdetail (orderDetailID, orderID, courseID, price)
            VALUES ('$orderDetailID', '$orderID', '$courseID', '$price')
        ");

        // 2. Insert ke enrolled
        mysqli_query($conn, "INSERT INTO enrolled (studentID, courseID, enrollmentDate)
            VALUES ('$studentID', '$courseID', NOW())
        ");

        // 3. Insert ke overallProgress
        mysqli_query($conn, "INSERT INTO overallProgress (studentID, courseID, progress, progressStatus)
            VALUES ('$studentID', '$courseID', 0, 'On Going')
        ");

        // 4. Insert ke learningProgress (semua session dalam course)
        $sessionQuery = mysqli_query($conn, "SELECT sessionID FROM `session` WHERE courseID = '$courseID'");
        while ($session = mysqli_fetch_assoc($sessionQuery)) {
            $sessionID = $session['sessionID'];
            mysqli_query($conn, "INSERT INTO learningProgress (studentID, sessionID, progressValue, sessionStatus)
                VALUES ('$studentID', '$sessionID', 0, 'On Going')
            ");
        }
    }

    // Cleanup
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
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=DM+Serif+Text:ital@0;1&family=Oswald:wght@200..700&family=Staatliches&display=swap" rel="stylesheet">
   <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet"> <link rel="icon" href="images/logo.png" type="image/png">
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
    
    <div class="container" id="container">
        <div class="title">
            <h1>Your Bag <p class="totalItems">(<?= htmlspecialchars($totalCart) ?> Courses)</p> </h1>
            
        </div>
        <div class="content">
            <div class="cart-content">
                <?php if (empty($cart_items)): ?>
                                    <div class="empty-cart">
                                        <img src="images/empty-cart.jpg">
                                        <p>Looks like you haven't add any courses into your bag</p>
                                        <a href="allCourses.php">Explore Courses</a>
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
                                            <h5>$<?= htmlspecialchars($cart['finalPrice'])?></h5>
                                            <i class="fa-solid fa-tag"></i>
                                        </div>
                                        <p>$<?= htmlspecialchars($cart['originalPrice']) ?></p>
                                    </div>
                                <?php else: ?>
                                    <div class="non-discount">
                                        <h5>$<?= htmlspecialchars($cart['originalPrice']) ?></h5>
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
                    <h5>$<?= htmlspecialchars($totals['discountedTotal']) ?></h5>
                    <p>$<?= htmlspecialchars($totals['originalTotal']) ?></p>   
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
                                        <p>| $<?= htmlspecialchars($payment['adminFee']) ?></p>
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
                                                <h5>$<?= htmlspecialchars($items['finalPrice'])?></h5>
                                                <i class="fa-solid fa-tag"></i>
                                            </div>
                                            <p>$<?= htmlspecialchars($items['originalPrice']) ?></p>
                                        </div>
                                        <?php else: ?>
                                        <div class="non-discount-checkout">
                                            <h5>$<?= htmlspecialchars($items['originalPrice']) ?></h5>
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
                            <p id="subtotal">$<?= number_format($totals['discountedTotal'], 2) ?></p>
                            <p id="paymentFee">$0.00</p>
                            <p id="tax">$0.00</p>
                            <p id="total">$<?= number_format($totals['discountedTotal'], 2) ?></p>
                        </div>
                    </div>
                    <div class="proceed-payment">
                        <form method="post">
                            <button type="submit" class="order-button" name="checkout">
                                PURCHASE <p id="total-button">$<?= number_format($totals['discountedTotal'], 2) ?></p>
                            </button>
                        </form>
                    </div>
                </div>
             </div>
        </div>
    </div>


    <script src="js/cart.js"></script>
</body>
</html>