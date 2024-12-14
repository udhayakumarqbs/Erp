<div class="alldiv flex widget_title">
    <h3>Update GRN</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.warehouse.grn'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
    <div class="alldiv">
        <form action="<?= url_to('erp.warehouse.grnupdate.post',$grn_id) ?>" method="POST"  class="flex" id="grn_update_form">
            <div class="form-width-1">
                <h3>Order Items</h3>
                <div class="table_responsive">
                    <table class="table">
                        <thead>
                            <th>SNo</th>
                            <th>Product</th>
                            <th>Ordered Qty</th>
                            <th>Received Qty</th>
                            <th>Returned Qty</th>
                        </thead>
                        <tbody>
                            <?php
                            $index = 1;
                            foreach ($order_items as $row) {
                            ?>
                                <tr>
                                    <td><?php echo $index; ?></td>
                                    <td><?php echo $row['product']; ?></td>
                                    <td><span class="ordered-qty"><?php echo $row['quantity']; ?></span></td>
                                    <td>
                                        <div class="form-group field-number">
                                            <input type="text" name="recv_qty_<?php echo $row['order_item_id']; ?>" class="form_control field-check recv-qty-input" />
                                            <p class="error-text"></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group field-number">
                                            <input class="form_control field-check ret-qty-input" type="text" name="ret_qty_<?php echo $row['order_item_id']; ?>" readonly />
                                            <p class="error-text"></p>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                $index++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-width-2">
                <div class="form-group field-required ">
                    <label class="form-label">Delivered On</label>
                    <input type="date" name="delivered_on" class="form_control field-check" />
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group">
                    <label class="form-label">Remarks</label>
                    <textarea rows="3" name="remarks" class="form_control field-check"></textarea>
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group textRight">
                    <a href="<?= url_to('erp.warehouse.grn'); ?>" class="btn outline-secondary">Cancel</a>
                    <button class="btn bg-primary" type="button" id="grn_update_submit">Update</button>
                </div>
            </div>
        </form>
    </div>

</div>








<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script type="text/javascript">
    let alert = new ModalAlert();

    document.querySelectorAll(".recv-qty-input").forEach((item) => {
        item.addEventListener('keyup', (evt) => {
            let recv_qty = parseInt(item.value);
            if (recv_qty !== null && recv_qty !== undefined && !isNaN(recv_qty)) {
                let ordered_qty = parseInt(item.parentElement.parentElement.previousElementSibling.querySelector(".ordered-qty").textContent);
                let ret_qty = ordered_qty - recv_qty;
                let ret_qty_input = item.parentElement.parentElement.nextElementSibling.querySelector(".ret-qty-input");
                if (ret_qty <= -1) {
                    ret_qty_input.value = 0;
                    ret_qty_input.setAttribute("value", 0);
                    alert.invoke_alert("Received Qty can't be greater than Ordered Qty", "error");
                } else {
                    ret_qty_input.value = ret_qty;
                    ret_qty_input.setAttribute("value", ret_qty);
                }
            }
        });
    });

    let form = document.getElementById("grn_update_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("grn_update_submit").onclick = function(evt) {
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
        alerts.invoke_alerts("<?= session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        alert.invoke_alert("<?= session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>