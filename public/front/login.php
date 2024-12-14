<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP DOCUMENT</title>
    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>


<div class="flex loginpage">
    <div class="loginleft">
        <img src="images/login.png" alt="">
    </div>
    <div class="loginRight">
        <form class="alldiv">
            <div class="loginTitle textCenter text-success">Welcome to <span class="text-primary">Qbrainstorm</span></div>
            <div class="alert st_violet">password is wrong</div>
            <input type="text" placeholder="UserName" class="form_control">
             <div class="password poR">
                <input type="password" placeholder="Password" class="form_control">
                <a type="button"><i class="fa fa-eye"></i></a> 
             </div>
            <div class="flex">
                <label for="rememberMe"><input type="checkbox" id="rememberMe" name="">Remember me</label>
                <a href="forgetPassword.php" class="text-primary">Forget Password?</a>
            </div>
            <button type="submit" class="btn bg-info">Login</button>
        </form>
    </div>
</div>



<script src="js/jquery.min.js"></script>
<script src="js/script.js"></script>

</body>
</html>