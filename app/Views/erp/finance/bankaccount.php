<div class="alldiv flex widget_title">
    <h3>Bank Accounts</h3>
    <div class="title_right">
        <a href="#" id="bankaccount_modal_invoker1" class="btn bg-success" ><i class="fa fa-plus" ></i> Add </a>
    </div>
</div>

<div class="alldiv">
    <div class="datatable" data-ajax-url="<?= url_to('erp.finance.ajaxbankaccountresponse').'?';?>" >
        <div class="filterBox">
            <div class="flex">
                <h4>Filter and Search</h4>
                <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
            </div>
     
            <div class="tableHeader flex">
                <div class="formWidth">
                    <div class="selectBox poR bulkaction" data-ajax-url="" >
                        <div class="selectBoxBtn flex"> 
                            <div class="textFlow" data-default="Action">Action</div>
                            <button class="close" type="button" ><i class="fa fa-close"></i></button>
                            <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                            <input type="hidden" class="selectBox_Value" value="" >
                        </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                            <li role="option" data-value="1" >Delete</li>
                            <li role="option" data-value="2" >Send</li>
                        </ul>
                    </div>
                </div>
                <div class="formWidth">
                    <input type="text" placeholder="search" class="form_control dt-search">
                </div>
                <div class="formWidth textRight">
                    <!--export button-->
                    <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i> Export</a>
                    <div class="export_container poF">
                        <ul class="exportUl">
                            <li><a data-default-href="<?= url_to('erp.finance.bankaccountexport').'?export=excel&';?>" href="<?= url_to('erp.finance.bankaccountexport').'?export=excel&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/xls.png' ;?>" alt="excel" >EXCEL</a></li>
                            <li><a data-default-href="<?= url_to('erp.finance.bankaccountexport').'?export=pdf&';?>" href="<?= url_to('erp.finance.bankaccountexport').'?export=pdf&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/pdf.png' ;?>" alt="pdf" >PDF</a></li>
                            <li><a data-default-href="<?= url_to('erp.finance.bankaccountexport').'?export=csv&';?>" href="<?= url_to('erp.finance.bankaccountexport').'?export=csv&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/csv.png' ;?>" alt="csv" >CSV</a></li>
                        </ul>
                        <a type="button" class="closeBtn3 HoverA"><i class="fa fa-close"></i></a>
                    </div>
                    <!--export button-->
                </div>
            </div>
            <div class="table_responsive">
                <table class="table">
                    <thead class="thead">
                        
                    </thead>
                    <tbody class="table-paint-area">
                        
                    </tbody>
                </table>
            </div>
            <div class="tableFooter flex">
                <div class="tableFooterLeft flex">
                    <p>Rows per page:</p>
                    <div class="selectBox miniSelectBox poR">
                        <div class="selectBoxBtn flex"> 
                            <div class="textFlow" data-default="10">10</div>
                            <button class="drops"><i class="fa fa-caret-down"></i></button>
                            <input type="hidden" class="selectBox_Value" value="10">
                        </div>
                        <ul role="listbox" class="selectBox_Container alldiv">
                            <li role="option" class="active" data-value="10" >10</li>
                            <li role="option" data-value="15" >15</li>
                            <li role="option" data-value="20">20</li>
                            <li role="option" data-value="25">25</li>
                        </ul>
                    </div>
                </div>
                <div class="tableFooterRight flex">
                    <div class="pagination"><span class="dt-page-start" >1</span> - <span class="dt-page-end">5</span> of <span class="dt-total-rows" >100<span></div>
                        <ul class="flex paginationBtns">
                            <li><a type="button" class="HoverA dt-prev-btn" ><i class="fa fa-angle-left"></i></a></li>
                            <li><a type="button" class="HoverA dt-next-btn" ><i class="fa fa-angle-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!--MODALS-->
<div class="modal" id="bankaccount_addedit_modal" role="dialog">
        <div class="modalbody">
            <h2 class="modalTitle">Bank Account</h2>
            <?= form_open(url_to('erp.finance.bankaccountsadd'),[
                    "id"=>"bankaccount_addedit_form",
                    "class"=>"flex modal-scroll-form"
            ]);

            ?>
                <input type="hidden" name="bank_id" id="f_bank_id" value="0" />
                <div class="form-width-1">
                    <div class="form-group field-required" >
                        <label class="form-label">GL Account</label>
                        <div class="ajaxselectBox poR" data-ajax-url="<?= url_to('erp.finance.ajaxfetchglaccounts'); ?>" >
                            <div class="ajaxselectBoxBtn flex"> 
                                <div class="textFlow" data-default="GL account">GL account</div>
                                <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                                <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" id="f_gl_acc_id" class="ajaxselectBox_Value field-check" name="gl_acc_id" value="" >
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
                <div class="form-width-1">
                    <div class="form-group field-required">
                        <label class="form-label">Bank Name</label>
                        <input type="text" class="form_control field-check" id="f_bank_name" name="bank_name" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required" >
                        <label class="form-label" >Bank Account No</label>
                        <input type="text" class="form_control field-check" id="f_bank_acc_no" name="bank_acc_no" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required" >
                        <label class="form-label" >Bank Code</label>
                        <input type="text" class="form_control field-check" id="f_bank_code" name="bank_code" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required" >
                        <label class="form-label" >Branch</label>
                        <input type="text" class="form_control field-check" id="f_branch" name="branch" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required" >
                        <label class="form-label" >Address</label>
                        <input type="text" class="form_control field-check" id="f_address" name="address" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1 ">
                    <div class="form-group textRight ">
                        <button type="button" class="btn outline-danger modalClose">Close</button>
                        <button class="btn bg-primary" type="button" id="bankaccount_addedit_btn" >Save</button>
                    </div>
                </div>
            <?= form_close();?>
        </div>
    </div>
