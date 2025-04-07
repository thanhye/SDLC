<?php
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die('Error connecting to database');
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Câu lệnh SQL xóa sản phẩm
    $sql = "DELETE FROM product WHERE product_id = '$product_id'";

    if (mysqli_query($connect, $sql)) {
        // Chuyển hướng về trang Web.php sau khi xóa thành công
        header("Location: Web.php");
        exit(); // Dừng script sau khi chuyển hướng
    } else {
        echo "<script>alert('Error deleting product'); window.location.href='Web.php';</script>";
    }
}
?>
