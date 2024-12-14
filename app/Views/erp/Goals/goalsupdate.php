<div class="alldiv flex widget_title">
    <h3>Update Goals</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.goalsview'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<link href="https://fonts.googleapis.com/css?family=Raleway:400,300,600,800,900" rel="stylesheet" type="text/css">
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

    .form_control_group {
        display: block;
        width: 100%;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
    }

    .form_control_achievement {
        display: block;
        width: 100%;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
    }

    .form-control-startdate,
    .form-control-enddate {
        display: inline-block;
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

    .form-control-text-area {
        display: inline-block;
        width: 100%;
        padding: 6px 12px;
        height: 80px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }


    .date-start,
    .date-end {
        padding: 0;
        width: 50%;
    }

    .date-start,
    .notification-group {
        padding: 0px 10px 0px 0px;
    }

    .form-control.subject {
        display: block;
        width: 100%;
        padding: 7px 13px;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #C83B3B;
    }

    .alert {
        display: none;
    }

    .alert_1 {
        display: none;
    }

    .alertgroup_1 {
        display: none;
    }

    .alert_2 {
        display: none;
    }

    .alertevent_1 {
        display: none;
    }

    .alertevent_2 {
        display: none;
    }

    .alertgroup_2 {
        display: none;
    }

    :focus-visible {
        outline-color: #4443453b;
    }

    #progress_bar {
        text-align: center;
        position: absolute;
        margin: 20px;
        height: 300px;
        width: 300px;
        left: 10px;
    }



    .contract_type_dropdown {
        height: max-content;
        overflow-y: scroll;
    }

    .contract_type.hide {
        display: none;
    }

    .form-group.error input,
    .selectBoxBtn.flex.error input {
        display: block;
        width: 100%;
        padding: 7px 13px;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #C83B3B;
    }

    .selectBoxBtn.flex.subject {
        display: flex !important;
        width: 100%;
        padding: 0px !important;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #C83B3B;
    }

    .form-control-enddate.subject {
        width: 100%;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #C83B3B;
    }

    .notify-manual {
        display: flex;
        justify-content: center;
    }

    .progress-bar {
        position: relative;
        max-width: 138%;
        min-width: 80%;
    }


    .signed-or-not {
        display: inline-block;
        font-size: 12px;
        width: max-content;
    }

    .signed-body {
        position: relative;
        color: #4bc53e;
        padding: 4px;
        background: #55fd6a30;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.7vh;
        text-align: center !important;
        border: 1px solid #55fd6a30;
    }

    .failed_label {
        color: #fd5555;
        padding: 3px 3px;
        background: #fd555552;
        border-radius: 12px;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        font-weight: 600;
        font-size: 1.7vh;
        text-align: center;
        border: 1px solid #fd555552;
    }

    /* .progress-container {
        width: 480px;
        height: 480px;
        position: relative;
        justify-content: center;
        align-items: center;
        /* overflow: hidden;
        display: flex;
        box-sizing: border-box; }*/
</style>

