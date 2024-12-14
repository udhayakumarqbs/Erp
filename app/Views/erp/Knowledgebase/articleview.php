<div class="alldiv flex widget_title">
    <h3>Internal Article</h3>
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

    .article_container {
        width: 100%;
        padding: 15px;
        box-shadow: 1px 1px 14px 2px lightgray;
        border: 1px solid lightgray;
        border-radius: 12px;
    }

    .description {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        font-size: 15px;
        font-weight: 500;
        text-align: justify;
        padding: 10px 0px;
    }

    .foot-container p {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        font-size: 15px;
        font-weight: 600;
        text-align: justify;
        padding: 10px 0px;
    }

    .voted-container {
        padding: 7px;
        border: 1px solid green;
        border-radius: 8px;
        width: 79px;
        color: black;
        background: #1aff1a1f;
    }
</style>
<div class="alldiv">
    <?php //var_dump($data[0]) ?>
    <div class="article_container">
        <h1 style="font-size: 23px;"><?= $data[0]['article_subject'] ?></h1>
        <hr>
        <div class="description"><?= $data[0]['article_description'] ?></div>
        <hr>
        <div class="foot-container p-2">
            <?php if (!$data[0]['is_voted']) { ?>
                <p>Did you find this article useful?</p>
            <?php } else { ?>
                <p>Thanks for Your Feedback!</p>
            <?php } ?>
            <div>
                <?php if (!$data[0]['is_voted']) { ?>
                    <button type="button" class="btn bg-success" data-type="positive">Yes</button>
                    <button type="button" class="btn bg-danger" data-type="negative">No</button>
                <?php } else { ?>
                    <div class="voted-container">
                        <i class="fa-solid fa-circle-check" style="color: #177c03;"></i> Voted
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> -->

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>





<script>

    $(".btn").on("click", (e) => {
        let data = $(e.target).data("type")
        $(".btn").prop("disabled", true);
        if (data) {
            $.ajax({
                url: "<?= url_to("erp.knowledgebase.internelarticle.submit") ?>",
                type: "post",
                data: {
                    type: data,
                    id: <?= $data[0]['article_id'] ?>
                },
                success: function (response) {
                    console.log(response);
                    // $(".btn").prop("disabled", true);
                    var alert_content = new ModalAlert();
                    if (response.success) {
                        alert_content.invoke_alert(response.message, "success");
                        setTimeout(()=>{
                            location.reload();
                        },1000)
                    } else {
                        alert_content.invoke_alert(response.message, "error");
                        setTimeout(()=>{
                            location.reload();
                        },1000)
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr)
                    console.log(status)
                    console.log(error)
                }
            })
        }
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