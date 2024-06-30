<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
// Memeriksa apakah user_id sudah diset, jika tidak maka redirect ke halaman login
if(!isset($user_id)){
   header('location:login.php');
}
// Update kuantitas produk dalam keranjang
if(isset($_POST['update_cart'])){
   $update_quantity = $_POST['update_quantity'];
   $update_id = $_POST['update_id'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
   $message[] = 'Kuantitas keranjang diperbaharui!';
}

// Menghapus produk tertentu dari keranjang
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   header('location:cart.php');
}
// Menghapus semua produk dari keranjang
if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Keranjangmu</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'header.php'; ?>
<div class="heading">
   <h3>Keranjangmu</h3>
   <p> <a href="home.php">Beranda</a> / Kerangjang </p>
</div>
<section class="shopping-cart">
   <h1 class="title">Produk yang ditambahkan</h1>
   <div class="box-container">
      <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
      ?>
      <div class="box">
         <img class="image" src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_cart['name']; ?></div>
         <div class="price">Rp<?php echo number_format($fetch_cart['price'], 0, ',', '.'); ?>,-</div>
         <form action="" method="post">
            <input type="hidden" name="update_id" value="<?php echo $fetch_cart['id']; ?>">
            <input type="number" min="1" name="update_quantity" value="<?php echo $fetch_cart['quantity']; ?>" class="qty">
            <input type="submit" name="update_cart" value="update" class="option-btn">
         </form>
         <div class="sub-total"> sub total : <span>Rp<?php echo number_format($sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']), 0, ',', '.'); ?>,-</span> </div>
      </div>
      <?php
      $grand_total += $sub_total;
         }
      }else{
         echo '<p class="empty">Belum ada produk yang ditambahkan</p>';
      }
      ?>
   </div>
   <div class="cart-total">
      <p>Total harga : <span>Rp<?php echo number_format($grand_total, 0, ',', '.'); ?>,-</span></p>
      <a href="shop.php" class="option-btn">Lanjut belanja</a>
      <a href="checkout.php" class="btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">Pembayaran</a>
      <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">Hapus Semua</a>
   </div>
</section>
<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
