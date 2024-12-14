<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract_View</title>
    <link rel="icon" href="<?php echo base_url() . 'assets/images/favicon.png';
                            ?>" sizes="16x16 32x32" type="image/png" />
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/font-awesome.css';
                                                        ?>"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.0.1/skins/content/dark/content.min.css">
    <link rel="stylesheet" type="text/css" href="?php echo base_url() . 'assets/css/style.css'; ?>">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
    <script src="<?= base_url() . 'assets/js/notify.js' ?>"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url('assets/plugins/ckeditor5/ckeditor.js'); ?>"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.45/moment-timezone.min.js"></script>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .head-c-2-2 {
            gap: 10px;
        }

        .text-container {
            width: 120vh;
            padding-left: 10px;
            padding-right: 20px;
        }

        .text-division {
            width: 75vh;
            padding-left: 30px;
            padding-right: 30px;
        }

        .text-area-division {
            width: 100%;
            padding-top: 20px;
            padding-bottom: 30px;
        }

        .text-area-container {
            width: 100%;
            padding: 20px 0px 30px 0px;
        }

        .text-area-container>input[type="textarea"] {
            width: 100%;
        }

        .nav-link {
            position: relative;
        }

        .nav-link::before {
            content: " ";
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;

        }

        .nav-link.active:focus-visible,
        .nav-link.active:focus {
            outline: none;
        }

        .nav-link {

            font-size: 13px;
        }

        .subject-title,
        .signed-or-not {
            display: inline-block;
            width: max-content;

        }

        .signed-or-not {
            display: inline-block;
            font-size: 12px;
            width: max-content;
        }

        .signed-body {
            margin-left: 20px;
            color: #4bc53e;
            padding: 4px;
            background: #55fd6a30;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.7vh;
            text-align: center !important;
            border: 1px solid #55fd6a30;
        }

        .signed-body.none {
            display: none;
        }

        .sign-btn.none {
            display: none;
        }

        .contract {
            padding: 5px 0px 5px 0px;
            height: 6vh;
        }

        .contract_type {
            padding: 5px 0px;
            font-size: 14px;
            color: rgb(124, 119, 119);
        }

        .line {
            position: absolute;
            width: 100px;
            height: 3px;
            background: green;
            top: 59px;
            left: 0;
            transition: all .3s ease;
        }

        .text-area-division .nav-link.active {
            color: #4b9bff !important;
        }

        .text-area-division {
            position: relative;
        }

        .container-summary-discussion {
            text-align: left;
        }

        .commend-box-pic {
            height: 5vh;
            border-radius: 50%;
            border: 2px solid #23e56b;
        }

        .commend-log {
            padding: 10px 0px 10px 10px;
            gap: 10px;
            margin-top: 10px;
            border-radius: 8px;
        }

        .commend-log-1 {
            gap: 10px;
            line-height: 30px;
        }

        .time {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }



        .edit {
            position: relative;
        }

        .edit::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
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
        .dropdown.tableAction {
            width: 90px;
        }

        .alert_1 {
            display: none;
        }

        /* .dropdown_container{
        margin-right: 75px;
    } */
        .modal.fade {
            position: fixed;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.3);
            width: 100%;
            height: 100vh;
        }

        .modal-dialog {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) !important;
            width: 100%;
            height: 650px;
            max-width: 600px;
            min-width: 500px;
            padding: 16px;
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

        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #fff;
            background-clip: padding-box;
            border: 0 !important;
            border-radius: .3rem;
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

        .alert {
            display: none;
        }



        .signature {
            border: 1px solid lightgray;
            border-radius: 8px;
        }

        .subject {
            display: block;
            width: 100%;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143 !important;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #C83B3B;
            border-radius: 5px;
        }

        .signature.signsubject {
            border: 1px solid #C83B3B;
        }

        .bg-primary {
            background-color: #007bff !important;
            color: white;
        }

        .btn-group.error input,
        .btn-group.error .email {
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

        .btn-group.error .signature {
            display: inline-block;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border: 1px solid #C83B3B;
        }

        :focus-visible {
            outline-color: #4443453b;
            outline-width: 0;
        }
    </style>
</head>

<body>
    <div class="wrapper p-5">
        <div class="content">

            <div class="head-container-1 d-flex p-2">
                <div class="logo">
                    <img src="<?php echo base_url() . 'uploads/contract/logo.png' ?>" alt="" class="w-1">
                </div>
                <div class="head-remainer" style="width: 100%;">
                </div>
            </div>
            <!-- container-heading -->
            <div class="headcontainer-2 d-flex justify-content-between align-items-center  rounded-lg p-1">
                <!-- content -->
                <div class="p-1">
                    <div class="d-flex contract">
                        <h1 class="h5 subject-title"><?php echo $contract_details->c_sub ?? ""; ?><span class="signed-body none" id="signed">
                                <p class="signed-or-not">Signed</p>
                            </span></h1>
                    </div>
                    <p class="contract_type"><?php echo $contract_details->cont_name ?? "" ?></p>
                </div>
                <!-- download and sign -->
                <div class="d-flex head-c-2-2">
                    <div>
                        <a href="<?php  echo url_to("erp.contract.view.pdf.download",$contract_details->id,"download") ?>" class="download btn btn-light  border border-black">
                            <i class="fa-regular fa-file-pdf"></i>Download
                        </a>
                    </div>
                    <div>
                        <button class="sign-btn btn btn-success" id="sign-btn" data-toggle="modal" data-target="#Sigining_modal">
                            <i class="fa-solid fa-signature"></i>Sign
                        </button>
                    </div>
                </div>
            </div>
            <!--  -->




            <div class="body-container  d-flex ">
                <!-- container -->
                <div class=" text-container">
                    <div class="text-area-container ">
                        <?php $value = isset($contract_details) ? strip_tags($contract_details->c_content) : " " ?>
                        <input type="text" name="content" id="contend-textarea" class="form-control p-4" disabled value="<?php echo $value ?>">
                    </div>
                </div>

                <!-- conatiner box -->
                <div class=" text-division ">
                    <!-- heading -->
                    <nav>
                        <div class="text-area-division nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-summary-tab" data-bs-toggle="tab" data-bs-target="#nav-summary" type="button" role="tab" aria-controls="nav-summary" aria-selected="true">
                                <i class="fa-regular fa-file-lines"></i>
                                Summary
                            </button>
                            <button class="nav-link" id="nav-discussion-tab" data-bs-toggle="tab" data-bs-target="#nav-discussion" type="button" role="tab" aria-controls="discussion-summary" aria-selected="false">
                                <i class="fa-regular fa-comment-dots"></i>
                                Discussion
                            </button>
                            <div class="line" id="line"></div>
                        </div>
                    </nav>
                    <!-- body -->
                    <div class="tab-content" id="nav-tabcontent">
                        <?php $contract_value = isset($contract_details) ? $contract_details->c_value : " " ?>
                        <?php $contract_num = isset($contract_details) ? $contract_details->id : " " ?>
                        <?php $start_date = isset($contract_details) ? $contract_details->s_date : " " ?>
                        <?php $end_date = isset($contract_details) ? $contract_details->e_date : " " ?>
                        <?php $contract_type = isset($contract_details) ? $contract_details->cont_name : " " ?>
                        <?php $contract_SignerName = isset($contract_details) ? $contract_details->first_name : " " ?>
                        <?php $contract_SignerlastName = isset($contract_details) ?  $contract_details->last_name : " " ?>
                        <?php $contract_SignedDate = isset($contract_details) ? $contract_details->sign_date : " " ?>
                        <?php $contract_signed_ip = isset($contract_details) ? $contract_details->ip : " " ?>
                        <?php $contract_f_name_title = isset($contract_details) ? "Signer Name" : " " ?>
                        <?php $contract_f_last_title = isset($contract_details) ? "Signed Date" : " " ?>
                        <?php $contract_ip_title = isset($contract_details) ? "IP Address" : " " ?>


                        <!-- summery body -->
                        <div class="tab-pane fade show active p-3 " id="nav-summary" role="tabpanel" aria-labelledby="nav-summary-tab">
                            <div class="row">
                                <div class="col-md-5">
                                    <h6>Contract Value</h6>
                                </div>
                                <div class="col-md-7"><?php echo "₹" . number_format($contract_value, 2, '.', ',') ?></div>
                                <div class="col-md-5">#Contract Number</div>
                                <div class="col-md-7"><?php echo $contract_num ?></div>
                                <div class="col-md-5">Start Date</div>
                                <div class="col-md-7"><?php echo $start_date ?></div>
                                <div class="col-md-5">End Date</div>
                                <div class="col-md-7"><?php echo $end_date ?></div>
                                <div class="col-md-5">Contract type</div>
                                <div class="col-md-7"><?php echo $contract_type ?></div>
                            </div>
                            <div class="form-width-1 mt-2">

                                <?php $Attachment_Title = ($attachments_count > 0) ? "Contract Files" : "" ?>
                                <h6 class="mt-3"><?php echo $Attachment_Title  ?></h6>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody class="attachment-holder" data-ajaxdel-url="<?php echo url_to('erp.contract.deleteattachment') . '?'; ?>">
                                            <?php
                                            foreach ($attachments as $attach) {
                                            ?>
                                                <tr>
                                                    <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('contract_Attachment') . $attach['filename'];
                                                                                                                ?>"><?php echo $attach['filename'];
                                                                                                                    ?></a></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-width-1 mt-2">
                                <?php $title = isset($contract_details->ip) ? "Signature" : "" ?>
                                <?php $a_name = isset($contract_details->ip) ? $contract_SignerName : "" ?>
                                <?php $l_name = isset($contract_details->ip) ? $contract_SignerlastName : "" ?>
                                <?php $a_date = isset($contract_details->ip) ? $contract_SignedDate : "" ?>
                                <?php $a_ip = isset($contract_details->ip) ? $contract_signed_ip : "" ?>
                                <?php $contract_f_name_ = isset($contract_details->ip) ? $contract_f_name_title : "" ?>
                                <?php $contract_f_last_ = isset($contract_details->ip) ? $contract_f_last_title : "" ?>
                                <?php $contract_ip_ = isset($contract_details->ip) ? $contract_ip_title : "" ?>
                                <div class="row">
                                    <div class="col-md-5">
                                        <h6><?php echo $title ?></h6>
                                    </div>
                                    <div class="col-md-7"><?php echo "" ?></div>
                                    <div class="col-md-5"><?php echo $contract_f_name_ ?></div>
                                    <div class="col-md-7"><?php echo $a_name . " " . $l_name ?></div>
                                    <div class="col-md-5"><?php echo $contract_f_last_ ?></div>
                                    <div class="col-md-7"><?php echo $a_date ?></div>
                                    <div class="col-md-5"><?php echo $contract_ip_ ?></div>
                                    <div class="col-md-7"><?php echo $a_ip ?></div>
                                </div>
                            </div>
                        </div>
                        <!-- dicussion body -->
                        <div class="tab-pane fade p-3" id="nav-discussion" role="tabpanel" aria-labelledby="nav-discussion-tab">
                            <!-- commend box-->
                            <?php echo form_open('erp.contractCommendAdd', array(
                                'id' => 'comment_form',
                                'class' => 'flex'
                            )) ?>
                            <textarea name="comment" id="comment_box" class="form-control"></textarea>
                            <button class="btn btn-primary mt-3" type="submit" id="submit_loader">
                                Add Comment
                            </button>
                            <?php echo form_close(); ?>
                            <!-- commend logs -->
                            <div class="commends-container" id="commend-cont">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="Sigining_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header d-flex justify-content-between">
                            <p class="modal-title" id="exampleModalLabel" class="form-label">Signature & Confirmation Of Identity</p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-x-lg" viewBox="0 0 16 16">
                                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                    </svg></span>
                            </button>
                        </div>
                        <hr>
                        <?php echo form_open(url_to('erp.contract.sign'), array(
                            "class" => "flex",
                            "id" => "signature_form"
                        ));
                        ?>

                        <div class="form-group field-required">
                            <label class="form-label d-block">First Name
                                <small class="req text-danger">*</small>
                            </label>
                            <div class="btn-group w-100" role="group">
                                <input type="text" name="first_name" class="form_control_modal w-100" id="f_name_id">
                            </div>
                            <p class="alert text-danger" id="alert_1"><?php echo "This field is required."; ?></p>
                        </div>


                        <div class="form-group field-required">
                            <label class="form-label d-block">Last Name
                                <small class="req text-danger">*</small>
                            </label>
                            <div class="btn-group w-100" role="group">
                                <input type="text" name="last_name" class="form_control_modal w-100" id="l_name_id">
                            </div>
                            <p class="alert text-danger p-0" id="alert_2"><?php echo "This field is required."; ?></p>
                        </div>

                        <div class="form-group field-required">
                            <label class="form-label d-block">Email
                                <small class="req text-danger">*</small>
                            </label>
                            <div class="btn-group w-100" role="group">
                                <input type="email" name="email" class="form_control_modal email w-100" id="email_id">
                            </div>
                            <p class="alert text-danger" id="alert_3"><?php echo "This field is required."; ?></p>
                        </div>
                        <div class="form-group field-required">
                            <label class="form-label d-block">Signature
                                <small class="req text-danger">*</small>
                            </label>
                            <div class="btn-group" role="group">
                                <div class="signature" id="signature">
                                </div>
                            </div>
                            <p class="alert text-danger" id="alert_4"><?php echo "Signature is required."; ?></p>
                        </div>
                        <div class="modal-footer d-flex justify-content-end">
                            <button type="button" class="btn bg-secondary m-1" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn bg-primary m-1" id="submit_btn">Save</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>


            <!--SCRIPT WORKS -->
            <script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
            <script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
            <script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
            <script src="<?php echo base_url() . 'assets/plugins/jsignature/jSignature.min.js'; ?>"></script>
            <script src="<?php echo base_url() . 'assets/plugins/jsignature/flashcanvas.js'; ?>"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/across-tabs/1.4.0/across-tabs.min.js"></script>


            <script>
                //signature
                $(document).ready(function() {
                    var sign = $("#signature");


                    //Init
                    sign.jSignature({
                        'height': 100,
                        'width': 565,
                        'lineWidth': 2,
                        'UndoButton': true,
                        'signatureLine': false,

                    });
                    sign.jSignature("reset");

                    $(".form_control_modal").on("input", function(event) {
                        if (event.target.value == "" || event.target.value == null || event.target.value == 0) {
                            event.target.classList.add("subject");
                            event.target.parentElement.parentElement.querySelector('p').classList.remove("alert");
                        } else {
                            event.target.parentElement.parentElement.classList.remove("error");
                            event.target.parentElement.parentElement.querySelector('p').classList.add("alert");
                            event.target.classList.remove("subject");

                        }
                    });


                    $("#signature_form").submit(function(event) {
                        event.preventDefault();
                        var firstName = $("#f_name_id").val();
                        console.log("fname", firstName);
                        var lastName = $("#l_name_id").val();
                        var email = $("#email_id").val();
                        var data1 = sign.jSignature("getData", "native");
                        var signature_data = sign.jSignature('getData', 'svg');
                        var signature = "data:" + signature_data[0] + "," + signature_data[1];
                        console.log(data1.length);
                        if (firstName == "") {
                            $("#f_name_id").addClass("subject");
                            $("#alert_1").removeClass("alert");
                        } else if (lastName == "") {
                            $("#l_name_id").addClass("subject");
                            $("#alert_2").removeClass("alert");
                        } else if (email == "") {
                            $("#email_id").addClass("subject");
                            $("#alert_3").removeClass("alert");
                        } else if (data1.length == 0 || data1.length == undefined) {
                            $("#signature").addClass("signsubject");
                            $("#alert_4").removeClass("alert");
                        } else {
                            console.log(signature);
                            $.ajax({
                                url: "<?php echo url_to("erp.contract.sign") ?>",
                                type: "POST",
                                data: {
                                    cont_id: <?= $contract_num ?>,
                                    firstName: firstName,
                                    lastName: lastName,
                                    email: email,
                                    sign: signature
                                },
                                success: function(response) {
                                    if (response.success) {
                                        $("#Sigining_modal").modal('hide');
                                        $("#signature_form")[0].reset();
                                        location.reload();

                                    } else {
                                        $("#signature_form")[0].reset();

                                    }
                                }
                            })
                        }

                    })

                });
                //commend api fetch 
                function api_commend() {
                    $.ajax({
                        url: "<?php echo url_to("erp.contractCommendfetch") ?>",
                        type: "GET",
                        data: {
                            id: <?= $contract_num ?>
                        },
                        success: function(response) {
                            var data = JSON.parse(response);
                            commend_box(data);
                        }

                    });
                }
                //
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

                function commend_box(data) {
                    $("#commend-cont").empty();
                    let commend_box = '<div class="commend-log d-flex border border-black"><img src="<?php echo base_url() . 'uploads/contract/user-placeholder.jpg' ?>" alt="" class="commend-box-pic ">' +
                        '<div class="commend-log-container ">' +
                        '<div class="d-flex commend-log-1">' +
                        '<div><b>{user_name}</b></div>' +
                        '<div> - </div>' +
                        '<div class="time"><p class="p-time">{time}</p></div>' +
                        '</div>' +
                        '<div class="mt-2">' +
                        '<p>{comment}</p>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                    $.each(data.commends, function(index, commend) {
                        var copy = commend_box;
                        var datestring = commend.c_date;
                        var date = moment(datestring, 'YYYY-MM-DD HH:mm:ss');
                        var date_format = dateformat(date);
                        copy = copy.replace('{user_name}', commend.f_name + '' + commend.l_name);
                        copy = copy.replace('{time}', date_format);
                        copy = copy.replace('{comment}', commend.commend);
                        $("#commend-cont").append(copy);
                    });

                }


                var que = document.querySelectorAll(".nav-link");
                que.forEach((q, index) => {
                    q.addEventListener('click', function(event) {
                        que.forEach((qu, index) => {
                            qu.classList.remove(".active")
                        });
                        q.classList.add(".active");
                        let line = document.querySelector(".line");
                        line.style.width = event.target.offsetWidth + "px";
                        line.style.left = 1 + event.target.offsetLeft + "px";
                        console.log(event.target.offsetWidth);
                    });
                });

                $(document).ready(function() {
                    var sign = <?= $contract_details->signed ?>;
                    if (sign > 0) {
                        $("#signed").removeClass("none");
                        $("#sign-btn").addClass("none");
                    }
                    api_commend();
                    $("#comment_form").submit(function(event) {
                        event.preventDefault();
                        var comment = $("#comment_box").val();
                        if (comment != "") {

                            let formdata = $(this).serialize();
                            $.ajax({
                                url: '<?php echo url_to("erp.contractCommendAdd", $contract_num) ?>',
                                type: 'POST',
                                data: formdata,
                                success: function(response) {
                                    api_commend();
                                    if (response.success) {
                                        $("#comment_form")[0].reset();
                                    } else {
                                        $("#comment_form")[0].reset();

                                    }
                                },
                                error: function(xhr, target, error) {
                                    console.error("Oops : !", error);
                                }
                            })
                        }
                    });
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