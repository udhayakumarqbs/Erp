<div class="alldiv flex widget_title">
    <h3>Update Sale Order</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.sale.orders'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>

<div class="alldiv">
    <?php
    echo form_open(url_to('erp.sale.order.edit', $order_id), array(
        "id" => "order_edit_form",
        "class" => "flex"
    ));
    ?>
    <div class="form-width-2">
        <div class="form-group field-ajax " data-ajax-url="<?= url_to('erp.sale.orders.ajax_order_code_unique') . '?id=' . $order_id . '&'; ?>">
            <label class="form-label">Order Code</label>
            <input type="text" name="code" value="<?php echo $order->code; ?>" class="form_control field-check" readonly />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Order Date</label>
            <input type="date" name="order_date" value="<?php echo $order->order_date; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Customer</label>
            <div class="ajaxselectBox poR" id="customer_ajax_select_box" data-ajax-url="<?= url_to('erp.sale.getCustomer'); ?>">
                <div class="ajaxselectBoxBtn flex">
                    <div class="textFlow" data-default="select customer"><?php echo $order->name; ?></div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="cust_id" id = "cust_id" value="<?php echo $order->cust_id; ?>">
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

    <div class="form-width-2">
        <div class="form-group">
            <label class="form-label"></label>
            <div>
                <label class="form-check-label"><input type="checkbox" name="transport_req" <?php
                                                                                            if ($order->transport_req == 1) {
                                                                                                echo "checked";
                                                                                            }
                                                                                            ?> value="1" class="field-check" /> Transport Required</label>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-money">
            <label class="form-label">Transport Charge</label>
            <input type="text" id="order_trans_charge" name="trans_charge" value="<?php echo $order->trans_charge; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Order Expiry</label>
            <input type="date" name="order_expiry" value="<?php echo $order->order_expiry; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Payment Terms</label>
            <input type="text" name="payment_terms" value="<?php echo $order->payment_terms; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group field-required ">
            <label class="form-label">Terms and condition</label>
            <textarea rows="3" name="terms_condition" id="termsCondition" class="form_control field-check"><?php echo $order->terms_condition; ?></textarea>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="widget_title">
            <h3>Add Items</h3>
        </div>
    </div>
    <div class="form-width-3">
        <div class="form-group" id="order_product">
            <label class="form-label">Product</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?= url_to('erp.crm.ajaxfetchfinishedgoods'); ?>">
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
        <div class="form-group" id="order_price_list" data-ajax-url="<?= url_to('erp.sale.getPriceData'); ?>">
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
    <input type="hidden" id="order_amount" />
    <input type="hidden" id="order_max_qty" />
    <input type="hidden" id="tax" />
    <div class="form-width-3">
        <div class="form-group" id="order_product_qty">
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
        <div class="table_responsive">
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
                <tbody id="order_items_holder">
                    <?php
                    $sno = 1;
                    foreach ($order_items as $row) {
                    ?>
                        <tr>
                            <td><?php echo $sno; ?></td>
                            <td><span><?php echo $row['product']; ?></span><input type="hidden" name="product_id[<?php echo $sno; ?>]" value="<?php echo $row['related_id']; ?>" /></td>
                            <td><span><?php echo $row['quantity']; ?></span><input type="hidden" name="quantity[<?php echo $sno; ?>]" value="<?php echo $row['quantity']; ?>" /></td>
                            <td><span><?php echo $row['unit_price']; ?></span><input type="hidden" name="price_id[<?php echo $sno; ?>]" value="<?php echo $row['price_id']; ?>" /></td>
                            <td><span><?php echo $row['amount']; ?></span></td>
                            <td><span><?php echo $row['tax1_rate'] . '-' . $row['tax1_amount'] . '% ,' . $row['tax2_rate'] . '-' . $row['tax2_amount'] . '%'; ?></span></td>
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
                        <td id="order_subtotal">0.00</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><b>Discount</b></td>
                        <td><input type="text" name="discount" id="order_discount" value="<?php echo $order->discount; ?>" class="form_control field-check" /></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><b>Total Inc. Tax</b></td>
                        <td id="order_total">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight ">
            <a class="btn outline-danger" href="<?= url_to('erp.sale.orders'); ?>">Cancel</a>
            <button class="btn bg-primary" type="button" id="order_edit_btn">Save</button>
        </div>
    </div>
    <?php
    echo form_close();
    ?>
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
    calculateSubtotal();
    calculateTotal();

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


    let form = document.getElementById("order_edit_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("order_edit_btn").onclick = function(evt) {
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

    let order_product = document.getElementById("order_product");
    let order_price_list = document.getElementById("order_price_list");
    let order_price_list_box;
    let order_product_box = new AjaxSelectBox(order_product.querySelector(".ajaxselectBox"));
    order_product_box.init();
    order_product_box.add_listener((params) => {
        let product_id = params.value;
        let ajax_url = order_price_list.getAttribute("data-ajax-url");
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
                            order_price_list.querySelector("ul").innerHTML = html;
                            order_price_list_box = new SelectBox(order_price_list.querySelector(".selectBox"));
                            order_price_list_box.init();
                            order_price_list_box.add_listener((params) => {
                                document.getElementById("order_amount").value = params['extra'][0];
                                document.getElementById("order_max_qty").value = params['extra'][1];
                                let tax_info = params['extra'][2];
                                tax_html = '';
                                if (tax_info) {
                                    tax_html += tax_info.tax1_name + '-' + tax_info.tax1_percent + '%';
                                    tax_html += ', ' + tax_info.tax2_name + '-' + tax_info.tax2_percent + '%';
                                }
                                document.getElementById("tax").value = tax_html;

                            }, {});
                            closer.register_shutdown(order_price_list_box.shutdown, order_price_list_box.get_container());
                        } else {
                            alert.invoke_alert(json['reason'], "error");
                        }
                    }
                }
            }
        } else {
            if (order_price_list_box != null) {
                order_price_list.querySelector(".selectBox_Value").value = "";
                order_price_list_box.construct();
            }
            order_price_list.querySelector("ul").innerHTML = "";
            order_price_list_box = null;
            document.getElementById("order_amount").value = "";
            document.getElementById("order_max_qty").value = "";
            document.getElementById("tax").value = "";
        }
    }, {});
    closer.register_shutdown(order_product_box.shutdown, order_product_box.get_container());
    let order_quantity = document.getElementById("order_product_qty");

    let sno = parseInt("<?php echo $sno++; ?>");
    document.getElementById("add_item_btn").onclick = (evt) => {
        let qty = parseInt(order_quantity.querySelector(".field-check").value);
        let max_qty = parseInt(document.getElementById("order_max_qty").value);
        let unit_price = parseFloat(document.getElementById("order_amount").value);
        let product_id = order_product.querySelector(".ajaxselectBox_Value").value;
        let product_name = order_product.querySelector(".textFlow").textContent;
        let price_id = order_price_list.querySelector(".selectBox_Value").value;
        let price_name = order_price_list.querySelector(".textFlow").textContent;
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

        let product_names = document.querySelectorAll("#order_items_holder tr td:nth-child(2) span");
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
        document.getElementById("order_items_holder").append(element);
        calculateSubtotal();
        calculateTotal();
    }
    document.getElementById("order_items_holder").onclick = (evt) => {
        let target = evt.target;
        document.querySelectorAll("#order_items_holder .product-remove-btn").forEach((item) => {
            if (item.contains(target)) {
                item.parentElement.parentElement.remove();
                calculateSubtotal();
                calculateTotal();
            }
        });
    }

    document.getElementById("order_discount").onchange = (evt) => {
        let discount = evt.target.value;
        let pattern = /^[0-9]+\.[0-9]{2}$/;
        if (!pattern.test(discount)) {
            // alert.invoke_alert("Invalid discount value", "error");
        }
        calculateTotal();
    };

    document.getElementById("order_trans_charge").onchange = (evt) => {
        let trans_charge = evt.target.value;
        let pattern = /^[0-9]+\.[0-9]{2}$/;
        if (!pattern.test(trans_charge)) {
            // alert.invoke_alert("Invalid Transport Charge value","error");
        }
        calculateTotal();
    };

    function calculateSubtotal() {
        let amounts = document.querySelectorAll("#order_items_holder tr td:nth-child(5) span");
        let total = 0.00;
        if (amounts.length != 0) {
            for (let i = 0; i < amounts.length; i++) {
                total += parseFloat(amounts[i].textContent);
            }
        }
        document.getElementById("order_subtotal").textContent = total.toFixed(2);
    };

    function calculateTotal() {
        let totalAmountWithTax = 0.00;
        let trans_charge = parseFloat(document.getElementById("order_trans_charge").value);
        let discount = parseFloat(document.getElementById("order_discount").value);

        document.querySelectorAll("#order_items_holder tr").forEach((row) => {
            let amount = parseFloat(row.querySelector("td:nth-child(5) span").textContent);
            let taxCell = row.querySelector("td:nth-child(6) span");

            if (taxCell) {
                let taxPercentages = taxCell.textContent.split(',');
                let totalTaxForRow = 0.00;

                taxPercentages.forEach((tax) => {
                    let percentage = parseFloat(tax.match(/\d+/)[0]);
                    totalTaxForRow += (percentage / 100) * amount;
                });

                let subtotalForRow = amount + totalTaxForRow;
                totalAmountWithTax += subtotalForRow;
            }
        });

        let total = totalAmountWithTax;

        if (!isNaN(trans_charge)) {
            total += trans_charge;
        }
        if (!isNaN(discount)) {
            total -= discount;
        }
        document.getElementById("order_total").textContent = total.toFixed(2);
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