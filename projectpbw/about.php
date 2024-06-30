<?php
//memasukkan file konfigurasi dan memulai sesi
include 'config.php';
session_start();

//memeriksa apakah user_id sudah ada, jika tidak, redirect ke halaman login
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tentang</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>
<!--Section Heading-->
<div class="heading">
   <h3>Tentang Kami</h3>
   <p> <a href="home.php">Beranda</a> / Tentang Kami</p>
</div>

<!--Section Tentang-->
<section class="about">
   <div class="flex">
      <div class="image">
         <img src="images/about-img.jpg" alt="">
      </div>
      <div class="content">
         <h3>Mengapa memilih Grampedia?</h3>
         <p>Grampedia adalah toko buku online terpercaya yang menyediakan pilihan buku terbaru, terbaik, dan terlengkap dari semua penerbit di Indonesia.</p>
         <p>Jenis buku yang dimaksudkan meliputi, novel, kumpulan cerpen, kumpulan puisi, ensiklopedia, dan komik.</p>
         <a href="contact.php" class="btn">Hubungi Kami</a>
      </div>
   </div>
</section>

<!-- Section Daftar Penulis Terbaik -->
<section class="authors">
   <h1 class="title">Penulis Terbaik</h1>
   <div class="box-container">
      <div class="box">
         <img src="images/author-1.jpg" alt="">
         <div class="share">
            <a href="https://www.facebook.com/tokobukutereliye/" class="fab fa-facebook-f"></a>
            <a href="https://x.com/darwistereliye" class="fab fa-twitter"></a>
            <a href="https://www.instagram.com/tereliyewriter?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="fab fa-instagram"></a>
            
         </div>
         <h3>Tere Liye</h3>
      </div>

      <div class="box">
         <img src="images/author-2.jpg" alt="">
         <div class="share">
            <a href="https://x.com/Andreahirata" class="fab fa-twitter"></a>
            <a href="https://www.instagram.com/hirataandrea?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="fab fa-instagram"></a>
         </div>
         <h3>Andrea Hinata</h3>
      </div>

      <div class="box">
         <img src="images/author-3.jpg" alt="">
         <div class="share">
            <a href="https://www.facebook.com/ekakurniawan.1975/" class="fab fa-facebook-f"></a>
         </div>
         <h3>Eka Kurniawan</h3>
      </div>

      <div class="box">
         <img src="images/author-4.jpg" alt="">
         <div class="share">
            <a href="https://www.instagram.com/leilachudori?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="fab fa-instagram"></a>
         </div>
         <h3>Leila S. Chudori</h3>
      </div>

      <div class="box">
         <img src="images/author-5.jpeg" alt="">
         <div class="share">
            <a href="https://www.instagram.com/pramoedyaanantatoer/" class="fab fa-instagram"></a>
         </div>
         <h3>Pramoedya Ananta Toer</h3>
      </div>

      <div class="box">
         <img src="images/author-6.jpeg" alt="">
         <div class="share">
            <a href="https://www.facebook.com/JKRowling" class="fab fa-facebook-f"></a>
            <a href="https://x.com/jk_rowling" class="fab fa-twitter"></a>
            <a href="https://www.jkrowling.com/" class="fab fa-blog"></a>
         </div>
         <h3>J. K. Rowling</h3>
      </div>
   </div>
</section>
<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>