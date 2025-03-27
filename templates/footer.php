
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animated Footer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            animation: slideDown 0.5s ease;
        }
        footer {
            background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
            color: #fff;
            padding: 40px 0;
            position: relative;
            overflow: hidden;
        }
        .footer-content {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
            gap: 20px;
        }
        .footer-section {
            flex: 1;
            min-width: 250px;
            padding: 20px;
            transition: transform 0.3s ease, color 0.3s;
            flex-direction: column;
        }
        .footer-section h3 {
            margin-bottom: 15px;
            font-size: 20px;
            color: #4f46e5;
            position: relative;
        }
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .footer-section ul {
            list-style-type: none;
            padding: 0;
        }
        .footer-section ul li {
            margin: 8px 0;
            position: relative;
            display: block;
            transition: transform 0.3s ease;
        }
        .footer-section ul li a {
            text-decoration: none;
            color: #ddd;
            transition: color 0.3s, transform 0.3s;
        }
        .footer-section ul li:before {
            content: '>> ';
            color: #fff;
            margin-right: 5px;
            transition: color 0.3s, transform 0.3s;
        }
        .footer-section ul li:hover a,
        .footer-section ul li:hover:before {
            color: #4f46e5;
            transition: transform 0.3s ease;
            transform: translateX(5px);
        }
        .footer-bottom {
            text-align: center;
            padding: 10px;
            background: #111;
            margin-top: 20px;
            font-size: 14px;
        }
        .footer-bottom p {
            margin: 0;
            color: #888;
        }
        .footer-section:nth-child(3) p {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ddd;
        }
        .footer-section:nth-child(3) p img {
            width: 20px;
            height: 15px;
        }
        .footer-section:nth-child(4) p {
            color: #fff;
        }
    </style>
</head>
<body>
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="./services.php">Services</a></li>
                    <li><a href="./about_us.php">About Us</a></li>
                    <li><a href="./how_its_work.php">How Its Work</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Our Services</h3>
                <ul>
                    <li><a href="./services.php">Essay Writing</a></li>
                    <li><a href="./services.php">Research Papers</a></li>
                    <li><a href="./services.php">Programming Assignments</a></li>
                    <li><a href="./services.php">Case Studies</a></li>
                    <li><a href="./services.php">Dissertation Writing</a></li>
                    <li><a href="./services.php">Thesis Writing</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Our Locations</h3>
                <p><img src="https://flagcdn.com/au.svg" alt="Australia Flag"> Australia</p>
                <p><img src="https://flagcdn.com/ca.svg" alt="Canada Flag"> Canada</p>
                <p><img src="https://flagcdn.com/gb.svg" alt="UK Flag"> United Kingdom</p>
                <p><img src="https://flagcdn.com/us.svg" alt="US Flag"> United States</p>
                <p><img src="https://flagcdn.com/nz.svg" alt="New Zealand Flag"> New Zealand</p>
                <p><img src="https://flagcdn.com/ie.svg" alt="Ireland Flag"> Ireland</p>
            </div>
            <div class="footer-section">
                <h3>Contact Info</h3>
                <p><i class="fas fa-envelope"></i> support@hbsthesis.co.uk </p>
                <p><i class="fas fa-phone"></i> +784 586 7261</p>
                <p><i class="fas fa-map"></i> 123 Assignment Street</p>
                <p><i class="fas fa-clock"></i> Mon-Fri: 9:00 AM - 6:00 PM</p>
                <p><i class="fas fa-headset"></i> 24/7 Support Available</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Assignment Help. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
