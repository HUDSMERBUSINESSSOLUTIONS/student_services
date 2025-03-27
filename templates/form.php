<?php
// Include database connection
include '../config/db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">You must be logged in to submit an assignment.</div>';
    exit;
}
$student_id = $_SESSION['user_id'];

if (isset($_POST['submit'])) {
    try {
        // Sanitize and collect form data
        $full_name = htmlspecialchars(trim($_POST['full_name']));
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars(trim($_POST['phone']));
        $university = htmlspecialchars(trim($_POST['university']));
        $country = htmlspecialchars(trim($_POST['country']));
        $subject_code = htmlspecialchars(trim($_POST['subject_code']));
        $subject_name = htmlspecialchars(trim($_POST['subject_name']));
        $deadline = $_POST['deadline'];
        $num_pages = (int)$_POST['num_pages'];
        $assignment_details = htmlspecialchars(trim($_POST['assignment_details']));
        function save_uploaded_file($file, $allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar'], $maxSize = 10 * 1024 * 1024) {
            $upload_dir = __DIR__ . "/uploads/";
        
            // Ensure the uploads folder exists
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
            // Get file info
            $file_name = $file['name'];
            $file_tmp = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
            // Validate file type
            if (!in_array($file_ext, $allowedExtensions)) {
                return "Invalid file type!";
            }
        
            // Check file size
            if ($file_size > $maxSize) {
                return "File is too large!";
            }
        
            // Check for errors in upload
            if ($file_error !== 0) {
                return "There was an error uploading the file.";
            }
        
            // Create a unique file name
            $uniqueFileName = uniqid('file_', true) . '.' . $file_ext;
            $destination = $upload_dir . $uniqueFileName;
        
            // Move file to destination properly
            if (move_uploaded_file($file_tmp, $destination)) {
                return "uploads/" . $uniqueFileName;
            } else {
                return "Failed to move file.";
            }
        }
        
        // Example use
        if (!empty($_FILES['document']['name'])) {
            $result = save_uploaded_file($_FILES['document']);
            if (strpos($result, "uploads/") !== false) {
                $document_path = $result; // Save path to DB
            } else {
                $errors[] = $result; // Capture error
            }
        }

        // Validate student_id exists in 'users' table
        $checkUser = $conn->prepare("SELECT id FROM users WHERE id = :student_id");
        $checkUser->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $checkUser->execute();

        if ($checkUser->rowCount() === 0) {
            throw new Exception('Invalid student ID. Please log in again.');
        }

        // Database Insertion
        $stmt = $conn->prepare("INSERT INTO assignments
            (student_id, full_name, email, phone, university, country, subject_code, subject_name, deadline, num_pages, assignment_details, document_path)
            VALUES (:student_id, :full_name, :email, :phone, :university, :country, :subject_code, :subject_name, :deadline, :num_pages, :assignment_details, :document_path)");

        // Bind parameters properly
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
        $stmt->bindParam(':document_path', $file_name);

        // Execute and check for errors
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Assignment submitted successfully!</div>';
        } else {
            throw new Exception('Failed to submit assignment: ' . implode(", ", $stmt->errorInfo()));
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
 
    <style>
        .heading-content{
            background:
                linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&q=80') no-repeat center center;
            background-size: cover;
            color: #fff;
            animation: fadeIn 2s ease-in-out;
            height: 60vh;
            text-align: center;
            display: flex;
            flex-direction: column;
            padding-top: 70px;
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
            background-color: #f6f6f6;
            margin-top: -50px;
            padding-top: 80px;
        }
        .bg-white-50{
            background-color: #fff;
        }
        @media (max-width: 768px) {
            .heading-content h1 {
            font-size: 2rem;
        }
        .heading-content p {
            font-size: 20px;
        }
        }
    </style>
        <script>
        // Client-side validation for future date and phone number
        $(document).ready(function() {
            // Validate phone number (only numbers)
            $("#phone").on("input", function() {
                if (!/^[0-9]*$/.test(this.value)) {
                    this.setCustomValidity("Phone number must contain only numbers.");
                } else {
                    this.setCustomValidity("");
                }
            });
 
            // Validate future date for the deadline
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
<?php include '../templates/header.php'; ?>
    <div class="container-fuild">
    <section class="heading-content">
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
        <input type="text" class="form-control" name="country" id="country" required>
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
            <span class="text-muted small">(Please provide details in APA format: Author, Year, Title, Source)</span>
            <span class="text-danger">*</span>
        </label>
        <textarea class="form-control" name="assignment_details" id="assignment_details" required></textarea>
    </div>
</div>
 
 
                <div class="form-group">
                    <label for="document">Upload Document (Optional)</label>
                    <div class="border border-dashed rounded p-4 text-center bg-light" onclick="document.getElementById('document').click()" style="cursor: pointer;">
                        <input type="file" id="document" name="document" class="d-none" accept=".pdf,.doc,.docx,.txt,.zip,.rar" />
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-upload upload-icon text-muted mb-2"></i>
                            <p class="small text-muted mb-1">Drag and drop your file here, or <a class="btn btn-link text-primary p-0">browse</a></p>
                            <p class="small text-muted">Supported file types: PDF, DOC, DOCX, TXT, ZIP, RAR (Max 10MB)</p>
                        </div>
                    </div>
                </div>
 
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
</body>
<?php include '../templates/footer.php'; ?>
 
</html>