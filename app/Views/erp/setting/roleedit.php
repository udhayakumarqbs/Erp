<div class="alldiv flex widget_title">
    <h3>Update Role</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/setting/roles' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.setting.roleedit',$role_id),array(
            "class"=>"flex",
            "id"=>"role_edit_form"
        ));
    ?>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Role Name</label>
                <input type="text" class="form_control field-check" value="<?php echo $role->role_name; ?>" name="role_name" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Role Description</label>
                <input type="text" class="form_control field-check" value="<?php echo $role->role_desc; ?>" name="role_desc" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-check-label"><input type="checkbox" name="do_reflect" value="1" /> Does this update should reflect to every users ? </label>
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
                        <?php
                            $perms=json_decode($role->permissions,true)??array();
                        ?>
                        <tr>
                            <td>CRM Leads</td>
                            <td><input name="crm_lead_view_global" <?php
                                if(in_array("crm_lead_view_global",$perms)){
                                    echo "checked";
                                }
                            ?> class="view_global" type="checkbox" /></td>
                            <td><input name="crm_lead_view_own" <?php
                                if(in_array("crm_lead_view_own",$perms)){
                                    echo "checked";
                                }
                            ?> class="view_own" type="checkbox" /></td>
                            <td><input name="crm_lead_create" <?php
                                if(in_array("crm_lead_create",$perms)){
                                    echo "checked";
                                }
                            ?> type="checkbox" /></td>
                            <td><input name="crm_lead_update" <?php
                                if(in_array("crm_lead_update",$perms)){
                                    echo "checked";
                                }
                            ?> type="checkbox" /></td>
                            <td><input name="crm_lead_delete" <?php
                                if(in_array("crm_lead_delete",$perms)){
                                    echo "checked";
                                }
                            ?> type="checkbox" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>CRM Customers</td>
                            <td><input name="crm_customer_view_global" <?php
                                if(in_array("crm_customer_view_global",$perms)){
                                    echo "checked";
                                }
                            ?> class="view_global" type="checkbox" /></td>
                            <td><input name="crm_customer_view_own"  <?php
                                if(in_array("crm_customer_view_own",$perms)){
                                    echo "checked";
                                }
                            ?> class="view_own" type="checkbox" /></td>
                            <td><input name="crm_customer_create" <?php
                                if(in_array("crm_customer_create",$perms)){
                                    echo "checked";
                                }
                            ?> type="checkbox" /></td>
                            <td><input name="crm_customer_update" <?php
                                if(in_array("crm_customer_update",$perms)){
                                    echo "checked";
                                }
                            ?> type="checkbox" /></td>
                            <td><input name="crm_customer_delete" <?php
                                if(in_array("crm_customer_delete",$perms)){
                                    echo "checked";
                                }
                            ?> type="checkbox" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Notifications</td>
                            <td><input name="notify_view_global"  <?php
                                if(in_array("notify_view_global",$perms)){
                                    echo "checked";
                                }
                            ?> class="view_global" type="checkbox" /></td>
                            <td><input name="notify_view_own"  <?php
                                if(in_array("notify_view_own",$perms)){
                                    echo "checked";
                                }
                            ?>  class="view_own" type="checkbox" /></td>
                            <td><input name="notify_create"  <?php
                                if(in_array("notify_create",$perms)){
                                    echo "checked";
                                }
                            ?> type="checkbox" /></td>
                            <td><input name="notify_update"  <?php
                                if(in_array("notify_update",$perms)){
                                    echo "checked";
                                }
                            ?> type="checkbox" /></td>
                            <td><input name="notify_delete"  <?php
                                if(in_array("notify_delete",$perms)){
                                    echo "checked";
                                }
                            ?> type="checkbox" /></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/setting/roles' ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="role_edit_submit">Update</button>
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

        let form=document.getElementById("role_edit_form");
        let validator=new FormValidate(form);

        document.getElementById("role_edit_submit").onclick=function(evt){
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