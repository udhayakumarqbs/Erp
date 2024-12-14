<div class="alldiv flex widget_title">
    <h3>Update Attendance</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/hr/attendance' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv import-section">
    <ol class="import-instructions">
        <li>Download excel file and fill the columns</li>
        <li>If the column you are trying to import is date make sure that is formatted in format <b>Y-m-d (2022-02-11)</b>.</li>
        <li>Don't <b>edit</b> the excel as it may leads to incorrect data .</li>
        <li><b>Invalid rows</b> wont' be imported.</li>
    </ol>
    <div class="flex download-template">
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Update Date</label>
                <input id="attend_update_date" max="<?php echo date('Y-m-d',time()-24*60*60); ?>" type="date" value="<?php echo date('Y-m-d',time()-24*60*60); ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group">
                <label class="form-label"></label>
                <a id="attend_update_date_l" href="<?php echo url_to('erp.hr.attendanceupdatetemplate').'?date='.date('Y-m-d',time()-24*60*60);?>" download class="btn bg-success">Download xlsx</a>
            </div>
        </div>
    </div>

    <?php
        echo form_open_multipart(url_to('erp.hr.attendanceedit'),array(
            "class"=>"flex",
            "id"=>"attendance_edit_form"
        ));
    ?>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Update Date</label>
                <input type="date" id="attend_update_date_i" value="<?php echo date('Y-m-d',time()-24*60*60); ?>" readonly name="rec_date" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Upload Excel file</label>
                <input type="file" name="excelfile" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/hr/attendance' ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="attendance_edit_submit">Import</button>
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

        document.getElementById("attend_update_date").onchange=(evt)=>{
            let date=evt.target.value;
            document.getElementById("attend_update_date_i").value=date;
            let a_tag=document.getElementById("attend_update_date_l");
            let href=a_tag.getAttribute("href");
            let newurl=href.substring(0,href.indexOf("=")+1)+date;
            a_tag.setAttribute("href",newurl);
        };

        let form=document.getElementById("attendance_edit_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("attendance_edit_submit").onclick=function(evt){
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