<div class="alldiv flex widget_title">
    <h3>Update Stock</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.warehouse.managestock') ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <form action="<?= url_to('erp.warehouse.managestock.edit.post', $pack_unit_id) ?>" class="flex" id="stock_edit_form">
        <div class="form-width-2">
            <div class="form-group field-ajax" data-ajax-url="<?= url_to('erp.ajax.managestock') .'?id='.$pack_unit_id.'&' ?>">
                <label class="form-label">SKU</label>
                <input type="text" name="sku" value="<?php echo $stock->sku; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Bin Name</label>
                <input type="text" name="bin_name" value="<?php echo $stock->bin_name; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Manufactured Date</label>
                <input type="date" name="mfg_date" value="<?php echo $stock->mfg_date; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Batch No</label>
                <input type="text" name="batch_no" value="<?php echo $stock->batch_no; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Lot No</label>
                <input type="text" name="lot_no" value="<?php echo $stock->lot_no; ?>" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?= url_to('erp.warehouse.managestock');?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="stock_edit_submit">Update</button>
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

    let form = document.getElementById("stock_edit_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("stock_edit_submit").onclick = function(evt) {
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
        alerts.invoke_alert("<?=session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?=session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>