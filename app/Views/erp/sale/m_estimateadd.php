<div class="alldiv flex widget_title">
    <h3>Create Estimate</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.sale.estimates'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>

<div class="alldiv">
    <form action="<?= url_to('erp.sale.estimates.add.post') ?>" method="post" class="flex" id="estimate_add_form">
        <div class="form-width-2">
            <div class="form-group field-ajax " data-ajax-url="<?php echo url_to('erp.sale.ajaxEstimateCodeUnique') . '?'; ?>">
                <?php
                $newCode = "EST-0" . sprintf('%03d', $maxCode['max_code'] + 1);
                ?>
                <label class="form-label">Estimate Code</label>
                <input type="text" name="code" class="form_control field-check" value="<?= $newCode; ?>" readonly />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Estimate Date</label>
                <input type="date" name="estimate_date" value="<?php echo date("Y-m-d"); ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Customer</label>
                <div class="ajaxselectBox poR" id="customer_ajax_select_box" data-ajax-url="<?php echo url_to('erp.sale.getCustomer'); ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="select customer">select customer</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="ajaxselectBox_Value field-check" name="cust_id" id="cust_id" value="">
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
            <div class="form-group ">
                <label class="form-label">Billing & Shipping Address</label>
                <input type="hidden" name="selected_bill_id" id="selected_bill_id">
                <input type="hidden" name="selected_shipp_id" id="selected_shipp_id">
                <div class="selectBox poR" id="customer_shipping_addr">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select address">select address</div>
                        <button class="edit-shipping-address" type="button"><i class="fa fa-pencil"></i></button>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <!-- <button class="drops" type="button"><i class="fa fa-caret-down"></i></button> -->
                        <input type="hidden" class="selectBox_Value field-check" name="shippingaddr_id" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">

                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label">Terms and condition</label>
                <textarea rows="3" name="terms_condition" id="termsCondition" class="form_control"></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="widget_title">
                <h3>Add Items</h3>
            </div>
        </div>
        <div class="form-width-3">
            <div class="form-group" id="est_product">
                <label class="form-label">Product</label>
                <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.crm.ajaxfetchfinishedgoods'); ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="select product">select product</div>
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
        <div class="form-width-3">
            <div class="form-group" id="est_price_list" data-ajax-url="<?php echo url_to('erp.sale.getPriceData') ?>">
                <label class="form-label">Price List</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select price">select price</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">

                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <input type="hidden" id="est_amount" />
        <input type="hidden" id="est_max_qty" />
        <input type="hidden" id="tax" />
        <div class="form-width-3">
            <div class="form-group" id="est_product_qty">
                <label class="form-label">Quantity</label>
                <input type="text" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <label class="form-label"></label>
                <button class="btn outline-primary" type="button" id="add_item_btn">Add Item</button>
            </div>
        </div>
        <div class="form-width-1">
            <table class="table">
                <thead>
                    <th>SNO</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                    <th>Tax</th>
                    <th>Action</th>
                </thead>
                <tbody id="estimate_items_holder">

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><b>Total</b></td>
                        <td id="estimate_subtotal">0.00</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><b>Total Inc. Tax</b></td>
                        <td id="est_total_tax">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight ">
                <a class="btn outline-danger" href="<?php echo url_to('erp.sale.estimates'); ?>">Cancel</a>
                <button class="btn bg-primary" type="button" id="estimate_add_btn">Save</button>
            </div>
        </div>
    </form>
</div>


<!-- Modal box for  shipping address -->

