<div class="alldiv flex widget_title">
    <h3>Update Workgroup</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.project.workgroups'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
    echo form_open(url_to('erp.project.workgroupedit', $wgroup_id), array(
        "class" => "flex",
        "id" => "workgroup_add_form"
    ));
    ?>
    <div class="form-width-2">
        <div class="form-group field-ajax" data-ajax-url="<?php echo base_url() . 'erp/project/workgroups-name-unique?id=' . $wgroup_id . '&'; ?>">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="<?php echo $workgroup->name; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-number ">
            <label class="form-label">Approx Days</label>
            <input type="text" name="approx_days" value="<?php echo $workgroup->approx_days; ?>" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Equipments</label>
            <div class="multiSelectBox poR">
                <div class="multiSelectBoxBtn">
                    <div class="Multi_InputContainer" data-default="Select equipment">Select equipment</div>
                    <button class="drops2" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" name="equipments" value="<?php echo $workgroup->equipments; ?>" class="multiSelectInput field-check">
                </div>
                <div role="comboBox" class="MultiselectBox_Container">
                    <?php
                    foreach ($equipments as $row) {
                    ?>
                        <label class="multiBox_label"><input type="checkBox" data-value="<?php echo $row['equip_id']; ?>"><?php echo $row['equip_name']; ?></label>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group field-required ">
            <label class="form-label">Description</label>
            <textarea rows="3" name="description" class="form_control field-check"><?php echo $workgroup->description; ?></textarea>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="widget_title">
            <h3>Add Raw Materials</h3>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group" id="wgroup_item">
            <label class="form-label">Product</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.procurement.ajaxfetchrawmaterials'); ?>">
                <div class="ajaxselectBoxBtn flex">
                    <div class="textFlow" data-default="select product">select product</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" value="">
                </div>
                <div class="ajaxselectBox_Container alldiv">
                    <input type="text" class="ajaxselectBox_Search form_control" />
                    <ul role="listbox">

                    </ul>
                </div>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group">
            <label class="form-label">Quantity</label>
            <input type="text" id="wgroup_item_qty" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight">
            <label class="form-label"></label>
            <button class="btn outline-primary" type="button" id="add_item_btn">Add Item</button>
        </div>
    </div>
    <div class="form-width-1">
        <table class="table">
            <thead>
                <th>SNo</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Action</th>
            </thead>
            <tbody id="wgroup_items_holder">
                <?php
                $sno = 1;
                foreach ($items as $row) {
                ?>
                    <tr>
                        <td><?php echo $sno; ?></td>
                        <td><span><?php echo $row['product']; ?></span><input type="hidden" name="related_id[<?php echo $sno; ?>]" value="<?php echo $row['related_id']; ?>" /></td>
                        <td><span><?php echo $row['qty']; ?></span><input type="hidden" name="qty[<?php echo $row['related_id']; ?>]" value="<?php echo $row['qty']; ?>" /></td>
                        <td><button class="btn bg-danger wgroup-item-remover "><i class="fa fa-trash"></i></button></td>
                    </tr>
                <?php
                    $sno++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight">
            <a href="<?php echo url_to('erp.project.workgroups'); ?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="workgroup_add_submit">Update</button>
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
    let alert = new ModalAlert();
    let closer = new WindowCloser();
    closer.init();

    document.querySelectorAll(".multiSelectBox").forEach((item) => {
        let multiselectbox = new MultiSelectBox(item);
        multiselectbox.init();
        closer.register_shutdown(multiselectbox.shutdown, multiselectbox.get_container());
    });

    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    let workgroup_item = document.getElementById("wgroup_item");
    let workgroup_qty = document.getElementById("wgroup_item_qty");
    let workgroup_item_holder = document.getElementById("wgroup_items_holder");
    let sno = parseInt("<?php echo $sno++; ?>");

    document.getElementById("add_item_btn").onclick = (evt) => {
        let item_id = workgroup_item.querySelector(".ajaxselectBox_Value").value;
        if (item_id === null || item_id === undefined || item_id === "") {
            alert.invoke_alert("Select the raw material", "error");
            return;
        }
        let item_name = workgroup_item.querySelector(".textFlow").textContent;
        let qty = parseInt(workgroup_qty.value);
        if (isNaN(qty) || qty <= 0) {
            alert.invoke_alert("Invalid quantity", "error");
            return;
        }
        let dup = false;
        let items = workgroup_item_holder.querySelectorAll("tr td:nth-child(2) span");
        for (let i = 0; i < items.length; i++) {
            if (items[i].textContent === item_name) {
                dup = true;
                break;
            }
        }
        if (dup) {
            alert.invoke_alert("Duplicate item not allowed", "error");
            return;
        }
        let tr = document.createElement("tr");
        let td = `<td>` + sno + `</td>`;
        sno++;
        td += `<td><span>` + item_name + `</span><input type="hidden" name="related_id[` + sno + `]" value="` + item_id + `" /></td>`;
        td += `<td><span>` + qty + `</span><input type="hidden" name="qty[` + item_id + `]" value="` + qty + `" /></td>`;
        td += `<td><button class="btn bg-danger wgroup-item-remover " ><i class="fa fa-trash"></i></button></td>`;
        tr.innerHTML = td;
        workgroup_item_holder.append(tr);
    };

    workgroup_item_holder.onclick = (evt) => {
        let target = evt.target;
        workgroup_item_holder.querySelectorAll(".wgroup-item-remover").forEach((item) => {
            if (item.contains(target)) {
                item.parentElement.parentElement.remove();
            }
        });
    };

    let form = document.getElementById("workgroup_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("workgroup_add_submit").onclick = function(evt) {
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
        alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>