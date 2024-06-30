<?php
include 'config.php';
session_start();

$message = '';

if(isset($_POST['submit'])){
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = mysqli_real_escape_string($conn, $_POST['password']);

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');
   
   if(mysqli_num_rows($select_users) > 0){
      $row = mysqli_fetch_assoc($select_users);
      if(password_verify($password, $row['password'])){
         if($row['user_type'] == 'admin'){
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            header('location: admin_page.php');
            exit();
         }elseif($row['user_type'] == 'user'){
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            header('location: home.php');
            exit();
         }
      }else{
         $message = 'Password salah!';
      }
   }else{
      $message = 'Email tidak ditemukan!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
if(!empty($message)){
   echo '
   <div class="message">
      <span>'.$message.'</span>
      <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
   </div>
   ';
}
?>
<div class="form-container">
   <form action="" method="post">
      <h3>Login</h3>
      <input type="email" name="email" placeholder="Masukkan email" required class="box">
      <input type="password" name="password" placeholder="Masukkan password" required class="box">
      <input type="submit" name="submit" value="Masuk" class="btn">
      <p>Belum punya akun? <a href="register.php">Daftar</a></p>
   </form>
</div>
</body>
</html>
