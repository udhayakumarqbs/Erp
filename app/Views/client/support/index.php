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

    .custom-tooltip {
        position: absolute;
        background: #333;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        pointer-events: none;
        z-index: 1000;
        white-space: nowrap;
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
<div class="row">
    <?php $open = isset($ticketsummary["OPEN"]) ? $ticketsummary["OPEN"] : 0 ?>
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="icon-card mb-30">
            <div class="content d-flex flex-column justify-content-between align-items-left">
                <h3 class="text-bold mb-10">Open</h3>
                <h6 class="mb-10 text-primary" style="padding-left: 5px;"><?= $open ?></h6>
            </div>
        </div>
    </div>
    <?php $inprogress = isset($ticketsummary["IN PROGRESS"]) ? $ticketsummary["IN PROGRESS"] : 0 ?>
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="icon-card mb-30">
            <div class="content d-flex flex-column justify-content-between align-items-left">
                <h3 class="text-bold mb-10">In Progress</h3>
                <h6 class="mb-10 text-warning" style="padding-left: 5px;"><?= $inprogress ?></h6>
            </div>
        </div>
    </div>
    <?php $solved = isset($ticketsummary["SOLVED"]) ? $ticketsummary["SOLVED"] : 0 ?>
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="icon-card mb-30">
            <div class="content d-flex flex-column justify-content-between align-items-left">
                <h3 class="text-bold mb-10">Solved</h3>
                <h6 class="mb-10 text-success" style="padding-left: 5px;"><?= $solved ?></h6>
            </div>
        </div>
    </div>
    <?php $closed = isset($ticketsummary["CLOSED"]) ? $ticketsummary["CLOSED"] : 0 ?>
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <div class="icon-card mb-30">
            <div class="content d-flex flex-column justify-content-between align-items-left">
                <h3 class="text-bold mb-10">Closed</h3>
                <h6 class="mb-10 text-danger" style="padding-left: 5px;"><?= $closed ?></h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-style mb-30">
            <div class="title d-flex flex-wrap align-items-center justify-content-between">
                <div class="left">
                    <h6 class="text-medium mb-30">Tickets</h6>
                </div>
                <div class="right">
                    <a href="<?= url_to('front.ticket.add.view') ?>" class="btn btn-primary text-medium mb-30"><i
                            class="fa-solid fa-plus fa-sm" style="color: #ffffff;"></i> Ticket</a>
                </div>
            </div>
            <!-- End Title -->
            <div class="table-responsive">
                <table class="table top-selling-table display" id="myTable">
                    <thead class="">
                        <tr>
                            <th class="min-width">
                                <h6 class="text-sm text-medium">SNO</h6>
                            </th>
                            <th class="min-width">
                                <h6 class="text-sm text-medium">Subject<i class="lni lni-arrows-vertical"></i></h6>
                            </th>
                            <th class="min-width">
                                <h6 class="text-sm text-medium">
                                    priority <i class="lni lni-arrows-vertical"></i>
                                </h6>
                            </th>
                            <th class="min-width">
                                <h6 class="text-sm text-medium">
                                    Contact <i class="lni lni-arrows-vertical"></i>
                                </h6>
                            </th>
                            <th class="min-width">
                                <h6 class="text-sm text-medium">
                                    Project <i class="lni lni-arrows-vertical"></i>
                                </h6>
                            </th>
                            <th class="min-width">
                                <h6 class="text-sm text-medium">
                                    status <i class="lni lni-arrows-vertical"></i>
                                </h6>
                            </th>
                            <th class="min-width">
                                <h6 class="text-sm text-medium">
                                    Created on <i class="lni lni-arrows-vertical"></i>
                                </h6>
                            </th>
                            <!-- <th>
                                <h6 class="text-sm text-medium text-end">
                                    Actions <i class="lni lni-arrows-vertical"></i>
                                </h6>
                            </th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        foreach ($tickets as $value) { ?>
                            <tr>
                                <td>
                                    <p class="text-sm"><?= $count ?></p>
                                </td>
                                <td class="hover-custom" title="Click to view">
                                    <p class="text-sm"><?= $value['subject'] ?></p>
                                </td>
                                <td>
                                    <?= $value['priority'] ?>
                                </td>
                                <td>
                                    <p class="text-sm"><?= $value['cust_id'] ?></p>
                                </td>
                                <td>
                                    <p class="text-sm"><?= $value['project_id'] ?></p>
                                </td>
                                <td>
                                    <?= $value['status'] ?>
                                </td>
                                <td>
                                    <p class="text-sm"><?= $value['created_at'] ?></p>
                                </td>

                            </tr>

                            <?php $count++;
                        } ?>
                    </tbody>
                </table>
                <!-- End Table -->
            </div>
        </div>
    </div>
    <!-- End Col -->
</div>
<script src="<?= base_url() . 'front-assets/' ?>js/custom.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script
    src="https://cdn.datatables.net/v/bs4/dt-2.1.8/af-2.7.0/b-3.2.0/b-colvis-3.2.0/b-print-3.2.0/cr-2.0.4/date-1.5.4/fc-5.0.4/fh-4.0.1/kt-2.12.1/r-3.0.3/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.1/sp-2.3.3/sl-2.1.0/sr-1.4.1/datatables.min.js"></script>
<script>
    let table = new DataTable('#myTable', {
        responsive: true,
        columnDefs: [
            { className: "dt-body-left", targets: "_all" },
            { className: "dt-body-center", targets: 0 },
        ],
    });

    // $(document).ready(function () {


    //     $(".hover-custom").on("mouseover", (e) => {
    //         console.log(e.pageX);
    //         console.log(e.pageY);
    //         if (!$("#tooltip").is(":visible")) {
    //             $("#tooltip").css(
    //                 {
    //                     left: (e.pageX - 316 )+ "px",
    //                     top: (e.pageY -40 )+ "px"
    //                 }
    //             ).fadeIn(700);
    //         }
    //     })

    //     // $(".hover-custom").on("mousemove", (e) => {
    //     //     $("#tooltip").css(
    //     //         {
    //     //             left: (e.pageX -200) + "px",
    //     //             top: e.pageY + "px"
    //     //         }
    //     //     )
    //     // })

    //     $(".hover-custom").on("mouseout", (e) => {
    //         $("#tooltip").fadeOut();
    //     })

    // })

    <?php if (session()->getFlashdata('op_success')) { ?>
        let alert = new Alert();
        alert.alert_invoke('success', '<?= session()->getFlashdata('op_success') ?>');
    <?php } elseif (session()->getFlashdata('op_error')) { ?>
        let alert = new Alert();
        alert.alert_invoke('error', '<?= session()->getFlashdata('op_error') ?>');
    <?php } ?>
</script>
<!-- End Row -->