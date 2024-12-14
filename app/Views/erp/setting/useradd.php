<div class="alldiv flex widget_title">
    <h3>Add User</h3>
    <div class="title_right">
        <a href="<?php echo base_url() . 'erp/setting/users'; ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<style>
    .staff-container {
        width: 100%;
    }
</style>
<div class="alldiv">
    <?php
    echo form_open(url_to('erp.users.add'), array(
        "class" => "flex",
        "id" => "user_edit_form"
    ));
    ?>
    <!-- <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Name</label>
                <input type="text" class="form_control field-check" value="" name="name" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Last Name</label>
                <input type="text" class="form_control " value="" name="last_name" />
                <p class="error-text" ></p>
            </div>
        </div> -->
    <div>
        <img src="data:image/png;base64,<?php //echo base64_encode($qrCodeImage); ?>" alt="QR Code">
    </div>
    <div class="staff-container p-2">
        <div class="form-group staff-container">
            <label class="form-label">Staff Member</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.setting.employee'); ?>">
                <div class="ajaxselectBoxBtn flex">
                    <div class="textFlow" data-default="" id="staff_text"></div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" name="staff_member" value="" id="staff_name">
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="staff_id" value="" id="staff_id">
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
            <label class="form-label">Email</label>
            <input type="text" class="form_control field-check" value="" name="email" id="email" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group ">
            <label class="form-label">Phone</label>
            <input type="text" class="form_control " value="" name="phone" id="phone" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Position</label>
            <input type="text" class="form_control field-check" value="" name="position" id="position" />
            <p class="error-text"></p>
        </div>
    </div>

    <div class="form-width-2" style="<?php
                                        if (!is_admin()) {
                                            echo 'display:none';
                                        }
                                        ?>">
        <div class="form-group ">
            <label class="form-label">Role</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="Select Role">Select Role</div>
                    <button type="button" class="close"><i class="fa fa-close"></i></button>
                    <button type="button" class="drops"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" name="role_id" class="selectBox_Value" value="">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($roles as $role) {
                    ?>
                        <li role="option" data-value="<?php echo $role['role_id']; ?>"><?php echo $role['role_name']; ?></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="form-width-1" style="<?php
                                        if (!is_admin()) {
                                            echo 'display:none';
                                        }
                                        ?>">
        <div class="form-group">
            <label class="form-check-label"><input type="checkbox" name="is_admin" value="1" <?php
                                                                                                if (get_user_id() == "1") {
                                                                                                    echo "checked";
                                                                                                } ?> /> Is Administrator ? </label>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group ">
            <label class="form-label">Description</label>
            <input type="text" class="form_control " value="" name="description" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group">
            <label class="form-label">Password Generate</label>
            <div class="password poR">
                <input type="password" name="password" class="form_control" value="<?php echo  password_generate(8); ?>">
                <a type="button"><i class="fa fa-eye"></i></a>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight">
            <a href="<?php echo base_url() . 'erp/setting/users'; ?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="user_edit_submit">Add</button>
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
    //ajax container 
    $(".ajaxselectBox_Container.alldiv").on("click", "li", function() {
        var selectedOption = $(this).data('value');

        $.ajax({
            url: "<?php echo url_to("erp.setting.employee.details") ?>",
            type: "POST",
            data: {
                id: selectedOption
            },
            success: function(response) {
                console.log(response);
                $("#email").val(response.mail);
                $("#phone").val(response.phone);
                $("#position").val(response.pos_name);
                $("#position").val(response.pos_name);
                $("#staff_name").val(response.first_name + " " + response.last_name);
            }
        })
    })

    let selectbox = new SelectBox(document.querySelector(".selectBox"));
    selectbox.init();

    let closer_drop = new WindowCloser();
    closer_drop.init();

    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer_drop.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    let form = document.getElementById("user_edit_form");
    let validator = new FormValidate(form);

    document.getElementById("user_edit_submit").onclick = function(evt) {
        validator.validate(
            (params) => {
                //success
                form.submit();
            },
            (params) => {
                //error
                console.log(params);
            }, {});
    }
    <?php
    if (session()->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
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