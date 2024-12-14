<div class="alldiv flex widget_title">
    <h3>Product Planning Add</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.mrp.planningschedule'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
    echo form_open(url_to('erp.mrp.productplanningadding'), array(
        "class" => "flex",
        "id" => "planning_add_form"
    ));
    ?>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Product</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select product">select product</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="finished_good_id">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($product as $value) {
                    ?>
                        <li role="option" data-value="<?php echo $value['finished_good_id']; ?>"><?php echo $value['name']; ?></li>
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
            <label class="form-label">Status</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select status">select Status</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="status">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($planning_status as $key => $status) {
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
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Stock</label>
            <input type="text" name="stock" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Start date</label>
            <input type="date" class="form_control field-check" id="start_date" name="start_date" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Due date</label>
            <input type="date" class="form_control field-check" id="end_date" name="end_date" />
            <p class="error-text"></p>
        </div>
    </div>
    
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Price</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select Price">select Price</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="price_id">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    if(!empty($price_list)){
                        foreach ($price_list as $val) {
                            ?>
                        <li role="option" data-value="<?php echo $val['price_id']; ?>"><?php echo $val['name']." [".$val['amount']."]"; ?></li>
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
        <div class="form-group textRight">
            <a href="<?php echo url_to('erp.mrp.planningschedule'); ?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="planning_add_submit">Save</button>
        </div>
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

    let form = document.getElementById("planning_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("planning_add_submit").onclick = function(evt) {
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