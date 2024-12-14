<div class="alldiv flex widget_title">
    <h3>View Planning</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.mrp.planningschedule'); ?>" class="btn bg-success"><i class="fa fa-reply"></i>
            Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="service_info">Info</a></li>
        <!-- <li><a type="button" class="tab_nav_item" data-src="mob">Scheduling</a></li> -->
        <li><a type="button" class="tab_nav_item" data-src="bom">BOM</a></li>
        <!-- <li><a type="button" class="tab_nav_item" data-src="operations">Operations</a></li> -->
        <!-- <li><a type="button" class="tab_nav_item" data-src="scrap">Scrap & Process Loss</a></li> -->
        <!-- <li><a type="button" class="tab_nav_item" data-src="quality">Quality Check</a></li> -->
    </ul>

    <div class="tab_content">
        <div class="tab_pane active" id="service_info">
            <div class="flex">
                <div class="form-width-1">
                    <h2>Planning Info</h2>
                    <div class="table_responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Product</th>
                                    <td><?php echo $planning->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Start date</th>
                                    <td><?php echo $planning->start_date; ?></td>
                                </tr>
                                <tr>
                                    <th>End date</th>
                                    <td><?php echo $planning->end_date; ?></td>
                                </tr>
                                <tr>
                                    <th>Stock</th>
                                    <td><?php echo $planning->stock; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span
                                            class="st <?php echo $planning_status_bg[$planning->status]; ?>"><?php echo $planning_status[$planning->status]; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Finished date</th>
                                    <td><?php echo $planning->finished_date; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="tab_pane" id="mob">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <a href="<?php //echo url_to('erp.mrp.addmrpstock', $planning_id); ?>" class="btn bg-primary">Add
                        Stock </a>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="mob_datatable" data-ajax-url="#">
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
                                <div class="pagination"><span class="dt-page-start">1</span> - <span
                                        class="dt-page-end">5</span> of <span class="dt-total-rows">100<span></div>
                                <ul class="flex paginationBtns">
                                    <li><a type="button" class="HoverA dt-prev-btn"><i class="fa fa-angle-left"></i></a>
                                    </li>
                                    <li><a type="button" class="HoverA dt-next-btn"><i
                                                class="fa fa-angle-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="tab_pane" id="bom">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <a href="<?php echo url_to('erp.mrp.add.bom', $planning_id); ?>" class="btn bg-primary"><i
                            class="fa-solid fa-plus fa-lg"></i> Create BOM
                    </a>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="bom_datatable"
                        data-ajax-url="<?= url_to('erp.mob.ajaxmrpschedulingresponse', $planning_id) . '?'; ?>">
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
                                <div class="pagination"><span class="dt-page-start">1</span> - <span
                                        class="dt-page-end">5</span> of <span class="dt-total-rows">100<span></div>
                                <ul class="flex paginationBtns">
                                    <li><a type="button" class="HoverA dt-prev-btn"><i class="fa fa-angle-left"></i></a>
                                    </li>
                                    <li><a type="button" class="HoverA dt-next-btn"><i
                                                class="fa fa-angle-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>






</div>
</div>






<!-- <div class="modal" id="scheduling_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Status</h2>
        <?php
        //echo form_open(url_to('erp.service.schedulingadd', $planning_id), array(
        //"id" => "scheduling_addedit_form",
        // "class" => "flex"
        //));
        ?>
        <input type="hidden" name="scheduling_id" id="scheduling_id" value="0" />
        <div class="flex filterBox_container">
            <div class="formWidth">
                <div class="selectBox poR" id="planning_filter_1">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select status">select status</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" name="status" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        //foreach ($planning_status as $key => $status) {
                        // ?>
                            <li role="option" data-value="<?php //echo $key; ?>"><?php //echo $status; ?></li>
                           // <?php
                           //}
                           ?>
                    </ul>
                </div>
            </div>
            <div class="form-width-1 ">
                <div class="form-group textRight ">
                    <button type="button" class="btn outline-danger modalClose">Close</button>
                    <button class="btn bg-primary" type="button" id="notify_addedit_btn">Save</button>
                </div>
            </div>
        </div>
        </form>
    </div>



