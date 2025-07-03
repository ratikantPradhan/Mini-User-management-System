<?php
require "db.php";

$error = array();

if (isset($_POST['submit'])) {
    // Input validation
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if username is valid
    if (empty($username) || strlen($username) < 3 || strlen($username) > 16) {
        $error[] = "Username must be between 3 and 16 characters.";
    }

    // Check if email is valid
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Invalid email address.";
    }

    // Check if password is valid
    if (empty($password) || strlen($password)  < 8 ) {
        $error[] = "Password must be at least 8 characters.";
    }

    // File upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $profile = $target_file;

    // Check if file was uploaded
    if (empty($_FILES["fileToUpload"]["name"])) {
        $error[] = "Please select a file to upload.";
    }

    // Check if file is an image
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $error[] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $error[] = "Sorry, file already exists.";
    }

    // Check if file size is too large
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $error[] = "Sorry, your file is too large.";
    }

    // If no errors, insert data into database
    if (empty($error)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, profile,role,created_at) VALUES ('$username', '$email', '$password', '$profile','user', NOW())";
        if ($conn->query($sql) === TRUE) {
            // Upload file
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $success = "New record created successfully";
            } else {
                $error[] = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error[] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form action="register.php" method="post" enctype="multipart/form-data">
        <div class="bg-white p-8 rounded shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6">Register</h2>
            <?php if (!empty($error)) { ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>
                        <?php foreach ($error as $err) { ?>
                            <li><?php echo $err; ?></li>
                            
                        <?php //reload page
                        header("Location: register.php");
                    } ?>
                    </ul>
                </div>
                
            <?php } ?>
            <?php if (isset($success)) { ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <?php echo $success; 
                    header("Location: login.php");?>
                </div>
            <?php } ?>
            <div class="mb-4">
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Profile Picture</label>
                <input type="file" name="fileToUpload" class="w-full border rounded px-3 py-2" required>
            </div>
            <button type="submit" name="submit" class="w-full bg-blue-500 text-white py-2 rounded">Register</button>
            <p class="mt-4 text-center text-sm">Already have an account? <a href="login.php" class="text-blue-500">Login</a></p>
        </div>
    </form>
</body>
<script>
    
</script>
</html>