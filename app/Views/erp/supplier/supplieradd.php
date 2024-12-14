<div class="alldiv flex widget_title">
    <h3>Create Supplier</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.supplier.page') ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?= form_open(url_to('erp.supplier.add'), [
        'class' => 'flex',
        'id'    => 'supplier_add_form'
    ]); ?>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Source</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select source">select source</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="supplier_source" value="">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($supplier_sources as $source) {
                    ?>
                        <li role="option" data-value="<?= $source['source_id']; ?>"><?= $source['source_name']; ?></li>
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
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-ajax" data-ajax-url="<?= url_to('erp.supplier.save')."?"; ?>">
            <label class="form-label">Code</label>
            <input type="text" name="code" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Position</label>
            <input type="text" name="position" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-ajax" data-ajax-url="<?= url_to('erp.supplier.save') . '?'; ?>">
            <label class="form-label">Email ID</label>
            <input type="text" name="email" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">State</label>
            <input type="text" name="state" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-phone">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group ">
            <label class="form-label">Fax Number</label>
            <input type="text" name="fax_number" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Zipcode</label>
            <input type="text" name="zipcode" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group ">
            <label class="form-label">Office Number</label>
            <input type="text" name="office_number" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group  ">
            <label class="form-label">Website</label>
            <input type="text" name="website" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Company</label>
            <input type="text" name="company" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">GST</label>
            <input type="text" name="gst" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Group</label>
            <div class="multiSelectBox poR">
                <div class="multiSelectBoxBtn">
                    <div class="Multi_InputContainer" data-default="Select Group">Select Group</div>
                    <button class="drops2" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" name="groups" value="" class="multiSelectInput field-check">
                </div>
                <div role="comboBox" class="MultiselectBox_Container">
                    <?php
                    foreach ($supplier_groups as $group) {
                    ?>
                        <label class="multiBox_label"><input type="checkBox" data-value="<?= $group['group_id']; ?>"><?= $group['group_name']; ?></label>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group ">
            <label class="form-label">Payment Terms</label>
            <textarea rows="3" name="payment_terms" class="form_control field-check"></textarea>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group ">
            <label class="form-label">Description</label>
            <textarea rows="3" name="description" class="form_control field-check"></textarea>
            <p class="error-text"></p>
        </div>
    </div>

    <!--CUSTOM FIELDS -->
    <input type="hidden" name="customfield_chkbx_counter" value="<?= $customfield_chkbx_counter; ?>" />
    <?= $customfields; ?>
    <!--CUSTOM FIELDS ENDS-->

    <div class="form-width-1">
        <div class="form-group textRight">
            <a href="<?=url_to('erp.supplier.page'); ?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="supplier_add_submit">Save</button>
        </div>
    </div>
    </form>
</div>








<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?= base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?= base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?= base_url() . 'assets/js/erp.js'; ?>"></script>
<script type="text/javascript">
    let closer = new WindowCloser();
    closer.init();

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


    let form = document.getElementById("supplier_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("supplier_add_submit").onclick = function(evt) {
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
    if (session()->setFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?= session()->flashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->setFlashdata("op_error")) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?= session()->setFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>