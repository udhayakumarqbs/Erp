<div class="alldiv flex widget_title">
    <h3>View Project</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.project.projects'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="project_info">Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="project_phase">Phases</a></li>
        <li><a type="button" class="tab_nav_item" data-src="project_expense">Expenses</a></li>
        <li><a type="button" class="tab_nav_item" data-src="project_rawmaterial">Raw Materials</a></li>
        <li><a type="button" class="tab_nav_item" data-src="project_testing">Testing</a></li>
        <li><a type="button" class="tab_nav_item" data-src="project_attachment">Attachments</a></li>
        <li><a type="button" class="tab_nav_item" data-src="project_contractor">Contractor</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="project_info">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <a href="<?=url_to('erp.project.projectdelete',$project_id)?>" class="btn bg-danger">Delete</a>
                </div>
                <div class="form-width-1">
                    <h2>Project Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><b>Name</b></td>
                                    <td><?php echo $project->project_name; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Customer</b></td>
                                    <td><?php echo $project->customer; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Status</b></td>
                                    <td><span class="st <?php echo $project_status_bg[$project->status]; ?>"><?php echo $project_status[$project->status]; ?></span></td>
                                </tr>
                                <tr>
                                    <td><b>Start Date</b></td>
                                    <td><?php echo $project->start_date; ?></td>
                                </tr>
                                <tr>
                                    <td><b>End Date</b></td>
                                    <td><?php echo $project->end_date; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Budget</b></td>
                                    <td><?php echo $project->budget; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Units</b></td>
                                    <td><?php echo $project->units; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Type</b></td>
                                    <td><?php echo $project->type_name; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Address</b></td>
                                    <td>
                                        <p><?php echo $project->address . ', ' . $project->city; ?></p>
                                        <p><?php echo $project->state . ', ' . $project->country . '-' . $project->zipcode; ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Created on</b></td>
                                    <td><?php echo date("Y-m-d", $project->created_at); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Description</b></td>
                                    <td><?php echo $project->description; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-width-1">
                    <h2>Project Members</h2>
                    <div class="table_responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($project_members as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['position']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab_pane" id="project_phase">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <button class="btn bg-primary" type="button" id="phase_add_invoker1"><i class="fa fa-plus"></i> Add Phase</button>
                </div>
                <div class="form-width-1">
                    <?php
                    foreach ($phases as $key => $value) {
                    ?>
                        <div class="project-phase-frame">
                            <p class="phase-header">
                                <span class="phase-title"><?php echo $value['phase_name']; ?></span>
                                <span>
                                    <?php
                                    if ($value['started'] == 0) {
                                    ?>
                                        <button class="btn bg-warning phase-edit" data-phase-id="<?php echo $key; ?>"><i class="fa fa-pencil"></i></button>
                                        <button class="btn bg-danger phase-delete" data-phase-id="<?php echo $key; ?>"><i class="fa fa-trash"></i></button>
                                        <a class="btn bg-primary" href="<?php echo url_to('erp.project.phasestart', $project_id, $key); ?>">Start</a>
                                        <button class="btn bg-success phase-workgroup-add" data-phase-id="<?php echo $key; ?>"><i class="fa fa-plus"></i> Add</button>
                                    <?php
                                    }
                                    ?>
                                </span>
                            </p>
                            <div class="phase-body">
                                <?php
                                $completed = 0;
                                $total = count($value['workgroups']);
                                $prev_completed = false;
                                foreach ($value['workgroups'] as $row) {
                                    if ($row['completed'] == 1) {
                                        ++$completed;
                                        $prev_completed = true;
                                    }
                                ?>
                                    <div class="phase-workgroup">
                                        <div class="pgroup">
                                            <span class="workgroup-title"><?php echo $row['workgroup_name']; ?></span>
                                            <span class="st st_violet">Work Days: <?php echo $row['approx_days']; ?></span>
                                        </div>
                                        <?php
                                        if (!empty($row['team'])) {
                                        ?>
                                            <div class="pgroup">
                                                <b><?php echo $row['team']; ?></b>
                                                <span class="st st_primary">Lead by: <?php echo $row['lead_by']; ?></span>
                                            </div>
                                        <?php
                                        } else { ?>
                                            <div class="pgroup">
                                                <b><?php echo $row['contractor']; ?></b>
                                                <span class="st st_primary">Contact: <?php echo $row['contact_person']; ?></span>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <div class="pgroup">
                                            <?php
                                            if ($row['completed_at'] != "") { ?>
                                                <span><b>Completed:</b> <?php echo $row['completed_at']; ?></span>
                                                <span class="st st_success">Days: <?php
                                                                                    $date1 = DateTime::createFromFormat('Y-m-d', $row['started_at']);
                                                                                    $date2 = DateTime::createFromFormat('Y-m-d', $row['completed_at']);
                                                                                    $diff = $date2->diff($date1);
                                                                                    echo $diff->days;
                                                                                    ?></span>
                                            <?php
                                            } else if ($row['started_at'] != "") { ?>
                                                <span><b>Started:</b> <?php echo $row['started_at']; ?></span>
                                            <?php
                                            } else { ?>
                                                <span></span>
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            if ($value['started'] == 0) {
                                            ?>
                                                <a href="<?php echo url_to('erp.project.phasewgroupdelete', $project_id, $row['project_wgrp_id']); ?>" class="btn bg-danger"><i class="fa fa-trash"></i></a>
                                            <?php
                                            } else if ($row['started_at'] != '' && $row['completed'] == 0) {
                                            ?>
                                                <a href="<?php echo url_to('erp.project.phasewgroupcomplete', $project_id, $row['project_wgrp_id']); ?>" class="btn bg-success"><i class="fa fa-check"></i></a>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php

                                }
                                ?>
                            </div>
                            <div class="phase-footer">
                                <div class="phase-progressbar">
                                    <?php
                                    $progress = 0;
                                    if ($total != 0) {
                                        $progress = round(($completed / $total) * 100, 0);
                                    }
                                    ?>
                                    <div class="phase-handler" style="width:<?php echo $progress; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="tab_pane" id="project_expense">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <a href="<?php echo url_to('erp.project.expenseadd', $project_id); ?>" class="btn bg-primary"><i class="fa fa-plus"></i> Add</a>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="project_expense_dt" data-ajax-url="<?php echo url_to('erp.project.ajaxprojectexpenseresponse') . '?projectid=' . $project_id . '&'; ?>">
                        <div class="filterBox">
                            <div class="flex">
                                <h4>Filter and Search</h4>
                                <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
                            </div>
                        </div>
                        <div class="tableHeader flex">
                            <div class="formWidth">
                                <div class="selectBox poR bulkaction" data-ajax-url="">
                                    <div class="selectBoxBtn flex">
                                        <div class="textFlow" data-default="Action">Action</div>
                                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                        <input type="hidden" class="selectBox_Value" value="">
                                    </div>
                                    <ul role="listbox" class="selectBox_Container alldiv">
                                        <li role="option" data-value="1">Delete</li>
                                        <li role="option" data-value="2">Send</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="formWidth">
                                <input type="text" placeholder="search" class="form_control dt-search">
                            </div>
                            <div class="formWidth textRight">
                                <!--export button-->
                                <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i> Export</a>
                                <div class="export_container poF">
                                    <ul class="exportUl">
                                        <li><a data-default-href="<?php echo url_to('erp.project.projectexpenseexport') . '?export=excel&' . 'projectid=' . $project_id . '&'; ?>" href="<?php echo url_to('erp.project.projectexpenseexport') . '?export=excel&' . 'projectid=' . $project_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?php echo url_to('erp.project.projectexpenseexport') . '?export=pdf&' . 'projectid=' . $project_id . '&'; ?>" href="<?php echo url_to('erp.project.projectexpenseexport') . '?export=pdf&' . 'projectid=' . $project_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?php echo url_to('erp.project.projectexpenseexport') . '?export=csv&' . 'projectid=' . $project_id . '&'; ?>" href="<?php echo url_to('erp.project.projectexpenseexport') . '?export=csv&' . 'projectid=' . $project_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
                                    </ul>
                                    <a type="button" class="closeBtn3 HoverA"><i class="fa fa-close"></i></a>
                                </div>
                                <!--export button-->
                            </div>
                        </div>
                        <div class="table_responsive">
                            <table class="table">
                                <thead class="thead">

                                </thead>
                                <tbody class="table-paint-area">

                                </tbody>
                            </table>
                        </div>
                        <div class="tableFooter flex">
                            <div class="tableFooterLeft flex">
                                <p>Rows per page:</p>
                                <div class="selectBox miniSelectBox poR">
                                    <div class="selectBoxBtn flex">
                                        <div class="textFlow" data-default="10">10</div>
                                        <button class="drops"><i class="fa fa-caret-down"></i></button>
                                        <input type="hidden" class="selectBox_Value" value="10">
                                    </div>
                                    <ul role="listbox" class="selectBox_Container alldiv">
                                        <li role="option" class="active" data-value="10">10</li>
                                        <li role="option" data-value="15">15</li>
                                        <li role="option" data-value="20">20</li>
                                        <li role="option" data-value="25">25</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tableFooterRight flex">
                                <div class="pagination"><span class="dt-page-start">1</span> - <span class="dt-page-end">5</span> of <span class="dt-total-rows">100<span></div>
                                <ul class="flex paginationBtns">
                                    <li><a type="button" class="HoverA dt-prev-btn"><i class="fa fa-angle-left"></i></a></li>
                                    <li><a type="button" class="HoverA dt-next-btn"><i class="fa fa-angle-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab_pane" id="project_rawmaterial">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <a href="<?php echo url_to('erp.project.fromactiveworkgroup', $project_id); ?>" class="btn outline-primary">From Active Workgroup</a>
                    <button class="btn bg-primary" type="button" id="rawmaterial_modal_invoker1"><i class="fa fa-plus"></i> Add</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="project_rawmaterial_dt" data-ajax-url="<?php echo base_url() . 'erp/project/ajax_project_rawmaterial_response?projectid=' . $project_id . '&'; ?>">
                        <div class="tableHeader flex">
                            <div class="formWidth">
                                <div class="selectBox poR bulkaction" data-ajax-url="">
                                    <div class="selectBoxBtn flex">
                                        <div class="textFlow" data-default="Action">Action</div>
                                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                        <input type="hidden" class="selectBox_Value" value="">
                                    </div>
                                    <ul role="listbox" class="selectBox_Container alldiv">
                                        <li role="option" data-value="1">Delete</li>
                                        <li role="option" data-value="2">Send</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="formWidth">
                                <input type="text" placeholder="search" class="form_control dt-search">
                            </div>
                            <div class="formWidth textRight">
                                <!--export button-->
                                <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i> Export</a>
                                <div class="export_container poF">
                                    <ul class="exportUl">
                                        <li><a data-default-href="<?php echo url_to('erp.project.projectexpenseexport').'?export=excel&' . 'projectid=' . $project_id . '&'; ?>" href="<?php echo url_to('erp.project.projectexpenseexport').'?export=excel&' . 'projectid=' . $project_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?php echo url_to('erp.project.projectexpenseexport').'?export=pdf&' . 'projectid=' . $project_id . '&'; ?>" href="<?php echo url_to('erp.project.projectexpenseexport').'?export=pdf&' . 'projectid=' . $project_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?php echo url_to('erp.project.projectexpenseexport').'?export=csv&' . 'projectid=' . $project_id . '&'; ?>" href="<?php echo url_to('erp.project.projectexpenseexport').'?export=csv&' . 'projectid=' . $project_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
                                    </ul>
                                    <a type="button" class="closeBtn3 HoverA"><i class="fa fa-close"></i></a>
                                </div>
                                <!--export button-->
                            </div>
                        </div>
                        <div class="table_responsive">
                            <table class="table">
                                <thead class="thead">

                                </thead>
                                <tbody class="table-paint-area">

                                </tbody>
                            </table>
                        </div>
                        <div class="tableFooter flex">
                            <div class="tableFooterLeft flex">
                                <p>Rows per page:</p>
                                <div class="selectBox miniSelectBox poR">
                                    <div class="selectBoxBtn flex">
                                        <div class="textFlow" data-default="10">10</div>
                                        <button class="drops"><i class="fa fa-caret-down"></i></button>
                                        <input type="hidden" class="selectBox_Value" value="10">
                                    </div>
                                    <ul role="listbox" class="selectBox_Container alldiv">
                                        <li role="option" class="active" data-value="10">10</li>
                                        <li role="option" data-value="15">15</li>
                                        <li role="option" data-value="20">20</li>
                                        <li role="option" data-value="25">25</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tableFooterRight flex">
                                <div class="pagination"><span class="dt-page-start">1</span> - <span class="dt-page-end">5</span> of <span class="dt-total-rows">100<span></div>
                                <ul class="flex paginationBtns">
                                    <li><a type="button" class="HoverA dt-prev-btn"><i class="fa fa-angle-left"></i></a></li>
                                    <li><a type="button" class="HoverA dt-next-btn"><i class="fa fa-angle-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="tab_pane" id="project_testing">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <a href="<?php echo url_to('erp.project.testingadd', $project_id); ?>" class="btn bg-primary"><i class="fa fa-plus"></i> Add</a>
                </div>
                <div class="form-width-1">
                    <div class="datatable" id="project_testing_dt" data-ajax-url="<?php echo url_to('erp.project.ajaxprojecttestingresponse') . '?projectid=' . $project_id . '&'; ?>">
                        <div class="tableHeader flex">
                            <div class="formWidth">
                                <div class="selectBox poR bulkaction" data-ajax-url="">
                                    <div class="selectBoxBtn flex">
                                        <div class="textFlow" data-default="Action">Action</div>
                                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                        <input type="hidden" class="selectBox_Value" value="">
                                    </div>
                                    <ul role="listbox" class="selectBox_Container alldiv">
                                        <li role="option" data-value="1">Delete</li>
                                        <li role="option" data-value="2">Send</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="formWidth">
                                <input type="text" placeholder="search" class="form_control dt-search">
                            </div>
                            <div class="formWidth textRight">
                                <!--export button-->
                                <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i> Export</a>
                                <div class="export_container poF">
                                    <ul class="exportUl">
                                        <li><a data-default-href="<?php echo base_url() . 'erp/project/project_testing_export?export=excel&' . 'projectid=' . $project_id . '&'; ?>" href="<?php echo base_url() . 'erp/project/project_testing_export?export=excel&' . 'projectid=' . $project_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/project/project_testing_export?export=pdf&' . 'projectid=' . $project_id . '&'; ?>" href="<?php echo base_url() . 'erp/project/project_testing_export?export=pdf&' . 'projectid=' . $project_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/project/project_testing_export?export=csv&' . 'projectid=' . $project_id . '&'; ?>" href="<?php echo base_url() . 'erp/project/project_testing_export?export=csv&' . 'projectid=' . $project_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
                                    </ul>
                                    <a type="button" class="closeBtn3 HoverA"><i class="fa fa-close"></i></a>
                                </div>
                                <!--export button-->
                            </div>
                        </div>
                        <div class="table_responsive">
                            <table class="table">
                                <thead class="thead">

                                </thead>
                                <tbody class="table-paint-area">

                                </tbody>
                            </table>
                        </div>
                        <div class="tableFooter flex">
                            <div class="tableFooterLeft flex">
                                <p>Rows per page:</p>
                                <div class="selectBox miniSelectBox poR">
                                    <div class="selectBoxBtn flex">
                                        <div class="textFlow" data-default="10">10</div>
                                        <button class="drops"><i class="fa fa-caret-down"></i></button>
                                        <input type="hidden" class="selectBox_Value" value="10">
                                    </div>
                                    <ul role="listbox" class="selectBox_Container alldiv">
                                        <li role="option" class="active" data-value="10">10</li>
                                        <li role="option" data-value="15">15</li>
                                        <li role="option" data-value="20">20</li>
                                        <li role="option" data-value="25">25</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tableFooterRight flex">
                                <div class="pagination"><span class="dt-page-start">1</span> - <span class="dt-page-end">5</span> of <span class="dt-total-rows">100<span></div>
                                <ul class="flex paginationBtns">
                                    <li><a type="button" class="HoverA dt-prev-btn"><i class="fa fa-angle-left"></i></a></li>
                                    <li><a type="button" class="HoverA dt-next-btn"><i class="fa fa-angle-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab_pane" id="project_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame" data-ajax-url="<?php echo base_url() . 'erp/project/upload_projectattachment?id=' . $project_id . '&'; ?>">
                        <div class="file-uploader-box">
                            <span class="file-uploader-text">drop or click to upload files</span>
                        </div>
                        <div class="file-uploader-progessbar">
                            <span class="progressbar bg-success"></span>
                        </div>
                        <input type="file" class="file-uploader-input" name="attachment" />
                        <p class="file-uploader-error"></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody class="attachment-holder" data-ajaxdel-url="<?php echo base_url() . 'erp/project/project_delete_attachment?'; ?>">
                                <?php
                                foreach ($attachments as $attach) {
                                ?>
                                    <tr>
                                        <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('project') . $attach['filename']; ?>"><?php echo $attach['filename']; ?></a></td>
                                        <td><button class="btn bg-danger del-attachment-btn" type="button" data-attach-id="<?php echo $attach['attach_id']; ?>"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab_pane" id="project_contractor">
            <div class="flex">
                <div class="form-width-1">
                    <div class="datatable" id="project_contractor_dt" data-ajax-url="<?php echo base_url() . 'erp/project/ajax_project_contractor_response?projectid=' . $project_id . '&'; ?>">
                        <div class="tableHeader flex">
                            <div class="formWidth">
                                <div class="selectBox poR bulkaction" data-ajax-url="">
                                    <div class="selectBoxBtn flex">
                                        <div class="textFlow" data-default="Action">Action</div>
                                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                                        <input type="hidden" class="selectBox_Value" value="">
                                    </div>
                                    <ul role="listbox" class="selectBox_Container alldiv">
                                        <li role="option" data-value="1">Delete</li>
                                        <li role="option" data-value="2">Send</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="formWidth">
                                <input type="text" placeholder="search" class="form_control dt-search">
                            </div>
                            <div class="formWidth textRight">
                                <!--export button-->
                                <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i> Export</a>
                                <div class="export_container poF">
                                    <ul class="exportUl">
                                        <li><a data-default-href="<?php echo base_url() . 'erp/project/project_contractor_export?export=excel&' . 'projectid=' . $project_id . '&'; ?>" href="<?php echo base_url() . 'erp/project/project_contractor_export?export=excel&' . 'projectid=' . $project_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>" alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/project/project_contractor_export?export=pdf&' . 'projectid=' . $project_id . '&'; ?>" href="<?php echo base_url() . 'erp/project/project_contractor_export?export=pdf&' . 'projectid=' . $project_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>" alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/project/project_contractor_export?export=csv&' . 'projectid=' . $project_id . '&'; ?>" href="<?php echo base_url() . 'erp/project/project_contractor_export?export=csv&' . 'projectid=' . $project_id . '&'; ?>" target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>" alt="csv">CSV</a></li>
                                    </ul>
                                    <a type="button" class="closeBtn3 HoverA"><i class="fa fa-close"></i></a>
                                </div>
                                <!--export button-->
                            </div>
                        </div>
                        <div class="table_responsive">
                            <table class="table">
                                <thead class="thead">

                                </thead>
                                <tbody class="table-paint-area">

                                </tbody>
                            </table>
                        </div>
                        <div class="tableFooter flex">
                            <div class="tableFooterLeft flex">
                                <p>Rows per page:</p>
                                <div class="selectBox miniSelectBox poR">
                                    <div class="selectBoxBtn flex">
                                        <div class="textFlow" data-default="10">10</div>
                                        <button class="drops"><i class="fa fa-caret-down"></i></button>
                                        <input type="hidden" class="selectBox_Value" value="10">
                                    </div>
                                    <ul role="listbox" class="selectBox_Container alldiv">
                                        <li role="option" class="active" data-value="10">10</li>
                                        <li role="option" data-value="15">15</li>
                                        <li role="option" data-value="20">20</li>
                                        <li role="option" data-value="25">25</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tableFooterRight flex">
                                <div class="pagination"><span class="dt-page-start">1</span> - <span class="dt-page-end">5</span> of <span class="dt-total-rows">100<span></div>
                                <ul class="flex paginationBtns">
                                    <li><a type="button" class="HoverA dt-prev-btn"><i class="fa fa-angle-left"></i></a></li>
                                    <li><a type="button" class="HoverA dt-next-btn"><i class="fa fa-angle-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>





<!--MODALS-->
<div class="modal" id="phase_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Phase</h2>
        <?php
        echo form_open(base_url() . 'erp/project/phaseaddedit/' . $project_id, array(
            "id" => "phase_addedit_form",
            "class" => "flex modal-scroll-form"
        ));
        ?>
        <input type="hidden" name="phase_id" id="f_phase_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Name</label>
                <input type="text" class="form_control field-check" id="f_phase_name" name="name" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="phase_addedit_btn">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>


<div class="modal" id="workgroup_add_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Workgroup</h2>
        <?php
        echo form_open(base_url() . 'erp/project/phaseworkgroup/' . $project_id, array(
            "id" => "workgroup_add_form",
            "class" => "flex modal-scroll-form"
        ));
        ?>
        <input type="hidden" name="phase_id" id="m_phase_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Workgroup</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select workgroup">select workgroup</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" id="m_workgroup" name="workgroup" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($workgroups as $row) {
                        ?>
                            <li role="option" data-value="<?php echo $row['wgroup_id']; ?>"><?php echo $row['name']; ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-checked-any">
                <div id="m_worker_type">
                    <label class="form-check-label"><input type="radio" class="field-check" name="worker_type" value="Team">Team</label>
                    <label class="form-check-label"><input type="radio" class="field-check" name="worker_type" value="Contractor">Contractor</label>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required field-no-validate" id="worker_type_team" style="display:none">
                <label class="form-label">Teams</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select team">select team</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" id="m_team" name="team" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($teams as $row) {
                        ?>
                            <li role="option" data-value="<?php echo $row['team_id']; ?>"><?php echo $row['name']; ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required field-no-validate" id="worker_type_contractor" style="display:none">
                <label class="form-label">Contractors</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select contractor">select contractor</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" id="m_contractor" name="contractor" value="">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($contractors as $row) {
                        ?>
                            <li role="option" data-value="<?php echo $row['contractor_id']; ?>"><?php echo $row['contractor']; ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="workgroup_add_btn">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>

<div class="modal" id="rawmaterial_add_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Raw Material</h2>
        <?php
        echo form_open(base_url() . 'erp/project/rawmaterialadd/' . $project_id, array(
            "id" => "rawmaterial_add_form",
            "class" => "flex modal-scroll-form"
        ));
        ?>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Raw Material</label>
                <div class="ajaxselectBox poR" data-ajax-url="<?php echo base_url() . 'erp/procurement/ajaxfetchrawmaterials'; ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="select product">select product</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" name="related_id" class="ajaxselectBox_Value field-check" value="">
                    </div>
                    <div class="ajaxselectBox_Container alldiv">
                        <input type="text" class="ajaxselectBox_Search form_control" />
                        <ul role="listbox">

                        </ul>
                    </div>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-number ">
                <label class="form-label">Quantity</label>
                <input type="text" class="form_control field-check" name="req_qty" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="rawmaterial_add_btn">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>

<div class="modal" id="rawmaterial_qty_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Update quantity</h2>
        <?php
        echo form_open(base_url() . 'erp/project/updateqty/' . $project_id, array(
            "id" => "rawmaterial_qty_form",
            "class" => "flex modal-scroll-form"
        ));
        ?>
        <input type="hidden" name="project_raw_id" id="f_project_raw_id" />
        <div class="form-width-1">
            <div class="form-group field-number ">
                <label class="form-label">Quantity</label>
                <input type="text" name="req_qty" id="f_project_req_qty" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="rawmaterial_qty_btn">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>

<div class="modal" id="contractor_edit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Update Contractor</h2>
        <?php
        echo form_open(base_url() . 'erp/project/contractoredit/' . $project_id, array(
            "id" => "contractor_edit_form",
            "class" => "flex modal-scroll-form"
        ));
        ?>
        <input type="hidden" name="project_wgrp_id" id="f_project_wgrp_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Amount</label>
                <input type="text" class="form_control field-check" id="f_amount" name="amount" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Pay before</label>
                <input type="date" class="form_control field-check" id="f_pay_before" name="pay_before" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="contractor_edit_btn">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>

<!-- MODAL ENDS -->


<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script type="text/javascript">
    let closer = new WindowCloser();
    closer.init();
    let tbody = document.querySelector(".attachment-holder");
    let fileuploader = new FileUploader(document.querySelector(".file-uploader-frame"));
    let alert = new ModalAlert();

    fileuploader.ajaxFullUpload({
        files_allowed: ["text/plain", "image/png", "application/pdf", "image/jpeg", "image/gif", "image/jpg", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "image/webp", "image/svg+xml"],
        listener: function(json) {
            let tr = document.createElement("tr");
            let td1 = document.createElement("td");
            let td2 = document.createElement("td");
            td1.innerHTML = `<a target="_BLANK" download class="text-primary" href="` + json['filelink'] + `">` + json['filename'] + `</a>`;
            td2.innerHTML = `<button class="btn bg-danger del-attachment-btn" type="button" data-attach-id="` + json['insert_id'] + `" ><i class="fa fa-trash"></i></button>`;
            tr.append(td1);
            tr.append(td2);
            tbody.append(tr);
        }
    });

    tbody.onclick = (evt) => {
        let target = evt.target;
        let ajax_url = tbody.getAttribute("data-ajaxdel-url");
        tbody.querySelectorAll(".del-attachment-btn").forEach((item) => {
            if (item.contains(target)) {
                let xhr = null;
                if (window.ActiveXObject) {
                    xhr = new ActiveXObject("Msxml2.XMLHTTP");
                } else if (window.XMLHttpRequest) {
                    xhr = new XMLHttpRequest();
                }
                if (xhr !== null || xhr !== undefined) {
                    xhr.open("GET", ajax_url + "id=" + item.getAttribute("data-attach-id"), true);
                    xhr.send(null);
                    xhr.onreadystatechange = (evt) => {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            let json = JSON.parse(xhr.responseText);
                            if (json['error'] == 0) {
                                alert.invoke_alert(json['reason'], "success");
                                item.parentElement.parentElement.remove();
                            } else {
                                alert.invoke_alert(json['reason'], "error");
                            }
                        }
                    }
                }
            }
        })
    };

    let phase_modal = new ModalBox(document.getElementById("phase_addedit_modal"));
    phase_modal.init();

    document.getElementById("phase_add_invoker1").onclick = (evt) => {
        document.getElementById("f_phase_id").value = "0";
        document.getElementById("f_phase_name").value = "";
        phase_modal.show_modal();
    };

    let phase_form = document.getElementById("phase_addedit_form");
    let phase_validator = new FormValidate(phase_form);

    let phase_lock = false;
    document.getElementById("phase_addedit_btn").onclick = (evt) => {
        if (!phase_lock) {
            phase_lock = true;
            phase_validator.validate(
                (params) => {
                    phase_form.submit();
                    phase_lock = false;
                },
                (params) => {
                    phase_lock = false;
                }, {});
        }
    }

    document.querySelectorAll("#project_phase .phase-edit").forEach((item) => {
        item.onclick = (evt) => {
            document.getElementById("f_phase_id").value = item.getAttribute("data-phase-id");
            document.getElementById("f_phase_name").value = item.parentElement.previousElementSibling.textContent;
            phase_modal.show_modal();
        }
    });

    let workgroup_modal = new ModalBox(document.getElementById("workgroup_add_modal"));
    workgroup_modal.init();

    let workgroup_select = [];
    document.querySelectorAll("#workgroup_add_modal .selectBox").forEach((item) => {
        let selectbox = new SelectBox(item);
        selectbox.init();
        workgroup_select.push(selectbox);
        closer.register_shutdown(selectbox.shutdown, selectbox.get_container());
    });

    document.querySelectorAll("#project_phase .phase-workgroup-add").forEach((item) => {
        item.onclick = (evt) => {
            document.getElementById("m_phase_id").value = item.getAttribute("data-phase-id");
            document.getElementById("m_workgroup").value = "";
            for (let i = 0; i < workgroup_select.length; i++) {
                workgroup_select[i].construct();
            }
            workgroup_modal.show_modal();
        }
    });

    let workgroup_form = document.getElementById("workgroup_add_form");
    let workgroup_validator = new FormValidate(workgroup_form);

    let workgroup_lock = false;
    document.getElementById("workgroup_add_btn").onclick = (evt) => {
        if (!workgroup_lock) {
            workgroup_lock = true;
            workgroup_validator.validate(
                (params) => {
                    workgroup_form.submit();
                    workgroup_lock = false;
                },
                (params) => {
                    workgroup_lock = false;
                }, {});
        }
    }

    let expense_datatable_elem = document.getElementById("project_expense_dt");
    let expense_rows_per_page = new SelectBox(expense_datatable_elem.querySelector(".tableFooter .selectBox"));
    expense_rows_per_page.init();
    closer.register_shutdown(expense_rows_per_page.shutdown, expense_rows_per_page.get_container());
    let expense_bulkaction = new SelectBox(expense_datatable_elem.querySelector(".tableHeader .bulkaction"));
    expense_bulkaction.init();
    closer.register_shutdown(expense_bulkaction.shutdown, expense_bulkaction.get_container());
    let expense_config = JSON.parse('<?php echo $expense_datatable_config; ?>');
    let expense_datatable = new DataTable(expense_datatable_elem, expense_config);
    expense_datatable.init();
    expense_rows_per_page.add_listener(expense_datatable.rows_per_page, {});


    let testing_datatable_elem = document.getElementById("project_testing_dt");
    let testing_rows_per_page = new SelectBox(testing_datatable_elem.querySelector(".tableFooter .selectBox"));
    testing_rows_per_page.init();
    closer.register_shutdown(testing_rows_per_page.shutdown, testing_rows_per_page.get_container());
    let testing_bulkaction = new SelectBox(testing_datatable_elem.querySelector(".tableHeader .bulkaction"));
    testing_bulkaction.init();
    closer.register_shutdown(testing_bulkaction.shutdown, testing_bulkaction.get_container());
    let testing_config = JSON.parse('<?php echo $testing_datatable_config; ?>');
    let testing_datatable = new DataTable(testing_datatable_elem, testing_config);
    testing_datatable.init();
    testing_rows_per_page.add_listener(testing_datatable.rows_per_page, {});


    let rawmaterial_datatable_elem = document.getElementById("project_rawmaterial_dt");
    let rawmaterial_rows_per_page = new SelectBox(rawmaterial_datatable_elem.querySelector(".tableFooter .selectBox"));
    rawmaterial_rows_per_page.init();
    closer.register_shutdown(rawmaterial_rows_per_page.shutdown, rawmaterial_rows_per_page.get_container());
    let rawmaterial_bulkaction = new SelectBox(rawmaterial_datatable_elem.querySelector(".tableHeader .bulkaction"));
    rawmaterial_bulkaction.init();
    closer.register_shutdown(rawmaterial_bulkaction.shutdown, rawmaterial_bulkaction.get_container());
    let rawmaterial_config = JSON.parse('<?php echo $rawmaterial_datatable_config; ?>');
    let rawmaterial_datatable = new DataTable(rawmaterial_datatable_elem, rawmaterial_config);
    rawmaterial_datatable.init();
    rawmaterial_rows_per_page.add_listener(rawmaterial_datatable.rows_per_page, {});

    let rawmaterial_modal = new ModalBox(document.getElementById("rawmaterial_add_modal"));
    rawmaterial_modal.init();
    let rawmaterial_ajaxselect = [];
    document.querySelectorAll("#rawmaterial_add_modal .ajaxselectBox").forEach((item) => {
        let ajaxselectbox = new AjaxSelectBox(item);
        ajaxselectbox.init();
        rawmaterial_ajaxselect.push(ajaxselectbox);
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
    });

    document.getElementById("rawmaterial_modal_invoker1").onclick = (evt) => {
        rawmaterial_modal.show_modal();
    }

    let rawmaterial_form = document.getElementById("rawmaterial_add_form");
    let rawmaterial_validator = new FormValidate(rawmaterial_form);

    let rawmaterial_lock = false;
    document.getElementById("rawmaterial_add_btn").onclick = (evt) => {
        if (!rawmaterial_lock) {
            rawmaterial_lock = true;
            rawmaterial_validator.validate(
                (params) => {
                    rawmaterial_form.submit();
                    rawmaterial_lock = false;
                },
                (params) => {
                    rawmaterial_lock = false;
                }, {});
        }
    }

    let updateqty_modal = new ModalBox(document.getElementById("rawmaterial_qty_modal"));
    updateqty_modal.init();

    let rawmaterial_paintarea = rawmaterial_datatable_elem.querySelector(".table-paint-area");
    rawmaterial_paintarea.onclick = (evt) => {
        let target = evt.target;
        rawmaterial_paintarea.querySelectorAll(".modalBtn").forEach((item) => {
            if (item.contains(target)) {
                let ajax_url = item.getAttribute("data-ajax-url");
                let xhr = null;
                if (window.XMLHttpRequest) {
                    xhr = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    xhr = new ActiveXObject("Msxml2.XMLHTTP");
                }
                if (xhr !== null && xhr !== undefined) {
                    xhr.open("GET", ajax_url, true);
                    xhr.send(null);
                    xhr.onreadystatechange = (evt) => {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            let json = JSON.parse(xhr.responseText);
                            if (json['error'] == 0) {
                                let data = json['data'];
                                document.getElementById("f_project_raw_id").value = data['project_raw_id'];
                                document.getElementById("f_project_req_qty").value = data['req_qty'];
                                updateqty_modal.show_modal();
                            }
                        }
                    }
                }
            }
        });
    };

    let updateqty_form = document.getElementById("rawmaterial_qty_form");
    let updateqty_validator = new FormValidate(updateqty_form);

    let updateqty_lock = false;
    document.getElementById("rawmaterial_qty_btn").onclick = (evt) => {
        if (!updateqty_lock) {
            updateqty_lock = true;
            updateqty_validator.validate(
                (params) => {
                    updateqty_form.submit();
                    updateqty_lock = false;
                },
                (params) => {
                    updateqty_lock = false;
                }, {});
        }
    }

    document.querySelectorAll("#m_worker_type .field-check").forEach((item) => {
        item.addEventListener("change", (evt) => {
            let value = evt.target.value;
            if (value == "Team") {
                document.getElementById("worker_type_team").style.display = "block";
                document.getElementById("worker_type_team").classList.remove("field-no-validate");
                document.getElementById("worker_type_contractor").style.display = "none";
                document.getElementById("worker_type_contractor").classList.add("field-no-validate");
            } else {
                document.getElementById("worker_type_contractor").style.display = "block";
                document.getElementById("worker_type_contractor").classList.remove("field-no-validate");
                document.getElementById("worker_type_team").style.display = "none";
                document.getElementById("worker_type_team").classList.add("field-no-validate");
            }
        });
    });

    let contractor_datatable_elem = document.getElementById("project_contractor_dt");
    let contractor_rows_per_page = new SelectBox(contractor_datatable_elem.querySelector(".tableFooter .selectBox"));
    contractor_rows_per_page.init();
    closer.register_shutdown(contractor_rows_per_page.shutdown, contractor_rows_per_page.get_container());
    let contractor_bulkaction = new SelectBox(contractor_datatable_elem.querySelector(".tableHeader .bulkaction"));
    contractor_bulkaction.init();
    closer.register_shutdown(contractor_bulkaction.shutdown, contractor_bulkaction.get_container());
    let contractor_config = JSON.parse('<?php echo $contractor_datatable_config; ?>');
    let contractor_datatable = new DataTable(contractor_datatable_elem, contractor_config);
    contractor_datatable.init();
    contractor_rows_per_page.add_listener(contractor_datatable.rows_per_page, {});

    let contractor_modal = new ModalBox(document.getElementById("contractor_edit_modal"));
    contractor_modal.init();

    let contractor_paintarea = contractor_datatable_elem.querySelector(".table-paint-area");
    contractor_paintarea.onclick = (evt) => {
        let target = evt.target;
        contractor_paintarea.querySelectorAll(".modalBtn").forEach((item) => {
            if (item.contains(target)) {
                let ajax_url = item.getAttribute("data-ajax-url");
                let xhr = null;
                if (window.XMLHttpRequest) {
                    xhr = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    xhr = new ActiveXObject("Msxml2.XMLHTTP");
                }
                if (xhr !== null && xhr !== undefined) {
                    xhr.open("GET", ajax_url, true);
                    xhr.send(null);
                    xhr.onreadystatechange = (evt) => {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            let json = JSON.parse(xhr.responseText);
                            if (json['error'] == 0) {
                                let data = json['data'];
                                document.getElementById("f_project_wgrp_id").value = data['project_wgrp_id'];
                                document.getElementById("f_amount").value = data['amount'];
                                document.getElementById("f_pay_before").value = data['pay_before'];
                                contractor_modal.show_modal();
                            }
                        }
                    }
                }
            }
        });
    };

    let contractor_form = document.getElementById("contractor_edit_form");
    let contractor_validator = new FormValidate(contractor_form);

    let contractor_lock = false;
    document.getElementById("contractor_edit_btn").onclick = (evt) => {
        if (!contractor_lock) {
            contractor_lock = true;
            contractor_validator.validate(
                (params) => {
                    contractor_form.submit();
                    contractor_lock = false;
                },
                (params) => {
                    contractor_lock = false;
                }, {});
        }
    }


    <?php
    if ($this->session->flashdata("op_success")) { ?>
        alert.invoke_alert("<?php echo $this->session->flashdata('op_success'); ?>", "success");
    <?php
    } else if ($this->session->flashdata("op_error")) { ?>
        alert.invoke_alert("<?php echo $this->session->flashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>