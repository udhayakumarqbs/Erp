<div class="alldiv flex widget_title">
    <h3>Edit Payment</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.sale.payments'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>

<div class="alldiv" id="payment_addedit_modal">
    <?php echo form_open(url_to('erp.sale.payments.editPage', $sale_pay_id), array(
        "id" => "payment_addedit_form",
        "class" => "flex modal-scroll-form" )); ?>

    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Amount</label>
            <input type="text" class="form_control field-check" id="f_amount" name="amount" value="<?= $payment_data['amount']; ?>"/>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Paid On</label>
            <input type="date" class="form_control field-check" id="f_paid_on" name="paid_on" value="<?= $payment_data['paid_on']; ?>" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Payment Mode</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select mode"><?= $selected_payment_mode['name'] ?? 'Select Mode' ?></div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" id="f_payment_id" name="payment_id" value="<?= $selected_payment_mode['payment_id']; ?>">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($paymentmodes as $row) {
                    ?>
                        <li role="option" data-value="<?php echo $row['payment_id']; ?>" <?= $selected_payment_mode['name'] == $row['name'] ? 'selected' : '' ?>> <?php echo $row['name']; ?></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Transaction ID</label>
            <input type="text" class="form_control field-check" id="f_transaction_id" name="transaction_id" value="<?= $payment_data['transaction_id']; ?>" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Notes</label>
            <textarea class="form_control field-check" id="f_notes" name="notes"><?= $payment_data['notes']; ?></textarea>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group textRight ">
            <a class="btn outline-danger" href="<?= url_to('erp.sale.payments'); ?>">Cancel</a>
            <button class="btn bg-primary" type="button" id="payment_addedit_btn">Save</button>
        </div>
    </div>
    <?= form_close(); ?>
</div>


<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script type="text/javascript">
    let closer = new WindowCloser();
    closer.init();

    let alert = new ModalAlert();


    let payment_selectboxes = [];
    document.querySelectorAll("#payment_addedit_modal .selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        payment_selectboxes.push(selectbox);
        closer.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });


    
    let payment_form = document.getElementById("payment_addedit_form");
    let payment_validator = new FormValidate(payment_form);

    let payment_lock = false;
    document.getElementById("payment_addedit_btn").onclick = (evt) => {
        if (!payment_lock) {
            payment_lock = true;
            payment_validator.validate(
                (params) => {
                    payment_form.submit();
                    payment_lock = false;
                },
                (params) => {
                    payment_lock = false;
                }, {});
        }
    }
</script>