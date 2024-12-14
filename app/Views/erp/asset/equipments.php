<div class="alldiv flex widget_title">
    <h3>Equipments</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.equipment.add'); ?>" class="btn bg-success"><i class="fa fa-plus"></i> Add </a>
    </div>
</div>

<div class="alldiv">
    <div class="datatable" data-ajax-url="<?= url_to('erp.ajaxresponse.for.equipments') . "?" ?>">
        <div class="filterBox">
            <div class="flex">
                <h4>Filter and Search</h4>
                <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
            </div>
            <div class="flex filterBox_container">
                <div class="formWidth">
                    <div class="selectBox poR" id="equipment_filter_1">
                        <div class="selectBoxBtn flex">
                            <div class="textFlow" data-default="select status">select status</div>
                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                            <input type="hidden" class="selectBox_Value field-check" value="">
                        </div>
                        <ul role="listbox" class="selectBox_Container alldiv">
                            <?php
                            foreach ($equip_status as $key => $status) {
                            ?>
                                <li role="option" data-value="<?php echo $key; ?>"><?php echo $status; ?></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="formWidth">
                </div>
                <div class="formWidth">
                </div>
            </div>
        </div>

        
        <div class="tableHeader flex">
            <div class="formWidth">
                <div class="selectBox poR bulkaction" data-ajax-url="<?= url_to('erp.ajaxresponse.for.equipments') . "?" ?>">
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
                        <li><a data-default-href="<?php echo url_to('erp.equipment.exportEquipment').'?export=excel&'; ?>" href="<?php echo url_to('erp.equipment.exportEquipment').'?export=excel&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                        <li><a data-default-href="<?php echo url_to('erp.equipment.exportEquipment').'?export=pdf&'; ?>" href="<?php echo url_to('erp.equipment.exportEquipment').'?export=pdf&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                        <li><a data-default-href="<?php echo url_to('erp.equipment.exportEquipment').'?export=csv&'; ?>" href="<?php echo url_to('erp.equipment.exportEquipment').'?export=csv&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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







<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>

<script type="text/javascript">
    // Initialize WindowCloser
    let closer = new WindowCloser();
    closer.init();

    // Get datatable element
    let datatable_elem = document.querySelector(".datatable");

    // Initialize rows per page select box
    let rows_per_page = new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
    rows_per_page.init();
    closer.register_shutdown(rows_per_page.shutdown, rows_per_page.get_container());

    // Initialize bulk action select box
    let bulkaction = new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
    bulkaction.init();
    closer.register_shutdown(bulkaction.shutdown, bulkaction.get_container());

    // Parse equipment datatable config from PHP
    let config = JSON.parse('<?php echo $equip_datatable_config; ?>');

    // Initialize DataTable
    let datatable = new DataTable(datatable_elem, config);
    datatable.init();

    // Add listener for rows per page change
    rows_per_page.add_listener(datatable.rows_per_page, {});

    // Check if filters are defined in config
    if (config['filters'] !== null && config['filters'] !== undefined) {
        // Get filter element
        let filter_1 = document.getElementById("equipment_filter_1");

        // Initialize SelectBox for filter
        let select_box_1 = new SelectBox(filter_1);
        select_box_1.init();

        // Add listener for filter change
        select_box_1.add_listener(datatable.filter, {
            type: "select",
            column: config['filters'][0]
        });

        // Register shutdown for the filter
        closer.register_shutdown(select_box_1.shutdown, select_box_1.get_container());
    }

    <?php
    if (session()->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>