<div class="alldiv flex widget_title">
    <h3>Create Request</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/crm/request' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open_multipart(base_url().'erp/crm/requestadd',array(
            "class"=>"flex",
            "id"=>"request_add_form"
        ));
    ?>
        <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Request To</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="select type">select type</div>
                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden"  class="selectBox_Value field-check" name="request_type" value="" >
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                        foreach ($requesttypes as $key=>$value) {
                            ?>
                    <li role="option" data-value="<?php echo $key;?>" ><?php echo $value;?></li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
            <p class="error-text" ></p>
        </div>
        </div>
        <input type="hidden" value="customer" name="related_to" />
        <div class="form-width-2">
        <div class="form-group field-required" >
            <label class="form-label">Customer</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo base_url().'erp/crm/ajaxfetchcustomers'; ?>" >
                <div class="ajaxselectBoxBtn flex"> 
                    <div class="textFlow" data-default="select customer">select customer</div>
                    <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="related_id" value="" >
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
            <div class="form-group field-required ">
                <label class="form-label">Description</label>
                <textarea rows="3" name="description" class="form_control field-check" ></textarea>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group ">
                <div>
                    <label class="form-check-label"><input type="checkbox" name="mail_request" value="1" class="field-check" /> Send via Email too
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label">Attachments</label>
                <div class="file-uploader-frame" >
                    <div class="file-uploader-box">
                        <span class="file-uploader-text">drop or click to upload files</span>
                    </div>
                    <div class="file-uploader-progessbar">
                        <span class="progressbar bg-success"></span>
                    </div>
                    <input type="file" class="file-uploader-input" />
                    <input type="file" class="multiple-preview-uploader-input" name="attachments[]" multiple />
                    <p class="file-uploader-error"></p>
                    <div class="file-uploader-preview">

                    </div>
                </div>
            </div>           
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/crm/request' ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="request_add_submit">Save</button>
            </div>
        </div>
    </form>
</div>








<!--SCRIPT WORKS -->
</div>
    </main>
    <script src="<?php echo base_url().'assets/js/jquery.min.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/script.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/erp.js' ;?>" ></script>
    <script type="text/javascript">
        let closer=new WindowCloser();
        closer.init();

        let fileuploader=new FileUploader(document.querySelector(".file-uploader-frame"));

        fileuploader.multiplePreviewUpload({
            files_allowed:JSON.parse('<?php echo json_encode($attach_filetypes); ?>'),
            maxfilesize:parseInt('<?php echo $attach_maxfilesize; ?>')
        });

        document.querySelectorAll(".selectBox").forEach((item)=>{
            let selectbox=new SelectBox(item);
            selectbox.init();
            closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
        });

        document.querySelectorAll(".ajaxselectBox").forEach((item)=>{
            let ajaxselectbox=new AjaxSelectBox(item);
            ajaxselectbox.init();
            closer.register_shutdown(ajaxselectbox.shutdown,ajaxselectbox.get_container());
        });

        let form=document.getElementById("request_add_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("request_add_submit").onclick=function(evt){
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

        <?php
            if($this->session->flashdata("op_success")){ ?>
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo $this->session->flashdata('op_success'); ?>","success");
        <?php
            }else if($this->session->flashdata("op_error")){ ?>
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo $this->session->flashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>