<div class="alldiv flex widget_title">
    <h3>Create Credit Note</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.sale.invoice'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>

<div class="alldiv">
    <?php
    echo form_open(url_to('invoice.creditnote.add', $invoice_id) , array(
        "id" => "credit_add_form",
        "class" => "flex"
    ));
    ?>
    <div class="form-width-2">
        <div class="form-group field-ajax " data-ajax-url="<?=url_to('erp.invoice.add.creditnote.code.ajax').'?'; ?>">
            <label class="form-label">Code</label>
            <input type="text" name="code" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Credit Note Date</label>
            <input type="date" name="issued_date" value="<?php echo date("Y-m-d"); ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-money">
            <label class="form-label">Other Charge</label>
            <input type="text" id="credit_other_charge" name="other_charge" value="0.00" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Payment Terms</label>
            <input type="text" name="payment_terms" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group field-required">
            <label class="form-label">Terms and condition</label>
            <textarea rows="3" name="terms_condition" class="form_control field-check"></textarea>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group">
            <label class="form-label">Remarks</label>
            <textarea rows="3" name="remarks" class="form_control field-check"></textarea>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="widget_title">
            <h3>Items</h3>
        </div>
    </div>
    <div class="form-width-1">
        <table class="table">
            <thead>
                <th>SNo</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Amount</th>
                <th>Action</th>
            </thead>
            <tbody id="credit_items_holder">
                <?php
                $sno = 1;
                foreach ($invoice_items as $row) {
                ?>
                    <tr>
                        <td><?php echo $sno; ?></td>
                        <td><span><?php echo $row['product']; ?></span><input type="hidden" name="product_id[<?php echo $sno; ?>]" value="<?php echo $row['related_id']; ?>" /></td>
                        <td>
                            <div class="form-group">
                                <input type="text" name="quantity[<?php echo $sno; ?>]" value="<?php echo $row['quantity']; ?>" data-max-qty="<?php echo $row['quantity']; ?>" class="form_control field-check credit-qty" />
                                <p class="error-text"></p>
                            </div>
                        </td>
                        <td><span><?php echo $row['unit_price']; ?></span><input type="hidden" name="unit_price[<?php echo $sno; ?>]" value="<?php echo $row['unit_price']; ?>" /></td>
                        <td><span><?php echo $row['amount']; ?></span></td>
                        <td><button type="button" class="btn bg-danger product-remove-btn"><i class="fa fa-trash"></i></button>
                    </tr>
                <?php
                    $sno++;
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><b>Subtotal</b></td>
                    <td id="credit_subtotal">0.00</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><b>Total</b></td>
                    <td id="credit_total">0.00</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight ">
            <a class="btn outline-danger" href="<?= url_to('erp.sale.invoice'); ?>">Cancel</a>
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
    calculateSubtotal();
    calculateTotal();
    let credit_qty = document.querySelectorAll("#credit_items_holder .credit-qty");

    let form = document.getElementById("credit_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("credit_add_btn").onclick = function(evt) {
        if (!lock) {
            lock = true;
            validator.validate(
                (params) => {
                    let qtys = document.querySelectorAll("#credit_items_holder .credit-qty");
                    let proceed = true;
                    for (let i = 0; i < credit_qty.length; i++) {
                        let val = parseInt(credit_qty[i].value);
                        let max_qty = parseInt(credit_qty[i].getAttribute("data-max-qty"));
                        if (isNaN(val) || (val <= 0) || (val > max_qty)) {
                            alert.invoke_alert("Invalid quantity", "error");
                            proceed = false;
                            break;
                        }
                    }
                    if (proceed) {
                        form.submit();
                    }
                    lock = false;
                },
                (params) => {
                    lock = false;
                }, {});
        }
    }

    document.getElementById("credit_items_holder").onclick = (evt) => {
        let target = evt.target;
        document.querySelectorAll("#credit_items_holder .product-remove-btn").forEach((item) => {
            if (item.contains(target)) {
                item.parentElement.parentElement.remove();
                calculateSubtotal();
                calculateTotal();
            }
        });
    }


    credit_qty.forEach((item) => {
        item.onchange = (evt) => {
            let max_qty = parseInt(item.getAttribute("data-max-qty"));
            let value = parseInt(item.value);
            if (isNaN(value)) {
                alert.invoke_alert("Invalid quantity", "error");
                return;
            }
            if (value <= 0) {
                alert.invoke_alert("Invalid quantity", "error");
                return;
            }
            if (value > max_qty) {
                alert.invoke_alert("Quantity can't be greater than sold limit", "error");
                return;
            }
            calculateSubtotal();
            calculateTotal();
        }
    });

    document.getElementById("credit_other_charge").onchange = (evt) => {
        let trans_charge = evt.target.value;
        let pattern = /^[0-9]+\.[0-9]{2}$/;
        if (!pattern.test(trans_charge)) {
            alert.invoke_alert("Invalid Charge value", "error");
        }
        calculateSubtotal();
        calculateTotal();
    };

    function calculateSubtotal() {
        let amounts = document.querySelectorAll("#credit_items_holder tr td:nth-child(5) span");
        let unit_price = document.querySelectorAll("#credit_items_holder tr td:nth-child(4) span");
        let qty = document.querySelectorAll("#credit_items_holder tr td:nth-child(3) .credit-qty");
        let total = 0.00;
        if (unit_price.length != 0) {
            for (let i = 0; i < unit_price.length; i++) {
                let amt = parseFloat(unit_price[i].textContent) * parseInt(qty[i].value);
                total += amt;
            }
        }
        document.getElementById("credit_subtotal").textContent = total.toFixed(2);
    };

    function calculateTotal() {
        let amount = parseFloat(document.getElementById("credit_subtotal").textContent);
        let other_charge = parseFloat(document.getElementById("credit_other_charge").value);
        let total = 0.00;
        if (!isNaN(amount)) {
            total += amount;
        }
        if (!isNaN(other_charge)) {
            total -= other_charge;
        }
        document.getElementById("credit_total").textContent = total.toFixed(2);
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