<div class="alldiv">
    <?php 
    // var_dump($achievement_done);
    ?>
    <div class="row">
        <div class="col-lg-7 col-md-12">
            <?php echo form_open(url_to('erp.goalsupdate', $existData->goals_id), array(
                "class" => "flex",
                "id" => "goal_update_form"
            ));
            ?>
            <div class="form-control form-width-3 ">
                <div class="form-group field-required">
                    <!-- subject -->
                    <div class="form-group">
                        <label class="form-label">Subject
                            <small class="req text-danger">*</small>
                        </label>
                        <input type="text" name="name" class="form-control" id="group_subject"
                            value="<?= $existData->subject ?>">
                        <p class="alert text-danger p-0" id="alert"><?php echo "This field is required."; ?></p>
                    </div>
                    <!-- Goals Type -->
                    <div class="">
                        <?php foreach (get_goal_types() as $type) {
                            if ($type["key"] == $existData->goal_type) {
                                $id = $type["key"];
                                $goal_name = $type["lang_key"];
                                $goal_sub = $type["subtext"] ?? "N/A";
                            }
                        } ?>
                        <label class="form-label">Goal Type
                            <small class="req text-danger">*</small>
                        </label>
                        <div class="form-group field-required">
                            <div class="selectBox poR">
                                <div class="selectBoxBtn flex" id="ajax-form-goal-type">
                                    <?php $goal_name = isset($id) ? $goal_name : "select Goaltype" ?>
                                    <?php $goal_id = isset($id) ? $id : "" ?>
                                    <div class="textFlow" data-default="<?= $goal_name ?>" id="goal-type-text">
                                        --Select--</div>
                                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" class="selectBox_Value field-check" name="goal_type"
                                        value="<?= $goal_id ?>" id="goal_id">
                                </div>
                                <ul role="listbox" class="selectBox_Container alldiv goaltype">
                                    <?php foreach (get_goal_types() as $type) { ?>
                                        <li class="check_contract" data-value="<?php echo $type['key'] ?>" data-subtext="<?php if (isset($type['subtext'])) {
                                               echo $type['subtext'];
                                           } ?>"> <?php echo $type['lang_key']; ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <p class="alert text-danger p-0" id="alert_1"><?php echo 'This field is required.'; ?></p>
                        </div>
                    </div>
                    <!--Staff member -->
                    <?php $staff_id = $existData->staff_id != 0 ? $existData->staff_id : " " ?>
                    <div>
                        <?php if ($staff_id != " ") {
                            $staff_name = $existData->staff_name;
                        } else {
                            $staff_name = "Select All Staff";
                        } ?>
                        <div class="form-group field-required">
                            <label class="form-label">Staff Member</label>
                            <div class="ajaxselectBox poR"
                                data-ajax-url="<?php echo url_to('erp.crm.ajaxFetchUsers'); ?>">
                                <div class="ajaxselectBoxBtn flex">
                                    <div class="textFlow" data-default="<?= $staff_name ?>" id="staff_text">
                                        <?= $staff_name ?>
                                    </div>
                                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" class="ajaxselectBox_Value field-check" name="staff_member"
                                        value="<?= $staff_id ?>" id="staff_id">
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

                    <!-- Achievements -->
                    <div>
                        <?php $achievement = isset($existData->achievement) ? $existData->achievement : " " ?>
                        <label class="form-label">Achievements</label>
                        <input type="number" class="form_control mb-1" id="achievement_id" name="achievement" min="0"
                            step="1" value="<?= $achievement ?>">
                    </div>

                    <div class="d-flex mb-1">
                        <?php $first_date = isset($existData->start_date) ? $existData->start_date : date('Y-m-d') ?>
                        <!-- start date -->
                        <div class="date-start">
                            <div class="form-group">
                                <label class="form-label">
                                    <?php echo "Start Date"; ?>
                                    <small class="req text-danger">*</small>
                                </label>
                                <input type="date" name="start_date" value="<?= $first_date ?>" id="start-date"
                                    class="form-control-startdate">
                                <p class="alert text-danger p-0" id="alertevent_1">
                                    <?php echo 'This field is required.'; ?>
                                </p>
                            </div>
                        </div>
                        <div class="date-end">
                            <?php $end_date = isset($existData->end_date) ? $existData->end_date : "" ?>
                            <!-- end date -->
                            <div class="form-group ">
                                <label class="form-label">
                                    <?php echo "End Date"; ?></label>
                                <small class="req text-danger">*</small>
                                <input type="date" name="end_date" id="enddate" class="form-control-enddate"
                                    value="<?= $end_date ?>">
                                <p class="alert text-danger p-0" id="alertevent_2">
                                    <?php echo 'This field is required.'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Contract type -->
                    <?php $con_id = $existData->contract_type != 0 ? $existData->contract_type : 0 ?>
                    <?php if ($con_id != 0) {
                        $con_name = $existData->contract_type;
                    } else {
                        $con_name = "select Contract Type";
                    } ?>
                    <div class="contract_type hide">
                        <div class="form-group field-required">
                            <label class="form-label">Contract Type
                                <small class="req text-danger">*</small>
                            </label>
                            <div class="selectBox poR">
                                <div class="selectBoxBtn flex" id="ajax-form-contract-type">
                                    <div class="textFlow" data-default="<?= $con_name ?>" id="cont-type">--Select--
                                    </div>
                                    <button class="close" type="button"><i class="fa fa-close"></i></button>
                                    <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" class="selectBox_Value field-check" name="c_type"
                                        value="<?= $con_id ?>" id="contract_type">
                                </div>
                                <ul role="listbox" class="selectBox_Container alldiv">
                                    <?php foreach ($contractType as $ctype) { ?>
                                        <li class="check_contract" data-value="<?php echo $ctype['cont_id']; ?>">
                                            <?php echo $ctype['cont_name']; ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <p class="alert text-danger p-0" id="alert_contract_type">
                                <?php echo 'This field is required.'; ?>
                            </p>
                        </div>
                    </div>
                    <!-- description -->
                    <label class="form-label">
                        <?php $description = isset($existData->description) ? $existData->description : " " ?>
                        <?php echo "Description"; ?></label>
                    <?php echo form_textarea('description', $description, 'class ="form-control-text-area "'); ?>

                    <!-- check box -->
                    <div class="tw-flex tw-justify-between tw-items-center mt-2 mb-4">
                        <div>
                            <div class="checkbox checkbox-primary checkbox-inline">
                                <input type="checkbox" name="when_goal_achieve" id="showtostaff" <?php echo $existData->notify_when_achieve == 1 ? "checked" : ""; ?>>
                                <label for="showtostaff"><?php echo 'Notify staff members when goal achieve'; ?></label>
                            </div>
                            <div class="checkbox checkbox-primary checkbox-inline">
                                <input type="checkbox" name="goal_failed_to_achieve" id="showtousers" <?php echo $existData->notify_when_fail == 1 ? "checked" : ""; ?>>
                                <label
                                    for="showtousers"><?php echo 'Notify staff members when goal failed to achieve'; ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <div class="form-group textRight">
                            <a href="<?php echo url_to('erp.goalsview'); ?>" class="btn outline-secondary">Cancel</a>
                            <button class="btn bg-primary" type="submit" id="announcement_add_submit">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <div calss="col-lg-6 col-md-12">
            <div class="form-control form-width-3 progress-bar">
                <h2 class="goal-text text-center mt-2">Goal Progress Total: <span
                        style="font-size:20px"><?= $existData->achievement ?><span></h2>
                <h5 class="text-center mt-3"><?= $goal_sub??"N/A" ?></h5>
                <?php if ($existData->notified == 0 && $existData->achievement == 0) { ?>
                    <div class="notify-manual mt-3">
                        <button type="button" id="manual_reminder" class="btn bg-success"><i class="fa-solid fa-bell"
                                style="color: #ffffff;"></i> Notify Staff Members Manually</button>
                    </div>
                <?php } ?>
                <div class="pro mt-3">
                    <div id="progress_bar"></div>
                </div>
                <?php $notify_type ?>

                <?php if ($existData->end_date < date('Y-m-d') && isset($existData->end_date)) { ?>
                <?php } ?>

                <?php if (!empty($achievement_done)) { ?>
                    <?php $notify_type = 'success'; ?>
                    <span class="signed-body mb-1" id="signed">
                        <p class="signed-or-not">Achieved</p>
                    </span>
                <?php } else if (isset($existData->end_date)) { ?>
                    <?php if ($existData->end_date < date('Y-m-d')) { ?>
                        <?php $notify_type = 'failed'; ?>
                            <span class="failed_label mb-1" id="signed">
                                <p class="signed-or-not">Pending</p>
                            </span>
                    <?php } else { ?>
                        <?php $notify_type = 'failed'; ?>
                            <span class="failed_label mb-1" id="signed">
                                <p class="signed-or-not">Failed</p>
                            </span>
                    <?php } ?>
                <?php } else { ?>
                    <?php $notify_type = 'failed'; ?>
                        <span class="failed_label mb-1" id="signed">
                            <p class="signed-or-not">Failed</p>
                        </span>
                <?php } ?>

            </div>

        </div>
        <!-- footer -->

    </div>
</div>
</div>



<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.0.1/progressbar.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script>
    //form submition 
    $(document).ready(function () {

        $(".form-control").on("input", function (event) {

            if (event.target.value == " " || event.target.value == null || event.target.value == 0) {
                event.target.parentElement.classList.add("error");
                event.target.parentElement.querySelector("p").classList.remove('alert');
            } else {
                event.target.parentElement.classList.remove("error");
                event.target.classList.remove("subject");
                event.target.parentElement.querySelector("p").classList.add('alert');
            }
        });
        $(".check_contract").on("click", function (event) {
            // console.log(event.target.parentElement.parentElement.children[0].classList.add('subject'));
            var data = event.target.parentElement.parentElement.children[0].children[3].value;

            if (data == " " || data == null || data == 0) {
                event.target.parentElement.parentElement.children[0].classList.add("error");
                event.target.parentElement.parentElement.parentElement.querySelector("p").classList.remove('alert');
            } else {
                event.target.parentElement.parentElement.children[0].classList.add("error");
                event.target.parentElement.parentElement.children[0].classList.remove("subject");
                event.target.parentElement.parentElement.parentElement.querySelector("p").classList.add('alert');
            }
        });

        $("#goal_update_form").submit(function (event) {
            event.preventDefault();
            var subject = $("#group_subject").val();
            var goal = $("#goal_id").val();
            var startdate = $("#start-date").val();
            var enddate = $("#enddate").val();
            var contracttype = $("#contract_type").val();
            console.log();
            if (!subject) {
                $("#group_subject").addClass("subject");
                $("#alert").removeClass("alert");
            } else if (!goal) {
                $("#ajax-form-goal-type").addClass("subject");
                $("#alert_1").removeClass("alert");
            } else if (!startdate) {
                $("#start-date").addClass("subject");
                $("#alertevent_1").removeClass("alertevent_1");
            } else if (!enddate) {
                $("#enddate").addClass("subject");
                $("#alertevent_2").removeClass("alert");
            } else if (!contracttype) {
                $("#ajax-form-contract-type").addClass("subject");
                $("#alert_contract_type").removeClass("alert");
            } else {
                this.submit();
            }
        })
    });


    document.addEventListener("DOMContentLoaded", function () {


        var progress_bar = document.getElementById('progress_bar');
        var bar = new ProgressBar.Circle(progress_bar, {
            color: '#808080',
            // This has to be the same size as the maximum width to
            // prevent clipping
            strokeWidth: 6,
            trailWidth: 6,
            easing: 'easeInOut',
            duration: 1400,
            text: {
                enabled: true
            },
            from: {
                color: '#3bbe39',
                width: 4
            },
            to: {
                color: '#3bbe39',
                width: 6
            },
            // Set default step function for all animate calls
            step: function (state, circle) {
                circle.path.setAttribute('stroke', state.color);
                circle.path.setAttribute('stroke-width', state.width);

                var value = Math.round(circle.value() * 100);
                console.log(value)
                if (value === 0) {
                    circle.setText('0%');
                } else {
                    circle.setText(value + "%");
                }
            }
        });
        bar.text.style.fontFamily = '"Raleway", Helvetica, sans-serif';
        bar.text.style.fontSize = '2rem';
        <?php $value = $existData->achievement != 0 ? $achievement_done / $existData->achievement : 1; ?>
        bar.animate(<?= $value; ?>);
    });


    let closer_drop = new WindowCloser();
    closer_drop.init();

    document.querySelectorAll(".selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        closer_drop.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    document.querySelectorAll(".ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        closer_drop.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    $(".selectBox_Container.alldiv.goaltype").on("click", "li", function (event) {
        var value = event.target.dataset.value;
        if (value == 5 || value == 7) {
            $(".contract_type").removeClass("hide");
            $("#contract_type").val("");
        } else {
            $(".contract_type").addClass("hide");
            $("#contract_type").val(0);
        }
    });

    //manual reminder
    <?php if ($staff_id == " ") {
        $staff_id = 0;
    }
    ?>

    $("#manual_reminder").on('click', function () {
        console.log("reminder hitted");
        $.ajax({
            url: "<?php echo url_to("erp.notify.reminder.goals") ?>",
            type: "POST",
            data: {
                goalid: "<?= $existData->goals_id ?>",
                staff_id: "<?= $staff_id ?>",
                goal_type: "<?= $existData->goal_type ?>",
                notify_type: "<?php //$notify_type ?>",
            },
            success: function (response) {
                console.log("response xdffgdfdf", response);
                var alert = new ModalAlert();
                if (response.success) {
                    console.log("success");
                    alert.invoke_alert("Notified Successfully", "success");
                    location.reload();
                } else {
                    console.log("error");
                    alert.invoke_alert("Notified error", "error");
                }
            },
            error: function (xhr, target, error) {
                console.error("Errorr : ", error);
            }
        });
    })

    <?php if (session()->getFlashdata('op_success')) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getFlashdata('op_success') ?>", "success");
    <?php } elseif (session()->getFlashdata('op_error')) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?php echo session()->getFlashdata('op_error') ?>", "error");
    <?php } ?>
</script>
<style>
    .progressbar-text{
        font-family:'Inter', sans-serif !important;
    }
</style>
</body>

</html>