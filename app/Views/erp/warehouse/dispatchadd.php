<div class="alldiv flex widget_title">
    <h3>Create Dispatch</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.warehouse.dispatch'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>

<div class="alldiv">
    <form action="<?= url_to('warehouse.dispatch.add.post') ?>" method="post" class="flex" id="dispatch_add_form">
        <div class="form-width-2">
            <div class="form-group field-ajax ">
                <label class="form-label">Order Code</label>
                <input type="text" name="code" class="form_control field-check" />
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
                        <input type="hidden" class="ajaxselectBox_Value field-check" name="cust_id" value="">
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
                <label class="form-label">Delivery Date</label>
                <input type="date" name="estimate_date" value="<?php echo date("Y-m-d"); ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Status</label>
                <div class="selectBox poR" id="dispatch_status">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select status">select status</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" name="dispatch_status">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($dispatch_status as $key => $value) {
                        ?>
                            <li role="option" data-value="<?php echo $key; ?>"><?php echo $value; ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>

       

        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Description</label>
                <textarea rows="3" name="terms_condition" class="form_control field-check"></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight ">
                <a class="btn outline-danger" href="<?php echo url_to('erp.warehouse.dispatch'); ?>">Cancel</a>
                <button class="btn bg-primary" type="button" id="dispatch_add_btn">Save</button>
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

    let customer_ajax_select = document.getElementById("customer_ajax_select_box");
    let customer_ajax_select_box = new AjaxSelectBox(customer_ajax_select);
    customer_ajax_select_box.init();
    closer.register_shutdown(customer_ajax_select_box.shutdown, customer_ajax_select_box.get_container());
    let customer_shipping_select = document.getElementById("customer_shipping_addr");
    let customer_shipping_select_box;
    customer_ajax_select_box.add_listener((params) => {
        let cust_id = params.value;
        let ajax_url = customer_shipping_select.getAttribute("data-ajax-url");
        if (cust_id !== null && cust_id !== undefined && cust_id !== "") {
            let xhr = null;
            if (window.ActiveXObject) {
                xhr = new ActiveXObject("Msxml2.XMLHTTP");
            } else if (window.XMLHttpRequest) {
                xhr = new XMLHttpRequest();
            }
            if (xhr !== null || xhr !== undefined) {
                xhr.open("GET", ajax_url + "?cust_id=" + cust_id, true);
                xhr.send(null);
                xhr.onreadystatechange = (evt) => {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        let json = JSON.parse(xhr.responseText);
                        if (json['error'] == 0) {
                            let data = json['data'];
                            let html = ``;
                            for (let i = 0; i < data.length; i++) {
                                html += ` <li role="option" data-value="` + data[i]['key'] + `" >` + data[i]['value'] + `</li>`;
                            }
                            customer_shipping_select.querySelector("ul").innerHTML = html;
                            customer_shipping_select_box = new SelectBox(customer_shipping_select);
                            customer_shipping_select_box.init();
                        } else {
                            alert.invoke_alert(json['reason'], "error");
                        }
                    }
                }
            }
        } else {
            if (customer_shipping_select_box != null) {
                customer_shipping_select.querySelector(".selectBox_Value").value = "";
                customer_shipping_select_box.construct();
            }
            customer_shipping_select.querySelector("ul").innerHTML = "";
            customer_shipping_select_box = null;
        }
    }, {});


    let form = document.getElementById("dispatch_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("dispatch_add_btn").onclick = function(evt) {
        if (!lock) {
            lock = true;estimate_items_holder
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