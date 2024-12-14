<div class="alldiv flex widget_title">
    <h3>View Payroll</h3>
    <div class="title_right">
        <a href="<?php echo base_url() . 'erp/hr/payrolls'; ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="emp_info">Info</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="emp_info">
            <div class="flex">
                <div class="form-width-1">
                    <h2>Payroll Info</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>NAME</th>
                                    <td><?php echo $payroll->name; ?></td>
                                </tr>
                                <tr>
                                    <th>PAYMENT DATE </th>
                                    <td><?php echo $payroll->payment_date; ?></td>
                                </tr>
                                <tr>
                                    <th>FROM</th>
                                    <td><?php echo $payroll->payment_from; ?></td>
                                </tr>
                                <tr>
                                    <th>TO</th>
                                    <td><?php echo $payroll->payment_to; ?></td>
                                </tr>
                                <tr>
                                    <?php
                                    $color = $payroll->processed == 1 ? "st st_success" : "st st_dark";
                                    ?>
                                    <th>PROCESSED</th>
                                    <td><span class="br20 <?= $color ?>"><?php echo $payroll->processed == 1 ? "Yes" : "No" ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
</body>

</html>