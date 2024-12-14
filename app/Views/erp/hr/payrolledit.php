<div class="alldiv flex widget_title">
    <h3>Update Payroll</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/hr/payrolls' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.hr.payrolledit',$pay_entry_id),array(
            "class"=>"flex",
            "id"=>"payroll_edit_form"
        ));
    ?>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="<?php echo $payroll->name; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Payment Date</label>
                <input type="date" name="payment_date" value="<?php echo $payroll->payment_date; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Payment From</label>
                <input type="date" name="payment_from" value="<?php echo $payroll->payment_from; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Payment To</label>
                <input type="date" name="payment_to" value="<?php echo $payroll->payment_to; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2" >
            <div class="form-group ">
                <label class="form-label">Deductions</label>
                <div class="multiSelectBox poR">
                    <div class="multiSelectBoxBtn">
                        <div class="Multi_InputContainer" data-default="Select Group">Select Group</div>
                        <button class="drops2" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" name="deductions" value="<?php echo $payroll->deduction; ?>" class="multiSelectInput field-check" >
                    </div>
                    <div role="comboBox" class="MultiselectBox_Container">
                        <?php
                            foreach ($deductions as $row) {
                                ?>
                        <label class="multiBox_label"><input type="checkBox" data-value="<?php echo $row['deduct_id'];?>" ><?php echo $row['name'];?></label>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2" >
            <div class="form-group ">
                <label class="form-label">Additions</label>
                <div class="multiSelectBox poR">
                    <div class="multiSelectBoxBtn">
                        <div class="Multi_InputContainer" data-default="Select Group">Select Group</div>
                        <button class="drops2" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" name="additions" value="<?php echo $payroll->addition; ?>" class="multiSelectInput field-check" >
                    </div>
                    <div role="comboBox" class="MultiselectBox_Container">
                        <?php
                            foreach ($additions as $row) {
                                ?>
                        <label class="multiBox_label"><input type="checkBox" data-value="<?php echo $row['add_id'];?>" ><?php echo $row['name'];?></label>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/hr/payrolls' ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="payroll_edit_submit">Update</button>
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

        let form=document.getElementById("payroll_edit_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("payroll_edit_submit").onclick=function(evt){
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