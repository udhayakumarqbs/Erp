<div class="alldiv flex widget_title">
    <h3>Create Project</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.project.projects') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.project.projectadd'),array(
            "class"=>"flex",
            "id"=>"project_add_form"
        ));
    ?>
        <div class="form-width-2">
            <div class="form-group field-ajax " data-ajax-url="<?php echo url_to('erp.project.ajaxprojectnameunique').'?'; ?>" >
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">No of Units</label>
                <input type="text" name="units" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Property Type</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="select type">select type</div>
                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden"  class="selectBox_Value field-check" name="type_id" value="" >
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                        foreach ($types as $row) {
                            ?>
                    <li role="option" data-value="<?php echo $row['type_id'];?>" ><?php echo $row['type_name'];?></li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
            <p class="error-text" ></p>
        </div>
        </div>
        <div class="form-width-2" >
            <div class="form-group field-required">
                <label class="form-label">Amenities</label>
                <div class="multiSelectBox poR">
                    <div class="multiSelectBoxBtn">
                        <div class="Multi_InputContainer" data-default="Select amenity">Select amenity</div>
                        <button class="drops2" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" name="amenity" value="" class="multiSelectInput field-check" >
                    </div>
                    <div role="comboBox" class="MultiselectBox_Container">
                        <?php
                            foreach ($amenities as $row) {
                                ?>
                        <label class="multiBox_label"><input type="checkBox" data-value="<?php echo $row['amenity_id'];?>" ><?php echo $row['amenity_name'];?></label>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-money">
                <label class="form-label">Budget</label>
                <input type="text" name="budget" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2"></div>
        <div class="form-width-2">
        <div class="form-group" >
            <label class="form-label">Customer</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.crm.ajaxfetchcustomers'); ?>" >
                <div class="ajaxselectBoxBtn flex"> 
                    <div class="textFlow" data-default="select customer">select customer</div>
                    <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="cust_id" value="" >
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
                        <input type="hidden" name="members" value="" class="multiSelectInput field-check" >
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
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">State</label>
                <input type="text" name="state" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Zipcode</label>
                <input type="text" name="zipcode" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Description</label>
                <textarea rows="3" name="description" class="form_control field-check" ></textarea>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo url_to('erp.project.projects') ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="project_add_submit">Save</button>
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

        document.querySelectorAll(".selectBox").forEach((item)=>{
            let selectbox=new SelectBox(item);
            selectbox.init();
            closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
        });

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

        let form=document.getElementById("project_add_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("project_add_submit").onclick=function(evt){
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