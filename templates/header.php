<?php
include_once(__DIR__ . '/../config/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            padding-top: 60px;
            background-color: #f4f4f4;
        }

        header {
            background-color: #6366F1;
            padding: 10px 0;
            color: white;
            position: fixed;
            top: 10px;
            left:10px;
            right:10px;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 20px
        }
        
        .nav-links {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            text-decoration: none;
        }
        
        .nav-links a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-size: 14px;
        }
        
        .nav-links a:hover {
            text-decoration: none;
        }
        
        .user-logo {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-logo i {
            font-size: 20px;
            cursor: pointer;
        }
        .custom-dropdown a {
            font-size: 16px; 
            color: #6366F1; 
        }

        .custom-dropdown i {
            font-size: 15px; 
            color: #6366F1; 
        }
        /* Floating Icons */
        .icon-button {
            position: fixed;
            font-size: 24px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(89, 32, 201, 0.1);
            z-index: 1000;
            height: 50px;
            width: 50px;
            display: flex;         
            justify-content: center; 
            align-items: center;  
        }

        .whatsapp-icon {
            left: 20px;
            background-color: #25D366;
            color: white;
            bottom: 40px;
            padding: 5px;
            text-decoration:none;
        }
        
        .move-arrow {
            bottom: 10px;
            right: 20px;
            background-color: #6366F1;
            color: white;
            padding: 4px 12px;
        }
        .menu-icon {
            display: none !important;
        }
        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #fff;
            overflow-x: hidden;
            transition: 0.3s;
            z-index: 2000;
            display: none; /* Initially hidden */
        }
        .order-btn{
            background-color: #fff;
            color: #6366F1;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
            margin-right: 10px;
            border: none;
        }
        .order-btn:hover{
            background-color:rgb(154, 155, 240);
            color:#fff;
        }
            @media (max-width: 768px) {
                header{
                    padding: 5px 3px;
                }
                .nav-links {
                    display: none;
                }
                .user-logo {
                    display: flex;
                    align-items: center;
                }
                .menu-icon {
                        font-size: 15px;
                        margin-left: 10px;
                        cursor: pointer;
                        display: flex !important;
                    }
                    .sidebar {
                    display: block; /* Show sidebar on mobile */
                }
                    .sidebar-title {
                padding: 15px;
                margin-bottom: 10px;
                color: #fff;
                background-color: #6366F1;
                text-align: left;
                border-bottom-left-radius: 0px;
                border-bottom-right-radius: 60px;
            }
            
            
            .sidebar a {
                text-decoration: none;
                padding: 10px 20px;
                text-align:left;
                display: block;
                color: #6366F1;
            }
            
            .sidebar a:hover {
                background-color:rgb(120, 122, 210);
                color: white;
            }
            
            .sidebar a i {
                margin-right: 10px;
            }
            
            .logout-btn {
                position: absolute;
                bottom: 0;
                width: 100%;
                color: white !important;
                text-align: center;
                border-top-left-radius: 0px;
                border-top-right-radius: 80px;
                background-color: #6366F1;
                padding: 10px;
            }
            }
            @media (max-width: 768px) {
                .logo {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                }

                .logo-img {
                    margin-bottom: 5px;
                }

                .logo-text {
                    margin-top: 0;
                    font-size: 6px
                }
            }

            @media (min-width: 769px) {
                .logo {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }

                .logo-text {
                    margin: 0;
                    font-size: 12px
                }
            }

    </style>
</head>
<body>
 
<header class="px-3">
<div class="logo d-flex flex-column flex-md-row align-items-center">
    <img src="assets/images/logo.png" alt="Logo" class="logo-img" style="height: 35px; width:35px">
    <h5 class="m-0 logo-text">Hudsmer Student Service</h5>
</div>

        <div class="nav-links">
            <a href="/assignmenthelp/index.php">Home</a>
            <a href="/assignmenthelp/services.php">Services</a>
            <a href="/assignmenthelp/about_us.php">About Us</a>
            <a href="/assignmenthelp/how_its_work.php">How Its Work</a>
        </div>
 
        <div class="user-logo">
            <button onclick="window.location.href='/assignmenthelp/form.php'" class="order-btn">Order</button>
            <i class="fas fa-user m-0 p-0 fa-lg text-white dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
            <i class="fas fa-bars menu-icon ml-2" onclick="toggleSidebar()"></i>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <a class="dropdown-item custom-dropdown" href="/assignmenthelp/templates/signup.php">
                    <i class="fas fa-user-plus mr-1"></i> Signup
                </a>
                <a class="dropdown-item custom-dropdown" href="/assignmenthelp/templates/login.php">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                </a>
            </div>
        </div>
</header>
 
<!-- Mobile Menu -->
<div id="mySidebar" class="sidebar">
    <!-- Sidebar Title with Education Icon and Close Button -->
    <div class="sidebar-title d-flex justify-content-start align-items-center p-3">
    <img src="assets/images/logo.png" alt="Logo" class="logo-img" style="height: 35px; width:35px">
    <h5 class="m-0" style="font-size :12px; font-weight: bold">Hudsmer Student Service</h5>
    </div>
 
    <!-- Sidebar Links -->
    <a href="/assignmenthelp/index.php"><i class="fas fa-home"></i> Home</a>
    <a href="/assignmenthelp/services.php"><i class="fas fa-concierge-bell"></i> Services</a>
    <a href="/assignmenthelp/about_us.php"><i class="fas fa-info-circle"></i> About Us</a>
    <a href="/assignmenthelp/how_its_work.php"><i class="fas fas fa-cogs"></i>How Its Work</a>
 
    <a href="/assignmenthelp/logout.php" class="logout-btn text-center">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>
 
 
<!-- WhatsApp Icon -->
<a href="https://api.whatsapp.com/send?phone=917845337261" class="icon-button whatsapp-icon" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>
 
<!-- Move Arrow -->
<div class="icon-button move-arrow" id="moveArrow" onclick="moveToTop()">
    <i class="fas fa-arrow-up"></i>
</div> 
<script>
        $(document).ready(function () {
        $('#userDropdown').on('click', function () {
            $('.dropdown-menu').toggleClass('show');
        });

        // Close dropdown when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu').removeClass('show');
            }
        });
    });
    function toggleSidebar() {
    const sidebar = document.getElementById('mySidebar');
    if (sidebar.style.width === '250px') {
        sidebar.style.width = '0';
    } else {
        sidebar.style.width = '250px';
    }
}
function closeSidebar() {
        document.getElementById("mySidebar").style.display = "none";
    }
 
    function moveToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
 
function toggleDropdown() {
        const dropdown = document.getElementById('dropdown-menu');
        dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
    }
</script>
 
</body>
</html>