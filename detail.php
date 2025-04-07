<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Chi tiết sản phẩm</title>
    <style>
        /* Tổng thể */
/* Tổng thể */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #f9d423, #ff4e50);
            color: #333;
        }

        /* Khung chứa sản phẩm */
        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 25px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            animation: fadeIn 0.6s ease-in-out;
        }

        /* Ảnh sản phẩm */
        .product-image {
            flex: 1;
            padding: 20px;
            text-align: center;
        }

        .product-image img {
            width: 100%;
            max-width: 450px;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            transition: transform 0.4s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .product-image img:hover {
            transform: scale(1.07);
            box-shadow: 0 10px 30px rgba(255, 78, 80, 0.5);
        }

        /* Thông tin sản phẩm */
        .product-details {
            flex: 1;
            padding: 20px;
        }

        .product-details h2 {
            font-size: 30px;
            font-weight: bold;
            color: #ff4e50;
            margin-bottom: 15px;
            text-transform: uppercase;
            text-shadow: 2px 2px 10px rgba(255, 78, 80, 0.3);
        }

        .product-details p {
            font-size: 18px;
            line-height: 1.7;
            color: #555;
        }

        .product-price {
            font-size: 26px;
            color: #e67e22;
            font-weight: bold;
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            padding: 10px;
            display: inline-block;
            border-radius: 8px;
            color: white;
        }

        /* Nút thêm vào giỏ hàng */
        .add-to-cart {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(to right, #36d1dc, #5b86e5);
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(91, 134, 229, 0.3);
            transition: all 0.3s ease-in-out;
            text-align: center;
        }

        .add-to-cart:hover {
            background: linear-gradient(to right, #5b86e5, #36d1dc);
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(91, 134, 229, 0.5);
        }

        /* Đường phân cách */
        .separator {
            border-bottom: 4px dashed #ff4e50;
            margin: 20px 0;
        }

        /* Hiệu ứng fade-in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive cho màn hình nhỏ */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                text-align: center;
            }

            .product-image img {
                max-width: 100%;
            }

            .add-to-cart {
                width: 100%;
                display: block;
            }
        }

    </style>
</head>
<body>

<div class="container">
    <?php 
    // Kết nối database
    $connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');

    // Kiểm tra kết nối
    if (!$connect) {
        die('Kết nối thất bại: ' . mysqli_connect_error());
    }

    // Kiểm tra nếu có 'id' trong URL
    if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
        $id = intval($_GET["id"]); // Chuyển đổi thành số nguyên để tránh lỗi injection

        // Truy vấn dữ liệu
        $sql = "SELECT * FROM product WHERE product_id = $id";
        $result = mysqli_query($connect, $sql);

        // Kiểm tra nếu sản phẩm tồn tại
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $product_id = $row['product_id'];
                $product_image = htmlspecialchars($row['product_image']);
                $default_image = "image/default.jpg"; // Ảnh mặc định nếu không có ảnh

                ?>
                <!-- Hiển thị sản phẩm -->
                <div class="product-image">
                    <img src="image/<?php echo $product_image; ?>" 
                         onerror="this.src='<?php echo $default_image; ?>';">
                </div>
                <div class="product-details">
                    <h2><?php echo htmlspecialchars($row['product_name']); ?></h2>
                    <p class="product-price"><?php echo number_format($row['product_price'], 2) . " $"; ?></p>

                    <br>
                    <a href='cart.php?id=<?php echo $product_id; ?>' class="add-to-cart">
                        🛒 Thêm vào giỏ hàng
                    </a>

                    </a>  
                    <br><br>
                    <div class="separator"></div>
                    <h2>Thông tin sản phẩm:</h2>               
                    <p><?php echo nl2br(htmlspecialchars($row["product_description"])); ?></p>
                </div>
                <?php
            }
        } else {
            echo "<p style='text-align:center; color:red;'>Không tìm thấy sản phẩm.</p>";
        }
    } else {
        echo "<p style='text-align:center; color:red;'>ID sản phẩm không hợp lệ.</p>";
    }

    // Đóng kết nối
    mysqli_close($connect);
    ?>
</div>

</body>
</html>
