<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="images/favicon.svg" type="image/x-icon" />
    <title><?= $title ?? 'ERP CLIENT' ?></title>
    <script src="<?= base_url() . 'assets/js/jquery.min.js' ?>"></script>
    <!-- ========== All CSS files linkup ========= -->
    <link rel="stylesheet" href="<?= base_url() . 'front-assets/' ?>css/bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link rel="stylesheet" href="<?= base_url() . 'front-assets/' ?>css/materialdesignicons.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="<?= base_url() . 'front-assets/' ?>css/fullcalendar.css" />
    <link rel="stylesheet" href="<?= base_url() . 'front-assets/' ?>css/fullcalendar.css" />
    <link rel="stylesheet" href="<?= base_url() . 'front-assets/' ?>css/main.css" />
    <link rel="stylesheet" href="<?= base_url() . 'front-assets/' ?>css/style.css" />
    <link rel="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
    <link href="https://cdn.datatables.net/v/bs4/dt-2.1.8/af-2.7.0/b-3.2.0/b-colvis-3.2.0/b-print-3.2.0/cr-2.0.4/date-1.5.4/fc-5.0.4/fh-4.0.1/kt-2.12.1/r-3.0.3/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.1/sp-2.3.3/sl-2.1.0/sr-1.4.1/datatables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
<style>
    .logo {
        width: 100%;
    }
</style>

