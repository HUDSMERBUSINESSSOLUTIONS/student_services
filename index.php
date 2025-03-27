<?php
session_start();
include './chats.php';
include './components/loading.php';

showLoading();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Help - Home</title>

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <link rel="shortcut icon" href="./assets/images/favicon.ico" type="image/x-icon" />
    <!-- Custom Styles -->
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- Tailwind CSS & Animation Library -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
        }
        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Hero Section */
        .hero-section {
            background:
                linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                url('./assets/images/indexbg.jpg') no-repeat center center;
                background-size: cover;
            color: #fff;
            animation: fadeIn 2s ease-in-out;
            height: 80vh;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin:0;
        }


        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
            animation: slideIn 1.5s ease-in-out;
        }

        .hero-section p {
            font-size: 1.5rem;
            margin-top: 10px;
            animation: slideIn 2s ease-in-out;
            color:"#fff"
        }

        .hero-buttons a {
            transition: transform 0.3s ease, background 0.3s;
        }

        .hero-buttons a:hover {
            transform: scale(1.1);
        }

        /* Features Section */
        .features-section {
            padding: 50px 20px;
            background: #f9f9f9;
            text-align: center;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .section-header h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .section-header p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 30px;
        }

        /* Grid System */
        .grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 20px;
        }

        @media (min-width: 768px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Feature Cards */
        .feature-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .icon-wrapper {
            font-size: 40px;
            color: #4f46e5;
            margin-bottom: 15px;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            color: #333;
        }

        .feature-card p {
            font-size: 1rem;
            color: #666;
        }

        .text-primary {
            color: #4f46e5 !important;
            font-weight: 600;
            text-decoration: none;
        }

        .text-primary:hover {
            text-decoration: none;
        }

        /* Scrolling Cards Section */
        .scrolling-wrapper {
            overflow: hidden;
            width: 100%;
            display: flex;
            justify-content: center;
            background-color: white;
            position: relative;
        }

        .scrolling-content {
            display: flex;
            transition: transform 1s ease;
        }

        .cards {
            flex: 0 0 350px;
            margin: 10px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            text-align: left;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .cards:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .stars {
            line-height: 1.2;
        }

        .profile-circle {
            width: 40px;
            height: 40px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            font-weight: bold;
        }

        .stars {
            color: #FFD700;
            font-size: 18px;
        }

        .fixed-text {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 80px;
            background-color: #6366F1;
            padding: 10px;
            color: white;
            text-align: center;
            font-weight: bold;
            z-index: 1;
            border-radius: 0 0 0 120px;
            height: 100%;
        }

        .section-review {
            background-color: #fff;
            padding: 50px 0;
        }
        .stat-number, .stat-label {
            color: white;
        }
            /* Responsive Design */
        @media (max-width: 768px) {
            .hero-section {
                height: 60vh;            }
            .hero-section h1 {
                font-size: 2rem;
            }
            .hero-section p {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
<body>
<?php include 'templates/header.php'; ?>
            <!-- Hero Section -->
            <section class="hero-section">
                <h1 class="font-bold" style="animation: slideIn 1.5s ease-in-out;">Expert Assignment Help for Students</h1>
                <p class="text-white" style="animation: slideIn 2s ease-in-out;">Get professional assistance with your assignments, essays, and projects. We deliver high-quality work on time.</p>

                <div class="hero-buttons">
                    <a href="./form.php" class="btn"
                        style="transition: transform 0.3s ease, background 0.3s;background-color: #4f46e5;color: #fff">
                        Order Now
                    </a>
                    <a href="./how_its_work.php" class="btn btn-outline-light"
                        style="transition: transform 0.3s ease, background 0.3s;">
                        How It Works
                    </a>
                </div>
            </section>
            <section class="features-section">
                <div class="container-fluid">
                    <div class="section-header">
                        <h2>Why Choose Our Assignment Help?</h2>
                        <p>We provide comprehensive assignment help services tailored to meet your academic needs.</p>
                    </div>
                    <div class="grid">
                        <div class="feature-card">
                            <div class="icon-wrapper">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h3>Expert Writers</h3>
                            <p>Our team consists of qualified professionals with advanced degrees in various fields.</p>
                            <a class="text-primary font-medium hover:underline text-left" href="about_us.php">Learn More →</a>
                        </div>

                        <div class="feature-card">
                            <div class="icon-wrapper">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3>On-Time Delivery</h3>
                            <p>We guarantee timely delivery of your assignments, no matter how tight the deadline.</p>
                            <a class="text-primary font-medium hover:underline text-left" href="about_us.php">Learn More →</a>
                        </div>

                        <div class="feature-card">
                            <div class="icon-wrapper">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3>Plagiarism-Free</h3>
                            <p>All assignments are written from scratch and checked for plagiarism before delivery.</p>
                            <a class="text-primary font-medium hover:underline text-left" href="about_us.php">Learn More →</a>
                        </div>

                        <div class="feature-card">
                            <div class="icon-wrapper">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3>24/7 Support</h3>
                            <p>Our customer support team is available round the clock to assist you.</p>
                            <a class="text-primary font-medium hover:underline text-left" href="about_us.php">Learn More →</a>
                        </div>

                        <div class="feature-card">
                            <div class="icon-wrapper">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <h3>All Subjects</h3>
                            <p>We cover a wide range of subjects including programming, math, science, and humanities.</p>
                            <a class="text-primary font-medium hover:underline text-left" href="about_us.php">Learn More →</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="py-5 text-white text-center" style="background-image: url('./assets/images/blue-bg.jpg'); background-size: cover; background-position: center;">
    <div class="container">
        <div class="row text-white" id="stats-container"></div>
    </div>
</section>


            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const statsData = [{
                            number: 10000,
                            label: "Completed Assignments"
                        },
                        {
                            number: 98,
                            label: "Satisfaction Rate"
                        },
                        {
                            number: 500,
                            label: "Expert Writers"
                        },
                        {
                            number: 24,
                            label: "Customer Support"
                        }
                    ];

                    const statsContainer = document.getElementById("stats-container");

                    function animateNumber(element, start, end, duration) {
                        let startTime = null;

                        function step(timestamp) {
                            if (!startTime) startTime = timestamp;
                            const progress = Math.min((timestamp - startTime) / duration, 1);
                            element.textContent = Math.floor(progress * (end - start) + start);
                            if (progress < 1) {
                                window.requestAnimationFrame(step);
                            } else {
                                element.textContent = end; 
                            }
                        }

                        window.requestAnimationFrame(step);
                    }

                    statsData.forEach(stat => {
                        const statDiv = document.createElement("div");
                        statDiv.className = "col-md-3";

                        const statNumber = document.createElement("h3");
                        statNumber.className = "display-5 stat-number";
                        statNumber.textContent = "0";

                        const statLabel = document.createElement("p");
                        statLabel.className = "stat-label";
                        statLabel.textContent = stat.label;

                        statDiv.appendChild(statNumber);
                        statDiv.appendChild(statLabel);
                        statsContainer.appendChild(statDiv);

                        // Always animate numbers on page load/refresh
                        animateNumber(statNumber, 0, stat.number, 1500);
                    });
                });
            </script>


            <!-- Global Presence Section -->
            <section class="py-20 text-dark text-center">
                <h2 class="text-3xl font-bold">Our Global Presence</h2>
                <p class="text-xl text-dark-300 mb-10">
                    We provide assignment help to students across the globe, with specialized services for different regions.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="locationGrid"></div>
            </section>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const locations = [{
                            country: "Australia",
                            image: "./assets/images/Australia.jpg",
                            description: "Australian university standards and requirements."
                        },
                        {
                            country: "Canada",
                            image: "./assets/images/Canada.jpg",
                            description: "Dedicated support for Canadian students."
                        },
                        {
                            country: "Singapore",
                            image: "./assets/images/Singapore.jpg",
                            description: "Assignment assistance for Singapore universities."
                        },
                        {
                            country: "Malaysia",
                            image: "./assets/images/Malaysia.jpg",
                            description: "Customized support for Malaysian students."
                        },
                        {
                            country: "Europe",
                            image: "./assets/images/london.jpg",
                            description: "Academic help following European education standards."
                        },
                        {
                            country: "USA",
                            image: "./assets/images/USA.jpg",
                            description: "Expert academic guidance for US universities."
                        }
                    ];
                    const locationGrid = document.getElementById("locationGrid");

                    locations.forEach((location) => {
                        const locationDiv = document.createElement("div");
                        locationDiv.className = "rounded-xl shadow-lg overflow-hidden transform transition duration-500 hover:scale-105";
                        locationDiv.innerHTML = `
                        <div class="h-48 overflow-hidden">
                            <img src="${location.image}" alt="${location.country}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold">${location.country}</h3>
                            <p class="text-gray-400">${location.description}</p>
                        </div>
                    `;
                        locationGrid.appendChild(locationDiv);
                    });
                });
            </script>
            <section class="section-review">
                <h2 class="text-4xl font-bold text-center mb-10">What Our Clients Say</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mt-4">
                    Don't just take our word for it. Here's what students have to say about our services.
                </p>
                <div class="scrolling-wrapper">
                    <div class="fixed-text">There have some reviews</div>
                    <div class="scrolling-content" id="testimonialContainer">

                        <!-- Testimonial Card -->
                        <div class="cards">
                            <div class="profile">
                                <div class="profile-circle">S</div>
                                <h4>Sarah</h4>
                            </div>
                            <h2>University of Sydney</h2>
                            <p>"I was really struggling with my assignment, but this service saved me! The quality was top-notch, and I got an A+!"</p>
                            <div class="stars">★★★★☆</div>
                        </div>

                        <div class="cards">
                            <div class="profile">
                                <div class="profile-circle">J</div>
                                <h4>Johnson</h4>
                            </div>
                            <h2>University of Melbourne</h2>
                            <p>"Absolutely amazing service! The support team was very helpful, and the assignment was delivered on time. Highly recommend!"</p>
                            <div class="stars">★★★★★</div>
                        </div>

                        <div class="cards">
                            <div class="profile">
                                <div class="profile-circle">S</div>
                                <h4>Sri</h4>
                            </div>
                            <h2>University of Toronto</h2>
                            <p>"The quality exceeded my expectations. The writer followed all my instructions and delivered a well-structured paper."</p>
                            <div class="stars">★★★★☆</div>
                        </div>

                        <div class="cards">
                            <div class="profile">
                                <div class="profile-circle">A</div>
                                <h4>Alice</h4>
                            </div>
                            <h2>University of British Columbia</h2>
                            <p>"I was skeptical at first, but they proved me wrong. The paper was well-researched and formatted correctly. Great job!"</p>
                            <div class="stars">★★★★★</div>
                        </div>

                    </div>
                </div>
            </section>

            <script>
                const container = document.getElementById('testimonialContainer');

                function scrollLeft() {
                    const firstCard = container.firstElementChild;
                    container.style.transition = 'transform 0.5s ease'; // Faster transition
                    container.style.transform = 'translateX(-360px)'; // Adjusted for card width

                    setTimeout(() => {
                        container.style.transition = 'none';
                        container.appendChild(firstCard); // Move first card to the end
                        container.style.transform = 'translateX(0)'; // Reset position
                    }, 500); // Match transition duration
                }

                setInterval(scrollLeft, 2000); // Faster move interval
            </script>

            <!-- Call To Action -->
            <section class="py-20 text-white text-center" style="background-color: #4f46e5">
                <h2 class="text-3xl font-bold">Ready to Excel in Your Academics?</h2>
                <p class="text-xl mb-8 text-white">Submit your assignment details today and let our experts handle the rest.</p>
                <a href="./form.php" 
                    class="px-3 py-1" 
                    style="transition: transform 0.3s ease, background 0.3s; background-color: #fff; color: #4f46e5; text-decoration: none; border-radius: 8px; padding: 10px 20px; display: inline-block;fontsize: 18px">
                    Get Started Now
                </a>

            </section>

        </div>
    <?php include 'templates/footer.php'; ?>
</body>

</html>