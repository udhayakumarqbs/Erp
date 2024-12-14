<div class="header-section">
    <h2 class="modalTitle">Contact</h2>
</div>
<div class="view content-container">
    <div class="row">
        <div class="profile form-container col-lg-6 col-sm-12">
            <h2>Profile</h2>
            <form action="<?= url_to('fornt.profile.update') ?>" method="POST" class="flex" id="profile_edit_form"
                enctype="multipart/form-data">
                <input type="hidden" name="contact_id" id="f_contact_id" value="<?= $clientdetail['contact_id'] ?>" />
                <div class="form-width-1 mt-2">
                    <div class="form-group">
                        <label class="form-label">Profile image</label>
                        <input type="file" class="form-control field-check" name="profile_image" value=""
                            id="profile_image" accept="image/*">
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-2 mt-2">
                    <div class="form-group field-required ">
                        <label class="form-label">First name <span class="imp">*</span></label>
                        <input type="text" class="form-control field-check" id="f_contact_firstname" name="firstname"
                            value="<?= $clientdetail['firstname'] ?>" />
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-2 mt-2">
                    <div class="form-group">
                        <label class="form-label">Last name</label>
                        <input type="text" class="form-control" id="f_contact_lastname" name="lastname"
                            value="<?= $clientdetail['lastname'] ?>" />
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-2 mt-2">
                    <div class="form-group">
                        <label class="form-label">Position</label>
                        <input type="text" class="form-control" id="f_contact_position" name="position"
                            value="<?= $clientdetail['position'] ?>" />
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-2 mt-2">
                    <div class="form-group field-email">
                        <label class="form-label">Email <span class="imp">*</span></label>
                        <input type="text" class="form-control field-check" id="f_contact_email" name="email"
                            value="<?= $clientdetail['email'] ?>" />
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-2 mt-2">
                    <div class="form-group field-phone">
                        <label class="form-label">Phone </label>
                        <input type="text" class="form-control field-check" id="f_contact_phone" name="phone"
                            value="<?= $clientdetail['phone'] ?>" />
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-1 mt-2">
                    <div class="form-group d-flex justify-content-end">
                        <button class="btn bg-success text-light mt-2" type="button" id="profile_edit_btn">Update</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="profile password-container col-lg-6 col-sm-12">
            <h2>Change Password</h2>
            <form action="<?= url_to('front.profile.password.change', $clientdetail['contact_id']) ?>" method="POST" id="password_validate">
                <div class="form-width-2 mt-2">
                    <div class="form-group field-required ">
                        <label class="form-label">Current Password</label>
                        <input type="text" class="form-control field-check" id="f_contact_firstname"
                            name="old_password" />
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-2 mt-2">
                    <div class="form-group field-required ">
                        <label class="form-label">New Password</label>
                        <input type="text" class="form-control field-check" id="f_contact_firstname"
                            name="new_password" />
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-2 mt-2">
                    <div class="form-group field-required ">
                        <label class="form-label">Repeat Password</label>
                        <input type="text" class="form-control field-check" id="f_contact_firstname"
                            name="repeat_password" />
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="button" class="btn bg-success text-light mt-2 w-100" id="password_validate_btn">Change Password</button>
                </div>
                <?php if ($clientdetail['password_updated_at']) { ?>
                    <div>
                        <hr>
                    </div>
                    <p id="password_updated_at" class="text-center"></p>
                <?php } ?>
            </form>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="<?= base_url() . 'front-assets/' ?>js/custom.js"></script>
<script>

    document.addEventListener('DOMContentLoaded', function () {


        let password_updated_at = '<?= $clientdetail['password_updated_at'] ?>';

        function dateformat(date) {
            var now = moment();
            var duration = moment.duration(now.diff(date));
            var minute = duration.asMinutes();
            if (minute < 1) return 'now';
            if (minute < 60) return Math.floor(minute) + 'mins ago';
            if (minute < 1440) return Math.floor(minute / 60) + 'hours ago';
            if (minute < 2880) return 'yesterday';
            return date.format('MMM D, YYYY');
        }

        if (password_updated_at) {
            var datestring = password_updated_at;
            var date = moment(datestring, 'YYYY-MM-DD HH:mm:ss');
            var date_format = dateformat(date);
            console.log(date_format);

            $('#password_updated_at').text(`Password last changed ${date_format} `);

        }

        //profile update validate
        let form = document.getElementById("profile_edit_form");
        let validator = new FormValidate(form);

        let lock = false;
        document.getElementById("profile_edit_btn").onclick = function (evt) {
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

        //Password update
        let form_1 = document.getElementById("password_validate");
        let validator_1 = new FormValidate(form_1);

        let lock_1 = false;
        document.getElementById("password_validate_btn").onclick = function (evt) {
            if (!lock_1) {
                lock_1 = true;
                validator_1.validate(
                    (params) => {
                        form_1.submit();
                        lock_1 = false;
                    },
                    (params) => {
                        lock_1 = false;
                    }, {});
            }
        }

    });

    <?php if (session()->getFlashdata('success')) { ?>
        let alert = new Alert();
        alert.alert_invoke('success', '<?= session()->getFlashdata('success') ?>');
    <?php } elseif (session()->getFlashdata('error')) { ?>
        let alert = new Alert();
        alert.alert_invoke('error', '<?= session()->getFlashdata('error') ?>');
    <?php } ?>
</script>