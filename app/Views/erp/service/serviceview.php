<div class="alldiv flex widget_title">
    <h3>View Service</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.service.service'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="service_info">Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="scheduling">Scheduling</a></li>
        <!-- <li><a type="button" class="tab_nav_item" data-src="customer_contacts">Contacts</a></li>
        <li><a type="button" class="tab_nav_item" data-src="customer_attachment">Attachments</a></li>-->
        <!-- <li><a type="button" class="tab_nav_item" data-src="customer_notify">Notify</a></li> -->
    </ul>

    <div class="tab_content">
        <div class="tab_pane active" id="service_info">
            <div class="flex">
                <div class="form-width-1">
                    <h2>Service Info</h2>
                    <div class="table_responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Code</th>
                                    <td><?php echo $service->code; ?></td>
                                </tr>
                                <tr>
                                    <th>Service Name</th>
                                    <td><?php echo $service->service_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Priority</th>
                                    <td><span class="st <?php echo $service_priority_bg[$service->priority]; ?>"><?php echo $service_priority[$service->priority]; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="st <?php echo $service_status_bg[$service->status]; ?>"><?php echo $service_status[$service->status]; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Assigned to</th>
                                    <td><?php echo $service->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Lobers</th>
                                    <td><?php echo $service->employee; ?></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $service->service_desc; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab_pane" id="scheduling">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <button class="btn bg-primary modalBtn" id="scheduling_modal_invoker1" type="button"><i class="fa fa-plus"></i>Add Scheduling</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="scheduling_datatable" data-ajax-url="<?php echo url_to('erp.service.schedulingresponse') . '?service_id=' . $service_id . '&'; ?>">
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
        <!-- <div class="tab_pane" id="customer_notify">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <button class="btn bg-primary modalBtn" id="notify_modal_invoker1" type="button"><i class="fa fa-plus"></i>Add Notify</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="notify_datatable" data-ajax-url="<?php //echo url_to('erp.service.ajaxservicenotifyresponse') . '?service_id=' . $service_id . '&'; ?>">
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
                                //<export button
                                <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i> Export</a>
                                <div class="export_container poF">
                                    <ul class="exportUl">
                                        <li><a data-default-href="<?php //echo base_url() . 'erp/crm/customer-notify-export?export=excel&custid=' . $customer_id . '&'; ?>" href="<?php //echo base_url() . 'erp/crm/customer-notify-export?export=excel&custid=' . $customer_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?php //echo base_url() . 'erp/crm/customer-notify-export?export=pdf&custid=' . $customer_id . '&'; ?>" href="<?php //echo base_url() . 'erp/crm/customer-notify-export?export=pdf&custid=' . $customer_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?php //echo base_url() . 'erp/crm/customer-notify-export?export=csv&custid=' . $customer_id . '&'; ?>" href="<?php //echo base_url() . 'erp/crm/customer-notify-export?export=csv&custid=' . $customer_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
                                    </ul>
                                    <a type="button" class="closeBtn3 HoverA"><i class="fa fa-close"></i></a>
                                </div>
                                //export button
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
        </div> -->
    </div>
</div>



<!-- NOTIFY MODAL -->
<!-- <div class="modal" id="notify_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Notify</h2>
        <?php
        echo form_open(url_to('erp.service.servicenotify', $service_id), array(
            "id" => "notify_addedit_form",
            "class" => "flex modal-scroll-form"
        ));
        ?>
        <input type="hidden" name="notify_id" id="f_notify_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Title</label>
                <input type="text" class="form_control field-check" id="f_notify_title" name="notify_title" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Description</label>
                <textarea class="form_control field-check" id="f_notify_desc" name="notify_desc"></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Notify To</label>
                <div class="ajaxselectBox poR" data-ajax-url="<?php //echo url_to('erp.crm.ajaxFetchUsers'); ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="Notify To">Notify To</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="ajaxselectBox_Value field-check" id="f_notify_to" name="notify_to" value="">
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
                <label class="form-label">Notify at</label>
                <input type="datetime-local" class="form_control field-check" id="f_notify_at" name="notify_at" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label"></label>
                <label class="form-check-label"><input id="f_notify_email" type="checkbox" name="notify_email" value="1" /> Notify via Email too </label>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="notify_addedit_btn">Save</button>
            </div>
        </div>
        </form>
    </div>
</div> -->

