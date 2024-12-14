<div class="alldiv flex widget_title">
    <h3>Update SMTP</h3>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.setting.smtp'),array(
            "class"=>"flex",
            "id"=>"smtp_edit_form"
        ));
    ?>
        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-label" >Mail Engine</label>
                <div>
                    <label class="form-check-label" ><input type="radio" name="mail_engine" value="PHPMailer" <?php
                        if($smtp['mail_engine']=="PHPMailer"){
                            echo "checked";
                        }
                    ?> > PHPMailer</label>
                    <label class="form-check-label" ><input type="radio" name="mail_engine" value="CodeIgniter" <?php
                        if($smtp['mail_engine']=="CodeIgniter"){
                            echo "checked";
                        }
                    ?> > CodeIgniter</label>
                    <label class="form-check-label" ><input type="radio" name="mail_engine" value="Sendgrid" <?php
                        if($smtp['mail_engine']=="Sendgrid"){
                            echo "checked";
                        }
                    ?> > Sendgrid</label>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-label" >Email Encryption</label>
                <div>
                    <label class="form-check-label" ><input type="radio" name="email_encryption" value="none"  <?php
                        if($smtp['email_encryption']=="none"){
                            echo "checked";
                        }
                    ?> > None</label>
                    <label class="form-check-label" ><input type="radio" name="email_encryption" value="tls"  <?php
                        if($smtp['email_encryption']=="tls"){
                            echo "checked";
                        }
                    ?> > TLS</label>
                    <label class="form-check-label" ><input type="radio" name="email_encryption" value="ssl"  <?php
                        if($smtp['email_encryption']=="ssl"){
                            echo "checked";
                        }
                    ?> > SSL</label>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group">
                <label class="form-label" >SMTP Host</label>
                <input type="text" name="smtp_host" value="<?php echo $smtp['smtp_host'];?>"  class="form_control" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group">
                <label class="form-label" >Port</label>
                <input type="number" name="smtp_port" value="<?php echo $smtp['smtp_port'];?>" class="form_control" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group">
                <label class="form-label" >SMTP username</label>
                <input type="email" name="smtp_username" value="<?php echo $smtp['smtp_username'];?>"  class="form_control" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group">
                <label class="form-label" >Password</label>
                <input type="password" name="smtp_password"  class="form_control" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label" >BCC All Mails To ( , separated)</label>
                <input type="text" name="bcc_list" value="<?php echo $smtp['bcc_list'];?>" class="form_control" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label" >CC All Mails To ( , separated)</label>
                <input type="text" name="cc_list" value="<?php echo $smtp['cc_list'];?>" class="form_control" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label" >Track Mail Quota</label>
                <input type="number" name="track_quota" value="<?php echo $smtp['track_quota'];?>" class="form_control" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <button class="btn bg-primary" type="button" id="smtp_edit_submit">Update</button>
            </div>
        </div>
        </form>
</div>
<div class="alldiv flex">
    <div class="form-width-1">
        <div class="form-group">
        <label class="form-label" >Send Test Email</label>
        <p >Send test email to make sure that your SMTP settings is set correctly.</p>
        <?php
            echo form_open(url_to("erp.setting.sendtestemail"),array(
            ));
        ?>
            <input type="email" required name="email_to" class="form_control" />
            <div class="form-group textRight" >
                <button class="btn bg-success" type="submit" >Test</button>
            </div>
        <?php
            echo form_close();
        ?>
        </div>
    </div>
</div>









<!--SCRIPT WORKS -->
</div>
    </main>
    <script src="<?php echo base_url().'assets/js/jquery.min.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/script.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/erp.js' ;?>" ></script>
    <script type="text/javascript">

        let form=document.getElementById("smtp_edit_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("smtp_edit_submit").onclick=function(evt){
            if(!lock){
                lock=true;
                validator.validate(
                    (params)=>{
                    //success
                    form.submit();
                },
                (params)=>{
                    //error
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