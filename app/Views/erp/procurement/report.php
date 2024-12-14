<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="alldiv flex widget_title">
    <h3>Procurement Report</h3>
</div>
<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="rfq_info">Simple View</a></li>
        <li><a type="button" class="tab_nav_item" data-src="rfq_attachment">Chart view</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="rfq_info">
            <div class="flex">
                <div class="mx-1">
                    <h2>Requsition</h2>
                </div>
                <?php
                $total = $total != 0 ? $total : 1;
                $rfqtotal = $rfqtotal != 0 ? $rfqtotal : 1;
                ?>
                <div class="mx-2 w-100">
                    <div class='mt-3'>
                        <?= 'Total Requsition Count ' . $total; ?>
                    </div>
                    <div class='mt-3'>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Pending</p>
                                    <p><?= $status0 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-secondary rounded-lg" style="width: <?= ($status0 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Processing</p>
                                    <p><?= $status1 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-warning rounded-lg" style="width: <?= ($status1 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Approved</p>
                                    <p><?= $status2 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-success rounded-lg" style="width: <?= ($status2 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Rejected</p>
                                    <p><?= $status3 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-danger rounded-lg" style="width: <?= ($status3 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <h2 class="my-4">Request for proposal (RFQ)</h2>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Created</p>
                                    <p><?= $rfqstatus0 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-secondary rounded-lg" style="width: <?= ($rfqstatus0 / $rfqtotal) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Sending</p>
                                    <p><?= $rfqstatus1 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-warning rounded-lg" style="width: <?= ($rfqstatus1 / $rfqtotal) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition sent</p>
                                    <p><?= $rfqstatus2 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-primary rounded-lg" style="width: <?= ($rfqstatus2 / $rfqtotal) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Partial Response</p>
                                    <p><?= $rfqstatus3 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-warning rounded-lg" style="width: <?= ($rfqstatus3 / $rfqtotal) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Full Response</p>
                                    <p><?= $rfqstatus4 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-success rounded-lg" style="width: <?= ($rfqstatus4 / $rfqtotal) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <h2 class="my-4">Orders</h2>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Order Created</p>
                                    <p><?= $status0 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-danger rounded-lg" style="width: <?= ($status0 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Order Sent</p>
                                    <p><?= $status1 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-danger rounded-lg" style="width: <?= ($status1 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Order Approved</p>
                                    <p><?= $status2 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-success rounded-lg" style="width: <?= ($status2 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Order Cancelled</p>
                                    <p><?= $status3 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-danger rounded-lg" style="width: <?= ($status3 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <h2 class="my-4">Invoice</h2>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Pending</p>
                                    <p><?= $status0 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-danger rounded-lg" style="width: <?= ($status0 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Processing</p>
                                    <p><?= $status1 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-danger rounded-lg" style="width: <?= ($status1 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Approved</p>
                                    <p><?= $status2 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-success rounded-lg" style="width: <?= ($status2 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Rejected</p>
                                    <p><?= $status3 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-danger rounded-lg" style="width: <?= ($status3 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <h2 class="my-4">Returns</h2>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Pending</p>
                                    <p><?= $status0 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-danger rounded-lg" style="width: <?= ($status0 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Processing</p>
                                    <p><?= $status1 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-danger rounded-lg" style="width: <?= ($status1 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Approved</p>
                                    <p><?= $status2 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-success rounded-lg" style="width: <?= ($status2 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                                <div class="d-flex justify-content-between my-2">
                                    <p>Requsition Rejected</p>
                                    <p><?= $status3 ?></p>
                                </div>
                                <div class="bg-light rounded-lg">
                                    <i class="bg-danger rounded-lg" style="width: <?= ($status3 / $total) * 100 ?>%;height:20px;display:block;"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="rfq_attachment">
            <div class="flex">
                <div class="form-width-1">
                    <table id="overView" class="display text-center">
                        <thead>
                            <tr>
                                <th>Order Code</th>
                                <th>Amount</th>
                                <th>Supplier</th>
                                <th>GRN Status</th>
                                <th>Invoice Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoicData as $row) : ?>
                                <tr>
                                    <td><?= $row['order_code'] ?></td>
                                    <td><?= $row['amount'] ?></td>
                                    <td><?= $row['supplier'] ?></td>
                                    <td><?= $row['grn_status'] ?></td>
                                    <td>
                                        <?php if ($row['invoice_status'] == 3) {
                                            echo '<span class="st_success">Paid</span>';
                                        } else if ($row['invoice_status'] == 0) {
                                            echo '<span class="st_danger">Not Paid</span>';
                                        } else if ($row['invoice_status'] == 1) {
                                            echo '<span class="st_warning">Overdue</span>';
                                        } else {
                                            echo '<span class="st_violet">Partially Paid</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#overView').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf'
            ],
            columnDefs: [{
                    targets: [0, 1, 2, 3, 4],
                    orderable: false
                }
            ]
        });
    });
    let alert = new ModalAlert();
    <?php
    if (session()->getFlashdata("op_success")) { ?>
        alert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>
</body>

</html>