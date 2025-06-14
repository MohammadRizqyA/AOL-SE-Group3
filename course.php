<?php
session_start();
include 'api/connect.php';
include 'exploreDropdown.php';
include 'functions.php';

if (!isset($_SESSION['studentID'])) {
    header("Location: signin.php");
}
$studentID = $_SESSION['studentID'];

$courseID = isset($_GET['courseID']) ? $_GET['courseID'] : '';
$courseID = mysqli_real_escape_string($conn, $courseID);

$query = "SELECT * FROM student WHERE studentID = '$studentID'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

$query = "SELECT 
            c.*, 
            cc.courseCat, 
            l.lecturerName,
            d.discountPercent,
            ROUND(AVG(cr.rating), 1) AS rating,
            COUNT(DISTINCT cr.studentID) AS totalReview
        FROM course c 
        JOIN coursecategory cc ON c.courseCatID = cc.courseCatID
        JOIN lecturer l ON c.lecturerID = l.lecturerID
        LEFT JOIN coursereview cr ON cr.courseID = c.courseID
        LEFT JOIN discount d ON c.courseID = d.courseID
        WHERE c.courseID = '$courseID'";
$result = mysqli_query($conn, $query);
$courseCurrent = mysqli_fetch_assoc($result);

$discount = $courseCurrent['discountPercent'];
$price = $courseCurrent['price'];
$courseCurrent['finalPrice'] = ($discount > 0) ? $price - ($price * $discount / 100) : null;
$courseCat = $courseCurrent['courseCatID'];

$rating = $courseCurrent['rating'] ?? 0;
$fullStars = floor($rating);
$halfStar = ($rating - $fullStars) >= 0.5;
$totalStars = 5;

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

$query = "SELECT videoURL FROM lesson LIMIT 1";
$result = mysqli_query($conn, $query);
$video = mysqli_fetch_assoc($result);

$query = "SELECT 
            c.*, 
            ROUND(AVG(cr.rating), 1) AS rating
          FROM course c
          LEFT JOIN coursereview cr ON c.courseID = cr.courseID
          WHERE c.courseCatID = '$courseCat'
          GROUP BY c.courseID
          LIMIT 4";
$result = mysqli_query($conn, $query);
$related = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT 
            rating,
            review,
            reviewDate,
            `name`,
            email,
            studentImage
        FROM coursereview cr
        JOIN student s ON cr.studentID = s.studentID
        WHERE courseID = '$courseID'";
