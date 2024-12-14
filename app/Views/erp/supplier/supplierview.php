<div class="alldiv flex widget_title">
    <h3>View Supplier</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.supplier.page') ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="supplier_profile">Profile</a></li>
        <li><a type="button" class="tab_nav_item" data-src="supplier_contacts">Contacts</a></li>
        <li><a type="button" class="tab_nav_item" data-src="supplier_locations">Locations</a></li>
        <li><a type="button" class="tab_nav_item" data-src="supplier_attachment">Attachments</a></li>
        <li><a type="button" class="tab_nav_item" data-src="supplier_supplylist">Supply List</a></li>
        <li><a type="button" class="tab_nav_item" data-src="supplier_segments">Segments</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="supplier_profile">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <a href="<?= url_to('erp.supplier.delete',$supplier_id)?>" class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1">
                    <h2>Supplier Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $supplier->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Code</th>
                                    <td><?php echo $supplier->code; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $supplier->email; ?></td>
                                </tr>
                                <tr>
                                    <th>Company</th>
                                    <td><?php echo $supplier->company; ?></td>
                                </tr>
                                <tr>
                                    <th>GST</th>
                                    <td><?php echo $supplier->gst; ?></td>
                                </tr>
                                <tr>
                                    <th>Position</th>
                                    <td><?php echo $supplier->position; ?></td>
                                </tr>
                                <tr>
                                    <th>Website</th>
                                    <td><?php echo $supplier->website; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><?php echo $supplier->phone; ?></td>
                                </tr>
                                <tr>
                                    <th>Fax Number</th>
                                    <td><?php echo $supplier->fax_number; ?></td>
                                </tr>
                                <tr>
                                    <th>Office Number</th>
                                    <td><?php echo $supplier->office_number; ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>
                                        <p><?php echo $supplier->address; ?> ,</p>
                                        <p> <?php echo $supplier->state; ?>,</p>
                                        <p> <?php echo $supplier->state; ?>,</p>
                                        <p> <?php echo $supplier->country; ?>-<?php echo $supplier->zipcode; ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Source</th>
                                    <td><?php echo $supplier->source_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Groups</th>
                                    <td><?php echo $supplier->groups; ?></td>
                                </tr>
                                <tr>
                                    <th>Active</th>
                                    <td><?php
                                        if ($supplier->active == 1) { ?>
                                            <span class="st st_success">Yes</span>
                                        <?php
                                        } else { ?>
                                            <span class="st st_dark">No</span>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                /******CUSTOM FIELDS******/
                                echo $custom_field_values;
                                ?>
                                <tr>
                                    <th>Payment Terms</th>
                                    <td><?php echo $supplier->payment_terms; ?></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $supplier->description; ?></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="supplier_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame" data-ajax-url="<?php echo base_url() . 'erp/supplier/attachment?id=' . $supplier_id . '&'; ?>">
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
                            <tbody class="attachment-holder" data-ajaxdel-url="<?php echo base_url() . 'erp/supplier/attachment-delete?'; ?>">
                                <?php
                                foreach ($attachments as $attach) {
                                ?>
                                    <tr>
                                        <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('supplier') . $attach['filename']; ?>"><?php echo $attach['filename']; ?></a></td>
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
        <div class="tab_pane" id="supplier_contacts">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <button class="btn bg-primary modalBtn" id="contact_modal_invoker1" type="button"><i class="fa fa-plus"></i>Add Contact</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="supplier_contact_datatable" data-ajax-url="<?php echo base_url() . 'erp/supplier/contact?supplierid=' . $supplier_id . '&'; ?>">
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
                                        <li><a data-default-href="<?=url_to('erp.supplier.contactexport').'?export=excel&supplierid=' . $supplier_id . '&'; ?>" href="<?= url_to('erp.supplier.contactexport').'?export=excel&supplierid=' . $supplier_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?=url_to('erp.supplier.contactexport').'?export=pdf&supplierid=' . $supplier_id . '&'; ?>" href="<?=url_to('erp.supplier.contactexport').'?export=pdf&supplierid=' . $supplier_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?=url_to('erp.supplier.contactexport').'?export=csv&supplierid=' . $supplier_id . '&'; ?>" href="<?=url_to('erp.supplier.contactexport').'?export=csv&supplierid=' . $supplier_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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
<div class="tab_pane" id="supplier_locations">
    <div class="flex">
        <div class="form-width-1 textRight">
            <button class="btn bg-primary modalBtn" id="location_modal_invoker1" type="button"><i class="fa fa-plus"></i>Add Location</button>
        </div>

        <div class="form-width-1">
            <div class="datatable" id="supplier_location_datatable" data-ajax-url="<?php echo base_url() . 'erp/supplier/location-response?supplierid=' . $supplier_id . '&'; ?>">
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
                                <li><a data-default-href="<?php echo base_url() . 'erp/supplier/location-export?export=excel&supplierid=' . $supplier_id . '&'; ?>" href="<?php echo base_url() . 'erp/supplier/location-export?export=excel&supplierid=' . $supplier_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                <li><a data-default-href="<?php echo base_url() . 'erp/supplier/location-export?export=pdf&supplierid=' . $supplier_id . '&'; ?>" href="<?php echo base_url() . 'erp/supplier/location-export?export=pdf&supplierid=' . $supplier_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                <li><a data-default-href="<?php echo base_url() . 'erp/supplier/location-export?export=csv&supplierid=' . $supplier_id . '&'; ?>" href="<?php echo base_url() . 'erp/supplier/location-export?export=csv&supplierid=' . $supplier_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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
<div class="tab_pane" id="supplier_supplylist">
    <div class="flex">
        <div class="form-width-1 textRight">
            <button class="btn bg-primary modalBtn" id="supplylist_modal_invoker1" type="button"><i class="fa fa-plus"></i>Add Supply List</button>
        </div>
        <div class="form-width-1">
            <div class="datatable" id="supplylist_datatable" data-ajax-url="<?php echo base_url() . 'erp/supplier/list-response?supplierid=' . $supplier_id . '&'; ?>">

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
                                <li><a data-default-href="<?php echo base_url() . 'erp/supplier/list-export?export=excel&supplierid=' . $supplier_id . '&'; ?>" href="<?php echo base_url() . 'erp/supplier/list-export?export=excel&supplierid=' . $supplier_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                <li><a data-default-href="<?php echo base_url() . 'erp/supplier/list-export?export=pdf&supplierid=' . $supplier_id . '&'; ?>" href="<?php echo base_url() . 'erp/supplier/list-export?export=pdf&supplierid=' . $supplier_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                <li><a data-default-href="<?php echo base_url() . 'erp/supplier/list-export?export=csv&supplierid=' . $supplier_id . '&'; ?>" href="<?php echo base_url() . 'erp/supplier/list-export?export=csv&supplierid=' . $supplier_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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
<div class="tab_pane" id="supplier_segments">
    <div class="flex">
        <div class="form-width-1">
            <?php echo $segment_html; ?>
        </div>
    </div>
</div>
<div class="tab_pane" id="supplier_notify">
    <div class="flex">
        <div class="form-width-1 textRight">
            <button class="btn bg-primary modalBtn" id="notify_modal_invoker1" type="button"><i class="fa fa-plus"></i>Add Notify</button>
        </div>
        <div class="form-width-1">
            <div class="datatable" id="notify_datatable" data-ajax-url="<?php echo base_url() . 'erp/supplier/ajax_suppliernotify_response?supplierid=' . $supplier_id . '&'; ?>">

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
                                <li><a data-default-href="<?php echo base_url() . 'erp/supplier/supplier_notify_export?export=excel&supplierid=' . $supplier_id . '&'; ?>" href="<?php echo base_url() . 'erp/supplier/supplier_notify_export?export=excel&supplierid=' . $supplier_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                <li><a data-default-href="<?php echo base_url() . 'erp/supplier/supplier_notify_export?export=pdf&supplierid=' . $supplier_id . '&'; ?>" href="<?php echo base_url() . 'erp/suuplier/supplier_notify_export?export=pdf&supplierid=' . $supplier_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                <li><a data-default-href="<?php echo base_url() . 'erp/supplier/supplier_notify_export?export=csv&supplierid=' . $supplier_id . '&'; ?>" href="<?php echo base_url() . 'erp/supplier/supplier_notify_export?export=csv&supplierid=' . $supplier_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
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
</div>



<!--MODALS-->
<!--NOTIFY MODAL -->
<div class="modal" id="notify_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Notify</h2>
        <?php
        echo form_open(base_url() . 'erp/supplier/suppliernotify/' . $supplier_id, array(
            "id" => "notify_addedit_form",
            "class" => "flex"
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
                <div class="ajaxselectBox poR" data-ajax-url="<?php echo base_url() . 'erp/crm/ajaxfetchusers'; ?>" id="f_notify_to">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="Notify To">Notify To</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="ajaxselectBox_Value field-check" name="notify_to" value="">
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
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label"></label>
                <label class="form-check-label"><input id="f_notify_creater" type="checkbox" name="notify_creater" value="1" /> Notify creater too </label>
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


<!-- CONTACT MODAL -->
<div class="modal" id="contact_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Contact</h2>
        <?php
        echo form_open(base_url().'erp/supplier/supplier-contact/'. $supplier_id, array(
            "id" => "contact_addedit_form",
            "class" => "flex"
        ));
        ?>
        <input type="hidden" name="contact_id" id="f_contact_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">First name</label>
                <input type="text" class="form_control field-check" id="f_contact_firstname" name="firstname" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-label">Last name</label>
                <input type="text" class="form_control field-check" id="f_contact_lastname" name="lastname" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Position</label>
                <input type="text" class="form_control field-check" id="f_contact_position" name="position" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-ajax " data-ajax-url="<?php echo base_url() . 'erp/supplier/contact-mail?supplierid=' . $supplier_id . '&contact_id=0&'; ?>">
                <label class="form-label">Email</label>
                <input type="text" class="form_control field-check" id="f_contact_email" name="email" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-phone ">
                <label class="form-label">Phone</label>
                <input type="text" class="form_control field-check" id="f_contact_phone" name="phone" />
                <p class="error-text"></p>
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


<!-- SHIPPING MODAL -->
<div class="modal" id="location_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Location</h2>
        <?php
        echo form_open(base_url() . 'erp/supplier/supplier-location/'. $supplier_id, array(
            "id" => "location_addedit_form",
            "class" => "flex"
        ));
        ?>
        <input type="hidden" name="location_id" id="f_location_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Address</label>
                <input type="text" class="form_control field-check" id="f_location_address" name="address" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">City</label>
                <input type="text" class="form_control field-check" id="f_location_city" name="city" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">State</label>
                <input type="text" class="form_control field-check" id="f_location_state" name="state" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Country</label>
                <input type="text" class="form_control field-check" id="f_location_country" name="country" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Zipcode</label>
                <input type="text" class="form_control field-check" id="f_location_zip" name="zip" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="location_addedit_btn">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>


<!-- SUPPLY LIST MODAL -->
<div class="modal" id="supplylist_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Supply List</h2>
        <?php
        echo form_open(base_url() . 'erp/supplier/supply-list/' . $supplier_id, array(
            "id" => "supplylist_addedit_form",
            "class" => "flex"
        ));
        ?>
        <input type="hidden" name="supply_list_id" id="f_supply_list_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Product Type</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select type">select type</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" id="f_product_type" class="selectBox_Value field-check" name="related_to" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        $first_product = "";
                        foreach ($product_types as $key => $status) {
                            if (empty($first_product)) {
                                $first_product = $key;
                            }
                        ?>
                            <li role="option" data-value="<?php echo $key; ?>"><?php echo $status; ?></li>
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
                <label class="form-label">Product</label>
                <div class="ajaxselectBox poR" data-ajax-url="<?php echo base_url() . $product_links[$first_product]; ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="select product">select product</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" id="f_product" class="ajaxselectBox_Value field-check" name="related_id" value="">
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
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="supplylist_addedit_btn">Save</button>
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
    let product_links = JSON.parse('<?php echo json_encode($product_links); ?>');
    let base_url = "<?php echo base_url(); ?>";

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

    // let notify_modal=new ModalBox(document.getElementById("notify_addedit_modal"));
    // notify_modal.init();

    // document.getElementById("notify_modal_invoker1").onclick=(evt)=>{
    //     document.getElementById("f_notify_id").value="0";
    //     document.getElementById("f_notify_title").value="";
    //     document.getElementById("f_notify_desc").value="";
    //     document.getElementById("f_notify_at").value="";
    //     document.getElementById("f_notify_email").checked=false;
    //     document.getElementById("f_notify_creater").checked=false;
    //     document.querySelector("#f_notify_to .textFlow").textContent=document.querySelector("#f_notify_to .textFlow").getAttribute("data-default");
    //     document.querySelector("#f_notify_to .ajaxselectBox_Value").value="";
    //     notify_modal.show_modal();
    // };

    // let notify_paintarea=document.querySelector("#notify_datatable .table-paint-area");
    // notify_paintarea.onclick=(evt)=>{
    //     let target=evt.target;
    //     notify_paintarea.querySelectorAll(".modalBtn").forEach((item)=>{
    //         if(item.contains(target)){
    //             let ajax_url=item.getAttribute("data-ajax-url");
    //             let xhr=null;
    //             if(window.XMLHttpRequest){
    //                 xhr=new XMLHttpRequest();
    //             }else if(window.ActiveXObject){
    //                 xhr=new ActiveXObject("Msxml2.XMLHTTP");
    //             }
    //             if(xhr!==null && xhr!==undefined){
    //                 xhr.open("GET",ajax_url,true);
    //                 xhr.send(null);
    //                 xhr.onreadystatechange=(evt)=>{
    //                     if(xhr.readyState==4 && xhr.status==200){
    //                         let json=JSON.parse(xhr.responseText);
    //                         if(json['error']==0){
    //                             let data=json['data'];
    //                             document.getElementById("f_notify_id").value=data['notify_id'];
    //                             document.getElementById("f_notify_title").value=data['title'];
    //                             document.getElementById("f_notify_desc").value=data['notify_text'];
    //                             document.getElementById("f_notify_at").value=data['notify_at'];
    //                             document.querySelector("#f_notify_to .textFlow").textContent=data['name'];
    //                             document.querySelector("#f_notify_to .ajaxselectBox_Value").value=data['user_id'];
    //                             ajaxselectbox.construct();
    //                             if(data['notify_email']==1){
    //                                 document.getElementById("f_notify_email").checked=true;
    //                             }
    //                             if(data['notify_creater']==1){
    //                                 document.getElementById("f_notify_creater").checked=true;
    //                             }
    //                             notify_modal.show_modal();
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // };

    // let notify_form=document.getElementById("notify_addedit_form");
    // let notify_validator=new FormValidate(notify_form);

    // let notify_lock=false;
    // document.getElementById("notify_addedit_btn").onclick=(evt)=>{
    //     if(!notify_lock){
    //         notify_lock=true;
    //         notify_validator.validate(
    //         (params)=>{
    //             notify_form.submit();
    //             notify_lock=false;
    //         },
    //         (params)=>{
    //             notify_lock=false;
    //         },
    //         {});
    //     }
    // }

    let contact_modal = new ModalBox(document.getElementById("contact_addedit_modal"));
    contact_modal.init();

    document.getElementById("contact_modal_invoker1").onclick = (evt) => {
        document.getElementById("f_contact_id").value = "0";
        document.getElementById("f_contact_firstname").value = "";
        document.getElementById("f_contact_lastname").value = "";
        document.getElementById("f_contact_position").value = "";
        document.getElementById("f_contact_email").value = "";
        document.getElementById("f_contact_phone").value = "";
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

    let contact_paintarea = document.querySelector("#supplier_contact_datatable .table-paint-area");
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
                            if (json['error'] == 0) {
                                let data = json['data'];
                                document.getElementById("f_contact_id").value = data['contact_id'];
                                document.getElementById("f_contact_firstname").value = data['firstname'];
                                document.getElementById("f_contact_lastname").value = data['lastname'];
                                document.getElementById("f_contact_position").value = data['position'];
                                document.getElementById("f_contact_email").value = data['email'];
                                document.getElementById("f_contact_phone").value = data['phone'];
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

    let contact_lock = false;
    document.getElementById("contact_addedit_btn").onclick = (evt) => {
        if (!contact_lock) {
            contact_lock = true;
            contact_validator.validate(
                (params) => {
                    contact_form.submit();
                    contact_lock = false;
                },
                (params) => {
                    contact_lock = false;
                }, {});
        }
    }

    let location_modal = new ModalBox(document.getElementById("location_addedit_modal"));
    location_modal.init();

    document.getElementById("location_modal_invoker1").onclick = (evt) => {
        document.getElementById("f_location_id").value = "0";
        document.getElementById("f_location_address").value = "";
        document.getElementById("f_location_city").value = "";
        document.getElementById("f_location_state").value = "";
        document.getElementById("f_location_country").value = "";
        document.getElementById("f_location_zip").value = "";
        location_modal.show_modal();
    };

    let location_paintarea = document.querySelector("#supplier_location_datatable .table-paint-area");
    location_paintarea.onclick = (evt) => {
        let target = evt.target;
        location_paintarea.querySelectorAll(".modalBtn").forEach((item) => {
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
                                document.getElementById("f_location_id").value = data['location_id'];
                                document.getElementById("f_location_address").value = data['address'];
                                document.getElementById("f_location_city").value = data['city'];
                                document.getElementById("f_location_state").value = data['state'];
                                document.getElementById("f_location_country").value = data['country'];
                                document.getElementById("f_location_zip").value = data['zipcode'];
                                location_modal.show_modal();
                            }
                        }
                    }
                }
            }
        });
    };

    let location_form = document.getElementById("location_addedit_form");
    let location_validator = new FormValidate(location_form);

    let location_lock = false;
    document.getElementById("location_addedit_btn").onclick = (evt) => {
        if (!location_lock) {
            location_lock = true;
            location_validator.validate(
                (params) => {
                    location_form.submit();
                    location_lock = false;
                },
                (params) => {
                    location_lock = false;
                }, {});
        }
    }

    let ajaxselectbox_elem = document.querySelector("#supplylist_addedit_modal .ajaxselectBox");
    ajaxselectbox = new AjaxSelectBox(ajaxselectbox_elem);
    let default_ajax_url = ajaxselectbox_elem.getAttribute("data-ajax-url");
    ajaxselectbox.init();
    closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());

    let selectbox_elem = document.querySelector("#supplylist_addedit_modal .selectBox");
    let selectbox = new SelectBox(selectbox_elem);
    selectbox.init();
    selectbox.add_listener((params) => {
        let url = params['value'];
        if (url !== null && url !== undefined && url !== "") {
            ajaxselectbox_elem.setAttribute("data-ajax-url", base_url + product_links[url]);
        } else {
            ajaxselectbox_elem.setAttribute("data-ajax-url", default_ajax_url);
        }
    }, {});
    closer.register_shutdown(selectbox.shutdown, selectbox.get_container());


    let supplylist_modal = new ModalBox(document.getElementById("supplylist_addedit_modal"));
    supplylist_modal.init();

    document.getElementById("supplylist_modal_invoker1").onclick = (evt) => {
        document.getElementById("f_supply_list_id").value = "0";
        document.getElementById("f_product_type").value = "";
        document.getElementById("f_product").value = "";
        selectbox.construct();
        ajaxselectbox.construct();
        supplylist_modal.show_modal();
    };

    let supplylist_paintarea = document.querySelector("#supplylist_datatable .table-paint-area");
    supplylist_paintarea.onclick = (evt) => {
        let target = evt.target;
        supplylist_paintarea.querySelectorAll(".modalBtn").forEach((item) => {
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
                                document.getElementById("f_supply_list_id").value = data['supply_list_id'];
                                document.getElementById("f_product_type").value = data['related_to'];
                                document.getElementById("f_product").value = data['related_id'];
                                document.getElementById("f_product").previousElementSibling.previousElementSibling.previousElementSibling.textContent = data['product'];
                                selectbox.construct();
                                ajaxselectbox.construct();
                                supplylist_modal.show_modal();
                            }
                        }
                    }
                }
            }
        });
    };

    let supplylist_form = document.getElementById("supplylist_addedit_form");
    let supplylist_validator = new FormValidate(supplylist_form);

    let supplylist_lock = false;
    document.getElementById("supplylist_addedit_btn").onclick = (evt) => {
        if (!supplylist_lock) {
            supplylist_lock = true;
            supplylist_validator.validate(
                (params) => {
                    supplylist_form.submit();
                    supplylist_lock = false;
                },
                (params) => {
                    supplylist_lock = false;
                }, {});
        }
    }




    /**
     * Contact Datatable
     */
    let contact_datatable_elem = document.querySelector("#supplier_contact_datatable");
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
    contact_datatable.add_listener({
        cb: load_active_toggler_1,
        params: {}
    });

    /**
     * Location Datatable
     */
    let location_datatable_elem = document.querySelector("#supplier_location_datatable");
    let location_rows_per_page = new SelectBox(location_datatable_elem.querySelector(".tableFooter .selectBox"));
    location_rows_per_page.init();
    closer.register_shutdown(location_rows_per_page.shutdown, location_rows_per_page.get_container());
    let location_bulkaction = new SelectBox(location_datatable_elem.querySelector(".tableHeader .bulkaction"));
    location_bulkaction.init();
    closer.register_shutdown(location_bulkaction.shutdown, location_bulkaction.get_container());
    let location_config = JSON.parse('<?php echo $location_datatable_config; ?>');
    let location_datatable = new DataTable(location_datatable_elem, location_config);
    location_datatable.init();
    location_rows_per_page.add_listener(location_datatable.rows_per_page, {});

    /**
     * Supply List Datatable
     */
    let supplylist_datatable_elem = document.querySelector("#supplylist_datatable");
    let supplylist_rows_per_page = new SelectBox(supplylist_datatable_elem.querySelector(".tableFooter .selectBox"));
    supplylist_rows_per_page.init();
    closer.register_shutdown(supplylist_rows_per_page.shutdown, supplylist_rows_per_page.get_container());
    let supplylist_bulkaction = new SelectBox(supplylist_datatable_elem.querySelector(".tableHeader .bulkaction"));
    supplylist_bulkaction.init();
    closer.register_shutdown(supplylist_bulkaction.shutdown, supplylist_bulkaction.get_container());
    let supplylist_config = JSON.parse('<?php echo $supplylist_datatable_config; ?>');
    let supplylist_datatable = new DataTable(supplylist_datatable_elem, supplylist_config);
    supplylist_datatable.init();
    supplylist_rows_per_page.add_listener(supplylist_datatable.rows_per_page, {});

    // /**
    //  * Notify Datatable
    //  */
    // let notify_datatable_elem=document.querySelector("#notify_datatable");
    // let notify_rows_per_page=new SelectBox(notify_datatable_elem.querySelector(".tableFooter .selectBox"));
    // notify_rows_per_page.init();
    // closer.register_shutdown(notify_rows_per_page.shutdown,notify_rows_per_page.get_container());
    // let notify_bulkaction=new SelectBox(notify_datatable_elem.querySelector(".tableHeader .bulkaction"));
    // notify_bulkaction.init();
    // closer.register_shutdown(notify_bulkaction.shutdown,notify_bulkaction.get_container());
    // let notify_config=JSON.parse('<?php //echo $notify_datatable_config; 
                                        ?>');
    // let notify_datatable=new DataTable(notify_datatable_elem,notify_config);
    // notify_datatable.init();
    // notify_rows_per_page.add_listener(notify_datatable.rows_per_page,{});

    let supplier_segment_form = document.getElementById("supplier_segment_form");
    if (supplier_segment_form !== null && supplier_segment_form !== undefined) {
        supplier_segment_form.querySelectorAll(".selectBox").forEach((item) => {
            let select_box_local = new SelectBox(item);
            select_box_local.init();
            closer.register_shutdown(select_box_local.shutdown, select_box_local.get_container());
        });
        let segment_lock = false;
        let segment_validator = new FormValidate(supplier_segment_form);
        supplier_segment_form.querySelector("#supplier_segment_submit").onclick = (evt) => {
            if (!segment_lock) {
                segment_lock = true;
                segment_validator.validate(
                    (params) => {
                        supplier_segment_form.submit();
                        segment_lock = false;
                    },
                    (params) => {
                        segment_lock = false;
                    }, {});
            }
        }
    }

    <?php
    $session = \Config\Services::session();
    if ($session->setFlashdata("op_success")) { ?>
        alert.invoke_alert("<?php echo $session->setFlashdata('op_success'); ?>", "success");
    <?php
    } else if ($session->setFlashdata("op_error")) { ?>
        alert.invoke_alert("<?php echo $session->setFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>