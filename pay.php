<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkout"])) {
    $customer_name = mysqli_real_escape_string($connect, $_POST["name"]);
    $customer_phone = mysqli_real_escape_string($connect, $_POST["phone"]);
    $customer_address = mysqli_real_escape_string($connect, $_POST["address"]);
    $total_price = 0;

    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        $order_query = "INSERT INTO orders (customer_name, customer_phone, customer_address, total_price, order_date) 
                        VALUES ('$customer_name', '$customer_phone', '$customer_address', '$total_price', NOW())";
        if (mysqli_query($connect, $order_query)) {
            $order_id = mysqli_insert_id($connect);

            foreach ($_SESSION['cart'] as $id => $item) {
                $product_id = $id;
                $quantity = $item['quantity'];
                $price = $item['price'];

                $detail_query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                                 VALUES ('$order_id', '$product_id', '$quantity', '$price')";
                mysqli_query($connect, $detail_query);
            }

            unset($_SESSION['cart']);
            header("Location: success.php");
            exit();
        } else {
            echo "<p style='color: red; text-align: center;'>Error placing order. Please try again!</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ff9a9e, #fad0c4);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 50%;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            text-align: center;
            color: #ff4081;
            font-size: 28px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #444;
        }

        input[type="text"], input[type="tel"], textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ff4081;
            border-radius: 8px;
            font-size: 16px;
            transition: 0.3s;
        }

        input:focus, textarea:focus {
            border-color: #ff80ab;
            outline: none;
            box-shadow: 0px 0px 8px rgba(255, 64, 129, 0.3);
        }

        h3 {
            text-align: center;
            font-size: 22px;
            color: #ff5722;
            background: #ffe0b2;
            padding: 10px;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 25px;
            background: linear-gradient(to right, #ff4081, #ff80ab);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            box-shadow: 2px 3px 10px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
        }

        button:hover {
            background: linear-gradient(to right, #ff1744, #ff4081);
            transform: scale(1.05);
        }

        button:active {
            transform: scale(0.98);
        }

        .back-home {
            width: 100%;
            margin-top: 10px;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 25px;
            background: linear-gradient(to right, #2196F3, #64B5F6);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            box-shadow: 2px 3px 10px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
        }

        .back-home:hover {
            background: linear-gradient(to right, #1976D2, #2196F3);
            transform: scale(1.05);
        }

        .empty-cart {
            text-align: center;
            font-size: 20px;
            color: #777;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .empty-cart img {
            width: 120px;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .empty-cart p {
            font-weight: bold;
            font-size: 18px;
            color: #ff4081;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🛍 Checkout</h2>
        <?php if (!empty($_SESSION['cart'])) { ?>
            <form method="post">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="address">Shipping Address:</label>
                    <textarea id="address" name="address" rows="3" required></textarea>
                </div>
                <h3>Total Price: 
                    <strong style="color: red;">
                        <?php 
                        $total = 0;
                        foreach ($_SESSION['cart'] as $item) {
                            $total += $item['price'] * $item['quantity'];
                        }
                        echo number_format($total, 2) . " VND";
                        ?>
                    </strong>
                </h3>
                <button type="submit" name="checkout">✅ Confirm Checkout</button>
                <button type="button" class="back-home" onclick="window.location.href='Web.php'">🏠 Back to Home</button>
            </form>
        <?php } else { ?>
            <div class="empty-cart">
                <img src="image/00.jpg" alt="Empty Cart">
                <p>Your cart is empty!</p>
                <button type="button" onclick="window.location.href='Web.php'">🏠 Back to Home</button>
            </div>
        <?php } ?>
    </div>
</body>
</html>
