<div class="alldiv flex widget_title">
    <h3>View Report</h3>
</div>

<div class="alldiv">
    <ul class="tab_nav">
        <li><a type="button" class="tab_nav_item active" data-src="rfq_attachment">Income vs Expenses</a></li>
        <li><a type="button" class="tab_nav_item" data-src="rfq_info">Simple View</a></li>
    </ul>
    <div class="tab_content">
        <div class="tab_pane active" id="rfq_attachment">
            <div class="flex">
                <div class="mx-2 w-100">
                    <div class='mt-3'>
                        <div class="row">
                            <div class="col-12">
                                <canvas id="incomeExpenditureChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab_pane" id="rfq_info">
            <div class="flex">
                <div class="mx-1">
                    <h2>GL Accounts Report</h2>
                </div>
                <div class="mx-2 w-100">
                    <div class='mt-3'>
                        <div class="row">
                            <div class="col-12">
                                <?php foreach ($journalEntryCount as $row): ?>
                                    <div class="d-flex justify-content-between my-2">
                                        <p class="text-uppercase"><?= $row['gl_name'] ?></p>
                                        <p><?= $row['gl_count'] ?></p>
                                    </div>
                                    <div class="bg-light rounded-lg border">
                                        <i class="bg-success rounded-lg"
                                            style="width: <?= ($row['gl_count'] / $journalEntryIdCount[0]['total_count']) * 100 ?>%;height:20px;display:block;"></i>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
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
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('incomeExpenditureChart').getContext('2d');
        const incomeExpenditureChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [
                    {
                        label: 'Income',
                        data: [<?php foreach ($monthly_income_expence as $entry) {
                            echo $entry['Income'] . ",";
                        } ?>],
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenditure',
                        data: [<?php foreach ($monthly_income_expence as $entry) {
                            echo $entry['Expence'] . ",";
                        } ?>],
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount'
                        }
                    }
                }
            }
        });
    });
</script>

</body>

</html>