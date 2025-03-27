<?php
session_start();
include './config/db.php';
include_once(__DIR__ . '/chats.php');
include './components/loading.php';

showLoading();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">You must be logged in to submit an assignment.</div>';
    exit;
}

$student_id = $_SESSION['user_id'];
$document_path = null; // Ensure it's initialized

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize and validate input
        $full_name = filter_var(trim($_POST['full_name']), FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);
        $university = filter_var(trim($_POST['university']), FILTER_SANITIZE_STRING);
        $country = filter_var(trim($_POST['country']), FILTER_SANITIZE_STRING);
        $subject_code = filter_var(trim($_POST['subject_code']), FILTER_SANITIZE_STRING);
        $subject_name = filter_var(trim($_POST['subject_name']), FILTER_SANITIZE_STRING);
        $deadline = $_POST['deadline'];
        $num_pages = (int) $_POST['num_pages'];
        $assignment_details = filter_var(trim($_POST['assignment_details']), FILTER_SANITIZE_STRING);

        // File upload handling
        if (!empty($_FILES['document']['name'])) {
            $upload_dir = __DIR__ . "/uploads/";

            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $file_name = $_FILES['document']['name'];
            $file_tmp = $_FILES['document']['tmp_name'];
            $file_size = $_FILES['document']['size'];
            $file_error = $_FILES['document']['error'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar'];
            $maxSize = 10 * 1024 * 1024; // 10MB

            if (!in_array($file_ext, $allowedExtensions)) {
                throw new Exception("Invalid file type! Allowed: " . implode(", ", $allowedExtensions));
            }

            if ($file_size > $maxSize) {
                throw new Exception("File is too large! Maximum allowed size is 10MB.");
            }

            if ($file_error !== 0) {
                throw new Exception("There was an error uploading the file.");
            }

            $uniqueFileName = uniqid('file_', true) . '.' . $file_ext;
            $destination = $upload_dir . $uniqueFileName;

            if (move_uploaded_file($file_tmp, $destination)) {
                $document_path = "uploads/" . $uniqueFileName;
            } else {
                throw new Exception("Failed to move uploaded file.");
            }
        }

        // Validate student_id exists in 'users' table
        $checkUser = $conn->prepare("SELECT id FROM users WHERE id = :student_id");
        $checkUser->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $checkUser->execute();

        if ($checkUser->rowCount() === 0) {
            throw new Exception("Invalid student ID. Please log in again.");
        }

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO assignments 
            (student_id, full_name, email, phone, university, country, subject_code, subject_name, deadline, num_pages, assignment_details, document_path)
            VALUES (:student_id, :full_name, :email, :phone, :university, :country, :subject_code, :subject_name, :deadline, :num_pages, :assignment_details, :document_path)");

        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':university', $university);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':subject_code', $subject_code);
        $stmt->bindParam(':subject_name', $subject_name);
        $stmt->bindParam(':deadline', $deadline);
        $stmt->bindParam(':num_pages', $num_pages, PDO::PARAM_INT);
        $stmt->bindParam(':assignment_details', $assignment_details);
        $stmt->bindParam(':document_path', $document_path);

        if ($stmt->execute()) {
            // Email notification
            $subject = "Hudsmer Student Services - Assignment Submission Successful";
            $message = "
                <html>
                <head><title>Assignment Submission Successful</title></head>
                <body>
                <h3>Assignment Details Submitted Successfully!</h3>
                <p>Hi, we have received your assignment details.</p>
                <p>Our team will review your submission and get back to you soon.</p>
                <br><br>
                <p>If you have any questions, feel free to contact our support team.</p>
                </body>
                </html>";

            $headers = "From: Hudsmer Student Services <support@hbsthesis.co.uk>\r\n";
            $headers .= "Reply-To: support@hbsthesis.co.uk\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            if (mail($email, $subject, $message, $headers)) {
                echo "<script>
                alert('Assignment details submitted successfully! We will respond soon.');
                window.location.href = 'index.php';
            </script>";
            exit();
            } else {
                throw new Exception("Failed to send email notification.");
            }
        } else {
            throw new Exception("Failed to submit assignment.");
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
    }
}
?>

 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Assignment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
 
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding:0;
        }
        .heading-content{
            background:
                linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&q=80') no-repeat center center;
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
        .heading-content h1 {
            font-size: 3rem;
            font-weight: bold;
            animation: slideIn 1.5s ease-in-out;
        }
        .heading-content p {
            font-size: 1.5rem;
            margin-top: 10px;
            animation: slideIn 2s ease-in-out;
            animation: slideIn 1.5s ease-in-out;
        }
        .form-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 8px;
            background-color: #fff;
            margin-top: -90px;
            z-index: 999;
            position: relative;
        }
        .form-card h5 {
            font-size: 20px;
            font-weight: bold;
            margin-top: 40px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
 
        .form-card h4 {
            text-align: center;
            margin-bottom: 50px;
            font-size: 35px;
            font-weight: bold;
            text-transform: uppercase;
            color: #4f46e5;
        }
 
        .error-message {
            color: red;
        }
        .contact-grid-feild{
            background-color:rgb(189, 180, 180);
            margin-top: -50px;
            padding-top: 80px;
            padding-bottom: 40px;
        }
        .bg-white-50{
            background-color: #fff;
        }
        @media (max-width: 768px) {
            .heading-content{
                height: 60vh;
            }
        .heading-content h1 {
            font-size: 2rem;
        }
        .heading-content p {
            font-size: 18px;
        }
        }
        .progress-container {
            display: none;
            margin-top: 10px;
        }
        .upload-success {
            display: none;
            margin-top: 10px;
            text-align: center;
        }
    </style>
        <script>
        $(document).ready(function() {
            $("#phone").on("input", function() {
                if (!/^[0-9]*$/.test(this.value)) {
                    this.setCustomValidity("Phone number must contain only numbers.");
                } else {
                    this.setCustomValidity("");
                }
            });
 
            $("#deadline").on("input", function() {
                var today = new Date();
                var selectedDate = new Date(this.value);
                if (selectedDate <= today) {
                    this.setCustomValidity("Deadline must be a future date.");
                } else {
                    this.setCustomValidity("");
                }
            });
        });
    </script>
</head>
<body>
<?php include 'templates/header.php'; ?>
    <div class="container-fluid p-0 m-0">
    <section class="heading-content m-0 p-0">
        <h1>Assignment Helping Project</h1>
        <p>Your one-stop solution for assignment submissions and support.</p>
    </section>
        <div class="form-card container">
            <h4>Submit Assignment</h4>
 
            <?php if (isset($error_message)): ?>
                <p class="error-message text-danger"><?php echo $error_message; ?></p>
            <?php endif; ?>
 
            <form method="POST" enctype="multipart/form-data">
                <!-- Personal Information Section -->
                <h5>Personal Information</h5>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="full_name">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="full_name" id="full_name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="phone">Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="phone" id="phone" required pattern="\d+" title="Phone number should contain only numbers">
                    </div>
                </div>
 
                <!-- Assignment Details Section -->
                <h5>Assignment Details</h5>
                <div class="form-row">
    <div class="form-group col-md-6">
        <label for="university">University <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="university" id="university" required>
    </div>
    <div class="form-group col-md-6">
        <label for="country">Country <span class="text-danger">*</span></label>
        <select class="form-control" name="country" id="country" required>
        <option value="">Select Country</option>
    </select>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("https://restcountries.com/v3.1/all")
        .then(response => response.json())
        .then(data => {
            let countryDropdown = document.getElementById("country");
            data.sort((a, b) => a.name.common.localeCompare(b.name.common)); // Sort alphabetically

            data.forEach(country => {
                let option = document.createElement("option");
                option.value = country.name.common;
                option.textContent = country.name.common;
                countryDropdown.appendChild(option);
            });
        })
        .catch(error => console.error("Error fetching country data:", error));
});
</script>

    </div>
