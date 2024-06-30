<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
}

// Proses saat tombol "Kirim pesan" ditekan
if(isset($_POST['send'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   // Memeriksa apakah pesan dengan detail yang sama sudah ada sebelumnya
   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');
   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'Pesan sudah terkirim sebelumnya!';
   }else {
      // Jika belum ada pesan yang sama, tambahkan pesan ke dalam database
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      $message[] = 'Pesan berhasil dikirim!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
<?php include 'header.php'; ?>
<div class="heading">
   <h3>Kontak Kami</h3>
   <p> <a href="home.php">Beranda</a> / Kontak </p>
</div>

<section class="contact">
   <form action="" method="post">
      <h3>Katakan sesuatu!</h3>
      <input type="text" name="name" required placeholder="Masukkan nama" class="box">
      <input type="email" name="email" required placeholder="Masukkan email" class="box">
      <input type="number" name="number" required placeholder="Masukkan no telepon" class="box">
      <textarea name="message" class="box" placeholder="Masukkan pesan" id="" cols="30" rows="10"></textarea>
      <input type="submit" value="Kirim pesan" name="send" class="btn">
   </form>
</section>
<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>