<div class="alldiv flex widget_title">
    <h3>Update Order</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/procurement/orders' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>

<div class="alldiv">
    <?php
        echo form_open(base_url().'erp/procurement/orderedit/'.$order_id,array(
            "id"=>"order_edit_form",
            "class"=>"flex"
        ));
    ?>
        <div class="form-width-3">
            <div class="form-group field-ajax " data-ajax-url="<?php echo base_url().'erp/procurement/ajax_order_code_unique?id='.$order_id.'&'; ?>" >
                <label class="form-label">Order Code</label>
                <input type="text" name="order_code" value="<?php echo $order->order_code; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-3">
            <div class="form-group field-required">
                <label class="form-label">Selection Rule</label>
                <div class="selectBox poR" id="selection_basis_box" data-ajax-url="<?php echo base_url().'erp/procurement/ajax_get_selection_basis?' ;?>" >
                    <div class="selectBoxBtn flex"> 
                        <div class="textFlow" data-default="select basis">select basis</div>
                        <button class="close" type="button" ><i class="fa fa-close"></i></button>
                        <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" name="selection_rule" value="<?php echo $order->selection_rule; ?>" >
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                            foreach ($selection_rules as $row) {
                        ?>
                        <li role="option" data-value="<?php echo $row['rule_id'];?>" ><?php echo $row['rule_name'];?></li>
                        <?php
                            }
                        ?>
                        <li role="option" data-value="supply_list" >Supply List Basis</li>
                        <li role="option" data-value="supplier_name" >Supplier Name Basis</li>
                    </ul>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-3" id="basis_box_holder" >
            <?php
                echo $supplier_select['html'];
            ?>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Delivery date</label>
                <input type="date" name="delivery_date" value="<?php echo $order->delivery_date; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2" id="supplier_location_holder">
            
        </div>
        <div class="form-width-2">
            <div class="form-group">
                <div>
                    <label class="form-check-label"><input id="internal_transport" <?php
                        if($order->internal_transport==1){
                            echo "checked";
                        }
                    ?> type="checkbox" name="internal_transport" value="1" /> Use Company's Transport</label>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2 internal-transport-field">
            <div class="form-group field-required field-no-validate" >
                <label class="form-label">Select Transport</label>
                <div class="ajaxselectBox transportajaxselectbox poR" data-ajax-url="<?php echo base_url().'erp/procurement/ajaxfetchtransport'; ?>" >
                    <div class="ajaxselectBoxBtn flex"> 
                        <div class="textFlow" data-default="Select Transport"><?php echo (!empty($order->transport))?$order->transport:'Select Transport' ; ?></div>
                        <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                        <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="ajaxselectBox_Value field-check" name="transport_id" value="<?php echo (!empty($order->transport_id))?$order->transport_id:''; ?>" >
                    </div>
                    <div class="ajaxselectBox_Container alldiv">
                        <input type="text" class="ajaxselectBox_Search form_control" />
                        <ul role="listbox" >

                        </ul>
                    </div>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2 internal-transport-field" >
            <div class="form-group field-number field-no-validate">
                <label class="form-label">Transport unit</label>
                <input type="text" name="transport_unit" value="<?php echo $order->transport_unit; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2 internal-transport-field" >
            <div class="form-group field-money field-no-validate">
                <label class="form-label">Transport charge</label>
                <input type="text" name="transport_charge" value="<?php echo $order->transport_charge; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required ">
                <label class="form-label">Warehouse</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex"> 
                        <div class="textFlow" data-default="select warehouse">select warehouse</div>
                        <button class="close" type="button" ><i class="fa fa-close"></i></button>
                        <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                        <input type="hidden"  class="selectBox_Value field-check" name="warehouse_id" value="<?php echo $order->warehouse_id; ?>" >
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                            foreach ($warehouses as $row) {
                                ?>
                        <li role="option" data-value="<?php echo $row['warehouse_id'];?>" ><?php echo $row['name'];?></li>
                        <?php
                            }
                        ?>
                    </ul>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Terms and Condition</label>
                <textarea rows="3" name="terms_condition" class="form_control field-check" ><?php echo $order->terms_condition; ?></textarea>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea rows="3" name="notes" class="form_control field-check" ><?php echo $order->notes; ?></textarea>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="widget_title">
                <h3 >Purchase Items</h3> 
            </div>
        </div>
        <div class="form-width-1">
            <table class="table" >
                <thead>
                    <th>SNO</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price List</th>
                    <th>Tax</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </thead>
                <tbody id="order_items_holder" >
                    <?php
                        $index=1;
                        $total_amount=0;
                        foreach ($order_items as $row) {
                            $total_amount+=$row['amount'];
                    ?>
                    <tr>
                        <td><?php echo $index; ?></td>
                        <td><?php echo $row['product']; ?></td>
                        <td class="db-qty"><?php echo $row['quantity'] ;?></td>
                        <td>
                            <div class="form-group field-required" >
                                <div class="ajaxselectBox poR" data-ajax-url="<?php echo base_url().'erp/procurement/ajaxfetchpricelist'; ?>" >
                                    <div class="ajaxselectBoxBtn flex"> 
                                        <div class="textFlow" data-default="Price List"><?php echo $row['name']; ?></div>
                                        <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                                        <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                        <input type="hidden" class="ajaxselectBox_Value field-check" name="price_list_<?php echo $row['order_item_id']; ?>" value="<?php echo $row['price_id'] ; ?>" >
                                    </div>
                                    <div class="ajaxselectBox_Container alldiv">
                                        <input type="text" class="ajaxselectBox_Search form_control" />
                                        <ul role="listbox" >

                                        </ul>
                                    </div>
                                </div>
                                <p class="error-text" ></p>
                            </div>
                        </td>
                        <td class="db-tax"><?php echo $row['tax'] ;?></td>
                        <td class="db-unit-price"><?php echo $row['unit_price'] ;?></td>
                        <td class="db-amount"><?php echo $row['amount'] ;?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right" colspan="6" ><b>Total Amount</b></td>
                        <td ><b id="order_items_total" ><?php echo $total_amount; ?></b></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="form-width-1" >
            <div class="form-group textRight ">
                <a class="btn outline-danger" href="<?php echo base_url().'erp/procurement/order' ;?>" >Cancel</a>
                <button class="btn bg-primary" type="button" id="order_edit_btn" >Save</button>
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
        let alert=new ModalAlert();
        closer.init();

        document.querySelectorAll(".selectBox:not(#selection_basis_box)").forEach((item)=>{
            let selectbox=new SelectBox(item);
            selectbox.init();
            closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
        });
        
        let internal_transport=document.getElementById("internal_transport");
        if(internal_transport.checked){
            document.querySelectorAll(".internal-transport-field .form-group").forEach((item)=>{
                item.classList.remove("field-no-validate");
                item.parentElement.style.display="block";
            });
        }

        document.getElementById("internal_transport").onchange=(evt)=>{
            let transport_state=evt.target.checked;
            if(transport_state){
                document.querySelectorAll(".internal-transport-field .form-group").forEach((item)=>{
                    item.classList.remove("field-no-validate");
                    item.parentElement.style.display="block";
                });
            }else{
                document.querySelectorAll(".internal-transport-field .form-group").forEach((item)=>{
                    item.classList.add("field-no-validate");
                    item.parentElement.style.display="none";
                });
            }
        }

        document.querySelectorAll(".transportajaxselectbox").forEach((item)=>{
            let ajaxselectbox=new AjaxSelectBox(item);
            ajaxselectbox.init();
            closer.register_shutdown(ajaxselectbox.shutdown,ajaxselectbox.get_container());
        });

        document.querySelectorAll("#order_items_holder .ajaxselectBox").forEach((item)=>{
            let ajaxselectbox=new AjaxSelectBox(item);
            ajaxselectbox.init();
            ajaxselectbox.add_listener((params)=>{
                let value=params['value'];
                let grandparent=item.parentElement.parentElement.parentElement;
                if(value!==""){
                    let extra=params['extra'];
                    grandparent.querySelector(".db-tax").textContent=extra[1];
                    grandparent.querySelector(".db-unit-price").textContent=extra[0];
                    let unit_price=parseFloat(extra[0]);
                    let qty=parseInt(grandparent.querySelector(".db-qty").textContent);
                    let amount=unit_price*qty;
                    grandparent.querySelector(".db-amount").textContent=amount.toFixed(2);
                }else{
                    grandparent.querySelector(".db-tax").textContent="";
                    grandparent.querySelector(".db-unit-price").textContent="";
                    grandparent.querySelector(".db-amount").textContent="";
                }
                calc_order_items_total();
            },{});
            closer.register_shutdown(ajaxselectbox.shutdown,ajaxselectbox.get_container());
        });

        let basis_box_holder=document.getElementById("basis_box_holder");
        document.querySelectorAll("#selection_basis_box").forEach((item)=>{
            let selectbox=new SelectBox(item);
            selectbox.init();
            let ajax_url=item.getAttribute("data-ajax-url");
            selectbox.add_listener((params)=>{
                let basis=params['value'] || '';
                if(basis!==''){
                    let xhr=null;
                    if(window.ActiveXObject){
                        xhr=new ActiveXObject("Msxml2.XMLHTTP");
                    }else if(window.XMLHttpRequest){
                        xhr=new XMLHttpRequest();
                    }
                    if(xhr!==null || xhr!==undefined){
                        xhr.open("GET",ajax_url+"data="+basis,true);
                        xhr.send(null);
                        xhr.onreadystatechange=(evt)=>{
                            if(xhr.readyState==4 && xhr.status==200){
                                let json=JSON.parse(xhr.responseText);
                                if(json['error']==0){
                                    let data=json['data'];
                                    basis_box_holder.innerHTML=data['html'];
                                    if(data['type']==="selectbox"){
                                        build_selectbox();
                                    }else if(data['type']==="ajaxselectbox"){
                                        build_ajaxselectbox();
                                    }
                                }else{
                                    basis_box_holder.innerHTML="";
                                    alert.invoke_alert(json['reason'],"error");
                                }
                            }
                        }
                    }
                }else{
                    basis_box_holder.innerHTML="";
                }
            },{});
            closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
        });

        function calc_order_items_total(){
            let order_items_total=document.getElementById("order_items_total");
            let tds=document.querySelectorAll("#order_items_holder .db-amount");
            let total_amount=0;
            for(let i=0;i<tds.length;i++){
                let amount=tds[i].textContent.trim();
                if(amount!==""){
                    total_amount+=parseFloat(amount);
                }
            }
            order_items_total.textContent=total_amount.toFixed(2);
        }

        function get_supplier_location(supplier_id,value=""){
            let ajax_url="<?php echo $supplier_location_url; ?>";
            let supplier_location_holder=document.getElementById("supplier_location_holder");
            let xhr=null;
            if(window.ActiveXObject){
                xhr=new ActiveXObject("Msxml2.XMLHTTP");
            }else if(window.XMLHttpRequest){
                xhr=new XMLHttpRequest();
            }
            if(xhr!==null || xhr!==undefined){
                xhr.open("GET",ajax_url+"data="+supplier_id,true);
                xhr.send(null);
                xhr.onreadystatechange=(evt)=>{
                    if(xhr.readyState==4 && xhr.status==200){
                        let json=JSON.parse(xhr.responseText);
                        if(json['error']==0){
                            let data=json['data'];
                            let html=`<div class="form-group field-required">
                                        <label class="form-label">Supplier Location</label>
                                        <div class="selectBox supplierlocationselectbox poR" >
                                            <div class="selectBoxBtn flex"> 
                                                <div class="textFlow" data-default="select location">select location</div>
                                                <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                                <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                                <input type="hidden" class="selectBox_Value field-check" name="supplier_location" value="`+value+`" >
                                            </div>
                                            <ul role="listbox" class="selectBox_Container alldiv">`;
                            for(let i=0;i<data.length;i++){
                                html+=`<li role="option" data-value="`+data[i]['location_id']+`" >`+data[i]['location']+`</li>`;
                            }
                            html+=
                                                `
                                            </ul>
                                        </div>
                                        <p class="error-text" ></p>
                                    </div>`;
                            supplier_location_holder.innerHTML=html;
                            document.querySelectorAll(".supplierlocationselectbox").forEach((item)=>{
                                let selectbox=new SelectBox(item);
                                selectbox.init();
                                selectbox.add_listener((params)=>{

                                },{})
                                closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
                            });
                        }else{
                            supplier_location_holder.innerHTML="";
                            //alert.invoke_alert(json['reason'],"error");
                        }
                    }
                }
            }
        }

        function build_selectbox(){
            document.querySelectorAll(".selectionselectbox").forEach((item)=>{
                let selectbox=new SelectBox(item);
                selectbox.init();
                selectbox.add_listener((params)=>{
                    get_supplier_location(params['value']);
                },{});
                closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
            });
        }

        function build_ajaxselectbox(){
            document.querySelectorAll(".selectionajaxselectbox").forEach((item)=>{
                let ajaxselectbox=new AjaxSelectBox(item);
                ajaxselectbox.init();
                ajaxselectbox.add_listener((params)=>{
                    get_supplier_location(params['value']);
                },{});
                closer.register_shutdown(ajaxselectbox.shutdown,ajaxselectbox.get_container());
            });
        }

        let form=document.getElementById("order_edit_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("order_edit_btn").onclick=function(evt){
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
            if(session()->getFlashdata("op_success")){ ?>
                alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getFlashdata("op_error")){ ?>
                alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>","error");
        <?php
            }
        ?>

        <?php
            if($supplier_select['type']=="selectbox"){ ?>
                build_selectbox();
                get_supplier_location(<?php echo $order->supplier_id; ?>,<?php echo $order->supp_location_id; ?>);
        <?php
            }else if($supplier_select['type']=="ajaxselectbox"){
        ?>
                build_ajaxselectbox();
                get_supplier_location(<?php echo $order->supplier_id; ?>,<?php echo $order->supp_location_id; ?>);
        <?php
            }
        ?>
        
    </script>
    </body>
</html>