<div class="alldiv flex widget_title">
    <h3>Import Supplier</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.supplier.page') ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv import-section">
    <ol class="import-instructions">
        <li>Your <b>CSV</b> data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is <b>UTF-8</b> to avoid unnecessary encoding problems.</li>
        <li>If the column you are trying to import is date make sure that is formatted in format <b>Y-m-d (2022-02-11)</b>.</li>
        <li><b>Duplicate email</b> rows won't be imported.</li>
        <li><b>Duplicate supplier code</b> rows won't be imported.</li>
    </ol>
    <div class="textRight download-template">
        <a href="<?php echo base_url() . 'erp/supplier/import-template'; ?>" target="_BLANK" class="btn bg-success">Download Template</a>
    </div>
    <div class="table_responsive">
        <table class="table">
            <thead class="thead">
                <tr>
                    <?php
                    foreach ($columns as $key => $col) { ?>
                        <th><?php echo $key ?> <?php
                                                if (isset($col['req'])) { ?>
                                <span class="text-danger"> *</span>
                            <?php
                                                }
                            ?>
                        </th>
                    <?php
                    }
                    ?>
                </tr>
            </thead>
            <tbody class="table-paint-area">
                <tr>
                    <?php
                    foreach ($columns as $col) { ?>
                        <td><?php echo $col['sample'] ?></td>
                    <?php
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </div>

    <?php
    echo form_open_multipart(url_to('erp.supplier.importpost'), array(
        "class" => "flex",
        "id" => "supplier_import_form"
    ));
    ?>

    <!--CUSTOM FIELDS -->
    <input type="hidden" name="customfield_chkbx_counter" value="<?php echo $customfield_chkbx_counter; ?>" />
    <?php echo $customfields; ?>
    <!--CUSTOM FIELDS ENDS-->

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
                        <label class="multiBox_label"><input type="checkBox" data-value="<?php echo $group['group_id']; ?>"><?php echo $group['group_name']; ?></label>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Upload CSV file</label>
            <input type="file" accept=".csv" name="csvfile" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight">
            <a href="<?php echo url_to('erp.supplier.page') ?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="supplier_import_submit">Import</button>
        </div>
    </div>
    <?php
    echo form_close();
    ?>
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

    let form = document.getElementById("supplier_import_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("supplier_import_submit").onclick = function(evt) {
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
        alerts.invoke_alert("<?php echo $session->getFlashdata('op_success'); ?>", "success");
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