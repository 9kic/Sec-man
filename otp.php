<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>OTP Verification Form</title>
    <link rel="stylesheet" href="otpcss.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  </head>
  <body>
    <div class="container">
      <header>
        <i class="bx bxs-check-shield"></i>
      </header>
      <h4>Enter OTP Code</h4>
      <form method="post" action="chackotp.php">
        <div class="input-field">
          <input type="number" name="otpcode" />
          
        </div>
        <button>تسجيل دخول</button>
      </form>
    </div>


<script>
   <?php
      if (isset($error_message) && !empty($error_message)) {
          echo "alert('$error_message');";
      }
      ?>
</script>

    


  </body>
</html>