<style>
    .select2-container--default .select2-selection--single {
        height: 40px;
        width: 100%;
        /* Adjust height */
        line-height: 40px;
        /* Align text vertically */
    }
    .selection{
        width: 100%;
    }
</style>
<?php $client_details = array() ?>
<?php $client_details = get_client_name() ?>
<?//= var_dump($client_details) ?>
<div class="header-section d-flex align-items-center justify-content-between">
    <h2 class="modalTitle">Create Tickect</h2>
    <a href="<?= url_To("front.supports.view") ?>" class="btn btn-primary text-white"><i class="fa-solid fa-chevron-left fa-lg p-2" style="color: #ffffff;"></i>Back</a>
</div>
<div class="view content-container">
    <div class="row">
        <div class="profile form-container col-lg- col-sm-12">
            <!-- <h2>Profile</h2> -->
            <form action="<?= url_to('fornt.ticket.add') ?>" method="POST" class="flex" id="ticket_add_form" enctype="multipart/form-data">
                <input type="hidden" name="contact_id" id="f_contact_id" value="<?= $client_details["contact_id"] ?>" />
                <div class="form-width-1 mt-2">
                    <div class="form-group field-required ">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control field-check" />
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-1 mt-2">
                    <div class="form-group field-required">
                        <label class="form-label">Priority</label>
                        <select class="form-control field-check"name="priority" id="priority">
                            <?php foreach ($priority as $key => $value) { ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-1 mt-2">
                    <div class="form-group">
                        <label class="form-label">Project</label>
                        <select class="form-control" id="projects_list" name="project">
                            <option value="">select project</option>
                            <?php foreach ($projects as $value) { ?>
                                <option value="<?php echo $value["project_id"]; ?>"><?php echo $value["name"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-width-1 mt-2">
                    <div class="form-group">
                        <label class="form-label">status</label>
                        <select class="form-control" id="status" name="status">
                            <?php foreach ($statuss as $key => $value) { ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-width-1 mt-2 mb-2">
                    <div class="form-group field-required ">
                        <label class="form-label">Problem</label>
                        <textarea rows="3" name="problem" class="form-control field-check"></textarea>
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-1 mt-3">
                    <div class="form-group textRight">
                        <button class="btn btn-primary" type="button" id="ticket_add_submit">Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<script src="<?= base_url() . 'front-assets/' ?>js/custom.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        //profile update validate
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
    });

    $(document).ready(function () {
        $("#projects_list").select2();
    })

    <?php if (session()->getFlashdata('success')) { ?>
        let alert = new Alert();
        alert.alert_invoke('success', '<?= session()->getFlashdata('success') ?>');
    <?php } elseif (session()->getFlashdata('error')) { ?>
        let alert = new Alert();
        alert.alert_invoke('error', '<?= session()->getFlashdata('error') ?>');
    <?php } ?>
</script>