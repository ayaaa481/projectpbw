<?php
include 'config.php';

$message = [];

if(isset($_POST['submit'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = mysqli_real_escape_string($conn, $_POST['password']);
   $confirm_password = mysqli_real_escape_string($conn, $_POST['cpassword']);

   if($password != $confirm_password){
      $message[] = 'Konfirmasi password tidak cocok!';
   }else{
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');
      
      if(mysqli_num_rows($select_users) > 0){
         $message[] = 'Email sudah terdaftar!';
      }else{
         $insert_query = "INSERT INTO `users` (name, email, password, user_type) VALUES ('$name', '$email', '$hashed_password', 'user')";
         if(mysqli_query($conn, $insert_query)){
            $message[] = 'Registrasi berhasil!';
            header('Location: login.php');
            exit();
         }else{
            $message[] = 'Gagal melakukan registrasi!';
         }
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
   <title>Registrasi</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
foreach($message as $msg){
   echo '
   <div class="message">
      <span>'.$msg.'</span>
      <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
   </div>
   ';
}
?>
<div class="form-container">
   <form action="" method="post">
      <h3>Registrasi</h3>
      <input type="text" name="name" placeholder="Masukkan nama" required class="box">
      <input type="email" name="email" placeholder="Masukkan email" required class="box">
      <input type="password" name="password" placeholder="Masukkan password" required class="box">
      <input type="password" name="cpassword" placeholder="Konfirmasi password" required class="box">
      <input type="submit" name="submit" value="Daftar Sekarang" class="btn">
      <p>Sudah punya akun? <a href="login.php">Masuk</a></p>
   </form>
</div>
</body>
</html>
