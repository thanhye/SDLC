<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Order Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background:rgb(237, 86, 86);
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #28a745;
        }
        a {
            display: block;
            margin-top: 20px;
            padding: 10px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
                <h2>🎉 Order Successful!</h2>
                <p>Thank you for your purchase. Your order is being processed.</p>
                <a href="Web.php">Return to Home Page</a>

    </div>
</body>
</html>