<body>
    <?php $client_details = get_client_name() ?>
    <!-- ======== Preloader =========== -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>

    <!-- Udhaya kumar both success and error -->
    <div class="Alert" style="z-index: 999;">
        <p class="alert_messsage">This is a Success message !</p>
        <a class="close_alert"><i class="fa-solid fa-xmark fa-sm" style="color: #ffffff;"></i></a>
    </div>

    <!-- ======== Preloader =========== -->

    <!-- ======== sidebar-nav start =========== -->
    <aside class="sidebar-nav-wrapper">
        <div class="navbar-logo">
            <a href="index.html">
                <img src="<?= get_logo_url() ?>" alt="logo" class="logo" />
            </a>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li class="nav-item">
                    <a href="<?= url_to('front.dashboard') ?>" >
                        <span class="icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.74999 18.3333C12.2376 18.3333 15.1364 15.8128 15.7244 12.4941C15.8448 11.8143 15.2737 11.25 14.5833 11.25H9.99999C9.30966 11.25 8.74999 10.6903 8.74999 10V5.41666C8.74999 4.7263 8.18563 4.15512 7.50586 4.27556C4.18711 4.86357 1.66666 7.76243 1.66666 11.25C1.66666 15.162 4.83797 18.3333 8.74999 18.3333Z" />
                                <path
                                    d="M17.0833 10C17.7737 10 18.3432 9.43708 18.2408 8.75433C17.7005 5.14918 14.8508 2.29947 11.2457 1.75912C10.5629 1.6568 10 2.2263 10 2.91665V9.16666C10 9.62691 10.3731 10 10.8333 10H17.0833Z" />
                            </svg>
                        </span>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url_to('front.Knowledgebase.view') ?>">
                        <span class="icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.33334 3.35442C3.33334 2.4223 4.07954 1.66666 5.00001 1.66666H15C15.9205 1.66666 16.6667 2.4223 16.6667 3.35442V16.8565C16.6667 17.5519 15.8827 17.9489 15.3333 17.5317L13.8333 16.3924C13.537 16.1673 13.1297 16.1673 12.8333 16.3924L10.5 18.1646C10.2037 18.3896 9.79634 18.3896 9.50001 18.1646L7.16668 16.3924C6.87038 16.1673 6.46298 16.1673 6.16668 16.3924L4.66668 17.5317C4.11731 17.9489 3.33334 17.5519 3.33334 16.8565V3.35442ZM4.79168 5.04218C4.79168 5.39173 5.0715 5.6751 5.41668 5.6751H10C10.3452 5.6751 10.625 5.39173 10.625 5.04218C10.625 4.69264 10.3452 4.40927 10 4.40927H5.41668C5.0715 4.40927 4.79168 4.69264 4.79168 5.04218ZM5.41668 7.7848C5.0715 7.7848 4.79168 8.06817 4.79168 8.41774C4.79168 8.76724 5.0715 9.05066 5.41668 9.05066H10C10.3452 9.05066 10.625 8.76724 10.625 8.41774C10.625 8.06817 10.3452 7.7848 10 7.7848H5.41668ZM4.79168 11.7932C4.79168 12.1428 5.0715 12.4262 5.41668 12.4262H10C10.3452 12.4262 10.625 12.1428 10.625 11.7932C10.625 11.4437 10.3452 11.1603 10 11.1603H5.41668C5.0715 11.1603 4.79168 11.4437 4.79168 11.7932ZM13.3333 4.40927C12.9882 4.40927 12.7083 4.69264 12.7083 5.04218C12.7083 5.39173 12.9882 5.6751 13.3333 5.6751H14.5833C14.9285 5.6751 15.2083 5.39173 15.2083 5.04218C15.2083 4.69264 14.9285 4.40927 14.5833 4.40927H13.3333ZM12.7083 8.41774C12.7083 8.76724 12.9882 9.05066 13.3333 9.05066H14.5833C14.9285 9.05066 15.2083 8.76724 15.2083 8.41774C15.2083 8.06817 14.9285 7.7848 14.5833 7.7848H13.3333C12.9882 7.7848 12.7083 8.06817 12.7083 8.41774ZM13.3333 11.1603C12.9882 11.1603 12.7083 11.4437 12.7083 11.7932C12.7083 12.1428 12.9882 12.4262 13.3333 12.4262H14.5833C14.9285 12.4262 15.2083 12.1428 15.2083 11.7932C15.2083 11.4437 14.9285 11.1603 14.5833 11.1603H13.3333Z" />
                            </svg>
                        </span>
                        <span class="text">Knowledge Base</span>
                    </a>
                </li>
                <?php if (has_client_permission("Projects")): ?>
                    <li class="nav-item">
                        <a href="<?= url_to('project.index') ?>">
                            <span class="icon">
                                <i class="fa fa-tasks"></i>
                            </span>
                            <span class="text">Project</span>
                        </a>
                    </li>
                <?php endif ?>
                <?php if (has_client_permission("Invoices")): ?>
                    <li class="nav-item">
                        <a href="<?= url_to('invoice.index') ?>">
                            <span class="icon">
                                <i class="fa fa-shopping-cart"></i>
                            </span>
                            <span class="text">Invoice</span>
                        </a>
                    </li>
                <?php endif ?>
                <?php if (has_client_permission("Contracts")): ?>
                    <li class="nav-item">
                        <a href="<?= url_to('contract.index'); ?>">
                            <span class="icon">
                                <i class="fa-solid fa-file-contract"></i>
                            </span>
                            <span class="text">Contract</span>
                        </a>
                    </li>
                <?php endif ?>
                <?php if (has_client_permission("Estimates")): ?>
                    <li class="nav-item">
                        <a href="<?= url_to('estimate.index') ?>">
                            <span class="icon">
                                <i class="fa fa-line-chart"></i>
                            </span>
                            <span class="text">Estimate</span>
                        </a>
                    </li>
                <?php endif ?>
                <?php if (has_client_permission("Quotations")): ?>
                    <li class="nav-item">
                        <a href="#">
                            <span class="icon">
                                <i class="fa-solid fa-receipt"></i>
                            </span>
                            <span class="text">Quotation</span>
                        </a>
                    </li>
                <?php endif ?>
                <span class="divider">
                    <hr />
                </span>
                <?php if (has_client_permission("Supports")): ?>
                    <li class="nav-item">
                        <a href="<?= url_To("front.supports.view") ?>" >
                            <span class="icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3.33334 3.35442C3.33334 2.4223 4.07954 1.66666 5.00001 1.66666H15C15.9205 1.66666 16.6667 2.4223 16.6667 3.35442V16.8565C16.6667 17.5519 15.8827 17.9489 15.3333 17.5317L13.8333 16.3924C13.537 16.1673 13.1297 16.1673 12.8333 16.3924L10.5 18.1646C10.2037 18.3896 9.79634 18.3896 9.50001 18.1646L7.16668 16.3924C6.87038 16.1673 6.46298 16.1673 6.16668 16.3924L4.66668 17.5317C4.11731 17.9489 3.33334 17.5519 3.33334 16.8565V3.35442ZM4.79168 5.04218C4.79168 5.39173 5.0715 5.6751 5.41668 5.6751H10C10.3452 5.6751 10.625 5.39173 10.625 5.04218C10.625 4.69264 10.3452 4.40927 10 4.40927H5.41668C5.0715 4.40927 4.79168 4.69264 4.79168 5.04218ZM5.41668 7.7848C5.0715 7.7848 4.79168 8.06817 4.79168 8.41774C4.79168 8.76724 5.0715 9.05066 5.41668 9.05066H10C10.3452 9.05066 10.625 8.76724 10.625 8.41774C10.625 8.06817 10.3452 7.7848 10 7.7848H5.41668ZM4.79168 11.7932C4.79168 12.1428 5.0715 12.4262 5.41668 12.4262H10C10.3452 12.4262 10.625 12.1428 10.625 11.7932C10.625 11.4437 10.3452 11.1603 10 11.1603H5.41668C5.0715 11.1603 4.79168 11.4437 4.79168 11.7932ZM13.3333 4.40927C12.9882 4.40927 12.7083 4.69264 12.7083 5.04218C12.7083 5.39173 12.9882 5.6751 13.3333 5.6751H14.5833C14.9285 5.6751 15.2083 5.39173 15.2083 5.04218C15.2083 4.69264 14.9285 4.40927 14.5833 4.40927H13.3333ZM12.7083 8.41774C12.7083 8.76724 12.9882 9.05066 13.3333 9.05066H14.5833C14.9285 9.05066 15.2083 8.76724 15.2083 8.41774C15.2083 8.06817 14.9285 7.7848 14.5833 7.7848H13.3333C12.9882 7.7848 12.7083 8.06817 12.7083 8.41774ZM13.3333 11.1603C12.9882 11.1603 12.7083 11.4437 12.7083 11.7932C12.7083 12.1428 12.9882 12.4262 13.3333 12.4262H14.5833C14.9285 12.4262 15.2083 12.1428 15.2083 11.7932C15.2083 11.4437 14.9285 11.1603 14.5833 11.1603H13.3333Z" />
                                </svg>
                            </span>
                            <span class="text">Support</span>
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        </nav>
    </aside>
    <div class="overlay"></div>
    <!-- ======== sidebar-nav end =========== -->

    <!-- ======== main-wrapper start =========== -->
    <main class="main-wrapper">
        <!-- ========== header start ========== -->
        <header class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-6">
                        <div class="header-left d-flex align-items-center">
                            <div class="menu-toggle-btn mr-15">
                                <button id="menu-toggle" class="main-btn primary-btn btn-hover">
                                    <i class="fa-solid fa-chevron-left me2"></i> Menu
                                </button>
                            </div>
                            <div class="header-search d-none d-md-flex">
                                <!-- <form action="#">
                                    <input type="text" placeholder="Search..." />
                                    <button><i class="lni lni-search-alt"></i></button>
                                </form> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-6">
                        <div class="header-right">
                            <!-- notification start -->
                            <div class="notification-box ml-15 d-none d-md-flex">
                                <button class="dropdown-toggle" type="button" id="notification"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11 20.1667C9.88317 20.1667 8.88718 19.63 8.23901 18.7917H13.761C13.113 19.63 12.1169 20.1667 11 20.1667Z"
                                            fill="" />
                                        <path
                                            d="M10.1157 2.74999C10.1157 2.24374 10.5117 1.83333 11 1.83333C11.4883 1.83333 11.8842 2.24374 11.8842 2.74999V2.82604C14.3932 3.26245 16.3051 5.52474 16.3051 8.24999V14.287C16.3051 14.5301 16.3982 14.7633 16.564 14.9352L18.2029 16.6342C18.4814 16.9229 18.2842 17.4167 17.8903 17.4167H4.10961C3.71574 17.4167 3.5185 16.9229 3.797 16.6342L5.43589 14.9352C5.6017 14.7633 5.69485 14.5301 5.69485 14.287V8.24999C5.69485 5.52474 7.60672 3.26245 10.1157 2.82604V2.74999Z"
                                            fill="" />
                                    </svg>
                                    <span></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notification">
                                    <li>
                                        <a href="#0">
                                            <div class="image">
                                                <img src="#" alt="" />
                                            </div>
                                            <div class="content">
                                                <h6>
                                                    John Doe
                                                    <span class="text-regular">
                                                        comment on a product.
                                                    </span>
                                                </h6>
                                                <p>
                                                    Lorem ipsum dolor sit amet, consect etur adipiscing
                                                    elit Vivamus tortor.
                                                </p>
                                                <span>10 mins ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            <div class="image">
                                                <img src="#" alt="" />
                                            </div>
                                            <div class="content">
                                                <h6>
                                                    Jonathon
                                                    <span class="text-regular">
                                                        like on a product.
                                                    </span>
                                                </h6>
                                                <p>
                                                    Lorem ipsum dolor sit amet, consect etur adipiscing
                                                    elit Vivamus tortor.
                                                </p>
                                                <span>10 mins ago</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- notification end -->
                            <!-- message start -->
                            <!-- <div class="header-message-box ml-15 d-none d-md-flex">
                                <button class="dropdown-toggle" type="button" id="message" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.74866 5.97421C7.91444 5.96367 8.08162 5.95833 8.25005 5.95833C12.5532 5.95833 16.0417 9.4468 16.0417 13.75C16.0417 13.9184 16.0364 14.0856 16.0259 14.2514C16.3246 14.138 16.6127 14.003 16.8883 13.8482L19.2306 14.629C19.7858 14.8141 20.3141 14.2858 20.129 13.7306L19.3482 11.3882C19.8694 10.4604 20.1667 9.38996 20.1667 8.25C20.1667 4.70617 17.2939 1.83333 13.75 1.83333C11.0077 1.83333 8.66702 3.55376 7.74866 5.97421Z"
                                            fill="" />
                                        <path
                                            d="M14.6667 13.75C14.6667 17.2938 11.7939 20.1667 8.25004 20.1667C7.11011 20.1667 6.03962 19.8694 5.11182 19.3482L2.76946 20.129C2.21421 20.3141 1.68597 19.7858 1.87105 19.2306L2.65184 16.8882C2.13062 15.9604 1.83338 14.89 1.83338 13.75C1.83338 10.2062 4.70622 7.33333 8.25004 7.33333C11.7939 7.33333 14.6667 10.2062 14.6667 13.75ZM5.95838 13.75C5.95838 13.2437 5.54797 12.8333 5.04171 12.8333C4.53545 12.8333 4.12504 13.2437 4.12504 13.75C4.12504 14.2563 4.53545 14.6667 5.04171 14.6667C5.54797 14.6667 5.95838 14.2563 5.95838 13.75ZM9.16671 13.75C9.16671 13.2437 8.7563 12.8333 8.25004 12.8333C7.74379 12.8333 7.33338 13.2437 7.33338 13.75C7.33338 14.2563 7.74379 14.6667 8.25004 14.6667C8.7563 14.6667 9.16671 14.2563 9.16671 13.75ZM11.4584 14.6667C11.9647 14.6667 12.375 14.2563 12.375 13.75C12.375 13.2437 11.9647 12.8333 11.4584 12.8333C10.9521 12.8333 10.5417 13.2437 10.5417 13.75C10.5417 14.2563 10.9521 14.6667 11.4584 14.6667Z"
                                            fill="" />
                                    </svg>
                                    <span></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="message">
                                    <li>
                                        <a href="#0">
                                            <div class="image">
                                                <img src="assets/images/lead/lead-5.png" alt="" />
                                            </div>
                                            <div class="content">
                                                <h6>Jacob Jones</h6>
                                                <p>Hey!I can across your profile and ...</p>
                                                <span>10 mins ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            <div class="image">
                                                <img src="assets/images/lead/lead-3.png" alt="" />
                                            </div>
                                            <div class="content">
                                                <h6>John Doe</h6>
                                                <p>Would you mind please checking out</p>
                                                <span>12 mins ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            <div class="image">
                                                <img src="assets/images/lead/lead-2.png" alt="" />
                                            </div>
                                            <div class="content">
                                                <h6>Anee Lee</h6>
                                                <p>Hey! are you available for freelance?</p>
                                                <span>1h ago</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div> -->
                            <!-- message end -->
                            <!-- profile start -->
                            <div class="profile-box ml-15">
                                <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="profile-info">
                                        <div class="info">
                                            <div class="image">
                                                <img src="<?= get_client_profile_url() ?>" alt="image" />
                                            </div>
                                            <div>
                                                <h6 class="fw-500">
                                                    <?= isset($client_details) ? ucwords(strtolower($client_details['firstname'])) . " " . ucwords(strtolower($client_details['lastname'])) : "Client" ?>
                                                </h6>
                                                <p><?= $client_details['position'] ?? "Client" ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile">
                                    <li>
                                        <div class="author-info flex items-center !p-1">
                                            <div class="image">
                                                <img src="<?= get_client_profile_url() ?>" alt="image">
                                            </div>
                                            <div class="content">
                                                <h4 class="text-sm">
                                                    <?= isset($client_details) ? ucwords(strtolower($client_details['firstname'])) . " " . ucwords(strtolower($client_details['lastname'])) : "Client" ?>
                                                </h4>
                                                <!-- <a class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white text-xs"
                                                    href="#"><? //= $client_details->email ?></a> -->
                                            </div>
                                        </div>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?= url_to('front.profile', $client_details['contact_id']) ?>">
                                            <i class="lni lni-user"></i> View Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0">
                                            <i class="lni lni-alarm"></i> Notifications
                                        </a>
                                    </li>
                                    <!-- <li>
                                        <a href="#0"> <i class="lni lni-inbox"></i> Messages </a>
                                    </li> -->
                                    <!-- <li>
                                        <a href="#0"> <i class="lni lni-cog"></i> Settings </a>
                                    </li> -->
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?= url_to('front.log.out') ?>"> <i class="lni lni-exit"></i> Sign Out
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- profile end -->
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- <div id="tooltip" class="custom-tooltip" style="display: none;">Click to view a ticket</div> -->
        <!-- ========== header end ========== -->

        <!-- ========== section start ========== -->
        <section class="section">
            <div class="container-fluid">
                <?= view('client/' . $pageFolder); ?>
            </div>
            <!-- end container -->
        </section>
        <!-- ========== section end ========== -->

        <!-- ========== footer start =========== -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 order-last order-md-first">
                        <div class="copyright text-center text-md-start">
                            <p class="text-sm">
                                Designed and Developed by
                                <a href="https://qbrainstorm.com/" rel="nofollow" target="_blank">
                                    Q Brainstorm Software
                                </a>
                            </p>
                        </div>
                    </div>
                    <!-- end col-->
                    <div class="col-md-6">
                        <div class="terms d-flex justify-content-center justify-content-md-end">
                            <a href="#" class="text-sm">Term & Conditions</a>
                            <a href="#" class="text-sm ml-15">Privacy & Policy</a>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </footer>
        <!-- ========== footer end =========== -->
    </main>
    <!-- ======== main-wrapper end =========== -->

    <!-- ========= All Javascript files linkup ======== -->

    <script src="<?= base_url() . 'front-assets/' ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() . 'front-assets/' ?>js/Chart.min.js"></script>
    <script src="<?= base_url() . 'front-assets/' ?>js/dynamic-pie-chart.js"></script>
    <script src="<?= base_url() . 'front-assets/' ?>js/moment.min.js"></script>
    <script src="<?= base_url() . 'front-assets/' ?>js/fullcalendar.js"></script>
    <script src="<?= base_url() . 'front-assets/' ?>js/jvectormap.min.js"></script>
    <script src="<?= base_url() . 'front-assets/' ?>js/world-merc.js"></script>
    <script src="<?= base_url() . 'front-assets/' ?>js/polyfill.js"></script>
    <script src="<?= base_url() . 'front-assets/' ?>js/main.js"></script>
    <script src="<?= base_url() . 'front-assets/' ?>js/custom.js"></script>


    <script>
        if (document.getElementById("#map")) {


            // ======== jvectormap activation
            var markers = [
                { name: "Egypt", coords: [26.8206, 30.8025] },
                { name: "Russia", coords: [61.524, 105.3188] },
                { name: "Canada", coords: [56.1304, -106.3468] },
                { name: "Greenland", coords: [71.7069, -42.6043] },
                { name: "Brazil", coords: [-14.235, -51.9253] },
            ];

            var jvm = new jsVectorMap({
                map: "world_merc",
                selector: "#map",
                zoomButtons: true,

                regionStyle: {
                    initial: {
                        fill: "#d1d5db",
                    },
                },

                labels: {
                    markers: {
                        render: (marker) => marker.name,
                    },
                },

                markersSelectable: true,
                selectedMarkers: markers.map((marker, index) => {
                    var name = marker.name;

                    if (name === "Russia" || name === "Brazil") {
                        return index;
                    }
                }),
                markers: markers,
                markerStyle: {
                    initial: { fill: "#4A6CF7" },
                    selected: { fill: "#ff5050" },
                },
                markerLabelStyle: {
                    initial: {
                        fontWeight: 400,
                        fontSize: 14,
                    },
                },
            });
            // ====== calendar activation
        }
        document.addEventListener("DOMContentLoaded", function () {
            if (document.getElementById("calendar-mini")) {

                var calendarMiniEl = document.getElementById("calendar-mini");
                var calendarMini = new FullCalendar.Calendar(calendarMiniEl, {
                    initialView: "dayGridMonth",
                    headerToolbar: {
                        end: "today prev,next",
                    },
                });
                calendarMini.render();
            }


            // =========== chart one start
            if (document.getElementById("Chart1")) {
                const ctx1 = document.getElementById("Chart1").getContext("2d");
                const chart1 = new Chart(ctx1, {
                    type: "line",
                    data: {
                        labels: [
                            "Jan",
                            "Fab",
                            "Mar",
                            "Apr",
                            "May",
                            "Jun",
                            "Jul",
                            "Aug",
                            "Sep",
                            "Oct",
                            "Nov",
                            "Dec",
                        ],
                        datasets: [
                            {
                                label: "",
                                backgroundColor: "transparent",
                                borderColor: "#365CF5",
                                data: [
                                    600, 800, 750, 880, 940, 880, 900, 770, 920, 890, 976, 1100,
                                ],
                                pointBackgroundColor: "transparent",
                                pointHoverBackgroundColor: "#365CF5",
                                pointBorderColor: "transparent",
                                pointHoverBorderColor: "#fff",
                                pointHoverBorderWidth: 5,
                                borderWidth: 5,
                                pointRadius: 8,
                                pointHoverRadius: 8,
                                cubicInterpolationMode: "monotone", // Add this line for curved line
                            },
                        ],
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    labelColor: function (context) {
                                        return {
                                            backgroundColor: "#ffffff",
                                            color: "#171717"
                                        };
                                    },
                                },
                                intersect: false,
                                backgroundColor: "#f9f9f9",
                                title: {
                                    fontFamily: "Plus Jakarta Sans",
                                    color: "#8F92A1",
                                    fontSize: 12,
                                },
                                body: {
                                    fontFamily: "Plus Jakarta Sans",
                                    color: "#171717",
                                    fontStyle: "bold",
                                    fontSize: 16,
                                },
                                multiKeyBackground: "transparent",
                                displayColors: false,
                                padding: {
                                    x: 30,
                                    y: 10,
                                },
                                bodyAlign: "center",
                                titleAlign: "center",
                                titleColor: "#8F92A1",
                                bodyColor: "#171717",
                                bodyFont: {
                                    family: "Plus Jakarta Sans",
                                    size: "16",
                                    weight: "bold",
                                },
                            },
                            legend: {
                                display: false,
                            },
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        title: {
                            display: false,
                        },
                        scales: {
                            y: {
                                grid: {
                                    display: false,
                                    drawTicks: false,
                                    drawBorder: false,
                                },
                                ticks: {
                                    padding: 35,
                                    max: 1200,
                                    min: 500,
                                },
                            },
                            x: {
                                grid: {
                                    drawBorder: false,
                                    color: "rgba(143, 146, 161, .1)",
                                    zeroLineColor: "rgba(143, 146, 161, .1)",
                                },
                                ticks: {
                                    padding: 20,
                                },
                            },
                        },
                    },
                });
                // =========== chart one end

                // =========== chart two start
                const ctx2 = document.getElementById("Chart2").getContext("2d");
                const chart2 = new Chart(ctx2, {
                    type: "bar",
                    data: {
                        labels: [
                            "Jan",
                            "Fab",
                            "Mar",
                            "Apr",
                            "May",
                            "Jun",
                            "Jul",
                            "Aug",
                            "Sep",
                            "Oct",
                            "Nov",
                            "Dec",
                        ],
                        datasets: [
                            {
                                label: "",
                                backgroundColor: "#365CF5",
                                borderRadius: 30,
                                barThickness: 6,
                                maxBarThickness: 8,
                                data: [
                                    600, 700, 1000, 700, 650, 800, 690, 740, 720, 1120, 876, 900,
                                ],
                            },
                        ],
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    titleColor: function (context) {
                                        return "#8F92A1";
                                    },
                                    label: function (context) {
                                        let label = context.dataset.label || "";

                                        if (label) {
                                            label += ": ";
                                        }
                                        label += context.parsed.y;
                                        return label;
                                    },
                                },
                                backgroundColor: "#F3F6F8",
                                titleAlign: "center",
                                bodyAlign: "center",
                                titleFont: {
                                    size: 12,
                                    weight: "bold",
                                    color: "#8F92A1",
                                },
                                bodyFont: {
                                    size: 16,
                                    weight: "bold",
                                    color: "#171717",
                                },
                                displayColors: false,
                                padding: {
                                    x: 30,
                                    y: 10,
                                },
                            },
                        },
                        legend: {
                            display: false,
                        },
                        legend: {
                            display: false,
                        },
                        layout: {
                            padding: {
                                top: 15,
                                right: 15,
                                bottom: 15,
                                left: 15,
                            },
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                grid: {
                                    display: false,
                                    drawTicks: false,
                                    drawBorder: false,
                                },
                                ticks: {
                                    padding: 35,
                                    max: 1200,
                                    min: 0,
                                },
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false,
                                    color: "rgba(143, 146, 161, .1)",
                                    drawTicks: false,
                                    zeroLineColor: "rgba(143, 146, 161, .1)",
                                },
                                ticks: {
                                    padding: 20,
                                },
                            },
                        },
                        plugins: {
                            legend: {
                                display: false,
                            },
                            title: {
                                display: false,
                            },
                        },
                    },
                });
                // =========== chart two end

                // =========== chart three start
                const ctx3 = document.getElementById("Chart3").getContext("2d");
                const chart3 = new Chart(ctx3, {
                    type: "line",
                    data: {
                        labels: [
                            "Jan",
                            "Feb",
                            "Mar",
                            "Apr",
                            "May",
                            "Jun",
                            "Jul",
                            "Aug",
                            "Sep",
                            "Oct",
                            "Nov",
                            "Dec",
                        ],
                        datasets: [
                            {
                                label: "Revenue",
                                backgroundColor: "transparent",
                                borderColor: "#365CF5",
                                data: [80, 120, 110, 100, 130, 150, 115, 145, 140, 130, 160, 210],
                                pointBackgroundColor: "transparent",
                                pointHoverBackgroundColor: "#365CF5",
                                pointBorderColor: "transparent",
                                pointHoverBorderColor: "#365CF5",
                                pointHoverBorderWidth: 3,
                                pointBorderWidth: 5,
                                pointRadius: 5,
                                pointHoverRadius: 8,
                                fill: false,
                                tension: 0.4,
                            },
                            {
                                label: "Profit",
                                backgroundColor: "transparent",
                                borderColor: "#9b51e0",
                                data: [
                                    120, 160, 150, 140, 165, 210, 135, 155, 170, 140, 130, 200,
                                ],
                                pointBackgroundColor: "transparent",
                                pointHoverBackgroundColor: "#9b51e0",
                                pointBorderColor: "transparent",
                                pointHoverBorderColor: "#9b51e0",
                                pointHoverBorderWidth: 3,
                                pointBorderWidth: 5,
                                pointRadius: 5,
                                pointHoverRadius: 8,
                                fill: false,
                                tension: 0.4,
                            },
                            {
                                label: "Order",
                                backgroundColor: "transparent",
                                borderColor: "#f2994a",
                                data: [180, 110, 140, 135, 100, 90, 145, 115, 100, 110, 115, 150],
                                pointBackgroundColor: "transparent",
                                pointHoverBackgroundColor: "#f2994a",
                                pointBorderColor: "transparent",
                                pointHoverBorderColor: "#f2994a",
                                pointHoverBorderWidth: 3,
                                pointBorderWidth: 5,
                                pointRadius: 5,
                                pointHoverRadius: 8,
                                fill: false,
                                tension: 0.4,
                            },
                        ],
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                intersect: false,
                                backgroundColor: "#fbfbfb",
                                titleColor: "#8F92A1",
                                bodyColor: "#272727",
                                titleFont: {
                                    size: 16,
                                    family: "Plus Jakarta Sans",
                                    weight: "400",
                                },
                                bodyFont: {
                                    family: "Plus Jakarta Sans",
                                    size: 16,
                                },
                                multiKeyBackground: "transparent",
                                displayColors: false,
                                padding: {
                                    x: 30,
                                    y: 15,
                                },
                                borderColor: "rgba(143, 146, 161, .1)",
                                borderWidth: 1,
                                enabled: true,
                            },
                            title: {
                                display: false,
                            },
                            legend: {
                                display: false,
                            },
                        },
                        layout: {
                            padding: {
                                top: 0,
                            },
                        },
                        responsive: true,
                        // maintainAspectRatio: false,
                        legend: {
                            display: false,
                        },
                        scales: {
                            y: {
                                grid: {
                                    display: false,
                                    drawTicks: false,
                                    drawBorder: false,
                                },
                                ticks: {
                                    padding: 35,
                                },
                                max: 350,
                                min: 50,
                            },
                            x: {
                                grid: {
                                    drawBorder: false,
                                    color: "rgba(143, 146, 161, .1)",
                                    drawTicks: false,
                                    zeroLineColor: "rgba(143, 146, 161, .1)",
                                },
                                ticks: {
                                    padding: 20,
                                },
                            },
                        },
                    },
                });
                // =========== chart three end

                // ================== chart four start
                const ctx4 = document.getElementById("Chart4").getContext("2d");
                const chart4 = new Chart(ctx4, {
                    type: "bar",
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                        datasets: [
                            {
                                label: "",
                                backgroundColor: "#365CF5",
                                borderColor: "transparent",
                                borderRadius: 20,
                                borderWidth: 5,
                                barThickness: 20,
                                maxBarThickness: 20,
                                data: [600, 700, 1000, 700, 650, 800],
                            },
                            {
                                label: "",
                                backgroundColor: "#d50100",
                                borderColor: "transparent",
                                borderRadius: 20,
                                borderWidth: 5,
                                barThickness: 20,
                                maxBarThickness: 20,
                                data: [690, 740, 720, 1120, 876, 900],
                            },
                        ],
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                backgroundColor: "#F3F6F8",
                                titleColor: "#8F92A1",
                                titleFontSize: 12,
                                bodyColor: "#171717",
                                bodyFont: {
                                    weight: "bold",
                                    size: 16,
                                },
                                multiKeyBackground: "transparent",
                                displayColors: false,
                                padding: {
                                    x: 30,
                                    y: 10,
                                },
                                bodyAlign: "center",
                                titleAlign: "center",
                                enabled: true,
                            },
                            legend: {
                                display: false,
                            },
                        },
                        layout: {
                            padding: {
                                top: 0,
                            },
                        },
                        responsive: true,
                        // maintainAspectRatio: false,
                        title: {
                            display: false,
                        },
                        scales: {
                            y: {
                                grid: {
                                    display: false,
                                    drawTicks: false,
                                    drawBorder: false,
                                },
                                ticks: {
                                    padding: 35,
                                    max: 1200,
                                    min: 0,
                                },
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false,
                                    color: "rgba(143, 146, 161, .1)",
                                    zeroLineColor: "rgba(143, 146, 161, .1)",
                                },
                                ticks: {
                                    padding: 20,
                                },
                            },
                        },
                    },
                });
                // =========== chart four end
            }
        });
    </script>
</body>

</html>