<div class="alldiv flex widget_title">
    <h3>Update Supplier</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.supplier.page'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
    echo form_open(base_url() . 'erp/supplier/supplier-edit/' . $supplier_id, array(
        "class" => "flex",
        "id" => "supplier_edit_form"
    ));
    ?>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Source</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select source">select source</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="supplier_source" value="<?php echo $supplier->source_id; ?>">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($supplier_sources as $source) {
                    ?>
                        <li role="option" data-value="<?php echo $source['source_id']; ?>"><?php echo $source['source_name']; ?></li>
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
            <input type="text" name="name" value="<?php echo $supplier->name; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-ajax" data-ajax-url="<?php echo base_url() . 'erp/supplier/ajax-code-unique?id=' . $supplier_id . '&'; ?>">
            <label class="form-label">Code</label>
            <input type="text" name="code" value="<?php echo $supplier->code; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Address</label>
            <input type="text" name="address" value="<?php echo $supplier->address; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Position</label>
            <input type="text" name="position" value="<?php echo $supplier->position; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">City</label>
            <input type="text" name="city" value="<?php echo $supplier->city; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-ajax" data-ajax-url="<?php echo base_url() . 'erp/supplier/supplier-save?id=' . $supplier_id . '&'; ?>">
            <label class="form-label">Email ID</label>
            <input type="text" name="email" value="<?php echo $supplier->email; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">State</label>
            <input type="text" name="state" value="<?php echo $supplier->state; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-phone">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="<?php echo $supplier->phone; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Country</label>
            <input type="text" name="country" value="<?php echo $supplier->country; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group ">
            <label class="form-label">Fax Number</label>
            <input type="text" name="fax_number" value="<?php echo $supplier->fax_number; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Zipcode</label>
            <input type="text" name="zipcode" value="<?php echo $supplier->zipcode; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group ">
            <label class="form-label">Office Number</label>
            <input type="text" name="office_number" value="<?php echo $supplier->office_number; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group  ">
            <label class="form-label">Website</label>
            <input type="text" name="website" value="<?php echo $supplier->website; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Company</label>
            <input type="text" name="company" value="<?php echo $supplier->company; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">GST</label>
            <input type="text" name="gst" value="<?php echo $supplier->gst; ?>" class="form_control field-check" />
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
                    <input type="hidden" name="groups" value="<?php echo $supplier->groups; ?>" class="multiSelectInput field-check">
                </div>
                <div role="comboBox" class="MultiselectBox_Container">
                    <?php
                    foreach ($supplier_groups as $group) {
                    ?>
                        <label class="multiBox_label"><input type="checkBox" data-value="<?php echo $group['group_id']; ?>"><?php echo $group['group_name']; ?></label>
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
            <textarea rows="3" name="payment_terms" class="form_control field-check"><?php echo $supplier->payment_terms; ?></textarea>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group ">
            <label class="form-label">Description</label>
            <textarea rows="3" name="description" class="form_control field-check"><?php echo $supplier->description; ?></textarea>
            <p class="error-text"></p>
        </div>
    </div>

    <!--CUSTOM FIELDS -->
    <input type="hidden" name="customfield_chkbx_counter" value="<?php echo $customfield_chkbx_counter; ?>" />
    <?php echo $customfields; ?>
    <!--CUSTOM FIELDS ENDS-->

    <div class="form-width-1">
        <div class="form-group textRight">
            <a href="<?= url_to('erp.supplier.page') ?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="supplier_edit_submit">Save</button>
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

    document.querySelectorAll(".multiSelectBox").forEach((item) => {
        let multiselectbox = new MultiSelectBox(item);
        multiselectbox.init();
        closer.register_shutdown(multiselectbox.shutdown, multiselectbox.get_container());
    });


    let form = document.getElementById("supplier_edit_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("supplier_edit_submit").onclick = function(evt) {
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
    $session = \Config\Services::session();
    if ($session->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alert.invoke_alert("<?php echo $session->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if ($session->getFlashdata("op_error")) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?php echo $session->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>