<div class="alldiv flex widget_title">
    <h3>Update Segment</h3>
    <div class="title_right">
        <a href="<?php echo base_url().'erp/supplier/segments' ;?>" class="btn bg-success" ><i class="fa fa-reply" ></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <?php
        echo form_open(base_url().'erp/supplier/segments-edit/'.$segment_id,array(
            "class"=>"flex",
            "id"=>"segment_edit_form"
        ));
    ?>
        <div class="form-width-1">
        <ol>
            <li><b>Position</b> matters in Segment Values</li>
            <li>If segment value used in suppliers or selection rules then you can't delete that value</li>
            <li><b>Duplicate segment key</b> won't allowed.</li>
        </ol>
        </div>
        <div class="form-width-1">
            <div class="form-group field-ajax " data-ajax-url="<?php echo base_url().'erp/supplier/segment-key-unique?id='.$segment_id.'&' ;?>" >
                <label class="form-label">Segment Key</label>
                <input type="text" name="segment_key" value="<?php echo $segment->segment_key; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <?php
            $segment_values=json_decode($segment->segment_value,true);
            $segment_value_index=count($segment_values);
            $segment_value_counter=count($segment_values)-1;
        ?>
        <div class="form-width-1"  >
            <div class="form-group field-required ">
                <label class="form-label">Segment Values</label>
                <input type="text" name="segment_value[0]" value="<?php echo $segment_values[1]; ?>" class="form_control field-check" />
                <p class="error-text" ></p>
            </div>
        </div>
        <?php
            for ($i=2;$i<count($segment_values);$i++) {
                ?>
            <div class="form-width-1"  >
                <div class="form-group field-required poR ">
                    <input type="text" name="segment_value[<?php echo $i; ?>]" value="<?php echo $segment_values[$i]; ?>" class="form_control field-check " />
                    <button class="append-icon-btn bg-danger" type="button"><i class="fa fa-trash" ></i></button>
                    <p class="error-text" ></p>
                </div>
            </div>
        <?php
            }
        ?>
        <div class="form-width-1" id="segment_values_prev_sibling" >
            <div class="form-group textRight">
                <button class="btn bg-success" type="button" id="segment_add_btn">Add Value</button>
            </div>
        </div>

        <div class="form-width-1">
            <div class="form-group textRight">
                <a href="<?php echo base_url().'erp/supplier/segments' ;?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="segment_edit_submit">Save</button>
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

        let segment_edit_form=document.getElementById("segment_edit_form");
        let prev_sibling=document.getElementById("segment_values_prev_sibling");
        let segment_value_index=parseInt("<?php echo $segment_value_index; ?>");
        let max_segment_value=5;
        let segment_value_counter=parseInt("<?php echo $segment_value_counter; ?>");
        document.getElementById("segment_add_btn").onclick=(evt)=>{
            if(segment_value_counter < max_segment_value ){
                let segment_value_html=`
                    <div class="form-group field-required poR ">
                        <input type="text" name="segment_value[`+segment_value_index+`]" class="form_control field-check " />
                        <button class="append-icon-btn bg-danger" type="button"><i class="fa fa-trash" ></i></button>
                        <p class="error-text" ></p>
                    </div>`;
                let div=document.createElement("div");
                div.classList.add("form-width-1");
                div.innerHTML=segment_value_html;
                segment_edit_form.insertBefore(div,prev_sibling);
                segment_value_index++;
                segment_value_counter++;
            }
        };

        segment_edit_form.onclick=(evt)=>{
            let target=evt.target;
            segment_edit_form.querySelectorAll(".append-icon-btn").forEach((item)=>{
                if(item.contains(target)){
                    item.parentElement.parentElement.remove();
                    segment_value_counter--;
                }
            });
        }
        
        let validator=new FormValidate(segment_edit_form);
        let lock=false;
        document.getElementById("segment_edit_submit").onclick=function(evt){
            if(!lock){
                lock=true;
                validator.validate(
                (params)=>{
                    segment_edit_form.submit();
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