<div class="alldiv flex widget_title">
    <h3>Create Journal Entry</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/finance/journalentry' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(base_url(),array(
            "class"=>"flex",
            "id"=>"journalentry_add_form"
        ));
    ?>
        <div class="form-width-2">
            <div class="form-group field-required" >
                <label class="form-label" >Date to Process</label>
                <input type="date" name="transaction_date" id="f_date_to_process" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-checked-any" >
                <label class="form-label" >Type</label>
                <div id="f_journal_type" >
                    <label class="form-check-label"><input type="radio" class="field-check" name="type" value="0" /> Normal </label>
                    <label class="form-check-label"><input type="radio" class="field-check" name="type" value="1" /> Revising </label>
                </div>
                <p class="error-text" ></p>
            </div>
        </div>

        <div class="form-width-3">
            <div class="form-group field-required" >
                <label class="form-label">GL Account</label>
                <div class="ajaxselectBox poR" data-ajax-url="<?php echo url_to('erp.finance.ajaxfetchglaccounts'); ?>" >
                    <div class="ajaxselectBoxBtn flex"> 
                        <div class="textFlow" data-default="GL Account">GL Account</div>
                        <button class="close" type="button" ><i class="fa fa-close" ></i></button>
                        <button class="drops" type="button" ><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" id="f_gl_acc_id" class="ajaxselectBox_Value field-check" value="" >
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
        <div class="form-width-3">
            <div class="form-group field-money f" >
                <label class="form-label">Debit</label>
                <input type="text" value="0.00" id="f_debit" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-3">
            <div class="form-group field-money" >
                <label class="form-label">Credit</label>
                <input type="text" value="0.00" id="f_credit" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group ">
                <label class="form-label">Narration</label>
                <textarea rows="3" class="form_control field-check" id="f_narration" ></textarea>
                <p class="error-text" ></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group textRight">
                <button type="button" class="btn outline-secondary" id="journalentry_clear_btn">Clear</button>
                <button class="btn bg-primary" type="button" id="journalentry_add_submit">Add</button>
            </div>
        </div>
    </form>