$result = mysqli_query($conn, $query);
$reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/course.css">
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
    <div class="content">      
        <div class="first-content">
            <div class="detail-course">
                <h2><?= htmlspecialchars($courseCurrent['courseCat']) ?></h2>
                <h1><?= htmlspecialchars($courseCurrent['courseTitle']) ?></h1>
                <p><?= htmlspecialchars($courseCurrent['courseDescription']) ?></p>
                <div class="rating"> 
                    <?php if (!empty($courseCurrent['rating'])): ?>
                        <h2><?= htmlspecialchars($courseCurrent['rating']) ?></h2>
                    <?php else: ?>
                        <h2>Not Rated</h2>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalStars; $i++): ?>
                        <?php if ($i <= $fullStars): ?>
                            <svg class="star full" viewBox="0 0 24 24">
                                <path d="M12 2l2.9 6.9L22 9.2l-5.5 4.8L18 21l-6-3.6L6 21l1.5-7L2 9.2l7.1-0.3L12 2z"/>
                            </svg>
                        <?php elseif ($i == $fullStars + 1 && $halfStar): ?>
                            <svg class="star" viewBox="0 0 24 24">
                                <defs>
                                    <linearGradient id="halfGold" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="50%" stop-color="gold"/>
                                        <stop offset="50%" stop-color="white"/>
                                    </linearGradient>
                                </defs>
                                <path d="M12 2l2.9 6.9L22 9.2l-5.5 4.8L18 21l-6-3.6L6 21l1.5-7L2 9.2l7.1-0.3L12 2z" fill="url(#halfGold)" stroke="gold" stroke-width="2"/>
                            </svg>
                        <?php else: ?>
                            <svg class="star" viewBox="0 0 24 24">
                                <path d="M12 2l2.9 6.9L22 9.2l-5.5 4.8L18 21l-6-3.6L6 21l1.5-7L2 9.2l7.1-0.3L12 2z"/>
                            </svg>
                        <?php endif; ?>
                    <?php endfor; ?>
                        <p>( <?= htmlspecialchars($courseCurrent['totalReview'])?> Review )</p>
                </div>
                <div class="pricing">
                    <?php if (!empty($courseCurrent['finalPrice'])): ?>
                        <div class="discount">
                            <div class="discount-pricing">
                                <div class="discounted">                         
                                    <h3><span>IDR</span> <?= number_format($courseCurrent['finalPrice'], 0, ',', '.') ?></h3>
                                    <p><span>IDR</span> <?= number_format($courseCurrent['price'], 0, ',', '.') ?></p>
                                </div> 
                                <h2>(<?= htmlspecialchars($courseCurrent['discountPercent']) ?>% OFF)</h2>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="non-discount">
                            <h2>IDR <?= htmlspecialchars($courseCurrent['price']) ?></h2>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="lecturer">
                    <h3>Lectured by </h3>
                    <p><?= htmlspecialchars($courseCurrent['lecturerName'])?></p>
                </div>
                <div class="last-detail">
                    <i class="fa-solid fa-language"></i> 
                    <p>English</p>
                    <i class="fa-solid fa-closed-captioning"></i>
                    <p>Indonesian, Mandarin, Korean</p>
                </div>
            </div>
            <img src="uploads/thumbnails/<?= htmlspecialchars($courseCurrent['courseThumbnail']) ?>" alt="">
        </div>
        <div class="second-content">
            <form method="POST" class="cart-form">
                <input type="hidden" name="courseID" value="<?= htmlspecialchars($courseCurrent ['courseID']) ?>">
                <div class="add-to-bag">
                    <button type="submit" name="add-to-cart">Add To Bag</button> 
                    <button id="preview-button" type="button">Preview This Course</button>
                </div>   
            </form>
            
            <div class="term">
                <h2>Attention</h2>
                <p>This course content is personal use only ( If any indication of a violation is found, we will permanently close the account. )</p>
            </div>
        </div>
        <div class="third-content">
            <div class="preview-container" id="preview" style="display:none;">
                <div class="preview" >
                    <button id="close-preview"><i class="fa-solid fa-xmark"></i></button>
                    <h2>Course Preview</h2>
                    <h1><?= htmlspecialchars($courseCurrent['courseTitle'])?></h1>
                    <iframe src="<?= htmlspecialchars($video['videoURL'])?>" allow="autoplay" allowfullscreen></iframe>          
                </div>
            </div>
            <div class="related-review">
                <div class="related">
                    <h1>Related Content</h1>
                    <div class="courses-container">
                        <?php foreach($related as $relate): ?>
                            <a href="course.php?courseID=<?= htmlspecialchars($relate['courseID'])?>" class="course-wrapper">
                                <img src="uploads/thumbnails/<?= htmlspecialchars($relate['courseThumbnail'])?>">
                                <div class="detail-related">
                                    <h2><?= htmlspecialchars($relate['courseTitle'])?></h2>
                                    <p><?= htmlspecialchars($relate['courseDescription'])?></p>
                                    <?php
                                    $ratingRelated = $relate['rating'] ?? 0;
                                    $fullStar = floor($ratingRelated);
                                    $halfStars = ($ratingRelated - $fullStar) >= 0.5;
                                    $totalStar = 5;
                                    ?>
                                    <div class="ratings"> 
                                        <?php if (!empty($relate['rating'])): ?>
                                            <h2><?= htmlspecialchars($relate['rating']) ?></h2>
                                        <?php else: ?>
                                            <h2>Not Rated</h2>
                                        <?php endif; ?>
                                        <?php for ($i = 1; $i <= $totalStar; $i++): ?>
                                            <?php if ($i <= $fullStar): ?>
                                                <svg class="stars full" viewBox="0 0 24 24">
                                                    <path d="M12 2l2.9 6.9L22 9.2l-5.5 4.8L18 21l-6-3.6L6 21l1.5-7L2 9.2l7.1-0.3L12 2z"/>
                                                </svg>
                                            <?php elseif ($i == $fullStar + 1 && $halfStars): ?>
                                                <svg class="stars" viewBox="0 0 24 24">
                                                    <defs>
                                                        <linearGradient id="halfGold" x1="0%" y1="0%" x2="100%" y2="0%">
                                                            <stop offset="50%" stop-color="gold"/>
                                                            <stop offset="50%" stop-color="white"/>
                                                        </linearGradient>
                                                    </defs>
                                                    <path d="M12 2l2.9 6.9L22 9.2l-5.5 4.8L18 21l-6-3.6L6 21l1.5-7L2 9.2l7.1-0.3L12 2z" fill="url(#halfGold)" stroke="gold" stroke-width="2"/>
                                                </svg>
                                            <?php else: ?>
                                                <svg class="stars" viewBox="0 0 24 24">
                                                    <path d="M12 2l2.9 6.9L22 9.2l-5.5 4.8L18 21l-6-3.6L6 21l1.5-7L2 9.2l7.1-0.3L12 2z"/>
                                                </svg>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="include-review">
                    <div class="include">
                        <h2>Package Including </h2>
                        <p><i class="fa-solid fa-graduation-cap"></i> 14 hours video-based learning (VBL)</p>
                        <p><i class="fa-solid fa-laptop-file"></i> exercises and projects</p>
                        <p><i class="fa-solid fa-headset"></i> interaction with lecturer</p>
                        <p><i class="fa-solid fa-scroll"></i> complation certificate</p>
                    </div>
                    <div class="review">
                        <h2>Reviews for this course</h2>
                        <?php if (empty($reviews)): ?>
                            <h1>There are no review for this course</h1>
                        <?php else: ?>
                            <?php foreach ($reviews as $review): ?>
                                <?php
                                    $ratingReview = $review['rating'] ?? 0;
                                    $fullStarss = floor($ratingReview);
                                    $halfStarr = ($ratingReview - $fullStarss) >= 0.5;
                                    $totalStarss = 5;
                                    $reviewDateFormat = formatDate($review['reviewDate']);                  
                                ?>
                                <div class="review-comment">
                                    <div class="student">
                                        <img src="uploads/profilePicture/<?= htmlspecialchars($review['studentImage']) ?>">
                                        <div class="student-detail">
                                            <h3><?= htmlspecialchars($review['name']) ?></h3>
                                            <p><?= htmlspecialchars($review['email']) ?></p>
                                        </div>
                                    </div>
                                    <h5>Reviewed on <?= $reviewDateFormat ?></h5> 
                                    <div class="ratingss"> 
                                            <?php for ($i = 1; $i <= $totalStarss; $i++): ?>
                                                <?php if ($i <= $fullStarss): ?>
                                                    <svg class="starss full" viewBox="0 0 24 24">
                                                        <path d="M12 2l2.9 6.9L22 9.2l-5.5 4.8L18 21l-6-3.6L6 21l1.5-7L2 9.2l7.1-0.3L12 2z"/>
                                                    </svg>
                                                <?php elseif ($i == $fullStarss + 1 && $halfStarr): ?>
                                                    <svg class="starss" viewBox="0 0 24 24">
                                                        <defs>
                                                            <linearGradient id="halfGold" x1="0%" y1="0%" x2="100%" y2="0%">
                                                                <stop offset="50%" stop-color="gold"/>
                                                                <stop offset="50%" stop-color="white"/>
                                                            </linearGradient>
                                                        </defs>
                                                        <path d="M12 2l2.9 6.9L22 9.2l-5.5 4.8L18 21l-6-3.6L6 21l1.5-7L2 9.2l7.1-0.3L12 2z" fill="url(#halfGold)" stroke="gold" stroke-width="2"/>
                                                    </svg>
                                                <?php else: ?>
                                                    <svg class="starss" viewBox="0 0 24 24">
                                                        <path d="M12 2l2.9 6.9L22 9.2l-5.5 4.8L18 21l-6-3.6L6 21l1.5-7L2 9.2l7.1-0.3L12 2z"/>
                                                    </svg>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    <h6><?= htmlspecialchars($review['review']) ?></h6>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <script src="js/course.js"></script>
    <script src="js/search.js"></script>
</body>
</html>