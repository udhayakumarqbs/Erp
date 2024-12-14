<div class="alldiv flex widget_title">
    <h3>View Invoice</h3>
    <div class="title_right">
        <?php if ($invoice->status == 1 || $invoice->status == 2): ?>
            <a href="<?= url_to('erp.sale.invoice.edit', $invoice_id); ?>" class="btn bg-secondary" style="display:none ;"
                ;><i class="fa fa-pencil"></i> Edit </a>
        <?php else: ?>
            <a href="<?= url_to('erp.sale.invoice.edit', $invoice_id); ?>" class="btn bg-secondary"><i
                    class="fa fa-pencil"></i> Edit </a>
        <?php endif ?>
        <a href="<?= url_to('erp.sale.invoice'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">

    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="invoice_info">Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="invoice_attachment">Attachments</a></li>
        <li><a type="button" class="tab_nav_item" data-src="invoice_notify">Notify</a></li>
        <li><a type="button" class="tab_nav_item" data-src="invoice_payment">Payments</a></li>
    </ul>

    <div class="tab_content">
        <div class="tab_pane active" id="invoice_info">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <a href="<?php echo url_to('erp.invoice.manage.payandcredit', $invoice_id); ?>"
                        class="btn bg-warning" type="button">Manage Credits</a>
                    <ul class="dropdown-style btn bg-info">
                        <li><a href="#">PDF &dtrif;</a>
                            <ul class="dropdown">
                                <li><a href="<?php echo url_to('erp.sale.invoice.pdf.view', $invoice_id, 'view'); ?>">View
                                        PDF</a> </li>
                                <li><a href="<?php echo url_to('erp.sale.invoice.pdf.view', $invoice_id, 'view'); ?>"
                                        target="_blank">View PDF in New Tab</a></li>
                                <li><a
                                        href="<?php echo url_to('erp.sale.invoice.pdf.view', $invoice_id, 'download'); ?>">Download</a>
                                </li>
                                <li><a href="<?php echo url_to('erp.sale.invoice.pdf.view', $invoice_id, 'view'); ?>"
                                        target="_blank">Print PDF</a></li>
                                <li><a href="#" id="printbutton">Print View</a></li>
                            </ul>
                        </li>
                    </ul>

                    <a class="btn bg-success" id="mail_modal_invoker1">Send Email</a>
                    <a href="<?= url_to('erp.sale.invoice.delete', $invoice_id) ?>"
                        class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1" id="printContainer">
                    <h2>Sale Invoice Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Code</th>
                                    <td><?php echo $invoice->code; ?></td>
                                </tr>
                                <tr>
                                    <th>Customer</th>
                                    <td><?php echo $invoice->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Billing Address</th>
                                    <td><?php echo $invoice->billing_addr; ?></td>
                                </tr>
                                <tr>
                                    <th>Shipping Address</th>
                                    <td><?php echo $invoice->shipping_addr; ?></td>
                                </tr>
                                <tr>
                                    <th>Invoice Date</th>
                                    <td><?php echo $invoice->invoice_date; ?></td>
                                </tr>
                                <tr>
                                    <th>Invoice Expiry</th>
                                    <td><?php echo $invoice->invoice_expiry; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="st <?php echo $invoice_status_bg[$invoice->status]; ?>">
                                            <?php
                                            if ($invoice->status == 0) {
                                                echo 'Created';
                                            } elseif ($invoice->status == 1) {
                                                echo 'Partially Paid';
                                            } elseif ($invoice->status == 2) {
                                                echo 'Paid';
                                            } elseif ($invoice->status == 3) {
                                                echo 'Overdue';
                                            }
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Transport Requested</th>
                                    <td><?php echo $invoice->transport_req; ?></td>
                                </tr>
                                <tr>
                                    <th>Transport Charge</th>
                                    <td><?php echo $invoice->trans_charge; ?></td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td><?php echo $invoice->discount; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <td><?php echo number_format($invoice->total_amount, 2, '.', ','); ?></td>
                                </tr>
                                <tr>
                                    <th>Credit Applied</th>
                                    <td><?php echo $applied_credits; ?></td>
                                </tr>
                                <tr>
                                    <th>Amount Paid</th>

                                    <td><?php echo $invoice->paid_till; ?></td>
                                </tr>
                                <tr>
                                    <th>Amount Due</th>
                                    <?php
                                    $amount_due_after_credits = max(0, $invoice->amount_due - $applied_credits);
                                    ?>
                                    <td><?php echo number_format($amount_due_after_credits, 2, '.', ','); ?></td>
                                </tr>
                                <tr>
                                    <th>Payment Terms</th>
                                    <td><?php echo $invoice->payment_terms; ?></td>
                                </tr>
                                <tr>
                                    <th>Terms and Condition</th>
                                    <td><?php echo $invoice->terms_condition; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h2>Invoice Items</h2>

                    <div class="table_responsive">
                        <table class="table">
                            <?php if ($isexpense) { ?>
                                <thead>
                                    <th>SNo</th>
                                    <th>Product</th>
                                    <th>Quantity</th>

                                    <th>Amount</th>
                                </thead>
                                <tbody>

                                    <?php
                                    $inc = 1;
                                    foreach ($invoice_items as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $inc; ?></td>
                                            <td><?php echo $row['expensetype']; ?></td>
                                            <td><?php echo $row['quantity']; ?></td>
                                            <td><?php echo number_format($row['total_amount'], 2, '.', ','); ?></td>
                                        </tr>

                                        <?php
                                        $inc++;
                                    }
                                    ?>
                                </tbody>
                            <?php } else { ?>
                                <thead>
                                    <th>SNo</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>unit_price</th>
                                    <th>Amount</th>
                                </thead>
                                <tbody>

                                    <?php
                                    $inc = 1;
                                    foreach ($invoice_items as $row) {
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
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="invoice_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame"
                        data-ajax-url="<?= url_to('erp.sale.invoice.upload.attachments') . '?id=' . $invoice_id . '&'; ?>">
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
                            <tbody class="attachment-holder"
                                data-ajaxdel-url="<?= url_to('erp.sale.invoice.delete.attachments') . '?'; ?>">
                                <?php
                                foreach ($attachments as $attach) {
                                    ?>
                                    <tr>
                                        <td><a target="_BLANK" download class="text-primary"
                                                href="<?php echo get_attachment_link('sale_invoice') . $attach['filename']; ?>"><?php echo $attach['filename']; ?></a>
                                        </td>
                                        <td><button class="btn bg-danger del-attachment-btn" type="button"
                                                data-attach-id="<?php echo $attach['attach_id']; ?>"><i
                                                    class="fa fa-trash"></i></button></td>
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
                    <button class="btn bg-primary modalBtn" id="notify_modal_invoker1" type="button"><i
                            class="fa fa-plus"></i>Add Notify</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="notify_datatable"
                        data-ajax-url="<?= url_to('sale.invoice.view.createnotify') . '?invoiceid=' . $invoice_id . '&'; ?>">
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
                                <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i>
                                    Export</a>
                                <div class="export_container poF">
                                    <ul class="exportUl">
                                        <li><a data-default-href="<?= url_to('sale.invoice.view.notify.exporter') . '?export=excel&invoiceid=' . $invoice_id . '&'; ?>"
                                                href="<?= url_to('sale.invoice.view.notify.exporter') . '?export=excel&invoiceid=' . $invoice_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>"
                                                    alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?= url_to('sale.invoice.view.notify.exporter') . '?export=pdf&invoiceid=' . $invoice_id . '&'; ?>"
                                                href="<?= url_to('sale.invoice.view.notify.exporter') . '?export=pdf&invoiceid=' . $invoice_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>"
                                                    alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?= url_to('sale.invoice.view.notify.exporter') . '?export=csv&invoiceid=' . $invoice_id . '&'; ?>"
                                                href="<?= url_to('sale.invoice.view.notify.exporter') . '?export=csv&invoiceid=' . $invoice_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>"
                                                    alt="csv">CSV</a></li>
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
        <div class="tab_pane" id="invoice_payment">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <a href="#" id="payment_modal_invoker1" class="btn bg-primary" type="button"><i
                            class="fa fa-plus"></i>Add Payment</a>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="invoice_payment_datatable"
                        data-ajax-url="<?= url_to('erp.sale.ajax_salepayment_response') . '?invoiceid=' . $invoice_id . '&'; ?>">
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
                                <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i>
                                    Export</a>
                                <div class="export_container poF">
                                    <ul class="exportUl">
                                        <li><a data-default-href="<?= url_to('erp.sale.invoice_payment_export') . '?export=excel&invoiceid=' . $invoice_id . '&'; ?>"
                                                href="<?= url_to('erp.sale.invoice_payment_export') . '?export=excel&invoiceid=' . $invoice_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>"
                                                    alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?= url_to('erp.sale.invoice_payment_export') . '?export=pdf&invoiceid=' . $invoice_id . '&'; ?>"
                                                href="<?= url_to('erp.sale.invoice_payment_export') . '?export=pdf&invoiceid=' . $invoice_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>"
                                                    alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?= url_to('erp.sale.invoice_payment_export') . '?export=csv&invoiceid=' . $invoice_id . '&'; ?>"
                                                href="<?= url_to('erp.sale.invoice_payment_export') . '?export=csv&invoiceid=' . $invoice_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>"
                                                    alt="csv">CSV</a></li>
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
                        <input type="hidden" class="ajaxselectBox_Value field-check" id="f_notify_to" name="notify_to"
                            value="">
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
                <label class="form-check-label"><input id="f_notify_email" type="checkbox" name="notify_email"
                        value="1" /> Notify via Email too </label>
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
            <div class="form-group field-money">
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
                        <input type="hidden" class="selectBox_Value field-check" id="f_payment_id" name="payment_id"
                            value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($paymentmodes as $row) {
                            ?>
                            <li role="option" data-value="<?php echo $row['payment_id']; ?>"><?php echo $row['name']; ?>
                            </li>
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
                <input type="email" class="form_control field-check" id="from_email" name="from_mail"
                    value="<?php echo $user_email; ?>" disabled />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">To</label>
                <select class="form_control field-check" name="to_mail" id="to_mail">
                    <?php foreach ($customerContact as $email): ?>
                        <option value="<?= $email['email'] ?>"><?= $email['email'] ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Cc</label>
                <input type="text" class="form_control" id="cc_email" name="cc_mail" value="" />
                <p class="error-text"></p>

            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Subject</label>
                <input type="text" class="form_control" required id="mail_subject" name="subject_mail"
                    value="<?= $email_template['subject'] ?>" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label ">Message</label>
                <?php
                echo form_textarea(array(
                    "id" => "mail_message",
                    "name" => "mail_message",
                    "value" => process_images_from_content(($email_template['message']), false),
                    "class" => "form-control different_language_custom_message"
                ));
                ?>
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary"  type="submit" id="email_addedit_btn">Send</button>
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
        listener: function (json) {
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
        document.getElementById("mail_subject").value = "<?= $email_template['subject'] ?>";
        // document.getElementById("mail_message").innerHTML = "<?= $email_template['message'] ?>";
        for (let i = 0; i < email_selectboxes.length; i++) {
            email_selectboxes[i].construct();
        }
        email_modal.show_modal();
    };

    //Print Invoice

    window.onload = function () {
        document.getElementById('printbutton').addEventListener('click', function () {
            // Use html2canvas to capture the specific container
            html2canvas(document.getElementById('printContainer')).then(function (canvas) {
                var screenshotImage = new Image();
                screenshotImage.src = canvas.toDataURL();

                // Create a new window and wait for it to load
                var screenshotWindow = window.open();
                screenshotWindow.document.write('<html><head><title>Invoice Print</title></head><body></body></html>');
                screenshotWindow.document.body.appendChild(screenshotImage);

                // Wait for the image to load in the new window
                screenshotImage.onload = function () {
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

<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

<script>
    initWYSIWYGEditor = function (element, options) {
        if (!options) {
            options = {};
        }

        var settings = $.extend({}, {
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['hr']],
                ['view', ['fullscreen', 'codeview']]
            ],
            disableDragAndDrop: true,
            callbacks: {
                onImageUpload: function (files, editor, $editable) {
                    for (var i = 0; i < files.length; i++) {
                        uploadPastedImage(files[i], $(element));
                    }

                }
            }
        }, options);

        $(element).summernote(settings);
    };

    getWYSIWYGEditorHTML = function (element) {
        return $(element).summernote('code');
    };

    combineCustomFieldsColumns = function (defaultFields, customFieldString) {
        if (defaultFields && customFieldString) {

            var startAfter = defaultFields.slice(-1)[0];
            //count no of custom fields
            var noOfCustomFields = customFieldString.split(',').length - 1;
            if (noOfCustomFields) {
                for (var i = 1; i <= noOfCustomFields; i++) {
                    defaultFields.push(i + startAfter);
                }
            }
        }
        return defaultFields;
    };
</script>

<script>

    var addCommentLink = function (event) {
        //modify comment link copied text on pasting
        var clipboardData = event.originalEvent.clipboardData.getData('text/plain');
        if (clipboardData.indexOf('/#comment') > -1) {
            //pasted comment link
            event.preventDefault();

            var splitClipboardData = clipboardData.split("/"),
                splitClipboardDataCount = splitClipboardData.length,
                commentId = splitClipboardData[splitClipboardDataCount - 1];

            if (!commentId) {
                //there has an extra / at last
                splitClipboardDataCount = splitClipboardDataCount - 1;
                commentId = splitClipboardData[splitClipboardDataCount - 1];
            }

            var splitCommentId = commentId.split("-");
            commentId = splitCommentId[1];

            var taskId = splitClipboardData[splitClipboardDataCount - 2];

            var newClipboardData = "#[" + taskId + "-" + commentId + "] (" + AppLanugage.comment + ") ";

            document.execCommand('insertText', false, newClipboardData);
        }
    };

    //normal input/textarea
    $('body').on('paste', 'input, textarea', function (e) {
        addCommentLink(e);
    });

    //summernote
    $('body').on('summernote.paste', function (e, ne) {
        addCommentLink(ne);
    });


    initWYSIWYGEditor("#mail_message", { height: 480 });
</script>



</body>

</html>