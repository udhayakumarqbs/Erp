<div class="alldiv flex widget_title">
    <h3>Groups</h3>
</div>
<style>
    .form-control {
        display: block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .alertgroup_1 {
        display: none;
    }

    .alertgroup_2 {
        display: none;
    }

    .edit::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        z-index: 2;
    }


 
</style>
<div class="alldiv">
    <!-- -->
    <?php
    echo form_open(url_to('erp.knowledgebaseupdate',$id), array(
        "class" => "flex",
        "id" => "knowledge_update_form"
    ));
    ?>
    <div class="form-width-3  form-control">
        <div class="form-group field-required">
            <!-- name -->
            <label class="form-label" id="form-alert">Group Name
                <small class="req text-danger">*</small>
            </label>
            <?php $value = (isset($Knowledgebase) ? $Knowledgebase->group_name : ''); ?>
            <?php echo  form_input('name', $value, 'id="group_name_id" class = "form_control"'); ?>
            <p class="alertgroup_1 text-danger p-0" id="alertgroup"><?php echo 'This field is required.'; ?></p>


            <!-- Short description -->
            <label class="form-label"> Short description </label>
            <?php $contents = '';
            if (isset($Knowledgebase)) {
                $contents = $Knowledgebase->short_description;
            } ?>
            <?php echo form_textarea('description', $contents, 'class = "form-control mb-1" id="group_description"'); ?>
            <!-- -->
            <!-- order -->
            <label class="form-label"> <?php echo "Order"; ?></label>
            <?php $orders = '';
            if (isset($Knowledgebase)) {
                $orders = $Knowledgebase->group_order;
            } ?>
            <?php echo  form_input('order', $orders, 'id = "group_order" class = "form_control" type = "number"'); ?>
            <p class="alertgroup_2 text-danger p-0" id="alertgroup_1"><?php echo 'Enter valid number.'; ?></p>
            <!-- checkbox -->
            <div class="tw-flex tw-justify-between tw-items-center">
                <div>
                    <div class="checkbox checkbox-primary checkbox-inline">
                        <input type="checkbox" name="disable" id="disable" <?php echo (!isset($Knowledgebase) || (isset($Knowledgebase) && $Knowledgebase->disabled == 1)) ? 'checked' : ''; ?>>
                        <label for="disable"><?php echo 'Disabled'; ?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="<?php echo url_to('erp.Knowledgebasegroupview') ?>">
                <button type="button" class="btn bg-secondary" data-dismiss="modal">Close</button>
            </a>
            <button type="submit" class="btn bg-primary">Save changes</button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
</div>
</div>




<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> -->

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>





<script>
    $(document).ready(function() {
        $('#knowledge_update_form').submit(function(event) {
            event.preventDefault();

            var subject = $('#group_name_id').val();
            var description = $("#group_description").val();
            var order = $("#group_order").val();
            order = Number(order)? Number(order) : "String";
            if (!subject) {
                $('#group_name_id').addClass('subject');
                $('#alertgroup').removeClass('alertgroup_1');
            } else if (typeof order != "number") {
                $('#alertgroup_1').removeClass('alertgroup_2');
            } else {
                this.submit();
            }
        });
    });

    <?php if (session()->getFlashdata('op_success')) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getFlashdata('op_success') ?>", "success");
    <?php } elseif (session()->getFlashdata('op_error')) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?php echo session()->getFlashdata('op_error') ?>", "error");
    <?php } ?>
</script>

</body>

</html>