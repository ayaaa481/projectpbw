<?php
// Memasukkan file konfigurasi database
include 'config.php';

// Variabel untuk data admin
$admin_name = 'Admin';
$admin_email = 'admin@example.com';
$hashed_password = 'HASHED_PASSWORD_FROM_SCRIPT'; // Ganti dengan hash password yang dihasilkan

// Periksa apakah admin sudah ada
$select_admin = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$admin_email'") or die('query failed');

//kondisi jika admin ada
if(mysqli_num_rows($select_admin) > 0){
   echo 'Admin sudah ada!';
} else {
   // Jika belum, tambahkan admin ke database
   $insert_admin = mysqli_query($conn, "INSERT INTO `users` (name, email, password, user_type) VALUES ('$admin_name', '$admin_email', '$hashed_password', 'admin')") or die('query failed');
   
   // Periksa apakah berhasil menambahkan admin
   if($insert_admin){
      echo 'Admin berhasil ditambahkan!';
   } else {
      echo 'Gagal menambahkan admin!';
   }
}
?>
