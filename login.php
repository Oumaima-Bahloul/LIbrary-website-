<?php
session_start();
include 'api/db.php';

$error = "";

// LOGIN LOGIC (Your existing code)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['first_name'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: admin/index.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - La Fleur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Modern Glassmorphism Style */
        body {
            background: #f4f7f6;
            font-family: "Roboto", sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-page {
            width: 400px;
        }

        .form-container {
            background: #ffffff;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

        .form-toggle {
            display: flex;
            margin-bottom: 25px;
            border-bottom: 2px solid #eee;
        }

        .form-toggle button {
            flex: 1;
            padding: 10px;
            background: none;
            border: none;
            cursor: pointer;
            color: #b3b3b3;
            text-transform: uppercase;
            font-weight: bold;
            transition: 0.3s;
        }

        .form-toggle button.active {
            color: #d63384;
            /* La Fleur Pink */
            border-bottom: 2px solid #d63384;
        }

        .form-container input {
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
            border-radius: 8px;
        }

        .btn-submit {
            text-transform: uppercase;
            outline: 0;
            background: #333;
            /* Dark button to match your nav */
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            cursor: pointer;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background: #d63384;
        }

        .message {
            margin: 15px 0 0;
            color: #b3b3b3;
            font-size: 12px;
        }

        .message a {
            color: #d63384;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="login-page">
        <div class="form-container shadow">
            <!-- Success/Error Alerts -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success py-2 small text-center">Account created! Please sign in.</div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger py-2 small text-center"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            <div class="form-toggle">
                <button id="login-toggle" class="active" onclick="toggleForm('login')">login</button>
                <button id="signup-toggle" onclick="toggleForm('signup')">signup</button>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Login Form -->
            <form id="login-form" method="POST">
                <input type="email" name="email" placeholder="Email Address" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" name="login" class="btn-submit">login</button>
                <p class="message">Not registered? <a href="javascript:void(0)" onclick="toggleForm('signup')">Create an account</a></p>
            </form>

            <!-- Signup Form (Static placeholder) -->
            <form id="signup-form" action="register-process.php" method="POST" style="display:none;">
                <div class="d-flex gap-2">
                    <input type="text" name="first_name" placeholder="First Name" required />
                    <input type="text" name="last_name" placeholder="Last Name" required />
                </div>
                <input type="email" name="email" placeholder="Email Address" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" name="register" class="btn-submit">create</button>
                <p class="message">Already registered? <a href="javascript:void(0)" onclick="toggleForm('login')">Sign In</a></p>
            </form>
        </div>
    </div>

    <script>
        function toggleForm(type) {
            const loginForm = document.getElementById('login-form');
            const signupForm = document.getElementById('signup-form');
            const loginToggle = document.getElementById('login-toggle');
            const signupToggle = document.getElementById('signup-toggle');

            if (type === 'signup') {
                loginForm.style.display = 'none';
                signupForm.style.display = 'block';
                signupToggle.classList.add('active');
                loginToggle.classList.remove('active');
            } else {
                loginForm.style.display = 'block';
                signupForm.style.display = 'none';
                loginToggle.classList.add('active');
                signupToggle.classList.remove('active');
            }
        }
    </script>

</body>

</html>