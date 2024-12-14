<div class="alldiv flex widget_title">
    <h3>View Quotation</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.sale.quotations'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="quote_info">Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="quote_attachment">Attachments</a></li>
        <li><a type="button" class="tab_nav_item" data-src="quote_notify">Notify</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="quote_info">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <?php
                    if ($quotation->status <= 1) {
                    ?>
                        <a href="<?php echo url_to('erp.sale.quotations.accept', $quote_id); ?>" class="btn outline-success">Accepted</a>
                        <a href="<?php echo url_to('erp.sale.quotations.decline', $quote_id); ?>" class="btn outline-danger">Declined</a>
                    <?php
                    }
                    ?>
                    <?php
                    if ($quotation->status == 2) {
                    ?>
                        <button class="btn bg-primary modalBtn" id="convertorder_modal_invoker1">Convert to Order</button>
                    <?php
                    }
                    ?>

                    <ul class="dropdown-style btn bg-info">
                        <li><a href="#">PDF &dtrif;</a>
                            <ul class="dropdown">
                                <li><a href="<?php echo url_to('erp.sale.quotation.pdf.view', $quote_id, 'view'); ?>">View PDF</a> </li>
                                <li><a href="<?php echo url_to('erp.sale.quotation.pdf.view', $quote_id, 'view'); ?>" target="_blank">View PDF in New Tab</a></li>
                                <li><a href="<?php echo url_to('erp.sale.quotation.pdf.view', $quote_id, 'download'); ?>">Download</a></li>
                                <li><a href="<?php echo url_to('erp.sale.quotation.pdf.view', $quote_id, 'view'); ?>" target="_blank">Print PDF</a></li>
                                <li><a href="#" id="printbutton">Print View</a></li>
                            </ul>
                        </li>
                    </ul>

                  
                    <a class="btn bg-success" id="mail_modal_invoker1">Send Email</a>
                    <a href="<?= url_to('erp.sale.quotation.delete', $quote_id); ?>" class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1" id="printContainer">
                    <h2>Quotation Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Code</th>
                                    <td><?php echo $quotation->code; ?></td>
                                </tr>
                                <tr>
                                    <th>Customer</th>
                                    <td><?php echo $quotation->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Billing Address</th>
                                    <td><?php echo $quotation->billing_addr; ?></td>
                                </tr>
                                <tr>
                                    <th>Shipping Address</th>
                                    <td><?php echo $quotation->shipping_addr; ?></td>
                                </tr>
                                <tr>
                                    <th>Quotation date</th>
                                    <td><?php echo $quotation->quote_date; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="st <?php echo $quote_status_bg[$quotation->status]; ?>"><?php echo $quote_status[$quotation->status]; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Transport Requested</th>
                                    <td><?php echo $quotation->transport_req; ?></td>
                                </tr>
                                <tr>
                                    <th>Transport Charge</th>
                                    <td><?php echo $quotation->trans_charge; ?></td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td><?php echo $quotation->discount; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <td><?php echo number_format($quotation->total_amount, 2, '.', ','); ?></td>
                                </tr>
                                <tr>
                                    <th>Payment Terms</th>
                                    <td><?php echo $quotation->payment_terms; ?></td>
                                </tr>
                                <tr>
                                    <th>Terms and Condition</th>
                                    <td><?php echo $quotation->terms_condition; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div><br>
                    <h2>Quotation Items</h2>
                    <div class="table_responsive">
                        <table class="table">
                            <thead>
                                <th>SNo</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Amount</th>
                            </thead>
                            <tbody>
                                <?php
                                $inc = 1;
                                foreach ($quotation_items as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $inc; ?></td>
                                        <td><?php echo $row['product']; ?></td>
                                        <td><?php echo $row['quantity']; ?></td>
                                        <td><?php echo $row['unit_price']; ?></td>
                                        <td><?php echo $row['amount']; ?></td>
                                    </tr>

                                <?php
                                    $inc++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="quote_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame" data-ajax-url="<?php echo url_to('erp.sale.quotations.upload_quoteattachment') . '?id=' . $quote_id . '&'; ?>">
                        <div class="file-uploader-box">
                            <span class="file-uploader-text">drop or click to upload files</span>
                        </div>
                        <div class="file-uploader-progessbar">
                            <span class="progressbar bg-success"></span>
                        </div>
                        <input type="file" class="file-uploader-input" name="attachment" />
                        <p class="file-uploader-error"></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody class="attachment-holder" data-ajaxdel-url="<?php echo url_to('erp.sale.quotations.quote_delete_attachment') . '?'; ?>">
                                <?php
                                foreach ($attachments as $attach) {
                                ?>
                                    <tr>
                                        <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('quotation') . $attach['filename']; ?>"><?php echo $attach['filename']; ?></a></td>
                                        <td><button class="btn bg-danger del-attachment-btn" type="button" data-attach-id="<?php echo $attach['attach_id']; ?>"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="quote_notify">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <button class="btn bg-primary modalBtn" id="notify_modal_invoker1" type="button"><i class="fa fa-plus"></i>Add Notify</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable" data-ajax-url="<?php echo url_to('erp.sale.quotations.fetch.notification') . '?quoteid=' . $quote_id . '&'; ?>">

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
                                        <li><a data-default-href="<?php echo url_to('erp.sale.quotation_notify_export') . '?export=excel&quoteid=' . $quote_id . '&'; ?>" href="<?php echo url_to('erp.sale.quotation_notify_export') . '?export=excel&quoteid=' . $quote_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?php echo url_to('erp.sale.quotation_notify_export') . '?export=pdf&quoteid=' . $quote_id . '&'; ?>" href="<?php echo url_to('erp.sale.quotation_notify_export') . '?export=pdf&quoteid=' . $quote_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?php echo url_to('erp.sale.quotation_notify_export') . '?export=csv&quoteid=' . $quote_id . '&'; ?>" href="<?php echo url_to('erp.sale.quotation_notify_export') . '?export=csv&quoteid=' . $quote_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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
    </div>
</div>
</div>
</div>



<!--MODALS-->
<div class="modal" id="notify_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Notify</h2>

        <form action="<?= url_to('erp.sale.quotations.insert_updated_notification', $quote_id) ?>" method="post" class="flex modal-scroll-form" id="notify_addedit_form">
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
                    <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.crm.ajaxFetchUsers') ?>">
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
</div>


<div class="modal" id="convert_order_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Convert To Order</h2>

        <form action="<?= url_to('erp.sale.convertorderquote', $quote_id) ?>" method="post" class="flex modal-scroll-form" id="convert_modal_form">
            <div class="form-width-1">
                <div class="form-group field-ajax" data-ajax-url="<?php echo url_to('erp.sale.orders.ajax_order_code_unique') . '?'; ?>">
                    <label class="form-label">Code</label>
                    <input type="text" class="form_control field-check" name="code" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group field-required">
                    <label class="form-label">Order Expiry</label>
                    <input type="date" class="form_control field-check" name="order_expiry" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1 ">
                <div class="form-group textRight ">
                    <button type="button" class="btn outline-danger modalClose">Close</button>
                    <button class="btn bg-primary" type="button" id="convert_order_btn">Convert</button>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- Email Send modal -->


<div class="modal" id="email_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Send via Mail</h2>
        <?php
        echo form_open(url_to('erp.sale.invoice.view.mailsend', $quote_id, 'quotations'), array(
            "id" => "email_addedit_form",
            "class" => "flex modal-scroll-form"
        ));
        ?>
        <input type="hidden" name="" id="email_send" />
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">From</label>
                <input type="email" class="form_control field-check" id="from_email" name="from_mail" value="<?php echo $user_email; ?>" disabled />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">To</label>
                <select class="form_control field-check" name="to_mail[]" id="to_mail">
                    <?php foreach ($customerContact as $email) : ?>
                        <option value="<?= $email['email'] ?>"><?= $email['email'] ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Cc</label>
                <input type="text" class="form_control field-check" id="cc_email" name="cc_mail" value="" />
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Subject</label>
                <input type="text" class="form_control field-check" id="mail_subject" name="subject_mail"></input>
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Message</label>
                <textarea class="form_control field-check" id="mail_message" name="message_mail"></textarea>
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="email_addedit_btn">Save</button>
            </div>
        </div>
        <?= form_close(); ?>
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
    let tbody = document.querySelector(".attachment-holder");
    let fileuploader = new FileUploader(document.querySelector(".file-uploader-frame"));
    let alert = new ModalAlert();

    fileuploader.ajaxFullUpload({
        files_allowed: ["text/plain", "image/png", "application/pdf", "image/jpeg", "image/gif", "image/jpg", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "image/webp", "image/svg+xml"],
        listener: function(json) {
            let tr = document.createElement("tr");
            let td1 = document.createElement("td");
            let td2 = document.createElement("td");
            td1.innerHTML = `<a target="_BLANK" download class="text-primary" href="` + json['filelink'] + `">` + json['filename'] + `</a>`;
            td2.innerHTML = `<button class="btn bg-danger del-attachment-btn" type="button" data-attach-id="` + json['insert_id'] + `" ><i class="fa fa-trash"></i></button>`;
            tr.append(td1);
            tr.append(td2);
            tbody.append(tr);
        }
    });

    tbody.onclick = (evt) => {
        let target = evt.target;
        let ajax_url = tbody.getAttribute("data-ajaxdel-url");
        tbody.querySelectorAll(".del-attachment-btn").forEach((item) => {
            if (item.contains(target)) {
                let xhr = null;
                if (window.ActiveXObject) {
                    xhr = new ActiveXObject("Msxml2.XMLHTTP");
                } else if (window.XMLHttpRequest) {
                    xhr = new XMLHttpRequest();
                }
                if (xhr !== null || xhr !== undefined) {
                    xhr.open("GET", ajax_url + "id=" + item.getAttribute("data-attach-id"), true);
                    xhr.send(null);
                    xhr.onreadystatechange = (evt) => {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            let json = JSON.parse(xhr.responseText);
                            if (json['error'] == 0) {
                                alert.invoke_alert(json['reason'], "success");
                                item.parentElement.parentElement.remove();
                            } else {
                                alert.invoke_alert(json['reason'], "error");
                            }
                        }
                    }
                }
            }
        })
    };

    let modal_box = new ModalBox(document.getElementById("notify_addedit_modal"));
    modal_box.init();

    let notify_ajax_select = [];
    document.querySelectorAll("#notify_addedit_modal .ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        notify_ajax_select.push(ajaxselectbox);
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    document.getElementById("notify_modal_invoker1").onclick = (evt) => {
        document.getElementById("f_notify_id").value = "0";
        document.getElementById("f_notify_title").value = "";
        document.getElementById("f_notify_desc").value = "";
        document.getElementById("f_notify_at").value = "";
        document.getElementById("f_notify_email").checked = false;
        document.getElementById("f_notify_to").value = "";
        for (let i = 0; i < notify_ajax_select.length; i++) {
            notify_ajax_select[i].construct();
        }
        modal_box.show_modal();
    };

    let paintarea = document.querySelector(".table-paint-area");
    paintarea.onclick = (evt) => {
        let target = evt.target;
        paintarea.querySelectorAll(".modalBtn").forEach((item) => {
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
                                document.getElementById("f_notify_id").value = data['notify_id'];
                                document.getElementById("f_notify_title").value = data['title'];
                                document.getElementById("f_notify_desc").value = data['notify_text'];
                                document.getElementById("f_notify_at").value = data['notify_at'];
                                if (data['notify_email'] == 1) {
                                    document.getElementById("f_notify_email").checked = true;
                                }
                                document.getElementById("f_notify_to").previousElementSibling.previousElementSibling.previousElementSibling.textContent = data['name'];
                                document.getElementById("f_notify_to").value = data['user_id'];
                                for (let i = 0; i < notify_ajax_select.length; i++) {
                                    notify_ajax_select[i].construct();
                                }
                                modal_box.show_modal();
                            }
                        }
                    }
                }
            }
        });
    };

    let form = document.getElementById("notify_addedit_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("notify_addedit_btn").onclick = (evt) => {
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

    let convert_modal = new ModalBox(document.getElementById("convert_order_modal"));
    convert_modal.init();
    <?php if ($quotation->status == 2) {
    ?>
        document.getElementById("convertorder_modal_invoker1").onclick = (evt) => {
            convert_modal.show_modal();
        };
    <?php
    }
    ?>

    let convert_form = document.getElementById("convert_modal_form");
    let convert_validator = new FormValidate(convert_form);

    let convert_lock = false;
    document.getElementById("convert_order_btn").onclick = (evt) => {
        if (!convert_lock) {
            convert_lock = true;
            convert_validator.validate(
                (params) => {
                    convert_form.submit();
                    convert_lock = false;
                },
                (params) => {
                    convert_lock = false;
                }, {});
        }
    }



    //Email modal

    function displayErrorMessage(inputElement, message) {
        removeErrorMessage(inputElement);

        let errorMessage = document.createElement("p");
        errorMessage.className = "error-message";
        errorMessage.textContent = "* " + message;
        errorMessage.style.color = "red";
        errorMessage.style.marginTop = "5px";
        inputElement.parentNode.appendChild(errorMessage);
    }


    function removeErrorMessage(inputElement) {
        let errorMessage = inputElement.parentNode.querySelector(".error-message");
        if (errorMessage) {
            errorMessage.parentNode.removeChild(errorMessage);
        }
    }

    let email_selectboxes = [];
    document.querySelectorAll("#email_addedit_modal .selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        email_selectboxes.push(selectbox);
        closer.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    let email_modal = new ModalBox(document.getElementById("email_addedit_modal"));
    email_modal.init();

    let email_form = document.getElementById("email_addedit_form");
    let email_validator = new FormValidate(email_form);

    let email_lock = false;
    document.getElementById("email_addedit_btn").onclick = (evt) => {
        if (!email_lock) {
            email_lock = true;

            email_validator.validate(
                (params) => {
                    let ccEmailInput = document.getElementById("cc_email");
                    let ccEmails = ccEmailInput.value.trim().split(',').map(email => email.trim());
                    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    let invalidEmails = ccEmails.filter(email => !emailPattern.test(email));

                    if (invalidEmails.length > 0) {
                        displayErrorMessage(ccEmailInput, "Please enter valid email addresses separated by commas.");
                        email_lock = false;
                    } else {
                        removeErrorMessage(ccEmailInput);
                        email_form.submit();
                    }
                },
                (params) => {
                    email_lock = false;
                }, {}
            );
        }
    };


    document.getElementById("mail_modal_invoker1").onclick = (evt) => {
        document.getElementById("email_send").value = "";
        document.getElementById("mail_subject").value = "";
        document.getElementById("mail_message").value = "";
        for (let i = 0; i < email_selectboxes.length; i++) {
            email_selectboxes[i].construct();
        }
        email_modal.show_modal();
    };

    //Print Quotation

    window.onload = function() {
        document.getElementById('printbutton').addEventListener('click', function() {
            // Use html2canvas to capture the specific container
            html2canvas(document.getElementById('printContainer')).then(function(canvas) {
                var screenshotImage = new Image();
                screenshotImage.src = canvas.toDataURL();

                // Create a new window and wait for it to load
                var screenshotWindow = window.open();
                screenshotWindow.document.write('<html><head><title>Order Print</title></head><body></body></html>');
                screenshotWindow.document.body.appendChild(screenshotImage);

                // Wait for the image to load in the new window
                screenshotImage.onload = function() {
                    // Trigger print manually (users will still need to confirm)
                    screenshotWindow.print();
                    screenshotWindow.close();
                };
            });
        });
    };

    let datatable_elem = document.querySelector(".datatable");

    let rows_per_page = new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
    rows_per_page.init();
    closer.register_shutdown(rows_per_page.shutdown, rows_per_page.get_container());

    let bulkaction = new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
    bulkaction.init();
    closer.register_shutdown(bulkaction.shutdown, bulkaction.get_container());

    let config = JSON.parse('<?php echo $notify_datatable_config; ?>');
    let datatable = new DataTable(datatable_elem, config);
    datatable.init();

    rows_per_page.add_listener(datatable.rows_per_page, {});

    <?php
    if (session()->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        let alertS = new ModalAlert();
        alertS.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>