<div class="alldiv flex widget_title">
    <h3>Update Sale Invoice</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.sale.invoice'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>

<div class="alldiv">

    <form action="<?= url_to('erp.sale.invoice.edit.post', $invoice_id) ?>" method="POST" class="flex" id="invoice_add_form">
        <div class="form-width-2">
            <div class="form-group field-ajax " data-ajax-url="<?= url_to('sale.invoice.add.fetchcode') . '?id=' . $invoice_id . '&'; ?>">
                <label class="form-label">Code</label>
                <input type="text" name="code" value="<?php echo $invoice->code; ?>" class="form_control field-check" readonly />
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
                        <input type="hidden" class="ajaxselectBox_Value field-check" id="cust_id" name="cust_id" value="<?php echo $invoice->cust_id; ?>">
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
                        <input type="hidden" class="selectBox_Value field-check" name="shippingaddr_id" value="<?php
                                                                                                                if (!empty($invoice->shippingaddr_id)) {
                                                                                                                    echo $invoice->shippingaddr_id;
                                                                                                                }
                                                                                                                ?>">

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
                    <label class="form-check-label"><input type="checkbox" <?php
                                                                            if ($invoice->transport_req == 1) {
                                                                                echo "checked";
                                                                            }
                                                                            ?> name="transport_req" value="1" class="field-check" /> Transport Required</label>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-money">
                <label class="form-label">Transport Charge</label>
                <input type="text" id="invoice_trans_charge" name="trans_charge" value="<?php echo $invoice->trans_charge; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
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

        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Currency Position</label>
                <select name="currency_place" id="currency_place" class="form_control field-check">
                    <?php foreach ($currency_place as $row) : ?>
                        <option value="<?= $row['place']; ?>" <?= ($row['place'] == $selected_currency_place['currency_place']) ? 'selected' : ''; ?>><?= $row['place']; ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Currency</label>
                <div class="ajaxselectBox poR" id="currency_ajax_select_box" data-ajax-url="<?php echo url_to('erp.sale.getCurrencies') ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="select customer"><?= $selected_currency_name['iso_code'] . '' . $selected_currency_name['symbol']; ?></div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="ajaxselectBox_Value field-check" name="currency_id" id="currency_id" value="<?= $selected_currency_name['currency_id'];?>">
                        <input type="hidden" name="selected_cur_name" id="selected_cur_name" value="">
                    </div>
                    <div class="ajaxselectBox_Container alldiv">
                        <input type="text" class="ajaxselectBox_Search form_control" value="" />
                        <ul role="listbox">

                        </ul>
                    </div>
                </div>
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-label">Terms and Condition</label>
                <!-- <textarea rows="3" name="terms_condition" class="form_control field-check"><?php //echo $invoice->terms_condition; 
                                                                                                ?></textarea> -->
                <textarea rows="3" id="termsCondition" name="terms_condition" class="form_control field-check"><?= htmlspecialchars($invoice->terms_condition) ?></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="widget_title">
                <h3>Add Items</h3>
            </div>
        </div>
        <div class="form-width-3">
            <div class="form-group" id="invoice_product">
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
            <div class="form-group" id="invoice_price_list" data-ajax-url="<?= url_to('erp.sale.getPriceData'); ?>">
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
        <input type="hidden" id="invoice_amount" />
        <input type="hidden" id="tax" />
        <div class="form-width-3">
            <div class="form-group" id="invoice_product_qty">
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
                <tbody id="invoice_items_holder">
                    <?php
                    $sno = 1;
                    foreach ($invoice_items as $row) {
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
                        <td id="invoice_subtotal">0.00</td>
                    </tr>
                    <!-- <tr>
                        <td colspan="4" class="text-right"><b>Tax</b></td>
                        <td id="invoice_tax">0.00</td>
                    </tr> -->
                    <tr>
                        <td colspan="4" class="text-right"><b>Discount</b></td>
                        <td><input type="text" name="discount" id="invoice_discount" value="<?php echo $invoice->discount; ?>" class="form_control field-check" /></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><b>Total Inc. Tax</b></td>
                        <td id="invoice_total">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight ">
                <a class="btn outline-danger" href="<?= url_to('erp.sale.invoice'); ?>">Cancel</a>
                <button class="btn bg-primary" type="button" id="invoice_add_btn">Update</button>
            </div>
        </div>
    </form>
</div>


<!-- Modal box for editing shipping address -->

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

    // let invoice_status_box=new SelectBox(document.getElementById("invoice_status"));
    // invoice_status_box.init();
    // closer.register_shutdown(invoice_status_box.shutdown,invoice_status_box.get_container());

    let customer_ajax_select = document.getElementById("customer_ajax_select_box");
    let customer_ajax_select_box = new AjaxSelectBox(customer_ajax_select);
    customer_ajax_select_box.init();
    closer.register_shutdown(customer_ajax_select_box.shutdown, customer_ajax_select_box.get_container());

    let currency_ajax_select = document.getElementById("currency_ajax_select_box");
    let currency_ajax_select_box = new AjaxSelectBox(currency_ajax_select);
    currency_ajax_select_box.init();
    closer.register_shutdown(currency_ajax_select_box.shutdown, currency_ajax_select_box.get_container());

    document.addEventListener("DOMContentLoaded", function() {
        var currencyPlaceSelect = document.getElementById('currency_place');
        var currencySelect = document.getElementById('currency_id');
        var ajaxUrl = document.getElementById("currency_ajax_select_box").getAttribute("data-ajax-url");

        currencyPlaceSelect.addEventListener("change", function() {
            updateCurrencyOptions();
        });

        function updateCurrencyOptions() {
            var selectedPlace = currencyPlaceSelect.value;
            var ajaxSelectBox = document.getElementById('currency_ajax_select_box');
            var search = ajaxSelectBox.querySelector('.ajaxselectBox_Container .ajaxselectBox_Search').value;
            // console.log('hi', ajaxSelectBox);
            fetch(ajaxUrl + '?place=' + selectedPlace + '&search=' + search)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // console.log(data);
                    var ajaxSelectBox = document.getElementById('currency_ajax_select_box');
                    var ulElement = ajaxSelectBox.querySelector('.ajaxselectBox_Container ul');
                    ulElement.innerHTML = '';
                    data.data.forEach(currency => {
                        var liElement = document.createElement('li');
                        liElement.setAttribute('role', 'option');
                        liElement.setAttribute('data-value', currency.currency_id);
                        liElement.textContent = currency.currency_name;
                        ulElement.appendChild(liElement);
                        // console.log('hi', data);

                    });
                })
                .catch(error => {
                    console.error('Error fetching currencies:', error);
                });
        }
        updateCurrencyOptions();
    });


    //Address Edit box modal
    document.addEventListener('DOMContentLoaded', function() {
        var editShippingAddressBtn = document.querySelector('.edit-shipping-address');
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

        editShippingAddressBtn.onclick = function() {
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
                            alert.invoke_alert('Billing & Shipping address updated successfully', 'success');
                            shippingAddressModal.style.display = 'none';
                        } else {
                            alert.invoke_alert('Failed to update Address', 'error');
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            };
        };
    });

    let form = document.getElementById("invoice_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("invoice_add_btn").onclick = function(evt) {
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

    let invoice_product = document.getElementById("invoice_product");
    let invoice_price_list = document.getElementById("invoice_price_list");
    let invoice_price_list_box;
    let invoice_product_box = new AjaxSelectBox(invoice_product.querySelector(".ajaxselectBox"));
    invoice_product_box.init();
    invoice_product_box.add_listener((params) => {
        let product_id = params.value;
        let ajax_url = invoice_price_list.getAttribute("data-ajax-url");
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
                            console.log(json['data']);
                            let html = ``;
                            for (let i = 0; i < data.length; i++) {
                                let extra = [];
                                if (data[i]['extra'] !== null && data[i]['extra'] !== undefined && data[i]['extra'] !== "") {
                                    extra = JSON.stringify(data[i]['extra']);
                                }
                                html += ` <li role="option" data-value="` + data[i]['key'] + `" data-extra='` + extra + `' >` + data[i]['value'] + `</li>`;
                            }
                            invoice_price_list.querySelector("ul").innerHTML = html;
                            invoice_price_list_box = new SelectBox(invoice_price_list.querySelector(".selectBox"));
                            invoice_price_list_box.init();
                            invoice_price_list_box.add_listener((params) => {
                                document.getElementById("invoice_amount").value = params['extra'][0];
                                let tax_info = params['extra'][2];
                                tax_html = '';
                                if (tax_info) {
                                    tax_html += tax_info.tax1_name + '-' + tax_info.tax1_percent + '%';
                                    tax_html += ', ' + tax_info.tax2_name + '-' + tax_info.tax2_percent + '%';
                                }
                                document.getElementById("tax").value = tax_html;

                            }, {});
                            closer.register_shutdown(invoice_price_list_box.shutdown, invoice_price_list_box.get_container());
                        } else {
                            alert.invoke_alert(json['reason'], "error");
                        }
                    }
                }
            }
        } else {
            if (invoice_price_list_box != null) {
                invoice_price_list.querySelector(".selectBox_Value").value = "";
                invoice_price_list_box.construct();
            }
            invoice_price_list.querySelector("ul").innerHTML = "";
            invoice_price_list_box = null;
            document.getElementById("invoice_amount").value = "";
            document.getElementById("tax").value = "";
        }
    }, {});
    closer.register_shutdown(invoice_product_box.shutdown, invoice_product_box.get_container());
    let invoice_quantity = document.getElementById("invoice_product_qty");

    let sno = parseInt("<?php echo $sno++; ?>");
    document.getElementById("add_item_btn").onclick = (evt) => {
        let qty = parseInt(invoice_quantity.querySelector(".field-check").value);
        let unit_price = parseFloat(document.getElementById("invoice_amount").value);
        var tax = document.getElementById("tax").value;
        // console.log(tax);
        let product_id = invoice_product.querySelector(".ajaxselectBox_Value").value;
        let product_name = invoice_product.querySelector(".textFlow").textContent;
        let price_id = invoice_price_list.querySelector(".selectBox_Value").value;

        let price_name = invoice_price_list.querySelector(".textFlow").textContent;

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

        let product_names = document.querySelectorAll("#invoice_items_holder tr td:nth-child(2) span");
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
        document.getElementById("invoice_items_holder").append(element);
        calculateSubtotal();
        calculateTotal();
    }

    document.getElementById("invoice_items_holder").onclick = (evt) => {
        let target = evt.target;
        document.querySelectorAll("#invoice_items_holder .product-remove-btn").forEach((item) => {
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
            // alert.invoke_alert("Invalid discount value", "error");
        }
        calculateTotal();
    };

    document.getElementById("invoice_trans_charge").onchange = (evt) => {
        let trans_charge = evt.target.value;
        let pattern = /^[0-9]+\.[0-9]{2}$/;
        if (!pattern.test(trans_charge)) {
            // alert.invoke_alert("Invalid Transport Charge value", "error");
        }
        calculateTotal();
    };


    //Currency Position 
    function extractCurrencyPosition(callback) {
        var currency_place = document.getElementById('currency_place').value;
        var currency_id = document.getElementById('currency_id').value;
        var parentElement = document.getElementById('currency_ajax_select_box');
        var textFlowElement = parentElement.querySelector('.textFlow');
    
        document.getElementById('selected_cur_name').value = textFlowElement.textContent;
        var currency_name = document.getElementById('selected_cur_name').value;
        console.log(currency_id);
        extractCurrencySymbol(currency_id, function(currency_symbol) {
            callback(currency_place, currency_symbol);
        });
    }

    function extractCurrencySymbol(currency_id, callback) {

        $.ajax({
            type: "GET",
            url: "<?= url_to('erp.getCurrencySymbol'); ?>",
            data: {
                currency_id: currency_id
            },
            success: function(data) {
                console.log('symbol_ajax:', data.symbol);
                callback(data.symbol);
            },
            error: function(error) {
                console.log(error);
                callback(null);
            }
        });
    }

    function calculateSubtotal() {
        let amounts = document.querySelectorAll("#invoice_items_holder tr td:nth-child(5) span");
        let total = 0.00;
        if (amounts.length != 0) {
            for (let i = 0; i < amounts.length; i++) {
                total += parseFloat(amounts[i].textContent);
            }
        }

        extractCurrencyPosition(function(currency_place, currency_symbol) {
            let formattedTotal = total.toFixed(2);
            if (currency_place === 'after') {
                document.getElementById("invoice_subtotal").textContent = formattedTotal + currency_symbol;
            } else if (currency_place === 'before') {
                console.log(currency_place);
                document.getElementById("invoice_subtotal").textContent = currency_symbol + formattedTotal;
            }
        });
    };

    function calculateTotal() {
        let totalAmountWithTax = 0.00;
        let trans_charge = parseFloat(document.getElementById("invoice_trans_charge").value);
        let discount = parseFloat(document.getElementById("invoice_discount").value);

        document.querySelectorAll("#invoice_items_holder tr").forEach((row) => {
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

        extractCurrencyPosition(function(currency_place, currency_symbol) {
            let formattedTotal = total.toFixed(2);
            if (currency_place === 'after') {
                document.getElementById("invoice_total").textContent = formattedTotal + currency_symbol;
            } else if (currency_place === 'before') {
                console.log(currency_place);
                document.getElementById("invoice_total").textContent = currency_symbol + formattedTotal;
            }
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