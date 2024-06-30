<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
}
// Proses saat tombol "order now" ditekan
if(isset($_POST['order_btn'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, $_POST['street'].', '. $_POST['city'].' - '. $_POST['pin_code']);
   $placed_on = date('d-M-Y');
   $cart_total = 0;
   $cart_products[] = '';

   // Mengambil produk dari keranjang pengguna
   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   
   // Menghitung total harga dari semua produk di keranjang
   if(mysqli_num_rows($cart_query) > 0){
      while($cart_item = mysqli_fetch_assoc($cart_query)){
         $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }
   $total_products = implode(', ',$cart_products);
   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');
   // Memeriksa apakah keranjang kosong sebelum menambahkan pesanan
   if($cart_total == 0){
      $message[] = 'Keranjang Anda kosong!';
   }else{
      // Jika pesanan sudah ada, tampilkan pesan
      if(mysqli_num_rows($order_query) > 0){
         $message[] = 'Pesanan sudah ditambahkan!'; 
      }else{
         // Jika pesanan belum ada, tambahkan pesanan ke dalam database
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
         $message[] = 'Pesanan berhasil ditambahkan!';
         // Hapus semua produk dari keranjang setelah berhasil memesan
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      }
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Keranjang</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>
<div class="heading">
   <h3>Keranjang</h3>
   <p> <a href="home.php">Beranda</a> / Pembayaran </p>
</div>

<section class="display-order">
   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['name']; ?> <span>(Rp<?php echo number_format($fetch_cart['price'], 0, ',', '.'); ?> x <?php echo $fetch_cart['quantity']; ?>)</span> </p>

   <?php
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   <div class="grand-total">Total harga: <span>Rp<?php echo number_format($grand_total, 0, ',', '.'); ?></span></div>
</section>

<section class="checkout">
   <form action="" method="post">
      <h3>Pesan Sekarang</h3>
      <div class="flex">
         <div class="inputBox">
            <span>Nama :</span>
            <input type="text" name="name" required placeholder="Masukkan nama">
         </div>
         <div class="inputBox">
            <span>Telepon :</span>
            <input type="number" name="number" required placeholder="Masukkan nomor telepon">
         </div>
         <div class="inputBox">
            <span>Email :</span>
            <input type="email" name="email" required placeholder="Masukkan email">
         </div>
         <div class="inputBox">
            <span>Metode pembayaran:</span>
            <select name="method">
               <option value="cash on delivery">COD</option>
               <option value="credit card">Kartu Kredit</option>
               <option value="transfer">Transfer Bank</option>
               <option value="e-wallet">e-wallet</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Alamat</span>
            <input type="text" name="street" required placeholder="Masukkan alamat Anda">
         </div>
         <div class="inputBox">
            <span>Kota :</span>
            <input type="text" name="city" required placeholder="Masukkan Kota/Kab Anda">
         </div>
         <div class="inputBox">
            <span>Provinsi:</span>
            <input type="text" name="state" required placeholder="Masukkan provinsi Anda">
         </div>
         <div class="inputBox">
            <span>Kode pos :</span>
            <input type="number" min="0" name="pin_code" required placeholder="Masukkan kode pos">
         </div>
      </div>
      <input type="submit" value="Pesan Sekarang" class="btn" name="order_btn">
   </form>
</section>
<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>