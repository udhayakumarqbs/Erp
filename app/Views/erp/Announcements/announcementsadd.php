<div class="alldiv flex widget_title">
    <h3 class="">Create Announcements</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.announcements'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
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
        border-radius: 5px ;
    }

    .ck-balloon-panel {
        display: none !important;
    }

    .form-control {
        display: block;
        width: 100%;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
    }
    .subject{
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
    .form{
        display: none;
    }
    .alert{
       display: none;

    }
</style>
<div class="alldiv">
    <?php
    echo form_open(url_to('erp.announcementsinsert'), array(
        "class" => "flex",
        "id" => "announcement_add_form"
    ));
    ?>
    <div class="form-width-3  form-control">
        <div class="form-group field-required">
            <label class="form-label" id="form-alert">Subject
                <small class="req text-danger">* </small>
            </label>


            <?php $value = (isset($announcement) ? $announcement->name : ''); ?>


            <?php echo  form_input('name', '', 'id="name_id" class = "form_control"'); ?>


            <p class="alert text-danger p-0" id="alert"><?php echo 'This field is required.'; ?></p>
            

            
            <p class="form-label"><?php echo 'message'; ?></p>
            <?php $contents = '';
            if (isset($announcement)) {
                $contents = $announcement->message;
            } ?>
            <textarea class="textarea" name="message" id="" cols="30" rows="10"><?=htmlspecialchars($contents)?></textarea>
            <?php //echo form_textarea('message',$contents,'class = "form-control mb-1"'); ?>
        </div>
        <div class="panel-footer">
            <div class="tw-flex tw-justify-between tw-items-center">
                <div>
                    <div class="checkbox checkbox-primary checkbox-inline">
                        <input type="checkbox" name="showtostaff" id="showtostaff" <?php echo (!isset($announcement) || (isset($announcement) && $announcement->showtostaff == 1)) ? 'checked' : ''; ?>>
                        <label for="showtostaff"><?php echo 'Show to staff '; ?></label>
                    </div>
                    <div class="checkbox checkbox-primary checkbox-inline">
                        <input type="checkbox" name="showtousers" id="showtousers" <?php echo isset($announcement) && $announcement->showtousers == 1 ? 'checked' : ''; ?>>
                        <label for="showtousers"><?php echo 'Show to clients'; ?></label>
                    </div>
                    <div class="checkbox checkbox-primary checkbox-inline">
                        <input type="checkbox" name="showname" id="showname" <?php echo isset($announcement) && $announcement->showname == 1 ? 'checked' : ''; ?>>
                        <label for="showname"><?php echo 'Show my name' ?></label>
                    </div>
                </div>
            </div>
            <div class="form-group textRight">
                <a href="<?php echo url_to('erp.announcements'); ?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="submit" id="announcement_add_submit">Save</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>

</div>


</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<link rel="stylesheet" href="<?php echo base_url() . "assets/plugins/summernote/summernote.css" ?>">
<script src="<?php echo base_url() . 'assets/plugins/summernote/summernote.js'; ?>"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script>
    // $(document).ready(function () {
    //       $('#summernote').summernote();
    // });

    ClassicEditor
        .create(document.querySelector('.textarea'))
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });



    //Form validation 
    $(document).ready(function(){
        $('#announcement_add_form').submit(function (event){
            event.preventDefault();
            
            var subject = $('#name_id').val();

            if(!subject){
                $('#name_id').addClass('subject');
                $('#alert').removeClass('alert');
            }
            else{
                this.submit();
            }

        })
    });

   
    
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
