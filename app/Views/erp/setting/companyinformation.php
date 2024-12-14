<div class="alldiv flex widget_title">
    <h3>Company Information</h3>
</div>
<div class="alldiv">
    <?php
    echo form_open_multipart(url_to('erp.setting.companyinformation'), array(
        "class" => "flex",
        "id" => "companyinfo_edit_form"
    ));
    ?>

     <div class="form-width-1" style="margin-top:20px; margin-bottom:18px;">
        <label for="">These information will be displayed on invoices PDF documents where company info is required</label>
    </div>
    <div class="form-width-2">
        <?php $logoPath = (!empty($companyData['company_logo'])) ? base_url() . 'assets/images/' . $companyData['company_logo'] : '';
        $fileName = (!empty($companyData['company_logo'])) ? $companyData['company_logo'] : 'No file chosen';
        ?>
        <div class="form-group <?= (!empty($companyData['company_logo'])) ? '' : 'field-required'; ?> ">
            <label class="form-label">Company Logo</label>
            <input type="file" class="form_control field-check" name="company_logo" value= "<?php echo $fileName; ?>" id="logoInput" accept="image/*" onchange="previewImage()">
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <img id="logoPreview" src="<?= $logoPath ?>" alt="" style="max-width: 50%; max-height: 50px; margin-top: 10px; <?= (empty($logoPath)) ? 'display: none;' : '' ?>">
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Company Name</label>
            <input type="text" class="form_control field-check" value="<?php echo $companyData['company_name']; ?>" name="company_name" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Address</label>
            <input type="text" class="form_control field-check" value="<?php echo $companyData['address']; ?>" name="address" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">City</label>
            <input type="text" class="form_control field-check" value="<?php echo $companyData['city']; ?>" name="city" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">State</label>
            <input type="text" class="form_control field-check" value="<?php echo $companyData['state']; ?>" name="state" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Country</label>
            <input type="text" class="form_control field-check" value="<?php echo $companyData['country']; ?>" name="country" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Zip Code</label>
            <input type="text" class="form_control field-check" value="<?php echo $companyData['zipcode']; ?>" name="zipcode" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">Phone Number</label>
            <input type="text" class="form_control field-check" value="<?php echo $companyData['phone_number']; ?>" name="phone" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">VAT Number</label>
            <input type="text" class="form_control field-check" value="<?php echo $companyData['vat_number']; ?>" name="vat_number" />
            <p class="error-text"></p>
        </div>
    </div>
    <div class="form-width-2">
        <div class="form-group field-required ">
            <label class="form-label">License Number</label>
            <input type="text" class="form_control field-check" value="<?php echo $companyData['license_number']; ?>" name="license_number" />
            <p class="error-text"></p>
        </div>
    </div>

    <div class="form-width-1">
        <div class="form-group textRight">
            <button class="btn bg-primary" type="button" id="company_edit_submit">Update</button>
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
    let form = document.getElementById("companyinfo_edit_form");
    let validator = new FormValidate(form);

    let lock = false;
    document.getElementById("company_edit_submit").onclick = function(evt) {
        if (!lock) {
            lock = true;
            validator.validate(
                (params) => {
                    //success
                    form.submit();
                    lock = false;
                },
                (params) => {
                    //error
                    lock = false;
                }, {});
        }
    }

    function previewImage() {
        var logoInput = document.getElementById('logoInput');
        var logoPreview = document.getElementById('logoPreview');

        if (logoInput.files && logoInput.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                logoPreview.src = e.target.result;
                logoPreview.style.display = 'block';
            };

            reader.readAsDataURL(logoInput.files[0]);
        }
    }

    $(document).ready(function() {
        if ($("#previewImage").attr("src") !== "") {
            previewImage();
        }
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