<div class="modal" id="scheduling_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Scheduling</h2>
        <?php
        echo form_open(url_to('erp.service.schedulingadd', $service_id), array(
            "id" => "scheduling_addedit_form",
            "class" => "flex"
        ));
        ?>
        <input type="hidden" name="scheduling_id" id="scheduling_id" value="0" />
        <div class="form-width-1 d-none">
            <div class="form-group field-required">
                <label class="form-label">Service Code</label>
                <input type="text" class="form_control field-check" id="service_id" name="service_id" value="<?php echo $service_code->service_id; ?>" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Start date</label>
                <input type="date" class="form_control field-check" id="start_date" name="start_date" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Due date</label>
                <input type="date" class="form_control field-check" id="due_date" name="due_date" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Location</label>
                <input type="text" class="form_control field-check" id="location" name="location" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="scheduling_addedit_btn">Save</button>
            </div>
        </div>
        </form>
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


    // let notify_modal = new ModalBox(document.getElementById("notify_addedit_modal"));
    // notify_modal.init();

    // let notify_ajax_select = [];
    // document.querySelectorAll("#notify_addedit_modal .ajaxselectBox").forEach((item) => {
    //     let ajaxselectbox = new AjaxSelectBox(item);
    //     ajaxselectbox.init();
    //     notify_ajax_select.push(ajaxselectbox);
    //     closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    // });

    // document.getElementById("notify_modal_invoker1").onclick = (evt) => {
    //     document.getElementById("f_notify_id").value = "0";
    //     document.getElementById("f_notify_title").value = "";
    //     document.getElementById("f_notify_desc").value = "";
    //     document.getElementById("f_notify_at").value = "";
    //     document.getElementById("f_notify_email").checked = false;
    //     document.getElementById("f_notify_to").value = "";
    //     for (let i = 0; i < notify_ajax_select.length; i++) {
    //         notify_ajax_select[i].construct();
    //     }
    //     notify_modal.show_modal();
    // };

    // let notify_paintarea = document.querySelector("#notify_datatable .table-paint-area");
    // notify_paintarea.onclick = (evt) => {
    //     let target = evt.target;
    //     notify_paintarea.querySelectorAll(".modalBtn").forEach((item) => {
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
    //                             document.getElementById("f_notify_id").value = data['notify_id'];
    //                             document.getElementById("f_notify_title").value = data['title'];
    //                             document.getElementById("f_notify_desc").value = data['notify_text'];
    //                             document.getElementById("f_notify_at").value = data['notify_at'];
    //                             if (data['notify_email'] == 1) {
    //                                 document.getElementById("f_notify_email").checked = true;
    //                             }
    //                             document.getElementById("f_notify_to").previousElementSibling.previousElementSibling.previousElementSibling.textContent = data['name'];
    //                             document.getElementById("f_notify_to").value = data['user_id'];
    //                             for (let i = 0; i < notify_ajax_select.length; i++) {
    //                                 notify_ajax_select[i].construct();
    //                             }
    //                             notify_modal.show_modal();
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // };


    // let notify_form = document.getElementById("notify_addedit_form");
    // let notify_validator = new FormValidate(notify_form);

    // let notify_lock = false;
    // document.getElementById("notify_addedit_btn").onclick = (evt) => {
    //     if (!notify_lock) {
    //         notify_lock = true;
    //         notify_validator.validate(
    //             (params) => {
    //                 notify_form.submit();
    //                 notify_lock = false;
    //             },
    //             (params) => {
    //                 notify_lock = false;
    //             }, {});
    //     }
    // }



    let shipping_modal = new ModalBox(document.getElementById("scheduling_addedit_modal"));
    shipping_modal.init();

    document.getElementById("scheduling_modal_invoker1").onclick = (evt) => {
        document.getElementById("scheduling_id").value = "0";
        document.getElementById("start_date").value = "";
        document.getElementById("due_date").value = "";
        document.getElementById("location").value = "";
        shipping_modal.show_modal();
    };

    let shipping_paintarea = document.querySelector("#scheduling_datatable .table-paint-area");
    shipping_paintarea.onclick = (evt) => {
        let target = evt.target;
        shipping_paintarea.querySelectorAll(".modalBtn").forEach((item) => {
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
                                document.getElementById("scheduling_id").value = data['scheduling_id'];
                                document.getElementById("start_date").value = data['start_date'];
                                document.getElementById("due_date").value = data['due_date'];
                                document.getElementById("location").value = data['location'];
                                shipping_modal.show_modal();
                            }
                        }
                    }
                }
            }
        });
    };

    let shipping_form = document.getElementById("scheduling_addedit_form");
    let shipping_validator = new FormValidate(shipping_form);

    let shipping_lock = false;
    document.getElementById("scheduling_addedit_btn").onclick = (evt) => {
        if (!shipping_lock) {
            shipping_lock = true;
            shipping_validator.validate(
                (params) => {
                    shipping_form.submit();
                    shipping_lock = false;
                },
                (params) => {
                    shipping_lock = false;
                }, {});
        }
    }


    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });


    /**
     * Shipping Address Datatable
     */
    let scheduling_datatable_elem = document.querySelector("#scheduling_datatable");
    let scheduling_rows_per_page = new SelectBox(scheduling_datatable_elem.querySelector(".tableFooter .selectBox"));
    scheduling_rows_per_page.init();
    closer.register_shutdown(scheduling_rows_per_page.shutdown, scheduling_rows_per_page.get_container());
    let scheduling_bulkaction = new SelectBox(scheduling_datatable_elem.querySelector(".tableHeader .bulkaction"));
    scheduling_bulkaction.init();
    closer.register_shutdown(scheduling_bulkaction.shutdown, scheduling_bulkaction.get_container());
    let scheduling_config = JSON.parse('<?php echo $scheduling_datatable_config; ?>');
    let scheduling_datatable = new DataTable(scheduling_datatable_elem, scheduling_config);
    scheduling_datatable.init();
    scheduling_rows_per_page.add_listener(scheduling_datatable.rows_per_page, {});



    <?php
    if (session()->getFlashdata("op_success")) { ?>
        alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>