<!-- MODAL ENDS-->





<!--SCRIPT WORKS -->
</div>
    </main>
    <script src="<?php echo base_url().'assets/js/jquery.min.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/script.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/erp.js' ;?>" ></script>

    <script type="text/javascript">
    let alert=new ModalAlert();
        let closer=new WindowCloser();
        closer.init();
        let ajaxselectboxes=[];
        document.querySelectorAll("#bankaccount_addedit_modal .ajaxselectBox").forEach((item)=>{
            let ajaxselectbox=new AjaxSelectBox(item);
            ajaxselectboxes.push(ajaxselectbox);
            ajaxselectbox.init();
            closer.register_shutdown(ajaxselectbox.shutdown,ajaxselectbox.get_container());
        });

        let bankaccount_modal=new ModalBox(document.getElementById("bankaccount_addedit_modal"));
        bankaccount_modal.init();

        document.getElementById("bankaccount_modal_invoker1").onclick=(evt)=>{
            document.getElementById("f_bank_id").value="0";
            document.getElementById("f_bank_name").value="";
            document.getElementById("f_bank_acc_no").value="";
            document.getElementById("f_bank_code").value="";
            document.getElementById("f_branch").value="";
            document.getElementById("f_address").value="";
            document.getElementById("f_gl_acc_id").value="";
            for(let i=0;i<ajaxselectboxes.length;i++){
                ajaxselectboxes[i].construct();
            }
            bankaccount_modal.show_modal();
        };

        let bankaccount_paintarea=document.querySelector(".table-paint-area");
        bankaccount_paintarea.onclick=(evt)=>{
            let target=evt.target;
            bankaccount_paintarea.querySelectorAll(".modalBtn").forEach((item)=>{
                if(item.contains(target)){
                    let ajax_url=item.getAttribute("data-ajax-url");
                    let xhr=null;
                    if(window.XMLHttpRequest){
                        xhr=new XMLHttpRequest();
                    }else if(window.ActiveXObject){
                        xhr=new ActiveXObject("Msxml2.XMLHTTP");
                    }
                    if(xhr!==null && xhr!==undefined){
                        xhr.open("GET",ajax_url,true);
                        xhr.send(null);
                        xhr.onreadystatechange=(evt)=>{
                            if(xhr.readyState==4 && xhr.status==200){
                                let json=JSON.parse(xhr.responseText);
                                if(json['error']==0){
                                    let data=json['data'];
                                    document.getElementById("f_bank_id").value=data['bank_id'];
                                    document.getElementById("f_bank_name").value=data['bank_name'];
                                    document.getElementById("f_bank_acc_no").value=data['bank_acc_no'];
                                    document.getElementById("f_bank_code").value=data['bank_code'];
                                    document.getElementById("f_branch").value=data['branch'];
                                    document.getElementById("f_address").value=data['address'];
                                    document.getElementById("f_gl_acc_id").value=data['gl_acc_id'];
                                    document.getElementById("f_gl_acc_id").previousElementSibling.previousElementSibling.previousElementSibling.textContent=data['gl_account'];
                                    for(let i=0;i<ajaxselectboxes.length;i++){
                                        ajaxselectboxes[i].construct();
                                    }
                                    bankaccount_modal.show_modal();
                                }
                            }
                        }
                    }
                }
            });
        };

        let bankaccount_form=document.getElementById("bankaccount_addedit_form");
        let bankaccount_validator=new FormValidate(bankaccount_form);

        let bankaccount_lock=false;
        document.getElementById("bankaccount_addedit_btn").onclick=(evt)=>{
            if(!bankaccount_lock){
                bankaccount_lock=true;
                bankaccount_validator.validate(
                (params)=>{
                    bankaccount_form.submit();
                    bankaccount_lock=false;
                },
                (params)=>{
                    bankaccount_lock=false;
                },
                {});
            }
        }

        let datatable_elem=document.querySelector(".datatable");

        let rows_per_page=new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
        rows_per_page.init();
        closer.register_shutdown(rows_per_page.shutdown,rows_per_page.get_container());

        let bulkaction=new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
        bulkaction.init();
        closer.register_shutdown(bulkaction.shutdown,bulkaction.get_container());

        let config=JSON.parse('<?php echo $bankaccount_datatable_config; ?>');
        let datatable=new DataTable(datatable_elem,config);
        datatable.init();

        rows_per_page.add_listener(datatable.rows_per_page,{});

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