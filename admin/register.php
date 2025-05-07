<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            padding: 30px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo h1 {
            color: #4361ee;
            margin: 0;
            font-size: 24px;
        }
        
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        input:focus {
            border-color: #4361ee;
            outline: none;
        }
        
        .btn {
            background-color: #4361ee;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #3a56d4;
        }
        
        .error {
            color: #e63946;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .success {
            color: #2ecc71;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: #4361ee;
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ecommerce_db";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $errorMsg = "";
    $successMsg = "";
    
    // Form submission processing
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $admin_code = trim($_POST['admin_code']);
        
        // Validate inputs
        if (empty($fullname) || empty($email) || empty($username) || empty($password) || empty($confirm_password) || empty($admin_code)) {
            $errorMsg = "All fields are required";
        } 
        elseif ($password !== $confirm_password) {
            $errorMsg = "Passwords do not match";
        } 
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg = "Invalid email format";
        } 
        elseif (strlen($password) < 8) {
            $errorMsg = "Password must be at least 8 characters long";
        } 
        elseif ($admin_code !== "ADMIN123") { // Example admin code
            $errorMsg = "Invalid admin access code";
        } 
        else {
            // Check if email or username already exists
            $check_query = "SELECT * FROM admins WHERE email = ? OR username = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("ss", $email, $username);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if ($user['email'] === $email) {
                    $errorMsg = "Email is already registered";
                } else {
                    $errorMsg = "Username is already taken";
                }
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new admin
                $insert_query = "INSERT INTO admins (fullname, email, username, password, created_at) VALUES (?, ?, ?, ?, NOW())";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bind_param("ssss", $fullname, $email, $username, $hashed_password);
                
                if ($insert_stmt->execute()) {
                    $successMsg = "Registration successful! You can now <a href='login.php'>login</a>";
                } else {
                    $errorMsg = "Error: " . $conn->error;
                }
                
                $insert_stmt->close();
            }
            
            $check_stmt->close();
        }
    }
    
    // Close connection
    $conn->close();
    ?>

    <div class="container">
        <div class="logo">
            <h1>E-Commerce Admin</h1>
        </div>
        
        <h2>Register New Administrator</h2>
        
        <?php if (!empty($errorMsg)): ?>
            <div class="error"><?php echo $errorMsg; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($successMsg)): ?>
            <div class="success"><?php echo $successMsg; ?></div>
        <?php else: ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <div class="form-group">
                    <label for="admin_code">Admin Access Code</label>
                    <input type="text" id="admin_code" name="admin_code" required>
                </div>
                
                <button type="submit" class="btn">Register</button>
            </form>
            
            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>