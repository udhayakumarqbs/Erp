<div class="alldiv flex widget_title">
    <h3>View Lead</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.crm.leads') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="lead_profile">Profile</a></li>
        <li><a type="button" class="tab_nav_item" data-src="lead_attachment">Attachments</a></li>
        <li><a type="button" class="tab_nav_item" data-src="lead_notify">Notify</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="lead_profile">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <?php
                        if($lead->status !=4){
                    ?>
                        <a href="#" id="convert_customer_invoker" class="btn bg-primary modalBtn">Convert To Customer</a>
                    <?php
                        }
                    ?>
                    <a href="<?= url_to('erp.crm.leaddelete',$lead_id);?>" class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1">
                    <h2>Lead Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="st <?php echo $leadstatus_bg; ?>"><?php echo $leadstatus; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Source</th>
                                    <td><?php echo $lead->source_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Assigned To </th>
                                    <td><?php echo $lead->assigned; ?></td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $lead->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $lead->email; ?></td>
                                </tr>
                                <tr>
                                    <th>Company</th>
                                    <td><?php echo $lead->company; ?></td>
                                </tr>
                                <tr>
                                    <th>Position</th>
                                    <td><?php echo $lead->position; ?></td>
                                </tr>
                                <tr>
                                    <th>Website</th>
                                    <td><?php echo $lead->website; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><?php echo $lead->phone; ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>
                                        <p> <?php echo $lead->address; ?> ,</p>
                                        <p> <?php echo $lead->state; ?>,</p>
                                        <p> <?php echo $lead->state; ?>,</p>
                                        <p> <?php echo $lead->country; ?>-<?php echo $lead->zip; ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $lead->description; ?></td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td><?php echo $lead->remarks; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="lead_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame" data-ajax-url="<?php echo url_to('erp.crm.uploadleadattachment').'?id='.$lead_id.'&' ;?>" >
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
                            <tbody class="attachment-holder" data-ajaxdel-url="<?php echo url_to('erp.crm.leaddeleteattachment').'?' ;?>" >
                                <?php
                                    foreach ($attachments as $attach) {
                                        ?>
                                <tr>
                                    <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('lead').$attach['filename'] ;?>"><?php echo $attach['filename'] ;?></a></td>
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
        <div class="tab_pane" id="lead_notify">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <button class="btn bg-primary modalBtn" id="notify_modal_invoker1" type="button" ><i class="fa fa-plus" ></i>Add Notify</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable" data-ajax-url="<?php echo url_to('erp.crm.ajaxleadnotifyresponse').'?leadid='.$lead_id.'&' ;?>" >

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
                                            <li><a data-default-href="<?php echo base_url().'erp/crm/lead-notify-export?export=excel&leadid='.$lead_id.'&';?>" href="<?php echo base_url().'erp/crm/lead-notify-export?export=excel&leadid='.$lead_id.'&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/xls.png' ;?>" alt="excel" >EXCEL</a></li>
                                            <li><a data-default-href="<?php echo base_url().'erp/crm/lead-notify-export?export=pdf&leadid='.$lead_id.'&';?>" href="<?php echo base_url().'erp/crm/lead-notify-export?export=pdf&leadid='.$lead_id.'&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/pdf.png' ;?>" alt="pdf" >PDF</a></li>
                                            <li><a data-default-href="<?php echo base_url().'erp/crm/lead-notify-export?export=csv&leadid='.$lead_id.'&';?>" href="<?php echo base_url().'erp/crm/lead-notify-export?export=csv&leadid='.$lead_id.'&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/csv.png' ;?>" alt="csv" >CSV</a></li>
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
                echo form_open(url_to('erp.crm.leadnotify',$lead_id),array(
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
                        <div class="ajaxselectBox poR" data-ajax-url="<?= url_to('erp.crm.ajaxFetchUsers'); ?>" >
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
            <?= form_close();?>
        </div>
    </div>



    <div class="modal" id="customer_convert_modal" role="dialog">
        <div class="modalbody" >
            <h2 class="modalTitle" >Convert To Customer</h2>
            <?php
                 echo form_open(url_to('erp.crm.convertleadcustomer',$lead_id),array(
                    "id"=>"customer_convert_form",
                    "class"=>"flex"
                ));
            ?>
            <input type="hidden" name="lead_id" value="<?php echo $lead_id; ?>" />
            <div class="form-width-1">
                <div class="form-group field-required">
                    <label class="form-label">Group</label>
                    <div class="multiSelectBox poR">
                        <div class="multiSelectBoxBtn">
                            <div class="Multi_InputContainer" data-default="Select Group">Select Group</div>
                            <button class="drops2" type="button"><i class="fa fa-caret-down"></i></button>
                            <input type="hidden" name="groups" value="" class="multiSelectInput field-check" >
                        </div>
                        <div role="comboBox" class="MultiselectBox_Container">
                            <?php
                                foreach ($customer_groups as $group) {
                                    ?>
                            <label class="multiBox_label"><input type="checkBox" data-value="<?php echo $group['group_id'];?>" ><?php echo $group['group_name'];?></label>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <p class="error-text" ></p>
                </div>
            </div>
            <!--CUSTOM FIELDS -->
            <input type="hidden" name="customfield_chkbx_counter" value="<?php echo $customfield_chkbx_counter; ?>" />
            <?php echo $customfields; ?>
            <!--CUSTOM FIELDS ENDS-->
            <div class="form-width-1 ">
                <div class="form-group textRight ">
                    <button type="button" class="btn outline-danger modalClose">Close</button>
                    <button class="btn bg-primary" type="button" id="convert_customer_btn" >Save</button>
                </div>
            </div>
            </form>
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

        let modal_box2=new ModalBox(document.getElementById("customer_convert_modal"));
        modal_box2.init();

        let modal2_invoker=document.getElementById("convert_customer_invoker");
        if(modal2_invoker!==null && modal2_invoker!==undefined){
            modal2_invoker.onclick=(evt)=>{
                modal_box2.show_modal();
            }           
        }   


        let convert_form=document.getElementById("customer_convert_form");
        let convert_validator=new FormValidate(convert_form);

        let convert_lock=false;
        document.getElementById("convert_customer_btn").onclick=(evt)=>{
            if(!convert_lock){
                convert_lock=true;
                convert_validator.validate(
                (params)=>{
                    convert_form.submit();
                    convert_lock=false;
                },
                (params)=>{
                    convert_lock=false;
                },
                {});
            }
        }

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

        document.querySelectorAll(".multiSelectBox").forEach((item)=>{
            let multiselectbox=new MultiSelectBox(item);
            multiselectbox.init();
            closer.register_shutdown(multiselectbox.shutdown,multiselectbox.get_container());
        });

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
            if(session()->getFlashdata("op_success")){ ?>
                alert.invoke_alert("<?= session()->getFlashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getFlashdata("op_error")){ ?>
                alert.invoke_alert("<?= session()->getFlashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>
