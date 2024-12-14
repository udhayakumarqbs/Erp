<div class="alldiv flex widget_title">
    <h3>Create Requisition</h3>
    <div class="title_right">
        <a href="<?php echo base_url() . 'erp/procurement/requisition'; ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>

<div class="alldiv">
    <?php
    echo form_open(url_to('erp.procurement.requisitionadd_form'), array(
        "id" => "requisition_add_form",
        "class" => "flex"
    ));
    ?>
    <div class="form-width-3">
        <div class="form-group field-ajax " data-ajax-url="<?php echo url_to('erp.procurement.ajax_requisition_code_unique') . '?'; ?>">
            <label class="form-label">Requisition Code</label>
            <input type="text" name="req_code" class="form_control field-check" value="<?= "REQ".date("y")."/".str_pad(intval($total_rfq_count->count) + 1,4,0,STR_PAD_LEFT) ?>" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-3">
        <div class="form-group field-required">
            <label class="form-label">Assigned To</label>
            <div class="ajaxselectBox req_ajax_select_box poR" data-ajax-url="<?php echo url_to('erp.crm.ajaxFetchUsers'); ?>">
                <div class="ajaxselectBoxBtn flex">
                    <div class="textFlow" data-default="assign to">assign to</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="assigned_to" value="">
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
        <div class="form-group field-required">
            <label class="form-label">Priority</label>
            <div class="selectBox req_select_box poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select priority">select priority</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="priority" value="">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($priority as $key => $value) {
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
        <div class="form-group ">
            <label class="form-check-label"><input value="1" type="checkbox" name="mail_sent" /> Send in email too ?</label>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group field-required ">
            <label class="form-label">Description</label>
            <textarea rows="3" name="description" class="form_control field-check"></textarea>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="widget_title">
            <h3>Add Items</h3>
        </div>
    </div>
    <div class="form-width-3">
        <div class="form-group" id="req_product_type">
            <label class="form-label">Product Type</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select type">select type</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" value="">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    $first_product = "";
                    foreach ($product_types as $key => $status) {
                        if (empty($first_product)) {
                            $first_product = $key;
                        }
                    ?>
                        <li role="option" data-value="<?php echo $key; ?>"><?php echo $status; ?></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-3">
        <div class="form-group" id="req_product">
            <label class="form-label">Product</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo base_url() . $product_links[$first_product]; ?>">
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
        <div class="form-group" id="req_product_qty">
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
                <th>Action</th>
            </thead>
            <tbody id="requisition_items_holder">

            </tbody>
        </table>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight ">
            <a class="btn outline-danger" href="<?php echo base_url() . 'erp/procurement/requisition'; ?>">Cancel</a>
            <button class="btn bg-primary" type="button" id="requisition_add_btn">Save</button>
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

    document.querySelectorAll(".req_select_box").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        closer.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    document.querySelectorAll(".req_ajax_select_box").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    let form = document.getElementById("requisition_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("requisition_add_btn").onclick = function(evt) {
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

    /**
     *Requisition Item Holder
     */
    let product_links = JSON.parse('<?php echo json_encode($product_links); ?>');
    let base_url = "<?php echo base_url(); ?>";

    let ajaxselectbox_elem = document.querySelector("#req_product .ajaxselectBox");
    ajaxselectbox = new AjaxSelectBox(ajaxselectbox_elem);
    let default_ajax_url = ajaxselectbox_elem.getAttribute("data-ajax-url");
    ajaxselectbox.init();
    closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());

    let selectbox_elem = document.querySelector("#req_product_type .selectBox");
    let selectbox = new SelectBox(selectbox_elem);
    selectbox.init();
    selectbox.add_listener((params) => {
        let url = params['value'];
        if (url !== null && url !== undefined && url !== "") {
            ajaxselectbox_elem.setAttribute("data-ajax-url", base_url + product_links[url]);
        } else {
            ajaxselectbox_elem.setAttribute("data-ajax-url", default_ajax_url);
        }
        ajaxselectbox_elem.querySelector(".ajaxselectBox_Value").value = "";
        ajaxselectbox.construct();
    }, {});
    closer.register_shutdown(selectbox.shutdown, selectbox.get_container());



    document.getElementById("add_item_btn").onclick = (evt) => {
        let form_valid = true;
        let product_type = document.getElementById("req_product_type").querySelector(".selectBox_Value").value;

        if (product_type === null || product_type === undefined || product_type === "") {
            form_valid = false;
            document.getElementById("req_product_type").querySelector(".error-text").textContent = "Product Type required";
            document.getElementById("req_product_type").classList.add("form-error");
        } else {
            document.getElementById("req_product_type").querySelector(".error-text").textContent = "";
            document.getElementById("req_product_type").classList.remove("form-error");
        }
        let product = document.getElementById("req_product").querySelector(".ajaxselectBox_Value").value;
        let productname = document.getElementById("req_product").querySelector(".textFlow").textContent;
        if (product === null || product === undefined || product === "") {
            form_valid = false;
            document.getElementById("req_product").querySelector(".error-text").textContent = "Product required";
            document.getElementById("req_product").classList.add("form-error");
        } else {
            document.getElementById("req_product").querySelector(".error-text").textContent = "";
            document.getElementById("req_product").classList.remove("form-error");
        }
        let qty = document.getElementById("req_product_qty").querySelector(".field-check").value;
        if (qty === null || qty === undefined || qty === "" || qty === "0") {
            form_valid = false;
            document.getElementById("req_product_qty").querySelector(".error-text").textContent = "Quantity required";
            document.getElementById("req_product_qty").classList.add("form-error");
        } else {
            try {
                qty = parseInt(qty);
                if (!isNaN(qty) && qty != 0) {
                    document.getElementById("req_product_qty").querySelector(".error-text").textContent = "";
                    document.getElementById("req_product_qty").classList.remove("form-error");
                } else {
                    document.getElementById("req_product_qty").querySelector(".error-text").textContent = "Quantity should be number";
                    document.getElementById("req_product_qty").classList.add("form-error");
                    form_valid = false;
                }
            } catch (e) {
                document.getElementById("req_product_qty").querySelector(".error-text").textContent = "Quantity should be number";
                document.getElementById("req_product_qty").classList.add("form-error");
                form_valid = false;
            }
        }

        if (form_valid) {
            append_products(product_type, product, productname, qty);
        }
    }

    let requisition_items_holder = document.getElementById("requisition_items_holder");
    let req_product_counter = 1;

    function append_products(product_type, product_id, product_name, qty) {
        let tds = requisition_items_holder.querySelectorAll("td:nth-child(2)");
        let dup_found = false;
        for (let i = 0; i < tds.length; i++) {
            if (tds[i].innerText === product_name) {
                dup_found = true;
                break;
            }
        }
        if (!dup_found) {
            let tr = document.createElement("tr");
            let td1 = document.createElement("td");
            td1.innerHTML = `
                    <input type="hidden" name="product_type_` + req_product_counter + `" value="` + product_type + `" />
                    <span>` + req_product_counter + `</span>
                `;
            tr.append(td1);
            let td2 = document.createElement("td");
            td2.innerHTML = `
                    <input type="hidden" name="product_id_` + req_product_counter + `" value="` + product_id + `" />
                    <span>` + product_name + `</span>
                `;
            tr.append(td2);
            let td3 = document.createElement("td");
            td3.innerHTML = `
                    <input type="hidden" name="product_qty_` + req_product_counter + `" value="` + qty + `" />
                    <span>` + qty + `</span>
                `;
            tr.append(td3);

            let td4 = document.createElement("td");
            td4.innerHTML = `
                    <button class="btn bg-danger req-product-remove"  ><i class="fa fa-trash"></i></button>
                `;
            tr.append(td4);

            requisition_items_holder.append(tr);
            req_product_counter++;
        } else {
            alert.invoke_alert("Duplicate product", "error");
        }
    }

    requisition_items_holder.onclick = (evt) => {
        let target = evt.target;
        requisition_items_holder.querySelectorAll(".req-product-remove").forEach((item) => {
            if (item.contains(target)) {
                item.parentElement.parentElement.remove();
            }
        });
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