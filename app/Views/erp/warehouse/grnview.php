<div class="alldiv flex widget_title">
    <h3>View GRN</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.warehouse.grn') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="grn_info">Info</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="lead_profile">
            <div class="flex">
                <div class="form-width-1 text-right">
                <?php
                    if($grn[0]['status']==1){ ?>
                    <!-- <a href="<?php echo base_url().'erp/warehouse/createpack/'.$grn_id ;?>" class="btn bg-success">Create Pack</a> -->
                    <!-- <a href="<?php echo base_url().'erp/warehouse/addtostock/'.$grn_id ;?>" class="btn bg-success">Add to Stock</a> -->
                <?php
                    }
                ?>
                <?php
                    if($grn[0]['status']<=1){ ?>
                    <a href="<?= url_to('erp.warehouse.grnupdate',$grn_id)?>" class="btn bg-primary">Update GRN</a>
                <?php
                    }
                ?>
                <?php
                    if($grn[0]['status']==2){ ?>
                    <a href="<?php echo base_url().'erp/warehouse/grnitems_import/'.$grn_id ;?>" class="btn bg-primary">Import Items</a>
                <?php
                    }
                ?>
                </div>
                <div class="form-width-1">
                    <h2>GRN Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Order Code</th>
                                    <td><?php echo $grn[0]['order_code']; ?></td>
                                </tr>
                                <tr>
                                    <th>Supplier</th>
                                    <td><?php echo $grn[0]['supplier']; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="st <?php echo $grn_status_bg[$grn[0]['status']]; ?>"><?php echo $grn_status[$grn[0]['status']]; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Delivered On</th>
                                    <td><?php echo $grn[0]['delivered_on']; ?></td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td><?php echo $grn[0]['remarks']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-width-1">
                <h2>Order Items</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th>SNo</th>
                                <th>Product</th>
                                <th>Unit Price</th>
                                <th>Tax</th>
                                <th>Ordered Qty</th>
                                <th>Received Qty</th>
                                <th>Returned Qty</th>
                            </thead>
                            <tbody>
                                <?php
                                    $index=1;
                                    foreach ($order_items as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $index; ?></td>
                                    <td><?php echo $row['product']; ?></td>
                                    <td><?php echo $row['unit_price']; ?></td>
                                    <td><?php echo $row['tax']; ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td><?php echo $row['received_qty']; ?></td>
                                    <td><?php echo $row['returned_qty']; ?></td>
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







<!--SCRIPT WORKS -->
</div>
    </main>
    <script src="<?php echo base_url().'assets/js/jquery.min.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/script.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/erp.js' ;?>" ></script>
    <script type="text/javascript">

        <?php
            if(session()->getFlashdata("op_success")){ ?>
                let alerts=new ModalAlert();
                alerts.invoke_alert("<?= session()->getFlashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getFlashdata("op_error")){ ?>
                let alert=new ModalAlert();
                alert.invoke_alert("<?= session()->getFlashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>