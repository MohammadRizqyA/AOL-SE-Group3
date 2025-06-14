<?php
session_start();
include 'api/connect.php';
include 'exploreDropdown.php';
include 'categoryList.php';
include 'addToCart.php';
if (!isset($_SESSION['studentID'])) {
    header("Location: signin.php");
}
$studentID = $_SESSION['studentID'];

$popupMessage = $_SESSION['popupMessage'] ?? '';
unset($_SESSION['popupMessage']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
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
    <div class="banner">
        <div class="content-banner">
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
            <button id="video-editing">Video Editing</button>
            <button id="programming-language">Programming Language</button>
            <button id="application-development">Application Development</button>
            <button id="website-development">Website Development</button>
            <button id="visual-design">Visual Design</button>
            <button id="data-analysis">Data Analysis</button>
        </div>
        <div class="course-container" id="VE" >
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
                                            <?php
                                                $rating = $ID['rating'] ?? 0;
                                                $fullStar = floor($rating);
                                                $halfStars = ($rating - $fullStar) >= 0.5;
                                                $totalStar = 5;
                                            ?>
                                            <div class="ratings"> 
                                                <?php if (!empty($ID['rating'])): ?>
                                                    <h2><?= htmlspecialchars($ID['rating']) ?></h2>
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
                                            <?php if (!empty($ID['finalPrice'])): ?>
                                            <div class="discount-checkout">                                             
                                                <div class="discounted-checkout">
                                                    <h5>IDR <?= number_format($ID['finalPrice'], 0, ',', '.')?></h5>
                                                </div> 
                                                <p>IDR <?= number_format($ID['price'], 0, ',', '.') ?></p>
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>IDR <?= htmlspecialchars($ID['price']) ?></h5>
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
        <div class="course-container" id="PL" style="display:none;">
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
                                            <?php
                                                $rating = $ID['rating'] ?? 0;
                                                $fullStar = floor($rating);
                                                $halfStars = ($rating - $fullStar) >= 0.5;
                                                $totalStar = 5;
                                            ?>
                                            <div class="ratings"> 
                                                <?php if (!empty($ID['rating'])): ?>
                                                    <h2><?= htmlspecialchars($ID['rating']) ?></h2>
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
                                            <?php if (!empty($ID['finalPrice'])): ?>
                                            <div class="discount-checkout">                                             
                                                <div class="discounted-checkout">
                                                    <h5>IDR <?= number_format($ID['finalPrice'], 0, ',', '.')?></h5>
                                                </div> 
                                                <p>IDR <?= number_format($ID['price'], 0, ',', '.') ?></p>
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>IDR <?= htmlspecialchars($ID['price']) ?></h5>
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
                                            <?php
                                                $rating = $ID['rating'] ?? 0;
                                                $fullStar = floor($rating);
                                                $halfStars = ($rating - $fullStar) >= 0.5;
                                                $totalStar = 5;
                                            ?>
                                            <div class="ratings"> 
                                                <?php if (!empty($ID['rating'])): ?>
                                                    <h2><?= htmlspecialchars($ID['rating']) ?></h2>
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
                                            <?php if (!empty($ID['finalPrice'])): ?>
                                            <div class="discount-checkout">                                             
                                                <div class="discounted-checkout">
                                                    <h5>IDR <?= number_format($ID['finalPrice'], 0, ',', '.')?></h5>
                                                </div> 
                                                <p>IDR <?= number_format($ID['price'], 0, ',', '.') ?></p>
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>IDR <?= htmlspecialchars($ID['price']) ?></h5>
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
                                            <?php
                                                $rating = $ID['rating'] ?? 0;
                                                $fullStar = floor($rating);
                                                $halfStars = ($rating - $fullStar) >= 0.5;
                                                $totalStar = 5;
                                            ?>
                                            <div class="ratings"> 
                                                <?php if (!empty($ID['rating'])): ?>
                                                    <h2><?= htmlspecialchars($ID['rating']) ?></h2>
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
                                            <?php if (!empty($ID['finalPrice'])): ?>
                                            <div class="discount-checkout">                                             
                                                <div class="discounted-checkout">
                                                    <h5>IDR <?= number_format($ID['finalPrice'], 0, ',', '.')?></h5>
                                                </div> 
                                                <p>IDR <?= number_format($ID['price'], 0, ',', '.') ?></p>
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>IDR <?= htmlspecialchars($ID['price']) ?></h5>
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
                                           <?php
                                                $rating = $ID['rating'] ?? 0;
                                                $fullStar = floor($rating);
                                                $halfStars = ($rating - $fullStar) >= 0.5;
                                                $totalStar = 5;
                                            ?>
                                            <div class="ratings"> 
                                                <?php if (!empty($ID['rating'])): ?>
                                                    <h2><?= htmlspecialchars($ID['rating']) ?></h2>
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
                                            <?php if (!empty($ID['finalPrice'])): ?>
                                            <div class="discount-checkout">                                             
                                                <div class="discounted-checkout">
                                                    <h5>IDR <?= number_format($ID['finalPrice'], 0, ',', '.')?></h5>
                                                </div> 
                                                <p>IDR <?= number_format($ID['price'], 0, ',', '.') ?></p>
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>IDR <?= htmlspecialchars($ID['price']) ?></h5>
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
        <div class="course-container" id="DM" style="display:none;">
            <div class="course-container-wrapper">
                <?php foreach ($courseDA as $ID): ?>
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
                                            <?php
                                                $rating = $ID['rating'] ?? 0;
                                                $fullStar = floor($rating);
                                                $halfStars = ($rating - $fullStar) >= 0.5;
                                                $totalStar = 5;
                                            ?>
                                            <div class="ratings"> 
                                                <?php if (!empty($ID['rating'])): ?>
                                                    <h2><?= htmlspecialchars($ID['rating']) ?></h2>
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
                                            <?php if (!empty($ID['finalPrice'])): ?>
                                            <div class="discount-checkout">                                             
                                                <div class="discounted-checkout">
                                                    <h5>IDR <?= number_format($ID['finalPrice'], 0, ',', '.')?></h5>
                                                </div> 
                                                <p>IDR <?= number_format($ID['price'], 0, ',', '.') ?></p>
                                            </div>
                                            <?php else: ?>
                                            <div class="non-discount-checkout">
                                                <h5>IDR <?= htmlspecialchars($ID['price']) ?></h5>
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
            <a class="show-all" href="category.php?courseCatID=DA">Show All Data Analysis Courses<i class="fa-solid fa-angle-right"></i></a>
        </div>
    </div>
    <div class="banner-promotion">
        <img src="images/banner/banner-promotion.png" alt="">
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

        <?php if (!empty($popupMessage)): ?>
            <div class="popup-message"><?= htmlspecialchars($popupMessage) ?></div>
            <audio id="popupSound" autoplay><source src="images/notification.wav" type="audio/mpeg"></audio>
        <?php endif; ?>

    <script src="js/index.js"></script>
    <script src="js/search.js"></script>
</body>
</html>