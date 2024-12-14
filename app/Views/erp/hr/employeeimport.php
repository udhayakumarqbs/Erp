<div class="alldiv flex widget_title">
    <h3>Import Employee</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/hr/employees' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv import-section">
    <ol class="import-instructions">
        <li>Your <b>CSV</b> data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is <b>UTF-8</b> to avoid unnecessary encoding problems.</li>
        <li>If the column you are trying to import is date make sure that is formatted in format <b>Y-m-d (2022-02-11)</b>.</li>
        <li><b>Duplicate email</b> rows won't be imported.</li>
        <li><b>Duplicate code</b> rows won't be imported.</li>
        <li><b>Duplicate phone</b> rows won't be imported.</li>
    </ol>
    <div class="textRight download-template">
        <a href="<?php echo url_to('erp.hr.employeeimporttemplate');?>" target="_BLANK" class="btn bg-success">Download Template</a>
    </div>
    <div class="table_responsive">
        <table class="table">
            <thead class="thead">
                <tr>
                    <?php
                        foreach($columns as $key=>$col){ ?>
                        <th><?php echo $key ?> <?php
                            if($col['req']==true){ ?>
                            <span class="text-danger"> *</span>
                        <?php
                            }
                        ?></th>
                    <?php
                        }
                    ?>
                </tr>
            </thead>
            <tbody class="table-paint-area">
                <tr>
                    <?php
                        foreach($columns as $col){ ?>
                        <td><?php echo $col['sample'] ?></td>
                    <?php
                        }
                    ?>
                </tr>     
            </tbody>
        </table>
    </div>

    <?php
        echo form_open_multipart(url_to('erp.hr.employeeimport'),array(
            "class"=>"flex",
            "id"=>"employee_import_form"
        ));
    ?>
        
        <div class="form-width-3">
        <div class="form-group field-required ">
            <label class="form-label">Gender</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="select gender">select gender</div>
                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden"  class="selectBox_Value field-check" name="gender" value="" >
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
                    <input type="hidden" class="selectBox_Value field-check" name="emp_status" value="" >
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
                    <input type="hidden" class="selectBox_Value field-check" name="marital_status" value="" >
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
        <div class="form-group field-required">
            <label class="form-label">Designation</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="select designation">select designation</div>
                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="designation_id" value="" >
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
                <label class="form-label">Upload CSV file</label>
                <input type="file"accept=".csv" name="csvfile" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/hr/employees' ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="employee_import_submit">Import</button>
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

        document.querySelectorAll(".selectBox").forEach((item)=>{
            let selectbox=new SelectBox(item);
            selectbox.init();
            closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
        });

        let form=document.getElementById("employee_import_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("employee_import_submit").onclick=function(evt){
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