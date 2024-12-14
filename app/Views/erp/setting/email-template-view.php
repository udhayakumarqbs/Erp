<div class="alldiv flex widget_title">
    <h3>Email Template</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.email.templates'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<style>
    .form-control {
        display: block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143 !important;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .form_control_modal {
        display: block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143 !important;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form-control-btn {
        display: block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-top-right-radius: 0px !important;
        border-bottom-right-radius: 0px !important;
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
    }

    .rupees {
        display: block;
        width: 4%;
        padding: 7px 11px;
        height: 100%;
        font-size: 14px;
        line-height: 1.37565;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 5px;
        border-top-right-radius: 5px;
    }

    .form_control_group {
        display: block;
        width: 100%;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 14px;
        line-height: 1.42857143 !important;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
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

    .btn-group.error input,
    .btn-group.error select {
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

    .btn-group.error input[type="date"] {
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
        border: 1px solid #C83B3B !important;
    }

    .btn-group select,
    .btn-group input {
        display: block;
        width: 100%;
        padding: 7px 13px;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
    }

    .plus.bg-primary {
        border-top-right-radius: 4px !important;
        border-bottom-right-radius: 4px !important;
        border-top-left-radius: 0px !important;
        border-bottom-left-radius: 0px !important;
        padding: 7px 13px;
    }

    .alert {
        display: none !important;
    }

    .alert_ct {
        display: none !important;
    }
    .btn-group.type{
        border-top-right-radius: 0px !important;
        border-bottom-right-radius: 0px !important;
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
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

    .modal-body {
        width: 100%;
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

    .noneclass {
        display: none;
    }
</style>

<div class="alldiv">
    <?php echo form_open(url_to('erp.contractadd'), array(
        "class" => "flex",
        "id" => "contract_add"
    ));
    ?>
    <div class="form-width-3  form-control">
        <div class="form-group field-required">

         <!-- subject -->
         <label class="form-label">Template Name
                <small class="req text-danger">*</small>
            </label>
            <div class="btn-group" role="group">
                <input type="text" name="subject" class="form-control" id="subject_id">
                <p class="alert text-danger p-0" id="alert_2"><?php echo "This field is required."; ?></p>
            </div>

            
            <!-- Customer -->
            <label class="form-label">Status
                <small class="req text-danger">*</small>
            </label>
            <div class="btn-group" role="group">
                <select class="form-control" id="customer_id" aria-label="Default select example" name="dropdown_customer"></select>
                <p class="alert text-danger p-0 mb-1" id="alert_1"><?php echo "This field is required."; ?></p>
            </div>

            <!-- subject -->
            <label class="form-label">Subject
                <small class="req text-danger">*</small>
            </label>
            <div class="btn-group" role="group">
                <input type="text" name="subject" class="form-control" id="subject_id">
                <p class="alert text-danger p-0" id="alert_2"><?php echo "This field is required."; ?></p>
            </div>

       

            <!-- description -->
            <div>                
                <label class="form-label"><?php echo "Description"; ?></label>
                <?php echo form_textarea('contractdescription', '', 'class ="form-control-text-area"'); ?>
                <p></p>
            </div>

            <!-- check box -->
            <div class="tw-flex tw-justify-between tw-items-center mb-4">
                <div>
                    <p>Don't Edit or change any ShortCodes {} </p>
                </div>
            </div>
            <div class="panel-footer">
                <div class="form-group textRight d-flex justify-content-end ">
                    <a href="<?php echo url_to('erp.email.templates'); ?>" class="btn outline-secondary m-1">Cancel</a>
                    <button class="btn bg-primary m-1" type="submit" id="announcement_add_submit">Save</button>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
    <!-- footer -->
    <!-- modal -->
    <div class="modal fade" id="contracttype_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title" id="exampleModalLabel">New Contract Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                            </svg></span>
                    </button>
                </div>
                <hr>
                <?php echo form_open(url_to("erp.contract.add.contracttype"), array(
                    "class" => "flex",
                    "id" => "contract_type_add_new"
                ));
                ?>
                <div class="modal-body">
                    <div class="form-group field-required mb-5">
                        <label class="form-label" id="form-alert">Contract Type Name
                            <small class="req text-danger">*</small>
                        </label>
                        <input type="text" name="id" id="id" hidden>
                        <!-- <input type="text" name="contract_name" class ="form_control_modal"> -->
                        <input type="text" name ="contract_name" class ="form_control_modal" id="contract_type_id">
                        <p class="alert_ct text-danger p-0" id="alert_ct"><?php echo 'This field is required.'; ?></p>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
                        <button type="button" class="btn bg-secondary m-1" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-primary m-1" id="insert_id_btn">Save</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!--  -->

</div>
</div>
</div>



<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    //form submition 
    $(document).ready(function() {
        customer();
        contract();
        var startdate = new Date();
        var date = ('0' + startdate.getDate()).slice(-2);
        var month = ('0' + (startdate.getMonth() + 1)).slice(-2);
        var year = startdate.getFullYear();
        var currentdate = year + '-' + month + '-' + date;
        var customer_value = $("#customer_id").val();
        $("#start-date").val(currentdate);

        $(".form-control").on("input", function(event) {
            if (event.target.value == " " || event.target.value == null || event.target.value == 0) {
                event.target.parentElement.classList.add("error");
                event.target.parentElement.querySelector("p").classList.remove('alert');
            } else {
                event.target.parentElement.classList.remove("error");
                event.target.classList.remove("subject");
                event.target.parentElement.querySelector("p").classList.add('alert');
            }
        });
        $("#contract_add").submit(function(event) {
            event.preventDefault();
            var subject = $("#subject_id").val();
            var startdate = $("#start-date").val().length;
            var enddate = $("#end-date").val().length;
            var customer = $("#customer_id").val();
            if (!customer) {
                $("#customer_id").addClass("subject");
                $("#alert_1").removeClass("alert");
            } else if(!subject) {
                $("#subject_id").addClass("subject");
                $("#alert_2").removeClass("alert");
            } else if (startdate == 0) {
                $("#start-date").addClass("subject");
                $("#alertevent_1").removeClass("alert");
            } else if (enddate == 0) {
                $("#end-date").addClass("subject");
                $("#alertevent_2").removeClass("alert");
            } else if (customer != " " && subject != " " && startdate != 0 && enddate != 0) {
                this.submit();
            }
        });
    });
    //modal submit
    $(document).ready(function() {
        $(".form_control_modal").on("input",function(event){
            if (event.target.value ==" " || event.target.value == null){
                event.target.parentElement.classList.add("error");
                event.target.parentElement.querySelector('p').remove("alert_ct");
                console.log(event.target.parentElement);
            } else {
                event.target.parentElement.querySelector("p").classList.add("alert_ct");
                event.target.classList.remove("subject");
            }
        });
        $("#contract_type_add_new").submit(function(event) {
            event.preventDefault();
            var name = $("#contract_type_id").val();
            if (!name){
                $("#contract_type_id").addClass("subject");
                $("#alert_ct").removeClass("alert_ct");
            } else {
                var formdata = $(this).serialize();
                $.ajax({
                    url: "<?php echo url_to('erp.contract.add.contracttype') ?>",
                    type: "POST",
                    data: formdata,
                    success: function(response) {
                        var alert = new ModalAlert();
                        if (response.success) {
                            $("#contracttype_modal").modal('hide');
                            $("#contract_type_add_new")[0].reset();
                            alert.invoke_alert("Added Successfully", "success");
                        } else {
                            $("#contracttype_modal").modal('hide');
                            $("#contract_type_add_new")[0].empty();
                            alert.invoke_alert("Error occured", "error");
                        }
                        contract();
                    },
                    error: function(xhr, target, error) {
                        console.error("Oops", error);
                    }
                });
            }
        })
    });


    function customer() {
        //customer dropdow list data
        $.ajax({
            url: "<?= url_to('erp.fetchCustomerDetails') ?>",
            type: "POST",
            success: function(response) {
                var data = JSON.parse(response);
                // console.log(data.customers);
                customer_assemble(data);
                console.log(data)
            },
            error: function(xhr, target, error) {
                console.error("utg oops", error);
            }
        });

        //contract type dropdown list data    
    }

    function contract() {
        $.ajax({
            url: "<?php echo url_to("erp.fetchContracttypeDetails") ?>",
            type: "post",
            success: function(response) {
                var data_1 = JSON.parse(response);
                contractype_assemble(data_1)
            }
        });
    }

    function customer_assemble(data) {
        $("#customer_id").empty();
        var structure = "<option value ='{id}'> {customer} </option>";
        var noselected = "<option value ='' disabled selected> {customer} </option>";
        if (data.customers.length <= 0) {
            var copy = structure;
            copy = copy.replace("{id}", " ");
            copy = copy.replace("{customer}", "--Empty--");
            $("#customer_id").append(copy);
        } else {
            var noselect = noselected;
            noselect = noselect.replace("{id}", "");
            noselect = noselect.replace("{customer}", "--Select--");
            $("#customer_id").append(noselect);
            $.each(data.customers, function(index, customer) {
                var copy = structure;
                copy = copy.replace("{id}", customer.cust_id);
                copy = copy.replace("{customer}", customer.company);
                $("#customer_id").append(copy);
            });
        }
    }

    function contractype_assemble(data_1) {
        $("#contract_type").empty();
        var noselected = "<option value ='' disabled selected> {customer} </option>";
        var structure = "<option value ='{id}'> {customer} </option>";
        if (data_1.contracttype.length <= 0) {
            var copy = structure;
            copy = copy.replace("{id}", "");
            copy = copy.replace("{customer}", "--Empty--");
            $("#contract_type").append(copy);
        } else {
            var noselect = noselected;
            noselect = noselect.replace("{id}", "");
            noselect = noselect.replace("{customer}", "--Select--");
            $("#contract_type").append(noselect);
            $.each(data_1.contracttype, function(index, contract) {
                var copy = structure;
                // console.log(customer.id,customer.name);
                copy = copy.replace("{id}", contract.cont_id);
                copy = copy.replace("{customer}", contract.cont_name);

                $("#contract_type").append(copy);
            });
        }
    }
    customer();
</script>
</body>

</html>