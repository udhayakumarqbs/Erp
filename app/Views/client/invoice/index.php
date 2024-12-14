<div class="mt-3">
    <div class="card-style">
        <div class="title d-flex flex-wrap align-items-center justify-content-between">
            <div class="left">
                <h6 class="text-medium mb-30 h3">Invoice</h6>
            </div>
            <div class="right">
                <div class="select-style-1">
                    <div class="select-position select-sm">
                        <select class="light-bg">
                            <option value="">Today</option>
                            <option value="">Yesterday</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table top-selling-table" id="myTable">
                <thead>
                    <tr>
                        <th>
                            <h6 class="text-sm text-medium">Code</h6>
                        </th>
                        <th class="min-width">
                            <h6 class="text-sm text-medium">
                            Date <i class="lni lni-arrows-vertical"></i>
                            </h6>
                        </th>
                        <th class="min-width">
                            <h6 class="text-sm text-medium">
                            Status <i class="lni lni-arrows-vertical"></i>
                            </h6>
                        </th>
                        <th class="min-width">
                            <h6 class="text-sm text-medium">
                            Amount <i class="lni lni-arrows-vertical"></i>
                            </h6>
                        </th>
                        <th>
                            <h6 class="text-sm text-medium text-end">
                                Actions <i class="lni lni-arrows-vertical"></i>
                            </h6>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($invoices)){ foreach($invoices as $invoice){ ?>
            
                    <tr>
                        <td>
                        <p class="text-sm"><?= $invoice['code'] ?></p>
                        </td>
                        <td>
                            <p class="text-sm"><?= $invoice['invoice_date'] ?></p>
                        </td>
                        <td>
                            <p class="text-sm"><?= $invoice['status'] ?></p>
                        </td>
                        <td>
                            <p class="text-sm"><?= $invoice['paid_till'] ?></p>
                        </td>
                        <td style="text-align:center;">
                            <div class="action justify-content-end" style="text-align:center;width:100%;">
                                <button class="more-btn ml-10 dropdown-toggle" id="moreAction1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="http://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<link rel="stylesheet" href="http://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
<script>
        let table = new DataTable('#myTable');
</script>