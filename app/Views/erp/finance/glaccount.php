<div class="alldiv flex widget_title">
    <h3>GL Accounts</h3>
    <div class="title_right">
        <a href="#" id="glaccount_modal_invoker1" class="btn bg-success" ><i class="fa fa-plus" ></i> Add </a>
        <a href="<?= url_to('erp.finance.glaccountimport');?>" class="btn bg-success" ><i class="fa fa-plus" ></i> Import </a>
    </div>
</div>

<div class="alldiv">
    <div class="datatable" data-ajax-url="<?php echo url_to('erp.finance.ajaxglaccresponse').'?' ;?>" >
        <div class="filterBox">
            <div class="flex">
                <h4>Filter and Search</h4>
                <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
            </div>
            <div class="flex filterBox_container">
                <div class="formWidth">
                    <div class="selectBox poR" id="glaccount_filter_1" >
                        <div class="selectBoxBtn flex"> 
                            <div class="textFlow" data-default="select group">select group</div>
                            <button class="close" type="button" ><i class="fa fa-close"></i></button>
                            <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                            <input type="hidden"  class="selectBox_Value field-check" name="lead_source" value="" >
                        </div>
                        <ul role="listbox" class="selectBox_Container alldiv">
                            <?php                                                                                                                                                               
                                foreach ($account_groups as $group) {
                            ?>
                            <li role="option" data-value="<?php echo $group['acc_group_id']; ?>" ><?php echo $group['group_name'];?></li>
                            <?php
                                }
                            ?>
                        </ul>
                        </div>
                    </div>
                    <div class="formWidth">
                        
                    </div>
                    <div class="formWidth">
                    </div>
                </div>
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
                            <li><a data-default-href="<?= url_to('erp.finance.glaccgroupexport').'?export=excel&';?>" href="<?= url_to('erp.finance.glaccgroupexport').'?export=excel&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/xls.png' ;?>" alt="excel" >EXCEL</a></li>
                            <li><a data-default-href="<?= url_to('erp.finance.glaccgroupexport').'?export=pdf&';?>" href="<?= url_to('erp.finance.glaccgroupexport').'?export=pdf&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/pdf.png' ;?>" alt="pdf" >PDF</a></li>
                            <li><a data-default-href="<?= url_to('erp.finance.glaccgroupexport').'?export=csv&';?>" href="<?= url_to('erp.finance.glaccgroupexport').'?export=csv&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/csv.png' ;?>" alt="csv" >CSV</a></li>
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
<div class="modal" id="glaccount_addedit_modal" role="dialog">
        <div class="modalbody">
            <h2 class="modalTitle">GL Account</h2>
            <?= form_open(url_to('erp.finance.glaccountadd'),["id"=>"glaccount_addedit_form",
            "class"=>"flex modal-scroll-form"]);
            ?>
                <input type="hidden" name="gl_acc_id" id="f_gl_acc_id" value="0" />
                <div class="form-width-1">
                    <div class="form-group field-ajax" data-ajax-url="<?= url_to('erp.finance.ajaxaccountcodeunique').'?gl_acc_id=0&' ;?>" >
                        <label class="form-label">Account Code</label>
                        <input type="text" class="form_control field-check" id="f_account_code" name="account_code" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required">
                        <label class="form-label">Account Name</label>
                        <input type="text" class="form_control field-check" id="f_account_name" name="account_name" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required ">
                        <label class="form-label">Account Group</label>
                        <div class="selectBox poR">
                            <div class="selectBoxBtn flex"> 
                                <div class="textFlow" data-default="select group">select group</div>
                                <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" id="f_account_group" class="selectBox_Value field-check" name="account_group" value="" >
                            </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                                <?php
                                    foreach ($account_groups as $group) {
                                ?>
                                <li role="option" data-value="<?php echo $group['acc_group_id']; ?>" ><?php echo $group['group_name'];?></li>
                                <?php
                                    }
                                ?>
                            </ul>
                        </div>
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required">
                        <label class="form-label">Cash Flow</label>
                        <div class="selectBox poR">
                            <div class="selectBoxBtn flex"> 
                                <div class="textFlow" data-default="select cashflow">select cashflow</div>
                                <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" id="f_cash_flow" class="selectBox_Value field-check" name="cash_flow" value="" >
                            </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                                <?php
                                    foreach($cashflow as $key=>$value){
                                ?>
                                    <li role="option" data-value="<?php echo $key;?>" ><?php echo $value; ?></li>
                                <?php
                                    }
                                ?>
                            </ul>
                        </div>
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group" >
                        <label class="form-label" >Order</label>
                        <input type="text" class="form_control field-check" id="f_order_num" name="order_num" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-money" >
                        <label class="form-label" >Opening Balance</label>
                        <input type="text" class="form_control field-check" id="f_balance_fwd" name="balance_fwd" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1 ">
                    <div class="form-group textRight ">
                        <button type="button" class="btn outline-danger modalClose">Close</button>
                        <button class="btn bg-primary" type="button" id="glaccount_addedit_btn" >Save</button>
                    </div>
                </div>
            <?= form_close(); ?>
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
        let selectboxes=[];
        document.querySelectorAll("#glaccount_addedit_modal .selectBox").forEach((item)=>{
            let selectbox=new SelectBox(item);
            selectboxes.push(selectbox);
            selectbox.init();
            closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
        });

        let glaccount_modal=new ModalBox(document.getElementById("glaccount_addedit_modal"));
        glaccount_modal.init();

        document.getElementById("glaccount_modal_invoker1").onclick=(evt)=>{
            document.getElementById("f_gl_acc_id").value="0";
            document.getElementById("f_account_code").value="";
            document.getElementById("f_account_name").value="";
            document.getElementById("f_account_group").value="";
            document.getElementById("f_cash_flow").value="";
            document.getElementById("f_order_num").value="";
            document.getElementById("f_balance_fwd").value="";
            for(let i=0;i<selectboxes.length;i++){
                selectboxes[i].construct();
            }
            let ajax_url_holder=document.getElementById("f_account_code").parentElement;
            let ajax_url=ajax_url_holder.getAttribute("data-ajax-url");
            let index=ajax_url.indexOf("gl_acc_id=");
            let amp_index=ajax_url.indexOf("&",index+1);
            let url1=ajax_url.substring(0,index+("gl_acc_id=".length));
            let url2=ajax_url.substring(amp_index);
            ajax_url=url1+"0"+url2;
            ajax_url_holder.setAttribute("data-ajax-url",ajax_url);

            let balance_fwd=document.getElementById("f_balance_fwd").parentElement;
            balance_fwd.style.display="block";
            balance_fwd.classList.add("field-money");
            glaccount_modal.show_modal();
        };

        let glaccount_paintarea=document.querySelector(".table-paint-area");
        glaccount_paintarea.onclick=(evt)=>{
            let target=evt.target;
            glaccount_paintarea.querySelectorAll(".modalBtn").forEach((item)=>{
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
                                    document.getElementById("f_gl_acc_id").value=data['gl_acc_id'];
                                    document.getElementById("f_account_code").value=data['account_code'];
                                    document.getElementById("f_account_name").value=data['account_name'];
                                    document.getElementById("f_account_group").value=data['acc_group_id'];
                                    document.getElementById("f_cash_flow").value=data['cash_flow'];
                                    document.getElementById("f_order_num").value=data['order_num'];
                                    for(let i=0;i<selectboxes.length;i++){
                                        selectboxes[i].construct();
                                    }
                                    let ajax_url_holder=document.getElementById("f_account_code").parentElement;
                                    let ajax_url=ajax_url_holder.getAttribute("data-ajax-url");
                                    let index=ajax_url.indexOf("gl_acc_id=");
                                    let amp_index=ajax_url.indexOf("&",index+1);
                                    let url1=ajax_url.substring(0,index+("gl_acc_id=".length));
                                    let url2=ajax_url.substring(amp_index);
                                    ajax_url=url1+data['gl_acc_id']+url2;
                                    ajax_url_holder.setAttribute("data-ajax-url",ajax_url);

                                    let balance_fwd=document.getElementById("f_balance_fwd").parentElement;
                                    balance_fwd.style.display="none";
                                    balance_fwd.classList.remove("field-money");
                                    glaccount_modal.show_modal();
                                }
                            }
                        }
                    }
                }
            });
        };

        let glaccount_form=document.getElementById("glaccount_addedit_form");
        let glaccount_validator=new FormValidate(glaccount_form);

        let glaccount_lock=false;
        document.getElementById("glaccount_addedit_btn").onclick=(evt)=>{
            if(!glaccount_lock){
                glaccount_lock=true;
                glaccount_validator.validate(
                (params)=>{
                    glaccount_form.submit();
                    glaccount_lock=false;
                },
                (params)=>{
                    glaccount_lock=false;
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

        let config=JSON.parse('<?php echo $glaccount_datatable_config; ?>');
        let datatable=new DataTable(datatable_elem,config);
        datatable.init();

        rows_per_page.add_listener(datatable.rows_per_page,{});

        if(config['filters']!==null && config['filters']!==undefined){
            let filter_1=document.getElementById("glaccount_filter_1");

            let select_box_1=new SelectBox(filter_1);
            select_box_1.init();
            select_box_1.add_listener(datatable.filter,{type:"select",column:config['filters'][0]});
            closer.register_shutdown(select_box_1.shutdown,select_box_1.get_container());
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