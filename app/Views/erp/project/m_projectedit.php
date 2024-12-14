<div class="alldiv flex widget_title">
    <h3>Update Project</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.project.projects') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.project.projectedit',$project_id),array(
            "class"=>"flex",
            "id"=>"project_edit_form"
        ));
    ?>
        <div class="form-width-2">
            <div class="form-group field-ajax" data-ajax-url="<?php echo url_to('erp.project.ajaxprojectnameunique').'?id='.$project_id.'&' ;?>" >
                <label class="form-label">Name</label>
                <input type="text" name="name" value="<?php echo $project->name; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" value="<?php echo $project->start_date; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" value="<?php echo $project->end_date; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-money ">
                <label class="form-label">Budget</label>
                <input type="text" name="budget" value="<?php echo $project->budget; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
        <div class="form-group" >
            <label class="form-label">Customer</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.crm.ajaxfetchcustomers'); ?>" >
                <div class="ajaxselectBoxBtn flex"> 
                    <div class="textFlow" data-default="select customer"><?php if(!empty($project->customer)){
                        echo $project->customer;
                    }else{
                        echo "select customer";
                    } ?></div>
                    <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="cust_id" value="<?php
                        if(!empty($project->cust_id)){
                            echo $project->cust_id;
                        }
                    ?>" >
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
        <div class="form-width-2">
        <div class="form-group field-required" >
            <label class="form-label">Product</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.crm.ajaxfetchfinishedgoods'); ?>" >
                <div class="ajaxselectBoxBtn flex"> 
                    <div class="textFlow" data-default="select product"><?php echo $project->product; ?></div>
                    <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="related_id" value="<?php echo $project->related_id; ?>" >
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
        <div class="form-width-2" >
            <div class="form-group field-required">
                <label class="form-label">Members</label>
                <div class="multiSelectBox poR">
                    <div class="multiSelectBoxBtn">
                        <div class="Multi_InputContainer" data-default="Select member">Select member</div>
                        <button class="drops2" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" name="members" value="<?php echo $project->members; ?>" class="multiSelectInput field-check" >
                    </div>
                    <div role="comboBox" class="MultiselectBox_Container">
                        <?php
                            foreach ($members as $row) {
                        ?>
                        <label class="multiBox_label"><input type="checkBox" data-value="<?php echo $row['user_id'];?>" ><?php echo $row['name'];?></label>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Description</label>
                <textarea rows="3" name="description" class="form_control field-check" ><?php echo $project->description; ?></textarea>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo url_to('erp.project.projects') ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="project_edit_submit">Update</button>
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

        document.querySelectorAll(".multiSelectBox").forEach((item)=>{
            let multiselectbox=new MultiSelectBox(item);
            multiselectbox.init();
            closer.register_shutdown(multiselectbox.shutdown,multiselectbox.get_container());
        });

        document.querySelectorAll(".ajaxselectBox").forEach((item)=>{
            let ajaxselectbox=new AjaxSelectBox(item);
            ajaxselectbox.init();
            closer.register_shutdown(ajaxselectbox.shutdown,ajaxselectbox.get_container());
        });

        let form=document.getElementById("project_edit_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("project_edit_submit").onclick=function(evt){
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