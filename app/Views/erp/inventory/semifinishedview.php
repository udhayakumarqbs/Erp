<div class="alldiv flex widget_title">
    <h3>View Semi Finished</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.inventory.semifinished') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="item_info">Item Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="rawmaterial_attachment">Attachments</a></li>
        <li><a type="button" class="tab_nav_item" data-src="rawmaterial_stockalert">Stock Alert</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="item_info">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <a href="<?= url_to('erp.inventory.semifinisheddelete', $semi_finished_id); ?>" class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1">
                    <h2>Item Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $semi_finished->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Code</th>
                                    <td><?php echo $semi_finished->code; ?></td>
                                </tr>
                                <tr>
                                    <th>Group</th>
                                    <td><?php echo $semi_finished->group_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Unit</th>
                                    <td><?php echo $semi_finished->unit_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Brand</th>
                                    <td><?php echo $semi_finished->brand_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Short Description</th>
                                    <td><?php echo $semi_finished->short_desc; ?></td>
                                </tr>
                                <tr>
                                    <th>Long Description</th>
                                    <td><?php echo $semi_finished->long_desc; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="rawmaterial_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame" data-ajax-url="<?php echo url_to('erp.inventory.uploadsemifinishedattachment').'?id='.$semi_finished_id.'&' ;?>" >
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
                            <tbody class="attachment-holder" data-ajaxdel-url="<?php echo url_to('erp.inventory.semifinisheddeleteattachment').'?' ;?>" >
                                <?php
                                    foreach ($attachments as $attach) {
                                        ?>
                                <tr>
                                    <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('rawmaterial').$attach['filename'] ;?>"><?php echo $attach['filename'] ;?></a></td>
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
        <div class="tab_pane" id="rawmaterial_stockalert">
            <?php
                echo form_open(url_to('erp.inventory.semifinishedstockalert',$semi_finished_id),array(
                    "id"=>"stockalert_add_form",
                    "class"=>"flex"
                ));
            ?>
            <div class="form-width-1">
                <div class="form-group field-number ">
                    <label class="form-label">Alert when qty falls below</label>
                    <input type="text" name="alert_qty_level" value="<?php 
                    if(!empty($stock_alert)){
                            echo $stock_alert->alert_qty_level;
                        } ;?>" class="form_control field-check" />
                    <p class="error-text" ></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group field-number ">
                    <label class="form-label">Alert before (in days)</label>
                    <input type="text" name="alert_before" value="<?php 
                    if(!empty($stock_alert)){
                            echo $stock_alert->alert_before;
                        } ;?>" class="form_control field-check" />
                    <p class="error-text" ></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group ">
                    <div>
                        <label class="form-check-label"><input type="checkbox" <?php 
                    if(!empty($stock_alert) && $stock_alert->recurring==1){
                            echo "checked";
                        } ;?> name="recurring" value="1" /> should send alert repeatadly ?</label>
                    </div>
                    <p class="error-text" ></p>
                </div>
            </div>
            <div class="form-width-1 ">
                <div class="form-group textRight">
                    <button class="btn bg-primary" type="button" id="stockalert_submit_btn" >Save</button
                </div>
            </div>
            </form>
        </div>
    </div>
</div>



<!--MODALS-->
<div class="modal" id="notify_addedit_modal" role="dialog">
        <div class="modalbody">
            <h2 class="modalTitle">Notify</h2>
            <?php
                echo form_open(base_url().'erp/crm/leadnotify/',array(
                    "id"=>"notify_addedit_form",
                    "class"=>"flex"
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
                <div class="form-width-1">
                    <div class="form-group">
                        <label class="form-label"></label>
                        <label class="form-check-label"><input id="f_notify_creater" type="checkbox" name="notify_creater" value="1" /> Notify creater too </label>
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1 ">
                    <div class="form-group textRight ">
                        <button type="button" class="btn outline-danger modalClose">Close</button>
                        <button class="btn bg-primary" type="button" id="notify_addedit_btn" >Save</button>
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

        let stockalert_form=document.getElementById("stockalert_add_form");
        let stockalert_validator=new FormValidate(stockalert_form);

        let stockalert_lock=false;
        document.getElementById("stockalert_submit_btn").onclick=(evt)=>{
            if(!stockalert_lock){
                stockalert_lock=true;
                stockalert_validator.validate(
                (params)=>{
                    stockalert_form.submit();
                    stockalert_lock=false;
                },
                (params)=>{
                    stockalert_lock=false;
                },
                {});
            }
        }

        let modal_box=new ModalBox(document.getElementById("notify_addedit_modal"));
        modal_box.init();

        document.getElementById("notify_modal_invoker1").onclick=(evt)=>{
            document.getElementById("f_notify_id").value="0";
            document.getElementById("f_notify_title").value="";
            document.getElementById("f_notify_desc").value="";
            document.getElementById("f_notify_at").value="";
            document.getElementById("f_notify_email").checked=false;
            document.getElementById("f_notify_creater").checked=false;
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
                                    if(data['notify_creater']==1){
                                        document.getElementById("f_notify_creater").checked=true;
                                    }
                                    modal_box.show_modal();
                                }
                            }
                        }
                    }
                }
            });
        };


        

        let closer=new WindowCloser();
        closer.init();

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

        let config=JSON.parse('<?php $notify_datatable_config; ?>');
        let datatable=new DataTable(datatable_elem,config);
        datatable.init();

        rows_per_page.add_listener(datatable.rows_per_page,{});

        <?php
            if(session()->getflashdata("op_success")){ ?>
                alert.invoke_alert("<?php echo session()->getflashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getflashdata("op_error")){ ?>
                alert.invoke_alert("<?php echo session()->getflashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>