<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div>
    <h1>Dashboard</h1>
</div>
<div class="mb-2">
    <h2 id="greetings"></h2>
</div>
<div class="mx-2 mb-5">
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="pieChart">
                <canvas id="pieChart"></canvas>
            </div>
        </div>

        <div class="col-lg-8 col-md-6 mt-3 mt-md-0">
            <div class="announcement_cont">
                <div class="mb-2">
                    <h1 class="a-h">Announcement</h1>
                </div>
                <hr>
                <div class="a-body" id="announcement_container">
                    <!-- Announcements Using api -->
                </div>

            </div>
        </div>
    </div>
</div>

<div class="mx-2 mb-5">
    <div class="row">
        <div class="col-lg-4 col-md-6 mt-3 mt-md-0">
            <div class="leadstatusChart">
                <canvas id="leadstatusChart"></canvas>
            </div>
        </div>
        <div class="col-lg-8 col-md-6 mt-3 mt-md-0">
            <table id="orderStatusTable" class="display text-center">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Amount Rs.</th>
                        <th>Tax Rs.</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <p></p>
        </div>
    </div>
</div>


<!-- Announcement -->


<!-- -->
<!-- Calender -->
<div class="" id="calendar">

</div>
<!--  -->
<!-- calender modal -->

<button id="test_model" data-toggle="modal" data-target="#calendar_modal" hidden></button>
<div class="modal fade" id="calendar_modal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="eventModalLabel">Add new event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                    <span aria-hidden="true" class="x-box"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                            height="16" fill="grey" class="x-box bi bi-x-lg" viewBox="0 0 16 16">
                            <path
                                d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                        </svg></span>
                </button>
            </div>
            <hr>
            <?php echo
                form_open(url_to("erp.calender_modal"), array(
                    "class" => "flex",
                    "id" => "calendar_form"
                ));
            ?>
            <div class="modal-body">
                <div class="form-group field-required">
                    <!-- name -->
                    <label class="form-label" id="form-alert">Event title
                        <small class="req text-danger">*</small>
                    </label>
                    <input type="text" name="eventid" id="id_event" hidden>
                    <?php echo form_input('title', '', 'id="event_name_id" class = "form_control"');
                    ?>
                    <p class="alertevent_1 text-danger p-0" id="alertevent"><?php echo 'This field is required.'; ?></p>

                    <!-- description -->
                    <label class="form-label"> description </label>
                    <?php echo form_textarea('description', "", 'class = "form-control-text-area mb-1" id="event_description"'); ?>
                    <!--  -->

                    <div class="d-flex">
                        <!-- start date -->
                        <div class="date-start">
                            <label class="form-label">
                                <small class="req text-danger">*</small>
                                <?php echo "Start Date"; ?>
                            </label>
                            <input type="datetime-local" name="start_date" value="" id="start-date"
                                class="form-control-startdate">
                            <p class="alertevent_2 text-danger p-0" id="alertevent_1">
                                <?php echo 'This field is required.'; ?>
                            </p>
                        </div>
                        <!--  -->
                        <!-- end date -->
                        <div class="date-end">
                            <label class="form-label">
                                <?php echo "End Date"; ?></label>
                            <input type="datetime-local" name="end_date" value="" id="end-date"
                                class="form-control-enddate">
                            <?php //echo  form_input('end_date', '', 'id="end-date" class="form-control-enddate" type="datetime"'); 
                            ?>
                        </div>
                        <!--  -->
                    </div>
                    <!-- Notification -->
                    <label class="form-label">
                        <small class="req text-danger">*</small>
                        <?php echo "Notification"; ?>
                    </label>
                    <div class="d-flex mb-3">
                        <div class="notification-group">
                            <input type="number" name="notification" id="notify" value="30" id="notification"
                                class="form-control-notify" step="1" min="30">
                            <p class="alertevent_3 text-danger p-0" id="alertevent_2">
                                <?php echo 'This field is required.'; ?>
                            </p>
                        </div>
                        <!--  -->
                        <!-- Timing -->
                        <div class="timing-group">
                            <div class="btn-group d-flex mb-2" role="group">
                                <select name="timing" id="reminder-time" class="form-control">
                                    <option value="Minutes">Minutes</option>
                                    <option value="Hours">Hours</option>
                                    <option value="Days">Days</option>
                                    <option value="Weeks">Weeks</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <!-- Color input -->
                    <div class="cont-color mb-3">
                        <label class="form-label">
                            <?php echo "Event Color"; ?>
                        </label>
                        <input type="color" name="color_input" id="color-input" class="form-control-color"
                            pattern="#[0-9a-fA-F]{6}">
                    </div>
                    <!-- Check box -->
                    <div class="tw-flex tw-justify-between tw-items-center mb-3">
                        <div class="checkbox checkbox-primary checkbox-inline">
                            <label for="event" class="form-label">Public Event</label>
                            <input type="checkbox" id="event" name="public-event">
                        </div>
                    </div>
                    <!--  -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="insert" class="btn bg-primary ">Save changes</button>
                    <button type="submit" id="update" class="btn bg-success update">Update</button>
                    <button type="button" class="btn bg-danger delete" id="delete">delete</button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!--  -->
