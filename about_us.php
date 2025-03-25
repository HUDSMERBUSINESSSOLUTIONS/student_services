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
    <title>Academic Support Platform</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .hero{
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('./assets/images/about_bg.jpg') no-repeat center center;
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

        .about-map {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            padding: 20px;
            background-color: #fff;
        }

        .map-container {
            flex: 1;
        }

        .map-container video {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .content-container {
            flex: 1;
            padding: 20px;
            text-align: center;
        }
        .fixed-bg {
            background: url('./assets/images/group1.jpg') no-repeat center center fixed;
            background-size: cover;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 50px;
        }

        .custom-box {
            background-color: rgba(255, 255, 255, 0.8); 
            border-radius: 15px;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
        }
        .custom-box h2 i {
            color: #4f46e5;
            animation: iconBounce 1s ease-in-out infinite;
        }

        /* Bounce Animation */
        @keyframes iconBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        /* Heading Style */
        .achievements-section{
            background-color: #f6f6f6;
            padding: 40px 0;
        }
        .animated-heading {
            text-align: center;
            color: #4f46e5;
            font-weight: bold;
            font-size: 2.5rem;
            margin: 20px 0;
            text-shadow: 2px 2px 8px rgba(79, 70, 229, 0.5);
        }
        .animated-heading::after {
    content: "";
    display: block;
    width: 20%; 
    height: 4px; 
    background: linear-gradient(135deg, #4f46e5, #7c3aed); /* Gradient line color */
    margin: 5px auto 0; 
    border-radius: 10px;
}       h2 {
            text-align: center;
            color: #4f46e5;
            font-weight: bold;
            font-size: 2.5rem;
            margin: 20px 0;
            text-shadow: 2px 2px 8px rgba(79, 70, 229, 0.5);
        }
        h2::after {
    content: "";
    display: block;
    width: 20%; 
    height: 4px; 
    background: linear-gradient(135deg, #4f46e5, #7c3aed); /* Gradient line color */
    margin: 5px auto 0; 
    border-radius: 10px;
}

        @keyframes highlight {
            0% { text-shadow: 2px 2px 8px rgba(79, 70, 229, 0.5); }
            100% { text-shadow: 2px 2px 20px rgba(79, 70, 229, 1); }
        }

        .stat i {
            color: #4f46e5;
            font-size: 3rem;
            margin-bottom: 10px;
            animation: iconBounce 2s linear infinite;
        }

        .stat-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin: 40px 0;
        }

        .stat {
            text-align: center;
            padding: 20px 40px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .count {
            font-weight: bold;
            font-size: 18px;
        }
        .Core-section {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            text-align: center;
            padding: 60px 20px;
            color: white;
        }

        .Core-section h2 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #fff;
        }

        .Core-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            justify-items: center;
            margin: 0 auto;
            max-width: 1200px;
        }

        .Core-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            width: 100%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            color: white;
            backdrop-filter: blur(10px);
        }

        .Core-card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .Core-card i {
            font-size: 40px;
            margin-bottom: 15px;
            color: #fbbf24;
        }

        .Core-card h3 {
            font-size: 22px;
            margin: 10px 0;
            color: #fff;
        }

        .Core-card p {
            font-size: 16px;
            color: #ddd;
            margin: 0;
        }

        @media (max-width: 768px) {
            .Core-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }


        @media (max-width: 768px) {
            .about-map {
                flex-direction: column;
                text-align: center;
            }

            .map-container,
            .content-container {
                width: 100%;
            }
        }
            .Expertise-section {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                flex-wrap: wrap;
                background: #fff;
                padding: 60px 20px;
                color: white;
            }

            .Expertise-section h2 {
                font-size: 32px;
                margin-bottom: 20px;
                color: #000;
                text-align: center;
                font-weight: bold;
            }

            .Expertise-section img {
                max-width: 100%;
                height: auto;
                border-radius: 15px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            }

            .expertise-list {
                list-style: none;
                padding: 0;
                margin: 0;
                display: flex;
                justify-content: space-between;
                flex-direction: column;
            }

            .expertise-list li {
                margin: 15px 0;
                font-size: 16px;
                background: linear-gradient(135deg,rgb(242, 241, 247),rgb(7, 65, 237));
                color: #333;
                padding: 10px 15px;
                border-radius: 30% 0 0 30%;
                display: inline-block;
                width: 50%;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
                font-weight: bold;
            }

            .expertise-list li:nth-child(odd) {
                text-align: center;
                margin-left: auto;
            }

            .expertise-list li:nth-child(even) {
                text-align: center;
                margin-right: auto;
            }

            @media (max-width: 768px) {
                .Expertise-section {
                    flex-direction: column;
                    text-align: center;
                }
                .expertise-list li {
                    text-align: center;
                    margin-left: auto;
                    margin-right: auto;
                }
            }

    </style>
</head>
<body class="m-0 p-0">
<?php include 'templates/header.php'; ?>
    <div class="hero">
        <h1>Empowering Your Academic Journey</h1>
        <p class="text-white">Transforming challenges into achievements, one assignment at a time</p>
    </div>
    <div class="about-map">
        <div class="map-container">
            <video autoplay loop muted>
                <source src="assets/images/map.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="content-container">
            <h2>Professional Assignment Writing Help Services for Students Worldwide</h2>
            <p>
                Feeling the weight of assignments? The team at Assignment in Need understands! With a track record of
                45,000+ assignments delivered and 30,000+ happy clients, they have established themselves as a trusted
                provider of assignment writing help for students in the UK, Australia, Canada, Malaysia, UAE, and Spain.
                Our subject-matter experts are ready to assist with any academic challenge, ensuring that students not only
                complete assignments but also gain a deeper understanding of the material through clear, step-by-step
                explanations. Whether it's research, writing, or organizing ideas, they provide affordable support to help
                students excel without stress. Meeting deadlines and boosting grades isn't just their goal—it's their expertise.
            </p>
            <a href="./form.php">
            <button style="background: #4f46e5; color: white; padding: 10px 50px; border-radius: 50px; margin: 10px; font-weight: 500; font-size: 20px; text-decoration: none;">
            Order Now
                </button>
            </a>
        </div>
    </div>
    <div class="container-fluid p-5">
        <div class="fixed-bg"></div>
<div class="content">
    <div class="col-lg-6 col-md-6 col-12 mb-4 card custom-box ms-auto">
        <h2><i class="fas fa-bullseye"></i> Our Vision</h2>
        <p>To be the global leader in academic support, fostering a community where every student has the opportunity to excel and reach their full potential. We envision a world where quality education knows no boundaries.</p>
    </div>

    <div class="col-lg-6 col-md-6 col-12 mb-4 card custom-box me-auto">
        <h2><i class="fas fa-brain"></i> Our Mission</h2>
        <p>To empower students with comprehensive academic support that not only helps them succeed in their assignments but also enhances their understanding and critical thinking abilities for long-term academic excellence.</p>
    </div>

    <div class="col-lg-6 col-md-6 col-12 mb-4 card custom-box ms-auto">
        <h2><i class="fas fa-star"></i> Our Values</h2>
        <p>We believe in integrity, excellence, and innovation. Our team is committed to delivering top-quality academic support while upholding ethical standards, encouraging creativity, and fostering continuous learning.</p>
    </div>
</div>

    </div>
    <div class="achievements-section">
        <h2 class="animated-heading">Our Achievements</h2>
        <div class="stat-container">
            <div class="stat">
                <i class="fas fa-bullseye"></i>
                <span class="count" data-count="50000">0</span>+
                <p>Students Helped</p>
            </div>
            <div class="stat">
                <i class="fas fa-brain"></i>
                <span class="count" data-count="95">0</span>%
                <p>Satisfaction Rate</p>
            </div>
            <div class="stat">
                <i class="fas fa-award"></i>
                <span class="count" data-count="100">0</span>+
                <p>Expert Writers</p>
            </div>
            <div class="stat">
                <i class="fas fa-globe"></i>
                <span class="count" data-count="25">0</span>+
                <p>Countries Served</p>
            </div>
        </div>
    </div>
    <script>
        // Number Counter Animation
        document.addEventListener("DOMContentLoaded", function() {
            let counters = document.querySelectorAll(".count");
            let speed = 50;

            counters.forEach((counter) => {
                let updateCount = () => {
                    let target = +counter.getAttribute("data-count");
                    let count = +counter.innerText;
                    let increment = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(updateCount, 40);
                    } else {
                        counter.innerText = target;
                    }
                };

                updateCount();
            });
        });
    </script>

    <div class="Expertise-section" style="display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
        <div style="flex: 1; text-align: center;">
            <img src="./assets/images/transparent-job.jpg" alt="Expertise">
        </div>
        <div style="flex: 1;">
            <h2 class="">Our Expertise</h2>
            <ul class="expertise-list">
                <li>Business & Management</li>
                <li>Computer Science</li>
                <li>Engineering</li>
                <li>Law & Justice</li>
                <li>Medicine & Healthcare</li>
                <li>Arts & Humanities</li>
            </ul>
        </div>
    </div>
    <section class="Core-section">
    <h2>Our Core Values</h2>
    <div class="Core-grid">
        <div class="Core-card">
            <i class="fas fa-lightbulb"></i>
            <h3>Excellence</h3>
            <p>We ensure high academic standards.</p>
        </div>
        <div class="Core-card">
            <i class="fas fa-users"></i>
            <h3>Collaboration</h3>
            <p>We work with students to achieve their goals.</p>
        </div>
        <div class="Core-card">
            <i class="fas fa-flask"></i>
            <h3>Innovation</h3>
            <p>We embrace modern learning methods.</p>
        </div>
        <div class="Core-card">
            <i class="fas fa-shield-alt"></i>
            <h3>Integrity</h3>
            <p>We value honesty and transparency.</p>
        </div>
    </div>
</section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let elements = document.querySelectorAll(".card, .stat, .step, .quality-list li");
            elements.forEach(el => {
                el.style.opacity = "0";
                el.style.transform = "translateY(30px)";
            });

            function animate() {
                elements.forEach(el => {
                    let pos = el.getBoundingClientRect().top;
                    if (pos < window.innerHeight - 100) {
                        el.style.opacity = "1";
                        el.style.transform = "translateY(0)";
                        el.style.transition = "all 0.5s ease-out";
                    }
                });
            }
            window.addEventListener("scroll", animate);
            animate();
        });
    </script>
</body>
    <?php include 'templates/footer.php'; ?>
</html>