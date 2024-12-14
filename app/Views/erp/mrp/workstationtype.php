<style>
    .form-control {
        display: block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143 !important;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form_control_modal {
        display: block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143 !important;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form-control-btn {
        display: block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-top-right-radius: 0px !important;
        border-bottom-right-radius: 0px !important;
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
    }

    .rupees {
        display: block;
        width: 4%;
        padding: 7px 11px;
        height: 100%;
        font-size: 14px;
        line-height: 1.37565;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 5px;
        border-top-right-radius: 5px;
    }

    .form_control_group {
        display: block;
        width: 100%;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 14px;
        line-height: 1.42857143 !important;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
    }

    .form-control-startdate,
    .form-control-enddate {
        display: inline-block;
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

    .form-control-text-area {
        display: inline-block;
        width: 100%;
        padding: 6px 12px;
        height: 80px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }


    .date-start,
    .date-end {
        padding: 0;
        width: 50%;
    }

    .date-start,
    .notification-group {
        padding: 0px 10px 0px 0px;
    }

    .btn-group.error input,
    .btn-group.error select {
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

    .btn-group.error input[type="date"] {
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
        border: 1px solid #C83B3B !important;
    }

    .btn-group select,
    .btn-group input {
        display: block;
        width: 100%;
        padding: 7px 13px;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
    }

    .plus.bg-primary {
        border-top-right-radius: 4px !important;
        border-bottom-right-radius: 4px !important;
        border-top-left-radius: 0px !important;
        border-bottom-left-radius: 0px !important;
        padding: 7px 13px;
    }

    .alert {
        display: none !important;
    }

    .alert_ct {
        display: none !important;
    }

    .btn-group.type {
        border-top-right-radius: 0px !important;
        border-bottom-right-radius: 0px !important;
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
    }



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
        max-width: 600px;
        min-width: 280px;
        padding: 16px;
        z-index: 1500;
        border: 1px solid white;
        /* overflow-Y: scroll; */
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

    .noneclass {
        display: none;
    }

    .form-group {
        width: 100%;
        margin: 0px 0px !important;
    }
</style>
<div class="alldiv flex widget_title">
    <h3>Workstation Type</h3>
    <div class="title_right">
        <button type="button" class="btn bg-success" data-bs-toggle="modal" data-bs-target="#electricityCostModal">
            <i class="fa fa-plus"></i> ADD
        </button>
    </div>
</div>
<div class="alldiv">
    <div class="datatable" data-ajax-url="<?php echo url_to('erp.mrp.workstationtypedatatable') . '?'; ?>">
        <div class="filterBox">
            <div class="flex">
                <h4>Filter and Search</h4>
                <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
            </div>
            <div class="flex filterBox_container">
                <div class="formWidth">
                </div>
            </div>
        </div>
        <div class="tableHeader flex">
            <div class="formWidth" style="display: none;">
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
                <div class="pagination"><span class="dt-page-start">1</span> - <span class="dt-page-end">5</span> of
                    <span class="dt-total-rows">100<span>
                </div>
                <ul class="flex paginationBtns">
                    <li><a type="button" class="HoverA dt-prev-btn"><i class="fa fa-angle-left"></i></a></li>
                    <li><a type="button" class="HoverA dt-next-btn"><i class="fa fa-angle-right"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- add -->
<div class="modal fade" id="electricityCostModal" tabindex="-1" aria-labelledby="electricityCostModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h2 class="modal-title" id="electricityCostModalLabel">Create Workstation Type</h2>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path
                                d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                        </svg></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="workstationtypetype_add" action="<?= url_to("erp.add.workstationtype") ?>" method="POST">
                    <div class="mb-3 mt-3 form-group field-required">
                        <div class="d-flex justify-content-left align-items-center">
                            <label for="workstation" class="form-label">Workstation Name</label>
                            <p class="error-text"></p>
                        </div>
                        <input type="text" class="form-control mt-1 field-check" id="workstation" name="name" value="">
                    </div>
                    <div class="mb-3 form-group field-required">
                        <div class="d-flex justify-content-left align-items-center">
                            <label for="powerRating" class="form-label">Electricity Cost (per hour)</label>
                            <p class="error-text"></p>
                        </div>
                        <input type="number" class="form-control mt-1 field-check" id="powerRating" name="ec" step="1"
                            value="" min="1">
                    </div>
                    <div class="mb-3 form-group field-required">
                        <div class="d-flex justify-content-left align-items-center">
                            <label for="duration" class="form-label">Rent Cost (per hour)</label>
                            <p class="error-text"></p>
                        </div>
                        <input type="number" class="form-control mt-1 field-check" id="duration" name="rc" step="1"
                            value="" min="1">
                    </div>
                    <div class="mb-3 form-group field-required">
                        <div class="d-flex justify-content-left align-items-center">
                            <label for="electricityRate" class="form-label">Consumable Cost (per hour)</label>
                            <p class="error-text"></p>
                        </div>
                        <input type="number" class="form-control mt-1 field-check" id="electricityRate" name="cc"
                            step="1" value="" min="1">
                    </div>
                    <div class="mb-3 form-group field-required">
                        <div class="d-flex justify-content-left align-items-center">
                            <label for="electricityCost" class="form-label">Wages (per hour)</label>
                            <p class="error-text"></p>
                        </div>
                        <input type="number" class="form-control mt-1 field-check" id="electricityCost" name="wage"
                            step="1" value="" min="1">
                    </div>
                    <div class="mb-3 form-group">
                        <label for="electricityCost" class="form-label">Description</label>
                        <textarea type="text" class="form-control-text-area mt-1" id="electricityCost"
                            name="desc"></textarea>
                    </div>
                    <button type="button" class="btn bg-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="add_form" class="btn bg-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- edit -->
<div class="modal fade" id="electricityCostModal_update" tabindex="-1"
    aria-labelledby="electricityCostModalLabel_update" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h2 class="modal-title" id="electricityCostModalLabel_update">update Workstation Type</h2>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path
                                d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                        </svg></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="workstationtypetype_update" action="<?= url_to("erp.edit.workstationtype") ?>" method="POST">
                    <input type="hidden" id="workstation_id" name="workstationid" value="">
                    <div class="mb-3 mt-3 form-group field-required">
                        <div class="d-flex justify-content-left align-items-center">
                            <label for="workstation_name" class="form-label">Workstation Name</label>
                            <p class="error-text"></p>
                        </div>
                        <input type="text" class="form-control mt-1 field-check" id="workstation_name" name="name"
                            value="">
                    </div>
                    <div class="mb-3 form-group field-required">
                        <div class="d-flex justify-content-left align-items-center">
                            <label for="ec_update" class="form-label">Electricity Cost (per hour)</label>
                            <p class="error-text"></p>
                        </div>
                        <input type="number" class="form-control mt-1 field-check" id="ec_update" name="ec" step="1"
                            value="" min="1">
                    </div>
                    <div class="mb-3 form-group field-required">
                        <div class="d-flex justify-content-left align-items-center">
                            <label for="rc_update" class="form-label">Rent Cost (per hour)</label>
                            <p class="error-text"></p>
                        </div>
                        <input type="number" class="form-control mt-1 field-check" id="rc_update" name="rc" step="1"
                            value="" min="1">
                    </div>
                    <div class="mb-3 form-group field-required">
                        <div class="d-flex justify-content-left align-items-center">
                            <label for="cc_update" class="form-label">Consumable Cost (per hour)</label>
                            <p class="error-text"></p>
                        </div>
                        <input type="number" class="form-control mt-1 field-check" id="cc_update" name="cc" step="1"
                            value="" min="1">
                    </div>
                    <div class="mb-3 form-group field-required">
                        <div class="d-flex justify-content-left align-items-center">
                            <label for="wage_update" class="form-label">Wages (per hour)</label>
                            <p class="error-text"></p>
                        </div>
                        <input type="number" class="form-control mt-1 field-check" id="wage_update" name="wage" step="1"
                            value="" min="1">
                    </div>
                    <div class="mb-3 form-group">
                        <label for="desc_update" class="form-label">Description</label>
                        <textarea type="text" class="form-control-text-area mt-1" id="desc_update"
                            name="desc"></textarea>
                    </div>
                    <button type="button" class="btn bg-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="edit_form" class="btn bg-primary">Save</button>
                </form>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

    let config = JSON.parse('<?php echo $datatable_config; ?>');

    let datatable = new DataTable(datatable_elem, config);
    datatable.init();

    rows_per_page.add_listener(datatable.rows_per_page, {});

    //formvalidator

    let add = document.getElementById("workstationtypetype_add");
    let validator = new FormValidate(add);
    let lock = false;
    document.getElementById("add_form").onclick = function (evt) {
        if (!lock) {
            lock = true;
            validator.validate(
                (params) => {
                    add.submit();
                    lock = false;
                },
                (params) => {
                    lock = false;
                }, {});
        }
    }

    let update = document.getElementById("workstationtypetype_update");
    let validator_edit = new FormValidate(update);
    let lock_edit = false;
    document.getElementById("edit_form").onclick = function (evt) {
        if (!lock_edit) {
            lock_edit = true;
            validator_edit.validate(
                (params) => {
                    update.submit();
                    lock_edit = false;
                },
                (params) => {
                    lock_edit = false;
                }, {});
        }
    }

    function edit_model(id) {
        if (id != "" || id !== 0) {
            $.ajax({
                url: "<?= url_to('erp.mrp.workstation.data') ?>",
                type: "post",
                data: {
                    w_id: id
                },
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        let exist_data = response.data;
                        $("#workstation_id").val(exist_data.id);
                        $("#workstation_name").val(exist_data.name);
                        $("#ec_update").val(exist_data.electricity_cost);
                        $("#rc_update").val(exist_data.rent_cost);
                        $("#cc_update").val(exist_data.consumable_cost);
                        $("#wage_update").val(exist_data.wages_cost);
                        $("#desc_update").val(exist_data.description);
                        $("#electricityCostModal_update").modal("show");

                    } else {
                        console.log("opps something went wrong");
                    }
                }, error: function (xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            })
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