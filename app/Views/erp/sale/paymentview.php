<div class="alldiv flex widget_title">
    <h3>View Payment</h3>
    <div class="title_right">
        <a href="<?= url_to('erp.sale.payments'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>
<div class="alldiv">

    <div class="tab_content">
        <div class="tab_pane active" id="invoice_info">
            <div class="flex">
                <div class="form-width-1 text-right">
                    <ul class="dropdown-style btn bg-info">
                        <li><a href="#">PDF &dtrif;</a>
                            <ul class="dropdown">
                                <li><a href="<?= url_to('erp.sale.payment.pdf.view', $sale_pay_id, 'view'); ?>">View PDF</a> </li>
                                <li><a href="<?= url_to('erp.sale.payment.pdf.view', $sale_pay_id, 'view'); ?>" target="_blank">View PDF in New Tab</a></li>
                                <li><a href="<?= url_to('erp.sale.payment.pdf.view', $sale_pay_id, 'download'); ?>">Download</a></li>
                                <li><a href="<?= url_to('erp.sale.payment.pdf.view', $sale_pay_id, 'view'); ?>" target="_blank">Print PDF</a></li>
                                <li><a href="#" id="printbutton">Print View</a></li>
                            </ul>
                        </li>
                    </ul>
                    <a class="btn bg-success" id="mail_modal_invoker1">Send Email</a>
                    <a href="#" class="btn bg-danger del-confirm">Delete</a>
                </div>

                <div class="form-width-1" id="printContainer">
                    <!-- <h2>Payment Info</h2><br></hr> -->
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="text-left" style="font-size: 15px;"><b>
                                            <?= $organisationData['company_name']; ?></b>,<br>
                                        <?= $organisationData['address']; ?>,<br>
                                        <?= $organisationData['city']; ?>,<br>
                                        <?= $organisationData['state']; ?>,<br>
                                        <?= $organisationData['country']; ?>,<br>
                                        <?= $organisationData['zipcode']; ?>,<br>Mobile No.:
                                        <?= $organisationData['phone_number']; ?>,<br>VAT Number:
                                        <?= $organisationData['vat_number']; ?>,<br>License:
                                        <?= $organisationData['license_number']; ?><br>
                                    </td>
                                    <td class="text-right" style="font-size: 15px;">
                                        <?= $customer_data['company']; ?></b>,<br>
                                        <?= $customer_data['address']; ?>,<br>
                                        <?= $customer_data['city']; ?>,<br>
                                        <?= $customer_data['state']; ?>,<br>
                                        <?= $customer_data['country']; ?>,<br>
                                        <?= $customer_data['zip']; ?><br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h2 class="mt-4 mb-4">Payment Receipt</h2>
                    <style>
                        .flex-list {
                            display: flex;
                            list-style-type: none;
                            padding: 0;
                        }

                        .total-btn {
                            /* background-color: #74b544; */
                            /* background-image: linear-gradient(#74b544, #0e5cab);
                            color: #fff;
                            padding: 20px 97px 20px 90px;
                            display: flex;
                            text-align: center;
                            font-size: 16px; */
                            background-color: #74b544;
                            color: #fff; 
                            padding: 90px; 
                            width: 50%; 
                            font-size: 16px; 
                            text-align:center; 
                            font-family:freeserif
                        }

                        .amount {
                            display: flex;
                        }

                        .hr-space-1 {
                            margin-top: 10px;
                            margin-bottom: 10px;
                            width: 100%;
                            margin-left: 3px;
                            border: 0;
                            height: 1px;
                            background-image: linear-gradient(to right, transparent, #ccc, transparent);
                        }
                    </style>

                    <!-- Currency Symbol Position -->
                    <?php $due_amount = $invoice_data['total_amount'] - ($total_paid_amount + $invoice_data['credited_amount']); ?>
                    <?php if ($currency['place'] === 'after') :
                        $totalAmount = number_format($invoice_data['total_amount'], 2, '.', ',') . '' . $currency['currency_symbol'];
                        $paidAmount = number_format($invoice_data['paid_amount'], 2, '.', ',') . '' . $currency['currency_symbol'];
                        $dueAmount = number_format($due_amount, 2, '.', ',') . '' . $currency['currency_symbol'];
                    elseif ($currency['place'] === 'before') :
                        $totalAmount = $currency['currency_symbol'] . number_format($invoice_data['total_amount'], 2, '.', ',');
                        $paidAmount =  $currency['currency_symbol'] . number_format($invoice_data['paid_amount'], 2, '.', ',');
                        $dueAmount =  $currency['currency_symbol'] . number_format($due_amount, 2, '.', ',');
                    ?>
                    <?php endif; ?>

                    <div>
                        <div>
                            <ul class="flex-list mb-4">
                                <li class="mr-5">Payment Date :</li>
                                <li><?= $invoice_data['paid_on']; ?></li>
                            </ul>
                            <ul class="flex-list">
                                <li class="mr-4"> Payment Mode : </li>
                                <li><?= $invoice_data['payment_mode']; ?></li>
                            </ul>
                        </div>
                        <div class="amount">
                            <p class="total-btn mt-4 mb-3"> Total Amount<br/> <?= $paidAmount; ?></p>
                        </div>
                    </div>

                    </br>
                    <hr class="hr-space-1">
                    <h2 class="mt-5 mb-3">Payment For</h2>
                    <div class="table_responsive">
                        <table class="table">
                            <thead>
                                <th>Invoice Number</th>
                                <th>Invoice Date</th>
                                <th>Invoice Amount</th>
                                <th>Payment Amount</th>
                                <th>Amount Due</th>
                            </thead>
                            <tbody>
                                <td><?= $invoice_data['code']; ?></td>
                                <td><?= $invoice_data['invoice_date']; ?></td>
                                <td><?= $totalAmount; ?></td>
                                <td><?= $paidAmount; ?></td>
                                <td><?= $dueAmount; ?></td>
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
    let closer = new WindowCloser();
    closer.init();
    // let tbody = document.querySelector(".attachment-holder");
    // let fileuploader = new FileUploader(document.querySelector(".file-uploader-frame"));
    let alert = new ModalAlert();

        //Print Invoice

        window.onload = function() {
        document.getElementById('printbutton').addEventListener('click', function() {
            // Use html2canvas to capture the specific container
            html2canvas(document.getElementById('printContainer')).then(function(canvas) {
                var screenshotImage = new Image();
                screenshotImage.src = canvas.toDataURL();

                // Create a new window and wait for it to load
                var screenshotWindow = window.open();
                screenshotWindow.document.write('<html><head><title>Payment Print</title></head><body></body></html>');
                screenshotWindow.document.body.appendChild(screenshotImage);

                // Wait for the image to load in the new window
                screenshotImage.onload = function() {
                    // Trigger print manually (users will still need to confirm)
                    screenshotWindow.print();
                    screenshotWindow.close();
                };
            });
        });
    };

</script>