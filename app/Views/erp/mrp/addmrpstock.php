<div class="alldiv flex widget_title">
    <h3>Add Stock</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.mrp.planningview', $planning_id) ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <form action="<?= url_to('erp.mrp.addmrpstock', $planning_id) ?>" method="POST" class="flex" id="stock_add_form">
        <div class="form-width-2">
            <div class="form-group field-ajax" data-ajax-url="<?= url_to('erp.ajax.managestock') . '?' ?>">
                <label class="form-label">SKU</label>
                <input type="text" name="disabled" class="form_control field-check" value="<?= $product_code ?>"  disabled/>
                <input type="hidden" name="sku" value="<?= $product_code ?>">
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Warehouse</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select warehouse">select warehouse</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" name="warehouse_id" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($warehouses as $row) {
                        ?>
                            <li role="option" data-value="<?php echo $row['warehouse_id']; ?>"><?php echo $row['name']; ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Product Type</label>
                <div class="selectBox stock_product_type poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select type">select type</div>
                        <button class="close" type="button" disabled><i class="fa fa-close"></i></button>
                        <button class="drops" type="button" disabled><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" name="related_to" value="<?php echo "finished_good"; ?>">
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
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Product</label>
                <div class="ajaxselectBox stock_product_type poR" data-ajax-url="<?= url_to("erp.crm.ajaxfetchfinishedgoods") . $product_links[$first_product]; ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="select product"><?= $finishedgoodProduct[0]['product_name'] ?></div>
                        <button class="close" type="button" disabled><i class="fa fa-close"></i></button>
                        <button class="drops" type="button" disabled><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="ajaxselectBox_Value field-check" name="related_id" value="<?= $finishedgoodProduct[0]['finished_good_id'] ?>">
                    </div>
                    <div class="ajaxselectBox_Container alldiv">
                        <input type="text" class="ajaxselectBox_Search form_control" />
                        <ul role="listbox"></ul>
                    </div>
                </div>
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Price List</label>
                <div class="ajaxselectBox poR" data-ajax-url="<?= url_to('erp.procurement.ajaxfetchpricelist'); ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="select price">select price</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="ajaxselectBox_Value field-check" name="price_id" value="">
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
                <label class="form-label">Stock</label>
                <input type="text" name="stock" class="form_control field-check" value="<?= $stocks[0]['stock'] ?>" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Bin Name</label>
                <input type="text" name="bin_name" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Manufactured Date</label>
                <input type="date" name="mfg_date" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Batch No</label>
                <input type="text" name="batch_no" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Lot No</label>
                <input type="text" name="lot_no" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?= url_to('erp.mrp.planningview', $planning_id) ?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="stock_add_submit">Save</button>
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
    closer.init();

    let product_links = JSON.parse('<?php echo json_encode($product_links); ?>');
    let base_url = "<?php echo base_url(); ?>";


    
    let ajaxselectbox_elem = document.querySelector(".ajaxselectBox.stock_product_type");
    let ajaxselectbox = new AjaxSelectBox(ajaxselectbox_elem);
    let default_ajax_url = ajaxselectbox_elem.getAttribute("data-ajax-url");
    ajaxselectbox.init();
    closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());

    let selectbox_elem = document.querySelector(".selectBox.stock_product_type");
    let selectbox = new SelectBox(selectbox_elem);
    selectbox.init();
    selectbox.add_listener((params) => {
        let url = params['value'];
        if (url !== null && url !== undefined && url !== "") {
            ajaxselectbox_elem.setAttribute("data-ajax-url", base_url + product_links[url]);
        } else {
            ajaxselectbox_elem.setAttribute("data-ajax-url", default_ajax_url);
        }
    }, {});
    closer.register_shutdown(selectbox.shutdown, selectbox.get_container());

    document.querySelectorAll(".selectBox:not(.stock_product_type)").forEach((item) => {
        let _selectbox = new SelectBox(item);
        _selectbox.init();
        closer.register_shutdown(_selectbox.shutdown, _selectbox.get_container());
    });

    document.querySelectorAll(".ajaxselectBox:not(.stock_product_type)").forEach((item) => {
        let _ajaxselectbox = new AjaxSelectBox(item);
        _ajaxselectbox.init();
        closer.register_shutdown(_ajaxselectbox.shutdown, _ajaxselectbox.get_container());
    });



    let form = document.getElementById("stock_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("stock_add_submit").onclick = function(evt) {
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

    <?php
    if (session()->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?= session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?= session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>