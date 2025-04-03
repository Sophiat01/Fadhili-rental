<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   header('location:login.php');
   exit();
}

if(isset($_POST['initiate_transaction'])){
    $property_id = filter_var($_POST['property_id'], FILTER_SANITIZE_STRING);
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_STRING);
    $payment_method = filter_var($_POST['payment_method'], FILTER_SANITIZE_STRING);
    
    $select_property = $conn->prepare("SELECT * FROM `property` WHERE id = ?");
    $select_property->execute([$property_id]);
    $property = $select_property->fetch(PDO::FETCH_ASSOC);
    
    if($property){
        $transaction_id = create_unique_id();
        $insert_transaction = $conn->prepare("INSERT INTO `transactions`(id, buyer_id, seller_id, property_id, amount, payment_method) VALUES(?,?,?,?,?,?)");
        $insert_transaction->execute([$transaction_id, $user_id, $property['user_id'], $property_id, $amount, $payment_method]);
        
        $success_msg[] = 'Transaction initiated successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Transaction</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'components/user_header.php'; ?>

<section class="transaction">
   <h1 class="heading">Initiate Transaction</h1>
   
   <?php
   if(isset($_GET['property_id'])){
      $property_id = $_GET['property_id'];
      $select_property = $conn->prepare("SELECT * FROM `property` WHERE id = ?");
      $select_property->execute([$property_id]);
      $property = $select_property->fetch(PDO::FETCH_ASSOC);
   ?>
   <form action="" method="POST" class="box">
      <input type="hidden" name="property_id" value="<?= $property_id ?>">
      <div class="flex">
         <div class="box">
            <p>Property Name: <span><?= $property['property_name'] ?></span></p>
            <p>Price: <span><?= $property['price'] ?></span></p>
            <p>Address: <span><?= $property['address'] ?></span></p>
         </div>
         <div class="box">
            <p>Amount <span>*</span></p>
            <input type="number" name="amount" required class="input" value="<?= $property['price'] ?>">
            <p>Payment Method <span>*</span></p>
            <select name="payment_method" class="input" required>
               <option value="credit_card">Credit Card</option>
               <option value="bank_transfer">Bank Transfer</option>
               <option value="mobile_payment">Mobile Payment</option>
            </select>
         </div>
      </div>
      <input type="submit" value="Initiate Transaction" name="initiate_transaction" class="btn">
   </form>
   <?php } ?>
</section>

<section class="transaction-history">
   <h1 class="heading">Transaction History</h1>
   <div class="box-container">
   <?php
      $select_transactions = $conn->prepare("SELECT t.*, p.property_name FROM `transactions` t 
                                             JOIN `property` p ON t.property_id = p.id 
                                             WHERE t.buyer_id = ? OR t.seller_id = ?");
      $select_transactions->execute([$user_id, $user_id]);
      if($select_transactions->rowCount() > 0){
         while($fetch_transaction = $select_transactions->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Property: <span><?= $fetch_transaction['property_name'] ?></span></p>
      <p>Amount: <span><?= $fetch_transaction['amount'] ?></span></p>
      <p>Method: <span><?= $fetch_transaction['payment_method'] ?></span></p>
      <p>Status: <span><?= $fetch_transaction['status'] ?></span></p>
      <p>Date: <span><?= $fetch_transaction['transaction_date'] ?></span></p>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No transactions yet!</p>';
      }
   ?>
   </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
<?php include 'components/message.php'; ?>
</body>
</html>