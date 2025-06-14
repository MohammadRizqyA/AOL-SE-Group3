-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 14 Jun 2025 pada 16.01
-- Versi server: 9.1.0
-- Versi PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `academiaplus`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `careerpath`
--

DROP TABLE IF EXISTS `careerpath`;
CREATE TABLE IF NOT EXISTS `careerpath` (
  `careerID` varchar(4) NOT NULL,
  `courseCatID` varchar(2) DEFAULT NULL,
  `career` varchar(255) DEFAULT NULL,
  `information` text,
  `salaryRate` int DEFAULT NULL,
  PRIMARY KEY (`careerID`),
  KEY `courseCatID` (`courseCatID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `careerpath`
--

INSERT INTO `careerpath` (`careerID`, `courseCatID`, `career`, `information`, `salaryRate`) VALUES
('CP01', 'AD', 'Mobile App Developer', 'Develop applications for mobile devices (Android/iOS), using languages such as Swift, Kotlin, or Flutter. Focus on UX and application performance.', 110000),
('CP02', 'AD', 'Software Developer', 'Design, develop, and test software applications or systems, whether for desktops, servers, or dedicated devices.', 95000),
('CP03', 'AD', 'Application Engineer', 'Integrate business needs into software applications. Focus on technical support and application optimization.', 120000),
('CP04', 'WD', 'Front-End Developer', 'Responsible for the appearance and user interaction of the website. Using HTML, CSS, JavaScript, and frameworks such as React or Vue.js.', 97000),
('CP05', 'WD', 'Back-End Developer', 'Building the server-side logic, database, and APIs used by the front-end. Typically using Node.js, Python (Django), or PHP.', 110000),
('CP06', 'WD', 'Web Developer', 'Developing and maintaining websites in general, can include both front-end and back-end tasks.', 98000),
('CP07', 'WD', 'Full Stack Developer', 'Proficient in both front-end and back-end. Able to build web applications from start to finish.', 145000),
('CP08', 'DA', 'Data Analyst', 'Collect, process, and analyze data to produce reports and business insights. Use Excel, SQL, and visualization tools such as Tableau.', 75000),
('CP09', 'AD', 'Bussiness Intelligence Analyst', 'Focus on business data analysis and reporting to assist strategic decision making.', 95000),
('CP10', 'AD', 'Data Scientist', 'Using statistics and machine learning to predict trends and behavior. Using Python, R, and big data tools.', 130000),
('CP11', 'AD', 'Data Engineer', 'Building and maintaining data pipeline systems, databases, and big data infrastructure.', 140000),
('CP12', 'PL', 'Software Engineer', 'A Software Engineer designs, develops, tests, and maintains software applications or systems. They use programming languages (like Java, Python, or C++) and follow software engineering principles to create solutions for user needs. Their work can involve anything from mobile apps to desktop software to cloud-based platforms.', 120000),
('CP13', 'PL', 'System Programmer', 'A System Programmer focuses on developing and maintaining system-level software such as operating systems, compilers, device drivers, and firmware. They often work closely with hardware and require deep knowledge of how systems operate at a low level.', 130000),
('CP14', 'PL', 'Embedded System Engineer', 'An Embedded System Engineer works on software that runs on specialized hardware (like microcontrollers or sensors). These systems are often found in cars, medical devices, home appliances, and IoT devices. Their code must be efficient and reliable, given the hardware constraints.', 125000),
('CP15', 'PL', 'Machine Learning Engineer', 'A Machine Learning Engineer builds systems that can learn from and make decisions based on data. They develop algorithms and models that are used in applications like recommendation systems, natural language processing, and image recognition.', 150000),
('CP16', 'VD', 'Graphic Designer', 'Designing visual elements such as logos, posters, infographics for print and digital media.', 70000),
('CP17', 'VD', 'UI/UX Designer', 'Design efficient, intuitive, and engaging user interfaces and experiences. Involves user research and prototyping.', 100000),
('CP18', 'VD', 'Product Designer', 'Focus on digital product design from a functional and aesthetic perspective, including user research and design iteration.', 110000),
('CP19', 'VD', 'Art Director', '	Memimpin tim kreatif dalam pengembangan konsep visual, brand identity, dan kampanye desain.', 120000),
('CP20', 'VE', 'Video Editor', 'Edit raw video footage into final content. Adjust tempo, transitions, audio, and effects.', 72000),
('CP21', 'VE', 'Motion Graphics Designer', 'Create graphic animations for advertisements, branding videos, or interactive content. Using Adobe After Effects, Blender, etc.', 95000),
('CP22', 'VE', 'Post-Projuction Specialist', 'Manage post-production processes such as color grading, audio syncing, VFX, and final rendering.', 87000),
('CP23', 'VE', 'Video Producer', 'Responsible for planning and managing video projects, from idea to distribution.', 100000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `studentID` varchar(3) NOT NULL,
  `courseID` varchar(3) NOT NULL,
  PRIMARY KEY (`studentID`,`courseID`),
  KEY `courseID` (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `certification`
--

DROP TABLE IF EXISTS `certification`;
CREATE TABLE IF NOT EXISTS `certification` (
  `certificateID` varchar(5) DEFAULT NULL,
  `courseID` varchar(3) DEFAULT NULL,
  `certificateName` varchar(255) DEFAULT NULL,
  `issuer` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  KEY `courseID` (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `certification`
--

INSERT INTO `certification` (`certificateID`, `courseID`, `certificateName`, `issuer`, `image`) VALUES
('CE001', 'C02', 'Python Master Certification', 'Harvard University', 'CE001_certificate.png'),
('CE002', 'C01', 'Flutter Mobile App Development Certification', 'Harvard University', 'CE002_certificate.png'),
('CE003', 'C03', 'C Programming Fundamentals Certification', 'Harvard University', 'CE003_certificate.png'),
('CE004', 'C04', 'Figma UI Design Certification', 'Harvard University', 'CE004_certificate.png'),
('CE005', 'C05', '3D Visual Effects in Premiere Pro Certification', 'Harvard University', 'CE005_certificate.png'),
('CE006', 'C06', 'Advanced Flutter App Animation Certification', 'Harvard University', 'CE006_certificate.png'),
('CE007', 'C07', 'HTML and CSS Foundations Certification', 'Harvard University', 'CE007_certificate.png'),
('CE008', 'C08', 'Full Stack Web Developer Certification', 'Harvard University', 'CE008_certificate.png'),
('CE009', 'C09', 'Data Dashboard with SQL & Excel Certification', 'Harvard University', 'CE009_certificate.png'),
('CE010', 'C10', 'C Programming Projects Certification', 'Harvard University', 'CE010_certificate.png'),
('CE011', 'C11', 'Modern UI Design with Figma Certification', 'Harvard University', 'CE011_certificate.png'),
('CE012', 'C12', 'YouTube Video Editing Certification', 'Harvard University', 'CE012_certificate.png'),
('CE013', 'C18', 'Davinci Resolve Master', 'Harvard University', 'CE012_certificate.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `course`
--

DROP TABLE IF EXISTS `course`;
CREATE TABLE IF NOT EXISTS `course` (
  `courseID` varchar(3) NOT NULL,
  `courseCatID` varchar(2) DEFAULT NULL,
  `lecturerID` varchar(5) DEFAULT NULL,
  `courseTitle` varchar(255) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `courseDescription` text,
  `price` int DEFAULT NULL,
  `courseThumbnail` text,
  PRIMARY KEY (`courseID`),
  KEY `courseCatID` (`courseCatID`),
  KEY `lecturerID` (`lecturerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `course`
--

INSERT INTO `course` (`courseID`, `courseCatID`, `lecturerID`, `courseTitle`, `level`, `courseDescription`, `price`, `courseThumbnail`) VALUES
('C01', 'AD', 'LEC01', 'Building Mobile E-Commerce Application Using Flutter', 'Beginner', 'This course will helps you out to build mobile e-commerce application, this course is very suitable for beginners who want to pursue a career in the field of application development.', 135900, 'C01.png'),
('C02', 'PL', 'LEC01', 'Full Course Python Programming Language From Beginner', 'Beginner', 'Do you want to start learning python programming language? then this course is perfect for you beginners to understand and apply how python programming language works, we will provide a full explanation that is easy for beginners to understand.', 129500, 'C02.png'),
('C03', 'PL', 'LEC01', 'Learn C Programming Language From Basic Fundamentals', 'Beginner', 'This course is a full package for everything you need to learn about C programming language, if you are beginner this course is completely for you because we guarantee that you will be understand all about this programming language as soon as u completing this course. Come and checkout now', 215500, 'C03.png'),
('C04', 'VD', 'LEC01', 'Figma Tutorial for Beginners - Complete Course 2025', 'Beginner', 'In this Figma course, we will learn every feature from scratch. This course is perfect for beginners who want to learn Figma and become professional UI designers. We will cover all features of Figma, including Auto-layout, Components,  Component Properties like Instance swap, Prototyping, Boolean operations, Figma animations, and more.', 217800, 'C04.png'),
('C05', 'VE', 'LEC01', 'Making 3D Visual Effect In Premiere Pro Full Course', 'Intermediate', 'Learn how to create stunning 3D visual effects step-by-step using Premiere Pro. Perfect for creative editors looking to enhance their projects.', 118000, 'C05.png'),
('C06', 'AD', 'LEC01', 'Building Mobile Application Using Flutter and Javascript Animation', 'Advanced', 'In this course, you’ll be guided through the process of building a complete mobile app with Flutter, enhanced with engaging animations in JavaScript.', 128000, 'C06.png'),
('C07', 'WD', 'LEC01', 'HTML And CSS Full Course From Zero Knowledge', 'Beginner', 'Never written a line of code before? Don’t worry—this course starts from zero and helps you master the foundations of HTML and CSS with real projects.', 230000, 'C07.png'),
('C08', 'WD', 'LEC01', 'Full Stack Web Developing Clearly Explained', 'Advanced', 'A well-explained journey through full stack web development. You’ll learn front-end and back-end with easy-to-follow examples and no fluff.', 219000, 'C08.png'),
('C09', 'DA', 'LEC01', 'Creating Data Dashboard with SQL and Excel', 'Beginner', 'Build dynamic dashboards from scratch using SQL queries and Excel tricks. No prior data experience needed.', 132000, 'C09.png'),
('C10', 'PL', 'LEC01', 'C Programming From Scratch To Advanced With Projects', 'Beginner', 'Understand C programming in-depth with hands-on projects and solid fundamentals. Ideal for absolute beginners and intermediate learners.', 121999, 'C10.png'),
('C11', 'VD', 'LEC01', 'Designing Modern UI with Figma For Real Projects', 'Advanced', 'This course teaches you practical Figma skills for designing modern user interfaces like the pros. Great for aspiring designers.', 207500, 'C11.png'),
('C12', 'VE', 'LEC01', 'Video Editing For YouTube Content Creators', 'Advanced', 'Want to create professional YouTube videos? Learn how to edit, cut, and produce great-looking content using simple tools and techniques.', 220000, 'C12.png'),
('C13', 'WD', 'LEC01', 'Building REST API With Node.js and Express', 'Intermediate', 'You’ll learn how to create a real RESTful API using Node.js and Express with proper structure and clear explanation for beginners.', 125000, 'C13.png'),
('C14', 'DA', 'LEC01', 'Advanced Excel Formulas and Data Manipulation', 'Beginner', 'Master powerful Excel formulas and data manipulation techniques that save hours and improve accuracy in your work.', 113500, 'C14.png'),
('C15', 'WD', 'LEC01', 'Intro to React.js For Frontend Web Development', 'Beginner', 'React.js is one of the most popular frameworks today. In this course, you’ll learn how to build responsive frontend applications using React step by step.', 123500, 'C15.png'),
('C16', 'DA', 'LEC01', 'Mastering Data Cleaning with Python Pandas', 'Advanced', 'Get your datasets clean and analysis-ready! This course will guide you through the essential techniques of cleaning messy data using Python\'s Pandas library.', 140000, 'C16.png'),
('C17', 'VD', 'LEC01', 'How To Design Engaging Thumbnails in Photoshop', 'Intermediate', 'Discover practical tips and tricks to make your YouTube thumbnails pop using Photoshop. No previous design experience needed.', 99999, 'C17.png'),
('C18', 'VE', 'LEC01', 'Complete Video Editing Workflow in DaVinci Resolve', 'Beginner', 'From importing footage to final export, learn the full editing workflow in DaVinci Resolve with hands-on guidance.', 290000, 'C18.png'),
('C19', 'VE', 'LEC01', 'Learn Git and GitHub For Real-World Collaboration', 'Advanced', 'Understand how to use Git and GitHub effectively in real-life team environments. You\'ll learn branching, merging, pull requests, and more.', 145000, 'C19.png'),
('C20', 'AD', 'LEC01', 'Developing Android Apps Using Kotlin From Scratch', 'Intermediate', 'This Kotlin-based course walks you through every step of creating Android apps, ideal for those looking to enter mobile development.', 190000, 'C20.png'),
('C21', 'AD', 'LEC01', 'Complete Guide to REST API Testing With Postman', 'Beginner', 'Master the basics and beyond of testing APIs using Postman. Learn how to write test scripts, organize collections, and automate your workflow.', 175000, 'C21.png'),
('C22', 'VD', 'LEC01', 'Design Stunning Website Layouts in Adobe XD', 'Advanced', 'In this hands-on course, you\'ll create beautiful web layouts using Adobe XD, while learning design principles and wireframing strategies.', 150000, 'C22.png'),
('C23', 'VE', 'LEC01', 'Editing Cinematic Travel Videos with Final Cut Pro', 'Intermediate', 'Turn your travel footage into cinematic experiences using Final Cut Pro. Great for vloggers, travelers, and aspiring filmmakers.', 280000, 'C23.png'),
('C24', 'DA', 'LEC01', 'Learn SQL Joins, Subqueries and Aggregates Like a Pro', 'Beginner', 'Master the key SQL operations including joins, subqueries, and aggregate functions to level up your data analysis skills.', 95000, 'C24.png'),
('C25', 'WD', 'LEC01', 'Building Interactive Web Pages with JavaScript DOM', 'Beginner', 'Learn how to make your web pages dynamic and interactive using JavaScript and the Document Object Model. A must-know for frontend developers.', 120000, 'C25.png'),
('C26', 'VD', 'LEC01', 'Create Motion Graphics for Social Media in After Effects', 'Advanced', 'This course teaches you how to design smooth and engaging motion graphics in Adobe After Effects for Instagram, TikTok, and YouTube.', 200000, 'C26.png'),
('C27', 'DA', 'LEC01', 'Master Data Visualization Using Tableau and Excel', 'Intermediate', 'Turn raw data into meaningful insights with powerful visualizations in Tableau and Excel. No coding required.', 150000, 'C27.png'),
('C28', 'WD', 'LEC01', 'Build A Weather App Using API and JavaScript', 'Beginner', 'A practical project course where you’ll learn how to fetch real-time data from weather APIs and display it using HTML, CSS, and JavaScript.', 135000, 'C28.png'),
('C29', 'VE', 'LEC01', 'Color Grading and Correction in Adobe Premiere Pro', 'Advanced', 'Get cinematic looks for your videos by mastering color correction and grading in Premiere Pro. Includes real-world footage and examples.', 95000, 'C29.png'),
('C30', 'PL', 'LEC01', 'Python Scripting for Automation Beginners', 'Intermediate', 'Automate boring tasks on your computer using simple Python scripts. A beginner-friendly course for office workers, students, or curious learners.', 89000, 'C30.png'),
('C31', 'VD', 'LEC01', 'Design and Animate Infographics in Adobe Illustrator', 'Beginner', 'Want to make professional infographics? Learn to design and animate them effectively with Adobe Illustrator and After Effects.', 85000, 'C31.png'),
('C32', 'WD', 'LEC01', 'Build a Portfolio Website With HTML, CSS, and Bootstrap', 'Advanced', 'Show off your work online by building your own portfolio website. This course walks you through it all step by step.', 125000, 'C32.png'),
('C33', 'PL', 'LEC01', 'Advanced MongoDB for Backend Developers', 'Intermediate', 'Take your backend development skills to the next level by learning advanced MongoDB queries, aggregation pipelines, and schema design.', 180000, 'C33.png'),
('C34', 'VD', 'LEC01', 'Make Engaging YouTube Intros Using Canva and CapCut', 'Beginner', 'Use free tools like Canva and CapCut to create cool, animated intros for your YouTube videos — fast and easy.', 199000, 'C34.png'),
('C35', 'PL', 'LEC01', 'Get In Touch With Javascript Programming Language', 'Beginner', 'In this course you will learn all about the javascript programming language - make sure you ready for learning new skills on this course for your programming passion', 87000, 'C35.png'),
('C37', 'PL', 'LEC01', 'Java Programming Essentials for Absolute Beginners', 'Beginner', 'A complete guide to learning Java programming including object-oriented concepts, syntax, and best practices.', 145500, 'C37.png'),
('C38', 'PL', 'LEC01', 'Learn C++ Programming Step-by-Step', 'Beginner', 'Understand C++ fundamentals, object-oriented design, and get started with real-world projects.', 128000, 'C38.png'),
('C39', 'PL', 'LEC01', 'PHP for Beginners: Build Dynamic Websites', 'Beginner', 'This course walks you through PHP fundamentals and helps you create interactive and dynamic web applications.', 275000, 'C39.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `coursecategory`
--

DROP TABLE IF EXISTS `coursecategory`;
CREATE TABLE IF NOT EXISTS `coursecategory` (
  `courseCatID` varchar(2) NOT NULL,
  `courseCat` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`courseCatID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `coursecategory`
--

INSERT INTO `coursecategory` (`courseCatID`, `courseCat`) VALUES
('AD', 'Application Development'),
('DA', 'Data Analytics'),
('PL', 'Programming Languages'),
('VD', 'Visual Design'),
('VE', 'Video Editing'),
('WD', 'Web Development');

-- --------------------------------------------------------

--
-- Struktur dari tabel `coursereview`
--

DROP TABLE IF EXISTS `coursereview`;
CREATE TABLE IF NOT EXISTS `coursereview` (
  `studentID` varchar(3) DEFAULT NULL,
  `courseID` varchar(3) DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `review` text,
  `reviewDate` date DEFAULT NULL,
  KEY `studentID` (`studentID`),
  KEY `courseID` (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `coursereview`
--

INSERT INTO `coursereview` (`studentID`, `courseID`, `rating`, `review`, `reviewDate`) VALUES
('S02', 'C04', 4, 'The Figma course covers all the basics and is great for beginners.', '2025-02-21'),
('S02', 'C08', 5, 'The full stack web development course gave me a solid foundation.', '2025-06-09'),
('S02', 'C09', 4, 'SQL and Excel dashboard lessons are very practical and easy to apply.', '2025-12-09'),
('S02', 'C10', 5, 'C programming is explained in detail, perfect for those new to coding.', '2025-11-01'),
('S02', 'C11', 3, 'Modern UI design with Figma is very useful for real projects.', '2025-02-09'),
('S02', 'C14', 2, 'Advanced Excel techniques are very helpful for my job.', '2025-06-09'),
('S02', 'C15', 3, 'React.js course is a great introduction to frontend development.', '2025-12-12'),
('S02', 'C01', 5, 'Building mobile apps with Flutter is now easier.', '2025-04-20'),
('S02', 'C02', 4, 'Python scripting is very helpful for automation.', '2025-05-29'),
('S02', 'C03', 5, 'C programming course is very comprehensive.', '2025-08-09'),
('S02', 'C05', 3, 'Premiere Pro effects are explained in detail.', '2025-06-09'),
('S02', 'C06', 3, 'Flutter and JavaScript animation integration is great.', '2025-01-09'),
('S02', 'C07', 4, 'HTML & CSS course is perfect for beginners.', '2025-06-09'),
('S02', 'C12', 4, 'Editing YouTube videos is now much easier.', '2025-12-09'),
('S03', 'C04', 5, 'Figma for beginners is well structured and easy to follow.', '2025-08-29'),
('S03', 'C08', 4, 'Full stack web development is explained in a clear and concise way.', '2025-10-09'),
('S03', 'C09', 5, 'The dashboard course helped me visualize data more effectively.', '2025-06-09'),
('S03', 'C10', 5, 'C programming projects are challenging but rewarding.', '2025-06-09'),
('S03', 'C11', 4, 'UI design with Figma is now much easier for me.', '2025-05-12'),
('S03', 'C14', 4, 'Excel data manipulation is very useful for my daily tasks.', '2025-05-29'),
('S03', 'C15', 5, 'React.js lessons are easy to follow and very practical.', '2025-09-09'),
('S03', 'C01', 5, 'Flutter e-commerce app course is very practical.', '2025-07-09'),
('S03', 'C02', 4, 'Python programming is taught step by step.', '2025-11-09'),
('S03', 'C03', 4, 'C programming basics are easy to follow.', '2025-11-09'),
('S03', 'C05', 4, 'Premiere Pro 3D effects are very useful.', '2025-06-09'),
('S03', 'C06', 5, 'Flutter animation course is very inspiring.', '2025-09-09'),
('S03', 'C07', 3, 'HTML and CSS course is well structured.', '2025-01-09'),
('S03', 'C12', 4, 'YouTube editing techniques are very practical.', '2025-06-02'),
('S04', 'C08', 5, 'The full stack web development course is very comprehensive.', '2025-01-30'),
('S04', 'C09', 4, 'SQL and Excel dashboard course is very practical and easy to implement.', '2025-01-02'),
('S04', 'C10', 3, 'C programming from scratch is perfect for absolute beginners.', '2025-07-19'),
('S04', 'C11', 4, 'Modern UI design with Figma is very helpful for my design projects.', '2025-06-09'),
('S04', 'C14', 3, 'Advanced Excel formulas are a must-have for data analysts.', '2025-06-09'),
('S04', 'C15', 4, 'React.js course is a great way to start learning frontend development.', '2025-07-09'),
('S04', 'C01', 5, 'Flutter course is easy to understand for beginners.', '2025-06-09'),
('S04', 'C02', 4, 'Python scripting is very useful for daily tasks.', '2025-02-03'),
('S04', 'C03', 5, 'C programming course is very detailed.', '2025-04-05'),
('S04', 'C05', 4, 'Premiere Pro course improved my editing skills.', '2025-06-09'),
('S04', 'C06', 5, 'Flutter animation integration is explained clearly.', '2025-05-02'),
('S04', 'C07', 4, 'HTML & CSS course is very beginner-friendly.', '2025-06-09'),
('S04', 'C12', 4, 'YouTube content editing is now much easier.', '2025-06-09'),
('S05', 'C04', 5, 'Figma course is very detailed and easy to follow.', '2025-06-09'),
('S05', 'C08', 4, 'Full stack web development is explained with real-world examples.', '2025-06-09'),
('S05', 'C09', 5, 'The dashboard course is very practical and useful for my work.', '2025-06-09'),
('S05', 'C10', 4, 'C programming course is well organized and easy to understand.', '2025-08-29'),
('S05', 'C11', 4, 'Modern UI design with Figma is very inspiring.', '2025-02-14'),
('S05', 'C14', 4, 'Excel data manipulation techniques are very helpful.', '2025-12-21'),
('S05', 'C15', 5, 'React.js course is the best introduction to frontend development.', '2025-04-19'),
('S05', 'C01', 4, 'Flutter mobile app course is very comprehensive.', '2025-02-01'),
('S05', 'C02', 4, 'Python programming is taught in a simple way.', '2025-02-09'),
('S05', 'C03', 4, 'C programming course is very practical.', '2025-03-30'),
('S05', 'C05', 4, 'Premiere Pro course is great for video editors.', '2025-06-09'),
('S05', 'C06', 5, 'Flutter animation course is very helpful.', '2025-06-09'),
('S05', 'C07', 4, 'HTML and CSS course is easy to follow.', '2025-01-29'),
('S05', 'C12', 5, 'YouTube video editing course is very detailed.', '2025-09-20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `discount`
--

DROP TABLE IF EXISTS `discount`;
CREATE TABLE IF NOT EXISTS `discount` (
  `courseID` varchar(3) NOT NULL,
  `discountPercent` int DEFAULT NULL,
  PRIMARY KEY (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `discount`
--

INSERT INTO `discount` (`courseID`, `discountPercent`) VALUES
('C01', 75),
('C02', 50),
('C03', 55),
('C04', 45),
('C05', 55),
('C06', 78),
('C07', 80),
('C08', 85),
('C09', 50),
('C10', 70),
('C11', 75),
('C12', 60),
('C13', 65),
('C14', 50),
('C15', 55),
('C16', 45),
('C17', 75),
('C18', 55),
('C19', 48),
('C20', 70),
('C21', 40),
('C22', 40),
('C23', 50),
('C24', 45),
('C25', 80),
('C26', 58),
('C27', 55),
('C28', 45),
('C29', 70),
('C30', 80),
('C31', 90),
('C32', 65),
('C33', 75),
('C34', 55),
('C35', 50),
('C37', 65),
('C38', 70),
('C39', 80);

-- --------------------------------------------------------

--
-- Struktur dari tabel `enrolled`
--

DROP TABLE IF EXISTS `enrolled`;
CREATE TABLE IF NOT EXISTS `enrolled` (
  `studentID` varchar(3) NOT NULL,
  `courseID` varchar(3) NOT NULL,
  `enrollmentDate` date DEFAULT NULL,
  PRIMARY KEY (`studentID`,`courseID`),
  KEY `courseID` (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `enrolled`
--

INSERT INTO `enrolled` (`studentID`, `courseID`, `enrollmentDate`) VALUES
('S01', 'C05', '2025-06-10'),
('S01', 'C18', '2025-06-10'),
('S01', 'C20', '2025-06-10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `exercise`
--

DROP TABLE IF EXISTS `exercise`;
CREATE TABLE IF NOT EXISTS `exercise` (
  `exerciseID` varchar(3) NOT NULL,
  `sessionID` varchar(4) DEFAULT NULL,
  `question` text,
  PRIMARY KEY (`exerciseID`),
  KEY `sessionID` (`sessionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `exercise`
--

INSERT INTO `exercise` (`exerciseID`, `sessionID`, `question`) VALUES
('E01', 'S005', 'Now that you have learned how to apply 3D visual effects using the Basic 3D feature in Adobe Premiere Pro, explain the steps you need to take to make a video clip appear to be rotating in 3D space. Also, explain how using parameters such as Swivel and Tilt affect the visual appearance, and under what conditions would you use the Distance to Image feature?'),
('E02', 'S011', 'In this session, you learned how Python handles various data types, including integers and strings. For example, if you want to add the value of the number 5 and a string that contains \"5\", how can you write the correct Python code so that it does not cause errors? Also explain why it is important to understand data type conversions in the process of writing programs.'),
('E03', 'S017', 'After watching the session on displaying a product list in a Flutter-based e-commerce application, you are asked to explain what widget is used to display items in a scrollable manner. Why is ListView the right choice for this case? Also explain how ListView.builder works to optimize application performance when displaying lots of data at once.'),
('E04', 'S023', 'Setelah mempelajari fitur-fitur utama Figma, buatlah daftar langkah-langkah untuk membuat sebuah tombol (button) interaktif dengan Auto-layout dan Component. Jelaskan juga bagaimana cara menggunakan Instance swap dan kapan fitur ini bermanfaat dalam proses desain UI.'),
('E05', 'S029', 'Buatlah program C sederhana yang meminta input dua angka dari user, lalu menampilkan hasil penjumlahan, pengurangan, perkalian, dan pembagian dari kedua angka tersebut. Jelaskan juga bagaimana proses input dan output bekerja di bahasa C.'),
('E06', 'S035', 'Jelaskan langkah-langkah untuk mengintegrasikan animasi JavaScript ke dalam aplikasi Flutter. Apa manfaat utama menggunakan animasi dalam aplikasi mobile, dan bagaimana pengaruhnya terhadap user experience?'),
('E07', 'S041', 'Buatlah halaman HTML sederhana yang menampilkan biodata diri Anda (nama, foto, dan deskripsi singkat) dan atur tampilannya menggunakan CSS. Jelaskan bagaimana Anda mengatur layout dan warna pada halaman tersebut.'),
('E08', 'S047', 'Jelaskan perbedaan utama antara front-end dan back-end dalam pengembangan web. Berikan contoh teknologi yang digunakan pada masing-masing bagian dan bagaimana keduanya saling terhubung dalam aplikasi full stack.'),
('E09', 'S053', 'Buatlah query SQL sederhana untuk mengambil data penjualan dari tabel database, lalu visualisasikan hasilnya dalam bentuk grafik di Excel. Jelaskan langkah-langkah integrasi data dari SQL ke Excel.'),
('E10', 'S059', 'Buatlah program C yang dapat membaca dan menampilkan data dari file teks eksternal. Jelaskan bagaimana proses pembacaan file dilakukan di bahasa C dan bagaimana Anda menangani error jika file tidak ditemukan.'),
('E11', 'S065', 'Buatlah satu komponen UI reusable di Figma (misal: card atau tombol) dan jelaskan bagaimana cara mengatur variant serta kapan sebaiknya menggunakan Auto Layout.'),
('E12', 'S071', 'Jelaskan langkah-langkah dasar untuk memotong, menggabungkan, dan menambahkan transisi pada video menggunakan software editing pilihan Anda.'),
('E13', 'S077', 'Buatlah endpoint sederhana menggunakan Express yang dapat menerima data user (nama dan email) melalui metode POST dan menyimpannya ke dalam array. Sertakan contoh kode dan penjelasan singkat.'),
('E14', 'S083', 'Buatlah formula Excel untuk menghitung total penjualan berdasarkan kategori produk dan tampilkan hasilnya dalam bentuk tabel pivot. Jelaskan langkah-langkah pembuatannya.'),
('E15', 'S089', 'Jelaskan perbedaan antara props dan state di React.js. Berikan contoh kasus penggunaan masing-masing dan bagaimana keduanya mempengaruhi tampilan aplikasi.'),
('E16', 'S095', 'Given a CSV file with missing and duplicate values, write a Python script using Pandas to clean the data. Explain each step and why it is necessary.'),
('E17', 'S101', 'Design a YouTube thumbnail for a travel vlog using Photoshop. List the steps and explain your design choices.'),
('E18', 'S107', 'Edit a short video (30–60 seconds) in DaVinci Resolve, including at least one transition and a title. Describe your editing process.'),
('E19', 'S113', 'Create a new repository on GitHub, clone it locally, and make at least two commits. Explain each step and the commands used.'),
('E20', 'S119', 'Create a simple Android app in Kotlin that takes user input and displays a greeting message. Explain your code and UI design.'),
('E21', 'S125', 'Test an API endpoint using Postman and explain the steps to validate the response and automate the test.'),
('E22', 'S131', 'Buat wireframe sederhana untuk landing page website menggunakan Adobe XD dan jelaskan prosesnya.'),
('E23', 'S137', 'Edit a short travel video in Final Cut Pro, add a title and background music, and describe your editing process.'),
('E24', 'S143', 'Buat query SQL untuk menampilkan total penjualan per kategori dan jelaskan hasilnya.'),
('E25', 'S149', 'Buat halaman web sederhana yang menampilkan daftar produk dan tombol \"Tambah ke Keranjang\" menggunakan JavaScript DOM.'),
('E26', 'S155', 'Buat animasi teks sederhana untuk Instagram Story menggunakan After Effects dan jelaskan langkah-langkahnya.'),
('E27', 'S161', 'Buat visualisasi data penjualan bulanan menggunakan Tableau dan Excel, lalu jelaskan insight yang didapat.'),
('E28', 'S167', 'Buat aplikasi cuaca sederhana yang menampilkan data suhu dan cuaca dari API, lalu jelaskan proses fetch dan error handling-nya.'),
('E29', 'S173', 'Jelaskan perbedaan antara color correction dan color grading pada video editing. Berikan contoh penggunaannya.'),
('E30', 'S179', 'Buat script Python sederhana untuk meng-rename banyak file sekaligus di satu folder. Jelaskan langkah dan logikanya.'),
('E31', 'S185', 'Buat satu infographic sederhana di Illustrator yang menampilkan data statistik, lalu jelaskan proses desainnya.'),
('E32', 'S191', 'Buat halaman portfolio sederhana dengan Bootstrap yang responsif di mobile dan desktop. Jelaskan struktur layout-nya.'),
('E33', 'S197', 'Buat query MongoDB untuk mengambil data user dengan umur di atas 25 tahun dan sorting berdasarkan nama. Jelaskan query-nya.'),
('E34', 'S203', 'Buat desain intro YouTube sederhana di Canva, lalu jelaskan elemen desain yang digunakan.'),
('E35', 'S209', 'Buat fungsi JavaScript untuk menghitung rata-rata dari array angka. Jelaskan cara kerjanya.'),
('E36', 'S215', 'Buat program Java sederhana untuk menampilkan daftar nama mahasiswa dan nilai mereka. Jelaskan konsep OOP yang digunakan.'),
('E37', 'S221', 'Buat fungsi C++ untuk menghitung rata-rata nilai dari array. Jelaskan penggunaan pointer jika diperlukan.'),
('E38', 'S227', 'Buat form login sederhana dengan PHP dan validasi input. Jelaskan proses validasi dan keamanan dasarnya.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `exerciseattempt`
--

DROP TABLE IF EXISTS `exerciseattempt`;
CREATE TABLE IF NOT EXISTS `exerciseattempt` (
  `studentID` varchar(3) NOT NULL,
  `exerciseID` varchar(3) NOT NULL,
  `answer` text,
  `score` decimal(10,1) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`studentID`,`exerciseID`),
  KEY `exerciseID` (`exerciseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `exerciseattempt`
--

INSERT INTO `exerciseattempt` (`studentID`, `exerciseID`, `answer`, `score`, `status`) VALUES
('S01', 'E01', 'Using parameters such as Swivel and Tilt affects the viewing angle and spatial perception of the object or scene being observed.\r\n\r\nSwivel rotates the view horizontally, around a vertical axis — like turning your head left or right. This allows users to inspect the sides of a 3D object or to change the point of view without changing the position of the object itself.\r\n\r\nTilt rotates the view vertically, around a horizontal axis — similar to nodding your head up or down. This is useful when you want to look at the object from above, below, or adjust the vertical perspective.\r\n\r\nTogether, Swivel and Tilt help create a more natural and flexible way to examine complex objects or anatomical structures in 3D space, enhancing depth perception and understanding of spatial relationships.', 8.5, 'Checked'),
('S01', 'E18', 'I edited a short 45-second video in DaVinci Resolve that included a combination of travel clips, a title, background music, and a smooth transition between scenes.', 7.8, 'Checked');

-- --------------------------------------------------------

--
-- Struktur dari tabel `learningprogress`
--

DROP TABLE IF EXISTS `learningprogress`;
CREATE TABLE IF NOT EXISTS `learningprogress` (
  `studentID` varchar(3) NOT NULL,
  `sessionID` varchar(4) NOT NULL,
  `progressValue` int DEFAULT NULL,
  `sessionStatus` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`studentID`,`sessionID`),
  KEY `sessionID` (`sessionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `learningprogress`
--

INSERT INTO `learningprogress` (`studentID`, `sessionID`, `progressValue`, `sessionStatus`) VALUES
('S01', 'S115', 10, 'Passed'),
('S01', 'S116', 10, 'Passed'),
('S01', 'S117', 0, 'On Going'),
('S01', 'S118', 0, 'On Going'),
('S01', 'S119', 0, 'On Going'),
('S01', 'S120', 0, 'On Going');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lecturer`
--

DROP TABLE IF EXISTS `lecturer`;
CREATE TABLE IF NOT EXISTS `lecturer` (
  `lecturerID` varchar(5) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `lecturerName` varchar(255) DEFAULT NULL,
  `universityOrigin` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`lecturerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `lecturer`
--

INSERT INTO `lecturer` (`lecturerID`, `username`, `password`, `lecturerName`, `universityOrigin`, `image`) VALUES
('LEC01', 'einstein', '$2y$10$Khxdu.T/i3gt3T1VqGl8XOUXXxOP8tAQTOb2xXFVOrg99L.M7meRS', 'Albert Einstein', 'Harvard University', 'einstein.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lesson`
--

DROP TABLE IF EXISTS `lesson`;
CREATE TABLE IF NOT EXISTS `lesson` (
  `lessonID` varchar(4) NOT NULL,
  `sessionID` varchar(4) DEFAULT NULL,
  `lessonTitle` varchar(255) DEFAULT NULL,
  `videoURL` text,
  `description` text,
  PRIMARY KEY (`lessonID`),
  KEY `sessionID` (`sessionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `lesson`
--

INSERT INTO `lesson` (`lessonID`, `sessionID`, `lessonTitle`, `videoURL`, `description`) VALUES
('L001', 'S001', 'First Step - Making 3D Visual Effect In Premiere Pro Full Course', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque convallis quam eget justo mattis, eget lacinia nulla scelerisque. Proin tincidunt semper odio et dignissim. Vestibulum cursus nec ante non egestas. Nulla gravida, nulla vel elementum hendrerit, est libero vulputate urna, rhoncus tempus felis metus nec mauris. Nam risus eros, maximus fringilla eleifend non, consequat nec arcu. Integer mattis facilisis ligula ut porta. Aenean laoreet purus ut augue suscipit semper. Mauris risus nisi, viverra id massa sed, aliquam porta dolor.\r\n\r\nAliquam semper odio neque, a placerat lorem tincidunt sed. Etiam tempor laoreet justo, a rutrum lectus cursus ac. Donec non justo at nunc molestie consequat eu ac elit. Morbi non ligula et diam rhoncus aliquet. Nulla tempor sapien ut orci pharetra laoreet. Fusce congue quam felis, id faucibus ex convallis vitae. Fusce ornare velit quis ante eleifend lacinia. Proin tristique, augue sed commodo efficitur, augue quam sodales nisl, ut bibendum mauris odio et metus. Duis eu sapien vulputate erat lacinia viverra. Donec et tellus pharetra, interdum lacus in, congue lorem.'),
('L002', 'S002', 'Second Step - Making 3D Visual Effect In Premiere Pro Full Course', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque convallis quam eget justo mattis, eget lacinia nulla scelerisque. Proin tincidunt semper odio et dignissim. Vestibulum cursus nec ante non egestas. Nulla gravida, nulla vel elementum hendrerit, est libero vulputate urna, rhoncus tempus felis metus nec mauris. Nam risus eros, maximus fringilla eleifend non, consequat nec arcu. Integer mattis facilisis ligula ut porta. Aenean laoreet purus ut augue suscipit semper. Mauris risus nisi, viverra id massa sed, aliquam porta dolor.\r\n\r\nAliquam semper odio neque, a placerat lorem tincidunt sed. Etiam tempor laoreet justo, a rutrum lectus cursus ac. Donec non justo at nunc molestie consequat eu ac elit. Morbi non ligula et diam rhoncus aliquet. Nulla tempor sapien ut orci pharetra laoreet. Fusce congue quam felis, id faucibus ex convallis vitae. Fusce ornare velit quis ante eleifend lacinia. Proin tristique, augue sed commodo efficitur, augue quam sodales nisl, ut bibendum mauris odio et metus. Duis eu sapien vulputate erat lacinia viverra. Donec et tellus pharetra, interdum lacus in, congue lorem.'),
('L003', 'S003', 'Third Step - Making 3D Visual Effect In Premiere Pro Full Course', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque convallis quam eget justo mattis, eget lacinia nulla scelerisque. Proin tincidunt semper odio et dignissim. Vestibulum cursus nec ante non egestas. Nulla gravida, nulla vel elementum hendrerit, est libero vulputate urna, rhoncus tempus felis metus nec mauris. Nam risus eros, maximus fringilla eleifend non, consequat nec arcu. Integer mattis facilisis ligula ut porta. Aenean laoreet purus ut augue suscipit semper. Mauris risus nisi, viverra id massa sed, aliquam porta dolor.\r\n\r\nAliquam semper odio neque, a placerat lorem tincidunt sed. Etiam tempor laoreet justo, a rutrum lectus cursus ac. Donec non justo at nunc molestie consequat eu ac elit. Morbi non ligula et diam rhoncus aliquet. Nulla tempor sapien ut orci pharetra laoreet. Fusce congue quam felis, id faucibus ex convallis vitae. Fusce ornare velit quis ante eleifend lacinia. Proin tristique, augue sed commodo efficitur, augue quam sodales nisl, ut bibendum mauris odio et metus. Duis eu sapien vulputate erat lacinia viverra. Donec et tellus pharetra, interdum lacus in, congue lorem.'),
('L004', 'S004', 'Last Step - Making 3D Visual Effect In Premiere Pro Full Course', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque convallis quam eget justo mattis, eget lacinia nulla scelerisque. Proin tincidunt semper odio et dignissim. Vestibulum cursus nec ante non egestas. Nulla gravida, nulla vel elementum hendrerit, est libero vulputate urna, rhoncus tempus felis metus nec mauris. Nam risus eros, maximus fringilla eleifend non, consequat nec arcu. Integer mattis facilisis ligula ut porta. Aenean laoreet purus ut augue suscipit semper. Mauris risus nisi, viverra id massa sed, aliquam porta dolor.\r\n\r\nAliquam semper odio neque, a placerat lorem tincidunt sed. Etiam tempor laoreet justo, a rutrum lectus cursus ac. Donec non justo at nunc molestie consequat eu ac elit. Morbi non ligula et diam rhoncus aliquet. Nulla tempor sapien ut orci pharetra laoreet. Fusce congue quam felis, id faucibus ex convallis vitae. Fusce ornare velit quis ante eleifend lacinia. Proin tristique, augue sed commodo efficitur, augue quam sodales nisl, ut bibendum mauris odio et metus. Duis eu sapien vulputate erat lacinia viverra. Donec et tellus pharetra, interdum lacus in, congue lorem.'),
('L005', 'S007', 'First Step - Full Course Python Programming Language From Beginner', 'https://drive.google.com/file/d/1vXV9DBmkJawZTiPRF4ZVhe-YlIRUtnBM/preview', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque convallis quam eget justo mattis, eget lacinia nulla scelerisque. Proin tincidunt semper odio et dignissim. Vestibulum cursus nec ante non egestas. Nulla gravida, nulla vel elementum hendrerit, est libero vulputate urna, rhoncus tempus felis metus nec mauris. Nam risus eros, maximus fringilla eleifend non, consequat nec arcu. Integer mattis facilisis ligula ut porta. Aenean laoreet purus ut augue suscipit semper. Mauris risus nisi, viverra id massa sed, aliquam porta dolor.\r\n\r\nAliquam semper odio neque, a placerat lorem tincidunt sed. Etiam tempor laoreet justo, a rutrum lectus cursus ac. Donec non justo at nunc molestie consequat eu ac elit. Morbi non ligula et diam rhoncus aliquet. Nulla tempor sapien ut orci pharetra laoreet. Fusce congue quam felis, id faucibus ex convallis vitae. Fusce ornare velit quis ante eleifend lacinia. Proin tristique, augue sed commodo efficitur, augue quam sodales nisl, ut bibendum mauris odio et metus. Duis eu sapien vulputate erat lacinia viverra. Donec et tellus pharetra, interdum lacus in, congue lorem.'),
('L006', 'S008', 'Second Step - Full Course Python Programming Language From Beginner', 'https://drive.google.com/file/d/1vXV9DBmkJawZTiPRF4ZVhe-YlIRUtnBM/preview', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque convallis quam eget justo mattis, eget lacinia nulla scelerisque. Proin tincidunt semper odio et dignissim. Vestibulum cursus nec ante non egestas. Nulla gravida, nulla vel elementum hendrerit, est libero vulputate urna, rhoncus tempus felis metus nec mauris. Nam risus eros, maximus fringilla eleifend non, consequat nec arcu. Integer mattis facilisis ligula ut porta. Aenean laoreet purus ut augue suscipit semper. Mauris risus nisi, viverra id massa sed, aliquam porta dolor.\r\n\r\nAliquam semper odio neque, a placerat lorem tincidunt sed. Etiam tempor laoreet justo, a rutrum lectus cursus ac. Donec non justo at nunc molestie consequat eu ac elit. Morbi non ligula et diam rhoncus aliquet. Nulla tempor sapien ut orci pharetra laoreet. Fusce congue quam felis, id faucibus ex convallis vitae. Fusce ornare velit quis ante eleifend lacinia. Proin tristique, augue sed commodo efficitur, augue quam sodales nisl, ut bibendum mauris odio et metus. Duis eu sapien vulputate erat lacinia viverra. Donec et tellus pharetra, interdum lacus in, congue lorem.'),
('L007', 'S009', 'Third Step - Full Course Python Programming Language From Beginner', 'https://drive.google.com/file/d/1vXV9DBmkJawZTiPRF4ZVhe-YlIRUtnBM/preview', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque convallis quam eget justo mattis, eget lacinia nulla scelerisque. Proin tincidunt semper odio et dignissim. Vestibulum cursus nec ante non egestas. Nulla gravida, nulla vel elementum hendrerit, est libero vulputate urna, rhoncus tempus felis metus nec mauris. Nam risus eros, maximus fringilla eleifend non, consequat nec arcu. Integer mattis facilisis ligula ut porta. Aenean laoreet purus ut augue suscipit semper. Mauris risus nisi, viverra id massa sed, aliquam porta dolor.\r\n\r\nAliquam semper odio neque, a placerat lorem tincidunt sed. Etiam tempor laoreet justo, a rutrum lectus cursus ac. Donec non justo at nunc molestie consequat eu ac elit. Morbi non ligula et diam rhoncus aliquet. Nulla tempor sapien ut orci pharetra laoreet. Fusce congue quam felis, id faucibus ex convallis vitae. Fusce ornare velit quis ante eleifend lacinia. Proin tristique, augue sed commodo efficitur, augue quam sodales nisl, ut bibendum mauris odio et metus. Duis eu sapien vulputate erat lacinia viverra. Donec et tellus pharetra, interdum lacus in, congue lorem.'),
('L008', 'S010', 'Last Step - Full Course Python Programming Language From Beginner', 'https://drive.google.com/file/d/1vXV9DBmkJawZTiPRF4ZVhe-YlIRUtnBM/preview', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque convallis quam eget justo mattis, eget lacinia nulla scelerisque. Proin tincidunt semper odio et dignissim. Vestibulum cursus nec ante non egestas. Nulla gravida, nulla vel elementum hendrerit, est libero vulputate urna, rhoncus tempus felis metus nec mauris. Nam risus eros, maximus fringilla eleifend non, consequat nec arcu. Integer mattis facilisis ligula ut porta. Aenean laoreet purus ut augue suscipit semper. Mauris risus nisi, viverra id massa sed, aliquam porta dolor.\r\n\r\nAliquam semper odio neque, a placerat lorem tincidunt sed. Etiam tempor laoreet justo, a rutrum lectus cursus ac. Donec non justo at nunc molestie consequat eu ac elit. Morbi non ligula et diam rhoncus aliquet. Nulla tempor sapien ut orci pharetra laoreet. Fusce congue quam felis, id faucibus ex convallis vitae. Fusce ornare velit quis ante eleifend lacinia. Proin tristique, augue sed commodo efficitur, augue quam sodales nisl, ut bibendum mauris odio et metus. Duis eu sapien vulputate erat lacinia viverra. Donec et tellus pharetra, interdum lacus in, congue lorem.'),
('L009', 'S013', 'First Step - Building Mobile E-Commerce Application Using Flutter', 'https://drive.google.com/file/d/1wSqJOLNGJZjSz7rQAVyFwNCwROHIT0Ou/preview', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque convallis quam eget justo mattis, eget lacinia nulla scelerisque. Proin tincidunt semper odio et dignissim. Vestibulum cursus nec ante non egestas. Nulla gravida, nulla vel elementum hendrerit, est libero vulputate urna, rhoncus tempus felis metus nec mauris. Nam risus eros, maximus fringilla eleifend non, consequat nec arcu. Integer mattis facilisis ligula ut porta. Aenean laoreet purus ut augue suscipit semper. Mauris risus nisi, viverra id massa sed, aliquam porta dolor.\r\n\r\nAliquam semper odio neque, a placerat lorem tincidunt sed. Etiam tempor laoreet justo, a rutrum lectus cursus ac. Donec non justo at nunc molestie consequat eu ac elit. Morbi non ligula et diam rhoncus aliquet. Nulla tempor sapien ut orci pharetra laoreet. Fusce congue quam felis, id faucibus ex convallis vitae. Fusce ornare velit quis ante eleifend lacinia. Proin tristique, augue sed commodo efficitur, augue quam sodales nisl, ut bibendum mauris odio et metus. Duis eu sapien vulputate erat lacinia viverra. Donec et tellus pharetra, interdum lacus in, congue lorem.'),
('L010', 'S014', 'Second Step - Building Mobile E-Commerce Application Using Flutter', 'https://drive.google.com/file/d/1wSqJOLNGJZjSz7rQAVyFwNCwROHIT0Ou/preview', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque convallis quam eget justo mattis, eget lacinia nulla scelerisque. Proin tincidunt semper odio et dignissim. Vestibulum cursus nec ante non egestas. Nulla gravida, nulla vel elementum hendrerit, est libero vulputate urna, rhoncus tempus felis metus nec mauris. Nam risus eros, maximus fringilla eleifend non, consequat nec arcu. Integer mattis facilisis ligula ut porta. Aenean laoreet purus ut augue suscipit semper. Mauris risus nisi, viverra id massa sed, aliquam porta dolor.\r\n\r\nAliquam semper odio neque, a placerat lorem tincidunt sed. Etiam tempor laoreet justo, a rutrum lectus cursus ac. Donec non justo at nunc molestie consequat eu ac elit. Morbi non ligula et diam rhoncus aliquet. Nulla tempor sapien ut orci pharetra laoreet. Fusce congue quam felis, id faucibus ex convallis vitae. Fusce ornare velit quis ante eleifend lacinia. Proin tristique, augue sed commodo efficitur, augue quam sodales nisl, ut bibendum mauris odio et metus. Duis eu sapien vulputate erat lacinia viverra. Donec et tellus pharetra, interdum lacus in, congue lorem.'),
('L011', 'S015', 'Third Step - Building Mobile E-Commerce Application Using Flutter', 'https://drive.google.com/file/d/1wSqJOLNGJZjSz7rQAVyFwNCwROHIT0Ou/preview', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque convallis quam eget justo mattis, eget lacinia nulla scelerisque. Proin tincidunt semper odio et dignissim. Vestibulum cursus nec ante non egestas. Nulla gravida, nulla vel elementum hendrerit, est libero vulputate urna, rhoncus tempus felis metus nec mauris. Nam risus eros, maximus fringilla eleifend non, consequat nec arcu. Integer mattis facilisis ligula ut porta. Aenean laoreet purus ut augue suscipit semper. Mauris risus nisi, viverra id massa sed, aliquam porta dolor.\r\n\r\nAliquam semper odio neque, a placerat lorem tincidunt sed. Etiam tempor laoreet justo, a rutrum lectus cursus ac. Donec non justo at nunc molestie consequat eu ac elit. Morbi non ligula et diam rhoncus aliquet. Nulla tempor sapien ut orci pharetra laoreet. Fusce congue quam felis, id faucibus ex convallis vitae. Fusce ornare velit quis ante eleifend lacinia. Proin tristique, augue sed commodo efficitur, augue quam sodales nisl, ut bibendum mauris odio et metus. Duis eu sapien vulputate erat lacinia viverra. Donec et tellus pharetra, interdum lacus in, congue lorem.'),
('L012', 'S016', 'Last Step - Building Mobile E-Commerce Application Using Flutter', 'https://drive.google.com/file/d/1wSqJOLNGJZjSz7rQAVyFwNCwROHIT0Ou/preview', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque convallis quam eget justo mattis, eget lacinia nulla scelerisque. Proin tincidunt semper odio et dignissim. Vestibulum cursus nec ante non egestas. Nulla gravida, nulla vel elementum hendrerit, est libero vulputate urna, rhoncus tempus felis metus nec mauris. Nam risus eros, maximus fringilla eleifend non, consequat nec arcu. Integer mattis facilisis ligula ut porta. Aenean laoreet purus ut augue suscipit semper. Mauris risus nisi, viverra id massa sed, aliquam porta dolor.\r\n\r\nAliquam semper odio neque, a placerat lorem tincidunt sed. Etiam tempor laoreet justo, a rutrum lectus cursus ac. Donec non justo at nunc molestie consequat eu ac elit. Morbi non ligula et diam rhoncus aliquet. Nulla tempor sapien ut orci pharetra laoreet. Fusce congue quam felis, id faucibus ex convallis vitae. Fusce ornare velit quis ante eleifend lacinia. Proin tristique, augue sed commodo efficitur, augue quam sodales nisl, ut bibendum mauris odio et metus. Duis eu sapien vulputate erat lacinia viverra. Donec et tellus pharetra, interdum lacus in, congue lorem.'),
('L013', 'S019', 'First Step - Figma Tutorial for Beginners', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction and overview of Figma for beginners.'),
('L014', 'S020', 'Second Step - Figma Tutorial for Beginners', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learning Figma basic tools and workspace.'),
('L015', 'S021', 'Third Step - Figma Tutorial for Beginners', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Working with components and auto-layout in Figma.'),
('L016', 'S022', 'Last Step - Figma Tutorial for Beginners', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Prototyping and exporting designs in Figma.'),
('L017', 'S025', 'First Step - Learn C Programming Language From Basic Fundamentals', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to C programming and setting up your environment.'),
('L018', 'S026', 'Second Step - Learn C Programming Language From Basic Fundamentals', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Understanding variables, data types, and basic input/output in C.'),
('L019', 'S027', 'Third Step - Learn C Programming Language From Basic Fundamentals', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Control structures: if, else, loops, and functions in C.'),
('L020', 'S028', 'Last Step - Learn C Programming Language From Basic Fundamentals', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Working with arrays, pointers, and basic memory management.'),
('L021', 'S031', 'First Step - Building Mobile Application Using Flutter and Javascript Animation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to Flutter and integrating JavaScript animations.'),
('L022', 'S032', 'Second Step - Building Mobile Application Using Flutter and Javascript Animation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Setting up project structure and basic navigation in Flutter.'),
('L023', 'S033', 'Third Step - Building Mobile Application Using Flutter and Javascript Animation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Implementing interactive UI and connecting with JavaScript animation.'),
('L024', 'S034', 'Last Step - Building Mobile Application Using Flutter and Javascript Animation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Testing, debugging, and deploying your animated Flutter app.'),
('L025', 'S037', 'First Step - HTML And CSS Full Course From Zero Knowledge', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to HTML structure and basic tags.'),
('L026', 'S038', 'Second Step - HTML And CSS Full Course From Zero Knowledge', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Styling with CSS: selectors, properties, and values.'),
('L027', 'S039', 'Third Step - HTML And CSS Full Course From Zero Knowledge', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Building layouts with Flexbox and Grid.'),
('L028', 'S040', 'Last Step - HTML And CSS Full Course From Zero Knowledge', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Responsive design and deploying your first website.'),
('L029', 'S043', 'First Step - Full Stack Web Developing Clearly Explained', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to full stack development and tools.'),
('L030', 'S044', 'Second Step - Full Stack Web Developing Clearly Explained', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Front-end basics: HTML, CSS, JavaScript.'),
('L031', 'S045', 'Third Step - Full Stack Web Developing Clearly Explained', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Back-end introduction: server, database, API.'),
('L032', 'S046', 'Last Step - Full Stack Web Developing Clearly Explained', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Connecting front-end and back-end, deployment.'),
('L033', 'S049', 'First Step - Creating Data Dashboard with SQL and Excel', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to data dashboards and data sources.'),
('L034', 'S050', 'Second Step - Creating Data Dashboard with SQL and Excel', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Basic SQL queries for data extraction.'),
('L035', 'S051', 'Third Step - Creating Data Dashboard with SQL and Excel', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Excel formulas and data visualization.'),
('L036', 'S052', 'Last Step - Creating Data Dashboard with SQL and Excel', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Integrating SQL data into Excel dashboard.'),
('L037', 'S055', 'First Step - C Programming From Scratch To Advanced With Projects', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to C programming and setup.'),
('L038', 'S056', 'Second Step - C Programming From Scratch To Advanced With Projects', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Control structures and functions in C.'),
('L039', 'S057', 'Third Step - C Programming From Scratch To Advanced With Projects', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Pointers, arrays, and memory management.'),
('L040', 'S058', 'Last Step - C Programming From Scratch To Advanced With Projects', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Project structure and debugging in C.'),
('L041', 'S061', 'First Step - Designing Modern UI with Figma For Real Projects', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to modern UI principles and Figma workspace.'),
('L042', 'S062', 'Second Step - Designing Modern UI with Figma For Real Projects', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Building reusable components and design systems.'),
('L043', 'S063', 'Third Step - Designing Modern UI with Figma For Real Projects', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Prototyping and user flows for real projects.'),
('L044', 'S064', 'Last Step - Designing Modern UI with Figma For Real Projects', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Exporting assets and collaborating with developers.'),
('L045', 'S067', 'First Step - Video Editing For YouTube Content Creators', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to YouTube video editing workflow.'),
('L046', 'S068', 'Second Step - Video Editing For YouTube Content Creators', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Cutting, trimming, and arranging video clips.'),
('L047', 'S069', 'Third Step - Video Editing For YouTube Content Creators', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Adding effects, transitions, and audio.'),
('L048', 'S070', 'Last Step - Video Editing For YouTube Content Creators', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Exporting and optimizing videos for YouTube.'),
('L049', 'S073', 'First Step - Building REST API With Node.js and Express', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to REST API concepts and Node.js setup.'),
('L050', 'S074', 'Second Step - Building REST API With Node.js and Express', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Creating routes and controllers in Express.'),
('L051', 'S075', 'Third Step - Building REST API With Node.js and Express', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Connecting to databases and handling requests.'),
('L052', 'S076', 'Last Step - Building REST API With Node.js and Express', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Testing, error handling, and deploying the API.'),
('L053', 'S079', 'First Step - Advanced Excel Formulas and Data Manipulation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to advanced Excel formulas.'),
('L054', 'S080', 'Second Step - Advanced Excel Formulas and Data Manipulation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Data cleaning and manipulation techniques.'),
('L055', 'S081', 'Third Step - Advanced Excel Formulas and Data Manipulation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Using pivot tables and advanced charting.'),
('L056', 'S082', 'Last Step - Advanced Excel Formulas and Data Manipulation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Automating tasks with macros and exporting data.'),
('L057', 'S085', 'First Step - Intro to React.js For Frontend Web Development', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to React.js and component-based architecture.'),
('L058', 'S086', 'Second Step - Intro to React.js For Frontend Web Development', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'JSX, props, and state management in React.'),
('L059', 'S087', 'Third Step - Intro to React.js For Frontend Web Development', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Handling events and lifecycle methods.'),
('L060', 'S088', 'Last Step - Intro to React.js For Frontend Web Development', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Building and deploying a simple React app.'),
('L061', 'S091', 'Introduction to Data Cleaning and Pandas', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Overview of data cleaning, why it matters, and introduction to Pandas library.'),
('L062', 'S092', 'Handling Missing Data in Pandas', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Techniques for detecting and handling missing values using Pandas.'),
('L063', 'S093', 'Data Transformation and Normalization', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'How to transform, normalize, and standardize data for analysis.'),
('L064', 'S094', 'Removing Duplicates and Outliers', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Identifying and removing duplicate records and outliers in datasets.'),
('L065', 'S097', 'Introduction to Thumbnail Design Principles', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn the basics of what makes a thumbnail engaging and effective.'),
('L066', 'S098', 'Photoshop Tools for Thumbnail Creation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Explore essential Photoshop tools for designing thumbnails.'),
('L067', 'S099', 'Adding Text and Effects to Thumbnails', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'How to add and style text, and apply effects for better visibility.'),
('L068', 'S100', 'Exporting and Optimizing Thumbnails for YouTube', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Best practices for exporting and optimizing thumbnails for web use.'),
('L069', 'S103', 'Getting Started with DaVinci Resolve', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to the DaVinci Resolve interface and workflow.'),
('L070', 'S104', 'Basic Video Editing Techniques', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Cutting, trimming, and arranging clips on the timeline.'),
('L071', 'S105', 'Adding Transitions, Titles, and Audio', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'How to add transitions, titles, and work with audio tracks.'),
('L072', 'S106', 'Exporting and Delivering Your Video', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Export settings and best practices for final delivery.'),
('L073', 'S109', 'Introduction to Git and Version Control', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn the basics of Git and why version control is important.'),
('L074', 'S110', 'Working with Local Repositories', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'How to initialize, commit, and manage local repositories.'),
('L075', 'S111', 'Collaborating with GitHub', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Push, pull, and collaborate with others using GitHub.'),
('L076', 'S112', 'Branching, Merging, and Pull Requests', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn how to use branches, merge code, and submit pull requests.'),
('L077', 'S115', 'Getting Started with Kotlin and Android Studio', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to Kotlin and setting up Android Studio for development.'),
('L078', 'S116', 'Building Your First Android App', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Create a simple Android app and understand the project structure.'),
('L079', 'S117', 'Working with Layouts and UI Components', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Designing layouts and using common UI components in Android.'),
('L080', 'S118', 'Handling User Input and Navigation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Capture user input and implement navigation between screens.'),
('L081', 'S121', 'Introduction to REST API Testing with Postman', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Overview of API testing, Postman interface, and basic requests.'),
('L082', 'S122', 'Writing and Organizing Test Collections', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'How to create, organize, and run collections in Postman.'),
('L083', 'S123', 'Scripting and Automating API Tests', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to test scripts and automation in Postman.'),
('L084', 'S124', 'Advanced Features: Environments & Monitoring', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Using environments, variables, and monitoring in Postman.'),
('L085', 'S127', 'Introduction to Website Layout Design in Adobe XD', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn the basics of web layout and Adobe XD workspace.'),
('L086', 'S128', 'Wireframing and Prototyping', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Create wireframes and interactive prototypes in Adobe XD.'),
('L087', 'S129', 'Applying Design Principles and Styles', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Use color, typography, and grids for stunning layouts.'),
('L088', 'S130', 'Exporting and Sharing Your Designs', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Export assets and share your XD projects with others.'),
('L089', 'S133', 'Getting Started with Final Cut Pro', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to Final Cut Pro interface and workflow.'),
('L090', 'S134', 'Editing and Arranging Clips', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Cutting, trimming, and organizing video clips.'),
('L091', 'S135', 'Adding Effects, Titles, and Music', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Enhance your video with effects, titles, and background music.'),
('L092', 'S136', 'Exporting and Delivering Your Video', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Export settings and best practices for final delivery.'),
('L093', 'S139', 'Introduction to SQL Joins and Subqueries', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn the basics of joins and subqueries in SQL.'),
('L094', 'S140', 'Aggregate Functions and Grouping Data', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Using SUM, COUNT, AVG, and GROUP BY in SQL.'),
('L095', 'S141', 'Practical Data Analysis with SQL', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Apply SQL queries to real-world data analysis scenarios.'),
('L096', 'S142', 'Optimizing and Exporting SQL Results', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Optimize queries and export results for reporting.'),
('L097', 'S145', 'Introduction to JavaScript DOM Manipulation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn how the DOM works and how to select elements.'),
('L098', 'S146', 'Event Handling and User Interaction', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Respond to user actions with JavaScript events.'),
('L099', 'S147', 'Dynamic Content and Animation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Create dynamic content and simple animations.'),
('L100', 'S148', 'Building a Complete Interactive Page', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Combine techniques to build a fully interactive web page.'),
('L101', 'S151', 'Introduction to Motion Graphics in After Effects', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Overview of motion graphics and After Effects workspace.'),
('L102', 'S152', 'Animating Text and Shapes for Social Media', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn to animate text and basic shapes for engaging posts.'),
('L103', 'S153', 'Transitions and Effects for Instagram & TikTok', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Apply trendy transitions and effects for social media.'),
('L104', 'S154', 'Exporting and Optimizing for Social Platforms', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Export settings and optimization for Instagram, TikTok, YouTube.'),
('L105', 'S157', 'Introduction to Data Visualization with Tableau', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Getting started with Tableau and basic chart types.'),
('L106', 'S158', 'Building Interactive Dashboards in Tableau', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Create interactive dashboards and filters.'),
('L107', 'S159', 'Data Visualization Techniques in Excel', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Use Excel to create charts, graphs, and pivot tables.'),
('L108', 'S160', 'Combining Tableau and Excel for Insights', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Integrate Tableau and Excel for comprehensive analysis.'),
('L109', 'S163', 'Introduction to Weather APIs and JavaScript Fetch', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn how to fetch data from weather APIs using JavaScript.'),
('L110', 'S164', 'Displaying Weather Data on a Web Page', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Show weather data dynamically with HTML and CSS.'),
('L111', 'S165', 'Adding User Input and Error Handling', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Let users search for cities and handle API errors.'),
('L112', 'S166', 'Styling and Deploying Your Weather App', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Make your app look great and deploy it online.'),
('L113', 'S169', 'Introduction to Color Correction Tools', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Overview of color correction in Premiere Pro.'),
('L114', 'S170', 'Basic Color Grading Techniques', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Apply basic color grading to enhance video.'),
('L115', 'S171', 'Advanced Looks and Cinematic Styles', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Create cinematic looks using advanced tools.'),
('L116', 'S172', 'Exporting and Color Management', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Export your video with correct color settings.'),
('L117', 'S175', 'Introduction to Python Scripting for Automation', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn the basics of scripting and automation with Python.'),
('L118', 'S176', 'Automating File and Folder Operations', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Write scripts to manage files and folders automatically.'),
('L119', 'S177', 'Web Scraping and Data Extraction', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Extract data from websites using Python.'),
('L120', 'S178', 'Scheduling and Running Automated Tasks', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Schedule and run scripts automatically.'),
('L121', 'S181', 'Introduction to Infographic Design', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn the basics of infographic design in Illustrator.'),
('L122', 'S182', 'Creating Charts and Visual Elements', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Design charts, icons, and visual elements.'),
('L123', 'S183', 'Animating Infographics with After Effects', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Animate your infographics for presentations or video.'),
('L124', 'S184', 'Exporting and Sharing Animated Infographics', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Export and share your animated infographics.'),
('L125', 'S187', 'Introduction to Portfolio Websites', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn the structure of a portfolio website.'),
('L126', 'S188', 'Building Responsive Layouts with Bootstrap', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Use Bootstrap to create responsive layouts.'),
('L127', 'S189', 'Adding Projects and Contact Forms', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Showcase your projects and add a contact form.'),
('L128', 'S190', 'Deploying Your Portfolio Online', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Deploy your website to the internet.'),
('L129', 'S193', 'Introduction to Advanced MongoDB Concepts', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn advanced MongoDB queries and schema design.'),
('L130', 'S194', 'Aggregation Pipelines and Data Processing', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Use aggregation pipelines for data analysis.'),
('L131', 'S195', 'Indexing and Performance Optimization', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Optimize MongoDB performance with indexes.'),
('L132', 'S196', 'Integrating MongoDB with Node.js', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Connect MongoDB to Node.js applications.'),
('L133', 'S199', 'Introduction to YouTube Intros with Canva', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Design YouTube intros using Canva templates.'),
('L134', 'S200', 'Animating Intros in CapCut', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Animate your intro with CapCut effects.'),
('L135', 'S201', 'Adding Music and Exporting Videos', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Add music and export your intro for YouTube.'),
('L136', 'S202', 'Tips for Effective Branding', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Branding tips for memorable intros.'),
('L137', 'S205', 'Introduction to JavaScript Programming', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn JavaScript basics and syntax.'),
('L138', 'S206', 'Working with Functions and Objects', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Understand functions, objects, and arrays.'),
('L139', 'S207', 'DOM Manipulation and Events', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Manipulate the DOM and handle events.'),
('L140', 'S208', 'Asynchronous JavaScript and APIs', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Work with async code and fetch data from APIs.'),
('L141', 'S211', 'Introduction to Java Programming', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn Java basics, syntax, and setup.'),
('L142', 'S212', 'Object-Oriented Concepts in Java', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Understand classes, objects, inheritance, and encapsulation.'),
('L143', 'S213', 'Java Collections and Exception Handling', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Work with collections, lists, maps, and handle exceptions.'),
('L144', 'S214', 'Building Simple Java Applications', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Create and run simple Java console applications.'),
('L145', 'S217', 'Getting Started with C++ Programming', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Introduction to C++ syntax and compiling your first program.'),
('L146', 'S218', 'C++ Functions, Arrays, and Pointers', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn about functions, arrays, and pointer basics in C++.'),
('L147', 'S219', 'Object-Oriented Programming in C++', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Implement classes, objects, and inheritance in C++.'),
('L148', 'S220', 'File Handling and Simple Projects in C++', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Read/write files and build a simple C++ project.'),
('L149', 'S223', 'PHP Fundamentals and Syntax', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Learn PHP syntax, variables, and basic operations.'),
('L150', 'S224', 'Working with Forms and Databases in PHP', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Process forms and connect PHP to MySQL databases.'),
('L151', 'S225', 'Building Dynamic Web Pages with PHP', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Create dynamic content and manage sessions in PHP.'),
('L152', 'S226', 'Deploying and Securing PHP Applications', 'https://drive.google.com/file/d/1XRg7N3rR-jxhEMzGsQiNNnY_BDvfEjdK/preview', 'Deploy PHP apps and apply basic security best practices.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `orderID` varchar(6) NOT NULL,
  `studentID` varchar(3) DEFAULT NULL,
  `orderDate` date DEFAULT NULL,
  `paymentTypeID` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`orderID`),
  KEY `studentID` (`studentID`),
  KEY `paymentTypeID` (`paymentTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `order`
--

INSERT INTO `order` (`orderID`, `studentID`, `orderDate`, `paymentTypeID`) VALUES
('ORD001', 'S01', '2025-06-10', 'BC');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orderdetail`
--

DROP TABLE IF EXISTS `orderdetail`;
CREATE TABLE IF NOT EXISTS `orderdetail` (
  `orderDetailID` varchar(6) NOT NULL,
  `orderID` varchar(6) DEFAULT NULL,
  `courseID` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`orderDetailID`),
  KEY `orderID` (`orderID`),
  KEY `courseID` (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `orderdetail`
--

INSERT INTO `orderdetail` (`orderDetailID`, `orderID`, `courseID`) VALUES
('DTL001', 'ORD001', 'C05'),
('DTL002', 'ORD001', 'C18'),
('DTL003', 'ORD001', 'C20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `overallprogress`
--

DROP TABLE IF EXISTS `overallprogress`;
CREATE TABLE IF NOT EXISTS `overallprogress` (
  `studentID` varchar(3) NOT NULL,
  `courseID` varchar(3) NOT NULL,
  `progress` int DEFAULT NULL,
  `progressStatus` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`studentID`,`courseID`),
  KEY `courseID` (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `overallprogress`
--

INSERT INTO `overallprogress` (`studentID`, `courseID`, `progress`, `progressStatus`) VALUES
('S01', 'C05', 100, 'Passed'),
('S01', 'C18', 100, 'Passed'),
('S01', 'C20', 20, 'On Going');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `paymentTypeID` varchar(2) NOT NULL,
  `paymentType` varchar(50) DEFAULT NULL,
  `adminFee` int DEFAULT NULL,
  `paymentIcon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`paymentTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `payment`
--

INSERT INTO `payment` (`paymentTypeID`, `paymentType`, `adminFee`, `paymentIcon`) VALUES
('BC', 'BCA', 2500, 'bca.png'),
('DA', 'Dana', 2000, 'dana.png'),
('GP', 'Gopay', 2000, 'gopay.png'),
('OV', 'OVO', 3500, 'ovo.png'),
('SB', 'SeaBank', 1500, 'seabank.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `project`
--

DROP TABLE IF EXISTS `project`;
CREATE TABLE IF NOT EXISTS `project` (
  `projectID` varchar(3) NOT NULL,
  `sessionID` varchar(4) DEFAULT NULL,
  `projectTitle` varchar(255) DEFAULT NULL,
  `projectDetail` text,
  `fileType` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`projectID`),
  KEY `sessionID` (`sessionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `project`
--

INSERT INTO `project` (`projectID`, `sessionID`, `projectTitle`, `projectDetail`, `fileType`) VALUES
('P01', 'S006', 'Create a Cinematic 3D Motion Intro Using Adobe Premiere Pro', 'For your final project, you are required to create a short cinematic video intro (10–30 seconds) that uses at least three 3D effects you learned during the course. You must apply the Basic 3D effect, camera movements, and at least one transition using 3D perspective. The video must include a title animation, background music, and demonstrate a creative use of depth and motion. Submit the final exported video file along with a brief explanation (150–200 words) describing the effects you used and why.', 'MP4, WEBM'),
('P02', 'S012', 'Develop a Console-Based Student Grade Management System', 'As your final project, build a Python program that manages student data, including inputting student names, recording multiple grades, calculating averages, and displaying results with performance labels (e.g., Pass, Fail). The program should include functions, loops, conditional statements, and file handling (reading/writing data to .txt or .csv). You are encouraged to use dictionaries or classes to organize the data. Submit the .py file and a short documentation (200–300 words) explaining your code structure and logic.', 'Python File Type'),
('P03', 'S018', 'Create a Basic Mobile E-Commerce App for Fashion Products', 'Develop a simple mobile e-commerce app using Flutter that displays a list of fashion products (e.g., shirts, shoes, accessories). Your app must include a product catalog page, product detail view, shopping cart functionality, and a checkout simulation. Use ListView, Navigator, and StatefulWidget appropriately. You may use hardcoded product data or simulate a backend using a local data file (e.g., JSON). The UI should be clean and responsive. Submit the full Flutter project folder along with screenshots of key features and a short report (250–300 words) describing your app structure and widgets used.', 'Flutter File Type'),
('P04', 'S024', 'Desain UI Aplikasi Mobile Sederhana dengan Figma', 'Sebagai proyek akhir, rancanglah tampilan antarmuka (UI) aplikasi mobile sederhana (misal: aplikasi catatan atau to-do list) menggunakan Figma. Desain harus mencakup minimal 3 layar (home, detail, dan form input), menggunakan Auto-layout, Component, dan prototyping sederhana. Sertakan file Figma dan penjelasan singkat (150–200 kata) mengenai struktur desain dan fitur yang digunakan.', 'Figma File'),
('P05', 'S030', 'Mini Project: Program Manajemen Data Mahasiswa dengan C', 'Sebagai proyek akhir, buatlah program berbasis console menggunakan bahasa C yang dapat menyimpan data beberapa mahasiswa (nama, NIM, dan nilai). Program harus bisa menambah data, menampilkan seluruh data, dan mencari data mahasiswa berdasarkan NIM. Sertakan penjelasan singkat (100–150 kata) mengenai struktur program dan penggunaan array/pointer.', 'C File'),
('P06', 'S036', 'Project: Aplikasi Mobile dengan Animasi Interaktif', 'Buatlah aplikasi mobile sederhana menggunakan Flutter yang menampilkan minimal satu animasi interaktif berbasis JavaScript (misal: animasi loading, tombol interaktif, atau transisi halaman). Sertakan penjelasan singkat (150–200 kata) mengenai proses integrasi animasi dan manfaatnya untuk aplikasi.', 'Flutter Project'),
('P07', 'S042', 'Mini Website Portfolio dengan HTML & CSS', 'Buatlah website portfolio sederhana yang menampilkan profil, daftar proyek, dan kontak Anda. Website harus responsif dan menggunakan minimal satu layout grid atau flexbox. Sertakan file HTML, CSS, dan screenshot hasil akhir.', 'HTML & CSS File'),
('P08', 'S048', 'Full Stack Web App: To-Do List', 'Bangun aplikasi to-do list sederhana dengan front-end (HTML, CSS, JS) dan back-end (Node.js/Express atau PHP). Aplikasi harus bisa menambah, mengedit, dan menghapus tugas. Sertakan penjelasan singkat (150–200 kata) tentang arsitektur aplikasi.', 'Web Project'),
('P09', 'S054', 'Dashboard Penjualan dengan SQL & Excel', 'Buatlah dashboard penjualan sederhana dengan mengambil data dari database menggunakan SQL, lalu visualisasikan di Excel (grafik, tabel dinamis). Sertakan file SQL, Excel, dan penjelasan singkat (100–150 kata) tentang proses integrasi.', 'SQL & Excel File'),
('P10', 'S060', 'Project: Sistem Inventaris Barang dengan C', 'Buat program C yang dapat mencatat, menampilkan, dan mencari data inventaris barang (nama, kode, jumlah). Data disimpan di file eksternal. Sertakan penjelasan singkat (100–150 kata) tentang struktur program dan penggunaan file.', 'C File'),
('P11', 'S066', 'Project: UI Kit untuk Aplikasi Mobile', 'Buatlah UI Kit di Figma yang terdiri dari minimal 5 komponen (button, input, card, navbar, dan modal). Sertakan penjelasan singkat (100–150 kata) tentang prinsip desain yang digunakan dan bagaimana komponen tersebut dapat digunakan ulang.', 'Figma File'),
('P12', 'S072', 'Project: Video YouTube Siap Upload', 'Edit video berdurasi 3–5 menit untuk YouTube, lengkap dengan intro, transisi, musik latar, dan outro. Sertakan file project dan penjelasan singkat (100–150 kata) tentang proses editing yang dilakukan.', 'Video File'),
('P13', 'S078', 'Project: REST API Sederhana dengan Node.js & Express', 'Bangun REST API sederhana untuk manajemen data produk (CRUD) menggunakan Node.js dan Express. Sertakan dokumentasi endpoint dan contoh penggunaan dengan Postman.', 'Node.js Project'),
('P14', 'S084', 'Project: Laporan Penjualan Otomatis di Excel', 'Buatlah laporan penjualan otomatis di Excel yang memanfaatkan formula, tabel pivot, dan macro sederhana. Sertakan file Excel dan penjelasan singkat (100–150 kata) tentang proses otomatisasi.', 'Excel File'),
('P15', 'S090', 'Project: Aplikasi To-Do List dengan React.js', 'Bangun aplikasi to-do list sederhana menggunakan React.js yang dapat menambah, menghapus, dan menandai tugas selesai. Sertakan penjelasan singkat (100–150 kata) tentang struktur komponen dan penggunaan state.', 'React.js Project'),
('P16', 'S096', 'Data Cleaning Project with Pandas', 'Clean a messy dataset (with missing values, duplicates, and outliers) using Pandas. Submit your Jupyter Notebook and a short report (150–200 words) explaining your cleaning process.', 'Jupyter Notebook'),
('P17', 'S102', 'Thumbnail Design Project', 'Create three different YouTube thumbnails for different video genres (e.g., travel, tech, education) using Photoshop. Submit the PSD files and a brief explanation (100–150 words) for each design.', 'PSD File'),
('P18', 'S108', 'DaVinci Resolve Editing Project', 'Edit a 2–3 minute video using DaVinci Resolve, applying multiple transitions, titles, and audio adjustments. Submit the project file and a short report (100–150 words) about your workflow.', 'DaVinci Resolve Project'),
('P19', 'S114', 'GitHub Collaboration Project', 'Work with a partner to create a collaborative repository. Each person must create a branch, make changes, and submit a pull request. Submit the repository link and a short summary (100–150 words) of your collaboration process.', 'GitHub Repo Link'),
('P20', 'S120', 'Kotlin Android App Project', 'Develop a basic Android app (e.g., a to-do list or calculator) using Kotlin. The app should have at least two screens and handle user input. Submit the project files and a short explanation (150–200 words) of your app structure.', 'Android Studio Project'),
('P21', 'S126', 'Postman API Testing Project', 'Create a collection in Postman to test a sample REST API with at least 3 endpoints. Include automated tests and documentation. Submit the exported collection and a brief explanation (100–150 words).', 'Postman Collection'),
('P22', 'S132', 'Website Layout Design Project', 'Design a multi-page website layout in Adobe XD, including homepage, about, and contact pages. Submit the XD file and a short explanation (100–150 words) of your design choices.', 'Adobe XD File'),
('P23', 'S138', 'Cinematic Travel Video Project', 'Edit a 2–3 minute cinematic travel video in Final Cut Pro, using transitions, titles, and music. Submit the project file and a short report (100–150 words) about your workflow.', 'Final Cut Pro Project'),
('P24', 'S144', 'SQL Data Analysis Project', 'Analyze a sales dataset using SQL. Write queries for joins, subqueries, and aggregates, and export the results. Submit the SQL file and a brief report (100–150 words).', 'SQL File'),
('P25', 'S150', 'Interactive Product Page Project', 'Build an interactive product page using HTML, CSS, and JavaScript DOM. The page should allow users to add products to a cart and display the cart contents. Submit the project files and a short explanation (100–150 words).', 'Web Project'),
('P26', 'S156', 'Motion Graphics Social Media Project', 'Buat satu video motion graphics berdurasi 10–20 detik untuk promosi produk di Instagram atau TikTok. Sertakan file project dan penjelasan singkat (100–150 kata) tentang konsep dan tools yang digunakan.', 'After Effects Project'),
('P27', 'S162', 'Data Visualization Dashboard Project', 'Buat dashboard interaktif yang menggabungkan data dari Excel dan Tableau. Sertakan file dashboard dan penjelasan singkat (100–150 kata) tentang insight yang dihasilkan.', 'Tableau/Excel File'),
('P28', 'S168', 'Weather App Project', 'Bangun aplikasi cuaca lengkap menggunakan JavaScript dan API. Aplikasi harus bisa mencari kota, menampilkan suhu, dan ikon cuaca. Sertakan file project dan penjelasan singkat (100–150 kata) tentang struktur kode.', 'Web Project'),
('P29', 'S174', 'Color Grading Video Project', 'Edit video berdurasi 1–2 menit dengan menerapkan color correction dan grading di Premiere Pro. Sertakan file project dan penjelasan singkat (100–150 kata) tentang teknik yang digunakan.', 'Premiere Pro Project'),
('P30', 'S180', 'Python Automation Project', 'Buat script Python yang melakukan scraping data dari website dan menyimpannya ke file CSV. Sertakan file script dan penjelasan singkat (100–150 kata) tentang proses scraping.', 'Python Script'),
('P31', 'S186', 'Animated Infographic Project', 'Desain dan animasikan satu infographic di Illustrator dan After Effects. Sertakan file project dan penjelasan singkat (100–150 kata) tentang proses desain dan animasi.', 'Illustrator/AE File'),
('P32', 'S192', 'Portfolio Website Project', 'Bangun website portfolio lengkap dengan HTML, CSS, dan Bootstrap. Website harus menampilkan daftar proyek, kontak, dan responsif di semua device. Sertakan file project dan penjelasan singkat (100–150 kata) tentang struktur website.', 'Web Project'),
('P33', 'S198', 'MongoDB Aggregation Project', 'Buat aplikasi backend sederhana yang menggunakan MongoDB aggregation pipeline untuk analisis data. Sertakan file project dan penjelasan singkat (100–150 kata) tentang pipeline yang dibuat.', 'Node.js/MongoDB Project'),
('P34', 'S204', 'YouTube Intro Design Project', 'Desain dan animasikan intro YouTube berdurasi 5–10 detik menggunakan Canva dan CapCut. Sertakan file project dan penjelasan singkat (100–150 kata) tentang konsep desain.', 'Canva/CapCut File'),
('P35', 'S210', 'JavaScript Mini App Project', 'Buat aplikasi mini berbasis JavaScript yang memanfaatkan DOM dan event. Sertakan file project dan penjelasan singkat (100–150 kata) tentang fitur dan logika aplikasi.', 'Web Project'),
('P36', 'S216', 'Java Console Application Project', 'Buat aplikasi Java berbasis console untuk manajemen data buku (tambah, tampilkan, cari). Sertakan file project dan penjelasan singkat (100–150 kata) tentang struktur kode dan konsep OOP yang digunakan.', 'Java Project'),
('P37', 'S222', 'C++ Mini Project', 'Buat aplikasi C++ sederhana untuk mengelola data inventaris barang (tambah, hapus, tampilkan). Sertakan file project dan penjelasan singkat (100–150 kata) tentang penggunaan array dan file handling.', 'C++ Project'),
('P38', 'S228', 'PHP Dynamic Website Project', 'Buat website dinamis sederhana menggunakan PHP dan MySQL (misal: sistem buku tamu atau katalog produk). Sertakan file project dan penjelasan singkat (100–150 kata) tentang struktur database dan keamanan dasar.', 'PHP Project');

-- --------------------------------------------------------

--
-- Struktur dari tabel `projectattempt`
--

DROP TABLE IF EXISTS `projectattempt`;
CREATE TABLE IF NOT EXISTS `projectattempt` (
  `studentID` varchar(3) NOT NULL,
  `projectID` varchar(3) NOT NULL,
  `submitedFile` text,
  `score` decimal(10,1) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `projectFeedback` text,
  PRIMARY KEY (`studentID`,`projectID`),
  KEY `projectID` (`projectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `projectattempt`
--

INSERT INTO `projectattempt` (`studentID`, `projectID`, `submitedFile`, `score`, `status`, `projectFeedback`) VALUES
('S01', 'P01', 'S01_P01_project.zip', 9.0, 'Checked', 'Good job!, you have understood this course thoroughly, your project assignment is perfect.'),
('S01', 'P18', 'S01_P18_project.zip', 8.5, 'Checked', 'Good job!, you have understood this course thoroughly, your project assignment is perfect.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `requirement`
--

DROP TABLE IF EXISTS `requirement`;
CREATE TABLE IF NOT EXISTS `requirement` (
  `requirementID` varchar(3) NOT NULL,
  `courseID` varchar(3) DEFAULT NULL,
  `requirementName` varchar(255) DEFAULT NULL,
  `downloadLink` text,
  PRIMARY KEY (`requirementID`),
  KEY `courseID` (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `requirement`
--

INSERT INTO `requirement` (`requirementID`, `courseID`, `requirementName`, `downloadLink`) VALUES
('R01', 'C05', 'Adobe Premiere Pro', 'https://www.adobe.com/id_en/creativecloud/business/teams/premiere-pro.html'),
('R02', 'C05', 'Blender 4.0', 'https://www.blender.org/download/'),
('R03', 'C02', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R04', 'C02', 'Python 3.13.3', 'https://www.python.org/downloads/release/python-3133/'),
('R05', 'C10', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R06', 'C35', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R07', 'C01', 'Flutter', 'https://docs.flutter.dev/get-started/install/windows'),
('R08', 'C01', 'Android Studio', 'https://developer.android.com/studio?gad_source=1&gad_campaignid=21831783564&gbraid=0AAAAAC-IOZkC7od9RK4S02zcdKOOABEK_d&gclid=CjwKCAjwruXBBhArEiwACBRtHRRwM5RnkNZipvvUl-wwoTT7PVLxgCkMEtyEYyqkVzdAP0SwpFIAcBoCB30QAvD_BwE&gclsrc=aw.ds&hl=id'),
('R09', 'C07', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R10', 'C08', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R11', 'C08', 'Laragon', 'https://laragon.org/download/'),
('R12', 'C08', 'Python 3.13.3', 'https://www.python.org/downloads/release/python-3133/'),
('R13', 'C09', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R14', 'C09', 'Node.js 20.8.0', 'https://nodejs.org/en/download/'),
('R15', 'C11', 'Microsoft Excel 2021', 'https://www.microsoft.com/id-id/microsoft-365/excel'),
('R16', 'C12', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R17', 'C12', 'Node.js 20.8.0', 'https://nodejs.org/en/download/'),
('R18', 'C13', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R19', 'C14', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R20', 'C15', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R21', 'C16', 'Python 3.x', 'https://www.python.org/downloads/'),
('R22', 'C16', 'Jupyter Notebook', 'https://jupyter.org/install'),
('R23', 'C16', 'Pandas Library', 'https://pandas.pydata.org/pandas-docs/stable/getting_started/install.html'),
('R24', 'C17', 'Adobe Photoshop', 'https://www.adobe.com/products/photoshop.html'),
('R25', 'C18', 'DaVinci Resolve', 'https://www.blackmagicdesign.com/products/davinciresolve/'),
('R26', 'C19', 'Git', 'https://git-scm.com/downloads'),
('R27', 'C19', 'GitHub Account', 'https://github.com/'),
('R28', 'C20', 'Android Studio', 'https://developer.android.com/studio'),
('R29', 'C20', 'Kotlin', 'https://kotlinlang.org/docs/getting-started.html'),
('R30', 'C21', 'Postman', 'https://www.postman.com/downloads/'),
('R31', 'C22', 'Adobe XD', 'https://www.adobe.com/products/xd.html'),
('R32', 'C23', 'Final Cut Pro', 'https://www.apple.com/final-cut-pro/'),
('R33', 'C24', 'MySQL', 'https://dev.mysql.com/downloads/'),
('R34', 'C25', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R35', 'C26', 'Adobe After Effects', 'https://www.adobe.com/products/aftereffects.html'),
('R36', 'C27', 'Tableau Public', 'https://public.tableau.com/app/discover'),
('R37', 'C27', 'Microsoft Excel 2021', 'https://www.microsoft.com/id-id/microsoft-365/excel'),
('R38', 'C28', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R39', 'C29', 'Adobe Premiere Pro', 'https://www.adobe.com/id_en/creativecloud/business/teams/premiere-pro.html'),
('R40', 'C30', 'Python 3.x', 'https://www.python.org/downloads/'),
('R41', 'C31', 'Adobe Illustrator', 'https://www.adobe.com/products/illustrator.html'),
('R42', 'C32', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R43', 'C33', 'MongoDB', 'https://www.mongodb.com/try/download/community'),
('R44', 'C34', 'Canva', 'https://www.canva.com/'),
('R45', 'C34', 'CapCut', 'https://www.capcut.com/'),
('R46', 'C35', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R47', 'C37', 'IntelliJ IDEA', 'https://www.jetbrains.com/idea/download/'),
('R48', 'C37', 'Java JDK', 'https://www.oracle.com/java/technologies/downloads/'),
('R49', 'C38', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R50', 'C38', 'C++ Compiler', 'https://www.mingw-w64.org/downloads/'),
('R51', 'C39', 'Visual Studio Code', 'https://code.visualstudio.com/'),
('R52', 'C39', 'XAMPP', 'https://www.apachefriends.org/download.html');

-- --------------------------------------------------------

--
-- Struktur dari tabel `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `sessionID` varchar(4) NOT NULL,
  `courseID` varchar(3) DEFAULT NULL,
  `sessionSeq` int DEFAULT NULL,
  `sessionType` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`sessionID`),
  KEY `courseID` (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `session`
--

INSERT INTO `session` (`sessionID`, `courseID`, `sessionSeq`, `sessionType`) VALUES
('S001', 'C05', 1, 'Lesson'),
('S002', 'C05', 2, 'Lesson'),
('S003', 'C05', 3, 'Lesson'),
('S004', 'C05', 4, 'Lesson'),
('S005', 'C05', 5, 'Exercise'),
('S006', 'C05', 6, 'Project'),
('S007', 'C02', 1, 'Lesson'),
('S008', 'C02', 2, 'Lesson'),
('S009', 'C02', 3, 'Lesson'),
('S010', 'C02', 4, 'Lesson'),
('S011', 'C02', 5, 'Exercise'),
('S012', 'C02', 6, 'Project'),
('S013', 'C01', 1, 'Lesson'),
('S014', 'C01', 2, 'Lesson'),
('S015', 'C01', 3, 'Lesson'),
('S016', 'C01', 4, 'Lesson'),
('S017', 'C01', 5, 'Exercise'),
('S018', 'C01', 6, 'Project'),
('S019', 'C04', 1, 'Lesson'),
('S020', 'C04', 2, 'Lesson'),
('S021', 'C04', 3, 'Lesson'),
('S022', 'C04', 4, 'Lesson'),
('S023', 'C04', 5, 'Exercise'),
('S024', 'C04', 6, 'Project'),
('S025', 'C03', 1, 'Lesson'),
('S026', 'C03', 2, 'Lesson'),
('S027', 'C03', 3, 'Lesson'),
('S028', 'C03', 4, 'Lesson'),
('S029', 'C03', 5, 'Exercise'),
('S030', 'C03', 6, 'Project'),
('S031', 'C06', 1, 'Lesson'),
('S032', 'C06', 2, 'Lesson'),
('S033', 'C06', 3, 'Lesson'),
('S034', 'C06', 4, 'Lesson'),
('S035', 'C06', 5, 'Exercise'),
('S036', 'C06', 6, 'Project'),
('S037', 'C07', 1, 'Lesson'),
('S038', 'C07', 2, 'Lesson'),
('S039', 'C07', 3, 'Lesson'),
('S040', 'C07', 4, 'Lesson'),
('S041', 'C07', 5, 'Exercise'),
('S042', 'C07', 6, 'Project'),
('S043', 'C08', 1, 'Lesson'),
('S044', 'C08', 2, 'Lesson'),
('S045', 'C08', 3, 'Lesson'),
('S046', 'C08', 4, 'Lesson'),
('S047', 'C08', 5, 'Exercise'),
('S048', 'C08', 6, 'Project'),
('S049', 'C09', 1, 'Lesson'),
('S050', 'C09', 2, 'Lesson'),
('S051', 'C09', 3, 'Lesson'),
('S052', 'C09', 4, 'Lesson'),
('S053', 'C09', 5, 'Exercise'),
('S054', 'C09', 6, 'Project'),
('S055', 'C10', 1, 'Lesson'),
('S056', 'C10', 2, 'Lesson'),
('S057', 'C10', 3, 'Lesson'),
('S058', 'C10', 4, 'Lesson'),
('S059', 'C10', 5, 'Exercise'),
('S060', 'C10', 6, 'Project'),
('S061', 'C11', 1, 'Lesson'),
('S062', 'C11', 2, 'Lesson'),
('S063', 'C11', 3, 'Lesson'),
('S064', 'C11', 4, 'Lesson'),
('S065', 'C11', 5, 'Exercise'),
('S066', 'C11', 6, 'Project'),
('S067', 'C12', 1, 'Lesson'),
('S068', 'C12', 2, 'Lesson'),
('S069', 'C12', 3, 'Lesson'),
('S070', 'C12', 4, 'Lesson'),
('S071', 'C12', 5, 'Exercise'),
('S072', 'C12', 6, 'Project'),
('S073', 'C13', 1, 'Lesson'),
('S074', 'C13', 2, 'Lesson'),
('S075', 'C13', 3, 'Lesson'),
('S076', 'C13', 4, 'Lesson'),
('S077', 'C13', 5, 'Exercise'),
('S078', 'C13', 6, 'Project'),
('S079', 'C14', 1, 'Lesson'),
('S080', 'C14', 2, 'Lesson'),
('S081', 'C14', 3, 'Lesson'),
('S082', 'C14', 4, 'Lesson'),
('S083', 'C14', 5, 'Exercise'),
('S084', 'C14', 6, 'Project'),
('S085', 'C15', 1, 'Lesson'),
('S086', 'C15', 2, 'Lesson'),
('S087', 'C15', 3, 'Lesson'),
('S088', 'C15', 4, 'Lesson'),
('S089', 'C15', 5, 'Exercise'),
('S090', 'C15', 6, 'Project'),
('S091', 'C16', 1, 'Lesson'),
('S092', 'C16', 2, 'Lesson'),
('S093', 'C16', 3, 'Lesson'),
('S094', 'C16', 4, 'Lesson'),
('S095', 'C16', 5, 'Exercise'),
('S096', 'C16', 6, 'Project'),
('S097', 'C17', 1, 'Lesson'),
('S098', 'C17', 2, 'Lesson'),
('S099', 'C17', 3, 'Lesson'),
('S100', 'C17', 4, 'Lesson'),
('S101', 'C17', 5, 'Exercise'),
('S102', 'C17', 6, 'Project'),
('S103', 'C18', 1, 'Lesson'),
('S104', 'C18', 2, 'Lesson'),
('S105', 'C18', 3, 'Lesson'),
('S106', 'C18', 4, 'Lesson'),
('S107', 'C18', 5, 'Exercise'),
('S108', 'C18', 6, 'Project'),
('S109', 'C19', 1, 'Lesson'),
('S110', 'C19', 2, 'Lesson'),
('S111', 'C19', 3, 'Lesson'),
('S112', 'C19', 4, 'Lesson'),
('S113', 'C19', 5, 'Exercise'),
('S114', 'C19', 6, 'Project'),
('S115', 'C20', 1, 'Lesson'),
('S116', 'C20', 2, 'Lesson'),
('S117', 'C20', 3, 'Lesson'),
('S118', 'C20', 4, 'Lesson'),
('S119', 'C20', 5, 'Exercise'),
('S120', 'C20', 6, 'Project'),
('S121', 'C21', 1, 'Lesson'),
('S122', 'C21', 2, 'Lesson'),
('S123', 'C21', 3, 'Lesson'),
('S124', 'C21', 4, 'Lesson'),
('S125', 'C21', 5, 'Exercise'),
('S126', 'C21', 6, 'Project'),
('S127', 'C22', 1, 'Lesson'),
('S128', 'C22', 2, 'Lesson'),
('S129', 'C22', 3, 'Lesson'),
('S130', 'C22', 4, 'Lesson'),
('S131', 'C22', 5, 'Exercise'),
('S132', 'C22', 6, 'Project'),
('S133', 'C23', 1, 'Lesson'),
('S134', 'C23', 2, 'Lesson'),
('S135', 'C23', 3, 'Lesson'),
('S136', 'C23', 4, 'Lesson'),
('S137', 'C23', 5, 'Exercise'),
('S138', 'C23', 6, 'Project'),
('S139', 'C24', 1, 'Lesson'),
('S140', 'C24', 2, 'Lesson'),
('S141', 'C24', 3, 'Lesson'),
('S142', 'C24', 4, 'Lesson'),
('S143', 'C24', 5, 'Exercise'),
('S144', 'C24', 6, 'Project'),
('S145', 'C25', 1, 'Lesson'),
('S146', 'C25', 2, 'Lesson'),
('S147', 'C25', 3, 'Lesson'),
('S148', 'C25', 4, 'Lesson'),
('S149', 'C25', 5, 'Exercise'),
('S150', 'C25', 6, 'Project'),
('S151', 'C26', 1, 'Lesson'),
('S152', 'C26', 2, 'Lesson'),
('S153', 'C26', 3, 'Lesson'),
('S154', 'C26', 4, 'Lesson'),
('S155', 'C26', 5, 'Exercise'),
('S156', 'C26', 6, 'Project'),
('S157', 'C27', 1, 'Lesson'),
('S158', 'C27', 2, 'Lesson'),
('S159', 'C27', 3, 'Lesson'),
('S160', 'C27', 4, 'Lesson'),
('S161', 'C27', 5, 'Exercise'),
('S162', 'C27', 6, 'Project'),
('S163', 'C28', 1, 'Lesson'),
('S164', 'C28', 2, 'Lesson'),
('S165', 'C28', 3, 'Lesson'),
('S166', 'C28', 4, 'Lesson'),
('S167', 'C28', 5, 'Exercise'),
('S168', 'C28', 6, 'Project'),
('S169', 'C29', 1, 'Lesson'),
('S170', 'C29', 2, 'Lesson'),
('S171', 'C29', 3, 'Lesson'),
('S172', 'C29', 4, 'Lesson'),
('S173', 'C29', 5, 'Exercise'),
('S174', 'C29', 6, 'Project'),
('S175', 'C30', 1, 'Lesson'),
('S176', 'C30', 2, 'Lesson'),
('S177', 'C30', 3, 'Lesson'),
('S178', 'C30', 4, 'Lesson'),
('S179', 'C30', 5, 'Exercise'),
('S180', 'C30', 6, 'Project'),
('S181', 'C31', 1, 'Lesson'),
('S182', 'C31', 2, 'Lesson'),
('S183', 'C31', 3, 'Lesson'),
('S184', 'C31', 4, 'Lesson'),
('S185', 'C31', 5, 'Exercise'),
('S186', 'C31', 6, 'Project'),
('S187', 'C32', 1, 'Lesson'),
('S188', 'C32', 2, 'Lesson'),
('S189', 'C32', 3, 'Lesson'),
('S190', 'C32', 4, 'Lesson'),
('S191', 'C32', 5, 'Exercise'),
('S192', 'C32', 6, 'Project'),
('S193', 'C33', 1, 'Lesson'),
('S194', 'C33', 2, 'Lesson'),
('S195', 'C33', 3, 'Lesson'),
('S196', 'C33', 4, 'Lesson'),
('S197', 'C33', 5, 'Exercise'),
('S198', 'C33', 6, 'Project'),
('S199', 'C34', 1, 'Lesson'),
('S200', 'C34', 2, 'Lesson'),
('S201', 'C34', 3, 'Lesson'),
('S202', 'C34', 4, 'Lesson'),
('S203', 'C34', 5, 'Exercise'),
('S204', 'C34', 6, 'Project'),
('S205', 'C35', 1, 'Lesson'),
('S206', 'C35', 2, 'Lesson'),
('S207', 'C35', 3, 'Lesson'),
('S208', 'C35', 4, 'Lesson'),
('S209', 'C35', 5, 'Exercise'),
('S210', 'C35', 6, 'Project'),
('S211', 'C37', 1, 'Lesson'),
('S212', 'C37', 2, 'Lesson'),
('S213', 'C37', 3, 'Lesson'),
('S214', 'C37', 4, 'Lesson'),
('S215', 'C37', 5, 'Exercise'),
('S216', 'C37', 6, 'Project'),
('S217', 'C38', 1, 'Lesson'),
('S218', 'C38', 2, 'Lesson'),
('S219', 'C38', 3, 'Lesson'),
('S220', 'C38', 4, 'Lesson'),
('S221', 'C38', 5, 'Exercise'),
('S222', 'C38', 6, 'Project'),
('S223', 'C39', 1, 'Lesson'),
('S224', 'C39', 2, 'Lesson'),
('S225', 'C39', 3, 'Lesson'),
('S226', 'C39', 4, 'Lesson'),
('S227', 'C39', 5, 'Exercise'),
('S228', 'C39', 6, 'Project');

-- --------------------------------------------------------

--
-- Struktur dari tabel `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `studentID` varchar(3) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `phoneNumber` varchar(20) DEFAULT NULL,
  `address` text,
  `DOB` date DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `studentImage` text,
  PRIMARY KEY (`studentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `student`
--

INSERT INTO `student` (`studentID`, `name`, `phoneNumber`, `address`, `DOB`, `gender`, `status`, `email`, `password`, `studentImage`) VALUES
('S01', 'Christiano Ronaldo', '81908196194', 'RT.5/RW.2, Gambir, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10110', '2004-08-29', 'Male', 'Employee', 'ronaldo@gmail.com', '$2y$10$bI2b0qUp4kq4d/dL/C0Auu66FOTlrEftNMUbFEA2AbfYeLmGHDXMS', 'S01.png'),
('S02', 'Marteen Paes', '88912345678', 'Avenida de Concha Espina No. 1, Apartemen 12B, Chamartín, Madrid, Comunidad de Madrid, 28036, Spanyol', '2000-07-12', 'Male', 'Student', 'paes@gmail.com', '$2y$10$NLt.XfIcCAayOpIUOZ25BO5NrO0geORA/KsQqdrelvQV8LzM8Sxma', 'S02.png'),
('S03', 'Emil Audero', '87323456789', 'Calle de Serrano No. 45, Lantai 5, Salamanca, Madrid, Comunidad de Madrid', '2003-06-29', 'Male', 'Student', 'audero@gmail.com', '$2y$10$GgNSzAgQVF9x.p2vXHDCJuC26aduhPnrOh6e9ik2i2Eyhg2OOo.3C', 'S03.png'),
('S04', 'Ragnar Oratmangoen', '82134567890', 'Calle de Velázquez No. 120, Blok C, Apartemen 8A, Chamartín, Madrid, Comunidad de Madrid, 28006, Spanyol', '2001-01-09', 'Male', 'Student', 'oratmangoen@gmail.com', '$2y$10$q2l2k7W0fbeodKT5TV.u/O8iai8gdsyCP2/L33tcI8eRzE1U3zVdK', 'S04.png'),
('S05', 'Marselino Ferdinan', '89145678901', 'Paseo de la Castellana No. 200, Lantai 10, Tetuán, Madrid, Comunidad de Madrid, 28046, Spanyol', '1990-01-04', 'Male', 'Employee', 'ferdinan@gmail.com', '$2y$10$ujXdEgS7b5eQqfb621g/y.Z78MW0kgtINTH9pgfHXcB.qDFpoDHG2', 'S05.png'),
('S06', 'dawda', '2121', 'dawda', '2004-03-12', 'Male', 'Employee', 'wadaw@gmail.com', '$2y$10$WJDD1jnHdr.6QxhapmVmY.d2Tplv5CMSh0WnM7SvRPjZcsWQhPqkK', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `temporarycheckout`
--

DROP TABLE IF EXISTS `temporarycheckout`;
CREATE TABLE IF NOT EXISTS `temporarycheckout` (
  `studentID` varchar(3) NOT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `paymentTypeID` varchar(2) DEFAULT NULL,
  `paymentFee` decimal(10,1) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`studentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `careerpath`
--
ALTER TABLE `careerpath`
  ADD CONSTRAINT `careerpath_ibfk_1` FOREIGN KEY (`courseCatID`) REFERENCES `coursecategory` (`courseCatID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `certification`
--
ALTER TABLE `certification`
  ADD CONSTRAINT `certification_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`courseCatID`) REFERENCES `coursecategory` (`courseCatID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `course_ibfk_2` FOREIGN KEY (`lecturerID`) REFERENCES `lecturer` (`lecturerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `coursereview`
--
ALTER TABLE `coursereview`
  ADD CONSTRAINT `coursereview_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coursereview_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `discount`
--
ALTER TABLE `discount`
  ADD CONSTRAINT `discount_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `enrolled`
--
ALTER TABLE `enrolled`
  ADD CONSTRAINT `enrolled_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enrolled_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `exercise`
--
ALTER TABLE `exercise`
  ADD CONSTRAINT `exercise_ibfk_1` FOREIGN KEY (`sessionID`) REFERENCES `session` (`sessionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `exerciseattempt`
--
ALTER TABLE `exerciseattempt`
  ADD CONSTRAINT `exerciseattempt_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exerciseattempt_ibfk_2` FOREIGN KEY (`exerciseID`) REFERENCES `exercise` (`exerciseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `learningprogress`
--
ALTER TABLE `learningprogress`
  ADD CONSTRAINT `learningprogress_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `learningprogress_ibfk_2` FOREIGN KEY (`sessionID`) REFERENCES `session` (`sessionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `lesson`
--
ALTER TABLE `lesson`
  ADD CONSTRAINT `lesson_ibfk_1` FOREIGN KEY (`sessionID`) REFERENCES `session` (`sessionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`paymentTypeID`) REFERENCES `payment` (`paymentTypeID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD CONSTRAINT `orderdetail_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `order` (`orderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderdetail_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `overallprogress`
--
ALTER TABLE `overallprogress`
  ADD CONSTRAINT `overallprogress_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `overallprogress_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`sessionID`) REFERENCES `session` (`sessionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `projectattempt`
--
ALTER TABLE `projectattempt`
  ADD CONSTRAINT `projectattempt_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `projectattempt_ibfk_2` FOREIGN KEY (`projectID`) REFERENCES `project` (`projectID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `requirement`
--
ALTER TABLE `requirement`
  ADD CONSTRAINT `requirement_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `temporarycheckout`
--
ALTER TABLE `temporarycheckout`
  ADD CONSTRAINT `temporarycheckout_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
