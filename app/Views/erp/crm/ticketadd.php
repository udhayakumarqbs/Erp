<style>
    .select2-container--default .select2-selection--single {
        height: 40px;
        /* Adjust height */
        line-height: 40px;
        /* Align text vertically */
    }
</style>
<div class="alldiv flex widget_title">
    <h3>Create Ticket</h3>
    <div class="title_right">
        <a href="<?php echo base_url() . 'erp/crm/tickets'; ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back
        </a>
    </div>
</div>
<div class="alldiv">
    <?php
    echo form_open(url_to('erp.crm.ticketadd'), array(
        "class" => "flex",
        "id" => "ticket_add_form"
    ));
    ?>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Priority</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select priority">select priority</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="priority" value="">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($ticket_priority as $key => $value) {
                        ?>
                        <li role="option" data-value="<?php echo $key; ?>"><?php echo $value; ?></li>
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
            <label class="form-label">Customer Contact</label>
            <div class="ajaxselectBox poR"
                data-ajax-url="<?php echo url_to('erp.crm.ajaxfetchcustomersforticktes'); ?>">
                <div class="ajaxselectBoxBtn flex">
                    <div class="textFlow" data-default="select customer">select customer</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" id="contact_input" class="ajaxselectBox_Value field-check" name="customer"
                        value="">
                </div>
                <div class="ajaxselectBox_Container alldiv">
                    <input type="text" id="ajaxbox" class="ajaxselectBox_Search form_control" />
                    <ul role="listbox">

                    </ul>
                </div>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group">
            <label class="form-label">Project</label>
            <select class="form_control" id="projects_list" name="project" style="width: 509px !important;">
            </select>
        </div>
    </div>

    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Assigned To</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.crm.ajaxFetchUsers'); ?>">
                <div class="ajaxselectBoxBtn flex">
                    <div class="textFlow" data-default="assign to">assign to</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="assigned_to" value="">
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
        <div class="form-group field-required">
            <label class="form-label">Status</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select Status">select Status</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="status" value="">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($ticket_status as $key => $value) {
                        ?>
                        <li role="option" data-value="<?php echo $key; ?>"><?php echo $value; ?></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group field-required ">
            <label class="form-label">Problem</label>
            <textarea rows="3" name="problem" class="form_control field-check"></textarea>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight">
            <a href="<?php echo base_url() . 'erp/crm/tickets'; ?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="ticket_add_submit">Save</button>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script type="text/javascript">
    let closer = new WindowCloser();
    closer.init();

    document.querySelectorAll(".selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        closer.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    let form = document.getElementById("ticket_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("ticket_add_submit").onclick = function (evt) {
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

    $(document).ready(function () {
        $("#projects_list").select2();
    })

    $("#projects_list").attr("disabled", true);
    $("#ajaxbox").on("change", function () {
        setTimeout(() => {
            let contact_id = $("#contact_input").val();
            console.log('contact_id ->', contact_id);
            api_call(contact_id);
        }, 1000)
    })

    function api_call(contact_id) {
        if (contact_id != '') {
            $.ajax({
                url: "<?= url_to('fetch.contact.project') ?>",
                method: "post",
                data: {
                    cont_id: contact_id
                },
                success: function (response) {
                    console.log(response);
                    let alert = new ModalAlert();
                    if (response.success) {
                        let data = response.data;
                        let ellement = document.getElementById("projects_list");
                        $("#projects_list").removeAttr("disabled");
                        if (data.length > 0) {
                            ellement.innerHTML = "";
                            // let list = `<option role="option" data-value="{key}" data-extra="{value}">{projectname}</option>`;
                            let li = `<option value="">select project</option>`;
                            data.forEach((value, index) => {
                                li += `<option value="${value.project_id}">${value.name}</option>`;
                            })
                            ellement.innerHTML = li;
                        } else {
                            ellement.innerHTML = "";
                            let msg = `<option value="">no results for that contact </option>`;
                            ellement.innerHTML = msg;
                            // ellement.append(msg);
                        }
                    } else {
                        alert.invoke_alert(response.message, "error")
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            })
        }
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