<div class="alldiv flex widget_title">
    <h3>Activity Log</h3>
</div>

<!-- <div class="alldiv d-none">
    <div class="filterBox">
        <div class="text-md-right mb-2 mr-3 text-right">
            <a href="<?= url_to('erp.setting.activityDelete'); ?>" class="btn bg-danger del-confirm"><i class="fa fa-trash mr-2 "></i>Delete</a>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-striped" id="data_table">
                <thead>
                    <tr>
                        <td class="font-weight-bold">S. No</td>
                        <td class="font-weight-bold">Action</td>
                        <td class="font-weight-bold">Created At</td>
                        <td class="font-weight-bold">Created By</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sno = 0;
                    if (!empty($allData)) :
                        foreach ($allData as $row) : ?>
                            <tr>
                                <td><?= ++$sno; ?></td>
                                <td><?= trim($row['log_text'], '[]'); ?></td>
                                <td><?= $row['created_at']; ?></td>
                                <td><?= $row['done_by']; ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div> -->

<div class="alldiv">
    <div class="datatable" data-ajax-url="<?= url_to('erp.settings.activity') . '?'; ?>">
        <div class="filterBox">
            <div class="flex">
                <h4>Filter and Search</h4>
                <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>


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
                            <li><a data-default-href="<?= url_to('erp.sale.credit_export') . '?export=excel&'; ?>" href="<?= url_to('erp.sale.credit_export') . '?export=excel&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                            <li><a data-default-href="<?= url_to('erp.sale.credit_export') . '?export=pdf&'; ?>" href="<?= url_to('erp.sale.credit_export') . '?export=pdf&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                            <li><a data-default-href="<?= url_to('erp.sale.credit_export') . '?export=csv&'; ?>" href="<?= url_to('erp.sale.credit_export') . '?export=csv&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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
<!-- <script>
    $(document).ready(function() {
        $('#data_table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel', 'csv', 'pdf', 'print'
            ],

        });
    });
</script> -->

<script>
    let alert = new ModalAlert();
    let closer = new WindowCloser();
    closer.init();


    let datatable_elem = document.querySelector(".datatable");

    let rows_per_page = new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
    rows_per_page.init();
    closer.register_shutdown(rows_per_page.shutdown, rows_per_page.get_container());
    let bulkaction = new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
    bulkaction.init();
    closer.register_shutdown(bulkaction.shutdown, bulkaction.get_container());
    let config = JSON.parse('<?php echo $data_config; ?>');
    let datatable = new DataTable(datatable_elem, config);
    datatable.init();
    rows_per_page.add_listener(datatable.rows_per_page, {});
</script>


</body>

</html>