<?php
session_start();
include 'api/connect.php';
include 'exploreDropdown.php';
include 'addToCart.php';

if (!isset($_SESSION['studentID'])) {
    header("Location: signin.php");
}
$studentID = $_SESSION['studentID'];

if (!isset($_GET['courseCatID']) || empty($_GET['courseCatID'])) {
    die("No courseCat ID provided.");
}
$courseCatID = mysqli_real_escape_string($conn, $_GET['courseCatID']);

$query = "SELECT 
            c.*,
            d.discountPercent,
            ROUND(AVG(cr.rating), 1) AS rating,
            COUNT(DISTINCT s.sessionID) AS totalSession,
            SUM(CASE WHEN s.sessionType = 'Lesson' THEN 1 ELSE 0 END) AS totalLesson,
            SUM(CASE WHEN s.sessionType = 'Exercise' THEN 1 ELSE 0 END) AS totalExercise,
            SUM(CASE WHEN s.sessionType = 'Project' THEN 1 ELSE 0 END) AS totalProject,
            COUNT(DISTINCT e.studentID) AS totalEnrolled,
            COUNT(DISTINCT cr.studentID) AS totalRatingStudent,
            (c.price - (c.price * IFNULL(d.discountPercent, 0) / 100)) AS finalPrice 
        FROM course c
        LEFT JOIN discount d ON c.courseID = d.courseID
        LEFT JOIN `session` s ON c.courseID = s.courseID
        LEFT JOIN enrolled e ON c.courseID = e.courseID
        LEFT JOIN coursereview cr ON cr.courseID = c.courseID
        WHERE courseCatID = '$courseCatID'
        GROUP BY c.courseID";        
$result = mysqli_query($conn, $query);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query = "SELECT 
            c.*,
            d.discountPercent,
            ROUND(AVG(cr.rating), 1) AS rating,
            COUNT(DISTINCT s.sessionID) AS totalSession,
            SUM(CASE WHEN s.sessionType = 'Lesson' THEN 1 ELSE 0 END) AS totalLesson,
            SUM(CASE WHEN s.sessionType = 'Exercise' THEN 1 ELSE 0 END) AS totalExercise,
            SUM(CASE WHEN s.sessionType = 'Project' THEN 1 ELSE 0 END) AS totalProject,
            COUNT(DISTINCT e.studentID) AS totalEnrolled,
            COUNT(DISTINCT cr.studentID) AS totalRatingStudent,
            (c.price - (c.price * IFNULL(d.discountPercent, 0) / 100)) AS finalPrice 
        FROM course c
        LEFT JOIN discount d ON c.courseID = d.courseID
        LEFT JOIN `session` s ON c.courseID = s.courseID
        LEFT JOIN enrolled e ON c.courseID = e.courseID
        LEFT JOIN coursereview cr ON cr.courseID = c.courseID
        WHERE courseCatID = '$courseCatID'
        GROUP BY c.courseID
        ORDER BY rating DESC, totalRatingStudent DESC
        LIMIT 1";
$result = mysqli_query($conn, $query);
$bestCourse = mysqli_fetch_assoc($result);

$query = "SELECT
            cp.*
        FROM careerpath cp
        JOIN coursecategory cc ON cp.courseCatID = cc.courseCatID
        WHERE cp.courseCatID = '$courseCatID'";
