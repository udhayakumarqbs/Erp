<div class="alldiv flex widget_title">
    <h3>Create Marketing</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.crm.marketing') ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(url_to('erp.crm.marketingadd'),array(
            "class"=>"flex",
            "id"=>"marketing_add_form"
        ));
    ?>
        <div class="form-width-2">
            <div class="form-group field-ajax " data-ajax-url="<?php echo url_to('erp.crm.ajaxmarketingnameunique').'?' ; ?>" > 
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-money ">
                <label class="form-label">Amount</label>
                <input type="text" name="amount" class="form_control field-check" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Product Type</label>
            <div class="selectBox poR">
                <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="select type">select type</div>
                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="related_to" value="" >
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                <?php
                    $first_marketing_product="";
                    foreach ($marketing_products as $key=>$status) {
                        if(empty($first_marketing_product)){
                            $first_marketing_product=$key;
                        }
                        ?>
                    <li role="option" data-value="<?php echo $key;?>" ><?php echo $status;?></li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
            <p class="error-text" ></p>
        </div>
        </div>
        <div class="form-width-2">
        <div class="form-group field-required" >
            <label class="form-label">Product</label>
            <div class="ajaxselectBox poR" data-ajax-url="<?php echo base_url().$marketing_products_links[$first_marketing_product]; ?>" >
                <div class="ajaxselectBoxBtn flex"> 
                    <div class="textFlow" data-default="select product">select product</div>
                    <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="ajaxselectBox_Value field-check" name="related_id" value="" >
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
            <div class="form-group field-required" >
                <label class="form-label">Done by</label>
                <input type="text" name="done_by" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group" >
                <label class="form-label">Email ID</label>
                <input type="text" name="email" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Phone 1</label>
                <input type="text" name="phone1" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group ">
                <label class="form-label">Phone 2</label>
                <input type="text" name="phone2" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
 
        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-label">Company</label>
                <input type="text" name="company" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-label">Address</label>
                <textarea rows="3" name="address" class="form_control field-check" ></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group field-required">
                <label class="form-label">Description</label>
                <textarea rows="3" name="description" class="form_control field-check" ></textarea>
                <p class="error-text" ></p>
            </div>
        </div>

        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo url_to('erp.crm.marketing') ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="marketing_add_submit">Save</button>
            </div>
        </div>
    </form>
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

        let marketing_product_links=JSON.parse('<?php echo json_encode($marketing_products_links); ?>');
        let base_url="<?php echo base_url(); ?>";

        let ajaxselectbox_elem=document.querySelector(".ajaxselectBox");
        let ajaxselectbox=new AjaxSelectBox(ajaxselectbox_elem);
        let default_ajax_url=ajaxselectbox_elem.getAttribute("data-ajax-url");
        ajaxselectbox.init();
        closer.register_shutdown(ajaxselectbox.shutdown,ajaxselectbox.get_container());

        let selectbox_elem=document.querySelector(".selectBox");
        let selectbox=new SelectBox(selectbox_elem);
        selectbox.init();
        selectbox.add_listener((params)=>{
            let url=params['value'];
            if(url!==null && url!==undefined && url!==""){
                ajaxselectbox_elem.setAttribute("data-ajax-url",base_url+marketing_product_links[url]);
            }else{
                ajaxselectbox_elem.setAttribute("data-ajax-url",default_ajax_url);
            }
        },{});
        closer.register_shutdown(selectbox.shutdown,selectbox.get_container());

        let form=document.getElementById("marketing_add_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("marketing_add_submit").onclick=function(evt){
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
            if(session()->setFlashdata("op_success")){ ?>
                let alerts=new ModalAlert();
                alerts.invoke_alert("<?php echo session()->setFlashdata('op_success'); ?>","success");
        <?php
            }else if(session()->setFlashdata("op_error")){ ?>
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo session()->setFlashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>