<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//required files
require 'vendor/autoload.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

include('Menu Bar.php');
include('connection.php');
if($eid=="") {
    header('location:Login.php');
}
$sql= mysqli_query($con,"select * from room_booking_details where email='$eid'"); 
$result=mysqli_fetch_assoc($sql);
extract($_REQUEST);
error_reporting(1);

if(isset($savedata)) {
    $sql= mysqli_query($con,"select * from room_booking_details where email='$email' and room_type='$room_type'");
    if(mysqli_num_rows($sql)) {
        $msg= "<h1 style='color:red'>You have already booked this room</h1>";    
    } else {
      $sql = "INSERT INTO room_booking_details(name,email,phone,address,city,state,zip,room_type,Occupancy,check_in_date,check_in_time,check_out_date) 
      VALUES('$name','$email','$phone','$address','$city','$state','$zip','$room_type','$Occupancy','$cdate','$ctime','$codate')";
if(mysqli_query($con,$sql)) {
            sendConfirmationEmail($name, $email, $room_type, $cdate, $codate);
            $msg= "<h1 style='color:blue'>You have Successfully booked this room</h1><h2><a href='order.php'>View </a></h2>"; 
        }
    }
}

function sendConfirmationEmail($name, $email, $room_type, $cdate, $codate) {
    $mail = new PHPMailer(true);
    try {

  // Server settings
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username   = 'automail424@gmail.com'; // SMTP email
  $mail->Password   = 'rrwhyejaffmcvopo';          // SMTP password
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;
  
        $mail->setFrom('your_email@example.com', 'Hotel Booking');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Booking Confirmation - Hotel';
        $mail->Body = "<h2>Booking Confirmation</h2>
                      <p><strong>Name:</strong> $name</p>
                      <p><strong>Email:</strong> $email</p>
                      <p><strong>Room Type:</strong> $room_type</p>
                      <p><strong>Check-in Date:</strong> $cdate</p>
                      <p><strong>Check-out Date:</strong> $codate</p>
                      <p>Thank you for booking with us!</p>";

        $mail->send();
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Online Hotel.com</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="css/style.css" rel="stylesheet"/>
</head>
<body style="margin-top:50px;">
  <?php include('Menu Bar.php'); ?>
  <div class="container-fluid text-center" id="primary">
    <h1>[ BOOKING Form ]</h1><br>
    <div class="container">
      <div class="row">
        <?php echo @$msg; ?>
        <form class="form-horizontal" method="post">
          <div class="col-sm-6">
            <div class="form-group">
              <label>Name:</label>
              <input type="text" class="form-control" name="name" value="<?php echo $result['name']; ?>" required>
            </div>
            <div class="form-group">
              <label>Email:</label>
              <input type="email" class="form-control" name="email" value="<?php echo $result['email']; ?>" required>
            </div>
            <div class="form-group">
              <label>Phone:</label>
              <input type="text" class="form-control" name="phone" value="<?php echo $result['mobile']; ?>" required>
            </div>
            <div class="form-group">
              <label>Address:</label>
              <textarea name="address" class="form-control" required><?php echo $result['address']; ?></textarea>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Room Type:</label>
              <select class="form-control" name="room_type" required>
                <option>Deluxe Room</option>
                <option>Luxurious Suite</option>
                <option>Standard Room</option>
                <option>Suite Room</option>
                <option>Twin Deluxe Room</option>
              </select>
            </div>
            <div class="form-group">
              <label>Check-in Date:</label>
              <input type="date" name="cdate" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Check-out Date:</label>
              <input type="date" name="codate" class="form-control" required>
            </div>
            <input type="submit" value="Book Now" name="savedata" class="btn btn-primary">
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php include('Footer.php'); ?>
</body>
</html>
