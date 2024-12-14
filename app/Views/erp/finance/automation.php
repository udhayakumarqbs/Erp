<div class="alldiv flex widget_title">
    <h3>Automation</h3>
    <div class="title_right">
        <a href="#" id="automation_modal_invoker1" class="btn bg-success"><i class="fa fa-plus"></i> Add </a>
    </div>
</div>

<div class="alldiv">
    <div class="datatable" data-ajax-url="<?php echo url_to('erp.finance.ajaxautomationresponse').'?'; ?>">
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
                            <li><a data-default-href="<?php echo base_url() . 'erp/finance/automation-export?export=excel&'; ?>" href="<?php echo base_url() . 'erp/finance/automation-export?export=excel&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                            <li><a data-default-href="<?php echo base_url() . 'erp/finance/automation-export?export=pdf&'; ?>" href="<?php echo base_url() . 'erp/finance/automation-export?export=pdf&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                            <li><a data-default-href="<?php echo base_url() . 'erp/finance/automation-export?export=csv&'; ?>" href="<?php echo base_url() . 'erp/finance/automation-export?export=csv&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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
<div class="modal" id="automation_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Automation</h2>
        <?php
        echo form_open(url_to('erp.finance.automation'), array(
            "id" => "automation_addedit_form",
            "class" => "flex modal-scroll-form"
        ));
        ?>
        <input type="hidden" name="autotrans_id" id="f_autotrans_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Transaction Name</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select transaction">select transaction</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" id="f_trans_id" class="selectBox_Value field-check" name="trans_id" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($autotranslist as $trans) {
                        ?>
                            <li role="option" data-value="<?php echo $trans['trans_id']; ?>"><?php echo $trans['transaction_name']; ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Debit GL Account</label>
                <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.finance.ajaxfetchglaccounts'); ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="GL account">GL account</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" id="f_debit_account" class="ajaxselectBox_Value field-check" name="debit_gl_account" value="">
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
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Credit GL Account</label>
                <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.finance.ajaxfetchglaccounts'); ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="GL account">GL account</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" id="f_credit_account" class="ajaxselectBox_Value field-check" name="credit_gl_account" value="">
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
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label"></label>
                <label class="form-check-label"><input id="f_auto_posting" type="checkbox" name="auto_posting" value="1" /> Enable auto posting? (use with caution)</label>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="automation_addedit_btn">Save</button>
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
    let ajaxselectboxes = [];
    let selectboxes = [];
    document.querySelectorAll("#automation_addedit_modal .ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectboxes.push(ajaxselectbox);
        ajaxselectbox.init();
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    document.querySelectorAll("#automation_addedit_modal .selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectboxes.push(selectbox);
        selectbox.init();
        closer.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    let automation_modal = new ModalBox(document.getElementById("automation_addedit_modal"));
    automation_modal.init();

    document.getElementById("automation_modal_invoker1").onclick = (evt) => {
        document.getElementById("f_autotrans_id").value = "0";
        document.getElementById("f_trans_id").value = "";
        document.getElementById("f_debit_account").value = "";
        document.getElementById("f_credit_account").value = "";
        document.getElementById("f_auto_posting").checked = false;
        for (let i = 0; i < ajaxselectboxes.length; i++) {
            ajaxselectboxes[i].construct();
        }
        for (let i = 0; i < selectboxes.length; i++) {
            selectboxes[i].construct();
        }
        automation_modal.show_modal();
    };

    let automation_paintarea = document.querySelector(".table-paint-area");
    automation_paintarea.onclick = (evt) => {
        let target = evt.target;
        automation_paintarea.querySelectorAll(".modalBtn").forEach((item) => {
            if (item.contains(target)) {
                let ajax_url = item.getAttribute("data-ajax-url");
                let xhr = null;
                if (window.XMLHttpRequest) {
                    xhr = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    xhr = new ActiveXObject("Msxml2.XMLHTTP");
                }
                if (xhr !== null && xhr !== undefined) {
                    xhr.open("GET", ajax_url, true);
                    xhr.send(null);
                    xhr.onreadystatechange = (evt) => {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            let json = JSON.parse(xhr.responseText);
                            if (json['error'] == 0) {
                                let data = json['data'];
                                document.getElementById("f_autotrans_id").value = data['autotrans_id'];
                                document.getElementById("f_trans_id").value = data['trans_id'];
                                document.getElementById("f_debit_account").value = data['debit_gl_account'];
                                document.getElementById("f_credit_account").value = data['credit_gl_account'];
                                document.getElementById("f_debit_account").previousElementSibling.previousElementSibling.previousElementSibling.textContent = data['debit_account'];
                                document.getElementById("f_credit_account").previousElementSibling.previousElementSibling.previousElementSibling.textContent = data['credit_account'];
                                document.getElementById("f_auto_posting").checked = false;
                                if (data['auto_posting'] == 1) {
                                    document.getElementById("f_auto_posting").checked = true;
                                }
                                for (let i = 0; i < ajaxselectboxes.length; i++) {
                                    ajaxselectboxes[i].construct();
                                }
                                for (let i = 0; i < selectboxes.length; i++) {
                                    selectboxes[i].construct();
                                }
                                automation_modal.show_modal();
                            }
                        }
                    }
                }
            }
        });
    };



    let automation_form = document.getElementById("automation_addedit_form");
    let automation_validator = new FormValidate(automation_form);

    let automation_lock = false;
    document.getElementById("automation_addedit_btn").onclick = (evt) => {
        if (!automation_lock) {
            automation_lock = true;
            automation_validator.validate(
                (params) => {
                    automation_form.submit();
                    automation_lock = false;
                },
                (params) => {
                    automation_lock = false;
                }, {});
        }
    }

    function load_active_toggler_1(params) {
        automation_paintarea.querySelectorAll(".active-toggler-1").forEach((item) => {
            item.querySelector("input[type=checkbox]").onchange = (evt) => {
                let ajax_url = item.getAttribute("data-ajax-url");
                let toggle_state = item.querySelector("input[type=checkbox]").checked;

                let xhr = null;
                if (window.XMLHttpRequest) {
                    xhr = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    xhr = new ActiveXObject("Msxml2.XMLHTTP");
                }
                if (xhr !== null && xhr !== undefined) {
                    xhr.open("GET", ajax_url, true);
                    xhr.send(null);
                    xhr.onreadystatechange = (evt) => {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            let json = JSON.parse(xhr.responseText);
                            if (json['error'] == 1) {
                                item.querySelector("input[type=checkbox]").checked = toggle_state;
                            }
                        }
                    }
                }
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

    let config = JSON.parse('<?php echo $automation_datatable_config; ?>');
    let datatable = new DataTable(datatable_elem, config);
    datatable.init();

    rows_per_page.add_listener(datatable.rows_per_page, {});
    datatable.add_listener({
        cb: load_active_toggler_1,
        params: {}
    });

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