</div>
 
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="subject_code">Subject Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="subject_code" id="subject_code" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="subject_name">Subject Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="subject_name" id="subject_name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="deadline">Deadline <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="deadline" id="deadline" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="num_pages">Number of Pages <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="num_pages" id="num_pages" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="assignment_details">Assignment Details
                            <span class="text-muted small">(Please provide details for referencing style, if any)</span>
                            <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" name="assignment_details" id="assignment_details" required></textarea>
                    </div>
                </div>
                <div class="form-group">
        <label for="document">Upload Document (Optional)</label>
        <div class="border border-dashed rounded p-4 text-center bg-light" onclick="document.getElementById('document').click()" style="cursor: pointer;">
            <input type="file" id="document" name="document" class="d-none" accept=".pdf,.doc,.docx,.txt,.zip,.rar" />
            <div class="d-flex flex-column align-items-center justify-content-center" id="upload-section">
                <i class="fas fa-upload upload-icon text-muted mb-2"></i>
                <p class="small text-muted mb-1">Drag and drop your file here, or <a class="btn btn-link text-primary p-0">browse</a></p>
                <p class="small text-muted">Supported file types: PDF, DOC, DOCX, TXT, ZIP, RAR (Max 10MB)</p>
            </div>
        </div>

        <div class="progress-container">
            <div class="progress mt-2">
                <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;"></div>
            </div>
            <p id="progress-text" class="text-center small mt-1">Uploading: 0%</p>
        </div>

        <div class="upload-success">
            <i class="fas fa-check-circle text-success fa-2x"></i>
            <p class="small text-success">File Submitted Successfully!</p>
            <p id="uploaded-file-name" class="small font-weight-bold text-primary"></p> <!-- File name will be displayed here -->
        </div>
    </div>
    <script>
    $(document).ready(function () {
        $('#document').change(function () {
            var file = this.files[0];
            if (file) {
                $('.progress-container').show();
                $('#upload-section').hide();
                $('.upload-success').hide();
                $('#progress-bar').css('width', '0%');
                $('#progress-text').text('Uploading: 0%');

                var progress = 0;
                var interval = setInterval(function () {
                    progress += 10;
                    $('#progress-bar').css('width', progress + '%');
                    $('#progress-text').text('Uploading: ' + progress + '%');

                    if (progress >= 100) {
                        clearInterval(interval);
                        $('.progress-container').hide();
                        $('.upload-success').show();
                        $('#uploaded-file-name').text('File Name: ' + file.name); // Show file name below success message
                    }
                }, 200);
            }
        });
    });
