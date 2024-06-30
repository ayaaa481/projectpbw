<?php
$admin_password = 'admin'; // Ganti 'admin_password' dengan password yang diinginkan
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
echo $hashed_password;
?>
