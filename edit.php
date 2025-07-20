
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$no = ''; // Initialize $no to avoid undefined variable warning
if (isset($_GET['id'])) {
    $no = $_GET['id'];
} else {
    // Redirect or show an error if no ID is provided
    echo "Error: No student ID provided for update.";
    exit;
}

// Initialize variables for form fields
$fname = $lname = $mail = $pass = $college = $sex = $hobb = $img = "";
$errorMessage = "";
$successMessage = "";

// --- Handle Form Submission (Update) ---
if (isset($_POST['update'])) {
    // Sanitize and validate input
    $fname = htmlspecialchars($_POST['FirstName']);
    $lname = htmlspecialchars($_POST['LastName']);
    $mail = htmlspecialchars($_POST['Email']);
    $pass = $_POST['password']; // Consider hashing passwords for security!
    $college = htmlspecialchars($_POST['course']);
    $sex = htmlspecialchars($_POST['gender']);
    $hobbies = isset($_POST['hobbies']) ? $_POST['hobbies'] : [];
    $stringHobbies = implode(",", array_map('htmlspecialchars', $hobbies)); // Sanitize hobbies

    $currentImage = '';
    // Fetch current image from DB for fallback if no new image is uploaded
    $stmt = $conn->prepare("SELECT `your_image` FROM `student` WHERE ID = ?");
    $stmt->bind_param("i", $no);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentImage = $row['your_image'];
    }
    $stmt->close();


    $uploadFilename = $currentImage; // Default to current image

    // Handle image upload
    if (isset($_FILES['uploadfile']) && $_FILES['uploadfile']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['uploadfile']['name'];
        $file_tmp = $_FILES['uploadfile']['tmp_name'];
        $file_size = $_FILES['uploadfile']['size'];
        $file_type = $_FILES['uploadfile']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $extensions = ["jpeg", "jpg", "png", "gif"];

        if (in_array($file_ext, $extensions) === false) {
            $errorMessage = "Extension not allowed, please choose a JPEG, JPG, PNG or GIF file.";
        } elseif ($file_size > 2097152) { // 2MB
            $errorMessage = "File size must be less than 2 MB.";
        } else {
            $uploadDir = 'images/';
            // Generate a unique filename to prevent overwrites
            $newFileName = uniqid('img_', true) . '.' . $file_ext;
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($file_tmp, $uploadPath)) {
                $uploadFilename = $newFileName;
            } else {
                $errorMessage = "Failed to upload image.";
            }
        }
    }


    if (empty($errorMessage)) {
        // SQL query to update student data using prepared statement
        $sql = "UPDATE `student` SET
                    `First_Name`=?,
                    `Last_Name`=?,
                    `Email`=?,
                    `Password`=?,
                    `College_Course`=?,
                    `Gender`=?,
                    `Hobbies`=?,
                    `your_image`=?
                WHERE `ID`=?";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssssssssi", $fname, $lname, $mail, $pass, $college, $sex, $stringHobbies, $uploadFilename, $no);
            if ($stmt->execute()) {
                $successMessage = "Record updated successfully!";
                // Refresh data after successful update to show current values
                header("Location: edit.php?id=" . $no . "&status=success"); // Redirect to self with success message
                exit();
            } else {
                $errorMessage = "Error updating record: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errorMessage = "Error preparing statement: " . $conn->error;
        }
    }
}

// --- Fetch Student Data (Display) ---
// This part runs whether the form is submitted or not, to populate the form
$sql_fetch = "SELECT * FROM `student` WHERE ID = ?";
$stmt_fetch = $conn->prepare($sql_fetch);
if ($stmt_fetch) {
    $stmt_fetch->bind_param("i", $no);
    $stmt_fetch->execute();
    $res = $stmt_fetch->get_result();

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $fname = $row['First_Name'];
        $lname = $row['Last_Name'];
        $mail = $row['Email'];
        $pass = $row['Password'];
        $college = $row['College_Course'];
        $sex = $row['Gender'];
        $hobb = $row['Hobbies'];
        $img = $row['your_image'];
    } else {
        $errorMessage = "No records found for ID: " . $no;
    }
    $stmt_fetch->close();
} else {
    $errorMessage = "Error preparing fetch statement: " . $conn->error;
}

$conn->close();

// Check for success message from redirection
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $successMessage = "Record updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="styles.css"> </head> -->
    <style>
        /* styles.css */

body {
    background-color: #f8f9fa; /* Light grey background */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container {
    max-width: 800px; /* Limit container width for better readability */
    margin-top: 50px;
    margin-bottom: 50px;
    background-color: #ffffff; /* White background for the form */
    padding: 30px;
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Soft shadow */
}

