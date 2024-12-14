<div class="alldiv flex widget_title">
    <h3>Update Equipment</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.assets'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <form action="<?= url_to('erp.equipment.edit.post', $equip_id) ?>" method="post" class="flex" id="equipment_edit_form">
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="<?php echo $equipment->name; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-ajax" data-ajax-url="<?php echo url_to('erp.equipment.ajaxEquipCodeUnique').'?id=' . $equip_id . '&'; ?>">
                <label class="form-label">Code</label>
                <input type="text" name="code" value="<?php echo $equipment->code; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Model</label>
                <input type="text" name="model" value="<?php echo $equipment->model; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Maker</label>
                <input type="text" name="maker" value="<?php echo $equipment->maker; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Bought Date</label>
                <input type="date" name="bought_date" value="<?php echo $equipment->bought_date; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group">
                <label class="form-label">Age</label>
                <input type="text" name="age" value="<?php echo $equipment->age; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-checked-any">
                <div>
                    <label class="form-check-label"><input class="field-check" <?php
                                                                                if ($equipment->work_type == "Automatic") {
                                                                                    echo "checked";
                                                                                }
                                                                                ?> value="Automatic" type="radio" name="work_type" /> Automatic </label>
                    <label class="form-check-label"><input class="field-check" <?php
                                                                                if ($equipment->work_type == "Manual") {
                                                                                    echo "checked";
                                                                                }
                                                                                ?> value="Manual" type="radio" name="work_type" /> Manual </label>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-checked-any">
                <div>
                    <label class="form-check-label"><input class="field-check" <?php
                                                                                if ($equipment->consump_type == "Electric") {
                                                                                    echo "checked";
                                                                                }
                                                                                ?> value="Electric" type="radio" name="consump_type" /> Electric </label>
                    <label class="form-check-label"><input class="field-check" <?php
                                                                                if ($equipment->consump_type == "Fuel") {
                                                                                    echo "checked";
                                                                                }
                                                                                ?> value="Fuel" type="radio" name="consump_type" /> Fuel </label>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Consumption</label>
                <input type="text" name="consumption" value="<?php echo $equipment->consumption; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Status</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select status">select status</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" name="equip_status" value="<?php echo $equipment->status; ?>">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($equip_status as $key => $status) {
                        ?>
                            <li role="option" data-value="<?php echo $key; ?>"><?php echo $status; ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Description</label>
                <textarea rows="3" name="description" class="form_control field-check"><?php echo $equipment->description; ?></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo url_to('erp.assets'); ?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="equipment_edit_submit">Save</button>
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

    let form = document.getElementById("equipment_edit_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("equipment_edit_submit").onclick = function(evt) {
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