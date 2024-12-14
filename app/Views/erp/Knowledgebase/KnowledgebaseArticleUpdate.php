<div class="alldiv flex widget_title">
    <h3 class="">Update Knowledge Base</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.Knowledgebase'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
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

    .ck-balloon-panel {
        display: none !important;
    }

    .form_control_group {
        display: block;
        width: 97%;
        padding: 4px 15px;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
    }

    .plus.bg-primary {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
        padding: 10px 16 px;
    }

    .subject {
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

    /* .form {
        display: none;
    } */

    .alert_1 {
        display: none;
    }

    .alertgroup_1 {
        display: none;
    }

    .alert_2 {
        display: none;
    }

    .alertgroup_2 {
        display: none;
    }

    .modal.fade {
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.3);
        width: 100%;
        height: 100vh;
        z-index: 1000;
    }

    .modal-dialog {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        max-width: 500px;
        min-width: 280px;
        padding: 16px;
        z-index: 1500;
        border: 1px solid white;
        border-radius: 6px;
        background: white;
    }

    .close {
        border: none;
        background: transparent;
    }

    hr {
        border-color: grey;
    }

    .btn.bg-secondary {
        padding: 8px 16px;
    }

    :focus-visible {
        outline-color: #4443453b;
        outline-width: 0;
    }
</style>
<div class="alldiv">
    <!-- erp.knowledgebasearticleadd -->
    <?php
    echo form_open(url_to('erp.knowledgebasearticleupdate',$id), array(
        "class" => "flex",
        "id" => "Knowledgebase_update_form"
    ));
    ?>
    <div class="form-width-3  form-control">
        <div class="form-group field-required">
            <label class="form-label" id="form-alert">Subject
                <small class="req text-danger">* </small>
            </label>
            <!-- input -->
            <?php $value = (isset($article) ? $article->article_subject : ''); ?>
            <?php echo  form_input('subject',$value, 'id="name_id" class = "form_control"'); ?>
            <p class="alert_1 text-danger p-0" id="alert"><?php echo 'This field is required.'; ?></p>

            <label class="form-label" id="form-alert">Group
                <small class="req text-danger">* </small>
            </label>
            <!-- -->
            <div class="btn-group d-flex mb-2" role="group">
                <!-- input -->
                <select class="form_control_group groupid" id="subject_id" aria-label="Default select example" name="dropdown_group">
                    
                </select>
                <a href="#">
                    <button class="plus btn bg-primary" type="button" style="height: 100%;" data-toggle="modal" data-target="#knowledgebase_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="100%" fill="white" class="bi bi-plus-lg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                        </svg>
                    </button>
                </a>
            </div>
            <p class="alert_2 text-danger p-0" id="alert"><?php echo 'This field is required.'; ?></p>
             <!-- input checkbox -->
            <div class="tw-flex tw-justify-between tw-items-center">
                <div>
                    <div class="checkbox checkbox-primary checkbox-inline">
                        <input type="checkbox" name="Internal_Article" id="Internal_Article" <?php echo (!isset($article) || (isset($article) && $article->Internal_article == 1)) ? 'checked' : ''; ?>>
                        <label for="Internal_Article"><?php echo 'Internal Article'; ?></label>
                    </div>
                    <div class="checkbox checkbox-primary checkbox-inline">
                        <input type="checkbox" name="Disabled" id="Disabled" <?php echo isset($article) && $article->disabled == 1 ? 'checked' : ''; ?>>
                        <label for="Disabled"><?php echo 'Disabled'; ?></label>
                    </div>
                </div>
            </div>
            <p class="form-label"><?php echo 'Article description'; ?></p>
            <!--  -->
            <?php $contents = '';
            // input
            if (isset($article)) {
                $contents = $article->article_description;
            } ?>
            
            <?php echo form_textarea('articledescription', $contents, 'class = "mb-1 textarea"'); ?>
            <!-- <textarea class="textarea mb-1" name="articledescription" id="" cols="30" rows="10"><?=htmlspecialchars($contents)?></textarea> -->
        </div>
        <div class="panel-footer">
            <div class="form-group textRight">
                <a href="<?php echo url_to('erp.Knowledgebase'); ?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="submit" id="knowledgebase_article_submit">Save</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
            
    <!--modal-->
    <div class="modal fade" id="knowledgebase_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title" id="exampleModalLabel">New Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                            </svg></span>
                    </button>
                </div>
                <hr>
                <?php
                echo form_open(url_to("erp.KnowledgebasegroupAdd"), array(
                    "class" => "flex",
                    "id" => "knowledge_add_form"
                ));
                ?>
                <div class="modal-body">
                    <div class="form-group field-required">
                        <!-- name -->
                        <label class="form-label" id="form-alert">Group Name
                            <small class="req text-danger">*</small>
                        </label>
                        <?php echo  form_input('name',"", 'id="group_name_id" class = "form_control"'); ?>
                        <p class="alertgroup_1 text-danger p-0" id="alertgroup"><?php echo 'This field is required.'; ?></p>

                        <!-- Short description -->
                        <label class="form-label"> Short description </label>
                        <?php echo form_textarea('description',"", 'class = "form-control mb-1" id="group_description"'); ?>

                        <!-- order -->
                        <label class="form-label"> <?php echo "Order"; ?></label>
                        
                        <?php echo  form_input('order',"", 'id = "group_order" class = "form_control" type = "number"'); ?>
                        <p class="alertgroup_2 text-danger p-0" id="alertgroup_1"><?php echo 'Enter valid number.'; ?></p>
                        <!-- checkbox -->
                        <div class="tw-flex tw-justify-between tw-items-center">
                            <div>
                                <div class="checkbox checkbox-primary checkbox-inline">
                                    <input type="checkbox" name="disable" id="disable">
                                    <label for="disable"><?php echo 'Disabled'; ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-primary">Save changes</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!-- -->