h2 {
    color: #343a40; /* Darker heading color */
    font-weight: 600;
    margin-bottom: 30px;
}

.form-floating label {
    color: #6c757d; /* Lighter label color */
}

.form-control:focus,
.form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
}

.form-check-label {
    color: #495057;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    padding: 10px 30px;
    font-size: 1.1rem;
    border-radius: 25px; /* Pill-shaped button */
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
    transform: translateY(-2px); /* Slight lift on hover */
    box-shadow: 0 6px 15px rgba(0, 123, 255, 0.3);
}

.img-thumbnail {
    border: 3px solid #007bff; /* Highlight current image */
    padding: 5px;
    background-color: #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

/* Optional: Add a subtle fade-in animation for the form */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

#registrationForm {
    animation: fadeIn 0.5s ease-out forwards;
}

.dashboard-link {
    display: block;
    margin-top: 20px;
    font-size: 1rem;
    color: #007bff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.dashboard-link:hover {
    color: #0056b3;
    text-decoration: underline;
}
    </style>
    
<body>
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h2>Update Student Details</h2>
            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>
        </div>
        <form action="edit.php?id=<?php echo htmlspecialchars($no); ?>" method="POST" enctype="multipart/form-data" id="registrationForm">

        <div class="mb-3 text-center">
                <?php if (!empty($img)): ?>
                    <label class="form-label d-block mb-2">Current Image:</label>
                    <img src="images/<?php echo htmlspecialchars($img); ?>" alt="Current Image" class="rounded-circle img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                <?php else: ?>
                    <p class="text-muted">No image uploaded yet.</p>
                <?php endif; ?>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="FirstName" name="FirstName" required value="<?php echo htmlspecialchars($fname); ?>">
                        <label for="FirstName">First Name</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="LastName" name="LastName" required value="<?php echo htmlspecialchars($lname); ?>">
                        <label for="LastName">Last Name</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="Email" name="Email" required value="<?php echo htmlspecialchars($mail); ?>">
                        <label for="Email">Email Address</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="Password" name="password" placeholder="Leave blank to keep current password">
                        <label for="Password">New Password (optional)</label>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-floating">
                    <select class="form-select" id="course" name="course" required>
                        <option value="" disabled <?php if(empty($college)) echo 'selected'; ?>>Select your course</option>
                        <option value="computer_science" <?php if($college == 'computer_science') echo 'selected'; ?>>Computer Science</option>
                        <option value="business" <?php if($college == 'business') echo 'selected'; ?>>Business Administration</option>
                        <option value="engineering" <?php if($college == 'engineering') echo 'selected'; ?>>Engineering</option>
                        <option value="medicine" <?php if($college == 'medicine') echo 'selected'; ?>>Medicine</option>
                        <option value="arts" <?php if($college == 'arts') echo 'selected'; ?>>Arts and Humanities</option>
                    </select>
                    <label for="course">College Course</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Gender:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="male" name="gender" value="male" <?php if($sex == 'male') echo 'checked'; ?>>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="female" name="gender" value="female" <?php if($sex == 'female') echo 'checked'; ?>>
                    <label class="form-check-label" for="female">Female</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="other" name="gender" value="other" <?php if($sex == 'other') echo 'checked'; ?>>
                    <label class="form-check-label" for="other">Other</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Hobbies:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="reading" name="hobbies[]" value="reading" <?php if(strpos($hobb, 'reading') !== false) echo 'checked'; ?>>
                    <label class="form-check-label" for="reading">Reading</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="traveling" name="hobbies[]" value="traveling" <?php if(strpos($hobb, 'traveling') !== false) echo 'checked'; ?>>
                    <label class="form-check-label" for="traveling">Traveling</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="cooking" name="hobbies[]" value="cooking" <?php if(strpos($hobb, 'cooking') !== false) echo 'checked'; ?>>
                    <label class="form-check-label" for="cooking">Cooking</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="sports" name="hobbies[]" value="sports" <?php if(strpos($hobb, 'sports') !== false) echo 'checked'; ?>>
                    <label class="form-check-label" for="sports">Sports</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="music" name="hobbies[]" value="music" <?php if(strpos($hobb, 'music') !== false) echo 'checked'; ?>>
                    <label class="form-check-label" for="music">Music</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="uploadfile" class="form-label">Upload new image (optional):</label>
                <input class="form-control" type="file" id="uploadfile" name="uploadfile">
            </div>
            
            <div class="text-center">
                <button type="submit" name="update" class="btn btn-primary">Save Changes</button>
              
                <p>
                    <a href="dashboard.php" class="dashboard-link">Back to Dashboard</a>
                </p>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
edit.php
Displaying edit.php.