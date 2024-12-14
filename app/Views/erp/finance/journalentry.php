<div class="alldiv flex widget_title">
    <h3>Journal Entry</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.finance.journalentryadd'); ?>" class="btn bg-success"><i class="fa fa-plus"></i> Add </a>
    </div>
</div>

<div class="alldiv">
    <div class="datatable" data-ajax-url="<?php echo url_to('erp.finance.ajaxjournalentryresponse').'?'; ?>">
        <div class="filterBox">
            <div class="flex">
                <h4>Filter and Search</h4>
                <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
            </div>
            <div class="flex filterBox_container">
                <div class="formWidth">
                    <div class="form-group">
                        <label class="form-label">Transaction Type</label>
                        <div class="selectBox poR " id="journalentry_filter_1">
                            <div class="selectBoxBtn flex">
                                <div class="textFlow" data-default="select transaction">select transaction</div>
                                <button class="close" type="button"><i class="fa fa-close"></i></button>
                                <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" class="selectBox_Value field-check" value="">
                            </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                                <li role="option" data-value="credit">Credit</li>
                                <li role="option" data-value="debit">Debit</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="formWidth">
                    <div class="form-group">
                        <label class="form-label">Journal Type</label>
                        <div class="selectBox poR" id="journalentry_filter_2">
                            <div class="selectBoxBtn flex">
                                <div class="textFlow" data-default="select type">select type</div>
                                <button class="close" type="button"><i class="fa fa-close"></i></button>
                                <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" class="selectBox_Value field-check" value="">
                            </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                                <li role="option" data-value="0">Normal</li>
                                <li role="option" data-value="1">Revising</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="formWidth">
                    <div id="journalentry_filter_3" class="form-group">
                        <label class="form-label">Transaction From</label>
                        <input type="date" class="form_control" />
                    </div>
                </div>
                <div class="formWidth">
                    <div id="journalentry_filter_4" class="form-group">
                        <label class="form-label">Transaction To</label>
                        <input type="date" class="form_control" />
                    </div>
                </div>
                <div class="formWidth">
                    <div id="journalentry_filter_5" class="form-group">
                        <label class="form-checked-label"><input type="checkbox" value="0" /> Exclude Posted ?</label>
                    </div>
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
                        <li><a data-default-href="<?php echo base_url() . 'erp/finance/journalentry-export?export=excel&'; ?>" href="<?php echo base_url() . 'erp/finance/journalentry-export?export=excel&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                        <li><a data-default-href="<?php echo base_url() . 'erp/finance/journalentry-export?export=pdf&'; ?>" href="<?php echo base_url() . 'erp/finance/journalentry-export?export=pdf&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                        <li><a data-default-href="<?php echo base_url() . 'erp/finance/journalentry-export?export=csv&'; ?>" href="<?php echo base_url() . 'erp/finance/journalentry-export?export=csv&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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




<!--MODALS-->
<div class="modal" id="accgroup_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Account Groups</h2>
        <?php
        echo form_open(url_to('erp.finance.accountgroups'), array(
            "id" => "accgroup_addedit_form",
            "class" => "flex"
        ));
        ?>
        <input type="hidden" name="acc_group_id" id="f_acc_group_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Group Name</label>
                <input type="text" class="form_control field-check" id="f_group_name" name="group_name" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Base</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select base">select base</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" id="f_base_id" class="selectBox_Value field-check" name="base_id" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($accountbase as $base) {
                        ?>
                            <li role="option" data-value="<?php echo $base['base_id']; ?>"><?php echo $base['base_name']; ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-checked-any">
                <label class="form-label">Profit/Loss</label>
                <div id="f_profit_loss">
                    <label class="form-check-label"><input type="radio" class="field-check" name="profit_loss" value="1" /> Yes </label>
                    <label class="form-check-label"><input type="radio" class="field-check" name="profit_loss" value="0" /> No </label>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="accgroup_addedit_btn">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>
<!-- MODAL ENDS-->


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

    let config = JSON.parse('<?php echo $entry_datatable_config; ?>');
    let datatable = new DataTable(datatable_elem, config);
    datatable.init();

    rows_per_page.add_listener(datatable.rows_per_page, {});

    if (config['filters'] !== null && config['filters'] !== undefined) {
        let filter_1 = document.getElementById("journalentry_filter_1");
        let filter_2 = document.getElementById("journalentry_filter_2");
        let filter_3 = document.getElementById("journalentry_filter_3");
        let filter_4 = document.getElementById("journalentry_filter_4");
        let filter_5 = document.getElementById("journalentry_filter_5");

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

        filter_3.querySelector("input[type=date]").onchange = (evt) => {
            let value = evt.target.value;
            if (value !== null && value !== undefined && value !== "") {
                datatable.filter({
                    type: "checkbox",
                    column: config['filters'][3],
                    value: value
                });
            } else {
                datatable.filter({
                    type: "checkbox",
                    column: config['filters'][3],
                    value: ""
                });
            }
        }
        filter_4.querySelector("input[type=date]").onchange = (evt) => {
            let value = evt.target.value;
            if (value !== null && value !== undefined && value !== "") {
                datatable.filter({
                    type: "checkbox",
                    column: config['filters'][4],
                    value: value
                });
            } else {
                datatable.filter({
                    type: "checkbox",
                    column: config['filters'][4],
                    value: ""
                });
            }
        }
        filter_5.querySelector("input[type=checkbox]").onclick = (evt) => {
            let checked = evt.target.checked;
            if (checked) {
                datatable.filter({
                    type: "checkbox",
                    column: config['filters'][2],
                    value: "0"
                });
            } else {
                datatable.filter({
                    type: "checkbox",
                    column: config['filters'][2],
                    value: ""
                });
            }
        }
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