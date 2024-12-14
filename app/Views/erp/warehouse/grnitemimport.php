<div class="alldiv flex widget_title">
    <h3>Import GRN Items</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/warehouse/grns' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv import-section">
    <ol class="import-instructions">
        <li>Download <b>CSV</b> file and enter data in right columns. Also make sure that your file is <b>UTF-8</b> to avoid unnecessary encoding problems.</li>
        <li>Manufactured Date format <b>Y-m-d (2022-02-11)</b>.</li>
        <li>Don't Edit <b>Related To</b> and <b>Related ID</b> columns.</li>
        <li><b>Duplicate SKU</b> rows won't be imported.</li>
    </ol>
    <div class="textRight download-template">
        <a href="<?php echo base_url().'erp/warehouse/grnitemsimporttemplate/'.$grn_id;?>" target="_BLANK" class="btn bg-success">Download CSV</a>
    </div>

    <?php
        echo form_open_multipart(base_url().'erp/warehouse/grnitems_import/'.$grn_id,array(
            "class"=>"flex",
            "id"=>"grnitem_import_form"
        ));
    ?>
        <input type="hidden" name="data_posted" value="1" />
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Upload CSV file</label>
                <input type="file"accept=".csv" name="csvfile" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/crm/leads' ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="grnitem_import_submit">Import</button>
            </div>
        </div>
    <?php
        echo form_close();
    ?>
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

        let form=document.getElementById("grnitem_import_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("grnitem_import_submit").onclick=function(evt){
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
            if($this->session->flashdata("op_success")){ ?>
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo $this->session->flashdata('op_success'); ?>","success");
        <?php
            }else if($this->session->flashdata("op_error")){ ?>
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo $this->session->flashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>