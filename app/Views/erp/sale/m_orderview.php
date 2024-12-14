<div class="alldiv flex widget_title">
    <h3>View Order</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.sale.orders'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="order_info">Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="order_attachment">Attachments</a></li>
        <li><a type="button" class="tab_nav_item" data-src="order_notify">Notify</a></li>
        <li><a type="button" class="tab_nav_item" data-src="order_stock">Stock</a></li>
        <li><a type="button" class="tab_nav_item" data-src="order_dispatch">Dispatch</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="order_info">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <?php
                    if ($order->stock_pick == 1 && $order->status == 0) {
                    ?>
                        <button type="button" class="btn bg-primary modalBtn" id="createinvoice_invoker1">Create Invoice</button>
                    <?php
                    }
                    ?>
                    
                    <ul class="dropdown-style btn bg-info">
                        <li><a href="#">PDF &dtrif;</a>
                            <ul class="dropdown">
                                <li><a href="#">View PDF</a> </li>
                                <li><a href="#">View PDF in New Tab</a></li>
                                <li><a href="#">Download</a></li>
                                <li><a href="#" id="printbutton">Print</a></li>
                            </ul>
                        </li>
                    </ul>

                    <!-- <a class="btn outline-primary" id="printbutton">Print</a> -->
                    <a class="btn bg-success" id="mail_modal_invoker1">Send Email</a>
                    <a href="<?= url_to('erp.sale.orderdelete', $order_id); ?>" class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1" id="printContainer">
                    <h2>Sale Order Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Code</th>
                                    <td><?php echo $order->code; ?></td>
                                </tr>
                                <tr>
                                    <th>Customer</th>
                                    <td><?php echo $order->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Billing Address</th>
                                    <td><?php echo $order->billing_addr; ?></td>
                                </tr>
                                <tr>
                                    <th>Shipping Address</th>
                                    <td><?php echo $order->shipping_addr; ?></td>
                                </tr>
                                <tr>
                                    <th>Order date</th>
                                    <td><?php echo $order->order_date; ?></td>
                                </tr>
                                <tr>
                                    <th>Order Expiry</th>
                                    <td><?php echo $order->order_expiry; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="st <?php echo $order_status_bg[$order->status]; ?>"><?php echo $order_status[$order->status]; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Transport Requested</th>
                                    <td><?php echo $order->transport_req; ?></td>
                                </tr>
                                <tr>
                                    <th>Transport Charge</th>
                                    <td><?php echo $order->trans_charge; ?></td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td><?php echo $order->discount; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <td><?php echo number_format($order->total_amount, 2, '.', ','); ?></td>
                                </tr>
                                <tr>
                                    <th>Payment Terms</th>
                                    <td><?php echo $order->payment_terms; ?></td>
                                </tr>
                                <tr>
                                    <th>Terms and Condition</th>
                                    <td><?php echo $order->terms_condition; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h2>Order Items</h2>
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
                                foreach ($order_items as $row) {
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
        <div class="tab_pane" id="order_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame" data-ajax-url="<?= url_to('erp.sale.order.upload.attachments') . '?id=' . $order_id . '&'; ?>">
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
                            <tbody class="attachment-holder" data-ajaxdel-url="<?= url_to('erp.sale.order.delete.attachments') . '?'; ?>">
                                <?php
                                foreach ($attachments as $attach) {
                                ?>
                                    <tr>
                                        <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('sale_order') . $attach['filename']; ?>"><?php echo $attach['filename']; ?></a></td>
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

        <div class="tab_pane" id="order_notify">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <button class="btn bg-primary modalBtn" id="notify_modal_invoker1" type="button"><i class="fa fa-plus"></i>Add Notify</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable" data-ajax-url="<?= url_to('sale.order.view.ajax.datatable') . '?orderid=' . $order_id . '&'; ?>">

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
                                        <li><a data-default-href="<?= url_to('sale.order.notify.export') . '?export=excel&orderid=' . $order_id . '&'; ?>" href="<?= url_to('sale.order.notify.export') . '?export=excel&orderid=' . $order_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?= url_to('sale.order.notify.export') . '?export=pdf&orderid=' . $order_id . '&'; ?>" href="<?= url_to('sale.order.notify.export') . '?export=pdf&orderid=' . $order_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?= url_to('sale.order.notify.export') . '?export=csv&orderid=' . $order_id . '&'; ?>" href="<?= url_to('sale.order.notify.export') . '?export=csv&orderid=' . $order_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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
        <div class="tab_pane" id="order_stock">
            <?php
            if ($order->stock_pick == 1) {
            ?>
                <div class="table_responsive">
                    <table class="table">
                        <thead>
                            <th>SNo</th>
                            <th>Product</th>
                            <th>Quantity to Pick</th>
                            <th>Warehouse</th>
                        </thead>
                        <tbody>
                            <?php
                            $sno = 1;
                            foreach ($stock_entry as $row) {
                            ?>
                                <tr>
                                    <td><?php echo $sno; ?></td>
                                    <td><?php echo $row['product']; ?></td>
                                    <td><?php echo $row['qty']; ?></td>
                                    <td><?php echo $row['warehouse']; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
            } else
            ?>
            <form action="base_url().'erp/sale/stockpick/'." id="stock_pick_form" class="flex">
                <div class="form-width-1 textCenter">
                    <div class="form-group">
                        <span class="st st_danger">Once updated can't be edited</span>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="table_responsive">
                        <table class="table">
                            <thead>
                                <th>SNo</th>
                                <th>Product</th>
                                <th>Quantity to Pick</th>
                                <th>Warehouse Stocks</th>
                            </thead>
                            <tbody id="stock_pick_holder">
                                <?php
                            $sno = 1;
                            foreach ($stock_pick as $key => $row) {
                                ?>
                                    <td><?php echo $sno; ?></td>
                                    <td><?php echo $row['product']; ?><input type="hidden" name="product_id[<?php echo $sno; ?>]" value="<?php echo $key; ?>" /></td>
                                    <td><span class="qty-to-pick"><?php echo $row['qty_to_pick']; ?></span></td>
                                    <td>
                                        <?php
                                        $inc = 0;
                                        foreach ($row['warehouses'] as $inner_row) {
                                        ?>
                                            <div class="flex">
                                                <input type="hidden" name="product_id_<?php echo $key; ?>[<?php echo $inc; ?>]" value="<?php echo $key; ?>" />
                                                <span><?php echo $inner_row['name']; ?></span>
                                                <input type="hidden" class="stock_id" name="stock_id_<?php echo $key; ?>[<?php echo $inc; ?>]" value="<?php echo $inner_row['id']; ?>" />
                                                <b class="warehouse-stock"><?php echo $inner_row['stock']; ?></b>
                                                <input type="hidden" class="warehouse_qty" name="warehouse_qty_<?php echo $key; ?>[<?php echo $inc; ?>]" />
                                                <button type="button" class="btn outline-success stock-pick-btn">Pick</button>
                                            </div>
                                        <?php
                                            $inc++;
                                        } ?>
                                    </td>
                                <?php
                                $sno++;
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- <div class="form-width-1">
                    <div class="form-group textRight">
                        <a class="btn outline-danger" href="url_to('erp.sale.orders'); ">Cancel</a>
                        <button class="btn bg-primary" type="button" id="stock_pick_submit">Update</button>
                    </div>
                </div> -->
                <?=
                form_close();
                ?>
        </div>



        <!--Dispatch-->

        <div class="tab_pane" id="order_dispatch">
            <div class="flex">
                <div class="form-width-1">
                    <div class="datatable" id="dispatch_datatable" data-ajax-url="<?= url_to('sale.order.view.dispatch.ajax.datatable') . '?orderid=' . $order_id . '&'; ?>">
                        <div class="filterBox">
                            <div class="flex">
                                <h4>Filter and Search</h4>
                                <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
                            </div>
                            <div class="flex filterBox_container">
                                <div class="formWidth">
                                    <div class="selectBox poR" id="dispatch_filter_1">
                                        <div class="selectBoxBtn flex">
                                            <div class="textFlow" data-default="select status">select status</div>
                                            <button class="close" type="button"><i class="fa fa-close"></i></button>
                                            <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                            <input type="hidden" class="selectBox_Value field-check" value="">
                                        </div>
                                        <ul role="listbox" class="selectBox_Container alldiv">
                                            <?php
                                            foreach ($dispatch_status as $key => $value) {
                                            ?>
                                                <li role="option" data-value="<?php echo $key; ?>"><?php echo $value; ?></li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="formWidth">
                                </div>
                                <div class="formWidth">
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
                                <!-- <div class="form-width-1 textRight">
                                    <button class="btn bg-primary modalBtn" id="notify_modal_invoker1" type="button">Add Dispatch</button>
                                </div> -->
                                <a href="#" type="button" id="dispatch_modal_invoker1" class="btn bg-primary modalBtn"> <i class="fa fa-plus"></i> Add Dispatch</a>
                                <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i> Export</a>
                                <div class="export_container poF">
                                    <ul class="exportUl">
                                        <li><a data-default-href="<?= url_to('sale.order.view.dispatch.export') . '?export=excel&dispatchid=' . $order_id . '&'; ?>" href="<?= url_to('sale.order.view.dispatch.export') . '?export=excel&dispatchid=' . $order_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?= url_to('sale.order.view.dispatch.export') . '?export=pdf&dispatchid=' . $order_id . '&'; ?>" href="<?= url_to('sale.order.view.dispatch.export') . '?export=pdf&dispatchid=' . $order_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?= url_to('sale.order.view.dispatch.export') . '?export=csv&dispatchid=' . $order_id . '&'; ?>" href="<?= url_to('sale.order.view.dispatch.export') . '?export=csv&dispatchid=' . $order_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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
    <?php echo form_open(url_to('sale.order.notify.add', $order_id), ["id" => "notify_addedit_form", "class" => "flex modal-scroll-form"]); ?>
    <div class="modalbody">
        <h2 class="modalTitle">Notify</h2>
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
    </div><?= form_close(); ?>
</div>



<div class="modal" id="stock_pick_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Pick</h2>
        <?php
        echo form_open(base_url(), array(
            "id" => "pick_form",
            "class" => "flex"
        ));
        ?>

        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-label">Max Stock</label>
                <input type="text" readonly class="form_control field-check" id="f_max_stock" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Pick</label>
                <input type="text" class="form_control field-check" id="f_pick_qty" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="pick_btn">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>


<div class="modal" id="create_dispatch_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Create Dispatch</h2>
        <?php
        echo form_open(url_to('order.view.dispatch.add', $order_id), array(
            "id" => "create_invoice_form",
            "class" => "flex modal-scroll-form"
        ));
        ?>
        <div class="form-width-1">
            <div class="form-group field-ajax">
                <label class="form-label">Order Code</label>
                <input type="text" class="form_control field-check" name="order_code" value="<?php echo $order->code; ?>" disabled />
                <input name="order_code" value="<?php echo $order->code; ?>" hidden />
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Customer</label>
                <input type="text" class="form_control field-check" name="customer_name" value="<?php echo $order->name; ?>" disabled />
                <input name="customer_name" value="<?php echo $order->name; ?>" hidden />
                <input name="cust_id" value="<?php echo $order->cust_id; ?>" hidden />
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Status</label>
                <div class="selectBox poR" id="dispatch_status">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select status">select status</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" name="dispatch_status" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($dispatch_status as $key => $value) {
                        ?>
                            <li role="option" data-value="<?php echo $key; ?>"><?php echo $value; ?></li>
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
                <label class="form-label">Delivery Date</label>
                <input type="datetime-local" class="form_control field-check" name="delivery_date" value="<?php echo $order->order_expiry; ?>" />
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Description</label>
                <textarea class="form_control field-check" id="dispatch_desc" name="dispatch_desc"></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="submit" id="create_invoice_btn">Create</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>

<!-- Dispatch -->


<!-- Email Send modal -->


<div class="modal" id="email_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Send via Mail</h2>
        <?php
        echo form_open(url_to('erp.sale.invoice.view.mailsend', $order_id, 'orders'), array(
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
            <div class="form-group field-required">
                <label class="form-label">Cc</label>
                <input type="text" class="form_control field-check" id="cc_email" name="cc_mail" value="" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Subject</label>
                <input type="text" class="form_control field-check" id="mail_subject" name="subject_mail"></input>
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label field-required">Message</label>
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

    let dispatch_status_box = new SelectBox(document.getElementById("dispatch_status"));
    dispatch_status_box.init();
    closer.register_shutdown(dispatch_status_box.shutdown, dispatch_status_box.get_container());

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

    let stock_pick_modal = new ModalBox(document.getElementById("stock_pick_modal"));
    stock_pick_modal.init();
    let current_stock_modal_invoker = null;
    document.querySelectorAll("#stock_pick_holder .stock-pick-btn").forEach((item) => {
        item.onclick = (evt) => {
            document.getElementById("f_pick_qty").parentElement.classList.remove("form-error");
            document.getElementById("f_pick_qty").nextElementSibling.textContent = "";
            let max_qty = item.previousElementSibling.previousElementSibling.textContent;
            document.getElementById("f_max_stock").value = max_qty;
            stock_pick_modal.show_modal();
            current_stock_modal_invoker = item;
        }
    });
    document.getElementById("pick_btn").onclick = (evt) => {
        let max_qty = parseInt(document.getElementById("f_max_stock").value);
        let pick_qty = parseInt(document.getElementById("f_pick_qty").value);

        if (isNaN(pick_qty) || pick_qty <= 0) {
            document.getElementById("f_pick_qty").parentElement.classList.add("form-error");
            document.getElementById("f_pick_qty").nextElementSibling.textContent = "Invalid pick quantity";
            return;
        }

        if (pick_qty > max_qty) {
            document.getElementById("f_pick_qty").parentElement.classList.add("form-error");
            document.getElementById("f_pick_qty").nextElementSibling.textContent = "Quantity greater than Max Stock";
            return;
        }

        if (current_stock_modal_invoker !== null) {
            current_stock_modal_invoker.previousElementSibling.value = pick_qty;
            let qty_to_pick_h = current_stock_modal_invoker.parentElement.parentElement.previousElementSibling.querySelector(".qty-to-pick");
            let q = parseInt(qty_to_pick_h.textContent);
            if (pick_qty > q) {
                stock_pick_modal.hide_modal();
                current_stock_modal_invoker = null;
                alert.invoke_alert("Quantity is much greater", "error");
            } else {
                q = q - pick_qty;
                qty_to_pick_h.textContent = q;
                let w_stock = current_stock_modal_invoker.previousElementSibling.previousElementSibling;
                let w = parseInt(w_stock.textContent);
                w = w - pick_qty;
                w_stock.textContent = w;
                stock_pick_modal.hide_modal();
                current_stock_modal_invoker = null;
            }
        }
    }



    let stock_pick_form = document.getElementById("stock_pick_form");
    let stock_pick_submit = document.getElementById("stock_pick_submit");
    if (stock_pick_submit !== null && stock_pick_submit !== undefined) {
        stock_pick_submit.onclick = (evt) => {
            let qty_to_pick = document.querySelectorAll("#stock_pick_holder .qty-to-pick");
            let process = true;
            for (let i = 0; i < qty_to_pick.length; i++) {
                let q = parseInt(qty_to_pick[i].textContent);
                console.log(q);
                if (q !== 0) {
                    process = false;
                    break;
                }
            }
            if (!process) {
                alert.invoke_alert("Quantity to Pick is not Zero", "error");
                return;
            } else {
                stock_pick_form.submit();
            }
        }
    }

    //dispatch
    /**
     * Dispatch Datatable
     */
    let dispatch_datatable_elem = document.querySelector("#dispatch_datatable");
    let dispatch_rows_per_page = new SelectBox(dispatch_datatable_elem.querySelector(".tableFooter .selectBox"));
    dispatch_rows_per_page.init();
    closer.register_shutdown(dispatch_rows_per_page.shutdown, dispatch_rows_per_page.get_container());
    let dispatch_bulkaction = new SelectBox(dispatch_datatable_elem.querySelector(".tableHeader .bulkaction"));
    dispatch_bulkaction.init();
    closer.register_shutdown(dispatch_bulkaction.shutdown, dispatch_bulkaction.get_container());
    let dispatch_config = JSON.parse('<?php echo $dispatch_datatable_config; ?>');
    let dispatch_datatable = new DataTable(dispatch_datatable_elem, dispatch_config);
    dispatch_datatable.init();
    dispatch_rows_per_page.add_listener(dispatch_datatable.rows_per_page, {});

    // Check if filters are defined in config
    if (dispatch_config['filters'] !== null && dispatch_config['filters'] !== undefined) {
        // Get filter element
        let filter_1 = document.getElementById("dispatch_filter_1");

        // Initialize SelectBox for filter
        let select_box_1 = new SelectBox(filter_1);
        select_box_1.init();

        // Add listener for filter change
        select_box_1.add_listener(dispatch_datatable.filter, {
            type: "select",
            column: dispatch_config['filters'][0]
        });

        // Register shutdown for the filter
        closer.register_shutdown(select_box_1.shutdown, select_box_1.get_container());
    }

    let create_dispatch_modal = new ModalBox(document.getElementById("create_dispatch_modal"));
    create_dispatch_modal.init();
    document.getElementById("dispatch_modal_invoker1").onclick = (evt) => {
        create_dispatch_modal.show_modal();
    };



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


    // Print view page
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

    //Dispatch

    let modal_box_dispatch = new ModalBox(document.getElementById("dispatch_addedit_modal"));
    modal_box_dispatch.init();

    let dispatch_ajax_select = [];
    document.querySelectorAll("#dispatch_addedit_modal .ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        dispatch_ajax_select.push(ajaxselectbox);
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });



    document.getElementById("dispatch_modal_invoker1").onclick = (evt) => {
        document.getElementById("dispatch_ordercode").value = "0";
        document.getElementById("dispatch_customer").value = "";
        document.getElementById("dispatch_delivery_date").value = "";
        document.getElementById("dispatch_desc").value = "";
        for (let i = 0; i < dispatch_ajax_select.length; i++) {
            dispatch_ajax_select[i].construct();
        }
        modal_box_dispatch.show_modal();
    };

    // let create_dispatch_modal = new ModalBox(document.getElementById("create_dispatch_modal"));
    // create_dispatch_modal.init();
    // document.getElementById("dispatch_modal_invoker1").onclick = (evt) => {
    //     create_dispatch_modal.show_modal();
    // };

    // let create_dispatch_form = document.getElementById("create_dispatch_form");
    // let create_dispatch_validator = new FormValidate(create_dispatch_form);

    // let create_dispatch_lock = false;
    // document.getElementById("create_dispatch_btn").onclick = (evt) => {
    //     if (!create_dispatch_lock) {
    //         create_dispatch_lock = true;
    //         create_dispatch_validator.validate(
    //             (params) => {
    //                 create_dispatch_form.submit();
    //                 create_dispatch_lock = false;
    //             },
    //             (params) => {
    //                 create_dispatch_lock = false;
    //             }, {});
    //     }
    // }


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