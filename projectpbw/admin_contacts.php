<?php
// Menghubungkan ke file konfigurasi database
include 'config.php';

// Memulai sesi untuk admin
session_start();

// Memeriksa apakah admin sudah login
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');// Redirect ke halaman login jika admin belum login
   exit;
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed'); // Menghapus pesan berdasarkan id
   header('location:admin_contacts.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pesan</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
   
<?php include 'admin_header.php'; ?>
<section class="messages">
   <h1 class="title"> Pesan </h1>
   <div class="box-container">
   <?php
      // Mengambil semua pesan dari tabel message
      $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
      if(mysqli_num_rows($select_message) > 0){ // Jika terdapat pesan
         while($fetch_message = mysqli_fetch_assoc($select_message)){
      
   ?>
   <div class="box">
      <p> user id : <span><?php echo $fetch_message['user_id']; ?></span> </p>
      <p> nama : <span><?php echo $fetch_message['name']; ?></span> </p>
      <p> telepon : <span><?php echo $fetch_message['number']; ?></span> </p>
      <p> email : <span><?php echo $fetch_message['email']; ?></span> </p>
      <p> pesan : <span><?php echo $fetch_message['message']; ?></span> </p>
      <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Hapus pesan ini?');" class="delete-btn">Hapus pesan</a>
   </div>
   <?php
      };
   }else{
      echo '<p class="empty">Belum ada pesan!</p>'; // Menampilkan pesan jika tidak ada pesan yang ditemukan
   }
   ?>
   </div>

</section>
<!-- Menghubungkan dengan file JavaScript kustom untuk admin -->
<script src="js/admin_script.js"></script>

</body>
</html>