<p class="mt-5" style="font-weight: bold;"><i class="fa-solid fa-street-view"></i> OVERVIEW</p>
<div class="mt-3">
    <table id="overView" class="display text-center">
        <thead>
            <tr>
                <th>QUOTATIONS OVERVIEW</th>
                <th>INVOICE OVERVIEW</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <a href="<?php echo url_to('erp.sale.quotations'); ?>">
                        <div class="side">
                            <p><?= $quotationCount[0]['quotation_count'] ?? 0 ?>&nbsp;<?= $quotationCount[0]['status'] = 4 ? "Converted" : "" ?>
                            </p>
                            <p><?= number_format(($quotationCount[0]['quotation_count'] ?? 0 / $quotationCount[0]['total']) * 100, 2) ?>%
                            </p>
                        </div>
                        <div class="bg-dark border-rounded mt-1">
                            <div class="bg-danger"
                                style="height: 4px; width:<?= ($quotationCount[0]['quotation_count'] ?? 0 / $quotationCount[0]['total']) * 100 ?>%">

                            </div>
                        </div>
                    </a>
                </td>
                <td>
                    <a href="<?= url_to('erp.sale.invoice'); ?>">
                        <div class="side">
                            <p><?= $invoiceCount[0]['invoice_count'] ?>&nbsp;<?= $quotationCount[0]['status'] = 2 ? "Paid" : "" ?>
                            </p>
                            <p><?= number_format(($invoiceCount[0]['invoice_count'] / $invoiceCount[0]['total']) * 100, 2) ?>%
                            </p>
                        </div>
                        <div class="bg-dark border-rounded">
                            <div class="bg-one"
                                style="height: 4px; width:<?= ($invoiceCount[0]['invoice_count'] / $invoiceCount[0]['total']) * 100 ?>%">

                            </div>
                        </div>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="<?php echo url_to('erp.sale.quotations'); ?>">
                        <div class="side">
                            <p><?= $quotationCount[0]['quotation_count'] ?>&nbsp;<?= $quotationCount[0]['status'] = 4 ? "Converted" : "" ?>
                            </p>
                            <p><?= number_format(($quotationCount[0]['quotation_count'] / $quotationCount[0]['total']) * 100, 2) ?>%
                            </p>
                        </div>
                        <div class="bg-dark border-rounded">
                            <div class="bg-danger"
                                style="height: 4px; width:<?= ($quotationCount[0]['quotation_count'] / $quotationCount[0]['total']) * 100 ?>%">

                            </div>
                        </div>
                    </a>
                </td>
                <td>
                    <a href="<?= url_to('erp.sale.invoice'); ?>">
                        <div class="side">
                            <p><?= $invoiceCount[0]['invoice_count'] ?>&nbsp;<?= $quotationCount[0]['status'] = 3 ? "Overdue" : "" ?>
                            </p>
                            <p><?= number_format(($invoiceCount[0]['invoice_count'] / $invoiceCount[0]['total']) * 100, 2) ?>%
                            </p>
                        </div>
                        <div class="bg-dark border-rounded">
                            <div class="bg-two"
                                style="height: 4px; width:<?= ($invoiceCount[0]['invoice_count'] / $invoiceCount[0]['total']) * 100 ?>%">

                            </div>
                        </div>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="<?php echo url_to('erp.sale.quotations'); ?>">
                        <div class="side">
                            <p><?= $quotationCount[0]['quotation_count'] ?? 0 ?>&nbsp;<?= $quotationCount[0]['status'] = 4 ? "Converted" : "" ?>
                            </p>
                            <p><?= number_format(($quotationCount[0]['quotation_count'] / $quotationCount[0]['total']) * 100, 2) ?>%
                            </p>
                        </div>
                        <div class="bg-dark border-rounded mt-1">
                            <div class="bg-danger"
                                style="height: 4px; width:<?= ($quotationCount[0]['quotation_count'] / $quotationCount[0]['total']) * 100 ?>%">

                            </div>
                        </div>
                    </a>
                </td>
                <td>
                    <a href="<?= url_to('erp.sale.invoice'); ?>">
                        <div class="side">
                            <p><?= $invoiceCount[2]['invoice_count'] ?? 0 ?>&nbsp;<?= $quotationCount[0]['status'] = 1 ? "Partially Paid" : "" ?>
                            </p>
                            <p><?= number_format(($invoiceCount[2]['invoice_count'] ?? 0 / $invoiceCount[0]['total'] ?? 0) * 100, 2) ?>%
                            </p>
                        </div>
                        <div class="bg-dark border-rounded">
                            <div class="bg-three"
                                style="height: 4px; width:<?= ($invoiceCount[2]['invoice_count'] ?? 0 / $invoiceCount[0]['total'] ?? 0) * 100 ?>%">

                            </div>
                        </div>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<!-- </div> -->
