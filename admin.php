<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Store</title>
    <link rel="stylesheet" href="stype.css">
    
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="logo">
                <img src="image/0.jpg" alt="Logo">
            </div>
            <div id="form_search">
            <form method="get" action="search.php">
                <input type="submit" value="Search">
            </form>
</div>


        </div>
        
        <div class="banner">
            <img src="Image/9.jpg" alt="Food Store Banner" width="100%">
        </div>

        <?php session_start(); ?>

        <div class="menu">
            <ul>
                <li><a href="User_management.php">User management</a></li>
                <li><a href="add_product.php">Add Food</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="Login.php">Login</a></li>

            </ul>
        </div>


        <div class="about-us">
            <div class="about-text">
                <h2>Welcome to Our Food Paradise!</h2>
                <p>Indulge in a world of flavors with our handpicked selection of fresh, delicious, and high-quality food items.  
                Whether you crave a quick snack or a full-course meal, we have something special for you.</p>
                <p>Order now and experience the joy of great food, delivered straight to your door!</p>
            </div>
            <div class="about-image">
                <img src="Image/0.jpg" alt="Delicious Food">
            </div>
        </div>

        <div class="content">
            <p>Menu</p>
            <div class="right">
                <?php
                $connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
                if (!$connect) {
                    echo ('Database Connection Error');
                }
                $sql = "SELECT * FROM product";
                $result = mysqli_query($connect, $sql);
                ?>

                <div class="product-grid">
                    <?php while ($row_product = mysqli_fetch_array($result)) { 
                        $product_id = $row_product['product_id'];
                        $product_name = $row_product['product_name'];
                        $product_price = $row_product['product_price'];
                        $product_img = $row_product['product_image'];
                        $product_old_price = isset($row_product['product_old_price']) ? $row_product['product_old_price'] : null;
                    ?>
                        <div class="product-card">
                            <img src="image/<?php echo htmlspecialchars($product_img); ?>" 
                                class="product-img" 
                                onerror="this.onerror=null; this.src='image/default.jpg';" />

                                <div class="product-info">
                            <h3><?php echo $product_name; ?></h3>
                            <?php if ($product_old_price) { ?>
                                <p class="price">
                                    <span class="old-price"><?php echo number_format($product_old_price, 0, ',', '.') . '₫'; ?></span> 
                                    <?php echo number_format($product_price, 0, ',', '.') . '₫'; ?>
                                </p>
                            <?php } else { ?>
                                <p class="price"><?php echo number_format($product_price, 0, ',', '.') . '₫'; ?></p>
                            <?php } ?>
                            <a href="detail.php?id=<?php echo $product_id; ?>" class="btn btn-detail">View Details</a>
                            <a href="cart.php?id=<?php echo $product_id; ?>" class="btn btn-add-cart">Add to Cart</a>
                            <a href="delete_product.php?id=<?php echo $product_id; ?>" class="btn btn-delete" onclick="return confirmDelete();">Delete</a>
                            </div>

                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this product?");
            }
        </script>

        <footer class="footer">
            <div class="footer-container">
                <!-- Giới thiệu -->
                <div class="footer-section about">
                    <h3>About Us</h3>
                    <p>We offer fresh and delicious food delivered to your door. Enjoy the best meals anytime, anywhere.</p>
                </div>

                <!-- Liên kết nhanh -->
                <div class="footer-section links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms.php">Terms of Service</a></li>
                    </ul>
                </div>

                <!-- Liên hệ -->
                <div class="footer-section contact">
                    <h3>Contact Us</h3>
                    <p>Email: support@foodstore.com</p>
                    <p>Phone: +123 456 789</p>
                    <p>Address: 123 Food Street, City</p>
                </div>

                <!-- Mạng xã hội -->
                <div class="footer-section social">
                    <h3>Follow Us</h3>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <script>
                    function confirmDelete(event, url) {
                    event.preventDefault(); // Ngăn chặn tải lại trang

                    Swal.fire({
                        title: "Are you sure?",
                        text: "This action cannot be undone!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#e74c3c",
                        cancelButtonColor: "#6c757d",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                }
                    function showAddToCartMessage(event) {
                    event.preventDefault(); // Ngăn chặn chuyển trang ngay lập tức
                    let url = event.target.href;

                    Swal.fire({
                        title: "Added to Cart!",
                        text: "You have successfully added this item to your cart.",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonText: "Go to Cart",
                        cancelButtonText: "Continue Shopping"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "cart.php"; // Chuyển hướng đến giỏ hàng
                        }
                    });
                }

                document.addEventListener("DOMContentLoaded", function () {
                    const userIcon = document.getElementById("userIcon");
                    const logoutDropdown = document.getElementById("logoutDropdown");
                
                    if (userIcon) {
                        userIcon.addEventListener("click", function () {
                            logoutDropdown.style.display = logoutDropdown.style.display === "block" ? "none" : "block";
                        });
                
                        // Ẩn dropdown khi click ra ngoài
                        document.addEventListener("click", function (event) {
                            if (!userIcon.contains(event.target) && !logoutDropdown.contains(event.target)) {
                                logoutDropdown.style.display = "none";
                            }
                        });
                    }
                });
                
            </script>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const userIcon = document.getElementById("userIcon");
                    const logoutDropdown = document.getElementById("logoutDropdown");

                    if (userIcon) {
                        userIcon.addEventListener("click", function () {
                            logoutDropdown.style.display = logoutDropdown.style.display === "block" ? "none" : "block";
                        });

                        // Ẩn dropdown khi click ra ngoài
                        document.addEventListener("click", function (event) {
                            if (!userIcon.contains(event.target) && !logoutDropdown.contains(event.target)) {
                                logoutDropdown.style.display = "none";
                            }
                        });
                    }
                });
            </script>

            <div class="footer-bottom">
                <p>&copy; 2025 Food Store | All rights reserved.</p>
            </div>
        </footer>

    </div>
</body>
</html>
