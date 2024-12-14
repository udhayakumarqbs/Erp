<div class="alldiv flex widget_title">
    <h3>Create Warehouse</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.warehouses') ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <form action="<?= url_to('erp.warehouse.add') ?>" method="POST" class="flex" id="warehouse_add_form">
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form_control field-check" />
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
                <label class="form-label">City</label>
                <input type="text" name="city" class="form_control field-check" />
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
            <div class="form-group field-required ">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form_control field-check" />
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
        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-label"></label>
                <div>
                    <label class="form-check-label"><input value="1" id="checker_to_display" type="checkbox" name="has_bins" class="field-check" /> Enable Bin Management </label>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2 on-check-display">
            <div class="form-group field-number field-no-validate ">
                <label class="form-label">Aisle Count</label>
                <input type="text" name="aisle_count" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2 on-check-display">
            <div class="form-group field-number field-no-validate">
                <label class="form-label">Racks per Aisle</label>
                <input type="text" name="racks_per_aisle" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2 on-check-display">
            <div class="form-group field-number field-no-validate">
                <label class="form-label">Shelves per Rack</label>
                <input type="text" name="shelf_per_rack" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2 on-check-display">
            <div class="form-group field-number field-no-validate">
                <label class="form-label">Bins per Shelf</label>
                <input type="text" name="bins_per_shelf" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea rows="3" name="description" class="form_control field-check"></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?= url_to('erp.warehouses') ?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="warehouse_add_submit">Save</button>
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

    document.querySelectorAll(".on-check-display").forEach((item) => {
        item.style.display = "none";
        item.querySelector(".form-group").classList.add("field-no-validate");
    });

    document.getElementById("checker_to_display").onclick = (evt) => {
        if (evt.target.checked) {
            document.querySelectorAll(".on-check-display").forEach((item) => {
                item.style.display = "block";
                item.querySelector(".form-group").classList.remove("field-no-validate");
            });
        } else {
            document.querySelectorAll(".on-check-display").forEach((item) => {
                item.style.display = "none";
                item.querySelector(".form-group").classList.add("field-no-validate");
            });
        }
    }

    let form = document.getElementById("warehouse_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("warehouse_add_submit").onclick = function(evt) {
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
        alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
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