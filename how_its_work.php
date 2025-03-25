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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('./assets/images/howitswork.jpg') no-repeat center center;
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

        .hero-section h1 {
            font-size: 3.5rem;
            margin-bottom: 10px;
            animation: slideDown 1s ease;
        }

        .hero-section p {
            font-size: 1.5rem;
            margin-top: 10px;
            animation: slideIn 2s ease-in-out;
            color:"#fff";
            animation: slideUp 1s ease;
        }

        .hero-section button {
            animation: bounce 1.5s infinite;
        }

        .steps-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #fff;
            align-items: center;
            padding-top: 30px;
        }

        .progress-bar {
            width: 90%;
            height: 10px;
            background-color: #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background-color: #6c63ff;
            width: 0;
            transition: width 1s ease;
        }

        .step-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            color: #6c63ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px;
            font-size: 1.5rem;
            transition: transform 0.3s ease;
            border: 1px dashed #6c63ff;
        }

        .step-circle.active {
            background-color: #6c63ff;
            transform: scale(1.1);
            color: #fff;
        }

        .image-content-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            animation: fadeIn 1s ease-in-out;
        }
        .image-display {
            max-width: 50%;
        }
        .image-display img {
            max-width: 50%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.5s ease;
        }

        .image-display img:hover {
            transform: scale(1.05);
        }

        .step-content {
            text-align: left;
            color: #333;
            animation: slideIn 1s ease;
        }

        .step-content h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .step-content p {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateX(-50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .faq-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 70px 20px;
            background-color:rgb(205, 194, 194);
        }
        .faq-section {
            width: 70%;
            margin-bottom: 20px;
        }
        .faq-heading {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            display: inline-block;
            color: #4f46e5;
        }
        .faq-item {
            background-color: white;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .faq-question {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: bold;
}

 .right-icon {
    margin: 0 10px;
    transition: transform 0.3s ease;
}

.active .right-icon {
    transform: rotate(180deg);
}

        .faq-item:hover {
            transform: scale(1.02);
        }
        .faq-answer {
            display: none;
            margin-top: 10px;
            color: #555;
        }
        .active .faq-answer {
            display: block;
        }
        .faq-image {
            width: 30%;
            display: flex;
            justify-content: flex-end;
            align-items: end;
        }
        .faq-image img {
            width: 450px;
            height: 300px;
            border-radius: 8px;
        }
        @media (max-width: 768px) {
            .faq-section, .faq-image {
                width: 100%;
            }
            .faq-image {
                display: none;
            }
        }
        .stepImage-container{
            display: flex;
            height:300px;
        }
    </style>
</head>
<body class="m-0 p-0">
<?php include 'templates/header.php'; ?>
    <div class="hero-section">
        <h1>How It Works</h1>
        <p class="text-white">Your journey to academic excellence made simple and efficient</p>
        <a href="./form.php">
            <button class="btn btn-primary">Order Now</button>
        </a>
        </div>
        <div class="steps-container">
        <h2 class="text-center fw-bold">Our Process</h2>
        <h4 class="text-center text-muted mb-2">Follow these simple steps to get started</h4>
 <div>
 <div class="progress-bar mt-4">
                <div id="progressFill" class="progress-bar-fill"></div>
            </div>
            <div class="d-flex justify-content-around">
                <div class="step-circle" id="step1"><i class="fas fa-file-alt"></i></div>
                <div class="step-circle" id="step2"><i class="fas fa-user-check"></i></div>
                <div class="step-circle" id="step3"><i class="fas fa-spinner"></i></div>
                <div class="step-circle" id="step4"><i class="fas fa-file-download"></i></div>
                <div class="step-circle" id="step5"><i class="fas fa-smile"></i></div>
            </div></div>
            <div class="container mt-5">
                <div class="row">
                    <!-- Image Display -->
                    <div class="col-12 col-md-6 d-flex justify-content-center stepImage-container">
                        <img id="stepImage" src="./assets/images/success.gif" alt="Step Image" class="img-fluid w-100 h-100">
                    </div>

                        <!-- Step Content -->
                        <div class="col-12 col-md-6 d-flex flex-column justify-content-center text-center text-md-start">
                            <h2 id="stepTitle" class="text-primary fw-bold"></h2>
                            <h4 id="stepSubheading" class="fw-bold"></h4>
                            <p id="stepDescription"></p>
                        </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        let currentStep = 0;
        const steps = [
                    { 
                        title: 'Step 1: Submit Details', 
                        subheading: 'Provide Accurate Project Information', 
                        description: 'Fill out the submission form with all the necessary project details. Include the deadline, subject name, subject code, and any specific instructions to ensure precision. Upload relevant files to support your requirements. Make sure your contact information is accurate for seamless communication.', 
                        image: './assets/images/submitapp.gif' 
                    },
                    { 
                        title: 'Step 2: Verification', 
                        subheading: 'Ensure Information Accuracy', 
                        description: 'Our team carefully reviews the submitted details to ensure completeness and correctness. We cross-check your instructions with the uploaded files to avoid discrepancies. If needed, we may contact you for additional clarification. Accuracy at this stage ensures smooth processing.', 
                        image: './assets/images/verify.gif' 
                    },
                    { 
                        title: 'Step 3: Processing', 
                        subheading: 'Expert Handling and Quality Assurance', 
                        description: 'Once verification is complete, our skilled experts begin working on your assignment. We follow the guidelines you provided and maintain high standards of quality. The content undergoes thorough proofreading to eliminate errors. We guarantee top-notch quality with a professional touch.', 
                        image: './assets/images/process.gif' 
                    },
                    { 
                        title: 'Step 4: Assignment received', 
                        subheading: 'Access Your Completed Assignment', 
                        description: 'After finalizing the assignment, we prepare it for download. You will receive a secure link to access your completed work directly from your dashboard. Double-check the output to ensure it meets your expectations. Your satisfaction is our ultimate goal.', 
                        image: './assets/images/download.gif' 
                    },
                    { 
                        title: 'Step 5: Success', 
                        subheading: 'Experience Excellence in Academic Assistance', 
                        description: 'Celebrate your achievement with a high-quality assignment delivered on time. Our commitment to accuracy and professionalism ensures outstanding results. Feel free to share your feedback and let us know how we can further assist you. Your success motivates us to keep improving.', 
                        image: './assets/images/success.gif' 
                    }
                        ];
        function updateStep(step) {
            document.querySelectorAll('.step-circle').forEach((circle, index) => {
                circle.classList.toggle('active', index === step);
            });
            document.getElementById('progressFill').style.width = ((step + 1) / steps.length) * 100 + '%';
            document.getElementById('stepImage').src = steps[step].image;
            document.getElementById('stepTitle').textContent = steps[step].title;
            document.getElementById("stepSubheading").textContent = steps[step].subheading;
            document.getElementById("stepDescription").textContent = steps[step].description;
        }

        function autoChangeStep() {
            currentStep = (currentStep + 1) % steps.length;
            updateStep(currentStep);
            setTimeout(autoChangeStep, 4000);
        }

        autoChangeStep();
    </script>
 <div class="faq-container">
    <div class="faq-section">
        <h2 class="faq-heading">Frequently Asked Questions</h2>
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>What is the process of assignment submission?</span>
                <i class="fas fa-chevron-down right-icon"></i>
            </div>
            <div class="faq-answer">
                Our process is simple and efficient. Submit your assignment details, and our experts will take care of the rest!
            </div>
        </div>
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>What is the submission process?</span>
                <i class="fas fa-chevron-down right-icon"></i>
            </div>
            <div class="faq-answer">
                 The submission process is simple and quick. Fill out the form with your project details, upload the required files, and submit your request.            </div>
        </div>
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>How long does it take to complete an assignment?</span>
                <i class="fas fa-chevron-down right-icon"></i>
            </div>
            <div class="faq-answer">
                    The completion time varies depending on the complexity and deadline of your project. Our team ensures timely delivery without compromising quality.
            </div>
        </div>
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>Can I track my assignment progress?</span>
                <i class="fas fa-chevron-down right-icon"></i>
            </div>
            <div class="faq-answer">
                Yes, you can track your assignment progress through your dashboard. We provide regular updates to keep you informed.
            </div>
        </div>
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>How can I contact customer support?</span>
                <i class="fas fa-chevron-down right-icon"></i>
            </div>
            <div class="faq-answer">
                You can reach our customer support through the contact page or via email. We are available 24/7 to assist you with any queries.
            </div>
        </div>
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>What payment methods are accepted?</span>
                <i class="fas fa-chevron-down right-icon"></i>
            </div>
            <div class="faq-answer">
                We accept various payment methods, including credit/debit cards, PayPal, and bank transfers. Your payment information is kept secure and confidential.
            </div>
        </div>
    </div>
    <div class="faq-image">
        <img src="./assets/images/transparent.png" alt="FAQ Image">
    </div>
</div>

<script>
    function toggleFaq(faqItem) {
        const answer = faqItem.querySelector('.faq-answer');
        faqItem.classList.toggle('active');
        
        if (faqItem.classList.contains('active')) {
            answer.style.display = 'block';
        } else {
            answer.style.display = 'none';
        }
    }
</script>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
