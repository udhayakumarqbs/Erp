<div class="alldiv flex widget_title">
    <h3>View Planning</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.mrp.planningschedule'); ?>" class="btn bg-success"><i class="fa fa-reply"></i>
            Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="opertaions">Opertaions</a></li>
        <li><a type="button" class="tab_nav_item" data-src="scraploss">Scrap & Process Loss</a></li>
    </ul>
    <div class="tab_pane active" id="opertaions">
        <div class="flex">
            <div class="alldiv">
                <form action="" method="post">
                    <div class="flex">
                        <div class="form-width-1" style="margin-top:20px; margin-bottom:18px;">
                            <label for="">Calculate and analyze the total cost incurred in producing finished goods,
                                including raw materials, operating and scrap materials.</label>
                        </div>
                        <div class="form-width-2">
                            <div class="form-group">
                                <label class="form-label">Raw Material Cost</label>
                                <input type="text" class="form_control field-check" value="" name="raw_material_cost"
                                    disabled />
                                <p class="error-text"></p>
                            </div>
                        </div>
                        <div class="form-width-2">
                            <div class="form-group  ">
                                <label class="form-label">Total Cost</label>
                                <input type="text" class="form_control field-check" value="" name="total_cost"
                                    disabled />
                                <p class="error-text"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-width-2">
                        <div class="form-group">
                            <label class="form-label">Operating Cost </label>
                            <input type="text" class="form_control field-check" value="" name="operating_cost"
                                disabled />
                            <p class="error-text"></p>
                        </div>
                    </div>
                    <div class="form-width-2">
                        <div class="form-group  ">
                            <label class="form-label">Scrap Material Cost </label>
                            <input type="text" class="form_control field-check" value="" name="scrap_cost" disabled />
                            <p class="error-text"></p>
                        </div>
                    </div>
                    <div class="form-width-1">
                        <div class="form-group textRight">
                            <!-- <button class="btn bg-primary" type="button" id="costing_edit_submit">Update</button> -->

                            <button class="btn bg-primary" type="submit">Update</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="tab_pane" id="scraploss">
        <div class="flex">
                <form action="" method="post">
                    <div class="form-width-1">
                        <div class="widget_title">
                            <h3>Scrap Items</h3>
                            <!-- <h3>Add Items</h3> -->
                        </div>
                        <div class="form-width-2">
                            <div class="form-group">
                                <label class="form-label">Select Materials Types</label>
                                <select name="rawmaterials_type" class="form_control" id="rawmaterials_type">
                                    <option value="0">select type</option>
                                    <option value="1">Raw Materials</option>
                                    <option value="2">Semi Finished</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 d-flex align-items-center p-2" id="product_container">
                        <div class="form-width-1">
                            <div class="form-group" id="invoice_product">
                                <label class="form-label" id="product_label">Raw Materials</label>
                                <div class="ajaxselectBox poR"
                                    data-ajax-url="<?= url_to('erp.crm.ajaxfetchrawmaterials'); ?>">
                                    <div class="ajaxselectBoxBtn flex">
                                        <div class="textFlow materials" data-default="select product">select materials
                                        </div>
                                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                        <input type="hidden" class="ajaxselectBox_Value field-check materials"
                                            name="raw_materials" value="0">
                                    </div>
                                    <div class="ajaxselectBox_Container alldiv">
                                        <input type="text" class="ajaxselectBox_Search form_control " />
                                        <ul role="listbox" class="listboxmaterials">
                                        </ul>
                                    </div>
                                </div>
                                <p class="error-text"></p>
                            </div>
                        </div>

                        <div class="form-width-1">
                            <div class="form-group" id="invoice_product_qty">
                                <label class="form-label">price</label>
                                <input type="text" class="form_control field-check" name="price" id="est_amount" />
                                <p class="error-text"></p>
                            </div>
                        </div>
                        <div class="form-width-1">
                            <div class="form-group" id="product_qty">
                                <label class="form-label">Quantity</label>
                                <input type="text" class="form_control field-check" name="quantitys" />
                                <p class="error-text"></p>
                            </div>
                        </div>
                        <div class="form-width-1">
                            <div class="form-group textRight">
                                <label class="form-label"></label>
                                <button class="btn outline-primary" type="button" id="add_item_btn">Add Item</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="total_amount" name="total_amount" value="0">
                    <div class="form-width-1">
                        <table class="table">
                            <thead>
                                <th>SNO</th>
                                <th>Raw Material</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Resource</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </thead>
                            <tbody id="invoice_items_holder">

                            </tbody>
                        </table>
                    </div>
                    <div class="form-width-1 mt-5">
                        <div class="form-group textRight">
                            <a href="<?= url_to('erp.mrp.planningview', $planning_id) ?>"
                                class="btn outline-secondary">Cancel</a>
                            <button class="btn bg-primary" type="button" id="bom_add_submit">Save</button>
                        </div>
                    </div>
                </form>
        </div>
    </div>