$result = mysqli_query($conn, $query);
$careerPath = mysqli_fetch_all($result, MYSQLI_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/category.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Afacad:ital,wght@0,400..700;1,400..700&family=Bebas+Neue&family=Gabarito:wght@400..900&family=Gloock&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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
        <div class="banner-cat">
            <div class="banner-text">
                <h2>Best Way To Start Your Learning Journey</h2>
                <h3>Explore courses according to your interests, with segmented fields to help you find the course you want.</h3>
            </div>
        </div>
        <div class="text">
            <?php if ($courseCatID === 'VE'): ?>
                <h1>Video Editing</h1>
                <p>Do you want to become a professional video editor? Then the following selected courses are suitable for you, some learning related to video editing with various software</p>
            <?php elseif ($courseCatID === 'PL'): ?>
                <h1>Programming Language</h1>
                <p>Want to master programming languages? The following selected courses are perfect for you, covering various popular languages used in the tech industry.</p>
            <?php elseif ($courseCatID === 'WD'): ?>
                <h1>Website Development</h1>
                <p>Aspiring to become a professional web developer? These courses will guide you through website development from basic to advanced levels using the latest technologies.</p>
            <?php elseif ($courseCatID === 'AD'): ?>
                <h1>Application Development</h1>
                <p>Interested in building your own applications? These courses are designed to equip you with the skills to develop mobile and desktop applications across various platforms.</p>
            <?php elseif ($courseCatID === 'VD'): ?>
                <h1>Visual Design</h1>
                <p>Want to express your ideas visually? Learn the fundamentals and advanced techniques of visual design through these carefully curated courses.</p>
            <?php else: ?>
                <h1>Data Analytics</h1>
                <p>Curious about turning data into insights? These courses are ideal for learning how to collect, analyze, and interpret data using industry-standard tools and methods.</p>
            <?php endif; ?>
            
        </div>
        <div class="course-container">     
            <?php foreach($courses as $course): ?>
                <div class="course-group">
                            <a href="course.php?courseID=<?= htmlspecialchars($course['courseID'])?>" class="course-wrapper">
                                <img src="uploads/thumbnails/<?= htmlspecialchars($course['courseThumbnail'])?>">
                                <div class="course-attribute">                                 
                                    <h2><?= htmlspecialchars(mb_strimwidth($course['courseTitle'], 0, 55, "...")) ?></h2>
                                    
                                </div>
                            </a>
                            <div class="course-details">
                                <div class="details-content">
                                    <div class="first-detail">
                                        <div class="level-rating">
                                            <p><?= htmlspecialchars($course['level']) ?></p>
                                            <?php
                                                $rating = $course['rating'] ?? 0;
                                                $fullStar = floor($rating);
                                                $halfStars = ($rating - $fullStar) >= 0.5;
                                                $totalStar = 5;
                                            ?>
                                            <div class="ratings"> 
                                                <?php if (!empty($course['rating'])): ?>
                                                    <h2><?= htmlspecialchars($course['rating']) ?></h2>
                                                <?php else: ?>
                                                    <h2>N/A</h2>
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
                                        <div class="pricing">
                                            <?php if (!empty($course['finalPrice'])): ?>
                                            <div class="discount-checkout">                                             
                                                <div class="discounted-checkout">
                                                    <h5>IDR <?= number_format($course['finalPrice'], 0, ',', '.')?></h5>
                                                </div> 
                                                <p>IDR <?= number_format($course['price'], 0, ',', '.') ?></p>
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>IDR <?= htmlspecialchars($course['price']) ?></h5>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="description"><?= htmlspecialchars($course['courseDescription']) ?></p>
                                    <h2>This course package include:</h2>
                                     <div class="sessions">
                                        <p><?= htmlspecialchars($course['totalLesson']) ?> Lesson</p>
                                        <p><?= htmlspecialchars($course['totalExercise']) ?> Exercise</p>                                            
                                        <p><?= htmlspecialchars($course['totalProject']) ?> Project</p>
                                    </div>
                                    <form method="POST" class="cart-form">
                                        <input type="hidden" name="courseID" value="<?= htmlspecialchars($course['courseID']) ?>">
                                        <div class="add-to-bag">
                                            <button type="submit" name="add-to-cart">Add To Bag</button> 
                                        </div>
                                    </form>
                                </div>
                            </div>
                    </div> 
            <?php endforeach; ?>  
        </div>
        <div class="best-course">
            <h1>Top Rated Course</h1>
            <h4>Many learners enjoy this top-rated course because of its interactive content.</h4>
            <a href="course.php?courseID=<?= htmlspecialchars($bestCourse['courseID'])?>" class="best-course-wrapper">
                <img src="uploads/thumbnails/<?= htmlspecialchars($bestCourse['courseThumbnail'])?>">
                <div class="best-course-attribute">                                 
                    <h2><?= htmlspecialchars($bestCourse['courseTitle']) ?></h2>
                    <h3><?= htmlspecialchars($bestCourse['level']) ?></h3>
                    <p><?= htmlspecialchars($bestCourse['courseDescription']) ?></p>
                    <?php
                        $rating = $bestCourse['rating'] ?? 0;
                        $fullStar = floor($rating);
                        $halfStars = ($rating - $fullStar) >= 0.5;
                        $totalStar = 5;
                    ?>
                    <div class="ratingss"> 
                        <?php if (!empty($bestCourse['rating'])): ?>
                            <h2><?= htmlspecialchars($bestCourse['rating']) ?></h2>
                        <?php else: ?>
                            <h2>N/A</h2>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalStar; $i++): ?>
                            <?php if ($i <= $fullStar): ?>
                                <svg class="starss full" viewBox="0 0 24 24">
                                    <path d="M12 2l2.9 6.9L22 9.2l-5.5 4.8L18 21l-6-3.6L6 21l1.5-7L2 9.2l7.1-0.3L12 2z"/>
                                </svg>
                            <?php elseif ($i == $fullStar + 1 && $halfStars): ?>
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
                    <div class="pricing">
                        <?php if (!empty($course['finalPrice'])): ?>
                        <div class="best-discount">                                             
                            <div class="best-discountd">
                                <h5><span>IDR</span> <?= number_format($course['finalPrice'], 0, ',', '.')?></h5>
                            </div> 
                            <p><span>IDR</span> <?= number_format($course['price'], 0, ',', '.') ?></p>
                        </div>
                        <?php else: ?>
                        <div class="best-non-discount">
                            <h5><span>IDR</span> <?= htmlspecialchars($course['price']) ?></h5>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </a>

        </div>
        <div class="career-path">
            <div class="career-content">
                <h1>Career Information</h1>
                <div class="career-container" id="careerVE">
                    <?php foreach($careerPath as $career): ?>
                        <div class="career-wrapper">
                            <h2><?= htmlspecialchars($career['career']) ?></h2>
                            <p><?= htmlspecialchars($career['information']) ?></p>
                            <h3>$<?= htmlspecialchars($career['salaryRate']) ?><span>/ Month</span></h3>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
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
    <script src="js/search.js"></script>
</body>
</html>