<div class="alldiv flex widget_title">
    <h3>Insert Attendance</h3>
    <div class="title_right">
        <a href="<?php echo base_url() . 'erp/hr/attendance'; ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
    echo form_open_multipart(url_to('erp.hr.attendanceadding'), array(
        "class" => "flex",
        "id" => "attendance_add_form"
    ));
    ?>
    <div class="form-width-3">
        <div class="form-group field-required ">
            <label class="form-label">Date</label>
            <input type="date" name="rec_date" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-3">
        <div class="form-group ">
            <label class="form-label">Employee</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.hr.ajaxfetchemployees'); ?>">
                <div class="ajaxselectBoxBtn flex">
                    <div class="textFlow" data-default="select employee">select employee</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="employee_id" value="">
                </div>
                <div class="ajaxselectBox_Container alldiv">
                    <input type="text" class="ajaxselectBox_Search form_control" />
                    <ul role="listbox">

                    </ul>
                </div>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-3">
        <div class="form-group field-required ">
            <label class="form-label">Work hours</label>
            <input type="text" name="work_hours" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-3">
        <div class="form-group field-required ">
            <label class="form-label">OT Hours</label>
            <input type="text" name="ot_hours" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight">
            <a href="<?php echo base_url() . 'erp/hr/attendance'; ?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="attendance_add_submit">Save</button>
        </div>
    </div>
    </form>
</div>








<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script type="text/javascript">
    let closer = new WindowCloser();
    closer.init();

    document.querySelectorAll(".selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        closer.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    let form = document.getElementById("attendance_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("attendance_add_submit").onclick = function(evt) {
        if (!lock) {
            lock = true;
            validator.validate(
                (params) => {
                    form.submit();
                    lock = false;
                },
                (params) => {
                    lock = false;
                }, {});
        }
    }

    <?php
    if (session()->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>