<div class="modal" id="shippingAddressModal" role="dialog">
    <div class="modalbody">
        <div class="form-container">
            <h2 class="modalTitle">Billing Address </h2>
            <input type="hidden" name="selected_bill_id">
            <div class="form-width-1">
                <div class="form-group field-required ">
                    <label class="form-label">Address</label>
                    <input type="text" class="form_control field-check" id="billing_address" name="billing_address" value="" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group field-required ">
                    <label class="form-label">City</label>
                    <input type="text" class="form_control field-check" id="billing_city" name="billing_city" value="" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group field-required">
                    <label class="form-label">State</label>
                    <input type="text" class="form_control field-check" id="billing_state" name="billing_state" value="" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group field-required">
                    <label class="form-label">Zipcode</label>
                    <input type="text" class="form_control field-check" id="billing_zipcode" name="billing_zipcode"></input>
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group field-required">
                    <label class="form-label ">Country</label>
                    <input type="text" class="form_control field-check" id="billing_country" name="billing_country"></input>
                    <p class="error-text"></p>
                </div>
            </div><br />

            <h2 class="modalTitle"> Shipping Address</h2>
            <input type="hidden" name="selected_shipp_id">
            <div class="form-width-1">
                <div class="form-group field-required ">
                    <label class="form-label">Address</label>
                    <input type="text" class="form_control field-check" id="shipp_address" name="shipp_address" value="" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group field-required ">
                    <label class="form-label">City</label>
                    <input type="text" class="form_control field-check" id="shipp_city" name="shipp_city" value="" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group field-required">
                    <label class="form-label">State</label>
                    <input type="text" class="form_control field-check" id="shipp_state" name="shipp_state" value="" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group field-required">
                    <label class="form-label">Zipcode</label>
                    <input type="text" class="form_control field-check" id="shipp_zipcode" name="shipp_zipcode"></input>
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group field-required">
                    <label class="form-label ">Country</label>
                    <input type="text" class="form_control field-check" id="shipp_country" name="shipp_country"></input>
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1 ">
                <div class="form-group textRight ">
                    <button type="button" class="btn outline-danger modalClose">Close</button>
                    <button class="btn bg-primary" type="button" id="shipp_addedit_btn">Save</button>
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

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
            .create(document.querySelector('#termsCondition'))
            .catch(error => {
                console.error(error);
            });
    });
</script>

