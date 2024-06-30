<?php
// Include file konfigurasi untuk koneksi ke database
include 'config.php';
session_start();
// Memeriksa apakah admin sudah masuk atau belum, jika belum akan diarahkan ke halaman login
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
};

// Proses penambahan produk baru
if(isset($_POST['add_product'])){
   // Mengambil dan membersihkan data input dari form
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;
   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'Nama produk sudah adaa';
   }else{
      // Menjalankan query untuk menambahkan produk baru
      $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, price, image) VALUES('$name', '$price', '$image')") or die('query failed');
      if($add_product_query){
         if($image_size > 2000000){
            $message[] = 'Maksimal gambar 2 MB ';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Produk berhasil ditambahkan!';
         }
      }else{
         $message[] = 'Produk tidak dapat ditambahkan!';
      }
   }
}
// Proses penghapusan produk
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   // Mengambil nama gambar produk yang akan dihapus
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   // Menghapus gambar dari folder
   unlink('uploaded_img/'.$fetch_delete_image['image']);
    // Menghapus produk dari database
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}
// Proses pembaruan data produk
if(isset($_POST['update_product'])){
   // Mengambil data yang diperlukan dari form
   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];

   // Menjalankan query untuk memperbarui nama dan harga produk
   mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price' WHERE id = '$update_p_id'") or die('query failed');

   // Memproses pembaruan gambar produk jika ada
   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   // Memeriksa apakah ada gambar baru yang diunggah
   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'Maksimal ukuran gambar 2 MB';
      }else{
         // Menghapus gambar lama dan mengunggah gambar baru
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }
   header('location:admin_products.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Produk</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
   
<?php include 'admin_header.php'; ?>
<!-- Section untuk menambahkan produk baru -->
<section class="add-products">
   <h1 class="title">Produk Toko</h1>
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Tambahkan Produk</h3>
      <input type="text" name="name" class="box" placeholder="Masukkan nama buku" required>
      <input type="number" min="0" name="price" class="box" placeholder="Masukkan harga produk" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="Tambahkan produk" name="add_product" class="btn">
   </form>
</section>

<!-- Section untuk menampilkan produk yang ada -->
<section class="show-products">
   <div class="box-container">
      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="price">Rp<?php echo number_format($fetch_products['price'], 0, ',', '.'); ?></div>
         <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">Edit</a>
         <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Hapus produk ini?');">Hapus</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Belum ada produk yang ditambahkan!</p>';
      }
      ?>
   </div>
</section>

<!-- Section untuk form edit produk -->
<section class="edit-product-form">
   <?php
      // Menampilkan form edit produk berdasarkan ID produk yang dipilih untuk diubah
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter product name">
      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter product price">
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <?php
         }
      }
      }else{
         // Menyembunyikan form edit produk jika tidak ada yang dipilih untuk diubah
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>
</section>
<script src="js/admin_script.js"></script>
</body>
</html>