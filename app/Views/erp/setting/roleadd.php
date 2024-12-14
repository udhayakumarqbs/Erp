<div class="alldiv flex widget_title">
    <h3>Create Role</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/setting/roles' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.setting.roleadd'),array(
            "class"=>"flex",
            "id"=>"role_add_form"
        ));
    ?>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Role Name</label>
                <input type="text" class="form_control field-check" name="role_name" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Role Description</label>
                <input type="text" class="form_control field-check" name="role_desc" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="table_responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Menus</th>
                            <th>View Global</th>
                            <th>View Own</th>
                            <th>Create</th>
                            <th>Update</th>
                            <th>Delete</th>
                            <th>Misc</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>CRM Leads</td>
                            <td><input name="crm_lead_view_global" class="view_global" type="checkbox" /></td>
                            <td><input name="crm_lead_view_own" class="view_own" type="checkbox" /></td>
                            <td><input name="crm_lead_create" type="checkbox" /></td>
                            <td><input name="crm_lead_update" type="checkbox" /></td>
                            <td><input name="crm_lead_delete" type="checkbox" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>CRM Customers</td>
                            <td><input name="crm_customer_view_global" class="view_global" type="checkbox" /></td>
                            <td><input name="crm_customer_view_own"  class="view_own" type="checkbox" /></td>
                            <td><input name="crm_customer_create" type="checkbox" /></td>
                            <td><input name="crm_customer_update" type="checkbox" /></td>
                            <td><input name="crm_customer_delete" type="checkbox" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Notifications</td>
                            <td><input name="notify_view_global" class="view_global" type="checkbox" /></td>
                            <td><input name="notify_view_own"  class="view_own" type="checkbox" /></td>
                            <td><input name="notify_create" type="checkbox" /></td>
                            <td><input name="notify_update" type="checkbox" /></td>
                            <td><input name="notify_delete" type="checkbox" /></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/setting/roles' ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="role_add_submit">Save</button>
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

        let form=document.getElementById("role_add_form");
        let validator=new FormValidate(form);

        document.getElementById("role_add_submit").onclick=function(evt){
            validator.validate(
                (params)=>{
                //success
                form.submit();
            },
            (params)=>{
                //error
                console.log(params);
            },
            {});
        }

        document.querySelectorAll(".view_global").forEach((item)=>{
            item.onchange=(evt)=>{
                let checked=item.checked;
                if(checked){
                    item.parentElement.nextElementSibling.querySelector(".view_own").checked=false;
                }
            }
        }); 

        document.querySelectorAll(".view_own").forEach((item)=>{
            item.onchange=(evt)=>{
                let checked=item.checked;
                if(checked){
                    item.parentElement.previousElementSibling.querySelector(".view_global").checked=false;
                }
            }
        }); 
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