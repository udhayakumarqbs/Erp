<div class="alldiv flex widget_title">
    <h3>Current Stock</h3>
    <div class="title_right">
    
    </div>
</div>

<div class="alldiv">
    <div class="datatable" data-ajax-url="<?= url_to('erp.warehouse.currentstock.datatable'). '?'; ?>">
        <div class="filterBox">
            <div class="flex">
                <h4>Filter and Search</h4>
                <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
            </div>
            <div class="flex filterBox_container">
                <div class="formWidth">
                    <div class="selectBox poR" id="currentstock_filter_1">
                        <div class="selectBoxBtn flex">
                            <div class="textFlow" data-default="select type">select type</div>
                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                            <input type="hidden" class="selectBox_Value field-check" name="" value="">
                        </div>
                        <ul role="listbox" class="selectBox_Container alldiv">
                            <?php
                            foreach ($product_types as $key => $value) {
                            ?>
                                <li role="option" data-value="<?php echo $key; ?>"><?php echo $value; ?></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="formWidth">
                    <div class="selectBox poR" id="currentstock_filter_2" data-ajax-url="<?php echo url_to('erp.warehouse.currentstock.datatable').'?' ;?>">
                        <div class="selectBoxBtn flex">
                            <div class="textFlow" data-default="select warehouse">select warehouse</div>
                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                            <input type="hidden" class="selectBox_Value field-check" name="" value="">
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
                </div>
                <div class="formWidth">

                </div>
            </div>
        </div>
        <div class="tableHeader flex">
            <div class="formWidth">
                <div class="selectBox poR bulkaction" data-ajax-url="<?= url_to('erp.warehouse.currentstock.datatable') . '?'; ?>">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="Action">Action</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <li role="option" data-value="1">Delete</li>
                        <li role="option" data-value="2">Send</li>
                    </ul>
                </div>
            </div>
            <div class="formWidth">
                <input type="text" placeholder="search" class="form_control dt-search">
            </div>
            <div class="formWidth textRight">
                <!--export button-->
                <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i> Export</a>
                <div class="export_container poF">
                    <ul class="exportUl">
                        <li><a data-default-href="<?= url_to('erp.warehouse.currentstock.exportcs').'?export=excel&'; ?>" href="<?= url_to('erp.warehouse.currentstock.exportcs').'?export=excel&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                        <li><a data-default-href="<?= url_to('erp.warehouse.currentstock.exportcs').'?export=pdf&'; ?>" href="<?= url_to('erp.warehouse.currentstock.exportcs').'?export=pdf&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                        <li><a data-default-href="<?= url_to('erp.warehouse.currentstock.exportcs').'?export=csv&'; ?>" href="<?= url_to('erp.warehouse.currentstock.exportcs').'?export=csv&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
                    </ul>
                    <a type="button" class="closeBtn3 HoverA"><i class="fa fa-close"></i></a>
                </div>
                <!--export button-->
            </div>
        </div>
        <div class="table_responsive">
            <table class="table">
                <thead class="thead">

                </thead>
                <tbody class="table-paint-area">

                </tbody>
            </table>
        </div>
        <div class="tableFooter flex">
            <div class="tableFooterLeft flex">
                <p>Rows per page:</p>
                <div class="selectBox miniSelectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="10">10</div>
                        <button class="drops"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value" value="10">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <li role="option" class="active" data-value="10">10</li>
                        <li role="option" data-value="15">15</li>
                        <li role="option" data-value="20">20</li>
                        <li role="option" data-value="25">25</li>
                    </ul>
                </div>
            </div>
            <div class="tableFooterRight flex">
                <div class="pagination"><span class="dt-page-start">1</span> - <span class="dt-page-end">5</span> of <span class="dt-total-rows">100<span></div>
                <ul class="flex paginationBtns">
                    <li><a type="button" class="HoverA dt-prev-btn"><i class="fa fa-angle-left"></i></a></li>
                    <li><a type="button" class="HoverA dt-next-btn"><i class="fa fa-angle-right"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
</div>


<!--MODAL STARTS -->
<div class="modal" id="stock_transfer_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Stock Transfer</h2>
        <form action="<?= url_to('erp.warehouse.currentstock.convert') ?>" method="POST" class="flex" id="stock_transfer_form">
            <input type="hidden" name="stock_id" id="f_stock_id" value="0" />
            <div class="form-width-1">
                <div class="form-group field-required ">
                    <label class="form-label">To Warehouse</label>
                    <div class="selectBox poR">
                        <div class="selectBoxBtn flex">
                            <div class="textFlow" data-default="select warehouse">select warehouse</div>
                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                            <input type="hidden" class="selectBox_Value field-check" id="f_warehouse_id" name="warehouse_id" value="">
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
            <div class="form-width-1">
                <div class="form-group field-number">
                    <label class="form-label">Quantity</label>
                    <input type="text" class="form_control field-check" id="f_quantity" value="0" name="quantity"></textarea>
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1 ">
                <div class="form-group textRight ">
                    <button type="button" class="btn outline-danger modalClose">Close</button>
                    <button class="btn bg-primary" type="button" id="stock_transfer_btn">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- MODAL ENDS -->




<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>

<script type="text/javascript">
    let closer = new WindowCloser();
    closer.init();

    let modal_box = new ModalBox(document.getElementById("stock_transfer_modal"));
    modal_box.init();
    let selectboxes = [];
    document.querySelectorAll("#stock_transfer_modal .selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        selectboxes.push(selectbox);
        closer.register_shutdown(selectbox.shutdown, selectbox.get_container());
    })

    let paintarea = document.querySelector(".table-paint-area");
    paintarea.onclick = (evt) => {
        let target = evt.target;
        paintarea.querySelectorAll(".modalBtn").forEach((item) => {
            if (item.contains(target)) {
                stock_id = item.getAttribute("data-stock-id");
                document.getElementById("f_stock_id").value = stock_id;
                document.getElementById("f_quantity").value = 0;
                document.getElementById("f_warehouse_id").value = "";
                for (let i = 0; i < selectboxes.length; i++) {
                    selectboxes[i].construct();
                }
                modal_box.show_modal();
            }
        });
    };

    let form = document.getElementById("stock_transfer_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("stock_transfer_btn").onclick = (evt) => {
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

    let datatable_elem = document.querySelector(".datatable");

    let rows_per_page = new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
    rows_per_page.init();
    closer.register_shutdown(rows_per_page.shutdown, rows_per_page.get_container());

    let bulkaction = new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
    bulkaction.init();
    closer.register_shutdown(bulkaction.shutdown, bulkaction.get_container());

    let config = JSON.parse('<?php echo $currentstock_datatable_config; ?>');
    let datatable = new DataTable(datatable_elem, config);
    datatable.init();
    rows_per_page.add_listener(datatable.rows_per_page, {});

    if (config['filters'] !== null && config['filters'] !== undefined) {
        let filter_1 = document.getElementById("currentstock_filter_1");
        let filter_2 = document.getElementById("currentstock_filter_2");

        let select_box_1 = new SelectBox(filter_1);
        select_box_1.init();
        select_box_1.add_listener(datatable.filter, {
            type: "select",
            column: config['filters'][0]
        });
        closer.register_shutdown(select_box_1.shutdown, select_box_1.get_container());

        let select_box_2 = new SelectBox(filter_2);
        select_box_2.init();
        select_box_2.add_listener(datatable.filter, {
            type: "select",
            column: config['filters'][1]
        });
        closer.register_shutdown(select_box_2.shutdown, select_box_2.get_container());
    }

    <?php
    if (session()->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?=session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?=session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>