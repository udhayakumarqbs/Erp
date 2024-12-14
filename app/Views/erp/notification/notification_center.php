<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/custom-style.css'; ?>">
<div class="alldiv flex widget_title">
    <h3>Notification Center</h3>
</div>


<style>
    .notification_box {
        margin-bottom: 18px;
        padding-bottom: 18px;
        border-bottom: 1px solid #2196f3a1;
    }

    .notification_box:last-child {
        border-bottom: 0;
    }

    .notification_box .notification_first_row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0px;
        border-bottom: 0.5px solid #0000001c;
    }

    .notification_box .first_name {
        padding: 16px;
        height: 20px;
        width: 20px;
        border-radius: 50%;
        background-color: black;
        color: white;
        font-size: 18px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 10px;
    }

    .notification_box .sender_name {
        font-size: 20px;
        color: #393939;
        font-weight: bold;
    }

    .notification_box .sent_date {
        font-size: 16px;
        color: black;
    }

    .notification_box .notify_title {
        font-size: 20px;
        color: black;
    }

    .notification_box .link {
        color: var(--primary);
    }
</style>

<div class="alldiv">
    <div class="mx-3 mx-md-5 mt-5">
        <?php if (!empty($notifications)) { ?>
            <?php foreach ($notifications as $notification) { ?>
                <div class="notification_box">
                    <div class="notification_first_row">
                        <div class="d-flex justify-content-start align-items-center">
                            <p class="first_name"><?= substr($notification["name"],0,1); ?></p>
                            <p class="sender_name"><?= $notification["name"] . " " . $notification["last_name"]; ?></p>
                        </div>
                        <p class="sent_date"><?php echo substr($notification["notify_at"], 0, 10) ?></p>
                    </div>
                    <div class="py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="notify_title mb-1"><?= $notification["title"]; ?></p>
                            <p><?= $notification["notify_text"]; ?></p>
                        </div>

                        <?php if (!empty($notification["related_base_url"])) { ?>
                            <a href="<?= url_to($notification["related_base_url"], $notification["related_id"]) ?>" class="link">Go to <?= $notification["related_to"]; ?></a>
                        <?php } ?>
                    </div>
                    <a href="#" class="text-primary">Dismiss</a>
                </div>
            <?php } ?>
        <?php } ?>

    </div>

</div>

<!--SCRIPT WORKS-->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script type="text/javascript">
    <?php
    if (session()->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo $this->session->flashdata('op_success'); ?>", "success");
        <?php
    } else if (session()->getFlashdata("op_error")) { ?>
            let alert = new ModalAlert();
            alert.invoke_alert("<?php echo $this->session->flashdata('op_error'); ?>", "error");
        <?php
    }
    ?>
</script>
</body>

</html>