<div class="alldiv flex widget_title">
    <h3>Update User</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/setting/users' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.setting.useredit',$user_id),array(
            "class"=>"flex",
            "id"=>"user_edit_form"
        ));
    ?>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Name</label>
                <input type="text" class="form_control field-check" value="<?php echo $user->name; ?>" name="name" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Last Name</label>
                <input type="text" class="form_control " value="<?php echo $user->last_name; ?>" name="last_name" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-ajax" data-ajax-url="<?php echo url_to('erp.setting.ajaxmailunique').'?id='.$user_id.'&' ;?>" >
                <label class="form-label">Email</label>
                <input type="text" class="form_control field-check" value="<?php echo $user->email; ?>" name="email" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Phone</label>
                <input type="text" class="form_control " value="<?php echo $user->phone; ?>" name="phone" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Position</label>
                <input type="text" class="form_control field-check" value="<?php echo $user->position; ?>" name="position" />
                <p class="error-text" ></p>
            </div>
        </div>

        <div class="form-width-2" style="<?php
            if(!is_admin()){
                echo 'display:none';
            }
        ?>">
            <div class="form-group ">
                <label class="form-label" >Role</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="Select Role"><?php echo (!empty($user->role_name))?$user->role_name:"Select Role"; ?></div>
                        <button type="button" class="close"><i class="fa fa-close"></i></button>
                        <button type="button" class="drops"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" name="role_id" class="selectBox_Value" value="<?php echo $user->role_id; ?>"  >
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                            foreach ($roles as $role) {
                                ?>
                        <li role="option" data-value="<?php echo $role['role_id'] ;?>" ><?php echo $role['role_name'] ;?></li>
                        <?php
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="form-width-1" style="<?php
            if(!is_admin()){
                echo 'display:none';
            }
        ?>" >
            <div class="form-group">
                <label class="form-check-label"><input type="checkbox" name="is_admin" value="1" <?php 
                if($user->is_admin=="1"){
                    echo "checked";
                } ?> /> Is Administrator ? </label>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-label">Description</label>
                <input type="text" class="form_control " value="<?php echo $user->description; ?>" name="description" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label">Password Reset</label>
                <div class="password poR">
                    <input type="password" name="password" class="form_control">
                    <a type="button"><i class="fa fa-eye"></i></a> 
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/setting/users' ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="user_edit_submit">Update</button>
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

        let selectbox=new SelectBox(document.querySelector(".selectBox"));
        selectbox.init();

        let form=document.getElementById("user_edit_form");
        let validator=new FormValidate(form);

        document.getElementById("user_edit_submit").onclick=function(evt){
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