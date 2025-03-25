
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Helping Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .heading-content {
        width: 100%;
        background-color: #000;
        height: 60vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .heading-content h2 {
        margin: 0;
        color: white;
        font-size: 36px;
        margin-bottom: 20px;
    }

    .heading-content p {
        font-size: 1.5rem;
            margin-top: 10px;
            animation: slideIn 2s ease-in-out;
            color:"#fff"
    }

.form-section {
    background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                          url('https://images.unsplash.com/photo-1506784983877-45594efa4cbe');
        background-size: cover;
        background-position: center;
        text-align: center;
}
.form {
    position: relative;
    top: -80px
}
.card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Rotating Border Animation */
        .icon-circle {
            position: relative;
            border: 4px solid #6366F1;
            border-radius: 50%;
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: rotate 4s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* Icon Style */
        .icon-circle i {
            font-size: 28px;
            color: #6366F1;
        }
        @media only screen and (max-width: 768px) {
    .heading-content h2 {
        font-size: 22px;
        margin-bottom: 15px;
    }

    .heading-content p {
        font-size: 16px;
    }

    h2, h3 {
        font-size: 20px;
    }

    p {
        font-size: 14px;
    }

    .icon-circle i {
        font-size: 22px;
    }
}
</style>

</head>
<body class="m-0 p-0">
<?php include 'templates/header.php'; ?>
<!-- Heading Section -->
<section class="heading-content">
        <h2>Assignment Helping Project</h2>
        <p>Your one-stop solution for assignment submissions and support.</p>
</section>


<div class="mx-auto p-4">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold mb-4">Why Choose Our Assignment Service?</h2>
        <p class="text-gray-600 max-w-3xl mx-auto">
            We provide comprehensive assignment assistance to help you achieve academic excellence.
        </p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
        <div class="bg-gray-50 p-6 rounded-lg text-center">
            <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clock text-indigo-600 text-3xl"></i>
            </div>
            <h3 class="text-xl text-center font-bold mb-2">On-Time Delivery</h3>
            <p class="text-gray-600">We guarantee timely delivery of your assignments, no matter how tight the deadline.</p>
        </div>

        <div class="bg-gray-50 p-6 rounded-lg text-center">
            <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-indigo-600 text-3xl"></i>
            </div>
            <h3 class="text-xl text-center font-bold mb-2">Quality Assurance</h3>
            <p class="text-gray-600">All assignments are written from scratch and checked for plagiarism before delivery.</p>
        </div>

        <div class="bg-gray-50 p-6 rounded-lg text-center">
            <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-upload text-indigo-600 text-3xl"></i>
            </div>
            <h3 class="text-xl text-center font-bold mb-2">Easy Submission</h3>
            <p class="text-gray-600">Our simple order form makes it easy to submit your requirements and get started.</p>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
</body>
</html>
