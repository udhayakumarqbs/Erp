<div class="alldiv flex widget_title">
    <h3>View Expense</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.expensesview'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
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
        width: 80px;
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

    .dropdown_container {
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

    .Top-head-second {
        gap: 10px;
    }

    .user_name {
        color: #3c84ed;
    }

    .exist-attach {
        width: 100%;
        display: flex !important;
        justify-content: space-between !important;
        gap: 300px;
    }

    .attach-file {
        width: 550px;
    }

    .file-uploader-frame {
        position: relative !important;
        display: block !important;
        box-sizing: border-box;
    }

    .file-uploader-input {
        display: flex !important;
        position: absolute !important;
        opacity: 0;
        z-index: 1;
        width: 100%;
        height: 100%;
        border: 1px solid red;
    }

    .file_uploder_name {
        color: #16b1ff;
        padding: 3px;
    }

    .expense-attach {
        display: flex !important;
        justify-content: space-between !important;
        height: 120vh;
    }

    .form-group.error textarea,
    .form-group.error input,
    .ajaxselectBoxBtn.flex.error input {
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

    .ajaxselectBoxBtn.flex.subject {
        display: flex !important;
        width: 100%;
        padding: 0px !important;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #C83B3B;
    }
</style>

<div class="alldiv">
    <!-- Second Container -->
    <div class="second-container">
        <?php //var_dump($existing_data) ?>
        <div class="head-1 d-flex justify-content-between border border-black">
            <div class="p-1 w-25">
                <h1><?= $existing_data->cat_name ?></h1>
                <?php if (isset($existing_data->name)) { ?>
                    <p><?= $existing_data->name ?></p>
                <?php  } ?>
                <p>Created by : <span class="user_name"><?= get_username_by_id($existing_data->addedfrom) ?></span></p>
            </div>
            <?php //var_dump($existing_data) ?>
            <div class="p-1 w-75 d-flex Top-head-second">
                <?php if($existing_data->invoice_code == null){?>
                    <a href="<?= url_to("sale.expense.add.invoice", $id) ?>" class="btn bg-success"><i class="fa-solid fa-file-invoice" style="color: #f0f2f5;"></i> Convert To Invoice</a>
                <?php }else{?>
                    <a href="<?= url_to("erp.sale.invoice.view",$existing_data->invoice_id)?>" class="btn bg-primary"><i class="fa-solid fa-file-invoice" style="color: #f0f2f5;"></i> <?= $existing_data->invoice_code ?></a>
                <?php } ?>
                <a href="<?= url_to("erp.expenses.update.view", $id) ?>" class="btn bg-primary"><i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="#" class="btn bg-light border border-black"><i class="fa-solid fa-print" style="color: #121212;"></i></a>
                <a href="<?= url_to("erp.expenses.delete", $id) ?>" class="btn bg-danger del-confirm"><i class="fa-solid fa-trash"></i></a>
            </div>
        </div>
    </div>
    <!-- Tabs -->
    <div class="container border border-black my-2">
        <nav class="nav_container border border-black">
            <ul class="nav_tabs" id="nav_tabs" role="tablist">
                <li class="nav_link active" id="nav_tab-expense" data-ds-toggle="tab" data-bs_target="nav_expense" type="button" role="tab" aria-controls="nav-expense" aria-selected="true">Expenses of</li>
                <li class="nav_link" id="nav_tab-Tasks" data-ds-toggle="tab" data-bs_target="nav_Tasks" type="button" role="tab" aria-controls="nav-expense" aria-selected="false"><?php echo "Tasks" ?><span class="badge" id="task_count"><?= $t_count ?></span></li>
                <li class="nav_link" id="nav_tab-reminder" data-ds-toggle="tab" data-bs_target="nav_Reminder" type="button" role="tab" aria-controls="nav-expense" aria-selected="false"><?php echo "Reminders" ?><span class="badge" id="reminder_count"><?php echo $r_count ?></li>
                <div class="line"></div>
            </ul>
        </nav>
        <div class="tab-content" id="nav_tabcontent">
            <!-- content -->
            <div class="tab-pane fade show p-3" id="nav_expense" role="tabpanel" aria-labelledby="nav-expense-tab">
                <div class="expense-attach">
                    <div class="content-expense p-4 w-50">
                        <?php $tax_1 = isset($existing_data->tax_1_name) ? (($existing_data->tax_1_per / 100) * $existing_data->amount)  : 0 ?>
                        <?php $tax_2 = isset($existing_data->tax_2_name) ? (($existing_data->tax_2_per / 100) * $existing_data->amount)  : 0 ?>
                        <h3>Amount : <span class="text-danger"> <?= "₹" . $existing_data->amount ?></span></h3>
                        <p style="display:inline-block; font-size:15px; font-weight:500; color:grey;">Paid Via <?= $existing_data->payment ?></p>
                        <?php if (isset($existing_data->tax_1_name) && isset($existing_data->tax_2_name)) { ?>
                            <h3 class="mt-4"><span>Tax 1 :</span>
                                <p style="display:inline-block; font-size:15px; font-weight:500; color:grey;"><?= $existing_data->tax_1_per . "%" . "(" . $existing_data->tax_1_name . ")" ?> </p>
                            </h3>
                            <h3 class="">Tax 2 : <p style="display:inline-block; font-size:15px; font-weight:500; color:grey;"><?= $existing_data->tax_2_per . "%" . "(" . $existing_data->tax_2_name . ")" ?> </p>
                            </h3>
                            <h3 class="text-danger">Total with tax : <?= "₹" . $tax_1 + $tax_2 + $existing_data->amount ?></h3>
                        <?php } elseif (isset($existing_data->tax_1_name)) { ?>
                            <h3 class="mt-4">Tax 1 : <p style="display:inline-block; font-size:15px; font-weight:500; color:grey;"><?= $existing_data->tax_1_per . "%" . "(" . $existing_data->tax_1_name . ")" ?> </p>
                            </h3>
                            <h3 class="text-danger">Total with tax : <?= "₹" . $tax_1 + $existing_data->amount ?></h3>
                        <?php } else { ?>
                            <h3 class="">Tax 2 : <p style="display:inline-block; font-size:15px; font-weight:500; color:grey;"><?= $existing_data->tax_2_per . "%" . "(" . $existing_data->tax_2_name . ")" ?> </p>
                            </h3>
                            <h3 class="text-danger">Total with tax : <?= "₹" . $tax_2 + $existing_data->amount ?></h3>
                        <?php } ?>
                        <!-- date -->
                        <h3 class="mt-4">Date : <p style="display:inline-block; font-size:15px; font-weight:500; color:#3c84ed;"><?= $existing_data->date ?> </p>
                        </h3>
                        <!-- Invoice -->
                        <?php if ($existing_data->invoice_code == null) { ?>
                            <h3 class="mt-4 text-danger"> <span class="border border-black p-1 rounded-lg" style="background-color: #F5B9C3 ;">Invoice Not Created</span> </h3>
                        <?php } else { ?>
                            <h3 class="mt-4 "><?= $existing_data->invoice_code ?> - <span class="st <?php echo $invoice_status_bg[$status["status"]]; ?>">
                                            <?php
                                            if ($status["status"] == 0) {
                                                echo 'Created';
                                            } elseif ($status["status"] == 1) {
                                                echo 'Partially Paid';
                                            } elseif ($status["status"] == 2) {
                                                echo 'Paid';
                                            } elseif ($status["status"] == 3) {
                                                echo 'Overdue';
                                            }
                                            ?>
                                        </span></h3>
                        <?php } ?>
                        <!-- reff -->
                        <?php if ($existing_data->refrence != "") { ?>
                            <h3 class="mt-4">Ref #: <p style="display:inline-block; font-size:15px; font-weight:500; color:#3c84ed;" style="background-color: #F5B9C3 ;"><?= $existing_data->refrence ?> </h3>
                        <?php } ?>
                        <?php if (isset($existing_data->customer_id)) { ?>
                            <h3 class="mt-4"><span>Customer</span></h3>
                            <p class="mt-1" style="display:inline-block; font-size:15px; font-weight:500; color:#3c84ed;"><?= $existing_data->customer ?> </p>
                        <?php } ?>
                        <?php if (isset($existing_data->project_id)) { ?>
                            <h3 class="mt-4"><span>Project</span></h3>
                            <p class="mt-1" style="display:inline-block; font-size:15px; font-weight:500; color:#3c84ed;"><?= $existing_data->project_name ?> </p>
                        <?php } ?>
                        <?php if (isset($existing_data->exp_note)) { ?>
                            <h3 class="mt-4"><span>Note</span></h3>
                            <p class="mt-1" style="display:inline-block; font-size:15px; font-weight:500; color:#3c84ed;"><?= $existing_data->exp_note ?> </p>
                        <?php } ?>
                    </div>
                    <div class="content-expense p-4 w-50 border border-black">

                        <?php if (isset($exist_attachment)) { ?>
                            <!-- No File -->
                            <h3 class="mt-2"><i class="fa-solid fa-file-lines"></i> <span>Expense Receipt</span></h3>
                            <div class="exist-attach p-2 mt-3 align-items-center">
                                <div>
                                    <a target="_BLANK" download class="text-primary" href='<?php echo "" . get_attachment_link('Expenses_Attachment') . $exist_attachment->filename ?>'> <?php echo $exist_attachment->filename ?> </a>
                                </div>
                                <a class="del-confirm" href="<?php echo url_to("expense.view.attachment.recipt.delete", $exist_attachment->attach_id, $existing_data->id) ?>"><i class="fa-solid fa-x fa-xs" style="color: #ff0000;"></i></a>
                            </div>
                        <?php } else { ?>
                            <!-- Attach Receipt -->

                            <div class="attach-file">
                                <h3 class="mt-4"><i class="fa-solid fa-file-lines"></i> <span>Expense Receipt</span></h3>
                                <?php echo form_open(url_to("erp.expenses.attachment.update", $existing_data->id), array(
                                    "id" => "attachment_update",
                                    "enctype" => "multipart/form-data"
                                )) ?>
                                <!-- file uploder -->
                                <div class="file-uploader-frame mt-2">
                                    <input type="file" class="file-uploader-input" name="attachment" id="file_uploder" data-file="#uploder_name" />
                                    <div class="file-uploader-box">
                                        <span class="file-uploader-text">Attach Receipt</span>
                                    </div>
                                </div>
                                <div>
                                    <p class="file_uploder_name" id="uploder_name"></p>
                                </div>
                                <button class="btn bg-primary m-1 text-right" type="submit" id="attachment_upload">Upload</button>
                                <!--  -->
                                </form>
                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="tab-pane fade datatable-pane p-3" id="nav_Tasks" role="tabpanel" aria-labelledby="nav-Tasks-tab">
                <!-- Task -->
                <div class="datatable" data-ajax-url="<?php echo url_to('erp.expense.task.fetchTable') . '?id=' . $existing_data->id . '&';
                                                        ?>">
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
                                                                            ?>" target="_BLANK"><img src="<?php //echo base_url() . 'assets/images/icons/xls.png'; 
                                                                                                            ?>" alt="excel">EXCEL</a></li>
                                    <li><a data-default-href="<?php //echo url_to("erp.Announcements.announcementexport") . '?export=pdf&'; 
                                                                ?>" href="<?php //echo base_url() . 'erp/Announcements/announcements-export?export=pdf&';
                                                                            ?>" target="_BLANK"><img src="<?php // echo base_url() . 'assets/images/icons/pdf.png'; 
                                                                                                            ?>" alt="pdf">PDF</a></li>
                                    <li><a data-default-href="<?php //echo url_to("erp.Announcements.announcementexport") . '?export=csv&'; 
                                                                ?>" href="<?php //echo base_url() . 'erp/Announcements/announcements-export?export=csv&';
                                                                            ?>" target="_BLANK"><img src="<?php //echo base_url() . 'assets/images/icons/csv.png'; 
                                                                                                            ?>" alt="csv">CSV</a></li>
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
                                <h5 class="modal-title" id="exampleModalLabel">TasK Add</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                        </svg></span>
                                </button>
                            </div>
                            <hr>
                            <?php echo form_open(url_to("erp.expense.task.add", $existing_data->id), array(
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
                                                        <li role="option" data-value="<?php echo $key;
                                                                                        ?>"><?php echo $status;
                                                                                            ?></li>
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
                                                        <li role="option" data-value="<?php echo $key;
                                                                                        ?>"><?php echo $value;
                                                                                            ?></li>
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
                                            <label class="form-label related-text">Expenses</label>
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
                                            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.contract.task.ajaxFetchemployee');
                                                                                            ?>">
                                                <div class="ajaxselectBoxBtn flex">
                                                    <div class="textFlow" data-default="assign to">assign to</div>
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
                                            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.contract.task.ajaxFetchemployee');
                                                                                            ?>">
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
                                        <button class="btn bg-primary" type="submit" id="task_add_submit">Save</button>
                                    </div>
                                </div>
                                </form>

                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade p-3" id="nav_Reminder" role="tabpanel" aria-labelledby="nav-Reminder-tab">
                <a href="#" class="btn bg-success mt-2" data-toggle="modal" data-target="#Reminder_modal"><i class="fa-solid fa-bell"></i> Set Expense Reminder</i></a>
                <!-- Reminder data table -->
                
                <div class="datatable" id="datatable_reminder" data-ajax-url="<?php echo url_to('erp.expense.reminder.fetchTable') . '?reminder_id=' . $existing_data->id . '&';
                                                                                ?>">
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
                            <div class="export_container poF">
                                <ul class="exportUl">
                                    <li><a data-default-href="<?php //echo url_to("erp.Announcements.announcementexport") . '?export=excel&'; 
                                                                ?>" href="<?php //echo base_url() . 'erp/Announcements/announcements-export?export=excel&';
                                                                            ?>" target="_BLANK"><img src="<?php //echo base_url() . 'assets/images/icons/xls.png'; 
                                                                                                            ?>" alt="excel">EXCEL</a></li>
                                    <li><a data-default-href="<?php //echo url_to("erp.Announcements.announcementexport") . '?export=pdf&'; 
                                                                ?>" href="<?php //echo base_url() . 'erp/Announcements/announcements-export?export=pdf&';
                                                                            ?>" target="_BLANK"><img src="<?php // echo base_url() . 'assets/images/icons/pdf.png'; 
                                                                                                            ?>" alt="pdf">PDF</a></li>
                                    <li><a data-default-href="<?php //echo url_to("erp.Announcements.announcementexport") . '?export=csv&'; 
                                                                ?>" href="<?php //echo base_url() . 'erp/Announcements/announcements-export?export=csv&';
                                                                            ?>" target="_BLANK"><img src="<?php //echo base_url() . 'assets/images/icons/csv.png'; 
                                                                                                            ?>" alt="csv">CSV</a></li>
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
                <!-- Reminder modal  -->
                <div class="modal fade" id="Reminder_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header d-flex justify-content-between">
                                <h5 class="modal-title" id="exampleModalLabel">Set Expense Reminder</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                        </svg></span>
                                </button>
                            </div>
                            <hr>
                            <?php echo form_open(url_to("erp.expense.reminder.add", $existing_data->id), array(
                                "class" => "flex",
                                "id" => "reminder_form"
                            ));
                            ?>
                            <div class="modal-body">

                                <div class="">
                                    <div>
                                        <div class="form-group field-required">
                                            <label class="form-label">Date to be notified
                                                <small class="req text-danger">*</small>
                                            </label>
                                            <input type="datetime-local" name="start_reminder_date" class="form_control field-check" id="start_reminder_date" />
                                            <p class="alert text-danger p-0 mb-1" id="alert_date"><?php echo "This field is required."; ?></p>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-width-5">
                                    <div class="form-group field-required">
                                        <label class="form-label">Set reminder to
                                            <small class="req text-danger">*</small>
                                        </label>
                                        <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.contract.task.ajaxFetchemployee', $existing_data->id);
                                                                                        ?>">
                                            <div class="ajaxselectBoxBtn flex">
                                                <div class="textFlow" data-default="select followers">select followers</div>
                                                <button class="close" type="button"><i class="fa fa-close"></i></button>
                                                <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                                <input type="hidden" class="ajaxselectBox_Value field-check" name="reminder_to" value="" id="reminder_to">
                                            </div>
                                            <div class="ajaxselectBox_Container alldiv">
                                                <input type="text" class="ajaxselectBox_Search form_control" />
                                                <ul role="listbox">

                                                </ul>
                                            </div>
                                            <p class="alert text-danger p-0 mb-1" id="alert_to"><?php echo "This field is required."; ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="description-reminder">
                                    <div class="form-group field-required ">
                                        <label class="form-label">Description
                                            <small class="req text-danger">*</small>
                                        </label>
                                        <textarea rows="3" name="reminder_description" class="form_control field-check" id="reminder_decription"></textarea>
                                        <p class="alert text-danger p-0 mb-1" id="alert_desc"><?php echo "This field is required."; ?></p>
                                    </div>
                                </div>
                                <div class="tw-flex tw-justify-between tw-items-center mb-2 mt-2 checkbox hide" id="check_box_id">
                                    <div class="checkbox checkbox-primary checkbox-inline">
                                        <input type="checkbox" name="email_reminder" id="email_reminder">
                                        <label for="trash"><?php echo 'Send also an email for this reminder'; ?></label>
                                    </div>
                                </div>
                                <div class="form-width-1">
                                    <div class="form-group textRight">
                                        <a href="" class="btn outline-secondary">Cancel</a>
                                        <button class="btn bg-primary" type="submit" id="task_add_submit">Save</button>
                                    </div>
                                </div>
                                </form>

                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
                <!-- Reminder update modal -->
                <div class="modal fade" id="Reminder_update_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header d-flex justify-content-between">
                                <h5 class="modal-title" id="exampleModalLabel">Update Expense Reminder</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                        </svg></span>
                                </button>
                            </div>
                            <hr>
                            <?php echo form_open(url_to("erp.expense.reminder.update"), array(
                                "class" => "flex",
                                "id" => "reminder_update_form"
                            ));
                            ?>
                            <div class="modal-body">
                                <div class="">
                                    <div>
                                        <input type="hidden" id="reminder_id" name="reminder_id" >
                                        <div class="form-group field-required">
                                            <label class="form-label">Date to be notified
                                                <small class="req text-danger">*</small>
                                            </label>
                                            <input type="datetime-local" name="start_reminder_date" class="form_control field-check" id="start_reminder_date_update" />
                                            <p class="alert text-danger p-0 mb-1" id="alert_date_u"><?php echo "This field is required."; ?></p>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-width-5">
                                    <div class="form-group field-required">
                                        <label class="form-label">Set reminder to
                                            <small class="req text-danger">*</small>
                                        </label>
                                        <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.contract.task.ajaxFetchemployee', $existing_data->id);
                                                                                        ?>">
                                            <div class="ajaxselectBoxBtn flex">
                                                <div class="textFlow" data-default="select followers" id="reminder_follower">select Reminder to</div>
                                                <button class="close" type="button"><i class="fa fa-close"></i></button>
                                                <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                                <input type="hidden" class="ajaxselectBox_Value field-check" name="reminder_to" value="" id="reminder_to_update">
                                            </div>
                                            <div class="ajaxselectBox_Container alldiv">
                                                <input type="text" class="ajaxselectBox_Search form_control" />
                                                <ul role="listbox">

                                                </ul>
                                            </div>
                                            <p class="alert text-danger p-0 mb-1" id="alert_to_u"><?php echo "This field is required."; ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="description-reminder">
                                    <div class="form-group field-required ">
                                        <label class="form-label">Description
                                            <small class="req text-danger">*</small>
                                        </label>
                                        <textarea rows="3" name="reminder_description" class="form_control field-check" id="reminder_decription_update"></textarea>
                                        <p class="alert text-danger p-0 mb-1" id="alert_desc_u"><?php echo "This field is required."; ?></p>
                                    </div>
                                </div>
                                <div class="tw-flex tw-justify-between tw-items-center mb-2 mt-2 checkbox hide" id="check_box_id">
                                    <div class="checkbox checkbox-primary checkbox-inline">
                                        <input type="checkbox" name="email_reminder" id="email_reminder_update" class="reminder_u_check">
                                        <label for="trash"><?php echo 'Send also an email for this reminder'; ?></label>
                                    </div>
                                </div>
                                <div class="form-width-1">
                                    <div class="form-group textRight">
                                        <a href="" class="btn outline-secondary">Cancel</a>
                                        <button class="btn bg-primary" type="submit" id="task_add_submit">Save</button>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
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
                                            <li role="option" data-value="<?php echo $key;
                                                                            ?>"><?php echo $status; ?></li>
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
                                            <li role="option" data-value="<?php echo $key;
                                                                            ?>"><?php echo $value;
                                                                                ?></li>
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
                                <label class="form-label related-text" id="related_text">Expense</label>
                                <div class="ajaxselectBox poR">
                                    <div class="ajaxselectBoxBtn flex">
                                        <div class="textFlow" id="show_select_option_update" data-default="select related_id">select related</div>
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
                                <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.contract.task.ajaxFetchemployee');
                                                                                ?>">
                                    <div class="ajaxselectBoxBtn flex">
                                        <div class="textFlow" data-default="assign to" id="assign_to_text">assign to</div>
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
                                <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.contract.task.ajaxFetchemployee');
                                                                                ?>">
                                    <div class="ajaxselectBoxBtn flex">
                                        <div class="textFlow" data-default="select followers" id="followers_text">select followers</div>
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

    //reminder datatable

    // data table handler
    let closer_r = new WindowCloser();
    closer_r.init();

    let datatable_elem_r = document.querySelector("#datatable_reminder");

    let rows_per_page_r = new SelectBox(datatable_elem_r.querySelector(".tableFooter .selectBox"));
    rows_per_page_r.init();
    closer_r.register_shutdown(rows_per_page_r.shutdown, rows_per_page_r.get_container());

    let bulkaction_r = new SelectBox(datatable_elem_r.querySelector(".tableHeader .bulkaction"));
    bulkaction_r.init();
    closer_r.register_shutdown(bulkaction_r.shutdown, bulkaction_r.get_container());
    let config_r = JSON.parse('<?php echo $dt_r_config; ?>');
    let datatable_r = new DataTable(datatable_elem_r, config_r);
    datatable_r.init();

    rows_per_page_r.add_listener(datatable_r.rows_per_page_r, {});

    if (config_r['filters'] !== null && config_r['filters'] !== undefined) {
        let filter_1_r = document.getElementById("lead_filter_1");
        let filter_2_r = document.getElementById("lead_filter_2");

        let select_box_1_r = new SelectBox(filter_1);
        select_box_1_r.init();
        select_box_1_r.add_listener(datatable_r.filter, {
            type: "select",
            column: config_r['filters'][0]
        });
        closer_r.register_shutdown(select_box_1_r.shutdown, select_box_1_r.get_container());

        let select_box_2_r = new SelectBox(filter_2_r);
        select_box_2_r.init();
        select_box_2_r.add_listener(datatable_r.filter, {
            type: "select",
            column: config_r['filters'][1]
        });
        closer_r.register_shutdown(select_box_2_r.shutdown, select_box_2_r.get_container());
    }

    //add Task
    $(document).ready(function() {

        $.ajax({
            type: 'GET',
            url: '<?= url_to('erp.expense.task.relate_to') ?>',
            dataType: 'json',
            success: function(response) {
                console.log('Success fetching data: ', response);

                if (response && response.length > 0) {
                    var html = '<ul role="listbox">';

                    $.each(response, function(index, item) {
                        // console.log(item);
                        console.log(Object.values(item)[0]);
                        html += '<li role="option"  onclick="select_for_related(event)" data-value="' + Object.values(item)[0] + '">' + Object.values(item)[1] + '</li>';
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
            url: '<?= url_to('erp.expense.task.relate_to') ?>',
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
                console.log(formdataTask);
                $.ajax({
                    url: "<?php echo url_to("erp.expense.task.add", $id) ?>",
                    type: "post",
                    data: formdataTask,
                    success: function(response) {
                        console.log("response : ", response);
                        var alert_task = new ModalAlert();
                        if (response.success) {
                            location.reload();
                            $("#Task_modal").modal('hide');
                            $("#add_task_submit")[0].reset();
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

    //------update exist task modal-------//
    function update_task(id) {
        $("#Task_update_modal").modal("toggle");
        $.ajax({
            url: '<?php echo url_to("erp.expense.taskexist.data.fetch") ?>',
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
                $("#ajaxselectBox_Value_related_update").val(data.task.expense_id);
                $("#show_select_option_update").text(data.task.expense_name);
                $("#assign_to_text").text(data.task.assigness_name);
                $("#Assigned_to_update").val(data.task.assignees);
                $("#followers_text").text(data.task.Followers_name);
                $("#follower_update").val(data.task.followers);
                $("#task-description").val(data.task.task_description);
                $("#task_id_update").val(data.task.task_id);
            }
        });

        $("#update_task_submit").submit(function(event) {
            event.preventDefault();
            var status = $("#status_update").val();
            var Priority = $("#Priority_update").val();
            var Subject = $("#subject_update").val();
            var Start_Date = $("#start_update").val();
            var End_Date = $("#end_update").val();
            var Related = $("#ajaxselectBox_selectBox_Value_related_update").val();
            var assigned_to = $("#Assigned_to_update").val();
            var followers = $("#follower_update").val();
            if (status != "" && Priority != "" && Subject != "" && Start_Date != "" && End_Date != "" && Related != "" && followers != "" && assigned_to != "") {
                var formdataTaskupdate = $(this).serialize();
                $.ajax({
                    url: "<?php echo url_to("erp.expense.task.update") ?>",
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
    //-------update exist reminder data modal---------//
    function update_reminder(id) {
        $("#Reminder_update_modal").modal("toggle");
        $.ajax({
            url: "<?php echo url_to('erp.reminder.exist.modal.data') ?>",
            type: "POST",
            data: {
                id: id
            },
            success: function(response) {
                var data = JSON.parse(response);
                console.log(data);
                $("#reminder_id").val(data.id);
                $("#start_reminder_date_update").val(data.date);
                $("#reminder_follower").text(data.reminder_name);
                $("#reminder_to_update").val(data.employee_id);
                $("#reminder_decription_update").val(data.description);
                if (data.email == 1) {
                    $(".reminder_u_check").prop('checked', true);
                } else {
                    $(".reminder_u_check").prop('checked', false);
                }

            }
        })

        $("#reminder_update_form").submit(function(event) {
            event.preventDefault();
            $(".form_control").on("input", function(event) {
                if (event.target.value == " " || event.target.value == null || event.target.value == 0) {
                    event.target.parentElement.classList.add("error");
                    event.target.parentElement.querySelector("p").classList.remove('alert');
                } else {
                    event.target.parentElement.classList.remove("error");
                    event.target.classList.remove("subject");
                    event.target.parentElement.querySelector("p").classList.add('alert');
                }
            });

            var Reminder_Date = $("#start_reminder_date_update").val();
            var Reminder_To = $("#reminder_to_update").val();
            var Reminder_Description = $("#reminder_decription_update").val();
            if (!Reminder_Date) {
                $("#alert_date_u").removeClass("alert");
                $("#start_reminder_date_update").addClass("subject");
            } else if (!Reminder_To) {
                $("#alert_to_u").removeClass("alert");
                $(".ajaxselectBoxBtn.flex").addClass("subject");
            } else if (!Reminder_Description) {
                $("#alert_desc_u").removeClass("alert");
                $("#reminder_decription_update").addClass("subject");
            }else{
                var dataformreminder = $(this).serialize();
                console.log(dataformreminder);
                $.ajax({
                    url : "<?php echo url_to("erp.expense.reminder.update") ?>",
                    type : "POST",
                    data : dataformreminder,
                    success :function(response){
                        var alert_task = new ModalAlert();
                        if(response.success){
                            $("#Reminder_update_modal").modal("hide");
                            location.reload();
                            alert_task.invoke_alert("Reminder updated successfully", "success");
                        }
                        else{
                            $("#Reminder_update_modal").modal("hide");
                            $("#reminder_update_form")[0].reset();
                            alert_task.invoke_alert("Error", "error ");
                        }
                    }
                })
            }
        })
    }
    //----------delete reminder-----------//
    function delete_reminder(id){
        $.ajax({
            url : "<?php echo url_to("erp.expenses.reminder.delete") ?>",
            type :"POST",
            data : {id : id},
            success :function (response){
                var alert_task = new ModalAlert();
                if(response.success){
                    location.reload();
                    alert_task.invoke_alert("deleted successfully", "success");
                }
                else{
                    alert_task.invoke_alert("error", "error");
                }
            }
        })
    }
    //delete task
    function delete_task(id) {
        $.ajax({
            url: "<?php echo url_to("erp.expense.task.delete") ?>",
            type: "POST",
            data: {
                id: id
            },
            success: function(response) {
                var alert_task = new ModalAlert();
                if (response.success) {
                    location.reload();
                    alert_task.invoke_alert("Task deleted successfully", "success");
                } else {
                    alert_task.invoke_alert("error", "error");
                }
            }
        });
    }

    //attachment
    $("#file_uploder").on("change", function(event) {
        var fileName = $(this).val().split('\\').pop();
        $('#uploder_name').text('Selected file: ' + fileName);
    });

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

    //attchment form 
    $("#attachment_update").submit(function(event) {
        event.preventDefault();
        var attach = $("#file_uploder").val().split('\\').pop();
        if (attach != "") {
            this.submit();
        }

    });

    //Reminder moadl submit
    $(document).ready(function() {
        $("#reminder_form").submit(function(event) {
            event.preventDefault();
            $(".form_control").on("input", function(event) {
                if (event.target.value == " " || event.target.value == null || event.target.value == 0) {
                    event.target.parentElement.classList.add("error");
                    event.target.parentElement.querySelector("p").classList.remove('alert');
                } else {
                    event.target.parentElement.classList.remove("error");
                    event.target.classList.remove("subject");
                    event.target.parentElement.querySelector("p").classList.add('alert');
                }
            });

            var Reminder_Date = $("#start_reminder_date").val();
            var Reminder_To = $("#reminder_to").val();
            var Reminder_Description = $("#reminder_decription").val();
            if (!Reminder_Date) {
                $("#alert_date").removeClass("alert");
                $("#start_reminder_date").addClass("subject");
            } else if (!Reminder_To) {
                $("#alert_to").removeClass("alert");
                $(".ajaxselectBoxBtn.flex").addClass("subject");
            } else if (!Reminder_Description) {
                $("#alert_desc").removeClass("alert");
                $("#reminder_decription").addClass("subject");
            } else {
                var data_reminder = $(this).serialize();
                $.ajax({
                    url: "<?php echo url_to("erp.expense.reminder.add", $existing_data->id) ?>",
                    type: "POST",
                    data: data_reminder,
                    success: function(response) {
                        var alert_task = new ModalAlert();
                        if (response.success) {
                            location.reload();
                            $("#Reminder_modal").modal('hide');
                            $("#reminder_form")[0].reset();
                            alert_task.invoke_alert("Reminder added successfully", "success");
                        } else {
                            $("#Reminder_modal").modal('hide');
                            $("#reminder_form")[0].reset();
                            alert_task.invoke_alert("Error", "error");

                        }
                    }
                });
            }
        });
    })

    //Text editor
    // ClassicEditor
    //     .create(document.getElementById("customer_email_description"), {
    //         toolbar: {
    //             items: [
    //                 'heading',
    //                 '|',
    //                 'bold',
    //                 'italic',
    //                 'link',
    //                 'bulletedList',
    //                 'numberedList',
    //                 '|',
    //                 'undo',
    //                 'redo'
    //             ]
    //         },
    //         language: 'en'
    //     })
    //     .then(editor => {
    //         console.log(editor);
    //     })
    //     .catch(error => {
    //         console.error(error);
    //     });
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