</main>
<!-- <script src="<?php // echo base_url() . 'assets/js/jquery.min.js'; 
?>"></script> -->
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="<?= base_url() . 'assets/plugins/fullcalendar/index.js' ?>"></script>

<script>
    $(document).ready(function () {
        $('#overView').DataTable();
    });

    const leadstatusCounts = <?php echo json_encode($leadsCountStatus); ?>;
    const leadLabels = ['OPEN', 'CONTACTED', 'WORKING', 'DISQUALIFIED', 'CUSTOMER'];
    const leadData = Object.values(leadstatusCounts);

    // Create Chart.js doughnut chart
    const leadTableId = document.getElementById('leadstatusChart').getContext('2d');
    const leadstatusChart = new Chart(leadTableId, {
        type: 'doughnut',
        data: {
            labels: leadLabels,
            datasets: [{
                data: leadData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(255, 205, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(39, 245, 174, 0.8)',
                ],
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Lead Status',
                },
            },
        },
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var orderStatus = {
            0: "Created",
            1: "Invoiced",
            2: "Completed",
            3: "Cancelled"
        };

        var table = $('#orderStatusTable').DataTable({
            "ajax": {
                "url": "<?= url_to('erp.orderstatus'); ?>",
                "type": "GET",
                "dataSrc": ""
            },
            "dom": 'Bfrtip',
            "buttons": [
                'excel'
            ],
            "columns": [{
                "data": "code"
            },

            {
                "data": "status",
                "render": function (data) {
                    return '<span class="' + getOrderStatusClass(data) + '">' + orderStatus[data] + '</span>';
                }
            },
            {
                "data": "amount"
            },
            {
                "data": "tax"
            }
            ]
        });

        function getOrderStatusClass(status) {
            switch (parseInt(status)) {
                case 0:
                    return 'created-text';
                case 1:
                    return 'invoiced-text';
                case 2:
                    return 'completed-text';
                case 3:
                    return 'cancelled-text';
                default:
                    return '';
            }
        }
    });


    const statusCounts1 = <?php echo json_encode($statusCounts); ?>;
    const data2 = Object.values(statusCounts1);

    const data1 = {
        labels: ['Created', 'Progress', 'On Hold', 'Completed', 'Cancelled'],
        datasets: [{
            label: '',
            data: data2,
            borderWidth: 1,
            backgroundColor: ['#0f9cfb', '#ff4069', '#2acccc', '#ffc338', '#b2b6be', '#bd5a1e', '#9edf9c'],
        }]
    };

    const config = {
        type: 'pie',
        data: data1,
        options: {
            plugins: {
                legend: {
                    onHover: handleHover,
                    onLeave: handleLeave
                },
                title: {
                    display: true,
                    text: 'Statistics by Project Status',
                },
            }
        }
    };

    const config2 = {
        type: 'polarArea',
        data: data1,
        options: {
            plugins: {
                legend: {
                    onHover: handleHover,
                    onLeave: handleLeave
                },
                title: {
                    display: true,
                    text: 'Statistics by Project Status',
                },
            }
        }
    };

    const ctx1 = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(ctx1, config2);

    function handleHover(event, legendItem) {
        // console.log('Hovered:', legendItem.text);
    }

    function handleLeave(event, legendItem) {
        // console.log('Left:', legendItem.text);
    }


    let date = new Date();
    let hour = date.getHours();
    if (hour < 12) {
        console.log("Good Morning ".user_name);
        document.getElementById("greetings").innerText = "Good Morning <?php echo get_user_name(); ?>";
    } else if (hour < 18) {
        console.log("Good Afternoon ".user_name);
        document.getElementById("greetings").innerText = "Good Afternoon <?php echo get_user_name(); ?>";
    } else if (hour >= 18) {
        console.log("Good Evening ".user_name);
        document.getElementById("greetings").innerText = "Good Evening <?php echo get_user_name(); ?>";
    }

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

