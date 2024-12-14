<div class="alldiv flex widget_title">
    <h3>Update RFQ</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/procurement/rfq' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(base_url().'erp/procurement/rfqedit/'.$rfq_id,array(
            "class"=>"flex",
            "id"=>"rfq_edit_form"
        ));
    ?>
        <div class="form-width-2">
            <div class="form-group field-ajax " data-ajax-url="<?php echo base_url().'erp/procurement/ajax_rfq_code_unique?id='.$rfq_id.'&' ;?>">
                <label class="form-label">Code</label>
                <input type="text" value="<?php echo $rfq->rfq_code; ?>" name="rfq_code" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Expiry Date</label>
                <input type="date" name="expiry_date" value="<?php echo $rfq->expiry_date; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Terms and Condition</label>
                <textarea rows="3" name="terms_condition" class="form_control field-check" ><?php echo $rfq->terms_condition; ?></textarea>
                <p class="error-text" ></p>
            </div>
        </div>

        <!--CUSTOM FIELDS -->
            <input type="hidden" name="customfield_chkbx_counter" value="<?php echo $customfield_chkbx_counter; ?>" />
            <?php echo $customfields; ?>
        <!--CUSTOM FIELDS ENDS-->

        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/procurement/rfq' ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="rfq_edit_submit">Save</button>
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

        let form=document.getElementById("rfq_edit_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("rfq_edit_submit").onclick=function(evt){
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
        let alert=new ModalAlert();

        <?php
            if(session()->getFlashdata("op_success")){ ?>
                alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getFlashdata("op_error")){ ?>
                alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>