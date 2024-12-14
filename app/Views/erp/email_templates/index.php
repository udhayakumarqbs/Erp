<div class="alldiv flex widget_title">
    <h3 class="text-capitalize"><?= $title ?? '' ?></h3>
    <div class="title_right">
    </div>
</div>

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css"
    integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" /> -->


<div class="alldiv">
    <div class="row p-2">
        <div class="col-sm-11 col-lg-12">
            <div class="row">
                <div class="col-md-3">
                    <div id="template-list-box" class="card bg-white">
                        <div class="page-title clearfix">
                            <h4> <?= $title ?></h4>
                        </div>

                        <ul class="nav nav-tabs vertical settings p15 d-block px-3 pt-2" role="tablist">
                            <?php
                            foreach ($templates as $template => $value) {

                                //collapse the selected template tab panel
                                $collapse_in = "";
                                $collapsed_class = "collapsed";
                                ?>
                                <div class="clearfix settings-anchor <?php echo $collapsed_class; ?>"
                                    data-bs-toggle="collapse" data-bs-target="#settings-tab-<?php echo $template; ?>">
                                    <?php echo app_lang($template); ?>
                                </div>
                                <?php
                                echo "<div id='settings-tab-$template' class='collapse $collapse_in'>";
                                echo "<ul class='list-group help-catagory'>";

                                foreach ($value as $sub_template_name => $sub_template) {
                                    echo "<span class='email-template-row list-group-item clickable' data-name='$sub_template_name' data-template-language=''>" . app_lang($sub_template_name) . "</span>";
                                }

                                echo "</ul>";
                                echo "</div>";
                            }
                            ?>
                        </ul>

                    </div>
                </div>
                <div class="col-md-9">
                    <div id="template-details-section">
                        <div id="empty-template" class="text-center p15 box card ">
                            <div class="box-content" style="vertical-align: middle; height: 100%">
                                <div><?php echo app_lang("select_a_template"); ?></div>
                                <span data-feather="code" width="15rem" height="15rem"
                                    style="color:rgba(128, 128, 128, 0.1)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"
    integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- <link rel="stylesheet" href="<?php echo base_url() . "assets/plugins/summernote/summernote.css" ?>">
<script src="<?php echo base_url() . 'assets/plugins/summernote/summernote.js'; ?>"></script> -->

<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

<script>
    initWYSIWYGEditor = function (element, options) {
        if (!options) {
            options = {};
        }

        var settings = $.extend({}, {
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['hr']],
                ['view', ['fullscreen', 'codeview']]
            ],
            disableDragAndDrop: true,
            callbacks: {
                onImageUpload: function (files, editor, $editable) {
                    for (var i = 0; i < files.length; i++) {
                        uploadPastedImage(files[i], $(element));
                    }

                }
            }
        }, options);

        $(element).summernote(settings);
    };

    getWYSIWYGEditorHTML = function (element) {
        return $(element).summernote('code');
    };

    combineCustomFieldsColumns = function (defaultFields, customFieldString) {
        if (defaultFields && customFieldString) {

            var startAfter = defaultFields.slice(-1)[0];
            //count no of custom fields
            var noOfCustomFields = customFieldString.split(',').length - 1;
            if (noOfCustomFields) {
                for (var i = 1; i <= noOfCustomFields; i++) {
                    defaultFields.push(i + startAfter);
                }
            }
        }
        return defaultFields;
    };
</script>

<script>

    var addCommentLink = function (event) {
        //modify comment link copied text on pasting
        var clipboardData = event.originalEvent.clipboardData.getData('text/plain');
        if (clipboardData.indexOf('/#comment') > -1) {
            //pasted comment link
            event.preventDefault();

            var splitClipboardData = clipboardData.split("/"),
                splitClipboardDataCount = splitClipboardData.length,
                commentId = splitClipboardData[splitClipboardDataCount - 1];

            if (!commentId) {
                //there has an extra / at last
                splitClipboardDataCount = splitClipboardDataCount - 1;
                commentId = splitClipboardData[splitClipboardDataCount - 1];
            }

            var splitCommentId = commentId.split("-");
            commentId = splitCommentId[1];

            var taskId = splitClipboardData[splitClipboardDataCount - 2];

            var newClipboardData = "#[" + taskId + "-" + commentId + "] (" + AppLanugage.comment + ") ";

            document.execCommand('insertText', false, newClipboardData);
        }
    };

    //normal input/textarea
    $('body').on('paste', 'input, textarea', function (e) {
        addCommentLink(e);
    });

    //summernote
    $('body').on('summernote.paste', function (e, ne) {
        addCommentLink(ne);
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"
    integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
    let closer = new WindowCloser();
    closer.init();

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

<script>
    $(document).ready(function () {

        /*load a template details*/
        $(".email-template-row").click(function () {
            //don't load this message if already has selected.
            if (!$(this).hasClass("active")) {
                var template_name = $(this).attr("data-name");
                var template_language = "";
                if (template_name) {
                    $(".email-template-row").removeClass("active");
                    $(this).addClass("active");
                    $.ajax({
                        url: "<?php echo url_to("email.templates.form"); ?>",
                        data: { template_name: template_name, template_language: template_language },
                        type: "POST",
                        success: function (result) {

                            // console.log(result);

                            $("#template-details-section").html(result);
                            $(".email-template-form-tab").trigger("click");
                        }
                    });
                }
            }
        });
    });
</script>
</body>

</html>