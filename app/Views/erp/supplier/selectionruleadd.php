<div class="alldiv flex widget_title">
    <h3>Create Selection Rule</h3>
    <div class="title_right">
        <a href="<?php echo base_url() . 'erp/supplier/selection-rules'; ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
    echo form_open(base_url() . 'erp/supplier/selection-rules-add', array(
        "class" => "flex",
        "id" => "selectionrule_add_form"
    ));
    ?>
    <div class="form-width-2">
        <div class="form-group field-ajax" data-ajax-url="<?php echo base_url() . 'erp/supplier/rules-name-unique?'; ?>">
            <label class="form-label">Rule Name</label>
            <input type="text" name="rule_name" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="flex">
            <div class="form-width-1">
                <h2>Segments</h2>
            </div>
            <?php
            foreach ($segments as $row) {
            ?>
                <div class="form-width-1">
                    <label class="form-label"><?php echo $row['segment_key']; ?></label>
                    <input type="hidden" value="<?php echo $row['segment_id']; ?>" name="segment_id_<?php echo $row['segment_id']; ?>" />
                </div>
                <div class="form-width-3">
                    <div class="form-group field-checked-any">
                        <div>
                            <label class="form-check-label"><input type="radio" class="field-check" name="above_below_<?php echo $row['segment_id']; ?>" value="1"> Above</label>
                            <label class="form-check-label"><input type="radio" class="field-check" name="above_below_<?php echo $row['segment_id']; ?>" value="0"> Below</label>
                        </div>
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-3">
                    <div class="form-group field-required">
                        <div class="selectBox poR">
                            <div class="selectBoxBtn flex">
                                <div class="textFlow" data-default="select value">select value</div>
                                <button class="close" type="button"><i class="fa fa-close"></i></button>
                                <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" class="selectBox_Value field-check" name="segment_value_idx_<?php echo $row['segment_id']; ?>" value="">
                            </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                                <?php
                                $segment_values = json_decode($row['segment_value'], true);
                                foreach ($segment_values as $key => $value) {
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
                <div class="form-width-3">
                    <div class="form-group">
                        <div>
                            <label class="form-check-label"><input type="checkbox" name="exclude_<?php echo $row['segment_id']; ?>" value="1" /> Exclude </label>
                        </div>
                        <p class="error-text"></p>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group field-required ">
            <label class="form-label">Description</label>
            <textarea rows="3" name="description" class="form_control field-check"></textarea>
            <p class="error-text"></p>
        </div>
    </div>

    <div class="form-width-1">
        <div class="form-group textRight">
            <a href="<?php echo base_url() . 'erp/supplier/selection-rules'; ?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="selectionrule_add_submit">Save</button>
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

    let form = document.getElementById("selectionrule_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("selectionrule_add_submit").onclick = function(evt) {
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