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
    
    <nav class="sidebar poF">
        <div class="flex">
            <div class="logo">
                <img src="images/logo.png" alt="qbarinstrom" class="img1 ">
                <img src="images/favicon.png" alt="qbarinstrom" class="img2 ">
            </div>
            <a type="button" class="closeIcon2 HoverA"><i class="fa fa-close"></i></a>
        </div>
        <ul class="navbar">
            <li><a href="index.php" class="navLink active"><i class="fa fa-dashboard"></i> <span>Dashboard</span> </a></li>
            <li class="navdown"><a type="button" class="navdownBtn dropdownToggle"><i class="fa fa-gear"></i> <span>CRM</span></a>
                <div class="navdown_container">
                    <ul>
                        <li><a href="buttons.php" class="navLink"><i class="fa fa-circle-o"></i> Supplier</a></li>
                        <li><a href="dialog.php" class="navLink"><i class="fa fa-circle-o"></i> Procurement</a></li>
                        <li><a href="tab.php" class="navLink"><i class="fa fa-circle-o"></i> Quality</a></li>
                        <li><a href="login.php" class="navLink"><i class="fa fa-circle-o"></i> Login</a></li>
                        <li><a href="forgetPassword.php" class="navLink"><i class="fa fa-circle-o"></i> Forget Password</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="#" class="navLink"><i class="fa fa-money"></i> <span>Sales</span></a></li>
            <li><a href="#" class="navLink"><i class="fa fa-handshake-o"></i> <span>Finance</span></a></li>
            <li><a href="#" class="navLink"><i class="fa fa-signal"></i> <span>Inventory</span></a></li>
            <li><a href="#" class="navLink"><i class="fa fa-home"></i> <span>Warehouse</span></a></li>
            <li><a href="#" class="navLink"><i class="fa fa-id-badge"></i> <span>Supplier</span></a></li>
            <li><a href="#" class="navLink"><i class="fa fa-file-text-o"></i> <span>Procurement</span></a></li>
            <li><a href="#" class="navLink"><i class="fa fa-truck"></i> <span>Transportation</span></a></li>
            <li><a href="#" class="navLink"><i class="fa fa-chain"></i> <span>supply chain</span></a></li>
            <li><a href="#" class="navLink"><i class="fa fa-fire"></i> <span>Production</span></a></li>
            <li><a href="#" class="navLink"><i class="fa fa-balance-scale"></i> <span>Quality Managament</span></a></li>
            <li><a href="#" class="navLink"><i class="fa fa-heart"></i> <span>Service Managament</span></a></li>
            <li><a href="#" class="navLink"><i class="fa fa-users"></i> <span>Asset Managament</span></a></li>
            <li><a href="#" class="navLink"><i class="fa fa-street-view"></i> <span>Human resources</span></a></li>
        </ul>
    </nav>

    <main class="main ">
        <div class="topNav flex">
            <ul class="topNavLeft flex">
                <li><a type="button" class="closeIcon HoverA"><i class="fa fa-circle-o"></i> </a></li>
                <li class="search">
                    <a type="button" class="searchBtn HoverA"><i class="fa fa-search"></i></a>
                    <div class="search_container poA bg-shadow">
                        <div class="searchBox bg-white poA flex">
                            <p><i class="fa fa-search"></i></p>
                            <input type="text" class="form_control" placeholder="Search Anything">
                            <a type="button" class="closeBtn"><i class="fa fa-close"></i></a>
                        </div>
                    </div>
                </li>
            </ul>  
            <div class="topNavRight">
                <ul class="flex">
                    <li><a type="button" class="HoverA fullscreen"><i class="fa fa-arrows-alt"></i></a></li>
                    <li class="dropdown"><a type="button" class="dropBtn HoverA"><i class="fa fa-envelope-o"></i> <span class="bg-warning br50"></span></a>
                        <div class="dropdown_container">
                            <ul class="messageDropdown">
                                <li><h3>Message <i class="text-danger fa fa-envelope-o"></i></h3></li>
                                <li>
                                    <a href="#">
                                        <h4 class="textFlow">Mark send you a message</h4>
                                        <p>1 Minutes ago</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <h4 class="textFlow">Mark send you a message</h4>
                                        <p>1 Minutes ago</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <h4 class="textFlow">Mark send you a message</h4>
                                        <p>1 Minutes ago</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="textCenter text-success">4 New Message</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="dropdown"><a type="button" class="dropBtn HoverA "><i class="fa fa-bell-o"></i> <span class="bg-danger br50"></span></a>
                        <div class="dropdown_container">
                            <ul class="messageDropdown">
                                <li>
                                    <h4>Notification <i class="text-success fa fa-bell-o"></i></h4>
                                </li>
                                <li>
                                    <a href="#">
                                        <h4 class="textFlow">Mark send you a message</h4>
                                        <p>1 Minutes ago</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <h4 class="textFlow">Mark send you a message</h4>
                                        <p>1 Minutes ago</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <h4 class="textFlow">Mark send you a message</h4>
                                        <p>1 Minutes ago</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="textCenter text-danger">see all notification</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="navProfile dropdown">
                        <a type="button" class="dropBtn"><img src="images/admin.png" alt="admin" class="img_cover br50"> <span class="bg-success br50"></span></a>
                        <div class="dropdown_container">
                            <div class="profileHead flex">
                                <div class="profileHeadLeft">
                                    <img src="images/admin.png" alt="admin" class="img_cover br50"> <span class="bg-success br50"></span>
                                </div>
                                <div class="profileHeadRight">
                                    <h4 class="textFlow">John Doe</h4>
                                    <p class="textFlow">Admin</p>
                                </div>
                            </div>
                            <ul class="profileHeadul">
                                <li><a href="#"><i class="fa fa-user-o"></i> profile</a></li>
                                <li><a href="#"><i class="fa fa-envelope-o"></i> Mail</a></li>
                                <li><a href="#"><i class="fa fa-comment-o"></i> Chat  <div class="NotificationCount bg-danger">2</div></a></li>
                                <li><a href="#"><i class="fa fa-user-o"></i> profile</a></li>
                                <li><a href="#"><i class="fa fa-envelope-o"></i> Mail</a></li>
                                <li><a href="#"><i class="fa fa-comment-o"></i> Chat</a></li>
                                <li><a href="#"><i class="fa fa-lock"></i> Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>