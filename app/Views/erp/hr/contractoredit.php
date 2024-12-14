<div class="alldiv flex widget_title">
    <h3>Update Contractor</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/hr/contractors' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.hr.contractoredit',$contractor_id),array(
            "class"=>"flex",
            "id"=>"contractor_edit_form"
        ));
    ?>
        <div class="form-width-2">
            <div class="form-group field-ajax" data-ajax-url="<?php echo url_to('erp.hr.ajaxcontractorcodeunique').'?id='.$contractor_id.'&' ;?>" >
                <label class="form-label">Code</label>
                <input type="text" name="con_code" value="<?php echo $contractor->con_code; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="<?php echo $contractor->name; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Contact Person</label>
                <input type="text" name="contact_person" value="<?php echo $contractor->contact_person; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-ajax " data-ajax-url="<?php echo url_to('erp.hr.ajaxcontractoremailunique').'?id='.$contractor_id.'&' ;?>">
                <label class="form-label">Email</label>
                <input type="text" name="email" value="<?php echo $contractor->email; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-ajax " data-ajax-url="<?php echo url_to('erp.hr.ajaxcontractorphoneunique').'?id='.$contractor_id.'&' ;?>">
                <label class="form-label">Phone 1</label>
                <input type="text" name="phone_1" value="<?php echo $contractor->phone_1; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group">
                <label class="form-label">Phone 2</label>
                <input type="text" name="phone_2" value="<?php echo $contractor->phone_2; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">GST No</label>
                <input type="text" name="gst_no" value="<?php echo $contractor->gst_no; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">PAN No</label>
                <input type="text" name="pan_no" value="<?php echo $contractor->pan_no; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Website</label>
                <input type="text" name="website" value="<?php echo $contractor->website; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Address</label>
                <input type="text" name="address" value="<?php echo $contractor->address; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">City</label>
                <input type="text" name="city" value="<?php echo $contractor->city; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">State</label>
                <input type="text" name="state" value="<?php echo $contractor->state; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Country</label>
                <input type="text" name="country" value="<?php echo $contractor->country; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Zipcode</label>
                <input type="text" name="zipcode" value="<?php echo $contractor->zipcode; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form_control field-check" ><?php echo $contractor->description; ?></textarea>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/hr/contractors' ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="contractor_edit_submit">Save</button>
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

        let form=document.getElementById("contractor_edit_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("contractor_edit_submit").onclick=function(evt){
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