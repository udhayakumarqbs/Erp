<div class="alldiv flex widget_title">
    <h3>View Task</h3>
    <div class="title_right">
        <a href="<?php echo base_url() . 'erp/crm/task'; ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="task_info">Info</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="task_info">
            <div class="flex">
                <div class="form-width-1">
                    <div class=" mb-2">
                        <h2>Task Info</h2>
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Subject</th>
                                    <td><?php echo $task->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Start date</th>
                                    <td><?php echo $task->start_date; ?></td>
                                </tr>
                                <tr>
                                    <th>Due date</th>
                                    <td><?php echo $task->due_date; ?></td>
                                </tr>
                                <tr>
                                    <th>Assigned To</th>
                                    <td><?php echo $tasks->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Priority</th>
                                    <td><span class="st <?php echo $task_priority_bg[$task->priority]; ?>"><?php echo $task_priority[$task->priority]; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="st <?php echo $task_status_bg[$task->status]; ?>"><?php echo $task_status[$task->status]; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Related to</th>
                                    <td><?php echo $task->related_to; ?></td>
                                </tr>
                                <tr>
                                    <th>Related</th>
                                    <td><?php echo $tasks->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Followers</th>
                                    <td><?php echo $followers->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $task->task_description; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script type="text/javascript">
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