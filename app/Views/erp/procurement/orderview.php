<div class="alldiv flex widget_title">
    <h3>View Purchase Order</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/procurement/orders/' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
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
                    <?php
                        if ($order->status <=1) {
                    ?>
                    <a href="<?php echo base_url().'erp/procurement/ordersend/'.$order_id ;?>" class="btn outline-warning">Send Order</a>
                    <?php
                        }
                    ?>
                    <!-- <a href="" target="_BLANK"  class="btn outline-primary">Print</a> -->
                    <a class="btn outline-primary" id="printbutton">Print</a>
                    <?php
                        if ($order->status <=1) {
                            ?>
                    <a href="<?php echo base_url().'erp/procurement/orderapprove/'.$order_id ;?>" class="btn bg-success">Approve</a>
                    <a href="<?php echo base_url().'erp/procurement/ordercancel/'.$order_id ;?>" class="btn bg-danger">Cancel</a>
                    <?php
                        }
                    ?>
                    <?php 
                        if ($order->status==2 && $order->grn_created==0) {
                            ?>
                        <a href="<?php echo base_url().'erp/procurement/ordergrn/'.$order_id ; ?>" class="btn bg-primary">Create GRN</a>
                    <?php
                    }
                    ?>
                    <?php
                        if($order->status==2 && $order->invoice_created==0){
                    ?>
                        <a href="<?php echo base_url().'erp/procurement/orderinvoice/'.$order_id ;?>" class="btn bg-success">Create Invoice</a>
                    <?php
                        }
                    ?>
                </div>
                <div class="form-width-1" id="printContainer">
                    <h2>Purchase Order Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Order Code</th>
                                    <td><?php echo $order->order_code; ?></td>
                                </tr>
                                <tr>
                                    <th>Supplier</th>
                                    <td><?php echo $order->supplier; ?></td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td><?php echo $order->location; ?></td>
                                </tr>
                                <tr>
                                    <th>Warehouse</th>
                                    <td><?php echo $order->warehouse; ?></td>
                                </tr>
                                <?php
                                    if ($order->internal_transport==1) {
                                        ?>
                                <tr>
                                    <th>Transport Name</th>
                                    <td><?php echo $order->transport; ?></td>
                                </tr>
                                <tr>
                                    <th>Units</th>
                                    <td><?php echo $order->transport_unit; ?></td>
                                </tr>
                                <tr>
                                    <th>Charge</th>
                                    <td><?php echo $order->transport_charge; ?></td>
                                </tr>
                                <?php
                                    } ?>
                                <tr>
                                    <th>Delivery Date</th>
                                    <td><?php echo $order->delivery_date; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="st <?php echo $status_bg[$order->status]; ?>"><?php echo $status[$order->status]; ?></span></td>
                                </tr>
                  
                                 <tr>
                                    <th>Terms and Condition</th>
                                    <td><?php echo $order->terms_condition; ?></td>
                                </tr>
                                <tr>
                                    <th>Notes</th>
                                    <td><?php echo $order->notes; ?></td>
                                </tr>
                                <tr>
                                    <th>GRN created</th>
                                    <td><?php echo (!empty($order->grn_created))?'Yes':'No'; ?></td>
                                </tr>
                                <tr>
                                    <th>Invoice created</th>
                                    <td><?php echo (!empty($order->invoice_created))?'Yes':'No'; ?></td>
                                </tr>
                                <tr>
                                    <th>Created on</th>
                                    <td><?php echo date("d F Y",$order->created_at); ?></td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td><?php echo $order->created; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-width-1">
                    <h2>Order Items</h2>
                    <div class="table_responsive">
                    <table class="table" >
                        <thead>
                            <th>SNo</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Tax</th>
                            <th>Amount</th>
                        </thead>
                        <tbody >
                            <?php
                                $index=1;
                                $total_amount=0;
                                foreach ($order_items as $item) {
                                    $total_amount+=$item['amount'];
                                    ?>
                                <tr>
                                    <td>
                                        <span><?php echo $index ; ?></span>
                                    </td>
                                    <td>
                                        <span><?php echo $item['product'] ; ?></span>
                                    </td>
                                    <td>
                                        <span><?php echo $item['quantity'] ; ?></span>
                                    </td>
                                    <td>
                                        <span><?php echo $item['unit_price'] ; ?></span>
                                    </td>
                                    <td>
                                        <span><?php echo $item['tax'] ; ?></span>
                                    </td>
                                    <td>
                                        <span><?php echo $item['amount'] ; ?></span>
                                    </td>
                                </tr>
                            <?php
                                $index++;
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right" ><b>Total Amount</b></td>
                                <td><b><?php echo $total_amount; ?></b></td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
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

         //Print Procurement Orderview
    window.onload = function() {
        document.getElementById('printbutton').addEventListener('click', function() {
            // Use html2canvas to capture the specific container
            html2canvas(document.getElementById('printContainer')).then(function(canvas) {
                var screenshotImage = new Image();
                screenshotImage.src = canvas.toDataURL();

                // Create a new window and wait for it to load
                var screenshotWindow = window.open();
                screenshotWindow.document.write('<html><head><title>Order Print</title></head><body></body></html>');
                screenshotWindow.document.body.appendChild(screenshotImage);

                // Wait for the image to load in the new window
                screenshotImage.onload = function() {
                    // Trigger print manually (users will still need to confirm)
                    screenshotWindow.print();
                    screenshotWindow.close();
                };
            });
        });
    };
        
        let alert=new ModalAlert();
        <?php
            if(session()->getFlashdata("op_success")){ ?>
                alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getFlashdata("op_error")){ ?>
                alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>