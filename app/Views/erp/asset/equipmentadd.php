<div class="alldiv flex widget_title">
    <h3>Create Equipment</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.assets') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <form action="<?=url_to('erp.equipment.add.post')?>" method="POST" class="flex" id="equipment_add_form">
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-ajax" data-ajax-url="<?php echo base_url().'erp/asset/ajax_equip_code_unique?' ;?>" >
                <label class="form-label">Code</label>
                <input type="text" name="code" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Model</label>
                <input type="text" name="model" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Maker</label>
                <input type="text" name="maker" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
    /**
     * Summary of getOperationCost
     * @param mixed $planning_id
     * @param mixed $bom_id
     * @return void
     */
                <label class="form-label">Bought Date</label>
                <input type="date" name="bought_date" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group">
                <label class="form-label">Age</label>
                <input type="text" name="age" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-checked-any">
                <div>
                    <label class="form-check-label"><input class="field-check" value="Automatic" type="radio" name="work_type" /> Automatic </label>
                    <label class="form-check-label"><input class="field-check" value="Manual" type="radio" name="work_type" /> Manual </label>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-checked-any">
                <div>
                    <label class="form-check-label"><input class="field-check" value="Electric" type="radio" name="consump_type" /> Electric </label>
                    <label class="form-check-label"><input class="field-check" value="Fuel" type="radio" name="consump_type" /> Fuel </label>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Consumption</label>
                <input type="text" name="consumption" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Status</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="select status">select status</div>
                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="equip_status" value="" >
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                <?php
                    foreach ($equip_status as $key=>$status) {
                        ?>
                    <li role="option" data-value="<?php echo $key;?>" ><?php echo $status;?></li>
                    <?php
                        }
                    ?>
                </ul>
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
            <div class="form-group textRight">
                <a href="<?php echo url_to('erp.assets') ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="submit" id="equipment_add_submit">Save</button>
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

        let form=document.getElementById("equipment_add_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("equipment_add_submit").onclick=function(evt){
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

            if(typeof alert === 'undefined'){
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>","success");
            }
        <?php
            }else if(session()->getFlashdata("op_error")){ ?>
            if(typeof alert === 'undefined'){
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>","error");
            }
        <?php
            }
        ?>
    </script>
    </body>
</html>