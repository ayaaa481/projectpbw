<?php
// Mengimpor file konfigurasi untuk koneksi ke database
include 'config.php';
session_start();

// Memeriksa apakah admin sudah masuk atau belum, jika belum akan diarahkan ke halaman login
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
   exit;
}
// Memproses pembaruan status pembayaran pesanan jika form update_order dikirimkan
if(isset($_POST['update_order'])){
   $order_update_id = $_POST['order_id'];
   if(isset($_POST['update_payment'])){
      $update_payment = $_POST['update_payment'];
      mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
      $message[] = 'Status pembayaran telah diperbaharui!';
   } else {
      $message[] = 'Silakan pilih status pembayaran yang baru!';
   }
}
// Memproses penghapusan pesanan jika parameter delete diterima melalui metode GET
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php'); // Mengarahkan kembali ke halaman daftar pesanan setelah penghapusan
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="orders">
   <h1 class="title">Daftar Pesanan</h1>
   <div class="box-container">
      <?php
      $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
         <p> User id : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
         <p>Tanggal Ditambahkan : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
         <p> Nama : <span><?php echo $fetch_orders['name']; ?></span> </p>
         <p> Telepon : <span><?php echo $fetch_orders['number']; ?></span> </p>
         <p> Email : <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> Alamat : <span><?php echo $fetch_orders['address']; ?></span> </p>
         <p> Total Produk : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
         <p> Total Harga : <span>Rp<?php echo number_format($fetch_orders['total_price'], 0, ',', '.'); ?></span> </p>
         <p> Metode Pembayaran : <span><?php echo $fetch_orders['method']; ?></span> </p>
         
         <!-- Form untuk memperbarui status pembayaran pesanan -->
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
            <select name="update_payment">
               <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
               <option value="Tertunda">Tertunda</option>
               <option value="Sukses">Sukses</option>
            </select>
            <input type="submit" value="update" name="update_order" class="option-btn">
            <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Hapus pesanan ini?');" class="delete-btn">hapus</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Belum ada pesanan yang ditambahkan!</p>'; // Tampilkan pesan jika tidak ada pesanan
      }
      ?>
   </div>
</section>
<script src="js/admin_script.js"></script>
</body>
</html>