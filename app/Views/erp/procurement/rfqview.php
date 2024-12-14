<div class="alldiv flex widget_title">
    <h3>View RFQ</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/procurement/rfq' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="rfq_info">Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="rfq_attachment">Attachments</a></li>
        <li><a type="button" class="tab_nav_item" data-src="rfq_supplier">Suppliers</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="rfq_info">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <a href="<?php echo base_url().'erp/procurement/rfqdelete/'.$rfq->rfq_id;?>" class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1">
                    <h2>RFQ Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Requisition Code</th>
                                    <td><?php echo $rfq->req_code; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="st <?php echo $status_bg[$rfq->status]; ?>"><?php echo $status[$rfq->status]; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Expiry Date</th>
                                    <td><?php echo $rfq->expiry_date; ?></td>
                                </tr>
                                <tr>
                                    <th>Terms and Condition</th>
                                    <td><?php echo $rfq->terms_condition; ?></td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td><?php echo $rfq->creater; ?></td>
                                </tr>
                                <tr>
                                    <th>Creater on</th>
                                    <td><?php echo date("Y-m-d",$rfq->created_at); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-width-1">
                    <h2>RFQ Products</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th>SNO</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                            </thead>
                            <tbody  >
                                <?php
                                    $rfq_item_counter=1;
                                    foreach ($rfq_items as $item) {
                                        ?>
                                    <tr>
                                        <td>
                                            <span><?php echo $rfq_item_counter; ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo $item['product']; ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo $item['qty']; ?></span>
                                        </td>
                                    </tr>
                                <?php $rfq_item_counter++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="rfq_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame" data-ajax-url="<?php echo base_url().'erp/procurement/upload_rfqattachment?id='.$rfq_id.'&' ;?>" >
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
                            <tbody class="attachment-holder" data-ajaxdel-url="<?php echo base_url().'erp/procurement/rfq_delete_attachment?' ;?>" >
                                <?php
                                    foreach ($attachments as $attach) {
                                        ?>
                                <tr>
                                    <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('rfq').$attach['filename'] ;?>"><?php echo $attach['filename'] ;?></a></td>
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
        <div class="tab_pane" id="rfq_supplier">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <a href="<?php echo base_url().'erp/procurement/rfqsendbatch/'.$rfq_id ;?>" class="btn bg-success" type="button" ><i class="fa fa-plus" ></i>Send RFQ</a>
                    <a href="<?php echo base_url().'erp/procurement/rfqsupplieradd/'.$rfq_id ;?>" class="btn bg-primary" type="button" ><i class="fa fa-plus" ></i>Add Supplier</a>
                </div>
                <div class="form-width-1">
                    <div class="datatable" data-ajax-url="<?php echo base_url().'erp/procurement/ajax_rfqsupplier_response?rfqid='.$rfq_id.'&' ;?>" >

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
                                            <li><a data-default-href="<?php echo base_url().'erp/procurement/rfqsupplier_export?export=excel&rfqid='.$rfq_id.'&';?>" href="<?php echo base_url().'erp/procurement/rfqsupplier_export?export=excel&rfqid='.$rfq_id.'&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/xls.png' ;?>" alt="excel" >EXCEL</a></li>
                                            <li><a data-default-href="<?php echo base_url().'erp/procurement/rfqsupplier_export?export=pdf&rfqid='.$rfq_id.'&';?>" href="<?php echo base_url().'erp/procurement/rfqsupplier_export?export=pdf&rfqid='.$rfq_id.'&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/pdf.png' ;?>" alt="pdf" >PDF</a></li>
                                            <li><a data-default-href="<?php echo base_url().'erp/procurement/rfqsupplier_export?export=csv&rfqid='.$rfq_id.'&';?>" href="<?php echo base_url().'erp/procurement/rfqsupplier_export?export=csv&rfqid='.$rfq_id.'&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/csv.png' ;?>" alt="csv" >CSV</a></li>
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





<!--SCRIPT WORKS -->
</div>
    </main>
    <script src="<?php echo base_url().'assets/js/jquery.min.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/script.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/erp.js' ;?>" ></script>
    <script type="text/javascript">
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

        let closer=new WindowCloser();
        closer.init();

        let datatable_elem=document.querySelector(".datatable");

        let rows_per_page=new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
        rows_per_page.init();
        closer.register_shutdown(rows_per_page.shutdown,rows_per_page.get_container());

        let bulkaction=new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
        bulkaction.init();
        closer.register_shutdown(bulkaction.shutdown,bulkaction.get_container());

        let config=JSON.parse('<?php echo $rfqsupplier_datatable_config; ?>');
        let datatable=new DataTable(datatable_elem,config);
        datatable.init();

        rows_per_page.add_listener(datatable.rows_per_page,{});

        <?php
            if(session()->getFlashdata("op_success")){ ?>
                alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getFlashdata("op_error")){ ?>
                alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>