<?php
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Error connecting to database");
}

// Thêm người dùng
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Mã hóa mật khẩu

    // Loại bỏ role khỏi câu lệnh INSERT
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if (mysqli_query($connect, $sql)) {
        echo "<script>alert('User added successfully'); window.location.href='User_management.php';</script>";
    } else {
        echo "<script>alert('Error adding user');</script>";
    }
}

// Cập nhật thông tin người dùng
if (isset($_POST['update_user'])) {
    $user_id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Loại bỏ role khỏi UPDATE
    $sql = "UPDATE users SET username='$username', email='$email' WHERE id='$user_id'";
    if (mysqli_query($connect, $sql)) {
        echo "<script>alert('User updated successfully'); window.location.href='User_management.php';</script>";
    } else {
        echo "<script>alert('Error updating user');</script>";
    }
}

// Xóa người dùng
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    
    $sql = "DELETE FROM users WHERE id='$user_id'";
    if (mysqli_query($connect, $sql)) {
        echo "<script>alert('User updated successfully'); window.location.href='User_management.php';</script>";
    } else {
        echo "<script>alert('Error updating user');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
/* Reset mặc định */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background-color: #f8f9fa;
    padding: 20px;
    animation: fadeIn 0.8s ease-in;
}

.container {
    max-width: 800px;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    animation: slideIn 0.5s ease-in-out;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #6a11cb;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
}

/* Form thêm người dùng */
form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 10px 0;
    animation: fadeInUp 0.6s ease-in;
}

input, button {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    transition: all 0.3s ease-in-out;
}

input:focus {
    border-color: #6a11cb;
    box-shadow: 0 0 5px rgba(106, 17, 203, 0.5);
    transform: scale(1.02);
}

button {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: white;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: linear-gradient(135deg, #2575fc, #6a11cb);
    transform: scale(1.05);
}

button:active {
    transform: scale(0.95);
}

/* Bảng danh sách người dùng */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    animation: fadeInUp 0.8s ease-in;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th {
    background: linear-gradient(135deg, #ff7eb3, #ff758c);
    color: white;
}

td a {
    text-decoration: none;
    color: #2575fc;
    font-weight: bold;
    margin: 0 5px;
    transition: 0.3s;
}

td a:hover {
    color: #6a11cb;
    transform: scale(1.1);
}
.navbar {
    width: 100%;
    background: linear-gradient(135deg, #ff758c, #ff7eb3);
    padding: 10px 0;
    text-align: center;
    margin-bottom: 20px;
}

.home-button {
    padding: 10px 15px;
    background: white;
    color: #ff758c;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 5px;
    transition: 0.3s;
    display: inline-block;
}

.home-button:hover {
    background: #ff758c;
    color: white;
    transform: scale(1.05);
}

/* Hiệu ứng động */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}

/* Responsive */
@media (max-width: 600px) {
    table {
        font-size: 14px;
    }
    input, button {
        font-size: 14px;
    }
}

    </style>
</head>
<body>

<h2>User Management</h2>

<!-- Form thêm người dùng -->
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="add_user">Add User</button>
</form>
<div class="navbar">
    <a href="Web.php" class="home-button">🏠 Quay lại Trang Chủ</a>
</div>


<!-- Danh sách người dùng -->
<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php
    $result = mysqli_query($connect, "SELECT user_id, username, email FROM users"); // Không lấy role
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$row['user_id']}</td>
            <td>{$row['username']}</td>
            <td>{$row['email']}</td>
            <td>
                <a href='edit_users.php?id={$row['user_id']}'>Edit</a> | 
                <a href='User_management.php?delete={$row['user_id']}' onclick=\"return confirm('Are you sure?')\">Delete</a>

            </td>
        </tr>";
    }
    
    ?>
</table>

</body>
</html>