</div>
</div>
<!--SCRAP MODALS ADD-->


<div class="modal" id="scraps_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Scrap & Process Loss</h2>
        <?php
        echo form_open(url_to('erp.mrp.scrap.add'), array(
            "id" => "scrap_addedit_form",
            "class" => "flex"
        ));

        ?>

        <input type="hidden" name="scrap_id" id="f_scrap_id" value="0" />
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Item Code</label>
                <input type="text" class="form_control field-check" id="f_item_code" name="item_name" />
                <p class="error-text"></p>
            </div>
        </div>

        <input type="hidden" name="scrap_id" id="f_scrap_id" value="0" />
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Item Name</label>
                <input type="text" class="form_control field-check" id="f_item_name" name="item_name" />
                <p class="error-text"></p>
            </div>
        </div>


        <input type="hidden" name="scrap_id" id="f_scrap_id" value="0" />
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Quantity</label>
                <input type="text" class="form_control field-check" id="f_qty" name="qty" />
                <p class="error-text"></p>
            </div>
        </div>


        <input type="hidden" name="scrap_id" id="f_scrap_id" value="0" />
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Rate</label>
                <input type="text" class="form_control field-check" id="f_rate" name="rate" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="scrap_addedit_btn">Save</button>
            </div>
        </div>
        </form>


    </div>
</div>
<!-- MODAL ENDS -->
<!--SCRAP  MODAL ENDS -->