</div>


</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script> -->
<script>
    ClassicEditor
    .create(document.querySelector('.textarea'),{
        toolbar: {
            items: [
                'heading',
                '|',
                'bold',
                'italic',
                'link',
                'bulletedList',
                'numberedList',
                '|',
                'undo',
                'redo'
            ]
        },
        language: 'en'
        })  
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });

    //Form validation 
    $(document).ready(function() {
        $('#Knowledgebase_update_form').submit(function(event) {
            event.preventDefault();

            var subject = $('#name_id').val();
            var group = $('#subject_id').val();
            console.log(group);
            if (!subject) {
                $('#name_id').addClass('subject');
                $('#alert').removeClass('alert_1');
            } else if (!group) {
                $('#group').addClass('subject');
                $('#alert').removeClass('alert_2');
            } else {
                this.submit();
            }

        })
    });

    $(document).ready(function() {
        $('#knowledge_add_form').submit(function(event) {
            event.preventDefault();

            var subject = $('#group_name_id').val();
            var description = $("#group_description").val();
            var order = $("#group_order").val();
            order = Number(order);
            if (!subject) {
                $('#group_name_id').addClass('subject');
                $('#alertgroup').removeClass('alertgroup_1');
            } else if (typeof order != "number") {
                $('#alertgroup_1').removeClass('alertgroup_2');
            } else {
                var formdata = $(this).serialize();
                $.ajax({
                    url: '<?= url_to("erp.KnowledgebasegroupAdd") ?>',
                    type: 'POST',
                    data: formdata,
                    success: function(response) {

                        if (response.success) {
                            $("#knowledgebase_modal").modal("hide");

                            $("#knowledge_add_form")[0].reset();

                            let alerts = new ModalAlert();
                            alerts.invoke_alert("Group added", "success");
                            
                        } else {
                            $("#knowledgebase_modal").modal("hide");
                            let alerts = new ModalAlert();
                            alerts.invoke_alert("error", "error");
                        }
                        getgroups();
                    },
                    error: function(xhr, status, error) {
                        console.error("debug", error);
                    }
                    
                });
            }

        });
    });
    //groups ajax
    function getgroups() {
        $.ajax({
            url: '<?= url_to("erp.ajaxgroup") ?>',
            type: "POST",
            success: function(response) {
                var data = JSON.parse(response);
                groups_data_element(data);
            }

        });
    }

    function groups_data_element(data) {
        $("#subject_id").empty();

        html_element = '<option value="{id}" {selected}> {group} </option>';
        // if(Object.keys(data.groups).length){

        // }
        //console.log(Object.keys(data.groups).length);
        $.each(data.groups, function(index, group) {
            var html_element_copy = html_element;
            var selected = "selected";

            if(<?php echo $article->article_group_id ?> == group.group_id ){
                html_element_copy = html_element_copy.replace('{id}', group.group_id);
                html_element_copy = html_element_copy.replace('{group}', group.group_name);
                html_element_copy = html_element_copy.replace('{selected}', selected); 
             }
            else{
                html_element_copy = html_element_copy.replace('{id}', group.group_id);
                html_element_copy = html_element_copy.replace('{group}', group.group_name);
                html_element_copy = html_element_copy.replace('{selected}', '');
            }
            
            $("#subject_id").append(html_element_copy);
        });
    }
    getgroups();
    
    //end
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