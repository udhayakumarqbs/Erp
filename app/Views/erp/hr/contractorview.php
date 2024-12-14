<div class="alldiv flex widget_title">
    <h3>View Contractor</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/hr/contractors' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="con_info">Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="con_attachment">Attachments</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="con_info">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <a href="<?= url_to('erp.hr.contractordelete',$contractor_id)?>" class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1">
                    <h2>Contractor Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Code</th>
                                    <td><?php echo $contractor->con_code; ?></td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $contractor->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Contact Person</th>
                                    <td><?php echo $contractor->contact_person; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $contractor->email; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone 1</th>
                                    <td><?php echo $contractor->phone_1; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone 2</th>
                                    <td><?php echo $contractor->phone_2; ?></td>
                                </tr>
                                <tr>
                                    <th>GST No</th>
                                    <td><?php echo $contractor->gst_no; ?></td>
                                </tr>
                                <tr>
                                    <th>PAN No</th>
                                    <td><?php echo $contractor->pan_no; ?></td>
                                </tr>
                                <tr>
                                    <th>Website</th>
                                    <td><?php echo $contractor->website; ?></td>
                                </tr>
                                <tr>
                                    <th>Active</th>
                                    <td><?php echo ($contractor->active==1)?'Yes':'No'; ?></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $contractor->description; ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>
                                        <p> <?php echo $contractor->address; ?> ,</p>
                                        <p> <?php echo $contractor->state; ?>,</p>
                                        <p> <?php echo $contractor->state; ?>,</p>
                                        <p> <?php echo $contractor->country; ?>-<?php echo $contractor->zipcode; ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="con_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame" data-ajax-url="<?php echo url_to('erp.hr.uploadcontractorattachment').'?id='.$contractor_id.'&' ;?>" >
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
                            <tbody class="attachment-holder" data-ajaxdel-url="<?php echo url_to('erp.hr.contractordeleteattachment').'?' ;?>" >
                                <?php
                                    foreach ($attachments as $attach) {
                                        ?>
                                <tr>
                                    <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('contractor').$attach['filename'] ;?>"><?php echo $attach['filename'] ;?></a></td>
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