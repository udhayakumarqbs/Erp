<div class="alldiv flex widget_title">
    <h3>Applied Credits</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.sale.invoice.view', $invoice_id); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <!-- <li><a type="button" class="tab_nav_item active" data-src="invoice_info">Applied credits</a></li> -->
        <!-- <li><a type="button" class="tab_nav_item" data-src="invoice_payment">Payments</a></li> -->
    </ul>
      
    <div class="tab_content">
        <div class="tab_pane active" id="invoice_info">
        <div class="d-flex justify-content-end mr-3">
            <a href="<?php echo url_to('erp.invoice.add.creditnote', $invoice_id); ?>" class="btn bg-primary" type="button"><i class="fa fa-plus"></i>Apply Credits</a>
        </div>
            <div class="flex">
                <div class="form-width-1" id="printContainer">
                    <div class="table_responsive">
                        <table class="table">
                            <thead>
                                <th>SNo</th>
                                <th>Credit Notes</th>
                                <th>Applied Amount</th>
                                <th>Date</th>
                            </thead>
                            <tbody>
                                <?php
                                $inc = 1;
                                foreach ($invoiceCreditItems as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $inc; ?></td>
                                        <td><?php echo $row['credit_code']; ?></td>
                                        <td><?php echo $row['amount']; ?></td>
                                        <td><?php echo $row['date']; ?></td>
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
        <div class="tab_pane" id="invoice_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame" data-ajax-url="<?= url_to('erp.sale.invoice.upload.attachments') . '?id=' . $invoice_id . '&'; ?>">
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
                            <tbody class="attachment-holder" data-ajaxdel-url="<?= url_to('erp.sale.invoice.delete.attachments') . '?'; ?>">
                                <?php
                                foreach ($attachments as $attach) {
                                ?>
                                    <tr>
                                        <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('sale_invoice') . $attach['filename']; ?>"><?php echo $attach['filename']; ?></a></td>
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
        <div class="tab_pane" id="invoice_notify">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <button class="btn bg-primary modalBtn" id="notify_modal_invoker1" type="button"><i class="fa fa-plus"></i>Add Notify</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="notify_datatable" data-ajax-url="<?= url_to('sale.invoice.view.createnotify') . '?invoiceid=' . $invoice_id . '&'; ?>">
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
                                        <li><a data-default-href="<?= url_to('sale.invoice.view.notify.exporter') . '?export=excel&invoiceid=' . $invoice_id . '&'; ?>" href="<?= url_to('sale.invoice.view.notify.exporter') . '?export=excel&invoiceid=' . $invoice_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?= url_to('sale.invoice.view.notify.exporter') . '?export=pdf&invoiceid=' . $invoice_id . '&'; ?>" href="<?= url_to('sale.invoice.view.notify.exporter') . '?export=pdf&invoiceid=' . $invoice_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?= url_to('sale.invoice.view.notify.exporter') . '?export=csv&invoiceid=' . $invoice_id . '&'; ?>" href="<?= url_to('sale.invoice.view.notify.exporter') . '?export=csv&invoiceid=' . $invoice_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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
        <div class="tab_pane" id="invoice_payment">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <a href="#" id="payment_modal_invoker1" class="btn bg-primary" type="button"><i class="fa fa-plus"></i>Add Payment</a>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="invoice_payment_datatable" data-ajax-url="<?= url_to('erp.sale.ajax_salepayment_response') . '?invoiceid=' . $invoice_id . '&'; ?>">
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
                                        <li><a data-default-href="<?= url_to('erp.sale.invoice_payment_export') . '?export=excel&invoiceid=' . $invoice_id . '&'; ?>" href="<?= url_to('erp.sale.invoice_payment_export') . '?export=excel&invoiceid=' . $invoice_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?= url_to('erp.sale.invoice_payment_export') . '?export=pdf&invoiceid=' . $invoice_id . '&'; ?>" href="<?= url_to('erp.sale.invoice_payment_export') . '?export=pdf&invoiceid=' . $invoice_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?= url_to('erp.sale.invoice_payment_export') . '?export=csv&invoiceid=' . $invoice_id . '&'; ?>" href="<?= url_to('erp.sale.invoice_payment_export') . '?export=csv&invoiceid=' . $invoice_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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



<!--MODALS-->
<div class="modal" id="notify_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Notify</h2>
        <?php
        echo form_open(url_to('sale.invoice.notify', $invoice_id), array(
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
                <div class="ajaxselectBox poR" data-ajax-url="<?= url_to('erp.crm.ajaxFetchUsers'); ?>">
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
        <?= form_close(); ?>
    </div>
</div>


<div class="modal" id="payment_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Payment</h2>
        <?php
        echo form_open(url_to('erp.sale.invoicepayment', $invoice_id), array(
            "id" => "payment_addedit_form",
            "class" => "flex modal-scroll-form"
        ));
        ?>
        <input type="hidden" name="sale_pay_id" id="f_sale_pay_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-money ">
                <label class="form-label">Amount</label>
                <input type="text" class="form_control field-check" id="f_amount" name="amount" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Paid On</label>
                <input type="date" class="form_control field-check" id="f_paid_on" name="paid_on" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Payment Mode</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select mode">select mode</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" id="f_payment_id" name="payment_id" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($paymentmodes as $row) {
                        ?>
                            <li role="option" data-value="<?php echo $row['payment_id']; ?>"><?php echo $row['name']; ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label">Transaction ID</label>
                <input type="text" class="form_control field-check" id="f_transaction_id" name="transaction_id" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea class="form_control field-check" id="f_notes" name="notes"></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="payment_addedit_btn">Save</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>

<!-- Email Send modal -->


<div class="modal" id="email_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Send via Mail</h2>
        <?php
        echo form_open(url_to('erp.sale.invoice.view.mailsend', $invoice_id, 'invoice'), array(
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
                <input type="text" class="form_control field-check" id="to_email" name="to_mail" value="<?php echo ($invoice->email); ?>" disabled/>
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label">Subject</label>
                <input type="text" class="form_control field-check" id="mail_subject" name="subject_mail"></input>
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-1">
            <div class="form-group">
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

    let paintarea = document.querySelector("#notify_datatable .table-paint-area");
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

    let datatable_elem = document.querySelector("#notify_datatable.datatable");
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


    let payment_selectboxes = [];
    document.querySelectorAll("#payment_addedit_modal .selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        payment_selectboxes.push(selectbox);
        closer.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    let payment_modal = new ModalBox(document.getElementById("payment_addedit_modal"));
    payment_modal.init();

    document.getElementById("payment_modal_invoker1").onclick = (evt) => {
        document.getElementById("f_sale_pay_id").value = "0";
        document.getElementById("f_payment_id").value = "";
        document.getElementById("f_notes").value = "";
        document.getElementById("f_amount").value = "";
        document.getElementById("f_paid_on").value = "";
        document.getElementById("f_transaction_id").value = "";
        for (let i = 0; i < payment_selectboxes.length; i++) {
            payment_selectboxes[i].construct();
        }
        payment_modal.show_modal();
    };

    let payment_paintarea = document.querySelector("#invoice_payment_datatable .table-paint-area");
    payment_paintarea.onclick = (evt) => {
        let target = evt.target;
        payment_paintarea.querySelectorAll(".modalBtn").forEach((item) => {
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
                                document.getElementById("f_sale_pay_id").value = data['sale_pay_id'];
                                document.getElementById("f_payment_id").value = data['payment_id'];
                                document.getElementById("f_notes").value = data['notes'];
                                document.getElementById("f_amount").value = data['amount'];
                                document.getElementById("f_paid_on").value = data['paid_on'];
                                document.getElementById("f_transaction_id").value = data['transaction_id'];
                                for (let i = 0; i < payment_selectboxes.length; i++) {
                                    payment_selectboxes[i].construct();
                                }
                                payment_modal.show_modal();
                            }
                        }
                    }
                }
            }
        });
    };

    let payment_form = document.getElementById("payment_addedit_form");
    let payment_validator = new FormValidate(payment_form);

    let payment_lock = false;
    document.getElementById("payment_addedit_btn").onclick = (evt) => {
        if (!payment_lock) {
            payment_lock = true;
            payment_validator.validate(
                (params) => {
                    payment_form.submit();
                    payment_lock = false;
                },
                (params) => {
                    payment_lock = false;
                }, {});
        }
    }

    let payment_datatable_elem = document.querySelector("#invoice_payment_datatable.datatable");
    let payment_rows_per_page = new SelectBox(payment_datatable_elem.querySelector(".tableFooter .selectBox"));
    payment_rows_per_page.init();
    closer.register_shutdown(payment_rows_per_page.shutdown, payment_rows_per_page.get_container())
    let payment_bulkaction = new SelectBox(payment_datatable_elem.querySelector(".tableHeader .bulkaction"));
    payment_bulkaction.init();
    closer.register_shutdown(payment_bulkaction.shutdown, payment_bulkaction.get_container());
    let payment_config = JSON.parse('<?php echo $payment_datatable_config; ?>');
    let payment_datatable = new DataTable(payment_datatable_elem, payment_config);
    payment_datatable.init();
    payment_rows_per_page.add_listener(payment_datatable.rows_per_page, {});


    //Email modal

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
                    email_form.submit();
                    email_lock = false;
                },
                (params) => {
                    email_lock = false;
                }, {});
        }
    }

    document.getElementById("mail_modal_invoker1").onclick = (evt) => {
        document.getElementById("email_send").value = "";
        document.getElementById("mail_subject").value = "";
        document.getElementById("mail_message").value = "";
        for (let i = 0; i < email_selectboxes.length; i++) {
            email_selectboxes[i].construct();
        }
        email_modal.show_modal();
    };

    //Print Invoice
    
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



    <?php
    if (session()->getFlashdata("op_success")) { ?>
        alert.invoke_alert("<?= session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        alert.invoke_alert("<?= session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>