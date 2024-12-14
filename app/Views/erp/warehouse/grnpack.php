<div class="alldiv flex widget_title">
    <h3>GRN Pack</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/warehouse/grns' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(base_url()."erp/warehouse/createpack/".$grn_id,array(
            "id"=>"grn_pack_form",
            "class"=>"flex"
        ));
    ?>
    <?php
        foreach ($grn_items as $row) {
            ?>
    <div class="form-width-1">
        <div class="table_responsive">
            <table class="table">
                <thead>
                    <th>Product</th>
                    <th>Received Qty</th>
                    <th>Remaining Qty</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $row['product'];?></td>
                        <td><?php echo $row['received_qty'];?></td>
                        <td><span class="remaining-qty"><?php echo $row['received_qty'];?></span></td>
                        <td><button class="btn outline-primary pack-add-btn" type="button">Add Pack</button></td>
                    </tr>
                    <tr>
                        <th colspan="2" >Pack</th>
                        <th>Pack Qty</th>
                        <th>Action</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
        }
    ?>
    <div class="form-width-1">
        <div class="form-group textRight">
            <a href="<?php echo base_url().'erp/warehouse/grns' ;?>" class="btn outline-secondary">Cancel</a>
            <button class="btn bg-primary" type="button" id="grn_pack_submit">Save</button>
        </div>
    </div>
    <?php
        echo form_close();
    ?>
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

        document.querySelectorAll(".pack-add-btn").forEach((item)=>{
            item.onclick=(evt)=>{
                let select_box_html=`
                        <div class="form-group field-required">
                            <div class="selectBox poR">
                                <div class="selectBoxBtn flex"> 
                                    <div class="textFlow" data-default="select pack">select pack</div>
                                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden" class="selectBox_Value field-check" value="" >
                                </div>
                                <ul role="listbox" class="selectBox_Container alldiv">
                                <?php
                                    $pack_ids=explode(",",$row['pack_ids']);
                                    $pack_names=explode(",",$row['pack_names']);
                                    for($i=0;$i<count($pack_ids);$i++) {
                                        ?>
                                    <li role="option" data-value="<?php echo $pack_ids[$i];?>" ><?php echo $pack_names[$i];?></li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                            <p class="error-text" ></p>
                        </div>`;
            }
        });

        <?php
            if($this->session->flashdata("op_success")){ ?>
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo $this->session->flashdata('op_success'); ?>","success");
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