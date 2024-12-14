<div class="alldiv flex widget_title">
    <h3>Finance</h3>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.setting.finance'),array(
            "class"=>"flex",
            "id"=>"finance_edit_form"
        ));
    ?>
        <div class="form-width-1">
            <a href="#" class="btn bg-danger" >Reset Data</a>
        </div>
        <div class="form-width-3">
            <div class="form-group field-money" >
                <label class="form-label" >Start with Capital</label>
                <input type="text" value="<?php echo $finance['finance_capital'];?>" class="form_control field-check" name="finance_capital" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-3">

        </div>
        <div class="form-width-3">

        </div>
        <div class="form-width-3">
            <div class="form-group">
                <label class="form-label" >Close Accounting Books</label>
                <input type="date" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $finance['close_account_book'];?>" class="form_control field-check" name="close_account_book" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <button class="btn bg-primary" type="button" id="finance_edit_submit">Update</button>
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

        let form=document.getElementById("finance_edit_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("finance_edit_submit").onclick=function(evt){
            if(!lock){
                lock=true;
                validator.validate(
                    (params)=>{
                    //success
                    form.submit();
                    lock=false;
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