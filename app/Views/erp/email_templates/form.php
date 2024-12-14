<div class="card no-border bg-transparent clearfix">

    <style>
        .loader {
            width: 95px;
            height: 95px;
            border-radius: 50%;
            position: relative;
            animation: rotate 1s linear infinite
        }

        .loader::before,
        .loader::after {
            content: "";
            box-sizing: border-box;
            position: absolute;
            inset: 0px;
            border-radius: 50%;
            border: 5px solid #75b545;
            animation: prixClipFix 2s linear infinite;
        }

        .loader::after {
            inset: 8px;
            transform: rotate3d(90, 90, 0, 180deg);
            border-color: #3d7bbb;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg)
            }

            100% {
                transform: rotate(360deg)
            }
        }

        @keyframes prixClipFix {
            0% {
                clip-path: polygon(50% 50%, 0 0, 0 0, 0 0, 0 0, 0 0)
            }

            50% {
                clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 0, 100% 0, 100% 0)
            }

            75%,
            100% {
                clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 100% 100%, 100% 100%)
            }
        }

        .loader-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            z-index: -100;
            background: white;
            overflow: hidden;

            transition: all ease 0.8s;

            visibility: hidden;
            opacity: 0;
        }

        .showLoader {
            visibility: visible;
            z-index: 10;
            opacity: 1;
        }
    </style>

    <div class="loader-container" id="loader">
        <div class="loader"></div>
    </div>

    <ul id="email-template-tab" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title scrollable-tabs my-2 px-3"
        role="tablist">
        <li class="email-template-tabs"><a role="presentation" data-bs-toggle="tab"
                href="<?= url_to("email.templates.different.modal.form", $model_info->id); ?>"
                data-bs-target="#email-template-form-default" data-reload="1"
                data-name="<?php echo $model_info->template_name; ?>"
                class="email-template-form-tab bg-info btn "><?php echo app_lang("default"); ?></a></li>
        <?php
        if ($different_language_templates) {
            foreach ($different_language_templates as $different_language_template) {
                echo view("email_templates/tab_view", array("tab_data" => $different_language_template));
            }
        }
        ?>
        <div class="tab-title clearfix no-border d-none">
            <div class="title-button-group">
                <?php echo modal_anchor(url_to("email.templates.add.modal.form"), "<i data-feather='plus-circle' class='icon-16'></i> ", array("class" => "btn btn-default", "id" => "add-template-button", "title" => app_lang('add_template'), "data-post-template_name" => $model_info->template_name)); ?>
            </div>
        </div>
    </ul>

    <div class="tab-content mt20">
        <div role="tabpanel" class="tab-pane clearfix" id="email-template-form-default"></div>
    </div>
</div>

<script>
    const loader = $('#loader');
    $(document).on('click', '.email-template-form-tab', function (e) {
        loader.toggleClass('.showLoader');
        // console.log(this);
        const url = $(this).attr('href');
        const name = $(this).data('name');
        const target = $(this).data('bs-target');

        $.ajax({
            url: url,
            data: { tempName: name },
            type: 'POST',

            success: function (response) {
                $(target).empty();
                $(target).html(response);

                loader.toggleClass('.showLoader');
            }
        });
    });
</script>