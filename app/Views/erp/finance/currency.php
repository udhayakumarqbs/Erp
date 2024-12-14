<div class="alldiv flex widget_title">
    <h3>Currency</h3>
    <div class="title_right">
        <a href="#" id="currency_modal_invoker1" class="btn bg-success" ><i class="fa fa-plus" ></i> Add </a>
    </div>
</div>

<div class="alldiv">
    <div class="datatable" data-ajax-url="<?php echo url_to('erp.finance.ajaxcurrencyresponse').'?' ;?>" >
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
                            <li><a data-default-href="<?php echo base_url().'erp/finance/currency-export?export=excel&';?>" href="<?php echo base_url().'erp/finance/currency-export?export=excel&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/xls.png' ;?>" alt="excel" >EXCEL</a></li>
                            <li><a data-default-href="<?php echo base_url().'erp/finance/currency-export?export=pdf&';?>" href="<?php echo base_url().'erp/finance/currency-export?export=pdf&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/pdf.png' ;?>" alt="pdf" >PDF</a></li>
                            <li><a data-default-href="<?php echo base_url().'erp/finance/currency-export?export=csv&';?>" href="<?php echo base_url().'erp/finance/currency-export?export=csv&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/csv.png' ;?>" alt="csv" >CSV</a></li>
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
<div class="modal" id="currency_addedit_modal" role="dialog">
        <div class="modalbody">
            <h2 class="modalTitle">Currency</h2>
            <?php
                echo form_open(url_to('erp.finance.currency'),array(
                    "id"=>"currency_addedit_form",
                    "class"=>"flex"
                ));
            ?>
                <input type="hidden" name="currency_id" id="f_currency_id" value="0" />
                <div class="form-width-1">
                    <div class="form-group field-required ">
                        <label class="form-label">ISO Code</label>
                        <input type="text" class="form_control field-check" id="f_iso_code" name="iso_code" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required">
                        <label class="form-label">Symbol</label>
                        <input type="text" class="form_control field-check" id="f_symbol" name="symbol" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-required ">
                        <label class="form-label">Decimal Separator</label>
                        <div class="selectBox poR">
                            <div class="selectBoxBtn flex"> 
                                <div class="textFlow" data-default="select decimal">select decimal</div>
                                <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" id="f_decimal_sep" class="selectBox_Value field-check" name="decimal_sep" value="" >
                            </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                                <li role="option" data-value="." >. period</li>
                                <li role="option" data-value="," >, comma</li>
                            </ul>
                        </div>
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group ">
                        <label class="form-label">Thousand Separator</label>
                        <div class="selectBox poR">
                            <div class="selectBoxBtn flex"> 
                                <div class="textFlow" data-default="select thousand">select thousand</div>
                                <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" id="f_thousand_sep" class="selectBox_Value field-check" name="thousand_sep" value="" >
                            </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                                <li role="option" data-value="." >. period</li>
                                <li role="option" data-value="," >, comma</li>
                                <li role="option" data-value="'" >' apostrophe</li>
                                <li role="option" data-value=" " >  space</li>
                                <li role="option" data-value="" >none</li>
                            </ul>
                        </div>
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-checked-any">
                        <label class="form-label" >Place</label>
                        <div id="f_place" >
                            <label class="form-check-label"><input type="radio" class="field-check" name="place" value="before" /> Before </label>
                            <label class="form-check-label"><input type="radio" class="field-check" name="place" value="after" /> After </label>
                        </div>
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1 ">
                    <div class="form-group textRight ">
                        <button type="button" class="btn outline-danger modalClose">Close</button>
                        <button class="btn bg-primary" type="button" id="currency_addedit_btn" >Save</button>
                    </div>
                </div>
            </form>
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

        let closer=new WindowCloser();
        closer.init();
        let selectboxes=[];
        document.querySelectorAll("#currency_addedit_modal .selectBox").forEach((item)=>{
            let selectbox=new SelectBox(item);
            selectboxes.push(selectbox);
            selectbox.init();
            closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
        });

        let currency_modal=new ModalBox(document.getElementById("currency_addedit_modal"));
        currency_modal.init();

        document.getElementById("currency_modal_invoker1").onclick=(evt)=>{
            document.getElementById("f_currency_id").value="0";
            document.getElementById("f_iso_code").value="";
            document.getElementById("f_symbol").value="";
            document.getElementById("f_decimal_sep").value="";
            document.getElementById("f_thousand_sep").value="";
            for(let i=0;i<selectboxes.length;i++){
                selectboxes[i].construct();
            }
            document.querySelectorAll("#f_place .field-check").forEach((item)=>{
                item.checked=false;
            });
            currency_modal.show_modal();
        };

        let currency_paintarea=document.querySelector(".table-paint-area");
        currency_paintarea.onclick=(evt)=>{
            let target=evt.target;
            currency_paintarea.querySelectorAll(".modalBtn").forEach((item)=>{
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
                                    document.getElementById("f_currency_id").value=data['currency_id'];
                                    document.getElementById("f_iso_code").value=data['iso_code'];
                                    document.getElementById("f_symbol").value=data['symbol'].trim();
                                    document.getElementById("f_decimal_sep").value=data['decimal_sep'];
                                    document.getElementById("f_thousand_sep").value=data['thousand_sep'];
                                    for(let i=0;i<selectboxes.length;i++){
                                        selectboxes[i].construct();
                                    }
                                    let place=data['place'];
                                    document.querySelectorAll("#f_place .field-check").forEach((item)=>{
                                        if(item.getAttribute("value")==place){
                                            item.checked=true;
                                        }
                                    });
                                    currency_modal.show_modal();
                                }
                            }
                        }
                    }
                }
            });
        };

        let currency_form=document.getElementById("currency_addedit_form");
        let currency_validator=new FormValidate(currency_form);

        let currency_lock=false;
        document.getElementById("currency_addedit_btn").onclick=(evt)=>{
            if(!currency_lock){
                currency_lock=true;
                currency_validator.validate(
                (params)=>{
                    currency_form.submit();
                    currency_lock=false;
                },
                (params)=>{
                    currency_lock=false;
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

        let config=JSON.parse('<?php echo $currency_datatable_config; ?>');
        let datatable=new DataTable(datatable_elem,config);
        datatable.init();

        rows_per_page.add_listener(datatable.rows_per_page,{});


        <?php
    if (session()->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
    </script>
    </body>
</html>