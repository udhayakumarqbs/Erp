<div class="alldiv flex widget_title">
    <h3>Credits Apply To Invoice</h3>
    <div class="title_right">
    </div>
</div>

<div class="alldiv">

    <?php
    echo form_open(url_to('erp.add.invocieapplyadd', $credit_id), array(
        "id" => "credit_apply_form",
        "class" => "flex"
    ));
    ?>

    <div class="form-width-2">
        <div class="form-group">
            <label class="form-label ">Credit Note</label>
            <input type="text" class="form_control field-check" value=<?= $credit_note['code'] ?> id="credit_note" readonly />
            <input type="hidden" value="<?= $credit_id; ?>" name="credit_id" />
            <p class="error-text"></p>
        </div>  
    </div>
    <div class="form-width-2">
        <div class="form-group  ">
            <label class="form-label">Invoice Code</label>
            <select class="form_control field-check" name="invoice_id" id="invoice_id" onchange=getInvoiceDetails(this.value)>
                <option value="select">select</option>
                <?php foreach ($invoice_code as $row) : ?>
                    <option value="<?= $row['invoice_id']; ?>"><?= $row['code']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-width-2 invoice-amount-div" style="display: none;">
        <div class="form-group">
            <label class="form-label">Total Invoice Amount</label>
            <input type="text" class="form_control field-check" id="invoice_amount" name="invoice_amount" readonly />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2 invoice-bal-amount-div" style="display: none;">
        <div class="form-group">
            <label class="form-label ">Invoice Balance Amount</label>
            <input type="text" class="form_control field-check" id="invoice_bal_amount" name="invoice_bal_amount" readonly />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group">
            <label class="form-label ">Total Credit Amount</label>
            <input type="text" class="form_control field-check" name="credit_amount" value=<?= number_format($credit_note['other_charge'],2,'.',',') ?> readonly />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group">
            <label class="form-label ">Available Credit's Amount</label>
            <input type="text" class="form_control field-check" id="avail_credit_amount" value=<?=  number_format(($credit_note['other_charge'] - $balance_amount['amount'])  ??  $credit_note['other_charge'],2,'.',',') ?> readonly />
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
            <a class="btn outline-danger" href="<?= url_to('erp.sale.creditnotesview', $credit_id); ?>">Cancel</a>
            <button class="btn bg-primary" type="button" id="credit_apply_btn">Save</button>
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

    let form = document.getElementById("credit_apply_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("credit_apply_btn").onclick = function(evt) {
        if (!lock) {
            
            let invoiceId = document.getElementById("invoice_id").value;
            let amountCredit = document.getElementById("amount_credit").value;

            if (invoiceId === 'select') {
            alert.invoke_alert("Invoice Code Field is required.", "error");
            } else if (amountCredit.trim() === '') {
                alert.invoke_alert("Enter the Credit Amount.", "error");
            } else {

            let enteredCreditAmount = parseFloat(document.getElementById("amount_credit").value);
            let availableCreditAmount = parseFloat(document.getElementById("avail_credit_amount").value.replace(/,/g, ''));
            console.log(enteredCreditAmount);
            console.log(availableCreditAmount);
            // return;
            if (enteredCreditAmount > availableCreditAmount) {
                alert.invoke_alert("Entered credit amount cannot exceed available credit amount.", "error");
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

    function getInvoiceDetails(invoice_id) {
        var selected_inv_id = invoice_id;
        console.log(selected_inv_id);
        $.ajax({
            type: 'POST',
            url: '<?= url_to('creditapply.invoice'); ?>',
            data: {
                selected_inv_id: selected_inv_id,
            },
            success: function(data) {
                console.log(data);
                var amountWithTax = numberFormat(data[0].amount_with_tax);
                var remainingAmount = numberFormat(data[0].remaining_amount);
                $('#invoice_amount').val(amountWithTax);
                $('#invoice_bal_amount').val(remainingAmount);
                if (selected_inv_id !== 'select') {
                    $('.invoice-amount-div').show();
                    $('.invoice-bal-amount-div').show();
                } else {
                    $('.invoice-amount-div').hide();
                    $('.invoice-bal-amount-div').hide();
                }
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