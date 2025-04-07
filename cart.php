<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");

// Handle adding to cart
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM product WHERE product_id = $product_id";
    $result = mysqli_query($connect, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = [
                'name' => $row['product_name'],
                'price' => $row['product_price'],
                'image' => $row['product_image'],
                'quantity' => 1
            ];
        } else {
            $_SESSION['cart'][$product_id]['quantity']++;
        }
    }
    header("Location: cart.php");
}

// Handle updating and removing items
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $id => $qty) {
            if ($qty > 0) {
                $_SESSION['cart'][$id]['quantity'] = $qty;
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
    } elseif (isset($_POST['remove_item'])) {
        unset($_SESSION['cart'][$_POST['product_id']]);
    }
    header("Location: cart.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <style>
/* ======= Entire Page ======= */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ff9a9e, #fad0c4);
            margin: 0;
            padding: 0;
        }

        /* ======= Cart Container ======= */
        .container {
            width: 80%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.8s ease-in-out;
        }

        /* ======= Fade In Effect ======= */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ======= Title ======= */
        h2 {
            text-align: center;
            color: #ff4081;
            font-size: 28px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        /* ======= Cart Table ======= */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        th {
            background: linear-gradient(to right, #ff4081, #ff80ab);
            color: white;
            font-size: 16px;
        }

        /* ======= Product Image ======= */
        td img {
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        td img:hover {
            transform: scale(1.1);
        }

        /* ======= Buttons with Gradient Effect ======= */
        button {
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            padding: 12px 18px;
            border-radius: 25px;
            transition: all 0.3s ease-in-out;
            box-shadow: 2px 3px 10px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
        }

        /* Remove Button */
        button[name="remove_item"] {
            background: linear-gradient(to right, #ff1744, #d50000);
            color: white;
        }

        button[name="remove_item"]:hover {
            background: linear-gradient(to right, #ff5252, #ff1744);
            transform: scale(1.05);
        }

        /* Update Cart Button */
        button[name="update_cart"] {
            background: linear-gradient(to right, #00e676, #00c853);
            color: white;
        }

        button[name="update_cart"]:hover {
            background: linear-gradient(to right, #00ff80, #00e676);
            transform: scale(1.05);
        }

        /* Checkout Button */
        button[type="button"] {
            background: linear-gradient(to right, #2979ff, #2962ff);
            color: white;
        }

        button[type="button"]:hover {
            background: linear-gradient(to right, #82b1ff, #2979ff);
            transform: scale(1.05);
        }

        /* Return Button */
        button.return-btn {
            background: linear-gradient(to right, #ff9100, #ff6d00);
            color: white;
        }

        button.return-btn:hover {
            background: linear-gradient(to right, #ffa726, #ff9100);
            transform: scale(1.05);
        }

        /* ======= Quantity Input ======= */
        input[type="number"] {
            width: 60px;
            padding: 5px;
            text-align: center;
            border: 2px solid #ff4081;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
        }

        /* ======= Total Price ======= */
        .total-row {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            background: linear-gradient(to right, #ffcc80, #ffb74d);
            color: #333;
            padding: 15px;
            border-radius: 8px;
        }

        /* ======= When Cart is Empty ======= */
        .empty-cart {
            text-align: center;
            font-size: 20px;
            color: #777;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* ======= Empty Cart Image ======= */
        .empty-cart img {
            width: 150px;
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
        <h2>🛒 Your Shopping Cart</h2>
        <button type="submit" name="update_cart">Update Cart</button>
        <button type="button" onclick="window.location.href='pay.php'">Checkout</button>
        <button type="button" onclick="window.location.href='Web.php'">🏠 Back to Home</button>

        <?php if (!empty($_SESSION['cart'])) { ?>
            <form method="post">
                <table>
                    <tr>
                        <th>Image</th>
                        <th>Food Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $id => $item) { 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><img src="image/<?php echo htmlspecialchars($item['image']); ?>" width="80" height="80"></td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo number_format($item['price'], 2) . " VND"; ?></td>
                        <td><input type="number" name="quantity[<?php echo $id; ?>]" value="<?php echo $item['quantity']; ?>" min="1"></td>
                        <td><?php echo number_format($subtotal, 2) . " VND"; ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <button type="submit" name="remove_item">❌ Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </form>
        <?php } else { ?>
            <div class="empty-cart">
                <img src="image/00.jpg">
                <p>Your cart is empty!</p>
            </div>
        <?php } ?>
    </div>
</body>
</html>
