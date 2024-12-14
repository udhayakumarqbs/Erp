<style>
    textarea.form_control {
        min-height: 131px;
    }

    .error {
        border: 1px solid red;
    }

    .commend-log {
        padding: 10px 0px 10px 10px;
        gap: 10px;
        margin-top: 10px;
        border-radius: 8px;
        position: relative;
        width: 97%;
    }

    .commend-log.staff {
        background: #8bc34a2e;
        transform: translateX(-18px);
    }

    .commend-log.staff::after {
        content: "";
        position: absolute;
        top: 10px;
        left: -21px;
        border: 10px solid transparent;
        border-right-color: #34db4a;
        /* border-right-color: #3498db; */
        /* Same as background color */
    }

    .commend-log.client {
        background: #2196f31f;
        transform: translateX(18px);
    }

    .commend-log.client::before {
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
        padding-left: 28px;
    }
</style>
<div class="alldiv flex widget_title">
    <h3>View Ticket</h3>
    <div class="title_right">
        <a href="<?php echo base_url() . 'erp/crm/tickets'; ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back
        </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="ticket_info">Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="ticket_attachment">Attachments</a></li>
        <li><a type="button" class="tab_nav_item" data-src="ticket_comment">Comments</a></li>
        <!-- <li><a type="button" class="tab_nav_item" data-src="lead_notify">Notify</a></li> -->
        <?php
        if ($ticket->status == 1 && $ticket->assigned_to == get_user_id()) { ?>
            <li><a type="button" class="tab_nav_item" data-src="ticket_submit">Solve / Close</a></li>
            <?php
        }
        ?>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="ticket_info">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <?php
                    if ($ticket->status == 0 && $ticket->assigned_to == get_user_id()) { ?>
                        <a href="<?php echo url_to('erp.crm.handleticket', $ticket_id); ?>"
                            class="btn bg-primary">Handle</a>
                        <?php
                    }
                    ?>
                    <a href="<?= url_to('erp.crm.ticketdelete', $ticket_id); ?>"
                        class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1">
                    <h2>Ticket Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Subject</th>
                                    <td><?php echo $ticket->subject; ?></td>
                                </tr>
                                <tr>
                                    <th>Customer</th>
                                    <td><?php echo $ticket->customer; ?></td>
                                </tr>
                                <tr>
                                    <th>Customer Contact</th>
                                    <td><?php echo $ticket->contact; ?></td>
                                </tr>
                                <tr>
                                    <th>Project</th>
                                    <td><?php echo $ticket->project_name ? $ticket->project_name : "-"; ?></td>
                                </tr>
                                <tr>
                                    <th>Assigned To</th>
                                    <td><?php echo $ticket->assigned; ?></td>
                                </tr>
                                <tr>
                                    <th>Priority</th>
                                    <td><span
                                            class="st <?php echo $ticket_priority_bg[$ticket->priority]; ?>"><?php echo $ticket_priority[$ticket->priority]; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span
                                            class="st <?php echo $ticket_status_bg[$ticket->status]; ?>"><?php echo $ticket_status[$ticket->status]; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created On</th>
                                    <td><?php echo $ticket->created_at; ?></td>
                                </tr>
                                <tr>
                                    <th>Problem</th>
                                    <td><?php echo $ticket->problem; ?></td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td><?php echo $ticket->remarks; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="ticket_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame"
                        data-ajax-url="<?php echo url_to('erp.crm.uploadticketattachment') . '?id=' . $ticket_id . '&'; ?>">
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
                            <tbody class="attachment-holder"
                                data-ajaxdel-url="<?php echo url_to('erp.crm.ticketdeleteattachment') . '?'; ?>">
                                <?php
                                foreach ($attachments as $attach) {
                                    ?>
                                    <tr>
                                        <td><a target="_BLANK" download class="text-primary"
                                                href="<?php echo get_attachment_link('ticket') . $attach['filename']; ?>"><?php echo $attach['filename']; ?></a>
                                        </td>
                                        <td><button class="btn bg-danger del-attachment-btn" type="button"
                                                data-attach-id="<?php echo $attach['attach_id']; ?>"><i
                                                    class="fa fa-trash"></i></button></td>
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

        <!-- comments -->
        <div class="tab_pane" id="ticket_comment">
            <div class="card-style mb-3">
                <h3 class="mb-3">Add reply to this ticket</h3>
                <form action="" method="post">
                    <input type="hidden" name="ticket_id" id="ticket_id" value="<?= $ticket_id ?>">
                    <textarea name="comment" id="comment" class="form_control"></textarea>
                    <button id="btn_commnet" type="button" class="btn bg-primary mt-2">Reply</button>
                </form>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card-style mt-2" id="commend-cont">

                </div>
            </div>
        </div>

        <div class="tab_pane" id="lead_notify">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <button class="btn bg-primary modalBtn" id="notify_modal_invoker1" type="button"><i
                            class="fa fa-plus"></i>Add Notify</button>
                </div>
                <div class="form-width-1">
                    <div class="datatable"
                        data-ajax-url="<?php echo url_to('erp.crm.ajaxleadnotifyresponse') . '?leadid=' . $ticket_id . '&'; ?>">

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
                                <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i>
                                    Export</a>
                                <div class="export_container poF">
                                    <ul class="exportUl">
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/lead-notify-export?export=excel&leadid=' . $ticket_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/lead-notify-export?export=excel&leadid=' . $ticket_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>"
                                                    alt="excel">EXCEL</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/lead-notify-export?export=pdf&leadid=' . $ticket_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/lead-notify-export?export=pdf&leadid=' . $ticket_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>"
                                                    alt="pdf">PDF</a></li>
                                        <li><a data-default-href="<?php echo base_url() . 'erp/crm/lead-notify-export?export=csv&leadid=' . $ticket_id . '&'; ?>"
                                                href="<?php echo base_url() . 'erp/crm/lead-notify-export?export=csv&leadid=' . $ticket_id . '&'; ?>"
                                                target="_BLANK"><img
                                                    src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>"
                                                    alt="csv">CSV</a></li>
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
                                <div class="pagination"><span class="dt-page-start">1</span> - <span
                                        class="dt-page-end">5</span> of <span class="dt-total-rows">100<span></div>
                                <ul class="flex paginationBtns">
                                    <li><a type="button" class="HoverA dt-prev-btn"><i class="fa fa-angle-left"></i></a>
                                    </li>
                                    <li><a type="button" class="HoverA dt-next-btn"><i
                                                class="fa fa-angle-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ($ticket->status == 1 && $ticket->assigned_to == get_user_id()) { ?>
            <div class="tab_pane" id="ticket_submit">
                <?php
                echo form_open(url_to('erp.crm.ticketsubmit', $ticket_id), array(
                    "class" => "flex"
                ));
                ?>
                <div class="form-width-1">
                    <div class="form-group ">
                        <label class="form-label">Remarks</label>
                        <textarea rows="3" required name="remarks" class="form_control field-check"></textarea>
                        <p class="error-text"></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group textRight">
                        <button class="btn bg-danger" type="submit" name="tick_status" value="3">Closed</button>
                        <button class="btn bg-success" type="submit" name="tick_status" value="2">Solved</button>
                    </div>
                </div>
                <?php
                echo form_close();
                ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>



<!--MODALS-->
<div class="modal" id="notify_addedit_modal" role="dialog">
    <div class="modalbody">
        <h2 class="modalTitle">Notify</h2>
        <?php
        echo form_open(url_to('erp.crm.leadnotify', $ticket_id), array(
            "id" => "notify_addedit_form",
            "class" => "flex"
        ));
        ?>
        <input type="hidden" name="notify_id" id="f_notify_id" value="0" />
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Title</label>
                <input type="text" class="form_control field-check" id="f_notify_title" name="notify_title" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Description</label>
                <textarea class="form_control field-check" id="f_notify_desc" name="notify_desc"></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Notify at</label>
                <input type="datetime-local" class="form_control field-check" id="f_notify_at" name="notify_at" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label"></label>
                <label class="form-check-label"><input id="f_notify_email" type="checkbox" name="notify_email"
                        value="1" /> Notify via Email too </label>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label"></label>
                <label class="form-check-label"><input id="f_notify_creater" type="checkbox" name="notify_creater"
                        value="1" /> Notify creater too </label>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1 ">
            <div class="form-group textRight ">
                <button type="button" class="btn outline-danger modalClose">Close</button>
                <button class="btn bg-primary" type="button" id="notify_addedit_btn">Save</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script type="text/javascript">
    let tbody = document.querySelector(".attachment-holder");
    let fileuploader = new FileUploader(document.querySelector(".file-uploader-frame"));
    let alert = new ModalAlert();

    fileuploader.ajaxFullUpload({
        files_allowed: ["text/plain", "image/png", "application/pdf", "image/jpeg", "image/gif", "image/jpg", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "image/webp", "image/svg+xml"],
        listener: function (json) {
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

    // let modal_box=new ModalBox(document.getElementById("notify_addedit_modal"));
    // modal_box.init();

    // document.getElementById("notify_modal_invoker1").onclick=(evt)=>{
    //     document.getElementById("f_notify_id").value="0";
    //     document.getElementById("f_notify_title").value="";
    //     document.getElementById("f_notify_desc").value="";
    //     document.getElementById("f_notify_at").value="";
    //     document.getElementById("f_notify_email").checked=false;
    //     document.getElementById("f_notify_creater").checked=false;
    //     modal_box.show_modal();
    // };

    // let paintarea=document.querySelector(".table-paint-area");
    // paintarea.onclick=(evt)=>{
    //     let target=evt.target;
    //     paintarea.querySelectorAll(".modalBtn").forEach((item)=>{
    //         if(item.contains(target)){
    //             let ajax_url=item.getAttribute("data-ajax-url");
    //             let xhr=null;
    //             if(window.XMLHttpRequest){
    //                 xhr=new XMLHttpRequest();
    //             }else if(window.ActiveXObject){
    //                 xhr=new ActiveXObject("Msxml2.XMLHTTP");
    //             }
    //             if(xhr!==null && xhr!==undefined){
    //                 xhr.open("GET",ajax_url,true);
    //                 xhr.send(null);
    //                 xhr.onreadystatechange=(evt)=>{
    //                     if(xhr.readyState==4 && xhr.status==200){
    //                         let json=JSON.parse(xhr.responseText);
    //                         if(json['error']==0){
    //                             let data=json['data'];
    //                             document.getElementById("f_notify_id").value=data['notify_id'];
    //                             document.getElementById("f_notify_title").value=data['title'];
    //                             document.getElementById("f_notify_desc").value=data['notify_text'];
    //                             document.getElementById("f_notify_at").value=data['notify_at'];
    //                             if(data['notify_email']==1){
    //                                 document.getElementById("f_notify_email").checked=true;
    //                             }
    //                             if(data['notify_creater']==1){
    //                                 document.getElementById("f_notify_creater").checked=true;
    //                             }
    //                             modal_box.show_modal();
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // };


    // let form=document.getElementById("notify_addedit_form");
    // let validator=new FormValidate(form);

    // let lock=false;
    // document.getElementById("notify_addedit_btn").onclick=(evt)=>{
    //     if(!lock){
    //         lock=true;
    //         validator.validate(
    //         (params)=>{
    //             form.submit();
    //             lock=false;
    //         },
    //         (params)=>{
    //             lock=false;
    //         },
    //         {});
    //     }
    // }

    // let closer=new WindowCloser();
    // closer.init();

    // document.querySelectorAll(".multiSelectBox").forEach((item)=>{
    //     let multiselectbox=new MultiSelectBox(item);
    //     multiselectbox.init();
    //     closer.register_shutdown(multiselectbox.shutdown,multiselectbox.get_container());
    // });

    // let datatable_elem=document.querySelector(".datatable");

    // let rows_per_page=new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
    // rows_per_page.init();
    // closer.register_shutdown(rows_per_page.shutdown,rows_per_page.get_container());

    // let bulkaction=new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
    // bulkaction.init();
    // closer.register_shutdown(bulkaction.shutdown,bulkaction.get_container());

    // let config=JSON.parse('<?php //echo $notify_datatable_config; 
    ?>');
    // let datatable=new DataTable(datatable_elem,config);
    // datatable.init();

    // rows_per_page.add_listener(datatable.rows_per_page,{});
    $(document).ready(function () {

        $("#btn_commnet").on("click", function (e) {
            let id = $("#ticket_id").val();
            let comment = $("#comment").val();
            if (id) {
                if (comment == "") {
                    $("#comment").addClass("error");
                } else {
                    $("#comment").removeClass("error");

                    $.ajax({
                        url: "<?= url_to('erp.ticket.add.comment') ?>",
                        method: "post",
                        data: {
                            ticketId: id,
                            message: comment
                        },
                        success: function (response) {
                            let alert = new ModalAlert();
                            if (response.success) {
                                alert.invoke_alert(response.message, "success");
                                setTimeout(() => {
                                    location.reload();
                                }, 1500)
                            } else {
                                alert.invoke_alert(response.message, "error");
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
            url: "<?= url_to('erp.ticket.comment.fetch') ?>",
            method: "post",
            data: {
                ticketId: id,
            },
            success: function (response) {
                // let alert = new Alert();
                // console.log(response.success)
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
        // console.log("data => ", data)
        
        if (data.length > 0) {
            $.each(data, function (index, commend) {
                var copy = commend_box;
                var datestring = commend.created_at;
                var date = moment(datestring, 'YYYY-MM-DD HH:mm:ss');
                var date_format = dateformat(date);
                var image = '<?= base_url() ?>' + 'uploads/contract/user-placeholder.jpg';
                var boxcolor = "staff";
                var name = "You";
                if (commend.related_type == "client") {
                    image = '<?= base_url() ?>' + '/uploads/customer/' + commend.image;
                    boxcolor = "client";
                    name = commend.related_id;
                }

                copy = copy.replace('{user_name}', name);
                copy = copy.replace('{userbox}', boxcolor);
                copy = copy.replace('{image_url}', image);
                copy = copy.replace('{time}', date_format);
                copy = copy.replace('{comment}', commend.comment);
                $("#commend-cont").append(copy);
            });
        } else {
            let commend_box = "<p class='text-center p-2 text-danger'>Comments is empty!</p>";
            $("#commend-cont").append(commend_box);
        }
    }

    <?php
    if (session()->getFlashdata("op_success")) { ?>
        alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
        <?php
    } else if (session()->getFlashdata("op_error")) { ?>
            alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
        <?php
    }
    ?>
</script>
</body>

</html>