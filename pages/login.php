<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | E-Commerce Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fb;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            display: flex;
            width: 900px;
            height: 600px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .left-panel .header {
            display: flex;
            align-items: center;
        }

        .left-panel .logo {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .left-panel .tagline {
            margin-top: 40px;
            font-size: 22px;
            font-weight: 300;
            line-height: 1.5;
        }

        .left-panel .illustration {
            text-align: center;
            margin: 20px 0;
        }

        .left-panel .illustration i {
            font-size: 100px;
            opacity: 0.8;
        }

        .left-panel .footer {
            font-size: 14px;
            opacity: 0.8;
        }

        .right-panel {
            flex: 1.2;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-size: 30px;
            color: #333;
            margin-bottom: 10px;
        }

        .login-header p {
            font-size: 16px;
            color: #666;
        }

        .login-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 15px;
            color: #999;
        }

        .form-group input {
            width: 100%;
            padding: 12px 20px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #4b6cb7;
            outline: none;
            box-shadow: 0 0 0 2px rgba(75, 108, 183, 0.2);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .forgot-password a {
            color: #4b6cb7;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .login-button {
            background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .signup-link {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }

        .signup-link a {
            color: #4b6cb7;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 100%;
                height: auto;
                border-radius: 0;
            }

            .left-panel {
                display: none;
            }

            .right-panel {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="header">
                <div class="logo">Login To Your Account</div>
            </div>
            <div class="tagline">
                <h2>Welcome Back!</h2>
                <p>To e-commerce store.</p>
            </div>
            <div class="illustration">
                <i class="fas fa-store"></i>
            </div>
            <div class="footer">
                &copy; 2025 Shop. All rights reserved.
            </div>
        </div>
        
        <div class="right-panel">
            <div class="login-header">
                <h2>Account Login</h2>
                <p>Please sign in to continue to your store</p>
            </div>
            
            <form class="login-form" action="/admin/dashboard.html" method="post">
                <div class="form-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="Email Address" required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <div class="forgot-password">
                        <a href="reset-password.html">Forgot password?</a>
                    </div>
                </div>
                
                <button type="submit" class="login-button">
                    Sign In
                </button>
                
                <div class="signup-link">
                    Don't have an user account? <a href="contact-support.html">Create Now</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.login-form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                // In a real application, you would send this data to your server
                // For demo purposes, we'll just show a success message and redirect
                console.log('Login attempt:', { email });
                
                // Demo credentials: admin@example.com / admin123
                if (email === 'admin@gmail.com' && password === 'admin123') {
                    alert('Login successful! Redirecting to dashboard...');
                    window.location.href = '/e-commerce/admin/index.php';
                } else {
                    alert('Invalid credentials. Please try again.');
                }
            });
        });
    </script>
</body>
</html>