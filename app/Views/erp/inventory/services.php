<div class="alldiv flex widget_title">
    <h3>Services</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.inventory.serviceadd'); ?>" class="btn bg-success"><i class="fa fa-plus"></i> Add </a>
    </div>
</div>

<div class="alldiv">
    <div class="datatable" data-ajax-url="<?php echo url_to('erp.inventory.ajaxservicesresponse') . '?'; ?>">
        <div class="filterBox">
            <div class="flex">
                <h4>Filter and Search</h4>
                <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
            </div>
            <div class="flex filterBox_container">
                <div class="formWidth">
                    <div class="selectBox poR" id="service_filter_1">
                        <div class="selectBoxBtn flex">
                            <div class="textFlow" data-default="select group">select group</div>
                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                            <input type="hidden" class="selectBox_Value" value="">
                        </div>
                        <ul role="listbox" class="selectBox_Container alldiv">
                            <?php
                            foreach ($service_groups as $group) {
                            ?>
                                <li role="option" data-value="<?php echo $group['group_id']; ?>"><?php echo $group['group_name']; ?></li>
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
                <div class="selectBox poR bulkaction" data-ajax-url="">
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
                        <li><a data-default-href="<?php echo base_url() . 'erp/inventory/services-export?export=excel&'; ?>" href="<?php echo base_url() . 'erp/inventory/service_export?export=excel&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                        <li><a data-default-href="<?php echo base_url() . 'erp/inventory/services-export?export=pdf&'; ?>" href="<?php echo base_url() . 'erp/inventory/service_export?export=pdf&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                        <li><a data-default-href="<?php echo base_url() . 'erp/inventory/services-export?export=csv&'; ?>" href="<?php echo base_url() . 'erp/inventory/service_export?export=csv&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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
    let closer = new WindowCloser();
    closer.init();

    let datatable_elem = document.querySelector(".datatable");
    let rows_per_page = new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
    rows_per_page.init();
    closer.register_shutdown(rows_per_page.shutdown, rows_per_page.get_container());

    let bulkaction = new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
    bulkaction.init();
    closer.register_shutdown(bulkaction.shutdown, bulkaction.get_container());

    let config = JSON.parse('<?php echo $service_datatable_config; ?>');
    let datatable = new DataTable(datatable_elem, config);
    datatable.init();

    rows_per_page.add_listener(datatable.rows_per_page, {});

    if (config['filters'] !== null && config['filters'] !== undefined) {
        let filter_1 = document.getElementById("service_filter_1");

        let select_box_1 = new SelectBox(filter_1);
        select_box_1.init();
        select_box_1.add_listener(datatable.filter, {
            type: "select",
            column: config['filters'][0]
        });
        closer.register_shutdown(select_box_1.shutdown, select_box_1.get_container());
    }

    <?php
    if (session()->getflashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getflashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getflashdata("op_error")) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?php echo session()->getflashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>