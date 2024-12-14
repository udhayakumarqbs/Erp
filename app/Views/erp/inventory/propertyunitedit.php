<div class="alldiv flex widget_title">
    <h3>Update Property Unit</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.inventory.property'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
    echo form_open(url_to('erp.inventory.propertyunitedit', $property_id, $prop_unit_id), array(
        "class" => "flex",
        "id" => "propertyunit_edit_form"
    ));
    ?>
    <div class="form-width-2">
        <div class="form-group field-ajax " data-ajax-url="<?php echo url_to('erp.inventory.ajaxpropertyunitnameunique') . '?id=' . $prop_unit_id . '&'; ?>">
            <label class="form-label">Name</label>
            <input type="text" name="unit_name" value="<?php echo $propertyunit->unit_name; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group ">
            <label class="form-label"> Floor No</label>
            <input type="text" name="floor_no" value="<?php echo $propertyunit->floor_no; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group ">
            <label class="form-label">Area sqft</label>
            <input type="text" name="area_sqft" value="<?php echo $propertyunit->area_sqft; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group ">
            <label class="form-label">Direction</label>
            <input type="text" name="direction" value="<?php echo $propertyunit->direction; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-money ">
            <label class="form-label">Price</label>
            <input type="text" name="price" value="<?php echo $propertyunit->price; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Tax1</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select tax">select tax</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="tax1" value="<?php echo $propertyunit->tax1; ?>">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($taxes as $tax) {
                    ?>
                        <li role="option" data-value="<?php echo $tax['tax_id']; ?>"><?php echo $tax['tax_name']; ?></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group ">
            <label class="form-label">Tax2</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select tax">select tax</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="tax2" value="<?php echo $propertyunit->tax2; ?>">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($taxes as $tax) {
                    ?>
                        <li role="option" data-value="<?php echo $tax['tax_id']; ?>"><?php echo $tax['tax_name']; ?></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group ">
            <label class="form-label">Description</label>
            <textarea rows="3" name="description" class="form_control field-check"><?php echo $propertyunit->description; ?></textarea>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight">
            <a href="<?php echo url_to('erp.inventory.property'); ?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="propertyunit_edit_submit">Update</button>
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

    let form = document.getElementById("propertyunit_edit_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("propertyunit_edit_submit").onclick = function(evt) {
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