</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script type="text/javascript">
    let closer = new WindowCloser();
    closer.init();
    // let product_links = JSON.parse('<?php ///echo json_encode($product_links); ?>');
    let base_url = "<?php echo base_url(); ?>";


    let ajaxselectbox_elem = document.querySelector(".ajaxselectBox.poR");
    let ajaxselectbox = new AjaxSelectBox(ajaxselectbox_elem);
    let default_ajax_url = ajaxselectbox_elem.getAttribute("data-ajax-url");
    ajaxselectbox.init();
    closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    document.querySelectorAll(".selectBox.poR").forEach((item) => {
        let _selectbox = new SelectBox(item);
        _selectbox.init();
        closer.register_shutdown(_selectbox.shutdown, _selectbox.get_container());
    });

    $("#add_item_btn").attr("disabled", true);
    $(".ajaxselectBox_Search.form_control").attr("disabled", true);
    $("#rawmaterials_type").on("change", function () {
        $(".textFlow.materials").text() == "select materials" ? "select materials" : $(".textFlow.materials").text("");
        $(".textFlow.materials").text() == "select materials" ? "select materials" : $(".ajaxselectBox_Value.field-check.materials").val(0);
        ($(".textFlow.materials").text() == "select materials") ? "select materials" : $(".listboxmaterials").html("");
        $("#product_container").innerHTML = "";
        let type_mat = $(this).val();
        if (type_mat == 1) {
            $("#product_label").text("Raw Materials");
            $(".ajaxselectBox.poR").attr("data-ajax-url", "<?= url_to('erp.crm.ajaxfetchrawmaterials'); ?>");
            $("#add_item_btn").removeAttr("disabled");
            $(".ajaxselectBox_Search.form_control").attr("disabled", false);
        } else {
            $("#product_label").text("Semi Finished Materials");
            $(".ajaxselectBox.poR").attr("data-ajax-url", "<?= url_to('erp.crm.semifinished'); ?>");
            $("#add_item_btn").removeAttr("disabled");
            $(".ajaxselectBox_Search.form_control").attr("disabled", false);
        }
    });

    let est_quantity = document.getElementById("product_qty");
    let sno = 1;
    //
    let total_amount = document.getElementById("total_amount");
    document.getElementById("add_item_btn").onclick = (evt) => {

        let est_product = document.getElementById("invoice_product");
        let qty = parseInt(est_quantity.querySelector(".field-check").value);
        let unit_price = parseFloat(document.getElementById("est_amount").value);
        let product_id = est_product.querySelector(".ajaxselectBox_Value").value;
        let product_name = est_product.querySelector(".textFlow").textContent;

        if (product_id === null || product_id === undefined || product_id === "") {
            alert.invoke_alert("Select product", "error");
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
        console.log("qty = >", qty)
        console.log("price =>", unit_price)
        let rawmaterials_type = $("#rawmaterials_type").val();
        if (rawmaterials_type == 1) {
            tr += `<td><span>` + product_name + `</span><input type="hidden" name="product[` + sno + `][product_id]" value="` + product_id + `" /></td>`;
            tr += `<td><span>` + qty + `</span><input type="hidden" name="product[` + sno + `][quantity]" value="` + qty + `" /></td>`;
            tr += `<td><span>` + unit_price + `</span><input type="hidden" name="product[` + sno + `][price]" value="` + unit_price + `" /></td>`;
            tr += `<td><span>` + "rawmaterial" + `</span><input type="hidden" name="product[` + sno + `][product_type]" value="rawmaterial" /></td>`;
        } else {
            // console.log("2");
            tr += `<td><span>` + product_name + `</span><input type="hidden" name="product[` + sno + `][product_id]" value="` + product_id + `" /></td>`;
            tr += `<td><span>` + qty + `</span><input type="hidden" name="product[` + sno + `][quantity]" value="` + qty + `" /></td>`;
            tr += `<td><span>` + unit_price + `</span><input type="hidden" name="product[` + sno + `][price]" value="` + unit_price + `" /></td>`;
            tr += `<td><span>` + "semifinished" + `</span><input type="hidden" name="product[` + sno + `][product_type]" value="semifinished" /></td>`;
        }
        let amount = unit_price * qty;
        amount = amount.toFixed(2);
        tr += `<td><span>` + amount + `</span><input type="hidden" name="product[` + sno + `][amount]" value="` + amount + `" /></td>`;
        tr += `<td><button type="button" class="btn bg-danger product-remove-btn" ><i class="fa fa-trash"></i></button>`;
        let element = document.createElement("tr");
        element.innerHTML = tr;

        //
        let t_amount = total_amount.value;
        t_amount = Number(t_amount);
        amount = Number(amount);
        total_amount.value = t_amount + amount;
        // console.log("t_amount : ",total_amount.value);
        document.getElementById("invoice_items_holder").append(element);
    }
    document.getElementById("invoice_items_holder").onclick = (evt) => {
        let target = evt.target;
        document.querySelectorAll("#invoice_items_holder .product-remove-btn").forEach((item) => {
            if (item.contains(target)) {
                item.parentElement.parentElement.remove();
            }
        });
    }


    let datatable_elem = document.querySelector(".datatable");
    let rows_per_page = new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
    rows_per_page.init();
    closer.register_shutdown(rows_per_page.shutdown, rows_per_page.get_container());

    let bulkaction = new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
    bulkaction.init();
    closer.register_shutdown(bulkaction.shutdown, bulkaction.get_container());

    let config = JSON.parse('<?php //echo $scrap_datatable_config; ?>');
    let datatable = new DataTable(datatable_elem, config);
    datatable.init();
    rows_per_page.add_listener(datatable.rows_per_page, {});

    <?php
    if (session()->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        // let alert = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
        <?php
    } else if (session()->getFlashdata("op_error")) { ?>
            let alerts = new ModalAlert();
            alerts.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
        <?php
    }
    ?>
</script>


</body>

</html>