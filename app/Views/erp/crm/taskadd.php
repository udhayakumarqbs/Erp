<div class="alldiv flex widget_title">
    <h3>Create Task</h3>
    <div class="title_right">
        <a href="<?php echo base_url() . 'erp/crm/task'; ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
    echo form_open(url_to('erp.crm.taskadd'), array(
        "class" => "flex",
        "id" => "task_add_form"
    ));
    ?>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Status</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select priority">select Status</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="status" value="">
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <?php
                    foreach ($task_status as $key => $status) {
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
                    foreach ($task_priority as $key => $value) {
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
        <div class="form-group field-required ">
            <label class="form-label">Subject</label>
            <input type="text" name="name" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>

    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form_control field-check" />
            <p class="error-text"></p>
        </div>
    </div>

    <div class="form-width-2">
        <div class="form-group field-required">
            <div class="selectBox poR">
                <label class="form-label">Related to</label>
                <div class="selectBoxBtn flex">
                    <div class="textFlow" data-default="select related">select related</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="related_to" value="" id="ajaxselectBox_selectBox_Value_related_to">
                </div>
                <ul role="listbox" class="selectBox_Container related alldiv">
                    <?php
                    foreach ($task_related as $value) {
                    ?>
                        <li role="option" data-value="<?php echo $value; ?>"><?php echo $value; ?></li>
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
            <label class="form-label related-text">Related</label>
            <div class="ajaxselectBox poR">
                <div class="ajaxselectBoxBtn flex">
                    <div class="textFlow" id="show_select_option" data-default="select related_id">select related</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" id="custom_drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="related_id" id="ajaxselectBox_Value_related" value="">
                </div>
                <div class="ajaxselectBox_Container alldiv" id="relatedData">
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
            <label class="form-label">Followers</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.crm.ajaxFetchUsers'); ?>">
                <div class="ajaxselectBoxBtn flex">
                    <div class="textFlow" data-default="select followers">select followers</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="followers" value="">
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
            <label class="form-label">Assigned To</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.crm.ajaxFetchUsers'); ?>">
                <div class="ajaxselectBoxBtn flex">
                    <div class="textFlow" data-default="assign to">assign to</div>
                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="assignees" value="">
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
    <div class="form-width-1">
        <div class="form-group field-required ">
            <label class="form-label">Description</label>
            <textarea rows="3" name="task_description" class="form_control field-check"></textarea>
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight">
            <a href="<?php echo base_url() . 'erp/crm/task'; ?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="task_add_submit">Save</button>
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
    $(document).ready(function() {

        $('.selectBox_Container.related').on('click', 'li', function() {
            var selectedOption = $(this).data('value');

            $.ajax({
                type: 'GET',
                url: '<?= url_to('erp.crm.relatedJobTask') ?>',
                data: {
                    option: selectedOption
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Success fetching data: ', response);

                    if (response && response.length > 0) {
                        var html = '<ul role="listbox">';

                        $.each(response, function(index, item) {
                            // console.log(item);
                            console.log(Object.values(item)[0]);
                            html += '<li role="option" onclick="select_for_related(event)" data-value="' + Object.values(item)[0] + '">' + Object.values(item)[1] + '</li>';
                        });

                        html += '</ul>';

                        $('#relatedData').html(html);

                        // $('.selectBox_Value').val(selectedOption);

                        $('.related-text').text(selectedOption);
                    }

                    // } else {
                    //     $('#relatedData').html('<p>No data available.</p>');
                    // }
                },
                error: function(error) {
                    console.error('Error fetching data: ', error);
                }
            });
        });
    });

    let relatedData_open = true;

    function select_for_related(event) {
        console.log(event.target.dataset.value);
        $('#relatedData').css('display', 'none');
        $('#custom_drops').removeClass('active');

        // $('#relatedData').css('display','block')
        document.getElementById('show_select_option').innerHTML = event.target.innerHTML;
        document.getElementById('ajaxselectBox_Value_related').value = event.target.dataset.value;

    }
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

    let form = document.getElementById("task_add_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("task_add_submit").onclick = function(evt) {
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