<div class="alldiv flex widget_title">
    <h3>View Sale Requests</h3>
    <div class="title_right">
        <a href="<?=url_to('erp.sale.requests') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>

<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="req_info">Request Info</a></li>
        <li><a type="button" class="tab_nav_item" data-src="req_message">Message</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="req_info">
            <div class="flex">
                <div class="form-width-1 textRight">
                    <?php
                        echo form_open(base_url().'erp/sale/requestaction/'.$request_id,array(
                            "id"=>"request_action_form"
                        ));
                    ?>
                        <?php
                            if (empty($request->responded_by)) {
                        ?>
                            <input type="hidden" name="action" value="respond" />
                            <button type="submit" class="btn bg-primary" >Handle</button>
                        <?php
                            }else if($request_status[$request->status]!="Responded" && $request_status[$request->status]!="Closed"){  ?>
                            <input type="hidden" name="action" value="statuschange" />
                            <div class="flex textLeft">
                                <div class="form-width-3">
                                    <div class="form-group field-required ">
                                    <!-- <label class="form-label"></label> -->
                                    <div class="selectBox poR">
                                        <div class="selectBoxBtn flex"> 
                                            <div class="textFlow" data-default="Status Change">Status Change</div>
                                            <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                            <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                            <input type="hidden"  class="selectBox_Value field-check" name="status" value="" >
                                        </div>
                                        <ul role="listbox" class="selectBox_Container alldiv">
                                            <?php
                                                for($i=2;$i<count($request_status);$i++) {
                                                    ?>
                                            <li role="option" data-value="<?php echo $i;?>" ><?php echo $request_status[$i];?></li>
                                            <?php
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                    <p class="error-text" ></p>
                                </div>
                                </div>
                                <div class="form-width-3">
                                    <button type="button" class="btn bg-primary" id="request_action_btn" >Change</button>
                                </div>
                                <div class="form-width-3">
                                </div>
                            </div>
                            
                        <?php
                            }
                        ?>
                    <?php
                        echo form_close();
                    ?>
                </div>
                <div class="form-width-1">
                    <div class="flex request_flex">
                        <div class="form-width-2">
                            <div class="table_responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="textCenter" >Info</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>From</b></td>
                                            <td><?php echo $request->from_m; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>To</b></td>
                                            <td><?php echo $request->to_m; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Purpose</b></td>
                                            <td><?php echo $request->purpose; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Related To</b></td>
                                            <td><?php echo ucfirst(str_replace("_"," ",$request->related_to)); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Requested For</b></td>
                                            <td><?php echo $request->requested_for; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Requested By</b></td>
                                            <td><?php echo $request->requested; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Requested On</b></td>
                                            <td><?php echo date("d F Y",$request->requested_at); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Sent via Email</b></td>
                                            <td><span class="st <?php 
                                                if($request->mail_request==1){ ?>
                                                st_success " >Yes</span>
                                            <?php    }else{  ?>
                                                st_dark " >No</span>
                                            <?php
                                                }
                                            ?></td>
                                        </tr>
                                        <?php
                                            if (!empty($request->responded_by)) {
                                                ?>
                                        <tr>
                                            <td><b>Responded By</b></td>
                                            <td><?php echo $request->responded_by; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Responded On</b></td>
                                            <td><?php echo date("d F Y",$request->responded_at); ?></td>
                                        </tr>
                                        <?php
                                            }
                                        ?>
                                        <tr>
                                            <td><b>Status</b></td>
                                            <td><?php echo $span_status; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" ><b>Description</b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><?php echo $request->description; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-width-2">
                            <div class="table_responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="textCenter" >Attachments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $attachments=explode(",",$request->attachment);
                                        for ($i=0;$i<count($attachments);$i++) {
                                            ?>
                                        <tr>
                                            <td><?php echo $i+1; ?></td>
                                            <td><a target="_BLANK" download class="text-primary" href="<?php echo get_attachment_link('request').$attachments[$i]; ?>" ><?php echo $attachments[$i]; ?></a></td>
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
            </div>
        </div>
        <div class="tab_pane" id="req_message">
            <p>lorem English</p>
        </div>
    </div>
</div>







<!--SCRIPT WORKS -->
</div>
    </main>
    <script src="<?php echo base_url().'assets/js/jquery.min.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/script.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/erp.js' ;?>" ></script>

    <script type="text/javascript">
        let closer=new WindowCloser();
        closer.init();

        document.querySelectorAll(".selectBox").forEach((item)=>{
            let selectbox=new SelectBox(item);
            selectbox.init();
            closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
        });

        let request_form=document.getElementById("request_action_form");
        let request_validator=new FormValidate(request_form);

        let request_lock=false;
        document.getElementById("request_action_btn").onclick=function(evt){
            if(!request_lock){
                request_lock=true;
                request_validator.validate(
                (params)=>{
                    request_form.submit();
                    request_lock=false;
                },
                (params)=>{
                    request_lock=false;
                },
                {});
            }
        }

        <?php
            if($this->session->flashdata("op_success")){ ?>
                let alerts=new ModalAlert();
                alerts.invoke_alert("<?php echo $this->session->flashdata('op_success'); ?>","success");
        <?php
            }else if($this->session->flashdata("op_error")){ ?>
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo $this->session->flashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>