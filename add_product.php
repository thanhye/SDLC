<?php
session_start();

// Database connection
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission when the user clicks "Add Product"
if (isset($_POST['add_product'])) {
    // Retrieve form data
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $quantity = $_POST['quantity'];
    $product_description = $_POST['product_description'];

    // Handle image upload
    $product_img = $_FILES['product_image']['name'];
    $product_img_tmp = $_FILES['product_image']['tmp_name'];
    $product_img_error = $_FILES['product_image']['error'];
    $product_img_size = $_FILES['product_image']['size'];

    // Ensure the image folder exists
    $image_folder = "Image/";
    if (!file_exists($image_folder)) {
        mkdir($image_folder, 0777, true);
    }

    $target_path = "";
    if ($product_img_error === UPLOAD_ERR_OK) {
        if ($product_img_size <= 2 * 1024 * 1024) { // Limit 2MB
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $file_extension = strtolower(pathinfo($product_img, PATHINFO_EXTENSION));
            if (in_array($file_extension, $allowed_extensions)) {
                // Save image with a unique name
                $new_file_name = time() . "_" . basename($product_img);
                $target_path = $image_folder . $new_file_name;
                if (move_uploaded_file($product_img_tmp, $target_path)) {
                    $target_path = $new_file_name; // Store only the filename in the database
                } else {
                    echo "<script>alert('Error saving image! Check folder permissions.');</script>";
                    $target_path = "";
                }
            } else {
                echo "<script>alert('Invalid image format! Only JPG, JPEG, PNG, and GIF are allowed.');</script>";
            }
        } else {
            echo "<script>alert('Image too large! Maximum allowed size is 2MB.');</script>";
        }
    } else {
        echo "<script>alert('An error occurred while uploading the image.');</script>";
    }

    // Check if product ID already exists
    $check_sql = "SELECT * FROM product WHERE product_id = '$product_id'";
    $result = mysqli_query($connect, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Product ID already exists. Please use a different ID.');</script>";
    } else {
        // Insert product into the database
        $sql = "INSERT INTO product (product_id, product_name, product_price, quantity, product_image, product_description) 
        VALUES ('$product_id', '$product_name', '$product_price', '$quantity', '$target_path', '$product_description')";

        if (mysqli_query($connect, $sql)) {
            header("Location: Web.php"); // Redirect to the main page
            exit();
        } else {
            echo "<script>alert('Failed to add product: " . mysqli_error($connect) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        /* Reset mặc định */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f3f4f6, #e0e7ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: 0.3s;
        }

        input[type="text"]:focus,
        input[type="file"]:focus {
            border-color: #4c51bf;
            outline: none;
            box-shadow: 0 0 8px rgba(76, 81, 191, 0.3);
        }

        .btn-submit {
            background: #4c51bf;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background: #3730a3;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Product</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_id">Product ID:</label>
                <input type="text" id="product_id" name="product_id" required>
            </div>

            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>
            </div>

            <div class="form-group">
                <label for="product_price">Product Price:</label>
                <input type="text" id="product_price" name="product_price" required>
            </div>

            <div class="form-group">
                <label for="quantity">Product Quantity:</label>
                <input type="text" id="quantity" name="quantity" required>
            </div>

            <div class="form-group">
                <label for="product_image">Product Image:</label>
                <input type="file" id="product_image" name="product_image" required>
            </div>

            <div class="form-group">
                <label for="product_description">Product Description:</label>
                <input type="text" id="product_description" name="product_description" required>
            </div>

            <button type="submit" name="add_product" class="btn-submit">Add Product</button>
        </form>
    </div>
</body>
</html>
