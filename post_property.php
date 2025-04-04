<?php  

include 'components/connect.php';

if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_role'])){
   $user_id = $_COOKIE['user_id'];
   $user_role = $_COOKIE['user_role'];
}else{
   $user_id = '';
   $user_role = '';
   header('location:login.php');
   exit();
}

// Restrict access to only sellers and landlords
if($user_role !== 'seller' && $user_role !== 'landlord'){
   $warning_msg[] = 'Only sellers and landlords can post properties!';
}else{
   if(isset($_POST['post'])){

      $id = create_unique_id();
      $property_name = $_POST['property_name'];
      $property_name = filter_var($property_name, FILTER_SANITIZE_STRING);
      $address = $_POST['address'];
      $address = filter_var($address, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $type = $_POST['type'];
      $type = filter_var($type, FILTER_SANITIZE_STRING);
      $status = $_POST['status'];
      $status = filter_var($status, FILTER_SANITIZE_STRING);
      $offer = $_POST['offer'];
      $offer = filter_var($offer, FILTER_SANITIZE_STRING);
      $furnished = $_POST['furnished'];
      $furnished = filter_var($furnished, FILTER_SANITIZE_STRING);
      $bhk = $_POST['bhk'];
      $bhk = filter_var($bhk, FILTER_SANITIZE_STRING);
      $age = $_POST['age'];
      $age = filter_var($age, FILTER_SANITIZE_STRING);
      $total_floors = $_POST['total_floors'];
      $total_floors = filter_var($total_floors, FILTER_SANITIZE_STRING);
      $description = $_POST['description'];
      $description = filter_var($description, FILTER_SANITIZE_STRING);

      if(isset($_POST['play_ground'])){
         $play_ground = $_POST['play_ground'];
         $play_ground = filter_var($play_ground, FILTER_SANITIZE_STRING);
      }else{
         $play_ground = 'no';
      }
      
      if(isset($_POST['gym'])){
         $gym = $_POST['gym'];
         $gym = filter_var($gym, FILTER_SANITIZE_STRING);
      }else{
         $gym = 'no';
      }
   
      $image_02 = $_FILES['image_02']['name'];
      $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
      $image_02_ext = pathinfo($image_02, PATHINFO_EXTENSION);
      $rename_image_02 = create_unique_id().'.'.$image_02_ext;
      $image_02_tmp_name = $_FILES['image_02']['tmp_name'];
      $image_02_size = $_FILES['image_02']['size'];
      $image_02_folder = 'uploaded_files/'.$rename_image_02;

      if(!empty($image_02)){
         if($image_02_size > 2000000){
            $warning_msg[] = 'Image 02 size is too large!';
         }else{
            move_uploaded_file($image_02_tmp_name, $image_02_folder);
         }
      }else{
         $rename_image_02 = '';
      }

      $image_03 = $_FILES['image_03']['name'];
      $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
      $image_03_ext = pathinfo($image_03, PATHINFO_EXTENSION);
      $rename_image_03 = create_unique_id().'.'.$image_03_ext;
      $image_03_tmp_name = $_FILES['image_03']['tmp_name'];
      $image_03_size = $_FILES['image_03']['size'];
      $image_03_folder = 'uploaded_files/'.$rename_image_03;

      if(!empty($image_03)){
         if($image_03_size > 2000000){
            $warning_msg[] = 'Image 03 size is too large!';
         }else{
            move_uploaded_file($image_03_tmp_name, $image_03_folder);
         }
      }else{
         $rename_image_03 = '';
      }

      $image_04 = $_FILES['image_04']['name'];
      $image_04 = filter_var($image_04, FILTER_SANITIZE_STRING);
      $image_04_ext = pathinfo($image_04, PATHINFO_EXTENSION);
      $rename_image_04 = create_unique_id().'.'.$image_04_ext;
      $image_04_tmp_name = $_FILES['image_04']['tmp_name'];
      $image_04_size = $_FILES['image_04']['size'];
      $image_04_folder = 'uploaded_files/'.$rename_image_04;

      if(!empty($image_04)){
         if($image_04_size > 2000000){
            $warning_msg[] = 'Image 04 size is too large!';
         }else{
            move_uploaded_file($image_04_tmp_name, $image_04_folder);
         }
      }else{
         $rename_image_04 = '';
      }

      $image_05 = $_FILES['image_05']['name'];
      $image_05 = filter_var($image_05, FILTER_SANITIZE_STRING);
      $image_05_ext = pathinfo($image_05, PATHINFO_EXTENSION);
      $rename_image_05 = create_unique_id().'.'.$image_05_ext;
      $image_05_tmp_name = $_FILES['image_05']['tmp_name'];
      $image_05_size = $_FILES['image_05']['size'];
      $image_05_folder = 'uploaded_files/'.$rename_image_05;

      if(!empty($image_05)){
         if($image_05_size > 2000000){
            $warning_msg[] = 'Image 05 size is too large!';
         }else{
            move_uploaded_file($image_05_tmp_name, $image_05_folder);
         }
      }else{
         $rename_image_05 = '';
      }

      $image_01 = $_FILES['image_01']['name'];
      $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
      $image_01_ext = pathinfo($image_01, PATHINFO_EXTENSION);
      $rename_image_01 = create_unique_id().'.'.$image_01_ext;
      $image_01_tmp_name = $_FILES['image_01']['tmp_name'];
      $image_01_size = $_FILES['image_01']['size'];
      $image_01_folder = 'uploaded_files/'.$rename_image_01;

      if($image_01_size > 2000000){
         $warning_msg[] = 'Image 01 size too large!';
      }else{
         $insert_property = $conn->prepare("INSERT INTO `property`(id, user_id, property_name, address, price, type, status, offer, furnished, bhk, age, total_floors, gym, play_ground, image_01, image_02, image_03, image_04, image_05, description) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"); 
         $insert_property->execute([$id, $user_id, $property_name, $address, $price, $type, $status, $offer, $furnished, $bhk, $age, $total_floors, $gym, $play_ground, $rename_image_01, $rename_image_02, $rename_image_03, $rename_image_04, $rename_image_05, $description]);
         move_uploaded_file($image_01_tmp_name, $image_01_folder);
         $success_msg[] = 'Property posted successfully!';
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
   <title>Post Property</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="property-form">
   <?php if($user_role === 'seller' || $user_role === 'landlord'): ?>
      <form action="" method="POST" enctype="multipart/form-data">
         <h3>Property Details</h3>
         <div class="box">
            <p>Property name <span>*</span></p>
            <input type="text" name="property_name" required maxlength="50" placeholder="Enter property name" class="input">
         </div>
         <div class="flex">
            <div class="box">
               <p>Property price <span>*</span></p>
               <input type="number" name="price" required min="0" max="9999999999" maxlength="10" placeholder="Enter property price" class="input">
            </div>
            <div class="box">
               <p>Property address <span>*</span></p>
               <input type="text" name="address" required maxlength="100" placeholder="Enter property address" class="input">
            </div>
            <div class="box">
               <p>Offer type</p>
               <select name="offer" class="input" required>
                  <option value="sale">Sale</option>
                  <option value="rent">Rent</option>
               </select>
            </div>
            <div class="box">
               <p>Property type <span>*</span></p>
               <select name="type" required class="input">
                  <option value="apartment">Apartment</option>
                  <option value="condo">Condo</option>
                  <option value="loft">Loft</option>
                  <option value="penthouse">Penthouse</option>
               </select>
            </div>
            <div class="box">
               <p>Property status <span>*</span></p>
               <select name="status" required class="input">
                  <option value="ready to move">Ready to move</option>
                  <option value="under construction">Under construction</option>
               </select>
            </div>
            <div class="box">
               <p>Furnished status <span>*</span></p>
               <select name="furnished" required class="input">
                  <option value="furnished">Furnished</option>
                  <option value="semi-furnished">Semi-furnished</option>
                  <option value="unfurnished">Unfurnished</option>
               </select>
            </div>
            <div class="box">
               <p>How many BHK <span>*</span></p>
               <select name="bhk" required class="input">
                  <option value="1">1 BHK</option>
                  <option value="2">2 BHK</option>
                  <option value="3">3 BHK</option>
                  <option value="4">4 BHK</option>
                  <option value="5">5 BHK</option>
               </select>
            </div>
            <div class="box">
               <p>Property age <span>*</span></p>
               <input type="number" name="age" required min="0" max="99" maxlength="2" placeholder="How old is property?" class="input">
            </div>
            <div class="box">
               <p>Total floors <span>*</span></p>
               <input type="number" name="total_floors" required min="0" max="99" maxlength="2" placeholder="How many floors available?" class="input">
            </div>
         </div>
         <div class="box">
            <p>Property description <span>*</span></p>
            <textarea name="description" maxlength="1000" class="input" required cols="30" rows="10" placeholder="Write about property..."></textarea>
         </div>
         <div class="checkbox">
            <div class="box">
               <p><input type="checkbox" name="play_ground" value="yes" />Play ground</p>
            </div>
            <div class="box">
               <p><input type="checkbox" name="gym" value="yes" />Gym</p>
            </div>
         </div>
         <div class="box">
            <p>Image 01 <span>*</span></p>
            <input type="file" name="image_0