<style>
    .form-control-text-area {
        display: inline-block;
        width: 100%;
        padding: 6px 12px;
        height: 120px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>
<?//= var_dump($exist_data) ?>
<div class="alldiv flex widget_title">
    <h3>Edit Workstation</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.mrp.worstation') ?>" class="btn bg-success"><i class="fa fa-reply"></i>
            Back </a>
    </div>
</div>
<div class="alldiv">
    <form action="<?= url_to('erp.mrp.workstationedit.view',$exist_data["id"]) ?>" method="POST" class="flex" id="workstation_edit_form">
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Workstation Name</label>
                <input type="text" class="form_control field-check" name="name" value="<?= $exist_data["name"] ?? "" ?>">
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label" id="product_label">Workstation Type</label>
                <div class="ajaxselectBox poR" data-ajax-url="<?= url_to('erp.mrp.ajax.workstation.types.list'); ?>">
                    <div class="ajaxselectBoxBtn flex">
                        <div class="textFlow" data-default="select product"><?= isset($exist_data["workstationtype_name"]) ? $exist_data["workstationtype_name"] : "select workstation Type" ?></div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="ajaxselectBox_Value field-check" name="workstation_id" id="workstation_id"
                            value="<?= $exist_data["workstationtype_id"] ?? "" ?>">
                    </div>
                    <div class="ajaxselectBox_Container alldiv">
                        <input type="text" class="ajaxselectBox_Search form_control" />
                        <ul role="listbox" class="listboxmaterials">
                        </ul>
                    </div>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Warehouse</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select warehouse">select warehouse</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" name="warehouse_id" value="<?= $exist_data["warehose_id"] ?? "" ?>">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($warehouses as $row) {
                            ?>
                            <li role="option" data-value="<?php echo $row['warehouse_id']; ?>"><?php echo $row['name']; ?>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Status</label>
                <div class="selectBox poR">
                    <div class="selectBoxBtn flex">
                        <div class="textFlow" data-default="select warehouse">select status</div>
                        <button class="close" type="button"><i class="fa fa-close"></i></button>
                        <button class="drops" type="button"><i class="fa fa-caret-down"></i></button>
                        <input type="hidden" class="selectBox_Value field-check" name="status" value="<?= $exist_data["status"] ?? "" ?>">
                    </div>
                    <ul role="listbox" class="selectBox_Container alldiv">
                        <?php
                        foreach ($status as $key => $value) {
                            ?>
                            <li role="option" data-value="<?php echo $key; ?>"><?php echo $value; ?></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <p class="error-text"></p>
            </div>
        </div>
        <hr>
        <div class="form-width-1 mt-2">
            <h2 class="form-label">Working Hours</h2>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Start Time</label>
                <input type="time" name="work_start_date" id="work_start_date" class="form_control field-check" value="<?= $exist_data["work_hour_start"] ?>" />
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">End Time</label>
                <input type="time" name="work_end_date" id="work_end_date" class="form_control field-check" value="<?= $exist_data["work_hour_end"] ?>" />
                <p class="error-text"></p>
            </div>
        </div>
        <hr>
        <div class="form-width-1 mt-2">
            <h2 class="form-label">Operting Cost</h2>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Electricity Cost (per hour)</label>
                <input type="number" class="form_control mt-1 field-check" id="e_cost" name="e_cost" step="1"
                    value="<?= $exist_data["electricity_cost"] ?? 0 ?>" min="1">
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Rent Cost (per hour)</label>
                <input type="number" class="form_control mt-1 field-check" id="r_cost" name="r_cost" step="1"
                    value="<?= $exist_data["rent_cost"] ?? 0 ?>" min="1">
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Consumable Cost (per hour)</label>
                <input type="number" class="form_control mt-1 field-check" id="cc_cost" name="cc_cost" step="1"
                    value="<?= $exist_data["consumable_cost"] ?? 0 ?>" min="1">
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-2">
            <div class="form-group field-required">
                <label class="form-label">Wages (wages per hour)</label>
                <input type="number" class="form_control mt-1 field-check" id="wages_cost" name="wages_cost" step="1"
                    value="<?= $exist_data["wages_cost"] ?? 0 ?>" min="1">
                <p class="error-text"></p>
            </div>
        </div>
        <div class="form-width-1">
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea type="date" name="description" id="description" class="form-control-text-area" ><?= $exist_data["description"] ?? "" ?></textarea>
                <p class="error-text"></p>
            </div>
        </div>
        <? //= var_dump($status) ?>
        <div class="form-width-1 mt-5">
            <div class="form-group textRight">
                <a href="<?= url_to('erp.mrp.worstation') ?>" class="btn outline-secondary">Cancel</a>
                <button class="btn bg-primary" type="button" id="workstation_edit_submit">Save</button>
            </div>
        </div>
    </form>
</div>








<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script type="text/javascript">

    // const today = new Date();
    // document.getElementById("mfg_date").value = today.toISOString().split('T')[0];

    $(document).ready(function () {
        let alert = new ModalAlert();
        let closer = new WindowCloser();
        closer.init();
        let base_url = "<?php echo base_url(); ?>";


        let ajaxselectbox_elem = document.querySelector(".ajaxselectBox.poR");
        let ajaxselectbox = new AjaxSelectBox(ajaxselectbox_elem);
        let default_ajax_url = ajaxselectbox_elem.getAttribute("data-ajax-url");
        ajaxselectbox.init();
        closer.register_shutdown(ajaxselectbox.shutdown, ajaxselectbox.get_container());
        // ajaxselectbox.add_listener((params) => {
        //     // let product_id = params.value;
        //     console.log(params);
        // })
        // let selectbox_elem = document.querySelector(".selectBox.poR");
        // let selectbox = new SelectBox(selectbox_elem);
        // selectbox.init();
        // selectbox.add_listener((params) => {
        //     let url = params['value'];
        //     if (url !== null && url !== undefined && url !== "") {
        //         ajaxselectbox_elem.setAttribute("data-ajax-url", base_url + product_links[url]);
        //     } else {
        //         ajaxselectbox_elem.setAttribute("data-ajax-url", default_ajax_url);
        //     }
        // }, {});
        // closer.register_shutdown(selectbox.shutdown, selectbox.get_container());

        document.querySelectorAll(".selectBox.poR").forEach((item) => {
            let _selectbox = new SelectBox(item);
            _selectbox.init();
            closer.register_shutdown(_selectbox.shutdown, _selectbox.get_container());
        });

        // document.querySelectorAll(".selectBox:not(.poR)").forEach((item) => {
        //     let _ajaxselectbox = new AjaxSelectBox(item);
        //     _ajaxselectbox.init();
        //     closer.register_shutdown(_ajaxselectbox.shutdown, _ajaxselectbox.get_container());
        // });
    })


    let form = document.getElementById("workstation_edit_form");
    let validator = new FormValidate(form);
    let lock = false;
    document.getElementById("workstation_edit_submit").onclick = function (evt) {
        if (!lock) {
            lock = true;
            validator.validate(
                (params) => {
                    form.submit();
                    lock = false;
                },
                (params) => {
                    lock = false;
                }, {});
        }
    }

    $(".ajaxselectBox_Search.form_control").on("change", function (e) {
        setTimeout(()=>{
            let workstation_id = $("#workstation_id").val();

            if(workstation_id != 0 || workstation_id != ""){
                get_worstation_details_by_id(workstation_id);
            }
        },1000)
    });

    function get_worstation_details_by_id(workstationid){
        $.ajax({
            url : "<?= url_to("erp.mrp.get.worstationdetails")?>",
            type : "POST",
            data : {
                id : workstationid
            },
            success : function(response){
                console.log(response);
                if(response.success){
                    let wt_data = response.data;
                    $("#e_cost").val(wt_data.electricity_cost);
                    $("#r_cost").val(wt_data.rent_cost);
                    $("#cc_cost").val(wt_data.consumable_cost);
                    $("#wages_cost").val(wt_data.wages_cost);
                }else{
                    if(!response.errorcode){
                        alert.invoke_alert("No Workstation type were Found!","error");
                    }
                }
            },error :function(xhr,status,error){
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        })
    }


    <?php
    if (session()->getFlashdata("op_success")) { ?>
        // let alerts = new ModalAlert();
        alerts.invoke_alert("<?= session()->getFlashdata('op_success'); ?>", "success");
        <?php
    } else if (session()->getFlashdata("op_error")) { ?>
            // let alerts = new ModalAlert();
            alerts.invoke_alert("<?= session()->getFlashdata('op_error'); ?>", "error");
        <?php
    }
    ?>
</script>
</body>

</html>