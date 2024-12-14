<div class="alldiv flex widget_title">
    <h3>View Requisition</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.procurement.requisition') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="req_info">Info</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="req_info">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <?php if($requisition->status==0){ ?>
                        <a href="<?= url_to('erp.procurement.requisitionhandle',$req_id) ;?>" class="btn bg-primary ">Handle</a>
                    <?php } ?>

                    <?php if($requisition->status==2){ ?>
                        <a href="<?= !empty($is_rfq_created->rfq_id)? url_to('erp.procurement.rfqview',$is_rfq_created->rfq_id) : url_to('erp.procurement.createrfq',$req_id );?>" class="btn bg-primary ">
                        <?= !empty($is_rfq_created->rfq_id)? "View RFQ":"Create RFQ";?>
                        </a>

                        <a href="<?= !empty($is_order_created->req_id)? url_to('erp.procurement.rfqview',$is_order_created->req_id) : url_to("erp.procurement.createorder_req",$req_id);?>" class="btn bg-success ">
                        <?= !empty($is_order_created->req_id)? "View Order" : "Create Order"; ?>
                        </a>
                    <?php } ?>

                    <?php if($requisition->status==0){ ?>
                        <a href="<?php  echo url_to('erp.procurement.requisitiondelete', $req_id) ;?>" class="btn bg-danger del-confirm">Delete</a>
                    <?php } ?>


                </div>
                <div class="form-width-1">
                    <h2>Requisition Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Requisition Code</th>
                                    <td><?php echo $requisition->req_code; ?></td>
                                </tr>
                                <tr>
                                    <th>Assigned To </th>
                                    <td><?php echo $requisition->assigned; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="st <?php echo $status_bg[$requisition->status]; ?>"><?php echo $status[$requisition->status]; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Priority</th>
                                    <td><span class="st <?php echo $priority_bg[$requisition->priority]; ?>"><?php echo $priority[$requisition->priority]; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Sent in Mail</th>
                                    <td><?php echo $requisition->mail_sent; ?></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $requisition->description; ?></td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td><?php echo $requisition->remarks; ?></td>
                                </tr>
                                <tr>
                                    <th>Created on</th>
                                    <td><?php echo date("d F Y",$requisition->created_at); ?></td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td><?php echo $requisition->created; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-width-1">
                    <h2 class="mt-4">Requisition Products</h2>
                    <div class="table_responsive">
                    <table class="table" >
                        <thead>
                            <th>SNO</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                        </thead>
                        <tbody id="requisition_items_holder" >
                            <?php
                                $req_item_counter=1;
                                foreach ($requisition_items as $item) {
                                    ?>
                                <tr>
                                    <td>
                                        <span><?php echo $req_item_counter ; ?></span>
                                    </td>
                                    <td>
                                        <span><?php echo $item['product'] ; ?></span>
                                    </td>
                                    <td>
                                        <span><?php echo $item['qty'] ; ?></span>
                                    </td>
                                </tr>
                            <?php
                                $req_item_counter++;
                                }
                            ?>
                        </tbody>
                    </table>
                    </div>
                    </div>
                    <?php
                        if ($requisition->assigned_to==get_user_id() && $requisition->status==1) {
                            ?>
                        <div class="form-width-1">
                            <h2 class="mt-5">Requisition Status Update</h2>
                            <?php
                                echo form_open(url_to('erp.procurement.requisitionaction',$req_id),array(
                                    "id"=>"requisition_action_form",
                                    "class"=>"flex"
                                ));
                            ?>
                            <div class="form-width-2">
                                <div class="form-group field-required">
                                    <label class="form-label">Status</label>
                                    <div class="selectBox poR">
                                        <div class="selectBoxBtn flex"> 
                                            <div class="textFlow" data-default="select status">select status</div>
                                            <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                            <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                            <input type="hidden" class="selectBox_Value field-check" name="req_status" value="" >
                                        </div>
                                        <ul role="listbox" class="selectBox_Container alldiv">
                                        <?php
                                            for ($i=2;$i<count($status);$i++) {
                                                ?>
                                            <li role="option" data-value="<?php echo $i;?>" ><?php echo $status[$i];?></li>
                                            <?php
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                    <p class="error-text" ></p>
                                </div>
                            </div>
                            <div class="form-width-1 ">
                                <div class="form-group field-required">
                                    <label class="form-label">Remarks</label>
                                    <textarea rows="3" name="remarks" class="form_control field-check" ></textarea>
                                    <p class="error-text"></p>
                                </div>
                            </div>
                            <div class="form-width-1">
                                <div class="form-group textRight">
                                    <a href="<?=url_to('erp.procurement.requisition') ;?>" class="btn outline-secondary">Cancel</a>
                                    <button class="btn bg-primary" type="button" id="requisition_action_btn">Save</button>
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
        
        <?php
            if ($requisition->assigned_to==get_user_id() && $requisition->status==1 ) {
        ?>
            let closer=new WindowCloser();
            closer.init();

            document.querySelectorAll(".selectBox").forEach((item)=>{
                let selectbox=new SelectBox(item);
                selectbox.init();
                closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
            });

            let form=document.getElementById("requisition_action_form");
            let validator=new FormValidate(form);

            let lock=false;
            document.getElementById("requisition_action_btn").onclick=(evt)=>{
                if(!lock){
                    lock=true;
                    validator.validate(
                    (params)=>{
                        form.submit();
                        lock=false;
                    },
                    (params)=>{
                        lock=false;
                    },
                    {});
                }
            }
        <?php
            }
        ?>
                let alert=new ModalAlert();
        <?php
            if(session()->getFlashdata("op_success")){ ?>
                alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getFlashdata("op_error")){ ?>
                alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>","error");
        <?php }?>
    </script>
    </body>
</html>