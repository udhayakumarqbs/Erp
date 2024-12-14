<div class="alldiv flex widget_title">
    <h3>Add Operation</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.mrp.operation.list'); ?>" class="btn bg-success"><i class="fa fa-reply"></i>
            Back </a>
    </div>
</div>
<div class="alldiv">
    <form action="<?= url_to('erp.mrp.operation.add') ?>" method="POST" class="flex" id="stock_add_form">
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Operation Name</label>
                <input type="text" class="form_control" name="operation_name">
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Default Workstation</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select priority">Select Workstation</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" name="workstation_id">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        if (!empty($workstations)) {
                            foreach ($workstations as $value) {
                                ?>
                                <li role="option" data-value="<?php echo $value['id']; ?>">
                                    <?php echo $value['name']; ?>
                                </li>
                                <?php
                            }
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
                <textarea name="description" class="form_control"></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <input type="checkbox" class="dt-checkbox-child" name="is_corrective_operation" value="1">
            <label for="is_corrective_operation">is corrective operation ?</label>
        </div>


        <div class="form-width-1 mt-5">
            <div class="form-group textRight">
                <a href="<?= url_to('erp.mrp.operation.list'); ?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary">Save</button>
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

    $(document).ready(function () {

        let closer = new WindowCloser();
        closer.init();

        let base_url = "<?php echo base_url(); ?>";

        document.querySelectorAll(".selectBox").forEach((item) => {
            let selectbox = new SelectBox(item);
            selectbox.init();
            closer.register_shutdown(selectbox.shutdown, selectbox.get_container());
        });
    })



    <?php
    if (session()->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?= session()->getFlashdata('op_success'); ?>", "success");
        <?php
    } else if (session()->getFlashdata("op_error")) { ?>
            let alert = new ModalAlert();
            alert.invoke_alert("<?= session()->getFlashdata('op_error'); ?>", "error");
        <?php
    }
    ?>
</script>
</body>

</html>