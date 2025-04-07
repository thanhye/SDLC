<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');

if (!$connect) {
    die('Kết nối thất bại: ' . mysqli_connect_error());
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Lấy thông tin sản phẩm
    $sql = "SELECT * FROM product WHERE product_id = $id";
    $result = mysqli_query($connect, $sql);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $product = [
            'id' => $row['product_id'],
            'name' => $row['product_name'],
            'price' => $row['product_price'],
            'image' => $row['product_image'],
            'quantity' => 1
        ];

        // Kiểm tra xem giỏ hàng đã có sản phẩm chưa
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Kiểm tra nếu sản phẩm đã tồn tại trong giỏ hàng
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product['id']) {
                $item['quantity']++; // Tăng số lượng nếu đã có
                $found = true;
                break;
            }
        }

        // Nếu sản phẩm chưa có, thêm vào giỏ hàng
        if (!$found) {
            $_SESSION['cart'][] = $product;
        }

        // Chuyển hướng về trang giỏ hàng hoặc sản phẩm
        header("Location: cart.php");
        exit();
    } else {
        echo "<p>Sản phẩm không tồn tại.</p>";
    }
} else {
    echo "<p>ID sản phẩm không hợp lệ.</p>";
}

// Đóng kết nối
mysqli_close($connect);
?>
