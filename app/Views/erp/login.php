<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP </title>
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/style.css'; ?>">
</head>

<body>

    <div class="ModalAlert" style="z-index : 900;">
        <p class="textFlow st_success"></p>
        <a type="button" class="HoverA"><i class="fa fa-close"></i></a>
    </div>
    <div class="flex loginpage">
        <div class="loginleft">
            <img src="<?php echo base_url() . 'assets/images/login.png'; ?>" alt="">
        </div>
        <div class="loginRight">
            <h1 class="text-center">ADMIN LOGIN</h1>
            <form class="allDiv" action="<?= url_to('erp.auth') ?>" method="POST">
                <div class="loginTitle textCenter text-success">Welcome to <span
                        class="text-primary"><?= get_company_name() ?></span></div>

                <!-- Flash Data Commented -->



                <!-- End Of Flashdata -->
                <input type="email" placeholder="Email" required name="email" class="form_control">
                <div class="password poR">
                    <input type="password" placeholder="Password" name="password" class="form_control">
                    <a type="button"><i class="fa fa-eye"></i></a>
                </div>
                <div class="flex">
                    <label for="rememberMe"><input type="checkbox" id="rememberMe" name="remember_me" value="1">Remember
                        me</label>
                    <a href="<?= url_to('erp.forgotpassword'); ?>" class="text-primary">Forget Password?</a>
                </div>
                <button type="submit" class="btn bg-info">Login</button>
            </form>
        </div>
    </div>



    <script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
    <script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
    <script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
    <script>
        <?php
        if (session()->getFlashdata("op_success")) { ?>
            let alerts = new ModalAlert();
            alerts.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
            <?php
        } else if (session()->getFlashdata("op_error")) { ?>
                let alert = new ModalAlert();
                alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
            <?php
        }
        ?>
    </script>

</body>

</html>