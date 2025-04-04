<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $id = create_unique_id();
   $name = htmlspecialchars($_POST['name']); // Sanitize with htmlspecialchars
   $number = htmlspecialchars($_POST['number']);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize email
   $pass = sha1($_POST['pass']);
   $pass = htmlspecialchars($pass);
   $c_pass = sha1($_POST['c_pass']);
   $c_pass = htmlspecialchars($c_pass);
   $role = $_POST['role']; // Get the selected role
   $role = filter_var($role, FILTER_SANITIZE_STRING); // Sanitize role

   // Validate role to ensure it's one of the allowed values
   $valid_roles = ['buyer', 'seller', 'landlord'];
   if(!in_array($role, $valid_roles)){
      $warning_msg[] = 'Invalid role selected!';
   }else{
      $select_users = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_users->execute([$email]);

      if($select_users->rowCount() > 0){
         $warning_msg[] = 'Email already taken!';
      }else{
         if($pass != $c_pass){
            $warning_msg[] = 'Password not matched!';
         }else{
            $insert_user = $conn->prepare("INSERT INTO `users`(id, name, number, email, password, role) VALUES(?,?,?,?,?,?)");
            $insert_user->execute([$id, $name, $number, $email, $c_pass, $role]); // Use selected role
            
            if($insert_user){
               $verify_users = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
               $verify_users->execute([$email, $pass]);
               $row = $verify_users->fetch(PDO::FETCH_ASSOC);
            
               if($verify_users->rowCount() > 0){
                  setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
                  setcookie('user_role', $row['role'], time() + 60*60*24*30, '/');
                  $success_msg[] = 'Registration successful! Welcome, ' . $name . '!';
               }else{
                  $error_msg[] = 'Something went wrong!';
               }
            }
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
   <title>Register</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>Create an Account!</h3>
      <input type="text" name="name" required maxlength="50" placeholder="Enter your name" class="box">
      <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="box">
      <input type="number" name="number" required min="0" max="9999999999" maxlength="10" placeholder="Enter your number" class="box">
      <input type="password" name="pass" required maxlength="20" placeholder="Enter your password" class="box">
      <input type="password" name="c_pass" required maxlength="20" placeholder="Confirm your password" class="box">
      <select name="role" required class="box">
         <option value="" disabled selected>Select your role</option>
         <option value="buyer">Buyer</option>
         <option value="seller">Seller</option>
         <option value="landlord">Landlord</option>
      </select>
      <p>Already have an account? <a href="login.php">Login now</a></p>
      <input type="submit" value="Register Now" name="submit" class="btn">
   </form>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
<?php include 'components/message.php'; ?>

</body>
</html>