<?php
include 'api/connect.php';
include 'categoryList.php';

$popupMessage = $_SESSION['popupMessage'] ?? '';
unset($_SESSION['popupMessage']);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add-to-cart'])) {
    $courseID = trim($_POST['courseID'] ?? '');
    $_SESSION['popupMessage'] = 'Please sign in to add course to the bag';
    header("Location: index.php");
    exit;

}

$categories = ['PL', 'AD', 'WD', 'VD', 'VE', 'DM'];
$courseResults = [];
$totalCourses = [];
foreach ($categories as $catID) {
    // Fetch 5 course
    $query = "SELECT courseTitle, courseID FROM course WHERE courseCatID = '$catID' LIMIT 5";
    $courseResults[$catID] = mysqli_query($conn, $query);

    // Count total course
    $query = "SELECT COUNT(courseID) as totalCourse FROM course WHERE courseCatID = '$catID'";
    $result = mysqli_query($conn, $query);
    $totalCourses[$catID] = mysqli_fetch_assoc($result)['totalCourse'];
}

$categoryLabels = [
    'PL' => 'Programming Languages',
    'AD' => 'Application Development',
    'WD' => 'Website Development',
    'VD' => 'Visual Design',
    'VE' => 'Video Editing',
    'DM' => 'Data Analysis'
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
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
    <div class="navbar">
        <a href="index.php" class="logo"><img src="images/fullLogo.png"></a>
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
        <div class="login-register">
            <a class="login" href="signin.php">Sign In</a>
            <a class="register" href="signup.php">Join for free</a>
        </div>
    </div>
    <div class="banner">
        <div class="content-banner">
            <a href="register.php">Join for free</a>
        </div>
    </div>
    <div class="universities">
     <p>We collaborate with more than hundreds of experienced lecturers in their fields from the world's 10 best universities</p>
     <div class="slider-container">
        <div class="slider" style="--width: 150px;--height: 100px;--quantity: 10;">
                <div class="list">
                    <div class="item" style="--position: 1"><img src="images/slider_animation/slider1.png" alt=""></div>
                    <div class="item" style="--position: 2"><img src="images/slider_animation/slider2.png" alt=""></div>
                    <div class="item" style="--position: 3"><img src="images/slider_animation/slider3.png" alt=""></div>
                    <div class="item" style="--position: 4"><img src="images/slider_animation/slider4.png" alt=""></div>
                    <div class="item" style="--position: 5"><img src="images/slider_animation/slider5.png" alt=""></div>
                    <div class="item" style="--position: 6"><img src="images/slider_animation/slider6.png" alt=""></div>
                    <div class="item" style="--position: 7"><img src="images/slider_animation/slider7.png" alt=""></div>
                    <div class="item" style="--position: 8"><img src="images/slider_animation/slider8.png" alt=""></div>
                    <div class="item" style="--position: 9"><img src="images/slider_animation/slider9.png" alt=""></div>
                    <div class="item" style="--position: 10"><img src="images/slider_animation/slider10.png" alt=""></div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-category">
        <div class="categories-button">
            <button id="programming-language">Programming Language</button>
            <button id="application-development">Application Development</button>
            <button id="website-development">Website Development</button>
            <button id="visual-design">Visual Design</button>
            <button id="video-editing">Video Editing</button>
            <button id="data-analysis">Data Analysis</button>
        </div>
        <div class="course-container" id="PL">
            <div class="course-container-wrapper">
                <?php foreach ($coursePL as $ID): ?>
                        <div class="course-group">
                            <a href="course.php?courseID=<?= htmlspecialchars($ID['courseID'])?>" class="course-wrapper">
                                <img src="uploads/thumbnails/<?= htmlspecialchars($ID['courseThumbnail'])?>">
                                <div class="course-attribute">                                 
                                    <h2><?= htmlspecialchars(mb_strimwidth($ID['courseTitle'], 0, 55, "...")) ?></h2>
                                    
                                </div>
                            </a>
                            <div class="course-details">
                                <div class="details-content">
                                    <div class="first-detail">
                                        <div class="level-rating">
                                            <p><?= htmlspecialchars($ID['level']) ?></p>
                                            <p><?= htmlspecialchars($ID['rating'] ?? 'N/A') ?> (<?= htmlspecialchars($ID['totalEnrolled']) ?> review)</p>  
                                        </div>
                                        <div class="pricing">
                                            <?php if (!empty($ID['finalPrice'])): ?>
                                            <div class="discount-checkout">
                                                <p>$<?= htmlspecialchars($ID['price']) ?></p>
                                                <div class="discounted-checkout">
                                                    <h5>$<?= htmlspecialchars($ID['finalPrice'])?></h5>
                                                </div> 
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>$<?= htmlspecialchars($ID['price']) ?></h5>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="description"><?= htmlspecialchars($ID['courseDescription']) ?></p>
                                    <h2>This course package include:</h2>
                                     <div class="sessions">
                                        <p><?= htmlspecialchars($ID['totalLesson']) ?> Lesson</p>
                                        <p><?= htmlspecialchars($ID['totalExercise']) ?> Exercise</p>                                            
                                        <p><?= htmlspecialchars($ID['totalProject']) ?> Project</p>
                                    </div>
                                    <form method="POST" class="cart-form">
                                        <input type="hidden" name="courseID" value="<?= htmlspecialchars($ID['courseID']) ?>">
                                        <div class="add-to-bag">
                                            <button type="submit" name="add-to-cart">Add To Bag</button> 
                                        </div>
                                       
                                    </form>
                                </div>
                            </div>
                        </div>                                                   
                <?php endforeach; ?> 
            </div>
            <a class="show-all" href="category.php?courseCatID=PL">Show All Programming Language Courses<i class="fa-solid fa-angle-right"></i></a>
        </div>
        <div class="course-container" id="AD" style="display:none;">
            <div class="course-container-wrapper">
                <?php foreach ($courseAD as $ID): ?> 
                        <div class="course-group">
                            <a href="course.php?courseID=<?= htmlspecialchars($ID['courseID'])?>" class="course-wrapper">
                                <img src="uploads/thumbnails/<?= htmlspecialchars($ID['courseThumbnail'])?>">
                                <div class="course-attribute">                                 
                                    <h2><?= htmlspecialchars(mb_strimwidth($ID['courseTitle'], 0, 55, "...")) ?></h2>
                                    
                                </div>
                            </a>
                            <div class="course-details">
                                <div class="details-content">
                                    <div class="first-detail">
                                        <div class="level-rating">
                                            <p><?= htmlspecialchars($ID['level']) ?></p>
                                            <p><?= htmlspecialchars($ID['rating'] ?? 'N/A') ?> (<?= htmlspecialchars($ID['totalEnrolled']) ?> review)</p>
                                        </div>
                                        <div class="pricing">
                                            <?php if (!empty($ID['finalPrice'])): ?>
                                            <div class="discount-checkout">
                                                <p>$<?= htmlspecialchars($ID['price']) ?></p>
                                                <div class="discounted-checkout">
                                                    <h5>$<?= htmlspecialchars($ID['finalPrice'])?></h5>
                                                </div> 
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>$<?= htmlspecialchars($ID['price']) ?></h5>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="description"><?= htmlspecialchars($ID['courseDescription']) ?></p>
                                    <h2>This course package include:</h2>
                                     <div class="sessions">
                                        <p><?= htmlspecialchars($ID['totalLesson']) ?> Lesson</p>
                                        <p><?= htmlspecialchars($ID['totalExercise']) ?> Exercise</p>                                            
                                        <p><?= htmlspecialchars($ID['totalProject']) ?> Project</p>
                                    </div>
                                    <form method="POST" class="cart-form">
                                        <input type="hidden" name="courseID" value="<?= htmlspecialchars($ID['courseID']) ?>">
                                        <div class="add-to-bag">
                                            <button type="submit" name="add-to-cart">Add To Bag</button> 
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>                                                                                              
                <?php endforeach; ?>
            </div>
            <a class="show-all" href="category.php?courseCatID=AD">Show All Application Development Courses<i class="fa-solid fa-angle-right"></i></a>
        </div>
        <div class="course-container" id="WD" style="display:none;">
            <div class="course-container-wrapper">
                <?php foreach ($courseWD as $ID): ?>
                    <div class="course-group">
                            <a href="course.php?courseID=<?= htmlspecialchars($ID['courseID'])?>" class="course-wrapper">
                                <img src="uploads/thumbnails/<?= htmlspecialchars($ID['courseThumbnail'])?>">
                                <div class="course-attribute">                                 
                                    <h2><?= htmlspecialchars(mb_strimwidth($ID['courseTitle'], 0, 55, "...")) ?></h2>
                                    
                                </div>
                            </a>
                            <div class="course-details">
                                <div class="details-content">
                                    <div class="first-detail">
                                        <div class="level-rating">
                                            <p><?= htmlspecialchars($ID['level']) ?></p>
                                            <p><?= htmlspecialchars($ID['rating'] ?? 'N/A') ?> (<?= htmlspecialchars($ID['totalEnrolled']) ?> review)</p>
                                        </div>
                                        <div class="pricing">
                                            <?php if (!empty($ID['finalPrice'])): ?>
                                            <div class="discount-checkout">
                                                <p>$<?= htmlspecialchars($ID['price']) ?></p>
                                                <div class="discounted-checkout">
                                                    <h5>$<?= htmlspecialchars($ID['finalPrice'])?></h5>
                                                </div> 
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>$<?= htmlspecialchars($ID['price']) ?></h5>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="description"><?= htmlspecialchars($ID['courseDescription']) ?></p>
                                    <h2>This course package include:</h2>
                                     <div class="sessions">
                                        <p><?= htmlspecialchars($ID['totalLesson']) ?> Lesson</p>
                                        <p><?= htmlspecialchars($ID['totalExercise']) ?> Exercise</p>                                            
                                        <p><?= htmlspecialchars($ID['totalProject']) ?> Project</p>
                                    </div>
                                    <form method="POST" class="cart-form">
                                        <input type="hidden" name="courseID" value="<?= htmlspecialchars($ID['courseID']) ?>">
                                        <div class="add-to-bag">
                                            <button type="submit" name="add-to-cart">Add To Bag</button> 
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>                                                               
                <?php endforeach; ?>
            </div>
            <a class="show-all" href="category.php?courseCatID=WD">Show All Website Development Courses<i class="fa-solid fa-angle-right"></i></a>
        </div>
        <div class="course-container" id="VD" style="display:none;">
            <div class="course-container-wrapper">
                <?php foreach ($courseVD as $ID): ?>
                    <div class="course-group">
                            <a href="course.php?courseID=<?= htmlspecialchars($ID['courseID'])?>" class="course-wrapper">
                                <img src="uploads/thumbnails/<?= htmlspecialchars($ID['courseThumbnail'])?>">
                                <div class="course-attribute">                                 
                                    <h2><?= htmlspecialchars(mb_strimwidth($ID['courseTitle'], 0, 55, "...")) ?></h2>
                                    
                                </div>
                            </a>
                            <div class="course-details">
                                <div class="details-content">
                                    <div class="first-detail">
                                        <div class="level-rating">
                                            <p><?= htmlspecialchars($ID['level']) ?></p>
                                            <p><?= htmlspecialchars($ID['rating'] ?? 'N/A') ?> (<?= htmlspecialchars($ID['totalEnrolled']) ?> review)</p>
                                        </div>
                                        <div class="pricing">
                                            <?php if (!empty($ID['finalPrice'])): ?>
                                            <div class="discount-checkout">
                                                <p>$<?= htmlspecialchars($ID['price']) ?></p>
                                                <div class="discounted-checkout">
                                                    <h5>$<?= htmlspecialchars($ID['finalPrice'])?></h5>
                                                </div> 
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>$<?= htmlspecialchars($ID['price']) ?></h5>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="description"><?= htmlspecialchars($ID['courseDescription']) ?></p>
                                    <h2>This course package include:</h2>
                                     <div class="sessions">
                                        <p><?= htmlspecialchars($ID['totalLesson']) ?> Lesson</p>
                                        <p><?= htmlspecialchars($ID['totalExercise']) ?> Exercise</p>                                            
                                        <p><?= htmlspecialchars($ID['totalProject']) ?> Project</p>
                                    </div>
                                    <form method="POST" class="cart-form">
                                        <input type="hidden" name="courseID" value="<?= htmlspecialchars($ID['courseID']) ?>">
                                        <div class="add-to-bag">
                                            <button type="submit" name="add-to-cart">Add To Bag</button> 
                                        </div>
                                    </form>
                                </div>
                            </div>
                    </div>                                                               
                <?php endforeach; ?>
            </div>
            <a class="show-all" href="category.php?courseCatID=VD">Show All Visual Design Courses<i class="fa-solid fa-angle-right"></i></a>
        </div>
        <div class="course-container" id="VE" style="display:none;">
            <div class="course-container-wrapper">
                <?php foreach ($courseVE as $ID): ?>
                    <div class="course-group">
                            <a href="course.php?courseID=<?= htmlspecialchars($ID['courseID'])?>" class="course-wrapper">
                                <img src="uploads/thumbnails/<?= htmlspecialchars($ID['courseThumbnail'])?>">
                                <div class="course-attribute">                                 
                                    <h2><?= htmlspecialchars(mb_strimwidth($ID['courseTitle'], 0, 55, "...")) ?></h2>
                                    
                                </div>
                            </a>
                            <div class="course-details">
                                <div class="details-content">
                                    <div class="first-detail">
                                        <div class="level-rating">
                                            <p><?= htmlspecialchars($ID['level']) ?></p>
                                            <p><?= htmlspecialchars($ID['rating'] ?? 'N/A') ?> (<?= htmlspecialchars($ID['totalEnrolled']) ?> review)</p>
                                        </div>
                                        <div class="pricing">
                                            <?php if (!empty($ID['finalPrice'])): ?>
                                            <div class="discount-checkout">
                                                <p>$<?= htmlspecialchars($ID['price']) ?></p>
                                                <div class="discounted-checkout">
                                                    <h5>$<?= htmlspecialchars($ID['finalPrice'])?></h5>
                                                </div> 
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>$<?= htmlspecialchars($ID['price']) ?></h5>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="description"><?= htmlspecialchars($ID['courseDescription']) ?></p>
                                    <h2>This course package include:</h2>
                                     <div class="sessions">
                                        <p><?= htmlspecialchars($ID['totalLesson']) ?> Lesson</p>
                                        <p><?= htmlspecialchars($ID['totalExercise']) ?> Exercise</p>                                            
                                        <p><?= htmlspecialchars($ID['totalProject']) ?> Project</p>
                                    </div>
                                    <form method="POST" class="cart-form">
                                        <input type="hidden" name="courseID" value="<?= htmlspecialchars($ID['courseID']) ?>">
                                        <div class="add-to-bag">
                                            <button type="submit" name="add-to-cart">Add To Bag</button> 
                                        </div>
                                    </form>
                                </div>
                            </div>
                    </div>                                                                
                <?php endforeach; ?>
            </div>
            <a class="show-all" href="category.php?courseCatID=VE">Show All Video Editing Courses<i class="fa-solid fa-angle-right"></i></a>
        </div>
        <div class="course-container" id="DM" style="display:none;">
            <div class="course-container-wrapper">
                <?php foreach ($courseDM as $ID): ?>
                    <div class="course-group">
                            <a href="course.php?courseID=<?= htmlspecialchars($ID['courseID'])?>" class="course-wrapper">
                                <img src="uploads/thumbnails/<?= htmlspecialchars($ID['courseThumbnail'])?>">
                                <div class="course-attribute">                                 
                                    <h2><?= htmlspecialchars(mb_strimwidth($ID['courseTitle'], 0, 55, "...")) ?></h2>
                                    
                                </div>
                            </a>
                            <div class="course-details">
                                <div class="details-content">
                                    <div class="first-detail">
                                        <div class="level-rating">
                                            <p><?= htmlspecialchars($ID['level']) ?></p>
                                            <p><?= htmlspecialchars($ID['rating'] ?? 'N/A') ?> (<?= htmlspecialchars($ID['totalEnrolled']) ?> review)</p>
                                        </div>
                                        <div class="pricing">
                                            <?php if (!empty($ID['finalPrice'])): ?>
                                            <div class="discount-checkout">
                                                <p>$<?= htmlspecialchars($ID['price']) ?></p>
                                                <div class="discounted-checkout">
                                                    <h5>$<?= htmlspecialchars($ID['finalPrice'])?></h5>
                                                </div> 
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>$<?= htmlspecialchars($ID['price']) ?></h5>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="description"><?= htmlspecialchars($ID['courseDescription']) ?></p>
                                    <h2>This course package include:</h2>
                                     <div class="sessions">
                                        <p><?= htmlspecialchars($ID['totalLesson']) ?> Lesson</p>
                                        <p><?= htmlspecialchars($ID['totalExercise']) ?> Exercise</p>                                            
                                        <p><?= htmlspecialchars($ID['totalProject']) ?> Project</p>
                                    </div>
                                    <form method="POST" class="cart-form">
                                        <input type="hidden" name="courseID" value="<?= htmlspecialchars($ID['courseID']) ?>">
                                        <div class="add-to-bag">
                                            <button type="submit" name="add-to-cart">Add To Bag</button> 
                                        </div>
                                    </form>
                                </div>
                            </div>
                    </div>                                                                
                <?php endforeach; ?>
            </div>
            <a class="show-all" href="category.php?courseCatID=DM">Show All Data Analysis Courses<i class="fa-solid fa-angle-right"></i></a>
        </div>
    </div>
    <script src="js/index.js"></script>

        <?php if (!empty($popupMessage)): ?>
            <div class="popup-message"><?= htmlspecialchars($popupMessage) ?></div>
            <audio id="popupSound" autoplay><source src="images/notification.wav" type="audio/mpeg"></audio>
        <?php endif; ?>
</body>
</html>