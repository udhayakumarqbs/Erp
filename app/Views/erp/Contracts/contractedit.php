<div class="alldiv flex widget_title">
    <h3>Update Contract</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.contractview'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<style>
    .form-control {
        display: block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143 !important;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    /* tabs */
    .nav_container {
        padding: 0px 30px 0px 35px;
        position: relative;
        scroll-behavior: smooth;
    }

    .nav_tabs {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        overflow-x: hidden;
        position: relative;
    }

    .nav_link {
        padding: 10px;
        margin-right: 10px;
        cursor: pointer;
        position: relative;
        z-index: 0 !important;
    }

    .nav_link::before {
        content: "";
        top: 0;
        right: 0;
        width: 100% !important;
        height: 100%;
        position: absolute;
        z-index: 99;
    }

    .fa-angle-up.rotate-left {
        top: 45%;
        left: 10px;
        position: absolute;
        transform: rotate(270deg);
    }

    .left-arrow {
        border: none;
        background: white;
    }

    .right-arrow {
        border: none;
        background: white;
    }

    .fa-angle-up.rotate-right {
        top: 45%;
        right: 10px;
        position: absolute;
        transform: rotate(90deg);
    }

    .line {
        position: absolute;
        width: 59px;
        height: 2px !important;
        background: green;
        top: 34px;
        left: 9px;
        transition: all .3s ease;
    }

    .nav_link.active {
        color: #4b9bff;
    }

    .tab-pane.fade {
        display: none;
    }
    .dropdown_container{
        z-index: 100;
    }
    .tab-pane.fade.show {
        display: block;
    }

    /*  */
    .form_control_modal {
        display: block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143 !important;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form-control-btn {
        display: block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-top-right-radius: 0px !important;
        border-bottom-right-radius: 0px !important;
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
    }

    .rupees {
        display: block;

        padding: 7px 11px;
        height: 100%;
        font-size: 14px;
        line-height: 1.37565;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 5px;
        border-top-right-radius: 5px;
    }

    .form_control_group {
        display: block;
        width: 100%;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 14px;
        line-height: 1.42857143 !important;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
    }

    .form-control-startdate,
    .form-control-enddate {
        display: inline-block !important;
        width: 100% !important;
        padding: 6px 12px !important;
        font-size: 14px !important;
        line-height: 1.42857143 !important;
        color: #555 !important;
        background-color: #fff !important;
        background-image: none !important;
        border: 1px solid #ccc !important;
        border-radius: 5px !important;
    }

    .formcontroltextarea {
        display: inline-block;
        width: 100%;
        padding: 6px 12px;
        height: 80px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .btn-group.error input,
    .btn-group.error select {
        display: block;
        width: 100%;
        padding: 7px 13px;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #C83B3B;
    }

    .content-btn {
        margin-left: 100%;
    }

    .btn-group.error input[type="date"] {
        display: block;
        width: 100%;
        padding: 7px 13px;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #C83B3B;
    }

    .subject {
        display: block;
        width: 100%;
        padding: 7px 13px;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #C83B3B !important;
    }

    .btn-group select,
    .btn-group input {
        display: block;
        width: 100%;
        padding: 7px 13px;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
    }

    .plus.bg-primary {
        border-top-right-radius: 4px !important;
        border-bottom-right-radius: 4px !important;
        border-top-left-radius: 0px !important;
        border-bottom-left-radius: 0px !important;
        padding: 7px 13px;
    }

    .alert {
        display: none !important;
    }

    .alert_ct {
        display: none !important;
    }

    .btn-group.type {
        border-top-right-radius: 0px !important;
        border-bottom-right-radius: 0px !important;
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
    }



    .modal.fade {
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.3);
        width: 100%;
        height: 100vh;
        z-index: 1000;
    }

    .modal-dialog {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        max-width: 500px;
        min-width: 280px;
        padding: 16px;
        z-index: 1500;
        border: 1px solid white;
        border-radius: 6px;
        background: white;
    }

    .modal-body {
        width: 100%;
    }

    .close {
        border: none;
        background: transparent;
    }

    hr {
        border-color: grey;

    }

    .btn.bg-secondary {
        padding: 8px 16px;
    }

    :focus-visible {
        outline-color: #4443453b;
        outline-width: 0;
    }

    .noneclass {
        display: none;
    }

    .alldiv {
        width: 100%;
        border: none !important;
        box-shadow: none;
    }

    .first-container {
        box-shadow: 0 4px 6px 0 rgb(0 0 0 / 14%);
    }

    .mail {
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 40px;
    }

    .subject-title-sidebar {
        justify-content: space-between;
        gap: 10px;
    }

    .head-1 {
        padding: 10px;
        border: 1px solid #ccc !important;
        box-shadow: 0 4px 6px 0 rgb(0 0 0 / 14%);
        border-radius: 8px;
        align-items: center;
    }

    .alldiv.flex.widget_title {
        width: 100%;
        background-color: white;
        background: white;
        box-shadow: 0 4px 6px 0 rgb(0 0 0 / 14%);
        border-radius: 8px;
    }

    .dropdown_cont {
        position: relative;
        z-index: 999;
    }

    .dropdown-pdf {
        position: relative;
        z-index: 5;
    }

    .dropdown:focus {
        outline: 1px solid blue;
    }

    .dropdown-menu {
        position: absolute;
        display: flex;
        flex-direction: column;
        width: 140px;
        top: 55px;
        right: -10px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 8px;
        transition: top 500ms ease;
        box-shadow: 4px 4px 6px 0 rgb(0 0 0 / 14%);
        cursor: pointer;
        background-color: white;
    }

    .dropdown-menu-pdf {
        position: absolute;
        display: flex;
        flex-direction: column;
        background: white;
        width: 155px;
        top: 55px;
        right: -2px;
        background: white;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 8px;
        transition: top 500ms ease;
        box-shadow: 4px 4px 6px 0 rgb(0 0 0 / 14%);
        cursor: pointer;
        z-index: 100;
    }

    .dropdown-menu.hide {
        display: none;
    }

    .dropdown-menu-pdf.hide {
        display: none;
    }

    .select,
    .select-1 {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 75px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        color: black;
        background-color: lightgray;
    }

    .dropdown-menu>a {
        padding: 5px 0px 5px 2px;
        border-radius: 8px;
        text-wrap: nowrap;
    }

    .dropdown-menu-pdf>a {
        padding: 5px 0px 5px 2px;
        border-radius: 8px;
        text-wrap: nowrap;
    }

    .dropdown-menu>a:hover {
        background: lightgray;
        color: black;
    }

    .dropdown-menu-pdf>a:hover {
        background: lightgray;
        color: black;
    }

    .caret,
    .caret-pdf {
        height: 0;
        width: 0;
        border-top: 6px solid black;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
    }

    .spinner {
        height: 20px;
        width: 20px;
        border-radius: 50%;
        border-top: 1px solid white;
        border-left: 1px solid white;
        border-right: 1px solid white;
        border-bottom: 0px solid transparent;
        animation-name: spinner;
        animation-duration: 1s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }

    @keyframes spinner {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .spinner.hide {
        display: none;
    }

    .send-icon.hide {
        display: none;
    }

    .send.hide {
        display: none;
    }

    .caret.rotate {
        transform: rotate(180deg);
    }

    .caret-pdf.rotate-2 {
        transform: rotate(180deg);
    }

    .select::before {
        content: "";
        top: 0;
        left: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 2;
    }

    .select-1::before {
        content: "";
        top: 0;
        left: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 2;
    }

    .badge {
        background-color: #dce5ed;
        border-radius: 50%;
        color: rgb(30 41 59 / var(--tw-text-opacity));
        display: inline;
        font-size: .75rem;
        font-weight: 500;
        line-height: 1rem;
        padding: .125rem .625rem;
        margin-left: 4px;
    }

    .commend-box-pic {
        height: 5vh;
        border-radius: 50%;
        border: 2px solid black !important;
    }

    .commend-log {
        padding: 20px 0px 10px 10px;
        gap: 10px;
        margin-top: 10px;
        border-radius: 8px;
    }

    .commend-log-1 {
        gap: 10px;
        line-height: 30px;
    }

    .time {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
    }

    .options-commend {
        display: flex;
        justify-content: left;
        gap: 10px;
    }

    .comment-edit-container {
        display: block;
        transition: display 500ms ease;
    }

    .comment-edit-container.fade {
        display: none;
    }

    .edit_commend_btn {
        border: none;
        background: white;
        cursor: pointer;
        position: relative;
    }

    .edit_commend_btn::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 5;
    }

    .delete_commend_btn,
    .renew-btn {
        border: none;
        background: white;
        cursor: pointer;
    }

    .comment.show {
        display: block;
    }

    .comment {
        display: none;
    }

    .renewal-box-container {
        border-radius: 8px;
        border: .5px solid #00000027
    }

    .r_para {
        font-weight: 500;
        padding: 5px;
    }

    .renew_con>p {
        padding: 5px;
        color: #16a34a;
        font-weight: 500;
    }

    .renew_lab {
        font-size: .8rem;
        color: #3c84ed;
        padding: 3px 5px 2px 5px;
    }

    .datatable-pane {
        overflow-x: scroll;
    }
</style>

<div class="alldiv">
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="first-container">
                <?php echo form_open(url_to('erp.contract.update', $data->contract_id), array(
                    "class" => "flex",
                    "id" => "contract_update"
                ));
                ?>
                <div class="form-width-3  form-control">
                    <div class="form-group field-required">
                        <!-- Customer -->
                        <label class="form-label">Customer
                            <small class="req text-danger">*</small>
                        </label>
                        <div class="btn-group" role="group">
                            <select class="form-control" id="customer_id" aria-label="Default select example" name="dropdown_customer"></select>
                            <p class="alert text-danger p-0 mb-1" id="alert_1"><?php echo "This field is required."; ?></p>
                        </div>
                        <!-- subject -->
                        <label class="form-label">Subject
                            <small class="req text-danger">*</small>
                        </label>
                        <div class="btn-group" role="group">
                            <?php $subject = isset($data) ? $data->subject : "" ?>
                            <input type="text" name="subject" class="form-control" id="subject_id" value='<?= $subject ?>'>
                            <p class="alert text-danger p-0" id="alert_2"><?php echo "This field is required."; ?></p>
                        </div>

                        <!-- Contract Value -->
                        <label class="form-label">Contract Value</label>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <div class="d-flex">
                                <?php $contract_value = isset($data) ? $data->contract_value : "" ?>
                                <input type="number" class="form-control-btn mb-1" id="achievement_id" name="contractType" value='<?= $contract_value ?>'>
                                <label class="rupees">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
                                        <path d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z" />
                                    </svg>
                                </label>
                            </div>
                        </div>

                        <!-- Contract Type -->
                        <label class="form-label">Contract Type
                        </label>
                        <div role="group">
                            <div class="d-flex">
                                <select class="form_control_group groupid" id="contract_type" aria-label="Default select example" name="dropdown_contract_type">
                                </select>
                                <button type="button" class="plus btn bg-primary" data-toggle="modal" data-target="#contracttype_modal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="100%" fill="white" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="d-flex justify-content-between mb-1">
                            <!-- start date -->
                            <div class="date-start btn-group">
                                <label class="form-label btn-group">
                                    <?php echo "Start Date"; ?>
                                    <small class="req text-danger">*</small>
                                </label>
                                <?php $s_date = isset($data) ? $data->datestart : "" ?>
                                <input type="date" name="start_date" value="<?= $s_date ?>" id="start-date" class="form-control-startdate">
                                <p class="alert text-danger p-0" id="alertevent_1"><?php echo 'This field is required.'; ?></p>
                            </div>
                            <!-- end date -->
                            <div class="date-end btn-group">
                                <label class="form-label btn-group">
                                    <?php echo "End Date"; ?>
                                    <small class="req text-danger">*</small>
                                </label>
                                <?php $e_date = isset($data) ? $data->dateend : "" ?>
                                <input type="date" name="end_date" value="<?= $e_date ?>" id="end-date" class="form-control-enddate">
                                <p class="alert text-danger p-0" id="alertevent_2"><?php echo 'This field is required.'; ?></p>
                            </div>
                        </div>
                        <!-- description -->
                        <div>
                            <label class="form-label">
                                <?php echo "Description"; ?></label>
                            <?php $description = isset($data) ? $data->description : "" ?>
                            <input type="textarea" name="contractdescription" class="formcontroltextarea" value="<?php echo $description ?>"></input>
                            <P></P>
                        </div>
                        <!-- check box -->
                        <div class="tw-flex tw-justify-between tw-items-center mt-2 mb-4">
                            <div>
                                <div class="checkbox checkbox-primary checkbox-inline">
                                    <input type="checkbox" name="trash" id="trash" <?php echo $data->trash == 0 ? '' : 'checked' ?>>
                                    <label for="trash"><?php echo 'Trash'; ?></label>
                                </div>
                                <div class="checkbox checkbox-primary checkbox-inline">
                                    <input type="checkbox" name="hide_from_customer" id="hide" <?php echo $data->not_visible_to_client == 0 ? '' : 'checked' ?>>
                                    <label for="hide"><?php echo 'Hide from customer'; ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="form-group textRight d-flex justify-content-end ">
                                <a href="<?php echo url_to('erp.contractview'); ?>" class="btn outline-secondary m-1">Cancel</a>
                                <button class="btn bg-primary m-1" type="submit" id="announcement_add_submit"><i class="fa-solid fa-pen-to-square"></i> Update </button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <!-- footer -->

                <!-- modal -->
                <div class="modal fade" id="contracttype_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header d-flex justify-content-between">
                                <h5 class="modal-title" id="exampleModalLabel">New Contract Type</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                        </svg></span>
                                </button>
                            </div>
                            <hr>
                            <?php echo form_open(url_to("erp.contract.add.contracttype"), array(
                                "class" => "flex",
                                "id" => "contract_type_add_new"
                            ));
                            ?>
                            <div class="modal-body">
                                <div class="form-group field-required mb-5">
                                    <label class="form-label" id="form-alert">Contract Type Name
                                        <small class="req text-danger">*</small>
                                    </label>
                                    <input type="text" name="id" id="id" hidden>
                                    <!-- <input type="text" name="contract_name" class ="form_control_modal"> -->
                                    <input type="text" name="contract_name" class="form_control_modal" id="contract_type_id">
                                    <p class="alert_ct text-danger p-0" id="alert_ct"><?php echo 'This field is required.'; ?></p>
                                </div>
                                <div class="modal-footer d-flex justify-content-end">
                                    <button type="button" class="btn bg-secondary m-1" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn bg-primary m-1" id="insert_id_btn">Save</button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 pt-5 pt-md-0">
            <!-- Second Container -->
            <div class="second-container">
                <div class="head-1 d-flex justify-content-between border border-black">
                    <div class="subject-title">
                        <?php $subject = isset($data) ? $data->subject : "" ?>
                        <h4><?= $subject ?></h4>
                    </div>
                    <div class="subject-title-sidebar d-flex">
                        <a href="<?= url_to("erp.contract.view", $data->contract_id) ?>" class="btn bg-success" target="_blank">
                            <i class="fa fa-eye"></i>
                        </a>
                        <div class="dropdown-pdf">
                            <div class="select-1 d-flex" data-hide="dropdown-menu-pdf" data-caretpdf="caret-pdf">
                                <span class="selected"><i class="fa-regular fa-file-pdf"></i></span>
                                <div class="caret-pdf"></div>
                            </div>
                            <div class="dropdown-menu-pdf hide" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?php  echo url_to("erp.contract.view.pdf.download",$data->contract_id,"view") ?>">View PDF</a>
                                <a class="dropdown-item" href="<?php  echo url_to("erp.contract.view.pdf.download",$data->contract_id,"view")  ?>" target="_blank" >View PDF in New Tab</a>
                                <a class="dropdown-item" href="<?php  echo url_to("erp.contract.view.pdf.download",$data->contract_id,"download")  ?>">Download</a>
                            </div>
                        </div>

                        <button type="button" class="mail" data-toggle="modal" data-target="#contract_email"><i class="fa-regular fa-envelope"></i></button>

                        <div class="dropdown_cont">
                            <div class="select d-flex" data-hide="dropdown-menu" data-caret="caret">
                                <span class="selected">More</span>
                                <div class="caret"></div>
                            </div>
                            <div class="dropdown-menu hide" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?= url_to("erp.contract.view", $data->contract_id) ?>" target="_blank">View Contract</a>
                                <a class="dropdown-item del-confirm" href="<?php echo  url_to('erp.contract.delete',$data->contract_id)  ?>">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabs -->
            <div class="container border border-black my-2">
                <nav class="nav_container border border-black">
                    <button class="left-arrow" onclick="left()"><i class="fa-solid fa-angle-up rotate-left"></i></button>
                    <ul class="nav_tabs" id="nav_tabs" role="tablist">
                        <li class="nav_link active" id="nav_tab-contract" data-ds-toggle="tab" data-bs_target="nav_contract" type="button" role="tab" aria-controls="nav-contract" aria-selected="true">Contract</li>
                        <li class="nav_link" id="nav_tab-Attachemnts" data-ds-toggle="tab" data-bs_target="nav_Attachemnts" type="button" role="tab" aria-controls="nav-contract" aria-selected="false"><?php echo "Attachemnts" ?><span class="badge"><?php echo "" . $a_count ?></span></li>
                        <li class="nav_link" id="nav_tab-Comments" data-ds-toggle="tab" data-bs_target="nav_Comments" type="button" role="tab" aria-controls="nav-contract" aria-selected="false"><?php echo "Comments" ?><span class="badge" id="comment_count"></span></li>
                        <li class="nav_link" id="nav_tab-Renewal_History" data-ds-toggle="tab" data-bs_target="nav_Renewal_History" type="button" role="tab" aria-controls="nav-contract" aria-selected="false"><?php echo "Renewal" ?><span class="badge" id="renewal_count"></li>
                        <li class="nav_link" id="nav_tab-Tasks" data-ds-toggle="tab" data-bs_target="nav_Tasks" type="button" role="tab" aria-controls="nav-contract" aria-selected="false"><?php echo "Tasks" ?><span class="badge" id="task_count"><?php echo $t_count ?></span></li>
                       
                        <div class="line"></div>
                    </ul>
                    <button class="right-arrow" onclick="right()"><i class="fa-solid fa-angle-up rotate-right"></i></button>
                </nav>
                <div class="tab-content border border-black" id="nav_tabcontent">
                    <!-- content -->
                    <div class="tab-pane fade show p-3" id="nav_contract" role="tabpanel" aria-labelledby="nav-contract-tab">
                        <div class="content-container">
                            <?php $id = isset($data) ? $data->contract_id : "" ?>
                            <form action="" method="POST" class="flex" id="content_add">
                                <label class="form-label" id="form-alert">Add Content</label>
                                <?php $content = isset($data) ? $data->content : "" ?>
                                <input type="textarea" class="formcontroltextarea mt-3" id="content_add_new" name="content_value" value="<?php echo strip_tags($content) ?>">

                                <button type="submit" class="content-btn btn bg-success m-3" id="send_btn_email">Save</button>
                            </form>
                        </div>
                    </div>
                    <!--  -->
                    <div class="tab-pane fade p-3" id="nav_Attachemnts" role="tabpanel" aria-labelledby="nav-Attachemnts-tab">
                        <!-- Attachment -->
                        <div class="flex">
                            <div class="form-width-1">
                                <!-- file uploder -->
                                <div class="file-uploader-frame" data-ajax-url="<?php echo url_to('erp.contract.Attachment') . '?id=' . $data->contract_id . '&'; ?>">
                                    <div class="file-uploader-box">
                                        <span class="file-uploader-text">drop or click to upload files</span>
                                    </div>
                                    <div class="file-uploader-progessbar">
                                        <span class="progressbar bg-success"></span>
                                    </div>
                                    <input type="file" class="file-uploader-input" name="attachment" />
                                    <p class="file-uploader-error"></p>
                                </div>
                                <!--  -->
                            </div>
                            <div class="form-width-1">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody class="attachment-holder" data-ajaxdel-url="<?php echo url_to('erp.contract.deleteattachment') . '?'; ?>">
                                            <?php
                                            foreach ($attachments as $attach) {
                                            ?>
                                                <tr>
                                                    <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('contract_Attachment') . $attach['filename'];
                                                                                                                ?>"><?php echo $attach['filename'];
                                                                                                                    ?></a></td>
                                                    <td><button class="btn bg-danger del-attachment-btn" type="button" data-attach-id="<?php echo $attach['attach_id'];
                                                                                                                                        ?>"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                    </div>
                    <div class="tab-pane fade p-3" id="nav_Comments" role="tabpanel" aria-labelledby="nav-Comments-tab">
                        <!-- commend box-->
                        <?php echo form_open('erp.contractCommendAdd', array(
                            'id' => 'comment_form',
                            'class' => 'flex'
                        )) ?>
                        <textarea name="comment" id="comment_box" class="form_control"></textarea>
                        <button type="submit" class="btn bg-primary mt-3" id="submit_loader">
                            Add Comment
                        </button>
                        <?php echo form_close(); ?>
                        <!-- commend logs -->
                        <div class="commends-container" id="commend-cont">

                        </div>
                    </div>
                    <div class="tab-pane fade p-3" id="nav_Renewal_History" role="tabpanel" aria-labelledby="nav-Renewal-Historyt-tab">
                        <!-- Renewal History -->
                        <div class="renewal-history-container">
                            <button type="button" class="btn bg-success" data-toggle="modal" data-target="#renewal_history_modal">
                                <i class="fa-solid fa-rotate"></i> Renew Contract
                            </button>

                            <!-- modal -->
                            <div class="modal fade" id="renewal_history_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header d-flex justify-content-between">
                                            <h5 class="modal-title" id="exampleModalLabel">Renew Contract</h5>
                                            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                                    </svg></span>
                                            </button> -->
                                        </div>
                                        <hr>
                                        <?php echo form_open(url_to("erp.update.renewal", $data->contract_id), array(
                                            "class" => "flex",
                                            "id" => "renewal_contract_form"
                                        ));
                                        ?>
                                        <div class="modal-body">
                                            <!-- Start date -->
                                            <div class="date-start btn-group mt-2">
                                                <label class="form-label btn-group">
                                                    <?php echo "Start Date"; ?>
                                                    <small class="req text-danger">*</small>
                                                </label>
                                                <?php $s_date = isset($data) ? $data->datestart : "" ?>
                                                <input type="date" name="renewal_start_date" value="<?= $s_date ?>" id="renewal-start-date" class="form-control-startdate mt-2">
                                                <p class="alert text-danger p-0" id="alertevent_1"><?php echo 'This field is required.'; ?></p>
                                            </div>
                                            <!-- end date -->
                                            <div class="date-end btn-group mt-2">
                                                <label class="form-label btn-group">
                                                    <?php echo "End Date"; ?>
                                                </label>
                                                <?php $e_date = isset($data) ? $data->dateend : "" ?>
                                                <input type="date" name="renewal_end_date" value="<?= $e_date ?>" id="renewal-end-date" class="form-control-enddate mt-2">

                                            </div>
                                            <!-- contract value -->
                                            <div class="btn-group mt-2" role="group" aria-label="Basic example">
                                                <label class="form-label">Contract Value</label>
                                                <div class="d-flex mt-2">
                                                    <?php $contract_value = isset($data) ? $data->contract_value : "" ?>
                                                    <input type="number" class="form-control-btn mb-1" id="renewal-achievement_id" name="renewal-value" value='<?= $contract_value ?>'>
                                                    <label class="rupees" style="font-size: 8px;;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
                                                            <path d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z" />
                                                        </svg>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-end mt-2">
                                                <button type="button" class="btn bg-secondary m-1" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn bg-primary m-1">Reset</button>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                            <!-- renewal history container -->
                            <div class="renewal_history_container" id="renewal-cont">

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade datatable-pane p-3" id="nav_Tasks" role="tabpanel" aria-labelledby="nav-Tasks-tab">
                        <!-- Task -->
                        <div class="datatable" data-ajax-url="<?php echo url_to('erp.contract_task_datatable') . '?id=' . $id . '&'; ?>">
                            <div class="filterBox">
                                <div class="flex">
                                    <h4>Filter and Search</h4>
                                    <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
                                </div>
                            </div>
                            <div class="tableHeader flex">
                                <div class="formWidth">
                                    <div class="selectBox poR bulkaction" data-ajax-url="">
                                        <div class="selectBoxBtn flex">
                                            <div class="textFlow" data-default="Action">Action</div>
                                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                            <input type="hidden" class="selectBox_Value" value="">
                                        </div>
                                        <ul role="listbox" class="selectBox_Container alldiv">
                                            <li role="option" data-value="1">Delete</li>
                                            <li role="option" data-value="2">Send</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="formWidth">
                                    <input type="text" placeholder="search" class="form_control dt-search">
                                </div>
                                <div class="formWidth textRight">
                                    <!--export button-->
                                    <!-- <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i> Export</a> -->
                                    <a type="button" class="btn bg-primary" data-toggle="modal" data-target="#Task_modal"><i class="fa-solid fa-plus" style="color: #ffffff;"></i> Task</a>
                                    <div class="export_container poF">
                                        <ul class="exportUl">
                                            <li><a data-default-href="<?php //echo url_to("erp.Announcements.announcementexport") . '?export=excel&'; 
                                                                        ?>" href="<?php //echo base_url() . 'erp/Announcements/announcements-export?export=excel&';
                                                                                    ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                            <li><a data-default-href="<?php //echo url_to("erp.Announcements.announcementexport") . '?export=pdf&'; 
                                                                        ?>" href="<?php //echo base_url() . 'erp/Announcements/announcements-export?export=pdf&';
                                                                                    ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                            <li><a data-default-href="<?php //echo url_to("erp.Announcements.announcementexport") . '?export=csv&'; 
                                                                        ?>" href="<?php //echo base_url() . 'erp/Announcements/announcements-export?export=csv&';
                                                                                    ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
                                        </ul>
                                        <a type="button" class="closeBtn3 HoverA"><i class="fa fa-close"></i></a>
                                    </div>
                                    <!--export button-->
                                </div>
                            </div>
                            <div class="table_responsive">
                                <table class="table">
                                    <thead class="thead">

                                    </thead>
                                    <tbody class="table-paint-area">

                                    </tbody>
                                </table>
                            </div>
                            <div class="tableFooter flex">
                                <div class="tableFooterLeft flex">
                                    <p>Rows per page:</p>
                                    <div class="selectBox miniSelectBox poR">
                                        <div class="selectBoxBtn flex">
                                            <div class="textFlow" data-default="10">10</div>
                                            <button class="drops"><i class="fa fa-caret-down"></i></button>
                                            <input type="hidden" class="selectBox_Value" value="10">
                                        </div>
                                        <ul role="listbox" class="selectBox_Container alldiv">
                                            <li role="option" class="active" data-value="10">10</li>
                                            <li role="option" data-value="15">15</li>
                                            <li role="option" data-value="20">20</li>
                                            <li role="option" data-value="25">25</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tableFooterRight flex">
                                    <div class="pagination"><span class="dt-page-start">1</span> - <span class="dt-page-end">5</span> of <span class="dt-total-rows">100<span></div>
                                    <ul class="flex paginationBtns">
                                        <li><a type="button" class="HoverA dt-prev-btn"><i class="fa fa-angle-left"></i></a></li>
                                        <li><a type="button" class="HoverA dt-next-btn"><i class="fa fa-angle-right"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Task modal -->
                        <div class="modal fade" id="Task_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header d-flex justify-content-between">
                                        <h5 class="modal-title" id="exampleModalLabel">Renew Contract</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                                </svg></span>
                                        </button>
                                    </div>
                                    <hr>
                                    <?php echo form_open(url_to("erp.contract.task.add", $data->contract_id), array(
                                        "class" => "flex",
                                        "id" => "add_task_submit"
                                    ));
                                    ?>
                                    <div class="modal-body">
                                        <div class="d-flex">
                                            <div class="form-width-2">
                                                <div class="form-group field-required">
                                                    <label class="form-label">Status</label>
                                                    <div class="selectBox poR">
                                                        <div class="selectBoxBtn flex">
                                                            <div class="textFlow" data-default="select priority">select Status</div>
                                                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                                                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                                            <input type="hidden" class="selectBox_Value field-check" name="status" value="" id="status">
                                                        </div>
                                                        <ul role="listbox" class="selectBox_Container alldiv">
                                                            <?php
                                                            foreach ($task_status as $key => $status) {
                                                            ?>
                                                                <li role="option" data-value="<?php echo $key; ?>"><?php echo $status; ?></li>
                                                            <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                    <p class="error-text"></p>
                                                </div>
                                            </div>
                                            <div class="form-width-2">
                                                <div class="form-group field-required">
                                                    <label class="form-label">Priority</label>
                                                    <div class="selectBox poR">
                                                        <div class="selectBoxBtn flex">
                                                            <div class="textFlow" data-default="select priority">select priority</div>
                                                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                                                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                                            <input type="hidden" class="selectBox_Value field-check" name="priority" value="" id="Priority">
                                                        </div>
                                                        <ul role="listbox" class="selectBox_Container alldiv">
                                                            <?php
                                                            foreach ($task_priority as $key => $value) {
                                                            ?>
                                                                <li role="option" data-value="<?php echo $key; ?>"><?php echo $value; ?></li>
                                                            <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                    <p class="error-text"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="form-width-2">
                                                <div class="form-group field-required ">
                                                    <label class="form-label">Subject</label>
                                                    <input type="text" name="name" class="form_control field-check" id="subject" />
                                                    <p class="error-text"></p>
                                                </div>
                                            </div>

                                            <div class="form-width-2">
                                                <div class="form-group field-required">
                                                    <label class="form-label">Start Date</label>
                                                    <input type="date" name="start_date" class="form_control field-check" id="start" />
                                                    <p class="error-text"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="form-width-2">
                                                <div class="form-group field-required">
                                                    <label class="form-label">Due Date</label>
                                                    <input type="date" name="due_date" class="form_control field-check" id="end" />
                                                    <p class="error-text"></p>
                                                </div>
                                            </div>
                                            <div class="form-width-2">
                                                <div class="form-group field-required">
                                                    <label class="form-label related-text">Contract</label>
                                                    <div class="ajaxselectBox poR">
                                                        <div class="ajaxselectBoxBtn flex">
                                                            <div class="textFlow" id="show_select_option" data-default="select related_id">select related</div>
                                                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                                                            <button class="drops" id="custom_drops" type="button"><i class="fa fa-caret-down"></i></button>
                                                            <input type="hidden" class="ajaxselectBox_Value field-check" name="related_id" id="ajaxselectBox_Value_related" value="">
                                                        </div>
                                                        <div class="ajaxselectBox_Container alldiv" id="relatedData">
                                                            <input type="text" class="ajaxselectBox_Search form_control" />
                                                            <ul role="listbox">
    
                                                            </ul>
                                                        </div>
                                                    </div>
    
                                                    <p class="error-text"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">

                                            <div class="form-width-2">
                                                <div class="form-group field-required">
                                                    <label class="form-label">Assigned To</label>
                                                    <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.contract.task.ajaxFetchemployee'); ?>">
                                                        <div class="ajaxselectBoxBtn flex">
                                                            <div class="textFlow" data-default="assign to" >assign to</div>
                                                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                                                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                                            <input type="hidden" class="ajaxselectBox_Value field-check" name="assignees" value="" id="Assigned_to">
                                                        </div>
                                                        <div class="ajaxselectBox_Container alldiv">
                                                            <input type="text" class="ajaxselectBox_Search form_control" />
                                                            <ul role="listbox">
    
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <p class="error-text"></p>
                                                </div>
                                            </div>
                                            <div class="form-width-2">
                                                <div class="form-group field-required">
                                                    <label class="form-label">Followers</label>
                                                    <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.contract.task.ajaxFetchemployee'); ?>">
                                                        <div class="ajaxselectBoxBtn flex">
                                                            <div class="textFlow" data-default="select followers">select followers</div>
                                                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                                                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                                            <input type="hidden" class="ajaxselectBox_Value field-check" name="followers" value="" id="follower">
                                                        </div>
                                                        <div class="ajaxselectBox_Container alldiv">
                                                            <input type="text" class="ajaxselectBox_Search form_control" />
                                                            <ul role="listbox">

                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <p class="error-text"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                        </div>
                                        <div class="form-width-1">
                                            <div class="form-group field-required ">
                                                <label class="form-label">Description</label>
                                                <textarea rows="3" name="task_description" class="form_control field-check"></textarea>
                                                <p class="error-text"></p>
                                            </div>
                                        </div>
                                        <div class="form-width-1">
                                            <div class="form-group textRight">
                                                <a href="" class="btn outline-secondary">Cancel</a>
                                                <button class="btn bg-primary" type="submit" id="task_add_submit" >Save</button>
                                            </div>
                                        </div>
                                        </form>

                                    </div>
                                    <?php echo form_close();
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Email modal -->
        <div class="modal fade" id="contract_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h5 class="modal-title" id="exampleModalLabel">Send Contract To Email</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                </svg></span>
                        </button>
                    </div>
                    <hr>
                    <?php
                    echo form_open(url_to("erp.contract.email"), array(
                        "class" => "flex",
                        "id" => "contract_send_email"
                    ));
                    ?>
                    <div class="modal-body">
                        <div class="form-group field-required mb-5">
                            <input type="text" name="id" id="id" value="<?php echo $email->id ?>" hidden>
                            <!-- send to custamer mail-->
                            <label class="form-label" id="form-alert">Send to <small class="req text-danger">*</small></label>
                            <?php $Email =  isset($email) ? $email->email : "Not entered" ?>
                            <div class="btn-group" role="group">
                                <input type="email" id="customer_email_id" class="form_control" value="<?= $Email ?>" disabled>
                            </div>
                            <input type="text" name="customer_email" value="<?= $Email ?>" hidden>

                            <!-- CC -->
                            <label class="form-label" id="form-alert">CC</label>
                            <div class="btn-group" role="group">
                                <input type="text" name="customer_email_cc" id="customer_email_cc" class="form_control">
                            </div>

                            <!-- subject -->
                            <label class="form-label" id="form-alert">Subject
                                <small class="req text-danger">*</small>
                            </label>
                            <div class="btn-group" role="group">
                                <input type="text" name="customer_email_subject" id="customer_email_subject" class="form_control">
                                <p class="alert text-danger p-0" id="alertsubject"><?php echo 'This field is required.'; ?></p>
                            </div>

                            <!-- message content -->
                            <label class="form-label" id="form-alert">Email Content</label>
                            <div class="btn-group" role="group">
                                <?php echo form_textarea("customer_description", "", "id='customer_email_description' class='form-control  '"); ?>
                            </div>
                        </div>

                        <div class="modal-footer d-flex justify-content-end">
                            <button type="button" class="btn bg-secondary m-1" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn bg-primary m-1" id="send_btn_email">
                                <div class="spinner hide" id="spinner"></div> <span id="btn-submit" class="send">Send</span> <i class="fa-regular fa-paper-plane send-icon" id="btn-submit-icon"></i>
                            </button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <!-- Task update Model -->
        <div class="modal fade" id="Task_update_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h5 class="modal-title" id="exampleModalLabel">Update Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                </svg></span>
                        </button>
                    </div>
                    <hr>
                    <?php echo form_open(url_to("erp.contract.task.update"), array(
                        "class" => "flex",
                        "id" => "update_task_submit"
                    ));
                    ?>
                    <div class="modal-body">
                        <div class="d-flex">
                            <input type="hidden" id="task_id_update" name="task_id">
                            <div class="form-width-2">
                                <div class="form-group field-required">
                                    <label class="form-label">Status</label>
                                    <div class="selectBox poR">
                                        <div class="selectBoxBtn flex">
                                            <?php $status = "" ?>
                                            <div class="textFlow" data-default="select priority" id="status-text">select Status</div>
                                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                            <input type="hidden" class="selectBox_Value field-check" name="status_update" value="" id="status_update">
                                        </div>
                                        <ul role="listbox" class="selectBox_Container alldiv">
                                            <?php
                                            foreach ($task_status as $key => $status) {
                                            ?>
                                                <li role="option" data-value="<?php echo $key; ?>"><?php echo $status; ?></li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <p class="error-text"></p>
                                </div>
                            </div>
                            <div class="form-width-2">
                                <div class="form-group field-required">
                                    <label class="form-label">Priority</label>
                                    <div class="selectBox poR">
                                        <div class="selectBoxBtn flex">
                                            <div class="textFlow" data-default="select priority" id="priority-text">select priority</div>
                                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                            <input type="hidden" class="selectBox_Value field-check" name="priority" value="" id="Priority_update">
                                        </div>
                                        <ul role="listbox" class="selectBox_Container alldiv">
                                            <?php
                                            foreach ($task_priority as $key => $value) {
                                            ?>
                                                <li role="option" data-value="<?php echo $key; ?>"><?php echo $value; ?></li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <p class="error-text"></p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="form-width-2">
                                <div class="form-group field-required ">
                                    <label class="form-label">Subject</label>
                                    <input type="text" name="name" class="form_control field-check" id="subject_update" />
                                    <p class="error-text"></p>
                                </div>
                            </div>

                            <div class="form-width-2">
                                <div class="form-group field-required">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form_control field-check" id="start_update" />
                                    <p class="error-text"></p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="form-width-2">
                                <div class="form-group field-required">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" name="due_date" class="form_control field-check" id="end_update" />
                                    <p class="error-text"></p>
                                </div>
                            </div>
                            <div class="form-width-2">
                                <div class="form-group field-required">
                                    <label class="form-label related-text" id="related_text">Contract</label>
                                    <div class="ajaxselectBox poR">
                                        <div class="ajaxselectBoxBtn flex">
                                            <div class="textFlow" id="show_select_option_update" data-default="select related_id" >select related</div>
                                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                                            <button class="drops" id="custom_drops_update" type="button"><i class="fa fa-caret-down"></i></button>
                                            <input type="hidden" class="ajaxselectBox_Value field-check" name="related_id" id="ajaxselectBox_Value_related_update" value="">
                                        </div>
                                        <div class="ajaxselectBox_Container alldiv" id="relatedDataupdate">
                                            <input type="text" class="ajaxselectBox_Search form_control" />
                                            <ul role="listbox">

                                            </ul>
                                        </div>
                                    </div>
                                    <p class="error-text"></p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="form-width-2">
                                <div class="form-group field-required">
                                    <label class="form-label">Assigned To</label>
                                    <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.contract.task.ajaxFetchemployee'); ?>">
                                        <div class="ajaxselectBoxBtn flex">
                                            <div class="textFlow" data-default="assign to" id = "assign_to_text">assign to</div>
                                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                            <input type="hidden" class="ajaxselectBox_Value field-check" name="assignees" value="" id="Assigned_to_update">
                                        </div>
                                        <div class="ajaxselectBox_Container alldiv">
                                            <input type="text" class="ajaxselectBox_Search form_control" />
                                            <ul role="listbox">

                                            </ul>
                                        </div>
                                    </div>
                                    <p class="error-text"></p>
                                </div>
                            </div>
                            <div class="form-width-2">
                                <div class="form-group field-required">
                                    <label class="form-label">Followers</label>
                                    <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.contract.task.ajaxFetchemployee'); ?>">
                                        <div class="ajaxselectBoxBtn flex">
                                            <div class="textFlow" data-default="select followers"  id="followers_text">select followers</div>
                                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                            <input type="hidden" class="ajaxselectBox_Value field-check" name="followers" value="" id="follower_update">
                                        </div>
                                        <div class="ajaxselectBox_Container alldiv">
                                            <input type="text" class="ajaxselectBox_Search form_control" />
                                            <ul role="listbox">

                                            </ul>
                                        </div>
                                    </div>
                                    <p class="error-text"></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-width-1">
                            <div class="form-group field-required ">
                                <label class="form-label">Description</label>
                                <textarea rows="3" name="task_description" class="form_control field-check" id="task-description"></textarea>
                                <p class="error-text"></p>
                            </div>
                        </div>
                        <div class="form-width-1">
                            <div class="form-group textRight">
                                <a href="" class="btn outline-secondary">Cancel</a>
                                <button class="btn bg-primary" type="submit" id="task_update_submit">Update</button>
                            </div>
                        </div>
                        </form>

                    </div>
                    <?php echo form_close();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/across-tabs/1.4.0/across-tabs.min.js"></script>

<script>
    // data table handler
    let closer_d = new WindowCloser();
    closer_d.init();

    let datatable_elem = document.querySelector(".datatable");

    let rows_per_page = new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
    rows_per_page.init();
    closer_d.register_shutdown(rows_per_page.shutdown, rows_per_page.get_container());

    let bulkaction = new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
    bulkaction.init();
    closer_d.register_shutdown(bulkaction.shutdown, bulkaction.get_container());
    let config = JSON.parse('<?php echo $dt_config; ?>');
    let datatable = new DataTable(datatable_elem, config);
    datatable.init();

    rows_per_page.add_listener(datatable.rows_per_page, {});

    if (config['filters'] !== null && config['filters'] !== undefined) {
        let filter_1 = document.getElementById("lead_filter_1");
        let filter_2 = document.getElementById("lead_filter_2");

        let select_box_1 = new SelectBox(filter_1);
        select_box_1.init();
        select_box_1.add_listener(datatable.filter, {
            type: "select",
            column: config['filters'][0]
        });
        closer_d.register_shutdown(select_box_1.shutdown, select_box_1.get_container());

        let select_box_2 = new SelectBox(filter_2);
        select_box_2.init();
        select_box_2.add_listener(datatable.filter, {
            type: "select",
            column: config['filters'][1]
        });
        closer_d.register_shutdown(select_box_2.shutdown, select_box_2.get_container());
    }

    //add Task
    $(document).ready(function() {
        
        $.ajax({
            type: 'GET',
            url: '<?= url_to('erp.contract.task.relate_to') ?>',
            dataType: 'json',
            success: function(response) {
                console.log('Success fetching data: ', response);

                if (response && response.length > 0) {
                    var html = '<ul role="listbox">';

                    $.each(response, function(index, item) {
                        // console.log(item);
                        console.log(Object.values(item)[0]);
                        html += '<li role="option" onclick="select_for_related(event)" data-value="' + Object.values(item)[0] + '">' + Object.values(item)[1] + '</li>';
                    });

                    html += '</ul>';

                    $('#relatedData').html(html);

                    // $('.selectBox_Value').val(selectedOption);

                    // $('.related-text').text(selectedOption);
                }

                // } else {
                //     $('#relatedData').html('<p>No data available.</p>');
                // }
            },
            error: function(error) {
                console.error('Error fetching data: ', error);
            }
        });
    });


    function select_for_related(event) {
        console.log(event.target.dataset.value);
        $('#relatedData').css('display', 'none');
        $('#custom_drops').removeClass('active');

        // $('#relatedData').css('display','block')
        document.getElementById('show_select_option').innerHTML = event.target.innerHTML;
        document.getElementById('ajaxselectBox_Value_related').value = event.target.dataset.value;

    }

    let closer_li = new WindowCloser();
    closer_li.init();

    document.querySelectorAll(".selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        closer_li.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer_li.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    //----update task-----//
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '<?= url_to('erp.contract.task.relate_to') ?>',
            dataType: 'json',
            success: function(response) {
                console.log('Success fetching data: ', response);

                if (response && response.length > 0) {
                    var html = '<ul role="listbox">';
                    $.each(response, function(index, item) {
                        console.log(Object.values(item)[0]);
                        html += '<li role="option" onclick="select_for_related_update(event)" data-value="' + Object.values(item)[0] + '">' + Object.values(item)[1] + '</li>';
                    });
                    html += '</ul>';

                    $('#relatedDataupdate').html(html);

                    // $('.related-text').text(selectedOption);
                }
            },
            error: function(error) {
                console.error('Error fetching data: ', error);
            }
        });
    });




    function select_for_related_update(event) {
        console.log(event.target.dataset.value);
        $('#relatedData_update').css('display', 'none');
        $('#custom_drops_update').removeClass('active');

        // $('#relatedData').css('display','block')
        document.getElementById('show_select_option_update').innerHTML = event.target.innerHTML;
        document.getElementById('ajaxselectBox_Value_related_update').value = event.target.dataset.value;

    }
    let closer_li_update = new WindowCloser();
    closer_li_update.init();
    document.querySelectorAll(".selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        closer_li_update.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer_li_update.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });


    //------task submit--------//
    $(document).ready(function() {
        $("#add_task_submit").submit(function(event) {
            event.preventDefault();
            var status = $("#status").val();
            var Priority = $("#Priority").val();
            var Subject = $("#subject").val();
            var Start_Date = $("#start").val();
            var End_Date = $("#end").val();
            var related = $("#ajaxselectBox_Value_related").val();
            var followers = $("#follower").val();
            var assigned_to = $("#Assigned_to").val();

            if (status != "" && Priority != "" && Subject != "" && Start_Date != "" && End_Date != "" && related != "" && followers != "" && assigned_to != "") {
                var formdataTask = $(this).serialize();
                $.ajax({
                    url: "<?php echo url_to("erp.contract.task.add", $data->contract_id) ?>",
                    type: "post",
                    data: formdataTask,
                    success: function(response) {
                        var alert_task = new ModalAlert();
                        if (response.success) {
                            $("#Task_modal").modal('hide');
                            $("#add_task_submit")[0].reset();
                            location.reload();
                            alert_task.invoke_alert("Task added successfully", "success");
                        } else {
                            $("#add_task_submit")[0].reset();
                            alert_task.invoke_alert("Task added successfully", "error");
                        }
                    },
                    error: function(xhr, target, error) {
                        console.log("Someerror", error);
                    }
                })
            }
        });
       
    });

    //------update task modal-------//
    function update_task(id) {
        $("#Task_update_modal").modal("toggle");
        $.ajax({
            url: '<?php echo url_to("erp.taskexist.data.fetch") ?>',
            type: "POST",
            data: {
                id: id
            },
            success: function(response) {
                var data = response;
                console.log(data.task);
                $("#status_update").val(data.task.status);
                if (data.task.status == 0) {
                    $("#status-text").text("Not Started");
                } else if (data.task.status == 1) {
                    $("#status-text").text("In progress");
                } else if (data.task.status == 2) {
                    $("#status-text").text("Testing");
                } else {
                    $("#status-text").text("Complete");
                }
                $("#Priority_update").val(data.task.priority);
                if (data.task.priority == 0) {
                    $("#priority-text").text("Low");
                } else if (data.task.priority == 1) {
                    $("#priority-text").text("Medium");
                } else if (data.task.priority == 2) {
                    $("#priority-text").text("high");
                } else {
                    $("#priority-text").text("Urgent");
                }
                $("#subject_update").val(data.task.name);
                $("#start_update").val(data.task.start_date);
                $("#end_update").val(data.task.due_date);
                $("#ajaxselectBox_Value_related_update").val(data.task.contract_id);
                $("#show_select_option_update").text(data.task.contract_name);
                $("#assign_to_text").text(data.task.assigness_name);
                $("#Assigned_to_update").val(data.task.assignees);
                $("#followers_text").text(data.task.Followers_name);
                $("#follower_update").val(data.task.followers);
                $("#task-description").val(data.task.task_description);
                $("#task_id_update").val(data.task.task_id);
            }
        });

        $("#update_task_submit").submit(function(event){
            event.preventDefault();
            var status = $("#status_update").val();
            var Priority = $("#Priority_update").val();
            var Subject = $("#subject_update").val();
            var Start_Date = $("#start_update").val();
            var End_Date = $("#end_update").val();
            var Related = $("#ajaxselectBox_selectBox_Value_related_update").val();
            var assigned_to = $("#Assigned_to_update").val();
            var followers = $("#follower_update").val();
            if(status != "" && Priority != "" && Subject != "" && Start_Date != "" && End_Date != "" && Related != "" && followers != "" && assigned_to != ""){
                var formdataTaskupdate = $(this).serialize();
                $.ajax({
                    url: "<?php echo url_to("erp.contract.task.update") ?>",
                    type: "post",
                    data: formdataTaskupdate,
                    success: function(response) {
                        var alert_task = new ModalAlert();
                        if (response.success) {
                            $("#Task_update_modal").modal('hide');
                            $("#update_task_submit")[0].reset();
                            location.reload(); 
                            alert_task.invoke_alert("Task updated successfully", "success");
                        } else {
                            $("#update_task_submit")[0].reset();
                            alert_task.invoke_alert("Task updated successfully", "error");
                        }
                    },
                    error: function(xhr, target, error) {
                        console.log("Someerror", error);
                    }
                })
            }
        });

    }
    //delete task
    function delete_task(id){
        $.ajax({
            url : "<?php echo url_to("erp.contract.task.delete") ?>",
            type:"POST",
            data :{id:id},
            success :function(response){
                var alert_task = new ModalAlert();
                if(response.success){
                    location.reload();
                    alert_task.invoke_alert("Task deleted successfully", "success");
                }else{
                    alert_task.invoke_alert("error", "error");
                }
            }
        });
    }
    //file attachment
    let closer = new WindowCloser();
    closer.init();
    let tbody = document.querySelector(".attachment-holder");
    let fileuploader = new FileUploader(document.querySelector(".file-uploader-frame"));
    let alert_file = new ModalAlert();

    fileuploader.ajaxFullUpload({
        files_allowed: ["text/plain", "image/png", "application/pdf", "image/jpeg", "image/gif", "image/jpg", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "image/webp", "image/svg+xml"],
        listener: function(json) {
            let tr = document.createElement("tr");
            let td1 = document.createElement("td");
            let td2 = document.createElement("td");
            td1.innerHTML = `<a target="_BLANK" download class="text-primary" href="` + json['filelink'] + `">` + json['filename'] + `</a>`;
            td2.innerHTML = `<button class="btn bg-danger del-attachment-btn" type="button" data-attach-id="` + json['insert_id'] + `" ><i class="fa fa-trash"></i></button>`;
            tr.append(td1);
            tr.append(td2);
            tbody.append(tr);
        }
    });

    tbody.onclick = (evt) => {
        let target = evt.target;
        let ajax_url = tbody.getAttribute("data-ajaxdel-url");
        tbody.querySelectorAll(".del-attachment-btn").forEach((item) => {
            if (item.contains(target)) {
                let xhr = null;
                if (window.ActiveXObject) {
                    xhr = new ActiveXObject("Msxml2.XMLHTTP");
                } else if (window.XMLHttpRequest) {
                    xhr = new XMLHttpRequest();
                }
                if (xhr !== null || xhr !== undefined) {
                    xhr.open("GET", ajax_url + "id=" + item.getAttribute("data-attach-id"), true);
                    xhr.send(null);
                    xhr.onreadystatechange = (evt) => {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            let json = JSON.parse(xhr.responseText);
                            if (json['error'] == 0) {
                                alert_file.invoke_alert(json['reason'], "success");
                                item.parentElement.parentElement.remove();
                            } else {
                                alert_file.invoke_alert(json['reason'], "error");
                            }
                        }
                    }
                }
            }
        })
    };
    //end file

    //comment
    function api_commend() {
        $.ajax({
            url: "<?php echo url_to("erp.contractCommendfetch") ?>",
            type: "GET",
            data: {
                id: <?= $data->contract_id ?>
            },
            success: function(response) {
                var data = JSON.parse(response);
                commend_box(data);
            }

        });
    }
    //renewal container
    function api_renewal_container() {
        $.ajax({
            url: "<?php echo url_to("erp.renewal.data", $data->contract_id) ?>",
            type: "GET",
            success: function(response) {
                var data_renewal = JSON.parse(response);
                console.log(data_renewal);
                renewal_box(data_renewal);
            }

        });
    }

    function dateformat(date) {
        var now = moment();
        var duration = moment.duration(now.diff(date));
        var minute = duration.asMinutes();
        if (minute < 1) return 'now';
        if (minute < 60) return Math.floor(minute) + 'mins ago';
        if (minute < 1440) return Math.floor(minute / 60) + 'hours ago';
        if (minute < 2880) return 'yesterday';
        return date.format('MMM D, YYYY');
    }
    //commend container
    function commend_box(data) {
        $("#commend-cont").empty();
        let commend_box = '<div class="commend-log d-flex border border-black">' +
            '<div class="commend-log-container w-100">' +
            '<div class="d-flex justify-content-between commend-log-1">' +
            '<div class="d-flex ">' +
            '<div><b>{user_name}</b></div>' +
            '<div> - </div>' +
            '<div class="time"><p class="p-time">{time}</p></div>' +
            '</div>' +
            '<div class="options-commend">' +
            '<button type="button" class="edit_commend_btn" onclick="edit_commend(event)" data-editbtn="comment-edit-container"><i class="fa-solid fa-pen-to-square"></i></button>' +
            '<button type="button" onclick="commend_del({del-id})" class="delete_commend_btn del-confirm"><i class="fa-solid fa-trash" style="color: #e60505;"></i></button>' +
            '</div>' +
            '</div>' +
            '<div class="mt-2">' +
            '<div class="comment-edit-container fade">' +

            '<div class="comment-form flex" id="comment-update-{id}"  method="POST">' +
            '<input type="hidden" name="comment_id" id="comment_id" value="{id-1}">' +
            '<textarea  class="formcontroltextarea contract-comment-textarea" name="comment_update">{comment_textarea}</textarea>' +
            '<button type="button" onclick="commend_fun({id-2})" class="btn bg-primary mt-3">update comment</button>' +
            '</div>' +

            '</div>' +
            '<p class="comment show">{comment}</p>' +
            '</div>' +
            '</div>' +
            '</div>';
        var count = 0;
        $.each(data.commends, function(index, commend) {
            // console.log(commend);
            var copy = commend_box;
            var datestring = commend.c_date;
            var date = moment(datestring, 'YYYY-MM-DD HH:mm:ss');
            var date_format = dateformat(date);
            copy = copy.replace('{user_name}', commend.f_name + '' + commend.l_name);
            copy = copy.replace('{time}', date_format);
            copy = copy.replace('{id}', commend.id);
            copy = copy.replace('{id-1}', commend.id);
            copy = copy.replace('{id-2}', commend.id);
            copy = copy.replace('{del-id}', commend.id);
            copy = copy.replace('{comment}', commend.commend);
            copy = copy.replace('{comment_textarea}', commend.commend);
            $("#commend-cont").append(copy);
            count++;
        });

        if (count > 0) {
            $("#comment_count").text(count);
        } else {
            $("#comment_count").text(0);
        }

    }

    //renewal cont
    function renewal_box(data) {
        $("#renewal-cont").empty();

        let renewal_box = '<div class="renewal-box-container mt-2 p-3 border border-black">' +
            '<div class="d-flex justify-content-between">' +
            '<p classs="r_para p-1">{user} renewed this contract</p>' +
            '<button type="button" onclick="renewal_del({id})" class="renew-btn del-confirm"><i class="fa-solid fa-x" style="color: #ff0000; font-size: x-small;"></i></button>' +
            '</div>' +
            '<div>' +
            '<label class="renew_lab" >{renewaltime}</label>' +
            '</div>' +
            '<div class="renew_con mt-3">' +
            '<p>New Start Date : {startdate}</p>' +
            '<p>New End Date : {enddate}</p>' +
            '</div>' +
            '</div>';
        var count = 0;
        $.each(data.renewal, function(index, renewal) {
            var copy = renewal_box;
            copy = copy.replace('{user}', renewal.f_name + "" + renewal.l_name);
            copy = copy.replace('{id}', renewal.id);
            copy = copy.replace('{renewaltime}', renewal.r_date);
            copy = copy.replace('{startdate}', renewal.s_date);
            copy = copy.replace('{enddate}', renewal.e_date);
            $("#renewal-cont").append(copy);
            count++;
        });

        if (count > 0) {
            $("#renewal_count").text(count);
        } else {
            $("#renewal_count").text(0);
        }
    }

    function edit_commend(event) {
        event.target.parentElement.parentElement.parentElement.querySelector(".comment-edit-container").classList.toggle("fade");
        event.target.parentElement.parentElement.parentElement.querySelector(".comment").classList.toggle("show");
    }
    //right arrow left arrow 
    function right() {
        document.getElementById("nav_tabs").scrollTo(598, 0);
    }

    function left() {
        document.getElementById("nav_tabs").scrollTo(0, 0);
    }

    //Tabs
    var tabs = document.querySelectorAll(".nav_link");
    var con = document.querySelectorAll(".tab-pane");
    tabs.forEach(tab => {
        tab.addEventListener("click", function(event) {
            tabs.forEach(tab => {
                tab.classList.remove("active");
            })
            tab.classList.add("active");

            con.forEach(cons => {
                cons.classList.remove("show");
            })
            var content = document.querySelector("#" + event.target.dataset.bs_target);
            console.log(content.classList);
            content.classList.add("show");
            var line = document.querySelector(".line");
            line.style.width = (event.target.offsetWidth - 20) + "px";
            console.log(event.target.offsetWidth);
            line.style.left = 10 + event.target.offsetLeft + "px";
        })
    })

    //add content using api
    $(document).ready(function() {
        $("#content_add").submit(function(event) {
            event.preventDefault();
            var content_value = $("#content_add_new").val();
            console.log(content_value);
            if (content_value === "" || content_value === null) {
                $("#content_add_new").focus();
            } else {
                var formdata_2 = $(this).serialize();
                // console.log(formdata_2);
                $.ajax({
                    url: "<?php echo url_to("erp.contract.add_content", $id) ?>",
                    type: "post",
                    data: formdata_2,
                    success: function(response) {
                        var alert_content = new ModalAlert();
                        if (response.success) {
                            alert_content.invoke_alert("Saved", "success");
                        } else {
                            alert_content.invoke_alert("error", "error");
                        }
                    },error : function(xhr,status,error){
                        var alert_content = new ModalAlert();
                        alert_content.invoke_alert("error", "error");
                    }
                });
            }
        })
    })
    //Text editor
    ClassicEditor
        .create(document.getElementById("customer_email_description"), {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'undo',
                    'redo'
                ]
            },
            language: 'en'
        })
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });

    //form submition 
    $(document).ready(function() {
        customer();
        contract();
        $(".form-control").on("input", function(event) {
            if (event.target.value == "" || event.target.value == null || event.target.value == 0) {
                event.target.parentElement.classList.add("error");
                event.target.parentElement.querySelector("p").classList.remove('alert');
                console.log(event.target.parentElement.querySelector("p"));
            } else {
                event.target.parentElement.classList.remove("error");
                event.target.classList.remove("subject");
                event.target.parentElement.querySelector("p").classList.add('alert');
            }
        });
        $("#contract_update").submit(function(event) {
            event.preventDefault();
            var subject = $("#subject_id").val();
            var startdate = $("#start-date").val().length;
            var enddate = $("#end-date").val().length;
            var customer = $("#customer_id").val();
            if (!customer) {
                $("#customer_id").addClass("subject");
                $("#alert_1").removeClass("alert");
            } else if (!subject) {
                $("#subject_id").addClass("subject");
                $("#alert_2").removeClass("alert");
            } else if (startdate == 0) {
                $("#start-date").addClass("subject");
                $("#alertevent_1").removeClass("alert");
            } else if (enddate == 0) {
                $("#end-date").addClass("subject");
                $("#alertevent_2").removeClass("alert");
            } else if (customer != " " && subject != " " && startdate != 0 && enddate != 0) {
                console.log("i am hitting");
                this.submit();
            }
        });
    });

    //comment add
    $(document).ready(function() {
        $("#comment_form").submit(function(event) {
            event.preventDefault();
            var comment = $("#comment_box").val();
            if (comment != "") {
                let formdata_comment = $(this).serialize();
                $.ajax({
                    url: '<?php echo url_to("erp.contractCommendAdd", $data->contract_id) ?>',
                    type: 'POST',
                    data: formdata_comment,
                    success: function(response) {
                        api_commend();
                        if (response.success) {
                            $("#comment_form")[0].reset();
                        } else {
                            $("#comment_form")[0].reset();
                        }
                    },
                    error: function(xhr, target, error) {
                        console.error("Oops : !", error);
                    }
                })
            }
        });
    });

    //comment update

    function commend_fun(element_id) {
        var id = element_id;
        var text_area_id = "#comment-update-" + id + "  .contract-comment-textarea";
        // console.log(text_area_id);
        var comment_u = $(text_area_id).val();
        // console.log(comment_u);
        if (comment_u != "") {
            $.ajax({
                url: '<?php echo url_to("erp.contractCommendupdate") ?>',
                type: 'POST',
                data: {
                    "id": id,
                    "comment": comment_u
                },
                success: function(response) {
                    console.log(response)
                    api_commend();
                    if (response.success) {
                        console.log("true");

                    } else {
                        console.log("false");
                    }
                },
                error: function(xhr, target, error) {
                    console.error("Oops : !", error);
                }
            });
        }

    }
    //commend delete 
    function commend_del(id) {
        $.ajax({
            url: '<?php echo url_to("erp.comment.delete") ?>',
            type: 'POST',
            data: {
                "id": id
            },
            success: function(response) {
                console.log(response)
                if (response.success) {
                    api_commend();
                    console.log("true");

                } else {
                    console.log("false");
                }
            },
            error: function(xhr, target, error) {
                console.error("Oops : !", error);
            }
        });
    }
    //renewal delete
    function renewal_del(id) {
        $.ajax({
            url: '<?php echo url_to("erp.renewal.delete") ?>',
            type: 'POST',
            data: {
                "id": id
            },
            success: function(response) {
                console.log(response)
                if (response.success) {
                    api_renewal_container();
                    console.log("true");

                } else {
                    console.log("false");
                }
            },
            error: function(xhr, target, error) {
                console.error("Oops : !", error);
            }
        });

    }
    //modal submit
    $(document).ready(function() {
        $(".form_control_modal").on("input", function(event) {
            if (event.target.value == " " || event.target.value == null) {
                event.target.parentElement.classList.add("error");
                event.target.parentElement.querySelector('p').remove("alert_ct");
                console.log(event.target.parentElement);
            } else {
                event.target.parentElement.querySelector("p").classList.add("alert_ct");
                event.target.classList.remove("subject");
            }
        });
        $("#contract_type_add_new").submit(function(event) {
            event.preventDefault();
            var name = $("#contract_type_id").val();
            if (!name) {
                $("#contract_type_id").addClass("subject");
                $("#alert_ct").removeClass("alert_ct");
            } else {
                var formdata = $(this).serialize();
                $.ajax({
                    url: "<?php echo url_to('erp.contract.add.contracttype') ?>",
                    type: "POST",
                    data: formdata,
                    success: function(response) {
                        var alert = new ModalAlert();
                        if (response.success) {
                            $("#contract_type_add_new")[0].reset();
                            $("#contracttype_modal").modal('hide');
                            alert.invoke_alert("Added Successfully", "success");
                        } else {
                            $("#contract_type_add_new")[0].empty();
                            $("#contracttype_modal").modal('hide');
                            alert.invoke_alert("Error occured", "error");
                        }
                        contract();
                    },
                    error: function(xhr, target, error) {
                        console.error("Oops", error);
                    }
                });
            }
        })
    });
    //Email Modal
    $(document).ready(function() {
        $("#customer_email_subject").on("input", function(event) {
            if (event.target.value == null || event.target.value == "") {
                event.target.parentElement.querySelector("p").classList.remove('alert');
                event.target.parentElement.classList.add('error');
            } else {
                event.target.parentElement.classList.remove('error');
                event.target.classList.remove('subject');
                event.target.parentElement.querySelector("p").classList.add('alert');
            }
        })
        $("#contract_send_email").submit(function(event) {
            event.preventDefault();
            var subject = $("#customer_email_subject").val();
            if (!subject) {
                $("#customer_email_subject").addClass("subject");
                $("#alertsubject").removeClass("alert");
            } else {
                var formdata_1 = $(this).serialize();
                $("#spinner").removeClass("hide");
                $("#btn-submit").addClass("hide");
                $("#btn-submit-icon").addClass("hide");
                $("#send_btn_email").prop("disabled", true);

                console.log(formdata_1);
                $.ajax({
                    url: "<?php echo url_to("erp.contract.email") ?>",
                    type: "post",
                    data: formdata_1,
                    success: function(response) {
                        $("#spinner").addClass("hide");
                        $("#btn-submit").removeClass("hide");
                        $("#btn-submit-icon").removeClass("hide");
                        $("#send_btn_email").prop("disabled", false);
                        var alert = new ModalAlert();
                        console.log(response);
                        if (response.success) {
                            $("#contract_send_email")[0].reset();
                            $("#contract_email").modal('hide');
                            alert.invoke_alert("Email Sended Successfully", "success");
                        } else {
                            $("#contract_send_email")[0].reset();
                            $("#contract_email").modal('hide');
                            alert.invoke_alert("Email Sended Successfully", "error");
                        }
                    },
                    error: function(xhr, target, error) {
                        console.error("email", error)
                    }

                })
            }
        })
    })

    //renewal modal
    $(document).ready(function() {
        customer();
        contract();
        $(".form-control").on("input", function(event) {
            if (event.target.value == " " || event.target.value == null || event.target.value == 0) {
                event.target.parentElement.classList.add("error");
                event.target.parentElement.querySelector("p").classList.remove('alert');
            } else {
                event.target.parentElement.classList.remove("error");
                event.target.classList.remove("subject");
                event.target.parentElement.querySelector("p").classList.add('alert');
            }
        });
        $("#renewal_contract_form").submit(function(event) {
            event.preventDefault();
            var startdate = $("#renewal-start-date").val().length;
            var enddate = $("#renewal-end-date").val().length;
            var customer = $("#renewal-achievement_id").val();
            if (startdate == 0) {
                $("#start-date").addClass("subject");
                $("#alertevent_1").removeClass("alert");
            } else if (enddate == 0) {
                $("#end-date").addClass("subject");
                $("#alertevent_2").removeClass("alert");
            } else {
                this.submit();
                api_renewal_container();
            }
        });
    });

    //customer dropdown list data
    function customer() {
        $.ajax({
            url: "<?= url_to('erp.fetchCustomerDetails') ?>",
            type: "POST",
            success: function(response) {
                var data = JSON.parse(response);
                customer_assemble(data);
            },
            error: function(xhr, target, error) {
                console.error("utg oops", error);
            }
        });
    }
    //contract dropdown list data
    function contract() {
        $.ajax({
            url: "<?php echo url_to("erp.fetchContracttypeDetails") ?>",
            type: "post",
            success: function(response) {
                var data_1 = JSON.parse(response);
                contractype_assemble(data_1)
            }
        });
    }
    //customertype dropdown
    function customer_assemble(data) {
        $("#customer_id").empty();
        var exist_customer = <?= $data->client ?>;
        console.log(exist_customer);
        var structure = "<option value ='{id}'> {customer} </option>";
        var structure_exist = "<option value ='{id}' selected> {customer} </option>";
        var noselected = "<option value ='' disabled selected> {customer} </option>";
        if (data.customers.length <= 0) {
            var copy = structure;
            copy = copy.replace("{id}", " ");
            copy = copy.replace("{customer}", "--Empty--");
            $("#customer_id").append(copy);
        } else {
            var noselect = noselected;
            noselect = noselect.replace("{id}", "");
            noselect = noselect.replace("{customer}", "--Select--");
            $("#customer_id").append(noselect);
            $.each(data.customers, function(index, customer) {
                var copy = "";
                if (exist_customer == customer.cust_id) {
                    copy = structure_exist;
                    copy = copy.replace("{id}", customer.cust_id);
                    copy = copy.replace("{customer}", customer.company);
                } else {
                    copy = structure;
                    copy = copy.replace("{id}", customer.cust_id);
                    copy = copy.replace("{customer}", customer.company);
                }
                $("#customer_id").append(copy);
            });
        }
    }


    //contracttype dropdown
    function contractype_assemble(data_1) {
        $("#contract_type").empty();
        var contract_type_id = <?= $data->contract_type ?>;
        var noselected = "<option value ='' disabled selected> {customer} </option>";
        var structure_exist = "<option value ='{id}' selected> {customer} </option>";
        var structure = "<option value ='{id}'> {customer} </option>";
        if (data_1.contracttype.length <= 0) {
            var copy = structure;
            copy = copy.replace("{id}", "");
            copy = copy.replace("{customer}", "--Empty--");
            $("#contract_type").append(copy);
        } else {
            var noselect = noselected;
            noselect = noselect.replace("{id}", "");
            noselect = noselect.replace("{customer}", "--Select--");
            $("#contract_type").append(noselect);
            $.each(data_1.contracttype, function(index, contract) {
                var copy = "";
                if (contract_type_id == contract.cont_id) {
                    copy = structure_exist;
                    copy = copy.replace("{id}", contract.cont_id);
                    copy = copy.replace("{customer}", contract.cont_name);
                } else {
                    copy = structure;
                    copy = copy.replace("{id}", contract.cont_id);
                    copy = copy.replace("{customer}", contract.cont_name);
                }
                $("#contract_type").append(copy);
            });
        }
    }

    //drop down
    $(".dropdown_cont").on("click", function(event) {
        $("." + event.target.dataset.caret).toggleClass("rotate");
        $("." + event.target.dataset.hide).toggleClass("hide");
    });
    $(".dropdown-pdf").on("click", function(event) {
        console.log(event.target.dataset.caretpdf);
        $("." + event.target.dataset.caretpdf).toggleClass("rotate-2");
        $("." + event.target.dataset.hide).toggleClass("hide");
    });
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
    customer();
    api_commend();
    api_renewal_container();
</script>
</body>

</html>