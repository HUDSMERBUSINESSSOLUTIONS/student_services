<?php
session_start();
include_once(__DIR__ . '/templates/chats.php');
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
            animation: slideIn 2s ease-in-out;
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
        .grid {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .expertise-list, .quality-list {
            list-style: none;
            padding: 0;
            text-align: center;
        }
        .expertise-list li, .quality-list li {
            background: white;
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            display: inline-block;
            transition: transform 0.3s ease;
    }
     .quality-list li:hover {
        transform: scale(1.1);
    }
        @media (max-width: 768px) {
            .service-container {
                flex-direction: column;
                align-items: center;
            }
            .service-card {
            width: 100%;
        }
        }
        .wave-section {
            position: relative;
            height: auto;
            background: #4f46e5;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            flex-wrap: wrap;
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
            margin-top: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            
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
        @media (max-width: 768px) {
            .wave-section {
                align-items: center;
                text-align: center;
            }

            .wave-text {
                max-width: 100%;
                padding: 10px;
            }

            .wave-image {
                max-width: 70%;
                padding: 10px;
            }

            .wave-image img {
                max-width: 100%;
                height: auto;
                right: 0;
                bottom: 0;
            }

            .wave-text h2 {
                font-size: 24px;
            }

            .wave-text p {
                font-size: 14px;
            }
        }
        .slider {
    position: relative;
    max-width: 100%;
    height: 70vh;
    overflow: hidden;
}

.slide {
    display: none;
    position: absolute;
    width: 100%;
    height: 100%;
    background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), center/cover no-repeat;
    color: white;
    padding: 20px;
    background-size: cover;
    background-position: center;
}

.slide .image-container {
    position: absolute;
    top: 50%;
    left: 5%;
    transform: translateY(-50%);
    width: 40%;
    opacity: 0.8;
}

.slide img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.slide .text-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%);
    color: white;
    padding: 20px;
    max-width: 45%;
    text-align: left;
}

.slide h2 {
    font-size: 50px;
    margin-bottom: 10px;
}

.slide p {
    font-size: 20px;
}

.active {
    display: block;
    animation: fade 1.5s;
}

@keyframes fade {
    from { opacity: 0.5; }
    to { opacity: 1; }
}

.nav {
    position: absolute;
    width: 100%;
    top: 50%;
    display: flex;
    justify-content: space-between;
    transform: translateY(-50%);
}

.prev, .next {
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    padding: 10px;
    cursor: pointer;
    border-radius: 50%;
}

.prev:hover, .next:hover {
    background-color: rgba(0, 0, 0, 0.8);
}

    </style>
</head>
<body class="m-0 p-0">
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
        <div class="slider">
    <div class="slide active" style="background-image: url('https://picsum.photos/1200/600?1');">
        <div class="image-container">
            <img src="https://picsum.photos/400/300" alt="Essay Writing">
        </div>
        <div class="text-container">
            <h2>Essay Writing</h2>
            <p>Get top-notch essay writing services to boost your academic performance.</p>
        </div>
    </div>
    <div class="slide" style="background-image: url('https://picsum.photos/1200/600?2');">
        <div class="image-container">
            <img src="https://picsum.photos/400/300" alt="Research Papers">
        </div>
        <div class="text-container">
            <h2>Research Papers</h2>
            <p>Professional research paper assistance from experienced writers.</p>
        </div>
    </div>
    <div class="slide" style="background-image: url('https://picsum.photos/1200/600?3');">
        <div class="image-container">
            <img src="https://picsum.photos/400/300" alt="Case Studies">
        </div>
        <div class="text-container">
            <h2>Case Studies</h2>
            <p>Thorough analysis and well-researched case studies for academic success.</p>
        </div>
    </div>
    <div class="slide" style="background-image: url('https://picsum.photos/1200/600?4');">
        <div class="image-container">
            <img src="https://picsum.photos/400/300" alt="Dissertation Writing">
        </div>
        <div class="text-container">
            <h2>Dissertation Writing</h2>
            <p>Expert guidance and support for your dissertation projects.</p>
        </div>
    </div>
    <div class="slide" style="background-image: url('https://picsum.photos/1200/600?5');">
        <div class="image-container">
            <img src="https://picsum.photos/400/300" alt="Thesis Writing">
        </div>
        <div class="text-container">
            <h2>Thesis Writing</h2>
            <p>Comprehensive thesis assistance to meet your academic needs.</p>
        </div>
    </div>
    <div class="nav">
        <span class="prev">&#10094;</span>
        <span class="next">&#10095;</span>
    </div>
</div>

    <script>
        let currentSlide = 0;
const slides = document.querySelectorAll(".slide");
const totalSlides = slides.length;

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.remove("active");
        if (i === index) {
            slide.classList.add("active");
        }
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    showSlide(currentSlide);
}

document.querySelector(".next").addEventListener("click", nextSlide);
document.querySelector(".prev").addEventListener("click", prevSlide);

showSlide(currentSlide);
setInterval(nextSlide, 3000);
</script>
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