</div> -->




<!--SCRAP MODALS ADD-->


<div class="modal" id="scraps_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Scrap & Process Loss</h2>
        <?php
        echo form_open(url_to('erp.mrp.scrap.add'), array(
            "id" => "scrap_addedit_form",
            "class" => "flex"
        ));

        ?>

        <input type="hidden" name="scrap_id" id="f_scrap_id" value="0" />
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Item Code</label>
                <input type="text" class="form_control field-check" id="f_item_code" name="item_code" />
                <p class="error-text"></p>
            </div>
        </div>

        <input type="hidden" name="scrap_id" id="f_scrap_id" value="0" />
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Item Name</label>
                <input type="text" class="form_control field-check" id="f_item_name" name="item_name" />
                <p class="error-text"></p>
            </div>
        </div>


        <input type="hidden" name="scrap_id" id="f_scrap_id" value="0" />
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Quantity</label>
                <input type="text" class="form_control field-check" id="f_qty" name="quantity" />
                <p class="error-text"></p>
            </div>
        </div>


        <input type="hidden" name="scrap_id" id="f_scrap_id" value="0" />
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Rate</label>
                <input type="text" class="form_control field-check" id="f_rate" name="rate" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="scrap_addedit_btn">Save</button>
            </div>
        </div>
        </form>


    </div>
</div>
<!-- MODAL ENDS -->
<!--SCRAP  MODAL ENDS -->

