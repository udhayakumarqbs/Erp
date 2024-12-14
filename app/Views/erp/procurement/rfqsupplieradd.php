<div class="alldiv flex widget_title">
    <h3>Create RFQ Supplier</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/procurement/rfqview/'.$rfq_id ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(base_url().'erp/procurement/rfqsupplieradd/'.$rfq_id,array("class"=>"flex","id"=>"rfqsupplier_add_form"));
    ?>

        <div class="form-width-2">
        <div class="form-group field-required">
            <label class="form-label">Selection Rule</label>
            <div class="selectBox poR" id="selection_basis_box" data-ajax-url="<?php echo base_url().'erp/procurement/ajax_get_selection_basis?' ;?>" >
                <div class="selectBoxBtn flex"> 
                    <div class="textFlow" data-default="select basis">select basis</div>
                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                    <input type="hidden" class="selectBox_Value field-check" name="selection_rule" value="" >
                </div>
                <ul role="listbox" class="selectBox_Container alldiv">
                    <li role="option" data-value="supply_list" >Supply List Basis</li>
                    <li role="option" data-value="supplier_name" >Supplier Name Basis</li>
                </ul>
            </div>
            <p class="error-text" ></p>
        </div>
        </div>
        <div class="form-width-2" id="basis_box_holder" >

        </div>
        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-check-label"><input value="1" type="checkbox" name="send_contacts" /> Send to Active Contacts too </label>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-check-label"><input value="1" type="checkbox" name="include_attach" /> Include Attachments</label>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/procurement/rfqview/'.$rfq_id ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="rfqsupplier_add_submit">Save</button>
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
        let alert=new ModalAlert();

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
                                    alert.invoke_alert(json['reason'],"error");
                                }
                            }
                        }
                    }
                }
            },{});
            closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
        });

        function build_selectbox(){
            document.querySelectorAll(".selectBox:not(#selection_basis_box)").forEach((item)=>{
                let selectbox=new SelectBox(item);
                selectbox.init();
                closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
            });
        }

        function build_ajaxselectbox(){
            document.querySelectorAll(".ajaxselectBox").forEach((item)=>{
                let ajaxselectbox=new AjaxSelectBox(item);
                ajaxselectbox.init();
                closer.register_shutdown(ajaxselectbox.shutdown,ajaxselectbox.get_container());
            });
        }


        let form=document.getElementById("rfqsupplier_add_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("rfqsupplier_add_submit").onclick=function(evt){
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
    </script>
    </body>
</html>