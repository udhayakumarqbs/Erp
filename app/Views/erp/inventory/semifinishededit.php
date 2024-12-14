<div class="alldiv flex widget_title">
    <h3>Update Semi Finished</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.inventory.semifinished') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.inventory.semifinishededit',$semi_finished_id),array(
            "class"=>"flex",
            "id"=>"semifinished_edit_form"
        ));
    ?>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="<?php echo $semi_finished->name; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group  field-ajax" data-ajax-url="<?php echo url_to('erp.inventory.ajaxsemifinishedcodeunique').'?id='.$semi_finished_id.'&' ;?>" >
                <label class="form-label">Code</label>
                <input type="text" name="code" value="<?php echo $semi_finished->code; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Unit</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex"> 
                        <div class="textFlow" data-default="select unit">select unit</div>
                        <button class="close" type="button" ><i class="fa fa-close"></i></button>
                        <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                        <input type="hidden"  class="selectBox_Value field-check" name="unit" value="<?php echo $semi_finished->unit_id; ?>" >
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                            foreach ($units as $unit) {
                                ?>
                        <li role="option" data-value="<?php echo $unit['unit_id'];?>" ><?php echo $unit['unit_name'];?></li>
                        <?php
                            }
                        ?>
                    </ul>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Brand</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex"> 
                        <div class="textFlow" data-default="select brand">select brand</div>
                        <button class="close" type="button" ><i class="fa fa-close"></i></button>
                        <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                        <input type="hidden"  class="selectBox_Value field-check" name="brand" value="<?php echo $semi_finished->brand_id; ?>" >
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                            foreach ($brands as $brand) {
                                ?>
                        <li role="option" data-value="<?php echo $brand['brand_id'];?>" ><?php echo $brand['brand_name'];?></li>
                        <?php
                            }
                        ?>
                    </ul>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Group</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex"> 
                        <div class="textFlow" data-default="select group">select group</div>
                        <button class="close" type="button" ><i class="fa fa-close"></i></button>
                        <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                        <input type="hidden"  class="selectBox_Value field-check" name="group" value="<?php echo $semi_finished->group_id; ?>" >
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                            foreach ($semifinished_groups as $group) {
                                ?>
                        <li role="option" data-value="<?php echo $group['group_id'];?>" ><?php echo $group['group_name'];?></li>
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
                <label class="form-label">Warehouse</label>
                <div class="multiSelectBox poR">
                    <div class="multiSelectBoxBtn">
                        <div class="Multi_InputContainer" data-default="Select Warehouse">Select Warehouse</div>
                        <button class="drops2" type="button"><i class="fa fa-caret-down"></i></button>
                        <?php
                            $warehouse_ids=array();
                            foreach($semifinished_warehouse as $warehouse){
                                array_push($warehouse_ids,$warehouse['warehouse_id']);
                            }
                        ?>
                        <input type="hidden" name="warehouses" value="<?php echo implode(',',$warehouse_ids); ?>" class="multiSelectInput field-check" >
                    </div>
                    <div role="comboBox" class="MultiselectBox_Container">
                        <?php
                            foreach ($warehouses as $warehouse) {
                                ?>
                        <label class="multiBox_label"><input type="checkBox" data-value="<?php echo $warehouse['warehouse_id'];?>" ><?php echo $warehouse['name'];?></label>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>

        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-label">Short Description</label>
                <textarea rows="3" name="short_desc" class="form_control field-check" ><?php echo $semi_finished->short_desc; ?></textarea>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-label">Long Description</label>
                <textarea rows="3" name="long_desc" class="form_control field-check" ><?php echo $semi_finished->long_desc; ?></textarea>
                <p class="error-text"></p>
            </div>
        </div>

        <!--CUSTOM FIELDS -->
            <input type="hidden" name="customfield_chkbx_counter" value="<?php echo $customfield_chkbx_counter; ?>" />
            <?php echo $customfields; ?>
        <!--CUSTOM FIELDS ENDS-->

        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo url_to('erp.inventory.semifinished') ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="semifinished_edit_submit">Update</button>
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

        let form=document.getElementById("semifinished_edit_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("semifinished_edit_submit").onclick=function(evt){
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
            if(session()->getflashdata("op_success")){ ?>
                let alerts=new ModalAlert();
                alerts.invoke_alert("<?php echo session()->getflashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getflashdata("op_error")){ ?>
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo session()->getflashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>