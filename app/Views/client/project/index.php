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

    .st_warning {
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
                <h2>Projects Summary </h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">
                            <a href="#0">Files</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#0">Calender</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="row">

    <?php $created = isset($projectSummary['Created']) ? $projectSummary['Created'] : 0 ?>

<? // var_dump($projectSummary);?>

    <div class="col-xl-2 col-lg-3 col-sm-6">
        <div class="icon-card mb-30">
            <div class="content d-flex flex-column justify-content-between align-items-left">
                <h3 class="text-bold mb-10">Created</h3>
                <h6 class="mb-10 text-primary" style="padding-left: 5px;"><?= $created ?></h6>
            </div>
        </div>
    </div>
    <?php $inprogress = isset($projectSummary['In Progress']) ? $projectSummary['In Progress'] :  0 ; ?>

    <div class="col-xl-2 col-lg-3 col-sm-6">
        <div class="icon-card mb-30">
            <div class="content d-flex flex-column justify-content-between align-items-left">
                <h3 class="text-bold mb-10">In Progress</h3>
                <h6 class="mb-10 text-warning" style="padding-left: 5px;"><?= $inprogress ?></h6>
            </div>
        </div>
    </div>
    <?php $hold = isset($projectSummary['On Hold'])? $projectSummary['On Hold'] : 0 ;?>

    <div class="col-xl-2 col-lg-3 col-sm-6">
        <div class="icon-card mb-30">
            <div class="content d-flex flex-column justify-content-between align-items-left">
                <h3 class="text-bold mb-10">On Hold</h3>
                <h6 class="mb-10 text-secondary" style="padding-left: 5px;"><?= $hold ?></h6>
            </div>
        </div>
    </div>

    <?php $completed = isset($projectSummary['Completed'])? $projectSummary['Completed'] : 0 ;?>

    <div class="col-xl-2 col-lg-3 col-sm-6">
        <div class="icon-card mb-30">
            <div class="content d-flex flex-column justify-content-between align-items-left">
                <h3 class="text-bold mb-10">Completed</h3>
                <h6 class="mb-10 text-success" style="padding-left: 5px;"><?= $completed ?></h6>
            </div>
        </div>
    </div>

    <?php $cancelled = isset($projectSummary['Cancelled']) ? $projectSummary['Cancelled'] : 0 ; ?>

    <div class="col-xl-2 col-lg-3 col-sm-6">
        <div class="icon-card mb-30">
            <div class="content d-flex flex-column justify-content-between align-items-left">
                <h3 class="text-bold mb-10">Cancelled</h3>
                <h6 class="mb-10 text-danger" style="padding-left: 5px;"><?= $cancelled ?></h6>
            </div>
        </div>
    </div>
</div>



<div class="mt-3">
    <div class="card-style">
        <div class="title d-flex flex-wrap align-items-center justify-content-between">
            <div class="left">
                <h6 class="text-medium mb-30 h3">Projects</h6>
            </div>
            <div class="right">
                <div class="select-style-1">
                    <div class="select-position select-sm">
                        <select class="light-bg">
                            <option value="">Today</option>
                            <option value="">Yesterday</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table top-selling-table" id="myTable">
                <thead>
                    <tr>
                    <th>
                            <h6 class="text-sm text-medium">SNO</h6>
                        </th>
                        <th>
                            <h6 class="text-sm text-medium">Project Name</h6>
                        </th>
                        <th class="min-width">
                            <h6 class="text-sm text-medium">
                            Start Date<i class="lni lni-arrows-vertical"></i>
                            </h6>
                        </th>
                        <th class="min-width">
                            <h6 class="text-sm text-medium">
                            Deadline <i class="lni lni-arrows-vertical"></i>
                            </h6>
                        </th>
                  
                        <th class="min-width">
                            <h6 class="text-sm text-medium">
                            Status <i class="lni lni-arrows-vertical"></i>
                            </h6>
                        </th>
                        <th>
                            <h6 class="text-sm text-medium text-end">
                                Actions <i class="lni lni-arrows-vertical"></i>
                            </h6>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;
                    if(!empty($projects)){ foreach($projects as $projects_status){ ?>
                    <tr>
                    <td>
                        <p class="text-sm"><?= $count ?></p>
                        </td>
                        <td>
                        <p class="text-sm"><a href="<?= url_to('project.view.list');?>"><?= $projects_status['name'] ?></a></p>
                        </td>
                        <td>
                            <p class="text-sm"><?= $projects_status['start_date'] ?></p>
                        </td>
                        <td>
                            <p class="text-sm"><?= $projects_status['end_date'] ?></p>
                        </td>
                    
                        <td>
                            <p class="text-sm"><?=$projects_status['status'] ?></p>
                        </td>

                        

                        
                        <td style="text-align:center;">
                            <div class="action justify-content-center" style="text-align:center;width:100%;">
                            <a href="<?= url_to('project.view.list');?>">
                                <button class="more-btn dropdown-toggle" id="moreAction1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </a>
                            </div>
                        </td>
                    </tr>
                    
                    <?php $count++; } }
                     ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script
    src="https://cdn.datatables.net/v/bs4/dt-2.1.8/af-2.7.0/b-3.2.0/b-colvis-3.2.0/b-print-3.2.0/cr-2.0.4/date-1.5.4/fc-5.0.4/fh-4.0.1/kt-2.12.1/r-3.0.3/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.1/sp-2.3.3/sl-2.1.0/sr-1.4.1/datatables.min.js"></script>
<!-- <link rel="stylesheet" href="http://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css"> -->
<script>
        let table = new DataTable('#myTable');
</script>