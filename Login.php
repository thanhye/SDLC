<?php
session_start(); // Ensure session_start() is called only once at the start.
ob_start(); // Start output buffering

$conn = new mysqli("localhost", "root", "", "se07102_sdlc");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Ordering System</title>
    <style>
        /* General */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee);
            color: white;
            text-align: center;
            padding: 50px;
            margin: 0;
        }

        /* Main Container */
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            margin: auto;
            color: black;
            text-align: left;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        /* Input */
        input[type="text"], input[type="password"], input[type="email"] {
            width: calc(100% - 20px);
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: 0.3s;
            display: block;
        }

        input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus {
            border-color: #a6c1ee;
            outline: none;
            box-shadow: 0 0 10px rgba(166, 193, 238, 0.5);
        }

        /* Button */
        input[type="submit"] {
            background: linear-gradient(135deg, #a6c1ee, #fbc2eb);
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            border-radius: 8px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }

        input[type="submit"]:hover {
            background: linear-gradient(135deg, #89a3d6, #e5a5d4);
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.2);
        }

        /* Navigation */
        nav {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        nav a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 18px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            transition: 0.3s;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Toggle Password */
        .password-container {
            position: relative;
        }

        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #666;
            transition: 0.3s;
        }

        .toggle-password:hover {
            color: #333;
        }

        /* Error */
        .error {
            color: red;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <nav>
        <a href="?page=register">Register</a>
        <a href="?page=login">Login</a>
    </nav>

    <div class="container">

    <?php
    // Check which page to display (Register or Login)
    if (isset($_GET['page']) && $_GET['page'] == 'register') {
        ?>

        <h2>Register</h2>
        <form action="?page=register" method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <div class="password-container">
                <input type="password" name="password" required>
                <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
            </div>

            <input type="submit" name="register" value="Register">
        </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                $email = trim($_POST['email']);

                // Check if username already exists
                $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();
                
                if ($stmt->num_rows > 0) {
                    echo "<script>alert('Username already exists!');</script>";
                } else {
                    // Hash the password using bcrypt
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    
                    // Set role based on username
                    $role = ($username == 'admin') ? 'admin' : 'user';

                    // Insert data into database
                    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $username, $hashedPassword, $email, $role);

                    if ($stmt->execute()) {
                        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                }
                $stmt->close();
            }
        ?>


        <?php
    } elseif (isset($_GET['page']) && $_GET['page'] == 'login') {
        ?>

        <h2>Login</h2>
        <form id="loginForm" action="?page=login" method="POST" onsubmit="return validateLogin()">
            <label>Username:</label>
            <input type="text" id="login_username" name="login_username" required>
            <span class="error" id="loginUsernameError"></span>

            <label>Password:</label>
            <div class="password-container">
                <input type="password" id="login_password" name="login_password" required>
                <span class="toggle-password" onclick="togglePassword('login_password')">👁️</span>
            </div>
            <span class="error" id="loginPasswordError"></span>

            <input type="submit" name="login" value="Login">
        </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
                $username = trim($_POST['login_username']);
                $password = $_POST['login_password'];

                $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($id, $hashedPassword, $role);
                $stmt->fetch();

                if ($stmt->num_rows > 0 && password_verify($password, $hashedPassword)) {
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;

                    if ($role == 'admin') {
                        header("Location: Web.php"); // Redirect to admin page
                        exit(); // Ensure no further output is processed
                    } else {
                        header("Location: Web.php");  // Redirect to user page
                        exit(); // Ensure no further output is processed
                    }
                } else {
                    echo "<script>alert('Invalid username or password!');</script>";
                }
                $stmt->close();
            }
            ?>


        <?php
    }
    $conn->close();
    ob_end_flush(); // End output buffering
    ?>

    </div>

<script>
function validateLogin() {
    return confirm("Are you sure you want to log in?");
}

function togglePassword(id) {
    let input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
