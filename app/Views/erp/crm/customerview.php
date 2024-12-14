<div class="alldiv flex widget_title">
    <h3>View Customer</h3>
    <div class="title_right">
        <a href="<?php echo base_url() . 'erp/crm/customers'; ?>" class="btn bg-success"><i class="fa fa-reply"></i>
            Back
        </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="customer_profile">Profile</a></li>
        <li><a type="button" class="tab_nav_item" data-src="customer_contacts">Contacts</a></li>
        <li><a type="button" class="tab_nav_item" data-src="customer_billingaddr">Billing Address</a></li>
        <li><a type="button" class="tab_nav_item" data-src="customer_shippingaddr">Shipping Address</a></li>
        <li><a type="button" class="tab_nav_item" data-src="customer_attachment">Attachments</a></li>
        <li><a type="button" class="tab_nav_item" data-src="customer_notify">Notify</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="customer_profile">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <a href="<?= url_to('erp.crm.customerdelete', $customer_id); ?>"
                        class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1">
                    <h2>Customer Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $customer->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $customer->email; ?></td>
                                </tr>
                                <tr>
                                    <th>Company</th>
                                    <td><?php echo $customer->company; ?></td>
                                </tr>
                                <tr>
                                    <th>GST</th>
                                    <td><?php echo $customer->gst; ?></td>
                                </tr>
                                <tr>
                                    <th>Position</th>
                                    <td><?php echo $customer->position; ?></td>
                                </tr>
                                <tr>
                                    <th>Website</th>
                                    <td><?php echo $customer->website; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><?php echo $customer->phone; ?></td>
                                </tr>
                                <tr>
                                    <th>Fax Number</th>
                                    <td><?php echo $customer->fax_num; ?></td>
                                </tr>
                                <tr>
                                    <th>Office Number</th>
                                    <td><?php echo $customer->office_num; ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>
                                        <p><?php echo $customer->address; ?> ,</p>
                                        <p> <?php echo $customer->state; ?>,</p>
                                        <p> <?php echo $customer->state; ?>,</p>
                                        <p> <?php echo $customer->country; ?>-<?php echo $customer->zip; ?></p>
                                    </td>
                                </tr>
                                <?php
                                /******CUSTOM FIELDS******/
                                echo $custom_field_values;
                                ?>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $customer->description; ?></td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td><?php echo $customer->remarks; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="customer_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame"
                        data-ajax-url="<?php echo url_to('erp.crm.uploadcustomerattachment') . '?id=' . $customer_id . '&'; ?>">
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
                                data-ajaxdel-url="<?php echo url_to('erp.crm.customerdeleteattachment') . '?'; ?>">
                                <?php
                                foreach ($attachments as $attach) {
                                    ?>
                                    <tr>
                                        <td><a target="_BLANK" download class="text-primary"
                                                href="<?php echo get_attachment_link('customer') . $attach['filename']; ?>"><?php echo $attach['filename']; ?></a>
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
        <div class="tab_pane" id="customer_contacts">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <button class="btn bg-primary modalBtn" id="contact_modal_invoker1" type="button"><i
                            class="fa fa-plus"></i>Add Contact</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="customer_contact_datatable"
                        data-ajax-url="<?php echo url_to('erp.crm.ajaxcustomercontactresponse') . '?' . 'custid=' . $customer_id . '&'; ?>">
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
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/customer-contact-export?export=excel&custid=' . $customer_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/customer-contact-export?export=excel&custid=' . $customer_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>"
                                                    alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/customer-contact-export?export=pdf&custid=' . $customer_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/customer-contact-export?export=pdf&custid=' . $customer_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>"
                                                    alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/customer-contact-export?export=csv&custid=' . $customer_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/customer-contact-export?export=csv&custid=' . $customer_id . '&'; ?>"
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
        <div class="tab_pane" id="customer_billingaddr">
            <div class="flex">
                <?php if ($get_billing_data > 0): ?>
                    <div class="form-width-1 textRight">
                        <button class="btn bg-primary modalBtn" id="billing_modal_invoker1" type="button"
                            style="display: none;"><i class="fa fa-plus"></i>Add Billing Address</button>
                    </div>
                <?php else: ?>
                    <div class="form-width-1 textRight">
                        <button class="btn bg-primary modalBtn" id="billing_modal_invoker1" type="button"><i
                                class="fa fa-plus"></i>Add Billing Address</button>
                    </div>
                <?php endif; ?>
                <div class="form-width-1">
                    <div class="datatable" id="customer_billing_datatable"
                        data-ajax-url="<?php echo url_to('erp.crm.ajaxcustomerbillingresponse') . '?custid=' . $customer_id . '&'; ?>">
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
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/customer-billing-export?export=excel&custid=' . $customer_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/customer-billing-export?export=excel&custid=' . $customer_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>"
                                                    alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/customer-billing-export?export=pdf&custid=' . $customer_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/customer-billing-export?export=pdf&custid=' . $customer_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>"
                                                    alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/customer-billing-export?export=csv&custid=' . $customer_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/customer-billing-export?export=csv&custid=' . $customer_id . '&'; ?>"
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
        <div class="tab_pane" id="customer_shippingaddr">
            <div class="flex">
                <?php if ($get_shipping_data > 0): ?>
                    <div class="form-width-1 textRight">
                        <button class="btn bg-primary modalBtn" id="shipping_modal_invoker1" type="button"
                            style="display: none;"><i class="fa fa-plus"></i>Add Shipping Address</button>
                    </div>
                <?php else: ?>
                    <div class="form-width-1 textRight">
                        <button class="btn bg-primary modalBtn" id="shipping_modal_invoker1" type="button"><i
                                class="fa fa-plus"></i>Add Shipping Address</button>
                    </div>
                <?php endif; ?>
                <div class="form-width-1">
                    <div class="datatable" id="customer_shipping_datatable"
                        data-ajax-url="<?php echo url_to('erp.crm.ajaxcustomershippingresponse') . '?custid=' . $customer_id . '&'; ?>">
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
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/customer-shipping-export?export=excel&custid=' . $customer_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/customer-shipping-export?export=excel&custid=' . $customer_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>"
                                                    alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/customer-shipping-export?export=pdf&custid=' . $customer_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/customer-shipping-export?export=pdf&custid=' . $customer_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>"
                                                    alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/customer-shipping-export?export=csv&custid=' . $customer_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/customer-shipping-export?export=csv&custid=' . $customer_id . '&'; ?>"
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
        <div class="tab_pane" id="customer_notify">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <button class="btn bg-primary modalBtn" id="notify_modal_invoker1" type="button"><i
                            class="fa fa-plus"></i>Add Notify</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="notify_datatable"
                        data-ajax-url="<?php echo url_to('erp.crm.ajaxcustomernotifyresponse') . '?custid=' . $customer_id . '&'; ?>">
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
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/customer-notify-export?export=excel&custid=' . $customer_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/customer-notify-export?export=excel&custid=' . $customer_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>"
                                                    alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/customer-notify-export?export=pdf&custid=' . $customer_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/customer-notify-export?export=pdf&custid=' . $customer_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>"
                                                    alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/customer-notify-export?export=csv&custid=' . $customer_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/customer-notify-export?export=csv&custid=' . $customer_id . '&'; ?>"
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
<!--NOTIFY MODAL -->
<div class="modal" id="notify_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Notify</h2>
        <?php
        echo form_open(url_to('erp.crm.customernotify', $customer_id), array(
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
                <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.crm.ajaxFetchUsers'); ?>">
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
        </form>
    </div>
</div>

<style>
    .contact-cont {
        position: relative;
        overflow-y: scroll;
    }
</style>

<!-- CONTACT MODAL -->
<div class="modal" id="contact_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Contact</h2>
        <form action="<?php echo url_to('erp.crm.customercontact', $customer_id) ?>" method="POST" class="flex"
            id="contact_addedit_form" enctype="multipart/form-data">
            <input type="hidden" name="contact_id" id="f_contact_id" value="0" />
            <div class="form-width-1">
                <div class="form-group">
                    <label class="form-label">Profile image</label>
                    <input type="file" class="form_control field-check" name="profile_image" value="" id="profile_image"
                        accept="image/*">
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-2">
                <div class="form-group field-required ">
                    <label class="form-label">First name <span class="text-danger p-1">*</span></label>
                    <input type="text" class="form_control field-check" id="f_contact_firstname" name="firstname" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-2">
                <div class="form-group ">
                    <label class="form-label">Last name</label>
                    <input type="text" class="form_control field-check" id="f_contact_lastname" name="lastname" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-2">
                <div class="form-group field-required ">
                    <label class="form-label">Position</label>
                    <input type="text" class="form_control field-check" id="f_contact_position" name="position" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-2">
                <div class="form-group field-ajax "
                    data-ajax-url="<?php echo url_to('erp.crm.ajaxcontactmailunique') . '?cust_id=' . $customer_id . '&contact_id=0&'; ?>">
                    <label class="form-label">Email <span class="text-danger p-1">*</span></label>
                    <input type="text" class="form_control field-check" id="f_contact_email" name="email" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-2">
                <div class="form-group field-phone ">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form_control field-check" id="f_contact_phone" name="phone" />
                    <p class="error-text"></p>
                </div>
            </div>
            <style>
                .cover-btn {
                    position: relative;
                }

                .cover-btn::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                    z-index: 10;
                }
            </style>
            <div class="form-width-2">
                <div class="form-group field-password">
                    <label class="form-label">Password</label>
                    <div class="d-flex justify-content-center align-items-center gap-1 p-0 m-0">
                        <input type="password" class="form_control field-check" id="rand_password" name="password" />
                        <button class="btn bg-success cover-btn" type="button" id="passwordtoggle"
                            style="margin:0px auto; margin-left:-5px;margin-top:-3px; width:30px;height: 40px; text-align:center; padding:8px;"><i
                                class="fa-solid fa-eye fa-sm" style="color: #ffffff;"></i></button>
                        <button type="button" class="p-2 btn bg-primary" id="random_password" style="margin:0px auto;">
                            <i class="fa-solid fa-rotate fa-lg"></i>
                        </button>
                    </div>
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1 tw-flex tw-justify-between tw-items-center">
                <div class="checkbox checkbox-primary checkbox-inline">
                    <input type="checkbox" name="Primary_contact" id="Primary_contact" value="1">
                    <input type="hidden" id="hidden_primary_contact" name="hidden_primary_contact" value="0">
                    <label for="Primary_contact">Primary Contact</label>
                </div>
            </div>
            <hr>
            <style>
                .slide-1 {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 2px;
                }
                .modalbody{
                    overflow-y: auto;
                    height: 100vh;
                }
            </style>
            <div class="form-width-1">
                <div class="form-group ">
                    <label class="form-label">Permissions</label>
                    <p class="mt-2 text-danger">Make sure to set appropriate permissions for this contact</p>
                </div>
            </div>
            <div class="form-width-2">
                <div class="form-group">
                    <div class="">
                        <div class="slide-1">
                            <Span class="">Invoices</Span>
                            <label class="toggle active-toggler-1" id="Invoices" data-ajax-url="">
                                <input type="checkbox" name="permission[]" value="Invoices"/>
                                <span class="togglebar"></span>
                            </label>
                        </div>
                        <div class="slide-1">
                            <Span class="">Estimates</Span>
                            <label class="toggle active-toggler-1" id="Estimates" data-ajax-url="">
                                <input type="checkbox" name="permission[]" value="Estimates" />
                                <span class="togglebar"></span>
                            </label>
                        </div>
                        <div class="slide-1">
                            <Span class="">Contracts</Span>
                            <label class="toggle active-toggler-1" id="Contracts" data-ajax-url="">
                                <input type="checkbox" name="permission[]" value="Contracts" />
                                <span class="togglebar"></span>
                            </label>
                        </div>
                        <div class="slide-1">
                            <Span class="">Quotations</Span>
                            <label class="toggle active-toggler-1" id="Quotations" data-ajax-url="">
                                <input type="checkbox" name="permission[]" value="Quotations" />
                                <span class="togglebar"></span>
                            </label>
                        </div>
                        <div class="slide-1">
                            <Span class="">Support</Span>
                            <label class="toggle active-toggler-1" id="Supports" data-ajax-url="">
                                <input type="checkbox" name="permission[]" value="Supports" />
                                <span class="togglebar"></span>
                            </label>
                        </div>
                        <div class="slide-1">
                            <Span class="">Projects</Span>
                            <label class="toggle active-toggler-1" id="Projects" data-ajax-url="">
                                <input type="checkbox" name="permission[]" value="Projects" />
                                <span class="togglebar"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-width-1 ">
                <div class="form-group textRight ">
                    <button type="button" class="btn outline-danger modalClose">Close</button>
                    <button class="btn bg-primary" type="button" id="contact_addedit_btn">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- BILLING MODAL -->
<div class="modal" id="billing_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Billing Address</h2>
        <?php
        echo form_open(url_to('erp.crm.customerbillingaddr', $customer_id), array(
            "id" => "billing_addedit_form",
            "class" => "flex"
        ));
        ?>
        <input type="hidden" name="billingaddr_id" id="f_billingaddr_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Address</label>
                <input type="text" class="form_control field-check" id="f_billing_address" name="address" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">City</label>
                <input type="text" class="form_control field-check" id="f_billing_city" name="city" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">State</label>
                <input type="text" class="form_control field-check" id="f_billing_state" name="state" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Country</label>
                <input type="text" class="form_control field-check" id="f_billing_country" name="country" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Zipcode</label>
                <input type="text" class="form_control field-check" id="f_billing_zip" name="zip" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="billing_addedit_btn">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>

<!-- SHIPPING MODAL -->
<div class="modal" id="shipping_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Shipping Address</h2>
        <?php
        echo form_open(url_to('erp.crm.customershippingaddr', $customer_id), array(
            "id" => "shipping_addedit_form",
            "class" => "flex"
        ));
        ?>
        <input type="hidden" name="shippingaddr_id" id="f_shippingaddr_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Address</label>
                <input type="text" class="form_control field-check" id="f_shipping_address" name="address" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">City</label>
                <input type="text" class="form_control field-check" id="f_shipping_city" name="city" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">State</label>
                <input type="text" class="form_control field-check" id="f_shipping_state" name="state" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Country</label>
                <input type="text" class="form_control field-check" id="f_shipping_country" name="country" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Zipcode</label>
                <input type="text" class="form_control field-check" id="f_shipping_zip" name="zip" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="shipping_addedit_btn">Save</button>
            </div>
        </div>
        </form>
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
    let ajaxselectbox = null;

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

    let notify_modal = new ModalBox(document.getElementById("notify_addedit_modal"));
    notify_modal.init();

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
        notify_modal.show_modal();
    };

    let notify_paintarea = document.querySelector("#notify_datatable .table-paint-area");
    notify_paintarea.onclick = (evt) => {
        let target = evt.target;
        notify_paintarea.querySelectorAll(".modalBtn").forEach((item) => {
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
                                notify_modal.show_modal();
                            }
                        }
                    }
                }
            }
        });
    };


    let notify_form = document.getElementById("notify_addedit_form");
    let notify_validator = new FormValidate(notify_form);

    let notify_lock = false;
    document.getElementById("notify_addedit_btn").onclick = (evt) => {
        if (!notify_lock) {
            notify_lock = true;
            notify_validator.validate(
                (params) => {
                    notify_form.submit();
                    notify_lock = false;
                },
                (params) => {
                    notify_lock = false;
                },
                {});
        }
    }

    let contact_modal = new ModalBox(document.getElementById("contact_addedit_modal"));
    contact_modal.init();

    document.getElementById("contact_modal_invoker1").onclick = (evt) => {
        document.getElementById("f_contact_id").value = "0";
        document.getElementById("f_contact_firstname").value = "";
        document.getElementById("f_contact_lastname").value = "";
        document.getElementById("f_contact_position").value = "";
        document.getElementById("f_contact_email").value = "";
        document.getElementById("f_contact_phone").value = "";
        document.getElementById("profile_image").value = "";
        document.getElementById("Primary_contact").value = "";
        let ajax_url_holder = document.getElementById("f_contact_email").parentElement;
        let ajax_url = ajax_url_holder.getAttribute("data-ajax-url");
        let index = ajax_url.indexOf("contact_id=");
        let amp_index = ajax_url.indexOf("&", index + 1);
        let url1 = ajax_url.substring(0, index + ("contact_id=".length));
        let url2 = ajax_url.substring(amp_index);
        ajax_url = url1 + "0" + url2;
        ajax_url_holder.setAttribute("data-ajax-url", ajax_url);

        contact_modal.show_modal();
    };

    let contact_paintarea = document.querySelector("#customer_contact_datatable .table-paint-area");
    contact_paintarea.onclick = (evt) => {
        let target = evt.target;
        contact_paintarea.querySelectorAll(".modalBtn").forEach((item) => {
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
                            // console.log(json);
                            if (json['error'] == 0) {
                                let data = json['data'];
                                // console.log(data);
                                document.getElementById("f_contact_id").value = data['contact_id'];
                                document.getElementById("f_contact_firstname").value = data['firstname'];
                                document.getElementById("f_contact_lastname").value = data['lastname'];
                                document.getElementById("f_contact_position").value = data['position'];
                                document.getElementById("f_contact_email").value = data['email'];
                                document.getElementById("f_contact_phone").value = data['phone'];
                                // console.log('******************');
                                let permissionlist = ['Invoices','Estimates','Contracts','Quotations','Supports','Projects'];
                                let permissions =  JSON.parse(data['permission']);
                                permissionlist.forEach((key)=>{
                                    for(let data in permissions){
                                        if(permissions[data] == key){
                                            document.getElementById(key).querySelector('input[type="checkbox"]').checked= true;
                                        }   
                                    }
                                })
                                if (data['primary_contact'] == 1) {
                                    document.getElementById("Primary_contact").checked = true;
                                    document.getElementById("hidden_primary_contact").value = 1;
                                    document.getElementById("Primary_contact").setAttribute("disabled", true);
                                } else {
                                    document.getElementById("Primary_contact").checked = false;
                                    document.getElementById("hidden_primary_contact").value = 0;
                                    document.getElementById("Primary_contact").removeAttribute("disabled");
                                }

                                document.getElementById("profile_image").value = "";
                                let ajax_url_holder = document.getElementById("f_contact_email").parentElement;
                                let ajax_url = ajax_url_holder.getAttribute("data-ajax-url");
                                let index = ajax_url.indexOf("contact_id=");
                                let amp_index = ajax_url.indexOf("&", index + 1);
                                let url1 = ajax_url.substring(0, index + ("contact_id=".length));
                                let url2 = ajax_url.substring(amp_index);
                                ajax_url = url1 + data['contact_id'] + url2;
                                ajax_url_holder.setAttribute("data-ajax-url", ajax_url);
                                contact_modal.show_modal();
                            }
                        }
                    }
                }
            }
        });
    };

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
    let contact_form = document.getElementById("contact_addedit_form");
    let contact_validator = new FormValidate(contact_form);
    let file = document.getElementById("profile_image");


    let contact_lock = false;
    document.getElementById("contact_addedit_btn").onclick = (evt) => {
        // console.log(contact_validator);
        if (!contact_lock) {
            contact_lock = true;
            let checkbox_1 = document.getElementById("Primary_contact")
            if(checkbox_1.checked){
                document.getElementById("hidden_primary_contact").value = 1;
            }else{
                document.getElementById("hidden_primary_contact").value = 0;
            }
            
            contact_validator.validate(
                (params) => {
                    contact_form.submit();
                    contact_lock = false;
                },
                (params) => {
                    contact_lock = false;
                },
                {});
            // } 
            // else {
            //     let errortext = file.closest(".form-group").querySelector(".error-text");
            //     errortext.innerText = "Profile Image is Required!";
            //     file.style.border = "1px solid red"
            //     errortext.style.color = "red";
            // }
        }
    }

    let billing_modal = new ModalBox(document.getElementById("billing_addedit_modal"));
    billing_modal.init();

    document.getElementById("billing_modal_invoker1").onclick = (evt) => {
        document.getElementById("f_billingaddr_id").value = "0";
        document.getElementById("f_billing_address").value = "";
        document.getElementById("f_billing_city").value = "";
        document.getElementById("f_billing_state").value = "";
        document.getElementById("f_billing_country").value = "";
        document.getElementById("f_billing_zip").value = "";
        billing_modal.show_modal();
    };

    let billing_paintarea = document.querySelector("#customer_billing_datatable .table-paint-area");
    billing_paintarea.onclick = (evt) => {
        let target = evt.target;
        billing_paintarea.querySelectorAll(".modalBtn").forEach((item) => {
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
                            console.log(json);
                            if (json['error'] == 0) {
                                let data = json['data'];
                                document.getElementById("f_billingaddr_id").value = data['billingaddr_id'];
                                document.getElementById("f_billing_address").value = data['address'];
                                document.getElementById("f_billing_city").value = data['city'];
                                document.getElementById("f_billing_state").value = data['state'];
                                document.getElementById("f_billing_country").value = data['country'];
                                document.getElementById("f_billing_zip").value = data['zipcode'];
                                billing_modal.show_modal();
                            }
                        }
                    }
                }
            }
        });
    };


    let shipping_modal = new ModalBox(document.getElementById("shipping_addedit_modal"));
    shipping_modal.init();

    document.getElementById("shipping_modal_invoker1").onclick = (evt) => {
        document.getElementById("f_shippingaddr_id").value = "0";
        document.getElementById("f_shipping_address").value = "";
        document.getElementById("f_shipping_city").value = "";
        document.getElementById("f_shipping_state").value = "";
        document.getElementById("f_shipping_country").value = "";
        document.getElementById("f_shipping_zip").value = "";
        shipping_modal.show_modal();
    };




    let shipping_paintarea = document.querySelector("#customer_shipping_datatable .table-paint-area");
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
                                document.getElementById("f_shippingaddr_id").value = data['shippingaddr_id'];
                                document.getElementById("f_shipping_address").value = data['address'];
                                document.getElementById("f_shipping_city").value = data['city'];
                                document.getElementById("f_shipping_state").value = data['state'];
                                document.getElementById("f_shipping_country").value = data['country'];
                                document.getElementById("f_shipping_zip").value = data['zipcode'];
                                shipping_modal.show_modal();
                            }
                        }
                    }
                }
            }
        });
    };

    let billing_form = document.getElementById("billing_addedit_form");
    let billing_validator = new FormValidate(billing_form);

    let billing_lock = false;
    document.getElementById("billing_addedit_btn").onclick = (evt) => {
        if (!billing_lock) {
            billing_lock = true;
            billing_validator.validate(
                (params) => {
                    billing_form.submit();
                    billing_lock = false;
                },
                (params) => {
                    billing_lock = false;
                },
                {});
        }
    }

    let shipping_form = document.getElementById("shipping_addedit_form");
    let shipping_validator = new FormValidate(shipping_form);

    let shipping_lock = false;
    document.getElementById("shipping_addedit_btn").onclick = (evt) => {
        if (!shipping_lock) {
            shipping_lock = true;
            shipping_validator.validate(
                (params) => {
                    shipping_form.submit();
                    shipping_lock = false;
                },
                (params) => {
                    shipping_lock = false;
                },
                {});
        }
    }


    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    /**
     * Contact Datatable
     */
    let contact_datatable_elem = document.querySelector("#customer_contact_datatable");
    let contact_rows_per_page = new SelectBox(contact_datatable_elem.querySelector(".tableFooter .selectBox"));
    contact_rows_per_page.init();
    closer.register_shutdown(contact_rows_per_page.shutdown, contact_rows_per_page.get_container());
    let contact_bulkaction = new SelectBox(contact_datatable_elem.querySelector(".tableHeader .bulkaction"));
    contact_bulkaction.init();
    closer.register_shutdown(contact_bulkaction.shutdown, contact_bulkaction.get_container());

    let contact_config = JSON.parse('<?php echo $contact_datatable_config; ?>');
    let contact_datatable = new DataTable(contact_datatable_elem, contact_config);
    contact_datatable.init();
    contact_rows_per_page.add_listener(contact_datatable.rows_per_page, {});
    contact_datatable.add_listener({ cb: load_active_toggler_1, params: {} });

    /**
     * Billing Address Datatable
     */
    let billing_datatable_elem = document.querySelector("#customer_billing_datatable");
    let billing_rows_per_page = new SelectBox(billing_datatable_elem.querySelector(".tableFooter .selectBox"));
    billing_rows_per_page.init();
    closer.register_shutdown(billing_rows_per_page.shutdown, billing_rows_per_page.get_container());
    let billing_bulkaction = new SelectBox(billing_datatable_elem.querySelector(".tableHeader .bulkaction"));
    billing_bulkaction.init();
    closer.register_shutdown(billing_bulkaction.shutdown, billing_bulkaction.get_container());
    let billing_config = JSON.parse('<?php echo $billing_datatable_config; ?>');
    let billing_datatable = new DataTable(billing_datatable_elem, billing_config);
    billing_datatable.init();
    billing_rows_per_page.add_listener(billing_datatable.rows_per_page, {});

    /**
     * Shipping Address Datatable
     */
    let shipping_datatable_elem = document.querySelector("#customer_shipping_datatable");
    let shipping_rows_per_page = new SelectBox(shipping_datatable_elem.querySelector(".tableFooter .selectBox"));
    shipping_rows_per_page.init();
    closer.register_shutdown(shipping_rows_per_page.shutdown, shipping_rows_per_page.get_container());
    let shipping_bulkaction = new SelectBox(shipping_datatable_elem.querySelector(".tableHeader .bulkaction"));
    shipping_bulkaction.init();
    closer.register_shutdown(shipping_bulkaction.shutdown, shipping_bulkaction.get_container());
    let shipping_config = JSON.parse('<?php echo $shipping_datatable_config; ?>');
    let shipping_datatable = new DataTable(shipping_datatable_elem, shipping_config);
    shipping_datatable.init();
    shipping_rows_per_page.add_listener(shipping_datatable.rows_per_page, {});

    /**
     * Notify Datatable
     */
    let notify_datatable_elem = document.querySelector("#notify_datatable");
    let notify_rows_per_page = new SelectBox(notify_datatable_elem.querySelector(".tableFooter .selectBox"));
    notify_rows_per_page.init();
    closer.register_shutdown(notify_rows_per_page.shutdown, notify_rows_per_page.get_container());
    let notify_bulkaction = new SelectBox(notify_datatable_elem.querySelector(".tableHeader .bulkaction"));
    notify_bulkaction.init();
    closer.register_shutdown(notify_bulkaction.shutdown, notify_bulkaction.get_container());
    let notify_config = JSON.parse('<?php echo $notify_datatable_config; ?>');
    let notify_datatable = new DataTable(notify_datatable_elem, notify_config);
    notify_datatable.init();
    notify_rows_per_page.add_listener(notify_datatable.rows_per_page, {});

    //random password generator 12 length
    let rand_button = document.getElementById("random_password");
    let password = document.getElementById("rand_password");
    rand_button.onclick = (e) => {

        password.value = "";
        const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        let pass = '';
        for (let i = 0; i < 12; i++) {
            let index = Math.floor(Math.random() * characters.length);
            pass += characters[index];
        }

        if (pass.length > 0) {
            password.value = pass;
        }
        console.log(pass);
    };

    let passwordtoggle = document.getElementById("passwordtoggle");
    passwordtoggle.onclick = () => {
        let type = password.type == "password" ? "text" : "password";

        let icon = "";
        if (password.type == "password") {
            // console.log("pass")
            icon = "<i class='fa-solid fa-eye-slash fa-sm' style='color: #ffffff;'></i>";
        } else {
            // console.log("text")
            icon = "<i class='fa-solid fa-eye fa-sm' style='color: #ffffff;' ></i>";
        }

        password.type = type;
        passwordtoggle.innerHTML = '';
        console.log(passwordtoggle.innerHTML);
        passwordtoggle.innerHTML = icon;

    }

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