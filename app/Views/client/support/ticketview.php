<!-- ========== title-wrapper start ========== -->
<!-- <link rel="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" /> -->
<style>
    div.dt-container div.dt-layout-table>div {
        padding: 20px 5px;
    }

    .st {
        padding: 4px 8px;
        border-radius: 32px;
        cursor: pointer;
        font-size: 13px;

    }

    .error {
        border: 1px solid red;
    }

    .st_primary {
        background: #2196f31f;
        color: #16b1ff;
    }

    .st_violet {
        background: #9155fd1f;
        color: #9155fd;
    }

    .st_dark {
        background: #06060626;
        color: #8a8d93;
    }

    .st_danger {
        background: #ff572224;
        color: #ff4c51;
    }

    .st_success {
        background: #8bc34a2e;
        color: #56ca00;
    }

    .project_cont {
        padding: 4px 18px;
        border-radius: 12px;
        font-size: 13px;
        height: 50px;
        width: 100%;
        background: #2196f31f;
        border: 1px solid #16b1ff;
    }

    .commend-log {
        padding: 10px 0px 10px 10px;
        gap: 10px;
        margin-top: 10px;
        border-radius: 8px;
        position: relative;
    }

    .commend-log.client {
        background: #8bc34a2e;
        transform: translateX(-18px);
    }

    .commend-log.client::after {
        content: "";
        position: absolute;
        top: 10px;
        left: -21px;
        border: 10px solid transparent;
        border-right-color: #34db4a;
        /* border-right-color: #3498db; */
        /* Same as background color */
    }

    .commend-log.staff {
        background: #2196f31f;
        transform: translateX(18px);
    }

    .commend-log.staff::before {
        content: "";
        position: absolute;
        top: 10px;
        right: -21px;
        border: 10px solid transparent;
        border-right-color: #16b1ff;
        transform: rotate(180deg);
        /* border-right-color: #3498db; */
        /* Same as background color */
    }

    .commend-box-pic {
        height: 5vh;
        width: 5vh;
        border-radius: 50%;
        border: 2px solid #15b851 !important;
    }

    table {
        border-collapse: separate;
        /* Required for border-spacing to work */
        border-spacing: 0 10px;
        /* 10px space between rows */
    }

    .text-dark {
        color: #5a5a5a !important;
    }

    .plr {
        padding: 0px 5px;
    }

    textarea.form-control {
        min-height: 99px;
    }

    .p-time {
        font-size: 13px;
        color: #16b1ff;
    }

    .text-justify {
        text-align: justify;
        position: relative;
        left: -10px !important;
        top: 0px;
    }

    .card-style {
        max-height: 500px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
</style>
<div class="title-wrapper pt-30">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="title">
                <h2>Tickets Summary (<?= session('client_cust_id') ?> )</h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">
                            <a href="#0">Support</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- 0 => "OPEN",
        1 => "IN PROGRESS",
        2 => "SOLVED",
        3 => "CLOSED" -->
<!-- ========== title-wrapper end ========== -->

<?//= var_dump($ticketdetails) ?>
<div class="row">
    <?php if (isset($ticketdetails["project_name"])) { ?>
        <div class="col-lg-12">
            <div class="project_cont">
                <p style="line-height: 41px;">This ticket is linked to project : <span
                        style="color : black;"><?= $ticketdetails["project_name"] ?></span></p>
            </div>
        </div>
    <?php } ?>
    <div class="col-lg-6 mt-3">
        <div class="card-style mb-30">
            <h5>Ticket Info</h5>
            <table class="mt-2">
                <tbody>
                    <tr>
                        <td>
                            <h6 class="text-dark">Subject</h6>
                        </td>
                        <td class="plr"> - </td>
                        <td class="plr">
                            <?= $ticketdetails["subject"] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6 class="text-dark">Created On</h6>
                        </td>
                        <td class="plr"> - </td>
                        <td class="plr"> <?= $ticketdetails['created_at'] ?> </td>
                    </tr>
                    <tr>
                        <td>
                            <h6 class="text-dark">Priority</h6>
                        </td>
                        <td class="plr"> - </td>
                        <td class="plr"> <?= $ticketdetails["priority"] ?> </td>
                    </tr>
                    <tr>
                        <td>
                            <h6 class="text-dark">Status</h6>
                        </td>
                        <td class="plr"> - </td>
                        <td class="plr"> <?= $ticketdetails["status"] ?> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-6 mt-3">
        <div class="card-style mb-3">
            <h5 class="mb-3">Add reply to this ticket</h5>
            <form action="" method="post">
                <input type="hidden" name="ticket_id" id="ticket_id" value="<?= $ticketdetails["ticket_id"] ?>">
                <textarea name="comment" id="comment" class="form-control"></textarea>
                <button id="btn" type="button" class="btn btn-primary mt-2">Reply</button>
            </form>
        </div>
        <div class="col-lg-12">
            <div class="card-style mb-30" id="commend-cont">

            </div>
        </div>
        <!--  -->
        <!-- <div class="commend-log d-flex border border-black">
            <img src="<?php //echo base_url() . 'uploads/contract/user-placeholder.jpg' ?>" alt=""
                class="commend-box-pic">
            <div class="commend-log-container">
                <div class="d-flex commend-log-1">
                    <div><b>{user_name}</b></div>
                    <div> - </div>
                    <div class="time">
                        <p class="p-time">{time}</p>
                    </div>
                </div>
                <div class="mt-2">
                    <p>{comment}</p>
                </div>
            </div>
        </div> -->

    </div>
    <!-- End Col -->
</div>
<script src="<?= base_url() . 'front-assets/' ?>js/custom.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    $(document).ready(function () {
        $("#btn").on("click", function (e) {
            let id = $("#ticket_id").val();
            let comment = $("#comment").val();
            if (id) {
                if (comment == "") {
                    $("#comment").addClass("error");
                } else {
                    $("#comment").removeClass("error");

                    $.ajax({
                        url: "<?= url_to('front.ticket.add.comment') ?>",
                        method: "post",
                        data: {
                            ticketId: id,
                            message: comment
                        },
                        success: function (response) {
                            let alert = new Alert();
                            if (response.success) {
                                alert.alert_invoke("success", response.message);
                                setTimeout(() => {
                                    location.reload();
                                }, 1500)
                            } else {
                                alert.alert_invoke("error", response.message);
                                setTimeout(() => {
                                    location.reload();
                                }, 1500)
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    })
                }

            }
        });

        comment_fetch_api();
    })

    function comment_fetch_api() {
        let id = $("#ticket_id").val();
        $.ajax({
            url: "<?= url_to('front.ticket.comment.fetch') ?>",
            method: "post",
            data: {
                ticketId: id,
            },
            success: function (response) {
                // let alert = new Alert();
                console.log(response.success)
                if (response.success) {
                    // alert.alert_invoke("success", response.message);
                    commend_box(response.data);
                } else {
                    console.log(response.data)
                    // alert.alert_invoke("error", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        })


    }

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
        let commend_box = '<div class="commend-log {userbox} d-flex border border-black"><img src="<?php echo '{image_url}' ?>" alt="profile-pic" class="commend-box-pic ">' +
            '<div class="commend-log-container ">' +
            '<div class="d-flex commend-log-1">' +
            '<div><b>{user_name}</b></div>' +
            '<div> - </div>' +
            '<div class="time"><p class="p-time">{time}</p></div>' +
            '</div>' +
            '<div class="mt-2">' +
            '<p class="p-1 text-justify">{comment}</p>' +
            '</div>' +
            '</div>' +
            '</div>';

        // var count = 0;
        console.log("data => ", data.length)
        if(data.length > 0){
            $.each(data, function (index, commend) {
                var copy = commend_box;
                var datestring = commend.created_at;
                var date = moment(datestring, 'YYYY-MM-DD HH:mm:ss');
                var date_format = dateformat(date);
                var image = '<?= base_url() ?>' + 'uploads/contract/user-placeholder.jpg';
                var boxcolor = "staff";
                var name = commend.related_id;
                if (commend.related_type == "client") {
                    image = '<?= get_client_profile_url() ?>';
                    boxcolor = "client";
                    name = "You";
                }
    
                copy = copy.replace('{user_name}', name);
                copy = copy.replace('{userbox}', boxcolor);
                copy = copy.replace('{image_url}', image);
                copy = copy.replace('{time}', date_format);
                copy = copy.replace('{comment}', commend.comment);
                $("#commend-cont").append(copy);
            });
        }else{
            let commend_box = "<p class='text-center p-2 text-danger'>Comments is empty!</p>";
            $("#commend-cont").append(commend_box);
        }

        // if (count > 0) {
        //     $("#comment_count").text(count);
        // } else {
        //     $("#comment_count").text(0);
        // }

    }



    <?php if (session()->getFlashdata('success')) { ?>
        let alert = new Alert();
        alert.alert_invoke('success', '<?= session()->getFlashdata('success') ?>');
    <?php } elseif (session()->getFlashdata('error')) { ?>
        let alert = new Alert();
        alert.alert_invoke('error', '<?= session()->getFlashdata('error') ?>');
    <?php } ?>
    
</script>