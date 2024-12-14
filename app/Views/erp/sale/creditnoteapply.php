<div class="alldiv flex widget_title">
    <h3>Credit Notes Apply</h3>
    <div class="title_right">
    </div>
</div>

<div class="alldiv">
    <?php
    echo form_open(url_to('erp.add.creditnoteapplyadd', $invoice_id), array(
        "id" => "credit_add_form",
        "class" => "flex"
    ));
    ?>

    <div class="form-width-2">
        <div class="form-group">
            <label class="form-label ">Invoice Number</label>
            <input type="text" class="form_control field-check" value=<?= $invoice_code ?> id="invoice_id" readonly />
            <input type="hidden" value="<?= $invoice_id; ?>" name="invoice_id" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group  ">
            <label class="form-label">Credit Notes</label>
            <select class="form_control field-check" name="credit_id" id="credit_id" onchange=getCreditDetails(this.value)>
                <option value="select">select</option>
                <?php foreach ($credit_note as $row) : ?>
                    <option value="<?= $row['credit_id']; ?>"><?= $row['code']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-width-2">
        <div class="form-group total_credit_amount-div" style="display: none;">
            <label class="form-label ">Total Credit Amount</label>
            <input type="text" class="form_control field-check" id="total_credit_amount" name="credit_amount" readonly />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group  avail_credit_amount-div" style="display: none;">
            <label class="form-label ">Available Credit's Amount</label>
            <input type="text" class="form_control field-check" id="avail_credit_amount" readonly />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group">
            <label class="form-label">Total Invoice Amount</label>
            <input type="text" class="form_control field-check" name="invoice_amount" value="<?= number_format($invoice_amount['amount_with_tax'], 2, '.', ','); ?>" readonly />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group">
            <label class="form-label ">Invoice Balance Amount</label>
            <input type="text" class="form_control field-check" id= "invoice_bal_amount" name="invoice_bal_amount" value="<?= number_format($invoice_amount['remaining_amount'], 2, '.', ','); ?>" readonly />
            <p class="error-text"></p>
        </div>
    </div>

    <div class="form-width-2">
        <div class="form-group">
            <label class="form-label ">Amount to credit</label>
            <input type="text" class="form_control field-check" id="amount_credit" name="amount_credit" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight ">
            <a class="btn outline-danger" href="<?= url_to('erp.invoice.manage.payandcredit', $invoice_id); ?>">Cancel</a>
            <button class="btn bg-primary" type="button" id="credit_add_btn">Save</button>
        </div>
    </div>
    <?php
    echo form_close();
    ?>
</div>


<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>

<script type="text/javascript">
    let closer = new WindowCloser();
    let alert = new ModalAlert();
    closer.init();

    let form = document.getElementById("credit_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("credit_add_btn").onclick = function(evt) {
        if (!lock) {

            let creditId = document.getElementById("credit_id").value;
            let amountCredit = document.getElementById("amount_credit").value;

            if (creditId === 'select') {
            alert.invoke_alert("Credit Note Field is required.", "error");
            } else if (amountCredit.trim() === '') {
                alert.invoke_alert("Enter the Credit Amount.", "error");
            } else {

            let enteredCreditAmount = document.getElementById("amount_credit").value;
            let availableCreditAmount = document.getElementById("avail_credit_amount").value;
            let invoiceBalanceAmount = parseFloat(document.getElementById("invoice_bal_amount").value.replace(/,/g, '').replace(/\..*/, ''));
            console.log('entered credit',enteredCreditAmount);
            console.log('available credit',availableCreditAmount);
            console.log('available invoice balance',invoiceBalanceAmount);
          
            if (enteredCreditAmount > availableCreditAmount) {
                alert.invoke_alert("Entered credit amount cannot exceed available credit amount.", "error");
                return;
            }
            
            if (enteredCreditAmount > invoiceBalanceAmount) {
                alert.invoke_alert("Entered credit amount cannot exceed Invoice Balance amount.", "error");
                return;
            }

            lock = true;
            validator.validate(
                (params) => {
                    form.submit();
                    lock = false;
                },
                (params) => {
                    lock = false;
                }, {});
        }
    }
    }

    function numberFormat(value) {
        return parseFloat(value).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function getCreditDetails(credit_id) {
        var selected_credit_id = credit_id;
        console.log(selected_credit_id);
        $.ajax({
            type: 'POST',
            url: '<?= url_to('creditdata.invoice'); ?>',
            data: {
                selected_credit_id: selected_credit_id,
            },
            success: function(data) {
                // console.log(data);
                var totalCreditAmount = parseFloat(data.total_credit);
                var remainingAmount = parseFloat(data.remaining_credit_amount);
                var enteredAmount = parseFloat($('#amount_credit').val());
                var formattedTotalCreditAmount = numberFormat(totalCreditAmount);
                var formattedRemainingAmount = remainingAmount;
                $('#total_credit_amount').val(formattedTotalCreditAmount);
                $('#avail_credit_amount').val(formattedRemainingAmount);

                if (selected_credit_id !== 'select') {
                    $('.total_credit_amount-div').show();
                    $('.avail_credit_amount-div').show();
                } else {
                    $('.total_credit_amount-div').hide();
                    $('.avail_credit_amount-div').hide();
                }
                // }
            },
            error: function(error) {
                console.log(error);
            },
        });
    }



    <?php
    if (session()->getFlashdata("op_success")) { ?>
        alert.invoke_alert("<?= session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        alert.invoke_alert("<?= session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>