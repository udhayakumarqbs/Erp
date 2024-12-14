<div class="alldiv flex widget_title">
    <h3>Update Team</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.project.teams') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.project.teamedit',$team_id),array(
            "class"=>"flex",
            "id"=>"team_edit_form"
        ));
    ?>
        <div class="form-width-2">
            <div class="form-group field-ajax" data-ajax-url="<?php echo base_url().'erp/project/teams-name-unque?id='.$team_id.'&' ;?>" >
                <label class="form-label">Name</label>
                <input type="text" name="name" value="<?php echo $team->team_name; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-number ">
                <label class="form-label">Count</label>
                <input type="text" name="team_count" value="<?php echo $team->team_count; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
        <div class="form-group field-required" >
            <label class="form-label">Lead By</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.crm.ajaxFetchUsers'); ?>" >
                <div class="ajaxselectBoxBtn flex"> 
                    <div class="textFlow" data-default="lead by"><?php echo $team->leadby; ?></div>
                    <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="lead_by" value="<?php echo $team->user_id; ?>" >
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
                <textarea rows="3" name="description" class="form_control field-check" ><?php echo $team->description; ?></textarea>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo url_to('erp.project.teams') ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="team_edit_submit">Update</button>
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

        document.querySelectorAll(".ajaxselectBox").forEach((item)=>{
            let ajaxselectbox=new AjaxSelectBox(item);
            ajaxselectbox.init();
            closer.register_shutdown(ajaxselectbox.shutdown,ajaxselectbox.get_container());
        });

        let form=document.getElementById("team_edit_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("team_edit_submit").onclick=function(evt){
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
            if(session()->getFlashdata("op_success")){ ?>
                let alerts=new ModalAlert();
                alerts.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getFlashdata("op_error")){ ?>
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>