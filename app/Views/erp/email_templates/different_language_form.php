<div class="card">
    <div class='card-header'>
        <h3><i class="fa-solid fa-envelope mx-2"></i><?php echo app_lang($model_info->template_name); ?></h3>
    </div>
    <?php echo form_open(url_to("email.templates.save"), array("id" => "email-template-form-$model_info->id", "class" => "general-form email-template-form", "role" => "form")); ?>
    <div class="modal-body clearfix">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

        <div class="container">
            <div class='row'>
                <div class="col-lg-12">
                    <div class="form-group">

                        <?php
                        echo form_input(array(
                            "id" => "email_subject",
                            "name" => "email_subject",
                            "value" => $model_info->email_subject,
                            "class" => "form_control",
                            "placeholder" => app_lang('subject'),
                            "autofocus" => true,
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>

                        <span id="unsupported-title-variable-error" class="text-danger inline-block mt5 hide"></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <?php
                        echo form_textarea(array(
                            "id" => "custom_message",
                            "name" => "custom_message",
                            "value" => process_images_from_content(($model_info->custom_message ? $model_info->custom_message : $model_info->default_message), false),
                            "class" => "form-control different_language_custom_message"
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div><strong><?php echo app_lang("avilable_variables"); ?></strong>: <?php
               foreach ($variables as $variable) {
                   echo "{" . $variable . "}, ";
               }
               ?></div>
            <hr />
            <div class="form-group m0">
                <button type="submit" class="btn bg-success mr15" id="submit-btn"><i
                        class="fa-solid fa-floppy-disk"></i>
                    <?php echo app_lang('save'); ?></button>
                <button id="restore_to_default" data-bs-toggle="popover" data-id="<?php echo $model_info->id; ?>"
                    data-placement="top" type="button" class="btn bg-danger d-none"><i class="fa-solid fa-recycle"></i>
                    <?php echo app_lang('restore_to_default'); ?></button>
            </div>

        </div>

    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        /*prepare html form data for suitable ajax submit*/
        function encodeAjaxPostData(html) {
            html = replaceAll("=", "~", html);
            html = replaceAll("&", "^", html);
            return html;
        }

        //replace all occurrences of a string
        function replaceAll(find, replace, str) {
            return str.replace(new RegExp(find, 'g'), replace);
        }


        var formId = "#email-template-form-<?php echo $model_info->id; ?>";

        // $(formId).appForm({
        //     isModal: false,
        //     beforeAjaxSubmit: function (data) {
        //         var custom_message = encodeAjaxPostData(getWYSIWYGEditorHTML("#custom_message"));
        //         $.each(data, function (index, obj) {
        //             if (obj.name === "custom_message") {
        //                 data[index]["value"] = custom_message;
        //             }
        //         });
        //     },
        //     onSuccess: function (result) {
        //         if (result.success) {
        //             appAlert.success(result.message, { duration: 10000 });
        //         } else {
        //             appAlert.error(result.message);
        //         }
        //     }
        // });

        let alerts = new ModalAlert();
        // alerts.invoke_alert("msg", "success");
        // alerts.invoke_alert("msg", "error");

        $(formId).on('submit', function (e) {
            e.preventDefault();
            const actionUrl = $(this).attr('action');
            const formData = $(this).serializeArray();

            var custom_message = encodeAjaxPostData(getWYSIWYGEditorHTML("#custom_message"));

            $.each(formData, function (index, obj) {
                if (obj.name === "custom_message") {
                    formData[index]["value"] = custom_message;
                }
            });

            $.ajax({
                url: actionUrl,
                data: formData,
                type: 'POST',

                success: function (response) {
                    response = JSON.parse(response);
                    console.log(response.success);
                    if (response.success) {
                        alerts.invoke_alert(response.message, "success");
                        $(".email-template-form-tab").trigger("click");
                    } else {
                        alerts.invoke_alert(response.message, "error");
                    }
                }
            });
            // console.log(formData);
            // console.log(e);
        });

        $('body').on('keyup', '#email_subject', function () {
            var emailSubjectValue = $(this).val();
            var unsupportedTitleVariables = <?php echo $unsupported_title_variables; ?>;

            console.log(unsupportedTitleVariables);

            for (var i = 0; i < unsupportedTitleVariables.length; i++) {
                if (emailSubjectValue.indexOf("{" + unsupportedTitleVariables[i] + "}") >= 0) {
                    $("#unsupported-title-variable-error").text("<?php echo app_lang("this_variable_is_unsupported"); ?>: " + "{" + unsupportedTitleVariables[i] + "}");
                    $("#unsupported-title-variable-error").removeClass("hide");
                    $("#submit-btn").attr("disabled", "disabled");
                    return false;
                } else {
                    $("#unsupported-title-variable-error").addClass("hide");
                    $("#submit-btn").removeAttr("disabled");
                }
            }
        });



        initWYSIWYGEditor("#custom_message", { height: 480 });
    });
</script>