</script>
 
                <button type="submit" name="submit" class="btn btn-block py-2" style="background:#4f46e5; color:#fff">Submit Assignment</button>
            </form>
        </div>
        <div class="mx-auto px-4 contact-grid-feild">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold mb-4">Why Choose Our Assignment Service?</h2>
        <p class="text-gray-600 max-w-3xl mx-auto">
            We provide comprehensive assignment assistance to help you achieve academic excellence.
        </p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
        <div class="bg-white-50 p-6 rounded-lg text-center">
            <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clock text-indigo-600 text-3xl"></i>
            </div>
            <h3 class="text-xl text-center font-bold mb-2">On-Time Delivery</h3>
            <p class="text-gray-600">We guarantee timely delivery of your assignments, no matter how tight the deadline.</p>
        </div>
 
        <div class="bg-white-50 p-6 rounded-lg text-center">
            <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-indigo-600 text-3xl"></i>
            </div>
            <h3 class="text-xl text-center font-bold mb-2">Quality Assurance</h3>
            <p class="text-gray-600">All assignments are written from scratch and checked for plagiarism before delivery.</p>
        </div>
 
        <div class="bg-white-50 p-6 rounded-lg text-center">
            <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-upload text-indigo-600 text-3xl"></i>
            </div>
            <h3 class="text-xl text-center font-bold mb-2">Easy Submission</h3>
            <p class="text-gray-600">Our simple order form makes it easy to submit your requirements and get started.</p>
        </div>
    </div>
</div>
    </div>
    <?php include './templates/footer.php'; ?>
</body> 
</html>