<script type="text/javascript">
    let closer = new WindowCloser();
    let alert = new ModalAlert();
    closer.init();

    let customer_ajax_select = document.getElementById("customer_ajax_select_box");
    let customer_ajax_select_box = new AjaxSelectBox(customer_ajax_select);
    customer_ajax_select_box.init();
    closer.register_shutdown(customer_ajax_select_box.shutdown, customer_ajax_select_box.get_container());


    //Address  box modal

    document.addEventListener('DOMContentLoaded', function() {
        var addShippingAddressBtn = document.querySelector('.edit-shipping-address');
        var shippingAddressModal = document.getElementById('shippingAddressModal');
        let modalCloseBtn = shippingAddressModal.querySelector('.modalClose');
        let shippAddressInput = document.getElementById('shipp_address');
        let shippCityInput = document.getElementById('shipp_city');
        let shippStateInput = document.getElementById('shipp_state');
        let shippZipcodeInput = document.getElementById('shipp_zipcode');
        let shippCountryInput = document.getElementById('shipp_country');
        let selectedShippIdInput = document.getElementById('selected_shipp_id');
        let saveShippingAddress = document.getElementById('shipp_addedit_btn');

        let billAddressInput = document.getElementById('billing_address');
        let billCityInput = document.getElementById('billing_city');
        let billStateInput = document.getElementById('billing_state');
        let billZipcodeInput = document.getElementById('billing_zipcode');
        let billCountryInput = document.getElementById('billing_country');
        let selectedBillIdInput = document.getElementById('selected_bill_id');

        addShippingAddressBtn.onclick = function() {
            shippingAddressModal.style.display = "block";
            let cust_id = document.getElementById('cust_id').value;
            console.log(cust_id);

            let xhr = new XMLHttpRequest();
            xhr.open("GET", `<?= url_to('edit.getshipping'); ?>?selected_cust_id=${cust_id}`, true);
            xhr.send();

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    let response = JSON.parse(xhr.responseText);
                    console.log(response);

                    if (response.shippingAddressDetails != null) {
                        shippAddressInput.value = response.shippingAddressDetails.address || '';
                        shippCityInput.value = response.shippingAddressDetails.city || '';
                        shippStateInput.value = response.shippingAddressDetails.state || '';
                        shippZipcodeInput.value = response.shippingAddressDetails.zipcode || '';
                        shippCountryInput.value = response.shippingAddressDetails.country || '';
                    } else {
                        shippAddressInput.value;
                        shippCityInput.value;
                        shippStateInput.value;
                        shippZipcodeInput.value;
                        shippCountryInput.value;
                    }

                    if (response.billingAddressDetails != null) {
                        billAddressInput.value = response.billingAddressDetails.address || '';
                        billCityInput.value = response.billingAddressDetails.city || '';
                        billStateInput.value = response.billingAddressDetails.state || '';
                        billZipcodeInput.value = response.billingAddressDetails.zipcode || '';
                        billCountryInput.value = response.billingAddressDetails.country || '';
                    } else {
                        billAddressInput.value;
                        billCityInput.value;
                        billStateInput.value;
                        billZipcodeInput.value;
                        billCountryInput.value;
                    }
                }
            };

            modalCloseBtn.onclick = function() {
                shippingAddressModal.style.display = 'none';
            };

            saveShippingAddress.onclick = function() {
                let updatedValues = {
                    customer_id: cust_id,
                    updatedShippAddress: shippAddressInput.value,
                    updatedShippCity: shippCityInput.value,
                    updatedShippState: shippStateInput.value,
                    updatedShippZipcode: shippZipcodeInput.value,
                    updatedShippCountry: shippCountryInput.value,

                    updatedBillingAddress: billAddressInput.value,
                    updatedBillingCity: billCityInput.value,
                    updatedBillingState: billStateInput.value,
                    updatedBillingZipcode: billZipcodeInput.value,
                    updatedBillingCountry: billCountryInput.value,
                };
                //console.log(updatedValues);

                $.ajax({
                    type: 'POST',
                    url: '<?= url_to('edit.editshipping'); ?>',
                    data: {
                        updatedValues: updatedValues,
                    },
                    success: function(response) {
                        console.log('hi ', response);
                        if (response === true) {
                            alert.invoke_alert('Billing & Shipping address Added successfully', 'success');
                            shippingAddressModal.style.display = 'none';
                        } else {
                            alert.invoke_alert('Failed to add Address', 'error');
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            };
        };
    });


    let form = document.getElementById("estimate_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("estimate_add_btn").onclick = function(evt) {
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

    let est_product = document.getElementById("est_product");
    // let est_price_list = document.getElementById("est_price_list");
    let est_price_list_box;
    let est_product_box = new AjaxSelectBox(est_product.querySelector(".ajaxselectBox"));
    est_product_box.init();
    est_product_box.add_listener((params) => {
        let product_id = params.value;
        let ajax_url = est_price_list.getAttribute("data-ajax-url");
        if (product_id !== null && product_id !== undefined && product_id !== "") {
            let xhr = null;
            if (window.ActiveXObject) {
                xhr = new ActiveXObject("Msxml2.XMLHTTP");
            } else if (window.XMLHttpRequest) {
                xhr = new XMLHttpRequest();
            }
            if (xhr !== null || xhr !== undefined) {
                xhr.open("GET", ajax_url + "?product_id=" + product_id, true);
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
                            est_price_list.querySelector("ul").innerHTML = html;
                            est_price_list_box = new SelectBox(est_price_list.querySelector(".selectBox"));
                            est_price_list_box.init();
                            est_price_list_box.add_listener((params) => {
                                document.getElementById("est_amount").value = params['extra'][0];
                                document.getElementById("est_max_qty").value = params['extra'][1];

                                let tax_info = params['extra'][2];
                                tax_html = '';
                                if (tax_info) {
                                    tax_html += tax_info.tax1_name + '-' + tax_info.tax1_percent + '%';
                                    tax_html += ', ' + tax_info.tax2_name + '-' + tax_info.tax2_percent + '%';
                                }
                                document.getElementById("tax").value = tax_html;

                            }, {});
                            closer.register_shutdown(est_price_list_box.shutdown, est_price_list_box.get_container());
                        } else {
                            alert.invoke_alert(json['reason'], "error");
                        }
                    }
                }
            }
        } else {
            if (est_price_list_box != null) {
                est_price_list.querySelector(".selectBox_Value").value = "";
                est_price_list_box.construct();
            }
            est_price_list.querySelector("ul").innerHTML = "";
            est_price_list_box = null;
            document.getElementById("est_amount").value = "";
            document.getElementById("est_max_qty").value = "";
            document.getElementById("tax").value = "";

        }
    }, {});
    closer.register_shutdown(est_product_box.shutdown, est_product_box.get_container());
    let est_quantity = document.getElementById("est_product_qty");

    let sno = 1;
    document.getElementById("add_item_btn").onclick = (evt) => {
        let qty = parseInt(est_quantity.querySelector(".field-check").value);
        let max_qty = parseInt(document.getElementById("est_max_qty").value);
        let unit_price = parseFloat(document.getElementById("est_amount").value);
        let product_id = est_product.querySelector(".ajaxselectBox_Value").value;
        let product_name = est_product.querySelector(".textFlow").textContent;
        let price_id = est_price_list.querySelector(".selectBox_Value").value;
        let price_name = est_price_list.querySelector(".textFlow").textContent;
        var tax = document.getElementById("tax").value;

        if (product_id === null || product_id === undefined || product_id === "") {
            alert.invoke_alert("Select product", "error");
            return;
        }
        if (price_id === null || price_id === undefined || price_id === "") {
            alert.invoke_alert("Select price list", "error");
            return;
        }
        if (qty === null || qty === undefined || isNaN(qty) || qty <= 0) {
            alert.invoke_alert("Invalid quantity", "error");
            return;
        }
        if (max_qty <= 0) {
            alert.invoke_alert("Out of stock for this price list", "error");
            return;
        }
        if (qty > max_qty) {
            alert.invoke_alert("Max avail stock for this price list is " + max_qty, "error");
            return;
        }

        let product_names = document.querySelectorAll("#estimate_items_holder tr td:nth-child(2) span");
        if (product_names.length != 0) {
            let dup_found = false;
            for (let i = 0; i < product_names.length; i++) {
                if (product_name == product_names[i].textContent) {
                    dup_found = true;
                    break;
                }
            }
            if (dup_found) {
                alert.invoke_alert("Duplicate product not allowed", "error");
                return;
            }
        }
        let tr = ``;
        tr += `<td>` + sno + `</td>`
        sno++;
        tr += `<td><span>` + product_name + `</span><input type="hidden" name="product_id[` + sno + `]" value="` + product_id + `" /></td>`;
        tr += `<td><span>` + qty + `</span><input type="hidden" name="quantity[` + sno + `]" value="` + qty + `" /></td>`;
        tr += `<td><span>` + unit_price + `</span><input type="hidden" name="price_id[` + sno + `]" value="` + price_id + `" /></td>`;
        let amount = unit_price * qty;
        amount = amount.toFixed(2); 
        tr += `<td><span>` + amount + `</span></td>`;
        tr += `<td><span>` + tax + `</span></td>`;
        tr += `<td><button type="button" class="btn bg-danger product-remove-btn" ><i class="fa fa-trash"></i></button>`;
        let element = document.createElement("tr");
        element.innerHTML = tr;
        document.getElementById("estimate_items_holder").append(element);
        calculateSubTotal();
        calculateTotalwithTax();
    }

    document.getElementById("estimate_items_holder").onclick = (evt) => {
        let target = evt.target;
        document.querySelectorAll("#estimate_items_holder .product-remove-btn").forEach((item) => {
            if (item.contains(target)) {
                item.parentElement.parentElement.remove();
                calculateSubTotal();
                calculateTotalwithTax();
            }
        });
    }

    function calculateSubTotal() {
        let amounts = document.querySelectorAll("#estimate_items_holder tr td:nth-child(5) span");
        let total = 0.00;
        if (amounts.length != 0) {
            for (let i = 0; i < amounts.length; i++) {
                total += parseFloat(amounts[i].textContent);
            }
        }
        document.getElementById("estimate_subtotal").textContent = total.toFixed(2);
    }

    function calculateTotalwithTax() {
        let totalAmountWithTax = 0.00;

        document.querySelectorAll("#estimate_items_holder tr").forEach((row) => {
            let amount = parseFloat(row.querySelector("td:nth-child(5) span").textContent);
            let taxCell = row.querySelector("td:nth-child(6) span");

            if (taxCell) {
                let taxPercentages = taxCell.textContent.split(',');
                let totalTaxForRow = 0.00;
                taxPercentages.forEach((tax) => {
                    let percentage = parseFloat(tax.match(/\d+/)[0]);
                    totalTaxForRow += (percentage / 100) * amount;
                    console.log('totaltax', totalTaxForRow);
                });
                let subtotalForRow = amount + totalTaxForRow;
                totalAmountWithTax += subtotalForRow;
            }
        });

        console.log('totalwithtax', totalAmountWithTax);

        document.getElementById("est_total_tax").textContent = totalAmountWithTax.toFixed(2);
    }

    <?php
    if (session()->getFlashdata("op_success")) { ?>
        alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>