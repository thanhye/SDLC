<?php
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

$search_query = "";
$result = null;

if (isset($_GET['query'])) {
    $search_query = mysqli_real_escape_string($connect, $_GET['query']);
    
    // Truy vấn tìm kiếm sản phẩm theo tên
    $sql = "SELECT * FROM product WHERE product_name LIKE '%$search_query%'";
    $result = mysqli_query($connect, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        /* Định dạng chung */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        .wrapper {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        /* Thanh tìm kiếm */
        #form_search {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        #form_search input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 2px solid #ff6600;
            border-radius: 5px 0 0 5px;
            outline: none;
        }

        #form_search input[type="submit"] {
            padding: 10px 20px;
            background-color: #ff6600;
            border: 2px solid #ff6600;
            color: white;
            cursor: pointer;
            border-radius: 0 5px 5px 0;
            transition: 0.3s;
        }

        #form_search input[type="submit"]:hover {
            background-color: #cc5500;
        }

        /* Kết quả tìm kiếm */
        h2 {
            text-align: center;
            color: #333;
        }

        /* Bố cục sản phẩm */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        /* Thẻ sản phẩm */
        .product-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        /* Ảnh sản phẩm */
        .product-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* Thông tin sản phẩm */
        .product-info h3 {
            font-size: 18px;
            color: #333;
            margin: 10px 0;
        }

        .price {
            font-size: 16px;
            color: #ff6600;
            font-weight: bold;
        }

        .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 14px;
            margin-right: 5px;
        }

        /* Nút bấm */
        .btn {
            display: block;
            padding: 10px;
            margin: 10px auto;
            width: 80%;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            color: white;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-detail {
            background-color: #28a745;
        }

        .btn-add-cart {
            background-color: #ff6600;
        }

        .btn:hover {
            opacity: 0.8;
        }

        /* Footer */
        .footer {
            background-color: #222;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
        }

        .footer a {
            color: #ff6600;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        
        <!-- Thanh tìm kiếm -->
        <div id="form_search">
            <form method="get" action="search.php">
                <input type="text" name="query" placeholder="Search for a Food Item" value="<?php echo htmlspecialchars($search_query); ?>">
                <input type="submit" value="Search">
            </form>
        </div>

        <h2>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
        
        <div class="product-grid">
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="product-card">';
                    echo '<img src="image/' . htmlspecialchars($row['product_image']) . '" class="product-img" onerror="this.onerror=null; this.src=\'image/default.jpg\';">';
                    echo '<div class="product-info">';
                    echo '<h3>' . htmlspecialchars($row['product_name']) . '</h3>';
                    if (!empty($row['product_old_price'])) {
                        echo '<p class="price"><span class="old-price">$' . $row['product_old_price'] . '</span> $' . $row['product_price'] . '</p>';
                    } else {
                        echo '<p class="price">$' . $row['product_price'] . '</p>';
                    }
                    echo '<a href="detail.php?id=' . $row['product_id'] . '" class="btn btn-detail">View Details</a>';
                    echo '<a href="cart.php?id=' . $row['product_id'] . '" class="btn btn-add-cart">Add to Cart</a>';
                    echo '</div></div>';
                }
            } else {
                echo "<p style='text-align: center;'>No results found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
