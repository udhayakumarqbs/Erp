<div class="alldiv flex widget_title">
    <h3>Knowledge Base Article</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.Knowledgebase'); ?>" class="btn bg-success"><i class="fa fa-reply"></i>
            Knowledge Base </a>
    </div>
</div>
<style>
    .form-control {
        display: block;
        width: 100%;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form_control_group {
        display: block;
        width: 30%;
        height: 37px;
        padding: 4px 15px;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .alertgroup_1 {
        display: none;
    }

    .alertgroup_2 {
        display: none;
    }

    .edit::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        z-index: 2;
    }

    .modal.fade {
        position: fixed;
        top: 0;
        /**
     * Summary of Users_model
     * @var 
     */
        background-color: rgba(0, 0, 0, 0.3);
        width: 100%;
        /**
     * Summary of Email_templates_model
     * @var 
     */
        z-index: 1000;
    }

    .modal-dialog {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        max-width: 500px;
        min-width: 280px;
        padding: 16px;
        z-index: 1500;
        border: 1px solid white;
        border-radius: 6px;
        background: white;
    }

    .close {
        border: none;
        background: transparent;
    }

    hr {
        border-color: grey;
    }

    .btn.bg-secondary {
        padding: 8px 16px;
    }

    .dropdown_container {
        position: absolute;
        max-width: 280px;
        min-width: 140px;
        z-index: 999;
        top: 100%;
        right: 72%;
        display: none;
    }

    .group {
        padding: 10px;
        border-radius: 8px;
        width: 100%;
        min-height: 60px;
        border: 1px solid lightgrey;
        box-shadow: 1px 1px 14px 2px lightgray;
    }

    .range {
        position: relative;
        width: 95%;
        height: 20px;
        background-color: #333;
        margin-left: 10px !important;
        border-radius: 12px;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    .range-child-yes,
    .range-child-no {
        position: absolute;
        top: 0;
        left: 0;
        /* padding-left: 5px; */
        border-radius: 12px;
        color: white;
        text-align: center;
    }

    .range-child-yes {
        background-color: #1d9d34;
    }

    .range-child-no {
        background-color: #d9534f;
    }

    .text-bold {
        font-size: 15px;
        font-weight: 600;
        margin-left: 10px !important;
    }

    .internal-article {
        border: 1px solid lightgray;
        border-radius: 8px;
        padding: 3px;
        margin-left: 6px;
    }

    .when_no_data {
        width: 90%;
        margin: 0px auto;
        padding: 10px;
        text-align: center;
    }

    .when_no_data p {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        font-size: 14px;
        font-weight: 600;
    }
</style>
<div class="alldiv p-2">
    <div class="group-header p-3">
        <h3>Choose Group</h3>
        <select class="form_control_group groupid" id="subject_id" onchange="get_the_kb(this)"
            aria-label="Default select example" name="dropdown_group">
            <?php $count = 0; ?>
            <?php foreach ($groups as $group) { ?>
                <option value="<?= $group["group_id"] ?>" <?= $count == 0 ? 'selected' : ''; ?>><?= $group["group_name"] ?>
                </option>

                <?php
                $count++;
            } ?>
        </select>
    </div>
    <div class="datatable p-3" id="article-table">
        <!-- group  div-->
        <!-- <div class="group mt-2">
            <div class="d-flex align-item-center justify-content-between">
                <div>
                    <span class="text-primary text-bold">knowledge base 1 ( Total: 1 )</span>
                    <span class="internal-article">Internal Article</span>
                </div>
                <span>Yes: 1</span>
            </div>
            <div class="range mt-2">
                <div class="range-child-yes" style="height:20px;width:20%">20%</div>
            </div>
            <div class="d-flex align-item-center justify-content-between">
                <span class="text-primary text-bold"></span>
                <span>No: 1</span>
            </div>
            <div class="range mt-2">
                <div class="range-child-no" style="height:20px;width:20%">20%</div>
            </div>
        </div> -->

    </div>
    <!-- -->
</div>
</div>
</div>




<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> -->

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>





<script>
    // data table handler
    // let closer = new WindowCloser();
    // closer.init();

    // let datatable_elem = document.querySelector(".datatable");

    // let rows_per_page = new SelectBox(datatable_elem.querySelector(".tableFooter .selectBox"));
    // rows_per_page.init();
    // closer.register_shutdown(rows_per_page.shutdown, rows_per_page.get_container());

    // let bulkaction = new SelectBox(datatable_elem.querySelector(".tableHeader .bulkaction"));
    // bulkaction.init();
    // closer.register_shutdown(bulkaction.shutdown, bulkaction.get_container());

    // let config = JSON.parse('<?php //$dt_config; ?>');
    // let datatable = new DataTable(datatable_elem, config);
    // datatable.init();

    // rows_per_page.add_listener(datatable.rows_per_page, {});

    // if (config['filters'] !== null && config['filters'] !== undefined) {
    //     let filter_1 = document.getElementById("lead_filter_1");
    //     let filter_2 = document.getElementById("lead_filter_2");

    //     let select_box_1 = new SelectBox(filter_1);
    //     select_box_1.init();
    //     select_box_1.add_listener(datatable.filter, {
    //         type: "select",
    //         column: config['filters'][0]
    //     });
    //     closer.register_shutdown(select_box_1.shutdown, select_box_1.get_container());

    //     let select_box_2 = new SelectBox(filter_2);
    //     select_box_2.init();
    //     select_box_2.add_listener(datatable.filter, {
    //         type: "select",
    //         column: config['filters'][1]
    //     });
    //     closer.register_shutdown(select_box_2.shutdown, select_box_2.get_container());
    // }
    // $(document).ready(function () {
    //     $('#knowledge_add_form').submit(function (event) {
    //         event.preventDefault();

    //         var subject = $('#group_name_id').val();
    //         var description = $("#group_description").val();
    //         var order = $("#group_order").val();
    //         order = Number(order) ? Number(order) : "String";
    //         if (!subject) {
    //             $('#group_name_id').addClass('subject');
    //             $('#alertgroup').removeClass('alertgroup_1');
    //         } else if (typeof order != "number") {
    //             $('#alertgroup_1').removeClass('alertgroup_2');
    //         } else {
    //             this.submit();
    //         }
    //     });
    // });

    function get_the_kb(current) {

        if (current.value > 0) {
            let group_id = current.value;
            $.ajax({
                url: "<?= url_to("erp.group.report.ajax") ?>",
                type: "post",
                data: { id: group_id },
                success: function (response) {
                    console.log('staffcount => ',response.staffcount)
                    console.log('clientcount => ',response.clientcount)
                    let kb_container = $("#article-table");
                    if (response.success) {
                        const knowledgeBaseHTML = '<div class="group mt-2">' +
                            '<div class="d-flex align-item-center justify-content-between">' +
                            '<div>' +
                            '<span class="text-primary text-bold">{title} ( Total: {total} )</span>' +
                            '<span class="internal-article {internal}">Internal Article</span>' +
                            '</div>' +
                            '<span>Yes: {count_yes}</span>' +
                            '</div>' +
                            '<div class="range mt-2">' +
                            '<div class="range-child-yes" style="height:20px;width:{pos_width}%">{pos_percentage}%</div>' +
                            '</div>' +
                            '<div class="d-flex align-item-center justify-content-between">' +
                            '<span class="text-primary text-bold"></span>' +
                            '<span>No: {count_no}</span>' +
                            '</div>' +
                            '<div class="range mt-2">' +
                            '<div class="range-child-no" style="height:20px;width:{neg_width}%;">{neg_percentage}%</div>' +
                            '</div>' +
                            '</div>';

                        const EmptyknowledgeBaseHTML = '<div class="group mt-2">' +
                            '<div class="d-flex align-item-center justify-content-between">' +
                            '<div>' +
                            '<span class="text-primary text-bold">{title} ( Total: 0 )</span>' +
                            '</div>' +
                            '</div>' +
                            '<p style="margin-left : 10px;"> No votes yet </p>' +
                            '</div>';

                        let knowledgeBasedata = response.kb;
                        let knowledgeBasefeedback = response.fb;
                        console.log(knowledgeBasedata);
                        console.log(knowledgeBasefeedback);
                        kb_container.empty();
                        if (knowledgeBasedata.length > 0) {
                            knowledgeBasedata.forEach(kb => {
                                let count = 0;
                                knowledgeBasefeedback.forEach(fb => {
                                    if (kb.article_id == fb.article_id) {
                                        count++;
                                        if (fb.positive > 0 || fb.negative > 0) {

                                            let copy = knowledgeBaseHTML;
                                            copy = copy.replace('{title}', kb.article_subject)
                                            
                                            let positive = 0;
                                            let negative= 0;
                                            let total_count =0;
                                            
                                            if (kb.Internal_article != 1) {
                                                total_count = response.clientcount + response.staffcount;
                                                copy = copy.replace('{internal}', 'd-none')
                                                positive = (fb.positive / total_count) * 100;
                                                negative = (fb.negative / total_count) * 100;
                                            }else{
                                                total_count = response.staffcount;
                                                positive = (fb.positive / total_count) * 100;
                                                negative = (fb.negative / total_count) * 100;
                                            }

                                            console.log(kb.article_subject);
                                            console.log("total_count = ",total_count);
                                            console.log("p-count ",fb.positive);
                                            console.log("n-count ",fb.negative);
                                            console.log("positive per",positive);
                                            console.log("negative per",negative);
                                            
                                            copy = copy.replace('{count_yes}', fb.positive)
                                            copy = copy.replace('{count_no}', fb.negative)
                                            copy = copy.replace('{total}', parseInt(fb.negative) + parseInt(fb.positive))

                                            //positive
                                            if (fb.positive > 0) {
                                                copy = copy.replace('{pos_percentage}', positive)
                                                copy = copy.replace('{pos_width}', positive)
                                            } else {
                                                copy = copy.replace('{pos_percentage}', 0)
                                                copy = copy.replace('{pos_width}', 2)
                                            }

                                            //negative
                                            if (fb.negative > 0) {
                                                copy = copy.replace('{neg_percentage}', negative)
                                                copy = copy.replace('{neg_width}', negative)
                                            } else {
                                                copy = copy.replace('{neg_percentage}', 0)
                                                copy = copy.replace('{neg_width}', 2)
                                            }
                                            kb_container.append(copy);
                                        } else {
                                            let copyempty = EmptyknowledgeBaseHTML;
                                            copyempty = copyempty.replace('{title}', kb.article_subject);
                                            kb_container.append(copy);
                                        }

                                    }
                                })

                                if (count == 0) {
                                    // kb_container.empty();
                                    console.log("hi");
                                    let copyempty1 = EmptyknowledgeBaseHTML;
                                            copyempty1 = copyempty1.replace('{title}', kb.article_subject);
                                    kb_container.append(copyempty1);
                                }
                            });
                        }
                    } else {
                        console.log("else")
                        kb_container.empty();
                        let empty_article = '<div class="when_no_data">' +
                            '<p><i class="fa-solid fa-rotate"></i> There is no Article in this Group</p>' +
                            '</div>';
                        kb_container.append(empty_article);

                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            })
        }
    }

    $(document).ready(function () {
        $("#subject_id").trigger("change");
    })


    <?php if (session()->getFlashdata('op_success')) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getFlashdata('op_success') ?>", "success");
    <?php } elseif (session()->getFlashdata('op_error')) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?php echo session()->getFlashdata('op_error') ?>", "error");
    <?php } ?>
</script>

</body>

</html>