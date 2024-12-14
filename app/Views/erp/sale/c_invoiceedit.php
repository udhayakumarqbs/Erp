<div class="alldiv flex widget_title">
    <h3>Update Sale Invoice</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.sale.invoice'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>

<div class="alldiv">

    <form action="<?= url_to('erp.sale.invoice.edit.post', $invoice_id) ?>" method="POST" class="flex" id="invoice_edit_form">
        <div class="form-width-2">
            <div class="form-group field-ajax " data-ajax-url="<?= url_to('sale.invoice.add.fetchcode') . '?id=' . $invoice_id . '&'; ?>">
                <label class="form-label">Invoice Code</label>
                <input type="text" name="code" value="<?php echo $invoice->code; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Invoice Date</label>
                <input type="date" name="invoice_date" value="<?php echo $invoice->invoice_date; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Customer</label>
                <div class="ajaxselectBox poR" id="customer_ajax_select_box" data-ajax-url="<?= url_to('erp.sale.getCustomer'); ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="select customer"><?php echo $invoice->name; ?></div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="ajaxselectBox_Value field-check" name="cust_id" value="<?php echo $invoice->cust_id; ?>">
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

        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Invoice Expiry</label>
                <input type="date" name="invoice_expiry" value="<?php echo $invoice->invoice_expiry; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Payment Terms</label>
                <input type="text" name="payment_terms" value="<?php echo $invoice->payment_terms; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Terms and condition</label>
                <textarea rows="3" name="terms_condition" class="form_control field-check"><?php echo $invoice->terms_condition; ?></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="widget_title">
                <h3>Add Units</h3>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group" id="invoice_property">
                <label class="form-label">Property</label>
                <div class="ajaxselectBox poR" data-ajax-url="<?= url_to('erp.sale.orders.ajaxfetchproperties'); ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="select property">select property</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="ajaxselectBox_Value field-check" value="">
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
            <div class="form-group" id="invoice_unit" data-ajax-url="<?= url_to('erp.sale.orders.ajaxfetchpropertyunits'); ?>">
                <label class="form-label">Unit</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select unit">select unit</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">

                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
            <input type="hidden" id="invoice_unit_amount" />
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <label class="form-label"></label>
                <button class="btn outline-primary" type="button" id="add_item_btn">Add Unit</button>
            </div>
        </div>
        <div class="form-width-1">
            <table class="table">
                <thead>
                    <th>SNo</th>
                    <th>Property</th>
                    <th>Unit</th>
                    <th>Amount</th>
                    <th>Action</th>
                </thead>
                <tbody id="invoice_items_holder">
                    <?php
                    $sno = 1;
                    foreach ($invoice_items as $row) {
                    ?>
                        <tr>
                            <td><?php echo $sno; ?></td>
                            <td><span><?php echo $row['property']; ?></span><input type="hidden" name="property_id[<?php echo $sno; ?>]" value="<?php echo $row['property_id']; ?>" /></td>
                            <td><span><?php echo $row['unit_name']; ?></span><input type="hidden" name="unit_id[<?php echo $sno; ?>]" value="<?php echo $row['prop_unit_id']; ?>" /></td>
                            <td><span><?php echo $row['price']; ?></span></td>
                            <td><button type="button" class="btn bg-danger property-remove-btn"><i class="fa fa-trash"></i></button>
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
                        <td colspan="3" class="text-right"><b>Subtotal</b></td>
                        <td id="invoice_subtotal">0.00</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><b>Discount</b></td>
                        <td><input type="text" name="discount" id="invoice_discount" value="<?php echo $invoice->discount; ?>" class="form_control field-check" /></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><b>Total</b></td>
                        <td id="invoice_total">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight ">
                <a class="btn outline-danger" href="<?= url_to('erp.sale.invoice'); ?>">Cancel</a>
                <button class="btn bg-primary" type="button" id="invoice_edit_btn">Save</button>
            </div>
        </div>
    </form>
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
    // let invoice_status_box=new SelectBox(document.getElementById("invoice_status"));
    // invoice_status_box.init();
    // closer.register_shutdown(invoice_status_box.shutdown,invoice_status_box.get_container());

    let customer_ajax_select = document.getElementById("customer_ajax_select_box");
    let customer_ajax_select_box = new AjaxSelectBox(customer_ajax_select);
    customer_ajax_select_box.init();
    closer.register_shutdown(customer_ajax_select_box.shutdown, customer_ajax_select_box.get_container());

    let form = document.getElementById("invoice_edit_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("invoice_edit_btn").onclick = function(evt) {
        if (!lock) {
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

    let invoice_property = document.getElementById("invoice_property");
    let invoice_unit = document.getElementById("invoice_unit");
    let invoice_price_list_box;
    let invoice_property_box = new AjaxSelectBox(invoice_property.querySelector(".ajaxselectBox"));
    invoice_property_box.init();
    invoice_property_box.add_listener((params) => {
        let property_id = params.value;
        let ajax_url = invoice_unit.getAttribute("data-ajax-url");
        if (property_id !== null && property_id !== undefined && property_id !== "") {
            let xhr = null;
            if (window.ActiveXObject) {
                xhr = new ActiveXObject("Msxml2.XMLHTTP");
            } else if (window.XMLHttpRequest) {
                xhr = new XMLHttpRequest();
            }
            if (xhr !== null || xhr !== undefined) {
                xhr.open("GET", ajax_url + "?property_id=" + property_id + "&invoice_id=<?php echo $invoice_id; ?>", true);
                xhr.send(null);
                xhr.onreadystatechange = (evt) => {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        let json = JSON.parse(xhr.responseText);
                        if (json['error'] == 0) {
                            let data = json['data'];
                            let html = ``;
                            for (let i = 0; i < data.length; i++) {
                                let extra = [];
                                if (data[i]['extra'] !== null && data[i]['extra'] !== undefined && data[i]['extra'] !== "") {
                                    extra = JSON.stringify(data[i]['extra']);
                                }
                                html += ` <li role="option" data-value="` + data[i]['key'] + `" data-extra='` + extra + `' >` + data[i]['value'] + `</li>`;
                            }
                            invoice_unit.querySelector("ul").innerHTML = html;
                            invoice_unit_box = new SelectBox(invoice_unit.querySelector(".selectBox"));
                            invoice_unit_box.init();
                            invoice_unit_box.add_listener((params) => {
                                document.getElementById("invoice_unit_amount").value = params.extra[0];
                            }, {});
                            closer.register_shutdown(invoice_unit_box.shutdown, invoice_unit_box.get_container());
                        } else {
                            alert.invoke_alert(json['reason'], "error");
                        }
                    }
                }
            }
        } else {
            if (invoice_unit_box != null) {
                invoice_unit.querySelector(".selectBox_Value").value = "";
                invoice_unit_box.construct();
            }
            invoice_unit.querySelector("ul").innerHTML = "";
            invoice_unit_box = null;
        }
    }, {});
    closer.register_shutdown(invoice_property_box.shutdown, invoice_property_box.get_container());

    let sno = parseInt("<?php echo $sno++; ?>");
    document.getElementById("add_item_btn").onclick = (evt) => {
        let amount = parseFloat(document.getElementById("invoice_unit_amount").value);
        let property_id = invoice_property.querySelector(".ajaxselectBox_Value").value;
        let property_name = invoice_property.querySelector(".textFlow").textContent;
        let unit_id = invoice_unit.querySelector(".selectBox_Value").value;
        let unit_name = invoice_unit.querySelector(".textFlow").textContent;

        if (property_id === null || property_id === undefined || property_id === "") {
            alert.invoke_alert("Select property", "error");
            return;
        }

        if (unit_id === null || unit_id === undefined || unit_id === "") {
            alert.invoke_alert("Select property unit", "error");
            return;
        }

        let unit_names = document.querySelectorAll("#invoice_items_holder tr td:nth-child(3) span");
        if (unit_names.length != 0) {
            let dup_found = false;
            for (let i = 0; i < unit_names.length; i++) {
                if (unit_name == unit_names[i].textContent) {
                    dup_found = true;
                    break;
                }
            }
            if (dup_found) {
                alert.invoke_alert("Duplicate Property Unit not allowed", "error");
                return;
            }
        }
        let tr = ``;
        tr += `<td>` + sno + `</td>`
        sno++;
        tr += `<td><span>` + property_name + `</span><input type="hidden" name="property_id[` + sno + `]" value="` + property_id + `" /></td>`;
        tr += `<td><span>` + unit_name + `</span><input type="hidden" name="unit_id[` + sno + `]" value="` + unit_id + `" /></td>`;
        tr += `<td><span>` + amount + `</span></td>`;
        tr += `<td><button type="button" class="btn bg-danger property-remove-btn" ><i class="fa fa-trash"></i></button>`;
        let element = document.createElement("tr");
        element.innerHTML = tr;
        document.getElementById("invoice_items_holder").append(element);
        calculateSubtotal();
        calculateTotal();
    }
    document.getElementById("invoice_items_holder").onclick = (evt) => {
        let target = evt.target;
        document.querySelectorAll("#invoice_items_holder .property-remove-btn").forEach((item) => {
            if (item.contains(target)) {
                item.parentElement.parentElement.remove();
                calculateSubtotal();
                calculateTotal();
            }
        });
    }

    document.getElementById("invoice_discount").onchange = (evt) => {
        let discount = evt.target.value;
        let pattern = /^[0-9]+\.[0-9]{2}$/;
        if (!pattern.test(discount)) {
            alert.invoke_alert("Invalid discount value", "error");
        }
        calculateTotal();
    };

    function calculateSubtotal() {
        let amounts = document.querySelectorAll("#invoice_items_holder tr td:nth-child(4) span");
        let total = 0.00;
        if (amounts.length != 0) {
            for (let i = 0; i < amounts.length; i++) {
                total += parseFloat(amounts[i].textContent);
            }
        }
        document.getElementById("invoice_subtotal").textContent = total.toFixed(2);
    };

    function calculateTotal() {
        let amount = parseFloat(document.getElementById("invoice_subtotal").textContent);
        let discount = parseFloat(document.getElementById("invoice_discount").value);
        let total = 0.00;
        if (!isNaN(amount)) {
            total += amount;
        }
        if (!isNaN(discount)) {
            total -= discount;
        }
        document.getElementById("invoice_total").textContent = total.toFixed(2);
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