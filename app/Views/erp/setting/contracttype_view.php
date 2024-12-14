<div class="alldiv flex widget_title">
    <h3>Contract Type</h3>
    <div class="title_right">
        <a href="#" class="btn bg-success" data-toggle="modal" data-target="#contracttype_modal"><i class="fa fa-plus"></i> New Contract</a>
    </div>
</div>
<style>
    .form-control {
        display: block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .edit {
        position: relative;
    }

    .edit::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }

    .subject {
        display: block;
        width: 100%;
        padding: 7px 13px;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #C83B3B;
    }

    /* .form {
        display: none;
    } */
    .dropdown.tableAction {
        width: 90px;
    }

    .alert_1 {
        display: none;
    }

    /* .dropdown_container{
        margin-right: 75px;
    } */
    .modal.fade {
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.3);
        width: 100%;
        height: 100vh;
        z-index: 1000;
    }

    .modal-dialog {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        max-width: 500px;
        min-width: 280px;
        padding: 16px;
        z-index: 1500;
        border: 1px solid white;
        border-radius: 6px;
        background: white;
    }

    .modal-body {
        width: 100%;
    }

    .close {
        border: none;
        background: transparent;
    }

    hr {
        border-color: grey;

    }

    .btn.bg-secondary {
        padding: 8px 16px;
    }

    :focus-visible {
        outline-color: #4443453b;
        outline-width: 0;
    }
    .noneclass{
        display: none;
    }
    .update{
        display: none;
    }
</style>

<div class="alldiv">
    <div class="datatable" data-ajax-url="<?php echo url_to('erp.setting.contracttypeajax.datatype') . '?'; ?>">
        <div class="filterBox">
            <div class="flex">
                <h4>Filter and Search</h4>
                <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
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
                        <li><a data-default-href="<?php //echo url_to("erp.Announcements.announcementexport") . '?export=excel&'; 
                                                    ?>" href="<?php //echo base_url() . 'erp/Announcements/announcements-export?export=excel&';
                                                                ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                        <li><a data-default-href="<?php //echo url_to("erp.Announcements.announcementexport") . '?export=pdf&'; 
                                                    ?>" href="<?php //echo base_url() . 'erp/Announcements/announcements-export?export=pdf&';
                                                                ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                        <li><a data-default-href="<?php //echo url_to("erp.Announcements.announcementexport") . '?export=csv&'; 
                                                    ?>" href="<?php //echo base_url() . 'erp/Announcements/announcements-export?export=csv&';
                                                                ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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
    <!-- modal -->
    <div class="modal fade" id="contracttype_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title" id="exampleModalLabel">New Contract Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                            </svg></span>
                    </button>
                </div>
                <hr>
                <?php
                echo form_open(url_to("erp.setting.contracttype_add"), array(
                    "class" => "flex",
                    "id" => "contract_add_new"
                ));
                ?>
                <div class="modal-body">
                    <div class="form-group field-required mb-5">
                        <!-- name -->
                        <label class="form-label" id="form-alert">Contract Type Name
                            <small class="req text-danger">*</small>
                        </label>
                        <input type="text" name="id" id="id" hidden>
                        <?php echo  form_input('contract_name', '', 'id="contract_type_id" class = "form_control"'); ?>
                        <p class="alert_1 text-danger p-0" id="alert"><?php echo 'This field is required.'; ?></p>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
                        <button type="button" class="btn bg-secondary m-1" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-primary m-1" id="insert_id_btn">Save</button>
                        <button type="submit" class="btn bg-warning  m-1 update" id="update_id_btn">Update</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!--  -->

</div>
</div>
</div>



<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/across-tabs/1.4.0/across-tabs.min.js"></script>

<script>
    // data table handler



    let closer = new WindowCloser();
    closer.init();

    let datatable_elem = document.querySelector(".datatable");

    let rows_per_page = new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
    rows_per_page.init();
    closer.register_shutdown(rows_per_page.shutdown, rows_per_page.get_container());

    let bulkaction = new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
    bulkaction.init();
    closer.register_shutdown(bulkaction.shutdown, bulkaction.get_container());

    let config = JSON.parse('<?php echo $dt_config; ?>');
    let datatable = new DataTable(datatable_elem, config);
    datatable.init();

    rows_per_page.add_listener(datatable.rows_per_page, {});

    if (config['filters'] !== null && config['filters'] !== undefined) {
        let filter_1 = document.getElementById("lead_filter_1");
        let filter_2 = document.getElementById("lead_filter_2");

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

    function update(id, name) {
        $("#contract_type_id").val(name);
        $('#id').val(id);
        $('#update_id_btn').removeClass("update");
        $("#insert_id_btn").addClass("noneclass");

    }
    //modal submit
    $(document).ready(function() {
        $("#contract_add_new").submit(function(event) {
            event.preventDefault();

            var name = $("#contract_type_id").val();

            if (!name) {
                $("#contract_type_id").addClass("subject");
                $("#alert").removeClass("alert_1");
            } else {
                this.submit();
            }
        })
    });

    <?php if (session()->getFlashdata('op_success')) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getFlashdata('op_success') ?>", "success");
    <?php } elseif (session()->getFlashdata('op_error')) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?php echo session()->getFlashdata('op_error') ?>", "error");
    <?php } ?>

    //update onclick 
</script>
</body>

</html>