</div>
<div class="alldiv widget_title">
    <h3 >Journal Summary</h3> 
    <?php
        echo form_open(url_to('erp.finance.journalentryadd'),array(
            "class"=>"flex",
            "id"=>"journalentry_batch_form"
        ));
    ?> 
    <div id="defaults_holder">
    </div>
    <div class="form-width-1">
    <div class="table-responsive" >
        <table class="table">
            <thead >
                <tr>
                    <th>GL Account</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Narrative</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="journal_entry_holder" >

            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th id="total_debit" >0.00</th>
                    <th id="total_credit" >0.00</th>
                    <td colspan="2" ></td>
                </tr>
            </tfoot>
        </table>
    </div>
    </div>
    <div class="form-width-1">
        <div class="form-group textRight">
            <button class="btn bg-primary" type="button" id="journalentry_batch_save" >Save Entries</button>
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

        /**
        Session Storage Block
         */
        let entry_counter=0;
        let date_to_process=document.getElementById("f_date_to_process");
        let journal_type=document.getElementById("f_journal_type");
        let defaults_holder=document.getElementById("defaults_holder");

        entry_counter=sessionStorage.getItem("key_entry_counter");
        if(entry_counter===null || entry_counter===undefined){
            entry_counter=0;
            sessionStorage.setItem("key_entry_counter",entry_counter);
        }else{
            entry_counter=parseInt(entry_counter);
        }

        let val=sessionStorage.getItem("key_date_to_process");
        if(val!==null && val!==undefined){
            date_to_process.value=val;
        }

        val=sessionStorage.getItem("key_journal_type");
        if(val!==null && val!==undefined){
            journal_type.querySelectorAll(".field-check").forEach((item)=>{
                if(item.getAttribute("value")==val){
                    item.checked=true;
                }
            });
        }

        date_to_process.onchange=(evt)=>{
            let value=evt.target.value;
            sessionStorage.setItem("key_date_to_process",value);
        }
        f_journal_type.querySelectorAll(".field-check").forEach((item)=>{
            item.onclick=(evt)=>{
                let value=evt.target.value;
                sessionStorage.setItem("key_journal_type",value);
            }
        });

        /********Session Storage Block Ends*************** */

        let f_debit=document.getElementById("f_debit");
        let f_credit=document.getElementById("f_credit");
        let f_gl_acc_id=document.getElementById("f_gl_acc_id");
        let f_narration=document.getElementById("f_narration");
        let journal_entry_holder=document.getElementById("journal_entry_holder");
        let total_credit=document.getElementById("total_credit");
        let total_debit=document.getElementById("total_debit");
        let journal_batch_form=document.getElementById("journalentry_batch_form");
        let ajaxselectbox=null;
        let max_limit_batch=10;
        let batch_counter=0;

        let dr_cr_balance=false;
        let alert=new ModalAlert();

        f_debit.onkeydown=(evt)=>{
            f_credit.value="0.00";
        }
        f_credit.onkeydown=(evt)=>{
            f_debit.value="0.00";
        }

        document.querySelectorAll(".ajaxselectBox").forEach((item)=>{
            ajaxselectbox=new AjaxSelectBox(item);
            ajaxselectbox.init();
            closer.register_shutdown(ajaxselectbox.shutdown,ajaxselectbox.get_container());
        });

        let form=document.getElementById("journalentry_add_form");
        let validator=new FormValidate(form);

        let lock=false;
        document.getElementById("journalentry_add_submit").onclick=function(evt){
            if(!lock){
                lock=true;
                validator.validate(
                (params)=>{
                    if(batch_counter<max_limit_batch){
                        let entry={};
                        entry['gl_account_name']=f_gl_acc_id.previousElementSibling.previousElementSibling.previousElementSibling.textContent;
                        entry['gl_account_id']=f_gl_acc_id.value;
                        entry['debit']=f_debit.value;
                        entry['credit']=f_credit.value;
                        entry['narration']=f_narration.value;
                        ++entry_counter;
                        
                        sessionStorage.setItem("key_entry_counter",entry_counter);
                        sessionStorage.setItem("key_journal_entry_"+entry_counter,JSON.stringify(entry));
                        append_journal_summary(entry,entry_counter);
                        clear_journal_entry();
                    }else{
                        alert.invoke_alert("At a time only "+max_limit_batch+" could be processed","error");
                    }
                    lock=false;
                },
                (params)=>{
                    lock=false;
                },
                {});
            }
        }

        document.getElementById("journalentry_batch_save").onclick=(evt)=>{
            if(inject_defaults_as_hidden()){
                calculate_total_dr_cr();
                if(dr_cr_balance){
                    sessionStorage.clear();
                    journal_batch_form.submit();
                }else{
                    alert.invoke_alert("Debit and Credit Balance should be equal","error");
                }
            }
        }

        function render_journal_summary(){
            let counter=entry_counter;
            for(let i=1;i<=counter;i++){
                let entry=sessionStorage.getItem("key_journal_entry_"+i);
                if(entry!==null && entry!==undefined){
                    append_journal_summary(JSON.parse(entry),i);
                }
            }
            tbl_entry_remove();
        }

        function append_journal_summary(entry,counter){
            let tr=document.createElement("tr");
            let td1=document.createElement("td");
            td1.innerHTML=`<input type="hidden" name="gl_acc_id_`+counter+`" value="`+entry['gl_account_id']+`" />`+entry['gl_account_name'];
            tr.append(td1);
            let td2=document.createElement("td");
            td2.innerHTML=`<input type="hidden" name="debit_`+counter+`" value="`+entry['debit']+`" /><span class="tbl-debit" >`+entry['debit']+`</span>`;
            tr.append(td2);
            let td3=document.createElement("td");
            td3.innerHTML=`<input type="hidden" name="credit_`+counter+`" value="`+entry['credit']+`" /><span class="tbl-credit" >`+entry['credit']+`</span>`;
            tr.append(td3);
            let td4=document.createElement("td");
            td4.innerHTML=`<input type="hidden" name="narration_`+counter+`" value="`+entry['narration']+`" />`+entry['narration'];
            tr.append(td4);
            let td5=document.createElement("td");
            td5.innerHTML=`<button type="button" class="btn bg-danger tbl-entry-remove" data-counter=`+counter+` ><i class="fa fa-trash"></i></button>`;
            tr.append(td5);
            journal_entry_holder.append(tr);
            ++batch_counter;
            calculate_total_dr_cr();
        }

        function calculate_total_dr_cr(){
            let debit=0.0;
            let credit=0.0;
            let tbl_debit=journal_entry_holder.querySelectorAll(".tbl-debit");
            let tbl_credit=journal_entry_holder.querySelectorAll(".tbl-credit");
            for(let i=0;i<tbl_debit.length;i++){
                let dr=parseFloat(tbl_debit[i].textContent);
                debit+=dr;
            }
            for(let i=0;i<tbl_credit.length;i++){
                let cr=parseFloat(tbl_credit[i].textContent);
                credit+=cr;
            }
            credit=credit.toFixed(2);
            debit=debit.toFixed(2);
            if(credit==debit){
                dr_cr_balance=true;
            }else{
                dr_cr_balance=false;
            }
            total_credit.textContent=credit;
            total_debit.textContent=debit;
        }

        function tbl_entry_remove(){
            journal_entry_holder.onclick=(evt)=>{
                let target=evt.target;
                journal_entry_holder.querySelectorAll(".tbl-entry-remove").forEach((item)=>{
                    if(item.contains(target)){
                        let counter=item.getAttribute("data-counter");
                        sessionStorage.removeItem("key_journal_entry_"+counter);
                        item.parentElement.parentElement.remove();
                        --batch_counter;
                        calculate_total_dr_cr();
                    }
                })
            }
        }

        function inject_defaults_as_hidden(){
            let injected=true;
            let input1_val=date_to_process.value;
            let inject_content=``;
            if(input1_val!==null && input1_val!==undefined && input1_val!==""){
                inject_content+=`<input type="hidden" name="transaction_date" value="`+input1_val+`" /> `;
            }else{
                injected=false;
                alert.invoke_alert("Date to process can't be empty","error");
                return injected;
            }
            let radios=journal_type.querySelectorAll(".field-check");
            let input2_val=null;
            for(let i=0;i<radios.length;i++){
                if(radios[i].checked){
                    input2_val=radios[i].value;
                    break;
                }
            }
            if(input2_val!==null && input2_val!==undefined && input2_val!==""){
                inject_content+=`<input type="hidden" name="journal_type" value="`+input2_val+`" /> `;
            }else{
                injected=false;
                alert.invoke_alert("Specify journal type","error");
                return injected;
            }
            defaults_holder.innerHTML=inject_content;
            return injected;
        }

        document.getElementById("journalentry_clear_btn").onclick=(evt)=>{
            clear_journal_entry();
        }

        function clear_journal_entry(){
            f_debit.value="0.00";
            f_credit.value="0.00";
            f_gl_acc_id.value="";
            f_narration.value="";
            ajaxselectbox.construct();
        }

        render_journal_summary();

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