</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script type="text/javascript">
    let closer = new WindowCloser();
    closer.init();
    // let tbody = document.querySelector(".attachment-holder");
    // let fileuploader = new FileUploader(document.querySelector(".file-uploader-frame"));
    let alert = new ModalAlert();
    let ajaxselectbox = null;
    document.querySelectorAll(".selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        closer.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    function load_active_toggler_1(params) {
        contact_paintarea.querySelectorAll(".active-toggler-1").forEach((item) => {
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


    // /**
    //  * Shipping Address Datatable
    //  */
    let scheduling_datatable_elem = document.querySelector("#bom_datatable");

    let scheduling_rows_per_page = new SelectBox(scheduling_datatable_elem.querySelector(".tableFooter .selectBox"));
    scheduling_rows_per_page.init();

    closer.register_shutdown(scheduling_rows_per_page.shutdown, scheduling_rows_per_page.get_container());
    let scheduling_bulkaction = new SelectBox(scheduling_datatable_elem.querySelector(".tableHeader .bulkaction"));
    scheduling_bulkaction.init();

    closer.register_shutdown(scheduling_bulkaction.shutdown, scheduling_bulkaction.get_container());

    let scheduling_config = JSON.parse('<?php echo $mrpscheduling_datatable_config; ?>');

    let scheduling_datatable = new DataTable(scheduling_datatable_elem, scheduling_config);
    scheduling_datatable.init();

    scheduling_rows_per_page.add_listener(scheduling_datatable.rows_per_page, {});

    // let shipping_modal = new ModalBox(document.getElementById("scheduling_addedit_modal"));
    // shipping_modal.init();

    // document.getElementById("mob_modal_invoker1").onclick = (evt) => {
    //     document.getElementById("mrp_scheduling_id").value = "0";
    //     document.getElementById("status").value = "";
    //     shipping_modal.show_modal();
    // };

    // let shipping_paintarea = document.querySelector("#mob_datatable .table-paint-area");
    // shipping_paintarea.onclick = (evt) => {
    //     let target = evt.target;
    //     shipping_paintarea.querySelectorAll(".modalBtn").forEach((item) => {
    //         if (item.contains(target)) {
    //             let ajax_url = item.getAttribute("data-ajax-url");
    //             let xhr = null;
    //             if (window.XMLHttpRequest) {
    //                 xhr = new XMLHttpRequest();
    //             } else if (window.ActiveXObject) {
    //                 xhr = new ActiveXObject("Msxml2.XMLHTTP");
    //             }
    //             if (xhr !== null && xhr !== undefined) {
    //                 xhr.open("GET", ajax_url, true);
    //                 xhr.send(null);
    //                 xhr.onreadystatechange = (evt) => {
    //                     if (xhr.readyState == 4 && xhr.status == 200) {
    //                         let json = JSON.parse(xhr.responseText);
    //                         if (json['error'] == 0) {
    //                             let data = json['data'];
    //                             document.getElementById("status").value = data['status'];
    //                             shipping_modal.show_modal();
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // };


    // let shipping_form = document.getElementById("scheduling_addedit_form");
    // let shipping_validator = new FormValidate(shipping_form);

    // let shipping_lock = false;
    // document.getElementById("scheduling_addedit_btn").onclick = (evt) => {
    //     if (!shipping_lock) {
    //         shipping_lock = true;
    //         shipping_validator.validate(
    //             (params) => {
    //                 shipping_form.submit();
    //                 shipping_lock = false;
    //             },
    //             (params) => {
    //                 shipping_lock = false;
    //             }, {});
    //     }
    // }


    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });



    // scrap model edit and add




    let modal_box = new ModalBox(document.getElementById("scraps_addedit_modal"));
    modal_box.init();

    // document.getElementById("scrap_modal_invoker1").onclick = (evt) => {
    //     document.getElementById("f_scrap_id").value = "";
    //     document.getElementById("f_item_code").value = "";
    //     document.getElementById("f_item_name").value = "";
    //     document.getElementById("f_qty").value = "";
    //     document.getElementById("f_rate").value = "";
    //     modal_box.show_modal();
    // };

    // let paintarea = document.querySelector(".table-paint-area");
    // paintarea.onclick = (evt) => {
    //     let target = evt.target;
    //     paintarea.querySelectorAll(".modalBtn").forEach((item) => {
    //         if (item.contains(target)) {
    //             let ajax_url = item.getAttribute("data-ajax-url");

    //             let xhr = null;
    //             if (window.XMLHttpRequest) {
    //                 xhr = new XMLHttpRequest();
    //             } else if (window.ActiveXObject) {
    //                 xhr = new ActiveXObject("Msxml2.XMLHTTP");
    //             }
    //             if (xhr !== null && xhr !== undefined) {
    //                 xhr.open("GET", ajax_url, true);
    //                 xhr.send(null);
    //                 xhr.onreadystatechange = (evt) => {
    //                     if (xhr.readyState == 4 && xhr.status == 200) {
    //                         let json = JSON.parse(xhr.responseText);
    //                         if (json['error'] == 0) {
    //                             let data = json['data'];
    //                             document.getElementById("f_scrap_id").value = data['scrap_id'];
    //                             document.getElementById("f_item_code").value = data['item_code'];
    //                             document.getElementById("f_item_name").value = data['item_name'];
    //                             document.getElementById("f_qty").value = data['quantity'];
    //                             document.getElementById("f_rate").value = data['rate'];

    //                             modal_box.show_modal();
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // };

    // let form = document.getElementById("scrap_addedit_form");
    // let validator = new FormValidate(form);

    // let lock = false;
    // document.getElementById("scrap_addedit_btn").onclick = function (evt) {
    //     if (!lock) {
    //         lock = true;
    //         validator.validate(
    //             (params) => {
    //                 form.submit();
    //                 lock = false;
    //             },
    //             (params) => {
    //                 lock = false;
    //             },
    //             {});
    //     }
    // }


    // let datatable_elem = document.querySelector(".datatable");
    // let rows_per_page = new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
    // rows_per_page.init();
    // closer.register_shutdown(rows_per_page.shutdown, rows_per_page.get_container());

    // let bulkaction = new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
    // bulkaction.init();
    // closer.register_shutdown(bulkaction.shutdown, bulkaction.get_container());



    // let config = JSON.parse('<?php //echo $scrap_datatable_config; ?>');
    // let datatable = new DataTable(datatable_elem, config);
    // datatable.init();
    // rows_per_page.add_listener(datatable.rows_per_page, {});








    <?php
    if (session()->getFlashdata("op_success")) { ?>
        // let alerts = new ModalAlert();
        // // let alert = new ModalAlert();
        alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
        <?php
    } else if (session()->getFlashdata("op_error")) { ?>
            // let alerts = new ModalAlert();
            alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
        <?php
    }
    ?>
</script>


</body>

</html>