<div class="alldiv flex widget_title">
    <h3>Update Sale Order</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.sale.orders') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>

<div class="alldiv">
    <?php
        echo form_open(url_to('erp.sale.order.edit'),$order_id,array(
            "id"=>"order_edit_form",
            "class"=>"flex"
        ));
    ?>
        <div class="form-width-1 textCenter">
            <div class="form-group">
                <span class="st st_danger">If you edit this order it's prices will be updated based on the price of the current property unit</span>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-ajax " data-ajax-url="<?=url_to('erp.sale.orders.ajax_order_code_unique').'?id='.$order_id.'&'; ?>" >
                <label class="form-label">Order Code</label>
                <input type="text" name="code" value="<?php echo $order->code; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required" >
                <label class="form-label">Order Date</label>
                <input type="date" name="order_date" value="<?php echo $order->order_date; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
        <div class="form-group field-required" >
            <label class="form-label">Customer</label>
            <div class="ajaxselectBox poR" id="customer_ajax_select_box" data-ajax-url="<?=url_to('erp.sale.getCustomer'); ?>" >
                <div class="ajaxselectBoxBtn flex"> 
                    <div class="textFlow" data-default="select customer"><?php echo $order->name; ?></div>
                    <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="cust_id" value="<?php echo $order->cust_id; ?>" >
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
        <div class="form-width-2">

        </div>
        <div class="form-width-2">
            <div class="form-group field-required" >
                <label class="form-label">Order Expiry</label>
                <input type="date" name="order_expiry" value="<?php echo $order->order_expiry; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required" >
                <label class="form-label">Payment Terms</label>
                <input type="text" name="payment_terms" value="<?php echo $order->payment_terms; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required ">
                <label class="form-label">Terms and condition</label>
                <textarea rows="3" name="terms_condition" class="form_control field-check" ><?php echo $order->terms_condition; ?></textarea>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="widget_title">
                <h3 >Add Units</h3>
            </div>
        </div>
        <div class="form-width-2">
        <div class="form-group" id="order_property" >
            <label class="form-label">Property</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?= url_to('erp.sale.orders.ajaxfetchproperties') ;?>" >
                <div class="ajaxselectBoxBtn flex">
                    <div class="textFlow" data-default="select property">select property</div>
                    <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" value="" >
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
        <div class="form-width-2">
        <div class="form-group" id="order_unit" data-ajax-url="<?=url_to('erp.sale.orders.ajaxfetchpropertyunits') ;?>" >
            <label class="form-label">Unit</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="select unit">select unit</div>
                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" value="" >
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
            
                </ul>
            </div>
            <p class="error-text" ></p>
        </div>
        <input type="hidden" id="order_unit_amount" />
        </div>
        <div class="form-width-1">
            <div class="form-group textRight" >
                <label class="form-label"></label>
                <button class="btn outline-primary" type="button" id="add_item_btn" >Add Unit</button>
            </div>
        </div>
        <div class="form-width-1">
            <table class="table" >
                <thead>
                    <th>SNo</th>
                    <th>Property</th>
                    <th>Unit</th>
                    <th>Amount</th>
                    <th>Action</th>
                </thead>
                <tbody id="order_items_holder" >
                    <?php
                        $sno=1;
                        foreach ($order_items as $row) {
                            ?>
                        <tr>
                        <td><?php echo $sno; ?></td>
                        <td><span><?php echo $row['property'] ;?></span><input type="hidden" name="property_id[<?php echo $sno; ?>]" value="<?php echo $row['property_id'] ;?>" /></td>
                        <td><span><?php echo $row['unit_name'];?></span><input type="hidden" name="unit_id[<?php echo $sno; ?>]" value="<?php echo $row['prop_unit_id']; ?>" /></td>
                        <td><span><?php echo $row['price']; ?></span></td>
                        <td><button type="button" class="btn bg-danger property-remove-btn" ><i class="fa fa-trash"></i></button>
                        </tr>
                    <?php
                        $sno++;
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" ></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><b>Subtotal</b></td>
                        <td id="order_subtotal">0.00</td> 
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><b>Discount</b></td>
                        <td><input type="text" name="discount" id="order_discount" value="<?php echo $order->discount; ?>" class="form_control field-check" /></td> 
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><b>Total</b></td>
                        <td id="order_total">0.00</td> 
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="form-width-1" >
            <div class="form-group textRight ">
                <a class="btn outline-danger" href="<?php echo base_url().'erp/sale/orders' ;?>" >Cancel</a>
                <button class="btn bg-primary" type="button" id="order_edit_btn" >Update</button>
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
        calculateSubtotal();
        calculateTotal();

        let customer_ajax_select=document.getElementById("customer_ajax_select_box");
        let customer_ajax_select_box=new AjaxSelectBox(customer_ajax_select);
        customer_ajax_select_box.init();
        closer.register_shutdown(customer_ajax_select_box.shutdown,customer_ajax_select_box.get_container());

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

        let order_property=document.getElementById("order_property");
        let order_unit=document.getElementById("order_unit");
        let order_price_list_box;
        let order_property_box=new AjaxSelectBox(order_property.querySelector(".ajaxselectBox"));
        order_property_box.init();
        order_property_box.add_listener((params)=>{
            let property_id=params.value;
            let ajax_url=order_unit.getAttribute("data-ajax-url");
            if(property_id!==null && property_id!==undefined && property_id!==""){
                let xhr=null;
                if(window.ActiveXObject){
                    xhr=new ActiveXObject("Msxml2.XMLHTTP");
                }else if(window.XMLHttpRequest){
                    xhr=new XMLHttpRequest();
                }
                if(xhr!==null || xhr!==undefined){
                    xhr.open("GET",ajax_url+"?property_id="+property_id+"&order_id="+"<?php echo $order_id; ?>",true);
                    xhr.send(null);
                    xhr.onreadystatechange=(evt)=>{
                        if(xhr.readyState==4 && xhr.status==200){
                            let json=JSON.parse(xhr.responseText);
                            if(json['error']==0){
                                let data=json['data'];
                                let html=``;
                                for(let i=0;i<data.length;i++){
                                    let extra=[];
                                    if(data[i]['extra']!==null && data[i]['extra']!==undefined && data[i]['extra']!=="" ){
                                        extra=JSON.stringify(data[i]['extra']);
                                    }
                                    html+=` <li role="option" data-value="`+data[i]['key']+`" data-extra='`+extra+`' >`+data[i]['value']+`</li>`;
                                }
                                order_unit.querySelector("ul").innerHTML=html;
                                order_unit_box=new SelectBox(order_unit.querySelector(".selectBox"));
                                order_unit_box.init();
                                order_unit_box.add_listener((params)=>{
                                    document.getElementById("order_unit_amount").value=params.extra[0];
                                },{});
                                closer.register_shutdown(order_unit_box.shutdown,order_unit_box.get_container());
                            }else{
                                alert.invoke_alert(json['reason'],"error");
                            }
                        }
                    }
                }
            }else{
                if(order_unit_box!=null){
                    order_unit.querySelector(".selectBox_Value").value="";
                    order_unit_box.construct();
                }
                order_unit.querySelector("ul").innerHTML="";
                order_unit_box=null;
            }
        },{});
        closer.register_shutdown(order_property_box.shutdown,order_property_box.get_container());
        
        let sno=parseInt("<?php echo $sno++; ?>");
        document.getElementById("add_item_btn").onclick=(evt)=>{
            let amount=parseFloat(document.getElementById("order_unit_amount").value);
            let property_id=order_property.querySelector(".ajaxselectBox_Value").value;
            let property_name=order_property.querySelector(".textFlow").textContent;
            let unit_id=order_unit.querySelector(".selectBox_Value").value;
            let unit_name=order_unit.querySelector(".textFlow").textContent;

            if(property_id===null || property_id===undefined || property_id===""){
                alert.invoke_alert("Select property","error");
                return;
            }
            if(unit_id===null || unit_id===undefined || unit_id===""){
                alert.invoke_alert("Select property unit","error");
                return;
            }

            let unit_names=document.querySelectorAll("#order_items_holder tr td:nth-child(3) span");
            if(unit_names.length!=0){
                let dup_found=false;
                for(let i=0;i<unit_names.length;i++){
                    if(unit_name==unit_names[i].textContent){
                        dup_found=true;
                        break;
                    }
                }
                if(dup_found){
                    alert.invoke_alert("Duplicate Property Unit not allowed","error");
                    return;
                }
            }
            let tr=``;
            tr+=`<td>`+sno+`</td>`
            sno++;
            tr+=`<td><span>`+property_name+`</span><input type="hidden" name="property_id[`+sno+`]" value="`+property_id+`" /></td>`;
            tr+=`<td><span>`+unit_name+`</span><input type="hidden" name="unit_id[`+sno+`]" value="`+unit_id+`" /></td>`;
            tr+=`<td><span>`+amount+`</span></td>`;
            tr+=`<td><button type="button" class="btn bg-danger property-remove-btn" ><i class="fa fa-trash"></i></button>`;
            let element=document.createElement("tr");
            element.innerHTML=tr;
            document.getElementById("order_items_holder").append(element);
            calculateSubtotal();
            calculateTotal();
        }
        document.getElementById("order_items_holder").onclick=(evt)=>{
            let target=evt.target;
            document.querySelectorAll("#order_items_holder .property-remove-btn").forEach((item)=>{
                if(item.contains(target)){
                    item.parentElement.parentElement.remove();
                    calculateSubtotal();
                    calculateTotal();
                }
            });
        }

        document.getElementById("order_discount").onchange=(evt)=>{
            let discount=evt.target.value;
            let pattern=/^[0-9]+\.[0-9]{2}$/;
            if(!pattern.test(discount)){
                alert.invoke_alert("Invalid discount value","error"); 
            }
            calculateTotal();
        };

        function calculateSubtotal(){
            let amounts=document.querySelectorAll("#order_items_holder tr td:nth-child(4) span");
            let total=0.00;
            if(amounts.length!=0){
                for(let i=0;i<amounts.length;i++){
                    total+=parseFloat(amounts[i].textContent);
                }
            }
            document.getElementById("order_subtotal").textContent=total.toFixed(2);
        };

        function calculateTotal(){
            let amount=parseFloat(document.getElementById("order_subtotal").textContent);
            let discount=parseFloat(document.getElementById("order_discount").value);
            let total=0.00;
            if(!isNaN(amount)){
                total+=amount;
            }
            if(!isNaN(discount)){
                total-=discount;
            }
            document.getElementById("order_total").textContent=total.toFixed(2);
        }

        <?php
            if(session()->getFlashdata("op_success")){ ?>
                alert.invoke_alert("<?=session()->getFlashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getFlashdata("op_error")){ ?>
                alert.invoke_alert("<?= session()->getFlashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>