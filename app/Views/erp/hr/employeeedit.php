<div class="alldiv flex widget_title">
    <h3>Update Employee</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/hr/employees' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.hr.employeeedit',$employee_id),array(
            "class"=>"flex",
            "id"=>"employee_edit_form"
        ));
    ?>
        <div class="form-width-2">
            <div class="form-group field-ajax " data-ajax-url="<?php echo url_to('erp.hr.ajaxempcodeunique').'?id='.$employee_id.'&' ;?>" >
                <label class="form-label">Code</label>
                <input type="text" name="emp_code" value="<?php echo $employee->emp_code; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Firstname</label>
                <input type="text" name="first_name" value="<?php echo $employee->first_name; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Lastname</label>
                <input type="text" name="last_name" value="<?php echo $employee->last_name; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-ajax " data-ajax-url="<?php echo url_to('erp.hr.ajaxempemailunique').'?id='.$employee_id.'&' ;?>">
                <label class="form-label">Email</label>
                <input type="text" name="email" value="<?php echo $employee->email; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-ajax " data-ajax-url="<?php echo url_to('erp.hr.ajaxempphoneunique').'?id='.$employee_id.'&' ;?>">
                <label class="form-label">Phone</label>
                <input type="text" name="phone_no" value="<?php echo $employee->phone_no; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group">
                <label class="form-label">Mobile</label>
                <input type="text" name="mobile_no" value="<?php echo $employee->mobile_no; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Qualification</label>
                <input type="text" name="qualification" value="<?php echo $employee->qualification; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Years of Experience</label>
                <input type="text" name="years_of_exp" value="<?php echo $employee->years_of_exp; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Designation</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="select designation">select designation</div>
                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="designation_id" value="<?php echo $employee->designation_id; ?>" >
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                <?php
                    foreach ($designations as $row) {
                        ?>
                    <li role="option" data-value="<?php echo $row['designation_id'];?>" ><?php echo $row['desig'];?></li>
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
                <label class="form-label">Joining Date</label>
                <input type="date" name="joining_date" value="<?php echo $employee->joining_date; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-3">
        <div class="form-group field-required ">
            <label class="form-label">Gender</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="select gender">select gender</div>
                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden"  class="selectBox_Value field-check" name="gender" value="<?php echo $employee->gender; ?>" >
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                        foreach ($genders as $key=>$value) {
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
        <div class="form-width-3">
        <div class="form-group field-required">
            <label class="form-label">Status</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="select status">select status</div>
                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="emp_status" value="<?php echo $employee->status; ?>" >
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                <?php
                    foreach ($emp_status as $key=>$status) {
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
        
        <div class="form-width-3">
        <div class="form-group field-required">
            <label class="form-label">Marital Status</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="select status">select status</div>
                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="marital_status" value="<?php echo $employee->marital_status; ?>" >
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                <?php
                    foreach ($marital_status as $key=>$status) {
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
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" value="<?php echo $employee->date_of_birth; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Blood Group</label>
                <input type="text" name="blood_group" value="<?php echo $employee->blood_group; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Address</label>
                <input type="text" name="address" value="<?php echo $employee->address; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">City</label>
                <input type="text" name="city" value="<?php echo $employee->city; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">State</label>
                <input type="text" name="state" value="<?php echo $employee->state; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Country</label>
                <input type="text" name="country" value="<?php echo $employee->country; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Zipcode</label>
                <input type="text" name="zipcode" value="<?php echo $employee->zipcode; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-money ">
                <label class="form-label">Work Hr Salary</label>
                <input type="text" name="w_hr_salary" value="<?php echo $employee->w_hr_salary; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-money ">
                <label class="form-label">OT Hr Salary</label>
                <input type="text" name="ot_hr_salary" value="<?php echo $employee->ot_hr_salary; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-money ">
                <label class="form-label">Salary</label>
                <input type="text" name="salary" value="<?php echo $employee->salary; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/hr/employees' ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="employee_edit_submit">Save</button>
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

        let form=document.getElementById("employee_edit_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("employee_edit_submit").onclick=function(evt){
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