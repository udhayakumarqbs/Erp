<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">
<div class="alldiv flex widget_title">
    <h3>Database Backup</h3>


    <div class="title_right d-flex justify-content-between justify-content-md-end align-items-center mt-4 mt-md-0 ">
        <a href="" class="btn bg-success"><i class="fa-solid fa-sync"></i> Auto backup </a>
        <a class="btn bg-primary " href="<?= url_to("erp.setting.databasebackupaction"); ?>"><i class="fa-solid fa-database"></i> Create Database Backup</a>
    </div>
</div>

<div class="alldiv p-3">
    <div class="mbot25 mb-4" style="border: 1px solid grey; border-radius: 5px; background-color: rgb(239 246 255);">
        <h4 class=" p-2">Note: Due to the limited execution time and memory available to PHP, backing up very large databases may not be possible. If your database is very large you might need to backup directly from your SQL server via the command line, or have your server admin do it for you if you do not have root privileges.</h4>
    </div>

    <style>
        .w-100-imporant {
            width: 100% !important;
        }
        .dbname {
            color: #4E90E6;
        }
        .down-icon{
            display: inline;
            top: 3px;
            left: 0;
        }
    </style>
    <div class="table_responsive mt-3">
        <table class="table w-100-imporant" data-order-col="2" data-order-type="desc" id="dt-table">
            <thead class="thead">
                <th><?php echo 'Backup' ?></th>
                <th><?php echo 'Backup size' ?></th>
                <th><?php echo 'Date' ?></th>
                <th><?php echo 'Options' ?></th>
            </thead>
            <tbody class="table-paint-area">
                <?php $backups = list_files(get_attachment_path("database")); ?>
                <?php foreach ($backups as $backup) {
                    $fullPath              = get_attachment_path("database") . $backup;
                    $backupNameNoExtension = preg_replace('/\\.[^.\\s]{3,4}$/', '', $backup); ?>
                    <tr class="mt-5 ">
                        <td>
                            <a href="<?php echo url_to('erp.setting.databasebackupdownload', $backupNameNoExtension); ?>" class="dbname">
                            <i class="down-icon fa-solid fa-cloud-arrow-down"></i>    
                            <?php echo $backup; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo byteToSize($fullPath); ?>
                        </td>
                        <td data-order="<?php echo date('Y-m-d H:m:s', filectime($fullPath)); ?>">
                            <?php echo date('M dS, Y, g:i a', filectime($fullPath)); ?>
                        </td>
                        <td>
                            <a href="<?php echo url_to('erp.setting.databasebackupdelete', $backupNameNoExtension); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                <i class="fa-regular fa-trash-can fa-lg del-confirm"></i>
                            </a>
                        </td>
                    </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>



<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src='https://cdn.datatables.net/2.0.5/js/dataTables.min.js'></script>

<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script>
    $("#dt-table").DataTable();
    // let closer = new WindowCloser();
    // closer.init();


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