<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP</title>
    <link rel="stylesheet" href="<?php echo base_url().'assets/css/font-awesome.css' ;?>">
    <link rel="stylesheet" href="<?php echo base_url().'assets/css/style.css'; ?>">
</head>
<body>


<div class="forgetPasswordPage flex">
  
        <form class="alldiv">
            <div class="logo">
                <img src="<?php echo base_url().'assets/images/logo.png' ;?>" alt="">
            </div>
            <div class="loginTitle"><span class="text-dark">Forgot Password?</span>Enter your email and we'll send you instructions to reset your password</div>
   
            <input type="email" placeholder="Enter Your Email" class="form_control">
            <button type="submit" class="btn bg-info">Send Rest Link</button>
            <a href="<?= url_to('erp.login') ;?>" class='text-primary'><i class="fa fa-angle-left"></i> Back to login</a>
        </form>
   
</div>



<script src="<?php echo base_url().'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/script.js' ;?>"></script>

</body>
</html>