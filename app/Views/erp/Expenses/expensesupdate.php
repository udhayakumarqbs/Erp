<div class="alldiv flex widget_title">
    <h3>Expenses Update</h3>
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
        width: 10%;
        padding: 7px 11px;
        height: 100%;
        font-size: 14px;
        line-height: 1.36000;
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
        display: inline-block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form-control-text-area {
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


    .date-start,
    .date-end {
        padding: 0;
        width: 50%;
    }

    .date-start,
    .notification-group {
        padding: 0px 10px 0px 0px;
    }

    .btn-group.error input,
    .btn-group.error select,
    .ajaxselectBox.flex.error input,
    .selectBoxBtn.flex.error {
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

    .ajaxselectBoxBtn.flex.subject.activate {
        width: 100%;
        display: flex;
        padding: 0;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #C83B3B !important;
    }

    .selectBoxBtn.flex.subject {
        width: 100%;
        display: flex;
        padding: 0;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #C83B3B !important;
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
        margin-left: -5px;
        z-index: 5;
        /* padding: 7px 13px; */
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

    .row {
        z-index: 1;
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

    .customer_cont_dropdown {
        overflow-y: scroll;
        height: 200px;
    }

    .checkbox.hide {
        display: none;
    }

    .field-required.hide {
        display: none;
    }
</style>

<div class="alldiv">
    
    <?php echo form_open(url_to('erp.expenses.update.view', $id), array(
        // "class" => "flex",
        "id" => "expense_update",
        "enctype" => "multipart/form-data"
    ));
    ?>

    <div class="row p-1">
        <div class="col-lg-6 col-md-12">
            <h3 class="p-1 mt-2 mb-2">Add new Expense of</h3>
            <div class="form-width-3 form-control">
                <div class="form-group field-required">
                    <?php if (isset($exist_attachment)) { ?>
                        <!-- No File -->
                        <div class="flex justify-content-between p-2 mt-1 align-items-center">
                            <div>
                                <a target="_BLANK" download class="text-primary" href='<?php echo "" . get_attachment_link('Expenses_Attachment') . $exist_attachment->filename ?>'> <?php echo $exist_attachment->filename ?> </a>
                            </div>
                            <a class="del-confirm" href="<?php echo url_to("expense.attachment.recipt.delete", $exist_attachment->attach_id, $existing_data->id) ?>"><i class="fa-solid fa-x fa-xs" style="color: #ff0000;"></i></a>
                        </div>
                    <?php } else { ?>
                        <!-- Attach Receipt -->
                        <div>
                            <div>
                                <!-- file uploder -->
                                <div class="file-uploader-frame">
                                    <input type="file" class="file-uploader-input" name="attachment" id="file_uploder" data-file="#uploder_name" />
                                    <div class="file-uploader-box">
                                        <span class="file-uploader-text">Attach Receipt</span>
                                    </div>
                                </div>
                                <div>
                                    <p class="file_uploder_name" id="uploder_name"></p>
                                </div>
                                <!--  -->
                            </div>
                        </div>
                    <?php } ?>
                    <!-- Name -->
                    <label class="form-label">Name
                        <small class="req text-danger">*</small>
                    </label>
                    <div class="btn-group" role="group">
                        <input type="text" class="form-control" id="name_id" name="name" value="<?= $existing_data->name ?>">
                        <p class="alert text-danger p-0 mb-1" id="alert_name"><?php echo "This field is required."; ?></p>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="form-label"><?php echo "Note"; ?></label>
                        <?php echo form_textarea('ExpensesNotes', $existing_data->exp_note, 'class ="form-control-text-area"'); ?>
                        <p></p>
                    </div>

                    <!--  Expenses catagories -->
                    <label class="form-label">Expense Category</label>
                    <div role="group w-100">
                        <div class="form-group field-required d-flex align-items-center">
                            <div class="ajaxselectBox poR">
                                <div class="ajaxselectBoxBtn flex">
                                    <?php $catagories = isset($existing_data->cat_name) ? $existing_data->cat_name : "select related"; ?>
                                    <div class="textFlow" id="show_select_option" data-default="select expense_id"><?php echo $catagories ?></div>
                                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                                    <button class="drops" id="custom_drops" type="button"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" class="ajaxselectBox_Value field-check" name="related_id" id="ajaxselectBox_Value_related" value="<?= $existing_data->cat_id ?>">
                                </div>
                                <div class="ajaxselectBox_Container alldiv" id="expenseData">
                                    <input type="text" class="ajaxselectBox_Search form_control" />
                                    <ul role="listbox">

                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="plus btn bg-primary" data-toggle="modal" data-target="#ExpensesCategory_modal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="100%" fill="white" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                </svg>
                            </button>

                            <p class="error-text"></p>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="btn-group">
                        <label class="form-label btn-group">
                            <?php echo "Expense Date"; ?>
                            <small class="req text-danger">*</small>
                        </label>
                        <input type="date" name="expense_date" value="<?= $existing_data->date ?>" id="expense-date" class="form-control">
                        <p class="alert text-danger p-0" id="alert_date"><?php echo 'This field is required.'; ?></p>
                    </div>


                    <!--  Amount -->
                    <label class="form-label"> Amount
                        <small class="req text-danger">*</small>
                    </label>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <div class="d-flex">
                            <input type="number" class="form-control-btn mb-1" id="amount_id" name="amount" value="<?= $existing_data->amount ?>">
                            <label class="rupees">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
                                    <path d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z" />
                                </svg>
                            </label>
                        </div>
                        <p class="alert text-danger p-0" id="alert_amount"><?php echo 'This field is required.'; ?></p>
                    </div>
                    <?php if ($existing_data->customer != null) { ?>
                        <div class="tw-flex tw-justify-between tw-items-center mb-2 mt-2 checkbox" id="check_box_id">
                            <div class="checkbox checkbox-primary checkbox-inline">
                                <input type="checkbox" name="billable" id="billable" <?php echo $existing_data->billable == 1 ? "checked" : "" ?>>
                                <label for="trash"><?php echo 'Billable'; ?></label>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="tw-flex tw-justify-between tw-items-center mb-2 mt-2 checkbox hide" id="check_box_id">
                            <div class="checkbox checkbox-primary checkbox-inline">
                                <input type="checkbox" name="billable" id="billable">
                                <label for="trash"><?php echo 'Billable'; ?></label>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- Customer -->
                    <div class="form-group field-required">
                        <label class="form-label">Customer</label>
                        <div class="selectBox poR">
                            <div class="selectBoxBtn flex" id="ajax-form-customer">
                                <?php $customer_name = isset($existing_data->customer) ? $existing_data->customer : "select customer" ?>
                                <div class="textFlow" data-default="<?= $customer_name ?>"><?php echo $customer_name ?></div>
                                <button class="close" type="button"><i class="fa fa-close"></i></button>
                                <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" class="selectBox_Value field-check" name="dropdown_customer_type" value="<?= $existing_data->customer_id ?>" id="customer_type">
                            </div>
                            <ul role="listbox" class="selectBox_Container related alldiv customer_cont_dropdown">
                                <?php
                                foreach ($customers as $value) {
                                ?>
                                    <li role="option" data-value="<?php echo $value["cust_id"]; ?>"><?php echo $value["company"]; ?></li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <p class="alert text-danger p-0" id="alert_customer"><?php echo 'This field is required.'; ?></p>
                    </div>


                    <!-- project -->
                    <?php if ($existing_data->project_name != null) { ?>
                        <div class="form-group field-required" id="project_cont_id">
                            <div class="selectBox poR">
                                <?php $project_name = isset($existing_data->project_name) ? $existing_data->project_name : "--Select--" ?>
                                <label class="form-label related-text">Project</label>
                                <div class="selectBoxBtn flex">
                                    <div class="textFlow" id="show_select_project" data-default="<?= $project_name ?>"><?= $project_name ?></div>
                                    <button class="close projectclose" type="button"><i class="fa fa-close"></i></button>
                                    <button class="drops" id="project_drops" type="button"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" class="selectBox_Value field-check" name="project_id" id="selectBox_Value_project" value="<?= $existing_data->project_id ?>">
                                </div>
                                <div class="selectBox_Container alldiv" id="projectData">
                                    <input type="text" class="selectBox_Search form_control" />
                                    <ul role="listbox">

                                    </ul>
                                </div>
                            </div>
                            <p class="alert text-danger p-0" id="alert_subject"><?php echo "This field is required."; ?></p>
                        </div>
                    <?php } else { ?>
                        <div class="form-group field-required hide" id="project_cont_id">
                            <div class="selectBox poR">
                                <label class="form-label related-text">Project</label>
                                <div class="selectBoxBtn flex">
                                    <div class="textFlow" id="show_select_project" data-default="select project">select project</div>
                                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                                    <button class="drops" id="project_drops" type="button"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" class="selectBox_Value field-check" name="project_id" id="selectBox_Value_project" value="">
                                </div>
                                <div class="selectBox_Container alldiv" id="projectData">
                                    <input type="text" class="selectBox_Search form_control" />
                                    <ul role="listbox">

                                    </ul>
                                </div>
                            </div>
                            <p class="alert text-danger p-0" id="alert_subject"><?php echo "This field is required."; ?></p>
                        </div>
                    <?php } ?>


                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <h3 class="p-1 mt-2 mb-2">Advanced Options</h3>
                <!-- Tax -->
                <div class="d-flex justify-content-evenly mt-3">
                    <!-- tax 1 -->
                    <div class="form-width-2">
                        <?php $tax_1_name = isset($existing_data->tax_1_per) ? $existing_data->tax_1_name . "" . $existing_data->tax_1_per . "%" : "select Tax"  ?>
                        <?php $tax_1_val = $existing_data->tax_1 > 0 ? $existing_data->tax_1 : ""  ?>
                        <label class="form-label">Tax 1
                        </label>
                        <div class="form-group field-required">
                            <div class="selectBox poR">
                                <div class="selectBoxBtn flex" id="ajax-form-tax-1">
                                    <div class="textFlow" id="tax-1-text" data-default="<?= $tax_1_name ?>"><?= $tax_1_name ?></div>
                                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" class="selectBox_Value field-check" name="tax1" value="<?= $tax_1_val ?>" id="tax_1">
                                </div>
                                <ul role="listbox" class="selectBox_Container alldiv">
                                    <?php
                                    foreach ($tax_data as $t) {
                                    ?>
                                        <li role="option" data-value="<?php echo $t["tax_id"]; ?>"><?php echo $t["tax_name"] . ' ' . $t['percent'] . "%"; ?></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <p class="alert text-danger p-0" id="alert_tax_1"><?php echo 'This field is required.'; ?></p>
                        </div>
                    </div>
                    <!-- tax 2 -->
                    <div class="form-width-2 ">
                        <?php $tax_2_name = isset($existing_data->tax_2_per) ? $existing_data->tax_2_name . "" . $existing_data->tax_2_per . "%" : "select Tax"  ?>
                        <?php $tax_2_val = $existing_data->tax_2 > 0 ? $existing_data->tax_2 : ""  ?>
                        <label class="form-label">Tax 2
                        </label>
                        <div class="form-group field-required">
                            <div class="selectBox poR">
                                <div class="selectBoxBtn flex" id="ajax-form-tax-2">
                                    <div class="textFlow" data-default="select Tax" id="tax-2-text"><?= $tax_2_name ?></div>
                                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" class="selectBox_Value field-check" name="tax2" value="<?= $tax_2_val ?>" id="tax_2">
                                </div>
                                <ul role="listbox" class="selectBox_Container alldiv">
                                    <?php
                                    foreach ($tax_data as $t) {
                                    ?>
                                        <li role="option" data-value="<?php echo $t["tax_id"]; ?>"><?php echo $t["tax_name"] . ' ' . $t['percent'] . "%"; ?></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <p class="alert text-danger p-0" id="alert_tax_2"><?php echo 'This field is required.'; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Payment -->
                <div>
                    <!-- Payment Mode -->
                    <div class="btn-group mt-3">
                        <div class>
                            <label class="form-label btn-group">
                                <?php echo "Payment Mode"; ?>
                            </label>
                            <div class="form-group field-required">
                                <?php $payment_mode = isset($existing_data->payment) ? $existing_data->payment : "select Pay Mode"  ?>
                                <?php $payment_id = $existing_data->payment_id > 0 ? $existing_data->payment_id : ""  ?>
                                <div class="selectBox poR">
                                    <div class="selectBoxBtn flex">
                                        <div class="textFlow" data-default="<?= $payment_mode ?>" id="payment-text"><?= $payment_mode ?></div>
                                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                        <input type="hidden" class="selectBox_Value field-check" name="payment" value="<?= $payment_id  ?>" id="payment_mode">
                                    </div>
                                    <ul role="listbox" class="selectBox_Container alldiv">
                                        <?php
                                        foreach ($payment_modes as $p) {
                                        ?>

                                            <li role="option" data-value="<?php echo $p["payment_id"]; ?>"><?php echo $p["name"]; ?></li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <p class="error-text"></p>
                            </div>
                        </div>
                    </div>
                    <!-- Reference  -->
                    <div class="btn-group mt-3">
                        <?php $refrence = isset($existing_data->refrence) ? $existing_data->refrence : "" ?>
                        <label class="form-label btn-group">
                            <?php echo "Reference #"; ?>
                        </label>
                        <input type="text" name="refrence" class="form_control" id="refrence_id" value="<?= $refrence ?>">
                    </div>
                </div>
            </div>

        </div>
        <div class="panel-footer " style="width : 97%">
            <div class="form-group textRight d-flex justify-content-end ">
                <a href="<?php echo url_to('erp.contractview'); ?>" class="btn outline-secondary m-1">Cancel</a>
                <button class="btn bg-primary m-1" type="submit" id="announcement_add_submit">Save</button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
    <!-- footer -->
    <!-- modal -->
    <div class="modal fade" id="ExpensesCategory_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title" id="exampleModalLabel">New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                            </svg></span>
                    </button>
                </div>
                <hr>
                <?php echo form_open(url_to('erp.expenses.type.add'), array(
                    "class" => "flex",
                    "id" => "ExpensesCategory_modal_add"
                ));
                ?>
                <div class="modal-body">
                    <div class="form-group field-required mb-5">
                        <label class="form-label" id="form-alert"> Category Name
                            <small class="req text-danger">*</small>
                        </label>
                        <input type="text" name="Category_Name" class="form_control_modal" id="expense_type_name">
                        <p class="alert_ct text-danger p-0" id="alert_ct"><?php echo 'This field is required.'; ?></p>
                    </div>
                    <div class="form-group field-required mb-5">
                        <label class="form-label" id="form-alert"> Category Description
                        </label>
                        <input type="text" name="Category_Description" class="form-control-text-area" id="expenses_type_Description">
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
    <!--  -->

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
    //
    function customer_api() {

        $.ajax({
            type: 'GET',
            url: '<?= url_to('erp.fetchexpensescategories') ?>',
            dataType: 'json',
            success: function(response) {
                // console.log('Success fetching data: ', response);

                if (response && response.length > 0) {
                    var html = '<ul role="listbox">';

                    $.each(response, function(index, item) {
                        // console.log(item);
                        console.log(Object.values(item)[0]);
                        html += '<li role="option" onclick="select_for_related(event)" data-value="' + Object.values(item)[0] + '">' + Object.values(item)[1] + '</li>';
                    });

                    html += '</ul>';

                    $('#expenseData').html(html);

                }

                $(".selectBox_Container.related").click();
            },
            error: function(error) {
                console.error('Error fetching data: ', error);
            }
        });

    };


    function select_for_related(event) {
        console.log(event.target.dataset.value);
        $('#expenseData').css('display', 'none');
        $('#custom_drops').removeClass('active');

        // $('#relatedData').css('display','block')
        document.getElementById('show_select_option').innerHTML = event.target.innerHTML;
        document.getElementById('ajaxselectBox_Value_related').value = event.target.dataset.value;

    }

    //CUSTOMER
    $(document).ready(function() {

        $('.selectBox_Container.related').on('click', 'li', function() {
            $("#project_cont_id").removeClass("hide");
            $("#check_box_id").removeClass("hide");
            $("#show_select_project").html("select project");
          
            $("#selectBox_Value_project").val("");



            var selectedOption = $(this).data('value');

            projectapicall(selectedOption);
            
        });

        let existcustomerdata = $("#customer_type").val();
        if(existcustomerdata){
            projectapicall(existcustomerdata);
        }
    });

    function projectapicall(selectedOption){
        $.ajax({
                type: 'GET',
                url: '<?= url_to('erp.expenses.project.fetch') ?>',
                data: {
                    id: selectedOption
                },
                dataType: 'json',
                success: function(response) {
                    console.log('project', response);

                    if (response && response.length > 0) {
                        var html = '<ul role="listbox">';

                        $.each(response, function(index, item) {
                            // console.log(item);
                            console.log(Object.values(item)[0]);
                            html += '<li role="option" onclick="select_for_project(event)" data-value="' + Object.values(item)[0] + '">' + Object.values(item)[1] + '</li>';
                        });

                        html += '</ul>';

                        $('#projectData').html(html);
                    } else {
                        $('#show_select_project').html('<p>No data available.</p>');
                    }
                },
                error: function(error) {
                    console.error('Error fetching data: ', error);
                }
            });
    }

    let relatedData_open = true;

    function select_for_project(event) {
        console.log(event.target.dataset.value);
        $('#projectData').css('display', 'none');
        $('#project_drops').removeClass('active');

        // $('#relatedData').css('display','block')
        document.getElementById('show_select_project').innerHTML = event.target.innerHTML;
        document.getElementById('selectBox_Value_project').value = event.target.dataset.value;

    }


    $("#file_uploder").on("change", function(event) {
        var fileName = $(this).val().split('\\').pop();
        $('#uploder_name').text('Selected file: ' + fileName);
    });

    //DROP DOWN 
    let closer_drop = new WindowCloser();
    closer_drop.init();

    document.querySelectorAll(".selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        closer_drop.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer_drop.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });


    //form submition 
    $(document).ready(function() {
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
        $(".form-control-btn").on("input", function(event) {
            if (event.target.value == " " || event.target.value == null || event.target.value == 0) {
                event.target.parentElement.parentElement.lassList.add("error");
                event.target.parentElement.parentElement.querySelector("p").classList.remove('alert');
            } else {
                event.target.parentElement.parentElement.classList.remove("error");
                event.target.classList.remove("subject");
                event.target.parentElement.parentElement.querySelector("p").classList.add('alert');
            }
        });
        $("#expense_update").submit(function(event) {
            event.preventDefault();
            var name = $("#name_id").val();
            var date = $("#expense-date").val();
            var amount = $("#amount_id").val();
            if (!name) {
                $("#name_id").addClass("subject");
                $("#alert_name").removeClass("alert");
            } else if (!date) {
                $("#expense-date").addClass("subject");
                $("#alert_date").removeClass("alert");
            } else if (!amount) {
                $("#amount_id").addClass("subject");
                $("#alert_amount").removeClass("alert");
            }else {
                this.submit();
            }
        });
    });
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
        $("#ExpensesCategory_modal_add").submit(function(event) {
            event.preventDefault();
            var name = $("#expense_type_name").val();
            var description = $("#expenses_type_Description").val();
            if (!name) {
                $("#expense_type_name").addClass("subject");
                $("#alert_ct").removeClass("alert_ct");
            } else {
                var formdata = $(this).serialize();
                $.ajax({
                    url: "<?php echo url_to('erp.expenses.type.add') ?>",
                    type: "POST",
                    data: formdata,
                    success: function(response) {
                        var alert = new ModalAlert();
                        if (response.success) {
                            customer_api();
                            $("#ExpensesCategory_modal").modal('hide');
                            $("#ExpensesCategory_modal_add")[0].reset();
                            alert.invoke_alert("Added Successfully", "success");
                        } else {
                            customer_api();
                            $("#ExpensesCategory_modal").modal('hide');
                            $("#ExpensesCategory_modal_add")[0].reset();
                            alert.invoke_alert("Error occured", "error");
                        }
                    },
                    error: function(xhr, target, error) {
                        console.error("Oops", error);
                    }
                });
            }
        })
    });
    customer_api();
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