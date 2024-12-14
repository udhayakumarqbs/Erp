<div class="alldiv flex widget_title">
    <h3>Service Add</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.service.service'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
    echo form_open(url_to('erp.service.serviceadd'), array(
        "class" => "flex",
        "id" => "service_add_form"
    ));
    ?>
    <div class="form-width-2">
        <div class="form-group  field-ajax" data-ajax-url="<?php echo url_to('erp.service.ajaxservicecodeunique') . '?'; ?>">
            <label class="form-label">Code</label>
            <input type="text" name="code" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Service Name</label>
            <input type="text" name="name" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Priority</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select priority">select priority</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="priority" value="">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($service_priority as $key => $value) {
                    ?>
                        <li role="option" data-value="<?php echo $key; ?>"><?php echo $value; ?></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <p class="error-text"></p>
        </div>
    </div>

    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Assigned to</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.crm.ajaxFetchUsers'); ?>">
                <div class="ajaxselectBoxBtn flex">
                    <div class="textFlow" data-default="select followers">select assigned</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="assigned_to" value="">
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
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Lobers</label>
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
    <div class="form-width-1">
        <div class="form-group ">
            <label class="form-label">Description</label>
            <textarea rows="3" name="service_desc" class="form_control field-check"></textarea>
            <p class="error-text"></p>
        </div>
    </div>


    <!--CUSTOM FIELDS ENDS-->

    <div class="form-width-1">
        <div class="form-group textRight">
            <a href="<?php echo url_to('erp.inventory.rawmaterials'); ?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="service_add_submit">Save</button>
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

    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    document.querySelectorAll(".selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        closer.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    document.querySelectorAll(".multiSelectBox").forEach((item) => {
        let multiselectbox = new MultiSelectBox(item);
        multiselectbox.init();
        closer.register_shutdown(multiselectbox.shutdown, multiselectbox.get_container());
    });

    let form = document.getElementById("service_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("service_add_submit").onclick = function(evt) {
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
    if (session()->getflashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getflashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getflashdata("op_error")) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?php echo session()->getflashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>