<?php
session_start();
include_once(__DIR__ . '/chats.php');
include './components/loading.php';
showLoading();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How It Works</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }
        .hero {
            background:
                linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                url('./assets/images/servicebg.png') center/cover no-repeat;
            background-size: cover;
            color: #fff;
            animation: fadeIn 2s ease-in-out;
            height: 80vh;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin:0
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
            animation: slideIn 1.5s ease-in-out;
        }
        .hero p {
            font-size: 1.5rem;
            margin-top: 10px;
            animation: slideIn 1.5s ease-in-out;
        }

        h2 {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        .service-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 60px 0;
        }
        .service-card {
            background: white;
            padding: 20px;
            width: 270px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
            text-align: center;
        }
        .service-card:hover {
            transform: translateY(-5px);
        }
        .icon-wrapper {
            width: 70px;
            height: 70px;
            background-color: #e0e7ff; 
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            margin-bottom: 15px;
        }
        .service-card i {
            font-size: 35px;
            color: #4f46e5;
        }
        h3 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        p {
            font-size: 14px;
            color: #666;
        }
        .wave-section {
            position: relative;
            height: 80vh;
            background: #4f46e5;
            display: flex;
            justify-content: space-between;
            align-items: end;
            color: white;
            flex-wrap: wrap;
            border: none;
        }

        .wave-top {
            position: absolute;
            top: 0;
            width: 100%;
        }

        .wave-top svg {
            display: block;
            height: auto;
        }

        .wave-text {
            flex: 1;
            max-width: 70%;
            text-align: left;
            display: flex;
            flex-direction: column;
            padding: 10px;
        }

        .wave-text h2 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            color: white;
        }

        .wave-text p {
            font-size: 16px;
            margin-bottom: 20px;
            color: white;
        }
        .wave-image {
            flex: 1;
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
            padding: 0;
        }

        .wave-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            position: relative;
            bottom: 0;
            right: 20px;
        }
        .slider-container {
            position: relative;
            width: 100%;
            height: 70vh;
            overflow: hidden;
        }
        .service-container h2 {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
            padding: 40px;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .active {
            opacity: 1;
        }
        .slide::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }
        .slide img {
            width: 40%;
            height: auto;
            position: relative;
            z-index: 2;
        }

        .slide-content {
            width: 50%;
            text-align: left;
            position: relative;
            z-index: 2;
        }

        .slide-content h2 {
            font-size: 32px;
            margin-bottom: 10px;
            color: #4f46e5;
            font-weight: bold;
        }

        .slide-content p {
            font-size: 18px;
            line-height: 1.6;
            color: #fff;
        }

        .slide:nth-child(1) { background-image: url('./assets/images/bgessay.jpg'); background-size: cover; background-position: center; }
        .slide:nth-child(2) { background-image: url('./assets/images/bgresearch.jpg'); background-size: cover; background-position: center; }
        .slide:nth-child(3) { background-image: url('./assets/images/bgprogramming.jpg'); background-size: cover; background-position: center; }
        .slide:nth-child(4) { background-image: url('./assets/images/bgcase.png'); background-size: cover; background-position: center; }
        .slide:nth-child(5) { background-image: url('./assets/images/bgdissertation.png'); background-size: cover; background-position: center; }
        .slide:nth-child(6) { background-image: url('./assets/images/bgthesis.png'); background-size: cover; background-position: center; }
        .happy-users-wrap {
            width: 100%;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            background: linear-gradient(to bottom, rgba(223, 221, 221, 0.8), white);
            background-blend-mode: overlay;
            border: none;
        }

        .container {
            max-width: 1200px;
            width: 100%;
        }

        .happy-users-main {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .left-user {
            width: 30%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .left-user img {
            width: 100%;
            max-width: 300px;
            height: auto;
        }

        .right-user {
            width: 70%;
            text-align: left;
        }

        .heading {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .sub-heading {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555;
        }

        .button-container-new {
            margin-top: 15px;
        }

        .button {
            display: inline-block;
            padding: 12px 20px;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
        }

        .button:hover {
            background:rgb(111, 105, 220);
            text-decoration: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero {
                height: 60vh;            
            }
            .hero h1 {
                font-size: 2rem;
            }
            .hero p {
                font-size: 18px;
            }
            .service-container {
                flex-direction: column;
                align-items: center;
            }
            .service-card {
                width: 100%;
            }
            .wave-section {
                height: 60vh;
            }

            .wave-text h2 {
                font-size: 18px;
            }

            .wave-text p {
                font-size: 14px;
            }
            .slide {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }

            .slide img {
                width: 80%;
                margin-bottom: 20px;
            }

            .slide-content {
                width: 100%;
            }

            .slide-content h2 {
                font-size: 24px;
            }

            .slide-content p {
                font-size: 16px;
            }
            .happy-users-main {
                flex-direction: column;
                text-align: center;
            }

            .left-user {
                width: 100%;
                margin-bottom: 20px;
            }

            .right-user {
                width: 100%;
                text-align: center;
            }
        }

    </style>
</head>
<body>
            <?php include 'templates/header.php'; ?>
            <div class="hero px-1">
                <h1>Our Expert Services</h1>
                <p class="text-white">Providing top-notch academic support to help you succeed</p>
            </div>
            <section aria-label="Our Services">
                <div class="service-container px-4">
                        <h2>We Help Master's Students with Assignments</h2>
                        <p>Our expert writers provide top-notch assignment assistance for master's students across various countries, ensuring academic excellence and timely delivery.</p>
                    <div class="service-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Expert Academic Assistance</h3>
                        <p>Specialized help for master's students with research, thesis, and coursework.</p>
                    </div>

                    <div class="service-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3>Global Reach</h3>
                        <p>Supporting students from Singapore, Malaysia, Europe, USA, UK, Australia, and Canada.</p>
                    </div>

                    <div class="service-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>Dedicated Support</h3>
                        <p>24/7 support for students to ensure their assignments meet academic standards.</p>
                    </div>

                    <div class="service-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>Plagiarism-Free Content</h3>
                        <p>All assignments are original, well-researched, and free from plagiarism.</p>
                    </div>
                </div>
            </section>

            <div class="slider-container">
                <div class="slide active">
                    <img src="./assets/images/essay.png" alt="Essay Writing">
                    <div class="slide-content">
                        <h2>Essay Writing</h2>
                        <p>We provide top-notch essay writing services with well-researched and structured content to help you achieve academic success.</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="./assets/images/research.png" alt="Research Papers">
                    <div class="slide-content">
                        <h2>Research Papers</h2>
                        <p>Our experts craft high-quality research papers, ensuring deep analysis and accurate citations for academic excellence.</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="./assets/images/programming.png" alt="Programming Assignments">
                    <div class="slide-content">
                        <h2>Programming Assignments</h2>
                        <p>Get assistance with coding projects, assignments, and debugging in various programming languages, ensuring accuracy and efficiency.</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="./assets/images/case.png" alt="Case Studies">
                    <div class="slide-content">
                        <h2>Case Studies</h2>
                        <p>Detailed case study analysis with real-world examples, helping you understand business, law, and healthcare scenarios.</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="./assets/images/dissertation.png" alt="Dissertation Writing">
                    <div class="slide-content">
                        <h2>Dissertation Writing</h2>
                        <p>Comprehensive dissertation writing with expert guidance, structured research, and proper formatting.</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="./assets/images/thesis.png" alt="Thesis Writing">
                    <div class="slide-content">
                        <h2>Thesis Writing</h2>
                        <p>High-quality thesis writing support, including literature review, methodology, and data analysis for academic success.</p>
                    </div>
                </div>
            </div>
            <script>
                let currentIndex = 0;
                const slides = document.querySelectorAll(".slide");

                function showSlide(index) {
                    slides.forEach(slide => slide.classList.remove("active"));
                    slides[index].classList.add("active");
                }

                function nextSlide() {
                    currentIndex = (currentIndex + 1) % slides.length;
                    showSlide(currentIndex);
                }

                setInterval(nextSlide, 3000); // Change slide every 3 seconds
            </script>
            <div class="happy-users-wrap">
                    <div class="container">
                        <div class="happy-users-main">
                            <div class="left-user">
                                <img src="./assets/images/books.png" alt="books">
                            </div>
                            <div class="right-user">
                                <div class="heading">Join our 1M+ happy users</div>
                                <div class="sub-heading">Get original papers written according to your instructions and save time for what matters most.</div>
                                <div class="button-container-new">
                                    <a href="./form.php" class="button">Order Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="wave-section">
                <div class="wave-top">
                    <svg viewBox="0 0 1440 320">
                        <path fill="#fff" d="M0,160L60,144C120,128,240,96,360,112C480,128,600,192,720,197.3C840,203,960,149,1080,128C1200,107,1320,117,1380,122.7L1440,128L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z"></path>
                    </svg>
                </div>
                <div class="wave-text">
                    <h2>Who Is This Online Assignment Help For?</h2>
                    <p>If you are facing challenges in managing your assignments, deadlines, or complex subjects, our expert team is here to help. We provide support tailored to your needs and ensure timely assistance.</p>
                </div>
                <div class="wave-image">
                    <img src="./assets/images/girlsolo.png" alt="Assignment Help">
                </div>
            </div>
            <?php include 'templates/footer.php'; ?>
</body>
</html>
