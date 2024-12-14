<div class="alldiv flex widget_title">
    <h3>View Order</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.sale.orders') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="order_info">Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="order_attachment">Attachments</a></li>
        <li><a type="button" class="tab_nav_item" data-src="order_notify">Notify</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="order_info">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <?php
                        if ($order->status==0) {
                            ?>
                    <button type="button" class="btn bg-primary modalBtn" id="createinvoice_invoker1" >Create Invoice</button>
                    <?php
                        }
                    ?>
                    <a class="btn outline-primary">View PDF</a>
                    <a class="btn bg-success">Send Email</a>
                    <a class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1">
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
                                    <th>Discount</th>
                                    <td><?php echo $order->discount; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <td><?php echo $order->total_amount; ?></td>
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
                                <th>Property</th>
                                <th>Unit</th>
                                <th>Amount</th>
                            </thead>
                            <tbody>
                            <?php
                                $inc=1;
                                foreach ($order_items as $row) {
                                    ?>
                                <tr>
                                    <td><?php echo $inc; ?></td>
                                    <td><?php echo $row['property']; ?></td>
                                    <td><?php echo $row['unit_name']; ?></td>
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
                    <div class="file-uploader-frame" data-ajax-url="<?= url_to('erp.sale.order.upload.attachments').'?id='.$order_id.'&' ;?>" >
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
                        <table class="table" >
                            <tbody class="attachment-holder" data-ajaxdel-url="<?= url_to('erp.sale.order.delete.attachments').'?' ;?>" >
                                <?php
                                    foreach ($attachments as $attach) {
                                        ?>
                                <tr>
                                    <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('sale_order').$attach['filename'] ;?>"><?php echo $attach['filename'] ;?></a></td>
                                    <td><button class="btn bg-danger del-attachment-btn" type="button" data-attach-id="<?php echo $attach['attach_id'];?>" ><i class="fa fa-trash"></i></button></td>
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
                    <button class="btn bg-primary modalBtn" id="notify_modal_invoker1" type="button" ><i class="fa fa-plus" ></i>Add Notify</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable" data-ajax-url="<?=url_to('sale.order.view.ajax.datatable').'?orderid='.$order_id.'&' ;?>" >

                            <div class="tableHeader flex">
                                <div class="formWidth">
                                    <div class="selectBox poR bulkaction" data-ajax-url="" >
                                        <div class="selectBoxBtn flex"> 
                                            <div class="textFlow" data-default="Action">Action</div>
                                            <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                            <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                            <input type="hidden" class="selectBox_Value" value="" >
                                        </div>
                                            <ul role="listbox" class="selectBox_Container alldiv">
                                            <li role="option" data-value="1" >Delete</li>
                                            <li role="option" data-value="2" >Send</li>
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
                                            <li><a data-default-href="<?=url_to('sale.order.notify.export').'?export=excel&orderid='.$order_id.'&';?>" href="<?=url_to('sale.order.notify.export').'?export=excel&orderid='.$order_id.'&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/xls.png' ;?>" alt="excel" >EXCEL</a></li>
                                            <li><a data-default-href="<?=url_to('sale.order.notify.export').'?export=pdf&orderid='.$order_id.'&';?>" href="<?=url_to('sale.order.notify.export').'?export=pdf&orderid='.$order_id.'&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/pdf.png' ;?>" alt="pdf" >PDF</a></li>
                                            <li><a data-default-href="<?=url_to('sale.order.notify.export').'?export=csv&orderid='.$order_id.'&';?>" href="<?=url_to('sale.order.notify.export').'?export=csv&orderid='.$order_id.'&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/csv.png' ;?>" alt="csv" >CSV</a></li>
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
                                            <li role="option" class="active" data-value="10" >10</li>
                                            <li role="option" data-value="15" >15</li>
                                            <li role="option" data-value="20">20</li>
                                            <li role="option" data-value="25">25</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tableFooterRight flex">
                                    <div class="pagination"><span class="dt-page-start" >1</span> - <span class="dt-page-end">5</span> of <span class="dt-total-rows" >100<span></div>
                                        <ul class="flex paginationBtns">
                                            <li><a type="button" class="HoverA dt-prev-btn" ><i class="fa fa-angle-left"></i></a></li>
                                            <li><a type="button" class="HoverA dt-next-btn" ><i class="fa fa-angle-right"></i></a></li>
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
            <?php
                echo form_open(url_to('sale.order.notify.add'),$order_id,array(
                    "id"=>"notify_addedit_form",
                    "class"=>"flex modal-scroll-form"
                ));
            ?>
                <input type="hidden" name="notify_id" id="f_notify_id" value="0" />
                <div class="form-width-1">
                    <div class="form-group field-required ">
                        <label class="form-label">Title</label>
                        <input type="text" class="form_control field-check" id="f_notify_title" name="notify_title" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required">
                        <label class="form-label">Description</label>
                        <textarea class="form_control field-check" id="f_notify_desc" name="notify_desc" ></textarea>
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required" >
                        <label class="form-label">Notify To</label>
                        <div class="ajaxselectBox poR" data-ajax-url="<?= url_to('erp.crm.ajaxFetchUsers'); ?>"  >
                            <div class="ajaxselectBoxBtn flex"> 
                                <div class="textFlow" data-default="Notify To">Notify To</div>
                                <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                                <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" class="ajaxselectBox_Value field-check" id="f_notify_to" name="notify_to" value="" >
                            </div>
                            <div class="ajaxselectBox_Container alldiv">
                                <input type="text" class="ajaxselectBox_Search form_control" />
                                <ul role="listbox" >

                                </ul>
                            </div>
                        </div>
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required">
                        <label class="form-label">Notify at</label>
                        <input type="datetime-local" class="form_control field-check" id="f_notify_at" name="notify_at" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group">
                        <label class="form-label"></label>
                        <label class="form-check-label"><input id="f_notify_email" type="checkbox" name="notify_email" value="1" /> Notify via Email too </label>
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1 ">
                    <div class="form-group textRight ">
                        <button type="button" class="btn outline-danger modalClose">Close</button>
                        <button class="btn bg-primary" type="button" id="notify_addedit_btn" >Save</button>
                    </div>
                </div>
           <?php
           form_close();
           ?>
        </div>
    </div>


    <div class="modal" id="create_invoice_modal" role="dialog">
        <div class="modalbody">
            <h2 class="modalTitle">Create Invoice</h2>
            <?php
                echo form_open(base_url().'erp/sale/createinvoice/'.$order_id,array(
                    "id"=>"create_invoice_form",
                    "class"=>"flex modal-scroll-form"
                ));
            ?>
                <div class="form-width-1">
                    <div class="form-group field-ajax" data-ajax-url="<?= url_to('sale.invoice.add.fetchcode').'?'; ?>" >
                        <label class="form-label">Code</label>
                        <input type="text" class="form_control field-check" name="code" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required">
                        <label class="form-label">Invoice Date</label>
                        <input type="date" class="form_control field-check" name="invoice_date" />
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required">
                        <label class="form-label">Invoice Expiry</label>
                        <input type="date" class="form_control field-check" name="invoice_expiry" />
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-1 ">
                    <div class="form-group textRight ">
                        <button type="button" class="btn outline-danger modalClose">Close</button>
                        <button class="btn bg-primary" type="button" id="create_invoice_btn" >Create</button>
                    </div>
                </div>
            <?php
            form_close();
            ?>
        </div>
    </div>

<!-- MODAL ENDS -->




<!--SCRIPT WORKS -->
</div>
    </main>
    <script src="<?php echo base_url().'assets/js/jquery.min.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/script.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/erp.js' ;?>" ></script>
    <script type="text/javascript">
        let closer=new WindowCloser();
        closer.init();
        let tbody=document.querySelector(".attachment-holder");
        let fileuploader=new FileUploader(document.querySelector(".file-uploader-frame"));
        let alert=new ModalAlert();

        fileuploader.ajaxFullUpload({
            files_allowed:["text/plain","image/png","application/pdf","image/jpeg","image/gif","image/jpg","application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/vnd.ms-powerpoint","application/vnd.openxmlformats-officedocument.presentationml.presentation","application/vnd.ms-excel","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","image/webp","image/svg+xml"],
            listener:function(json){
                let tr=document.createElement("tr");
                let td1=document.createElement("td");
                let td2=document.createElement("td");
                td1.innerHTML=`<a target="_BLANK" download class="text-primary" href="`+json['filelink']+`">`+json['filename']+`</a>`;
                td2.innerHTML=`<button class="btn bg-danger del-attachment-btn" type="button" data-attach-id="`+json['insert_id']+`" ><i class="fa fa-trash"></i></button>`;
                tr.append(td1);
                tr.append(td2);
                tbody.append(tr);
            }
        });

        tbody.onclick=(evt)=>{
            let target=evt.target;
            let ajax_url=tbody.getAttribute("data-ajaxdel-url");
            tbody.querySelectorAll(".del-attachment-btn").forEach((item)=>{
                if(item.contains(target)){
                    let xhr=null;
                    if(window.ActiveXObject){
                        xhr=new ActiveXObject("Msxml2.XMLHTTP");
                    }else if(window.XMLHttpRequest){
                        xhr=new XMLHttpRequest();
                    }
                    if(xhr!==null || xhr!==undefined){
                        xhr.open("GET",ajax_url+"id="+item.getAttribute("data-attach-id"),true);
                        xhr.send(null);
                        xhr.onreadystatechange=(evt)=>{
                            if(xhr.readyState==4 && xhr.status==200){
                                let json=JSON.parse(xhr.responseText);
                                if(json['error']==0){
                                    alert.invoke_alert(json['reason'],"success");
                                    item.parentElement.parentElement.remove();
                                }else{
                                    alert.invoke_alert(json['reason'],"error");
                                }
                            }
                        }
                    }
                }
            })
        };

        let modal_box=new ModalBox(document.getElementById("notify_addedit_modal"));
        modal_box.init();

        let notify_ajax_select=[];
        document.querySelectorAll("#notify_addedit_modal .ajaxselectBox").forEach((item)=>{
            let ajaxselectbox=new AjaxSelectBox(item);
            ajaxselectbox.init();
            notify_ajax_select.push(ajaxselectbox);
            closer.register_shutdown(ajaxselectbox.shutdown,ajaxselectbox.get_container());
        });

        document.getElementById("notify_modal_invoker1").onclick=(evt)=>{
            document.getElementById("f_notify_id").value="0";
            document.getElementById("f_notify_title").value="";
            document.getElementById("f_notify_desc").value="";
            document.getElementById("f_notify_at").value="";
            document.getElementById("f_notify_email").checked=false;
            document.getElementById("f_notify_to").value="";
            for(let i=0;i<notify_ajax_select.length;i++){
                notify_ajax_select[i].construct();
            }
            modal_box.show_modal();
        };

        let paintarea=document.querySelector(".table-paint-area");
        paintarea.onclick=(evt)=>{
            let target=evt.target;
            paintarea.querySelectorAll(".modalBtn").forEach((item)=>{
                if(item.contains(target)){
                    let ajax_url=item.getAttribute("data-ajax-url");
                    let xhr=null;
                    if(window.XMLHttpRequest){
                        xhr=new XMLHttpRequest();
                    }else if(window.ActiveXObject){
                        xhr=new ActiveXObject("Msxml2.XMLHTTP");
                    }
                    if(xhr!==null && xhr!==undefined){
                        xhr.open("GET",ajax_url,true);
                        xhr.send(null);
                        xhr.onreadystatechange=(evt)=>{
                            if(xhr.readyState==4 && xhr.status==200){
                                let json=JSON.parse(xhr.responseText);
                                if(json['error']==0){
                                    let data=json['data'];
                                    document.getElementById("f_notify_id").value=data['notify_id'];
                                    document.getElementById("f_notify_title").value=data['title'];
                                    document.getElementById("f_notify_desc").value=data['notify_text'];
                                    document.getElementById("f_notify_at").value=data['notify_at'];
                                    if(data['notify_email']==1){
                                        document.getElementById("f_notify_email").checked=true;
                                    }
                                    document.getElementById("f_notify_to").previousElementSibling.previousElementSibling.previousElementSibling.textContent=data['name'];
                                    document.getElementById("f_notify_to").value=data['user_id'];
                                    for(let i=0;i<notify_ajax_select.length;i++){
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

        let form=document.getElementById("notify_addedit_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("notify_addedit_btn").onclick=(evt)=>{
            if(!lock){
                lock=true;
                validator.validate(
                (params)=>{
                    form.submit();
                    lock=false;
                },
                (params)=>{
                    lock=false;
                },
                {});
            }
        }

        let datatable_elem=document.querySelector(".datatable");

        let rows_per_page=new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
        rows_per_page.init();
        closer.register_shutdown(rows_per_page.shutdown,rows_per_page.get_container());

        let bulkaction=new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
        bulkaction.init();
        closer.register_shutdown(bulkaction.shutdown,bulkaction.get_container());

        let config=JSON.parse('<?php echo $notify_datatable_config; ?>');
        let datatable=new DataTable(datatable_elem,config);
        datatable.init();

        rows_per_page.add_listener(datatable.rows_per_page,{});


        <?php
            if ($order->status==0) {
                ?>
        let create_invoice_modal=new ModalBox(document.getElementById("create_invoice_modal"));
        create_invoice_modal.init();
        document.getElementById("createinvoice_invoker1").onclick=(evt)=>{
            create_invoice_modal.show_modal();
        };

        let create_invoice_form=document.getElementById("create_invoice_form");
        let create_invoice_validator=new FormValidate(create_invoice_form);

        let create_invoice_lock=false;
        document.getElementById("create_invoice_btn").onclick=(evt)=>{
            if(!create_invoice_lock){
                create_invoice_lock=true;
                create_invoice_validator.validate(
                (params)=>{
                    create_invoice_form.submit();
                    create_invoice_lock=false;
                },
                (params)=>{
                    create_invoice_lock=false;
                },
                {});
            }
        }
        <?php
            }
        ?>

        <?php
            if($this->session->flashdata("op_success")){ ?>
                alert.invoke_alert("<?php echo $this->session->flashdata('op_success'); ?>","success");
        <?php
            }else if($this->session->flashdata("op_error")){ ?>
                alert.invoke_alert("<?php echo $this->session->flashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>