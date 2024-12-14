<div class="alldiv flex widget_title">
    <h3>View Project Testing</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.project.projects'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="testing_info">Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="testing_attachment">Attachments</a></li>
        <?php
        if ($testing->result == 0) {
        ?>
            <li><a type="button" class="tab_nav_item" data-src="testing_result">Result</a></li>
        <?php
        }
        ?>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="testing_info">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <a href="<?=url_to('erp.project.testingdelete',$project_test_id,$project_id);?>" class="btn bg-danger del-confirm">Delete</a>
                </div>
                <div class="form-width-1">
                    <h2>Project Testing Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $testing->test_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $testing->description; ?></td>
                                </tr>
                                <tr>
                                    <th>Created on</th>
                                    <td><?php echo date("Y-m-d", $testing->created_at); ?></td>
                                </tr>
                                <tr>
                                    <th>Assigned to</th>
                                    <td><?php echo $testing->assigned; ?></td>
                                </tr>
                                <tr>
                                    <th>Complete Before</th>
                                    <td><?php echo $testing->complete_before; ?></td>
                                </tr>
                                <tr>
                                    <th>Result</th>
                                    <td><span class="st <?php echo $testing_status_bg[$testing->result]; ?>"><?php echo $testing_status[$testing->result]; ?></span></td>
                                </tr>
                                <?php
                                if ($testing->result != 0) {
                                ?>
                                    <tr>
                                        <th>Completed on</th>
                                        <td><?php echo $testing->completed_at; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Remarks</th>
                                        <td><?php echo $testing->remarks; ?></td>
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
        <div class="tab_pane" id="testing_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <div class="file-uploader-frame" data-ajax-url="<?php echo url_to('erp.project.uploadtestingattachment') . '?id=' . $project_test_id . '&'; ?>">
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
                            <tbody class="attachment-holder" data-ajaxdel-url="<?php echo url_to('erp.project.testingdeleteattachment') . '?'; ?>">
                                <?php
                                foreach ($attachments as $attach) {
                                ?>
                                    <tr>
                                        <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('project_testing') . $attach['filename']; ?>"><?php echo $attach['filename']; ?></a></td>
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
        <div class="tab_pane" id="testing_result">
            <?php
            echo form_open(url_to('erp.project.testingupdate', $project_test_id), array(
                "class" => "flex",
            ));
            ?>
            <div class="form-width-1">
                <div class="form-group ">
                    <label class="form-label">Remarks</label>
                    <textarea rows="3" name="remarks" required class="form_control field-check"></textarea>
                    <p class="error-text"></p>
                </div>
            </div>
            <div class="form-width-1">
                <div class="form-group textRight">
                    <button class="btn bg-danger" type="submit" name="result" value="2">Failed</button>
                    <button class="btn bg-success" type="submit" name="result" value="1">Passed</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>



<!--MODALS-->



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

    <?php
    if (session()->getflashdata("op_success")) { ?>
        alert.invoke_alert("<?php echo session()->getflashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getflashdata("op_error")) { ?>
        alert.invoke_alert("<?php echo session()->getflashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>