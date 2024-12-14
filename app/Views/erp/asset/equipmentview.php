<div class="alldiv flex widget_title">
    <h3>View Equipment</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.assets') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="equip_info">Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="equip_attachment">Attachments</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="equip_info">
            <div class="flex">
                <div class="form-width-1 textRight" >
                    <a href="<?= url_to('erp.equipment.delete',$equip_id)?>" class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1">
                    <h2>Equipment Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $equipment->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Code</th>
                                    <td><?php echo $equipment->code; ?></td>
                                </tr>
                                <tr>
                                    <th>Model</th>
                                    <td><?php echo $equipment->model; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="st <?php echo $equip_status_bg[$equipment->status]; ?>"><?php echo $equip_status[$equipment->status]; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Maker</th>
                                    <td><?php echo $equipment->maker; ?></td>
                                </tr>
                                <tr>
                                    <th>Bought Date</th>
                                    <td><?php echo $equipment->bought_date; ?></td>
                                </tr>
                                <tr>
                                    <th>Age</th>
                                    <td><?php echo $equipment->age; ?></td>
                                </tr>
                                <tr>
                                    <th>Work Type</th>
                                    <td><?php echo $equipment->work_type; ?></td>
                                </tr>
                                <tr>
                                    <th>Consumption Type</th>
                                    <td><?php echo $equipment->consump_type; ?></td>
                                </tr>
                                <tr>
                                    <th>Consumption</th>
                                    <td><?php echo $equipment->consumption; ?></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $equipment->description; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="equip_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame" data-ajax-url="<?php echo base_url().'erp/assets/upload_equipment_attachment?id='.$equip_id.'&';?>" >
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
                            <tbody class="attachment-holder" data-ajaxdel-url="<?php echo url_to('erp.equipment.delete.document').'?' ;?>" >
                                <?php
                                    
                                    if(!empty($attachments)):
                                        foreach($attachments as $attach):
                                        ?>
                                <tr>
                                    <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('equipment').$attach['filename'] ;?>"><?php echo $attach['filename'] ;?></a></td>
                                    <td><button class="btn bg-danger del-attachment-btn" type="button" data-attach-id="<?php echo $attach['attach_id'];?>" ><i class="fa fa-trash"></i></button></td>
                                </tr>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                        </table>
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
                 <form action="" id="notify_addedit_form" class="flex modal-scroll-form">
                    <!-- 'erp/crm/leadnotify/'.$equip_id,array -->
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
                        <div class="ajaxselectBox poR" data-ajax-url="<?php echo base_url().'erp/crm/ajaxfetchusers'; ?>"  >
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