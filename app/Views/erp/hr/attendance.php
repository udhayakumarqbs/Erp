<div class="alldiv flex widget_title">
    <h3>Employee Attendance</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/hr/employees' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <div class="flex">
        <div class="form-width-1 textRight">
            <a href="<?php echo url_to('erp.hr.attendanceadd') ;?>" class="btn bg-primary" >Add Entry</a>
            <button type="button" class="btn bg-success" id="attendance_export_invoker" >Monthly Export</button>
        </div>
        <div class="form-width-1">
            <div class="datatable" data-ajax-url="<?php echo url_to('erp.hr.ajaxattendanceresponse').'?' ;?>" >
                <div class="filterBox">
                    <div class="flex">
                        <h4>Filter and Search</h4>
                        <a type='button' class="filterIcon HoverA" title="Filter open/close"><i class="fa fa-filter"></i></a>
                    </div>
                    <div class="flex filterBox_container">
                        <div class="formWidth">
                            <div class="selectBox poR" id="attend_filter_1" >
                                <div class="selectBoxBtn flex"> 
                                    <div class="textFlow" data-default="select type">select type</div>
                                    <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                    <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                    <input type="hidden"  class="selectBox_Value field-check" value="" >
                                </div>
                                <ul role="listbox" class="selectBox_Container alldiv">
                                    <?php
                                        foreach ($attend_types as $key=>$value) {
                                            ?>
                                    <li role="option" data-value="<?php echo $key;?>" ><?php echo $value;?></li>
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
                                <li><a data-default-href="<?php echo base_url().'erp/hr/attendance-export?export=excel&';?>" href="<?php echo base_url().'erp/hr/attendance-export?export=excel&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/xls.png' ;?>" alt="excel" >EXCEL</a></li>
                                <li><a data-default-href="<?php echo base_url().'erp/hr/attendance-export?export=pdf&';?>" href="<?php echo base_url().'erp/hr/attendance-export?export=pdf&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/pdf.png' ;?>" alt="pdf" >PDF</a></li>
                                <li><a data-default-href="<?php echo base_url().'erp/hr/attendance-export?export=csv&';?>" href="<?php echo base_url().'erp/hr/attendance-export?export=csv&';?>" target="_BLANK" ><img src="<?php echo base_url().'assets/images/icons/csv.png' ;?>" alt="csv" >CSV</a></li>
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


<!-- Modal Starts -->
    <div class="modal" id="attendance_export_modal" role="dialog">
        <div class="modalbody">
            <h2 class="modalTitle">Monthly Export</h2>
            <?php
                echo form_open(url_to('erp.hr.attendance'),array(
                    "id"=>"attendance_export_form",
                    "class"=>"flex"
                ));
            ?>
                <div class="form-width-1">
                    <div class="form-group " >
                        <label class="form-label">Employee</label>
                        <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.hr.ajaxfetchemployees'); ?>" >
                            <div class="ajaxselectBoxBtn flex"> 
                                <div class="textFlow" data-default="select employee">select employee</div>
                                <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                                <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" class="ajaxselectBox_Value field-check" name="employee_id" value="" >
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
                        <label class="form-label">Month</label>
                        <div class="selectBox poR">
                            <div class="selectBoxBtn flex"> 
                                <div class="textFlow" data-default="select month">select month</div>
                                <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" class="selectBox_Value field-check" name="select_month" value="" >
                            </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                            <?php
                                foreach ($months as $key=>$status) {
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
                <div class="form-width-1">
                    <div class="form-group field-required">
                        <label class="form-label">Year</label>
                        <div class="selectBox poR">
                            <div class="selectBoxBtn flex"> 
                                <div class="textFlow" data-default="select year">select year</div>
                                <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" class="selectBox_Value field-check" name="select_year" value="" >
                            </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                            <?php
                                foreach ($years as $row) {
                                    ?>
                                <li role="option" data-value="<?php echo $row['years'];?>" ><?php echo $row['years'];?></li>
                                <?php
                                    }
                                ?>
                            </ul>
                        </div>
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1 ">
                    <div class="form-group textRight">
                        <button type="button" class="btn outline-danger modalClose">Close</button>
                        <button class="btn bg-primary" type="button" id="attendance_export_submit">Export xls</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal" id="attendance_edit_modal" role="dialog">
        <div class="modalbody">
            <h2 class="modalTitle">Attendance Update</h2>
            <?php
                echo form_open(url_to('erp.hr.attendanceedit'),array(
                    "id"=>"attendance_edit_form",
                    "class"=>"flex"
                ));
            ?>
                <input type="hidden" name="attend_id" id="attend_id" />
                <div class="form-width-1">
                    <div class="form-group field-required">
                        <label class="form-label">Status</label>
                        <div class="selectBox poR">
                            <div class="selectBoxBtn flex"> 
                                <div class="textFlow" data-default="select status">select status</div>
                                <button class="close" type="button" ><i class="fa fa-close"></i></button>
                                <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                                <input type="hidden" class="selectBox_Value field-check" name="status" id="attend_status" value="" >
                            </div>
                            <ul role="listbox" class="selectBox_Container alldiv">
                                <?php
                                    foreach($attend_types as $key=>$value){
                                ?>
                                <li role="option" data-value="<?php echo $key;?>" ><?php echo $value;?></li>
                                <?php
                                    }
                                ?>
                            </ul>
                        </div>
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-number" >
                        <label class="form-label">Work hours</label>
                        <input type="text" name="work_hours" id="attend_work_hours" class="form_control field-check" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1">
                    <div class="form-group field-number" >
                        <label class="form-label">OT hours</label>
                        <input type="text" name="ot_hours" id="attend_ot_hours" class="form_control field-check" />
                        <p class="error-text" ></p>
                    </div>
                </div>
                <div class="form-width-1 ">
                    <div class="form-group textRight">
                        <button type="button" class="btn outline-danger modalClose">Close</button>
                        <button class="btn bg-primary" type="button" id="attendance_edit_submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


<!-- Modal ends -->




<!--SCRIPT WORKS -->
</div>
    </main>
    <script src="<?php echo base_url().'assets/js/jquery.min.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/script.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/erp.js' ;?>" ></script>
    <script type="text/javascript">
        let closer=new WindowCloser();
        closer.init();

        document.querySelectorAll("#attendance_export_modal .selectBox").forEach((item)=>{
            let selectbox=new SelectBox(item);
            selectbox.init();
            closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
        });

        document.querySelectorAll("#attendance_export_modal .ajaxselectBox").forEach((item)=>{
            let ajaxselectbox=new AjaxSelectBox(item);
            ajaxselectbox.init();
            closer.register_shutdown(ajaxselectbox.shutdown,ajaxselectbox.get_container());
        });

        let modal_box=new ModalBox(document.getElementById("attendance_export_modal"));
        modal_box.init();
        document.getElementById("attendance_export_invoker").onclick=(evt)=>{
            modal_box.show_modal();
        }
        let form=document.getElementById("attendance_export_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("attendance_export_submit").onclick=function(evt){
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

        let datatable_elem=document.querySelector(".datatable");
        let rows_per_page=new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
        rows_per_page.init();
        closer.register_shutdown(rows_per_page.shutdown,rows_per_page.get_container());
        let bulkaction=new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
        bulkaction.init();
        closer.register_shutdown(bulkaction.shutdown,bulkaction.get_container());
        let config=JSON.parse('<?php echo $attendance_datatable_config; ?>');
        let datatable=new DataTable(datatable_elem,config);
        datatable.init();
        rows_per_page.add_listener(datatable.rows_per_page,{});
        if(config['filters']!==null && config['filters']!==undefined){
            let filter_1=document.getElementById("attend_filter_1");

            let select_box_1=new SelectBox(filter_1);
            select_box_1.init();
            select_box_1.add_listener(datatable.filter,{type:"select",column:config['filters'][0]});
            closer.register_shutdown(select_box_1.shutdown,select_box_1.get_container());
        }


        let attend_edit_modal=new ModalBox(document.getElementById("attendance_edit_modal"));
        attend_edit_modal.init();
        let attend_edit_select=[];
        document.querySelectorAll("#attendance_edit_modal .selectBox").forEach((item)=>{
            let selectbox=new SelectBox(item);
            selectbox.init();
            attend_edit_select.push(selectbox);
            closer.register_shutdown(selectbox.shutdown,selectbox.get_container());
        });

        let paintarea=document.querySelector(".table-paint-area");
        paintarea.onclick=(evt)=>{
            let target=evt.target;
            paintarea.querySelectorAll(".modalBtn").forEach((item)=>{
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
                                    document.getElementById("attend_id").value=data['attend_id'];
                                    document.getElementById("attend_work_hours").value=data['work_hours'];
                                    document.getElementById("attend_ot_hours").value=data['ot_hours'];
                                    document.getElementById("attend_status").value=data['status'];
                                    for(let i=0;i<attend_edit_select.length;i++){
                                        attend_edit_select[i].construct();
                                    }
                                    attend_edit_modal.show_modal();
                                }
                            }
                        }
                    }
                }
            });
        };

        let attend_edit_form=document.getElementById("attendance_edit_form");
        let attend_edit_validator=new FormValidate(attend_edit_form);

        let attend_edit_lock=false;
        document.getElementById("attendance_edit_submit").onclick=function(evt){
            if(!attend_edit_lock){
                attend_edit_lock=true;
                attend_edit_validator.validate(
                (params)=>{
                    attend_edit_form.submit();
                    attend_edit_lock=false;
                },
                (params)=>{
                    attend_edit_lock=false;
                },
                {});
            }
        }

        <?php
            if(session()->getFlashdata("op_success")){ ?>
                let alerts=new ModalAlert();
                alerts.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>","success");
        <?php
            }else if(session()->getFlashdata("op_error")){ ?>
                let alert=new ModalAlert();
                alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>","error");
        <?php
            }
        ?>
    </script>
    </body>
</html>