<script>
    function getAnouncementData() {

        $.ajax({
            url: "<?= url_to('erp.ajaxannouncement') ?>",
            method: 'GET',
            success: function (response) {
                var data = JSON.parse(response);
                //    console.log(data.announcement);

                formatElement(data.announcement)
            }
        });
    }

    function formatElement(data) {

        $('#announcement_container').empty();

        html_element = '<div class="child d-flex">' +
            '<div class="child-1">' +
            '<p class="day">{day}</p>' +
            '<p class="month">{date}</p>' +
            '</div>' +
            '<div class="child-2">' +
            '<p class="a_subject">{subject}</p>' +
            '<p class="a_message">{message}</p>' +
            '</div>' +
            '<div class="child-3">' +
            '<button class="btn-x d-none"><i class="fa-solid fa-x fa-xs" style="color: #616161;"></i></button>' +
            '</div>' +
            '</div>';

        $.each(data, function (index, item) {

            var html_element_copy = html_element;
            var message = item.message.replace(/<p[^>]*>|<\/p>/g, '');
            // Replace placeholders with actual data values
            html_element_copy = html_element_copy.replace('{day}', getDayOfWeek(item.dateadded));
            html_element_copy = html_element_copy.replace('{date}', item.dateadded.split(" ")[0]);
            html_element_copy = html_element_copy.replace('{subject}', item.name);
            html_element_copy = html_element_copy.replace('{message}', message);

            // Append the modified HTML element to the container
            // $('.container').append(html_element_copy);

            $('#announcement_container').append(html_element_copy);
        });
    }

    // getAnouncementData()

    function getDayOfWeek(dateString) {
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var date = new Date(dateString);
        var dayIndex = date.getDay();
        return days[dayIndex];
    }

    // function removeTimeFromDate(dateString) {
    //     // Convert the string to a Date object
    //     var date = new Date(dateString);

    //     // Set the time portion to 00:00:00
    //     date.setHours(0, 0, 0, 0);

    //     // Return the date without the time portion
    //     return date;
    // }

    setInterval(getAnouncementData, 1000000);
    getAnouncementData()
    // calender

    document.addEventListener('DOMContentLoaded', function () {
        events();
    });

    function events() {

        $.ajax({
            url: "<?php echo url_to('erp.calenderEventAjax') ?>",
            type: "POST",
            success: function (response) {
                console.log("hi");
                console.log(response);
                var events = response.result.map(function (event) {
                    return {
                        start: event.start,
                        end: event.end,
                        title: event.title,
                        color: event.color,
                        id: event.id
                    }
                });
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    initialDate: '2024-01-12',
                    navLinks: true, // can click day/week names to navigate views
                    selectable: true,
                    selectMirror: true,
                    select: function (arg) {
                        now = new Date();
                        hour = now.getHours();
                        min = now.getMinutes();
                        reset_modal();
                        test(arg, hour, min, method = 0);
                    },
                    editable: true,
                    dayMaxEvents: true, // allow "more" link when too many events
                    events: events,
                    eventClick: function (arg) {
                        reset_modal();
                        exist_data(arg);
                    }
                });

                calendar.render();
            },
            error: function (xhr, status, error) {

            }
        });
    }
    //modal click on
    var startdate;
    var formSubmit = "";

    function test(args, hour = "", min = "", method) {
        startdate = args.start;
        value = new Date(startdate);
        year = value.getFullYear();
        month = ('0' + (value.getMonth() + 1)).slice(-2);
        date = ('0' + (value.getDate())).slice(-2);
        hour = ('0' + hour).slice(-2);
        minute = ('0' + min).slice(-2);
        start_date = year + '-' + month + '-' + date + 'T' + hour + ':' + minute;
        // console.log(start_date);
        if (method == 0) {
            $('#test_model').click();
            $("#update").addClass("update");
            $("#delete").addClass("delete");
            $("#insert").removeClass("insert");
            $("#eventModalLabel").text("Add new event");
            $("#start-date").val(start_date);
            formSubmit = "insert";
        } else if (method == 1) {
            $("#update").removeClass("update");
            $("#delete").removeClass("delete");
            $("#insert").addClass("insert");
            $('#test_model').click();
            // $("#eventid").val(id);
            $("#eventModalLabel").text("Update Event");
            formSubmit = "update";
        }


    }

    function exist_data(arg) {

        $.ajax({
            url: "<?php echo url_to('erp.calender_exist_modal') ?>",
            type: "GET",
            data: {
                id: arg.event.id
            },
            success: function (response) {
                var data = JSON.parse(response);
                test(arg, "", "", method = 1);
                $("#id_event").val(data.event_id);
                $("#event_name_id").val(data.title);
                $("#event_description").val(data.description);
                $("#start-date").val(data.Start_data);
                $("#end-date").val(data.end_data);
                $("#notify").val(data.reminder_before);
                $("#reminder-time").val(data.reminder_before_type);
                $("#color-input").val(data.event_color);
                if (data.public_event == 1) {
                    $("#event").prop("checked", true);
                } else {
                    $("#event").prop("checked", false);
                }
            },
            error: function (xhr, target, error) {
                console.error("udhai", error);
            }
        });
    }

    //reset modal
    function reset_modal() {
        $("#alertevent").addClass("alertevent_1");
        $("#alertevent_2").addClass("alertevent_3");
        $("#alertevent_1").addClass("alertevent_2");
        $('#notify').removeClass("sub");
        $("#start-date").removeClass("sub");
        $("#event_name_id").removeClass("subject");
        $("#calendar_form")[0].reset();
    }
    $("#close").on('click', function () {
        $("#calendar_form")[0].reset();
    });
    //delete api
    $("#delete").on("click", function () {
        console.log("hit");
        var event_id = $('#id_event').val();
        var text = "Are you sure want to delete event?";
        if (confirm(text)) {
            $.ajax({
                url: "<?php echo url_to('erp.calenderDelete') ?>",
                type: "POST",
                data: {
                    event_id: event_id
                },
                success: function (response) {
                    console.log(response);
                    var alert = new ModalAlert();
                    // console.log(response);
                    if (response.success) {
                        $("#calendar_modal").modal("hide");
                        alert.invoke_alert("deleted", "success");
                        events();
                    } else {
                        alert.invoke_alert("error", "error");
                        events();
                    }
                }
            });
        }
    });
    //Modal submit
    $(document).ready(function () {
        $("#calendar_form").submit(function (event) {
            event.preventDefault();
            var title = $("#event_name_id").val();
            var notify = $('#notify').val();
            var sdate = $("#start-date").val();
            var color = $("#color-input").val();
            if (!title) {
                $("#alertevent").removeClass("alertevent_1");
                $("#event_name_id").addClass("subject");
            } else if (!sdate) {
                $("#alertevent_1").removeClass("alertevent_2");
                $("#start-date").addClass("sub");
            } else if (!notify) {
                $("#alertevent_2").removeClass("alertevent_3");
                $('#notify').addClass("sub");
            } else {
                var formdata = $(this).serialize();
                var forminsertUrl = '<?php echo url_to("erp.calender_modal") ?>';
                var formupdateUrl = '<?php echo url_to("erp.calender_modal_update") ?>';
                var formUrl = "";
                if (formSubmit == "insert") {
                    formUrl = forminsertUrl;
                } else if (formSubmit == "update") {
                    formUrl = formupdateUrl;
                }
                console.log(formUrl);
                $.ajax({
                    url: formUrl,
                    type: "POST",
                    data: formdata,
                    success: function (response) {
                        if (response.success) {
                            $("#calendar_modal").modal("hide");
                            $("#calendar_form")[0].reset();
                            flash = new ModalAlert();
                            if (formSubmit == "insert") {
                                flash.invoke_alert('success', 'success');
                                events();
                            } else if (formSubmit == "update") {
                                flash.invoke_alert('updated', 'success');
                                events();
                            }

                        } else {
                            $("#calendar_modal").modal("hide");
                            flash = new ModalAlert();
                            flash.invoke_alert('error', 'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Oops", error);
                    }

                });
            }
        });
    });
</script>
<style>
    .a-h {
        color: #313939;
        text-transform: capitalize;
        font-size: .9375 rem;
        font-weight: 700;
        position: relative;
    }

    .a-h::before {
        content: "";
        position: absolute;
        height: 2rem;
        width: .2rem;
        background: linear-gradient(to bottom, #0CE2DA 50%, rgba(12, 226, 93, .5) 50%);
        border-radius: .5rem;
        inset-block-start: .15rem;
        inset-inline-start: -.65rem;
    }

    .announcement_cont {
        padding: 20px;
        width: 100%;
        max-height: 330px;
        border: 1px solid white;
        border-radius: 5px;
        margin: 0px 10px;
        border: 0.5px solid #d3d3d3;
        box-shadow: 2px 2px 15px 6px #d3d3d361;
        overflow: hidden;
    }

    #announcement_container {
        overflow-y: scroll;
        height: 235px;
    }

    .child-1 {
        width: 100px;
    }

    .child-2 {
        width: 800px;
    }

    .child-3 {
        width: 100px;
        text-align: right;
    }

    .child-1>p {
        padding: 10px;
        text-align: center;
    }

    .child-2>p {
        padding: 10px;
        text-align: left;
    }

    .day {
        font-size: 15px;
        font-weight: 700;
        color: #0A76F9;
    }

    .month {
        font-size: 10px;
        font-weight: 500;
    }

    .a_subject {
        font-size: 15px;
        font-weight: 700;
        color: #063970;
    }

    .a_message {
        font-size: 13px;
        font-weight: 500;
        color: #342E2C;
    }

    .child {
        justify-content: left;
        height: 90px;
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ededed;
        margin-top: 20px;
    }

    .btn-x {
        border: none;
        background: transparent;
        cursor: pointer;
    }

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

    .form-control-startdate,
    .form-control-enddate,
    .form-control-notify {
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

    .sub {
        display: inline-block;
        width: 100%;
        padding: 7px 13px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #C83B3B;
        border-radius: 4px;
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

    .form-control-color {
        display: inline-block;
        width: 10%;
        padding: 6px 12px;
        height: 30px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .date-start,
    .date-end,
    .notification-group,
    .timing-group {
        padding: 0;
        width: 50%;
    }

    .date-start,
    .notification-group {
        padding: 0px 10px 0px 0px;
    }

    .ck-balloon-panel {
        display: none !important;
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

    .calendar {
        display: none;
    }

    .alertevent_1 {
        display: none;
    }

    .alertevent_2 {
        display: none;
    }

    .alertevent_3 {
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

    .fc-event-time {
        display: none;
    }

    .close {
        border: none;
        height: 25px;
        background: transparent;

    }

    .close:hover {
        background: red;
        color: white;
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

    .update {
        display: none;
    }

    .delete {
        display: none;
    }


    .insert {
        display: none;
    }

    /* #orderStatusTable_wrapper .dataTables_scroll,
    #orderStatusTable_wrapper .dataTables_scrollBody {
        width: 100%;
    } */

    /* Reduce DataTable size */
    #orderStatusTable {
        width: 50%;
        max-height: 10px;
    }

    .alldiv #overView_filter {
        display: none;
        width: 50%;
    }

    #overView_length {
        display: none;
    }

    .pieChart {
        display: block;
        box-sizing: border-box;
        border: 0.5px solid #d3d3d3;
        box-shadow: 2px 2px 15px 6px #d3d3d361;
        padding: 8px;
        border-radius: 8px;
    }

    .leadstatusChart {
        display: block;
        box-sizing: border-box;
        border: 0.5px solid #d3d3d3;
        box-shadow: 2px 2px 15px 6px #d3d3d361;
        width: auto;
        padding: 8px;
        border-radius: 8px;
    }
    #orderStatusTable_wrapper{
        max-height: 332px;
    }
</style>
</body>

</html>