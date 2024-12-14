<div class="alldiv flex widget_title">
    <h3><?= $title ?? "On Going.." ?></h3>
    <div class="title_right">
        <!-- <a href="#" class="btn bg-success"><i class="fa-solid fa-square-caret-right"></i> Start Forecasting </a> -->
    </div>
</div>


<div class="alldiv">
    <div class="datatable">
        <form method="POST" action="<?= url_to('erp.mrp.forecasting.post') ?>">
            <div class="filterBox">
                <div class="flex">
                    <h4>Select Product For Forecast</h4>
                    <a type='button' class="filterIcon HoverA" title="Filter open/close"><i
                            class="fa fa-filter"></i></a>
                </div>

                <div class="flex filterBox_container">
                    <div class="formWidth px-2">
                        <div class="form-group field-required">
                            <label class="form-label">Product Type</label>
                            <div class="selectBox stock_product_type poR">
                                <div class="selectBoxBtn flex">
                                    <div class="textFlow" data-default="select type">select type</div>
                                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" class="selectBox_Value field-check" name="related_to" value="">
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
                    <div class="formWidth px-2">
                        <div class="form-group field-required">
                            <label class="form-label">Select Product</label>
                            <div class="ajaxselectBox stock_product_type poR"
                                data-ajax-url="<?= url_to("erp.crm.ajaxfetchfinishedgoods") . $product_links[$first_product]; ?>">
                                <div class="ajaxselectBoxBtn flex">
                                    <div class="textFlow" data-default="select product">select product</div>
                                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" class="ajaxselectBox_Value field-check" name="related_id"
                                        value="">
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

                    <div class="formWidth px-2">
                        <div class="form-group field-required">
                            <label class="form-label">From Date</label>
                            <input type="date" name="fromDate" min="<?= $min_year ?>" max="<?= $max_year ?>"
                                class="form_control field-check">

                        </div>
                    </div>
                    <div class="formWidth px-2">
                        <div class="form-group field-required">
                            <label class="form-label">To Date</label>
                            <input type="date" name="toDate" min="<?= $min_year ?>" max="<?= $max_year ?>"
                                class="form_control field-check">
                        </div>
                    </div>
                </div>

                <div class="title_right">
                    <button type="submit" name="start_forecast" class="btn bg-success"><i
                            class="fa-solid fa-square-caret-right"></i> Start Forecasting </button>
                </div>

            </div>
        </form>

        <div class="tableHeader flex">

            <div class="formWidth textRight justify-content-end">
                <!--export button-->
                <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i> Export Forecast
                    Data As Excel</a>
                <div class="export_container justify-content-center poF">
                    <ul class="exportUl justify-content-center">
                        <li><a data-default-href="<?php echo url_to('erp.mrp.forecasting.excel') ?>"
                                href="<?php echo url_to('erp.mrp.forecasting.excel'); ?>" target="_BLANK"><img
                                    src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>"
                                    alt="excel">EXCEL</a></li>
                    </ul>
                    <a type="button" class="closeBtn3 HoverA"><i class="fa fa-close"></i></a>
                </div>
                <!--export button-->
            </div>
        </div>




        <!-- Table -->
        <div class="table_responsive">
            <table class="table">
                <thead class="thead">
                    <tr>
                        <th>SNO</th>
                        <th>TIMESTAMP</th>
                        <th>ON HAND STOCKS</th>
                        <th>REQUIREMENTS</th>
                        <th>EXPECTED ON DEMAND</th>
                        <th>TO BE SCHEDULED</th>
                    </tr>
                </thead>
                <tbody class="table-paint-area">




                    <?php
                    if (isset($forcasted_table) && !empty($forcasted_table)) {
                        foreach ($forcasted_table as $data) {
                            foreach ($data as $td) {
                                echo $td;
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<div class="alldiv flex widget_title">
    <h4>Note:</h4>
    <p>The forecasting provided here is based on assumptions and may not be 100% accurate. It serves as an estimate and
        should be used with caution for decision-making.</p>
</div>

</div>


</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script>
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

    let datatable_elem = document.querySelector(".datatable");

    // let rows_per_page = new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
    // rows_per_page.init();
    // closer.register_shutdown(rows_per_page.shutdown, rows_per_page.get_container());

    // let config = JSON.parse('<?php //echo $forecast_datatable_config; ?>');
    // let datatable = new DataTable(datatable_elem, config);
    // datatable.init();

    // rows_per_page.add_listener(datatable.rows_per_page, {});
</script>
<script>
    <?php
    if (session()->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getFlashdata("op_success"); ?>", "success");
        <?php
    } else if (session()->getFlashdata("op_error")) { ?>
            let alert = new ModalAlert();
            alert.invoke_alert("<?php echo session()->getFlashdata("op_error"); ?>", "error");
        <?php
    }
    ?>
</script>
</body>

</html>