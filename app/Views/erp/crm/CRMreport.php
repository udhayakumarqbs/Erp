    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .text-primary {
            background-color: #007bff;
        }

        .bg-info {
            background-color: #1fe0ff;
        }

        .bg-success {
            background-color: #3af364;
        }

        .bg-secondary {
            background-color: #aebcc9;
        }

        /* .sourceStatus {
            box-sizing: border-box;
            box-shadow: 0 4px 6px 0 rgb(0 0 0 / 14%);
             height: 100%;
            width: auto;
        }*/

        .leadstatusChart {
            box-sizing: border-box;
            /* box-shadow: 0 4px 6px 0 rgb(0 0 0 / 14%); */
            height: 400px;
            width: 400px;
        }

        .total-leads {
            width: 259px;
            height: 58px;
            padding: 10px;
        }

        .total-space {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-bottom: -10px;
        }

        .total-space p {
            margin-bottom: 20px;
        }

        .inline-box ul {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .sourceStatus {
            /* display: block; */
            box-sizing: border-box;
            height: 400px;
            width: 400px;
        }

        .col-md-6 {
            width: 720px;
        }

        .bs {

            box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
            /* background-color: #ffffff; */
        }

        .chart {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
    </style>
    <div class="alldiv flex widget_title">
        <h3>View Report</h3>
    </div>
    <div class="inline-box">
        <ul>
            <li>
                <div class="alldiv total-leads">
                    <div class="total-space">
                        <p><i class="fa-solid fa-leaf"></i> Leads Contacted</p>
                        <p><?= $leadReportDashboard['contacted_total'][0]->count ?>/<?= $leadReportDashboard['total'][0]->total ?></p>
                    </div>
                    <div class="bs border-rounded mt-1">
                        <div class="text-primary" style="height: 4px; width:<?= ($leadReportDashboard['contacted_total'][0]->count / $leadReportDashboard['total'][0]->total) * 100  ?>%">
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="alldiv total-leads">
                    <div class="total-space">
                        <p><i class="fa-solid fa-list-check"></i> Task Completed</p>
                        <p><?= $taskReportBox['completed_total'][0]->count ?>/<?= $taskReportBox['total'][0]->total ?></p>
                    </div>
                    <div class="bs border-rounded mt-1">
                        <div class="bg-success" style="height: 4px; width:<?= ($taskReportBox['completed_total'][0]->count / $taskReportBox['total'][0]->total) * 100  ?>%">
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="alldiv total-leads">
                    <div class="total-space">
                        <p><i class="fa-solid fa-ticket"></i> Ticket Solved</p>
                        <p><?= $ticketReportBox['soled_total'][0]->count ?>/<?= $ticketReportBox['total'][0]->total ?></p>
                    </div>
                    <div class="bs border-rounded mt-1">
                        <div class="bg-secondary" style="height: 4px; width:<?= ($ticketReportBox['soled_total'][0]->count / $ticketReportBox['total'][0]->total) * 100  ?>%">
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="chart">
        <div class="col-5 sourceStatus">
            <canvas id="sourceStatus"></canvas>
        </div>
        <div class="col-5 leadstatusChart">
            <canvas id="leadstatusChart"></canvas>
        </div>
    </div>
    <!--SCRIPT WORKS -->
    </div>
    </main>
    <script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
    <script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
    <script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
    <script type="text/javascript">
        const leadstatusCounts = <?php echo json_encode($leadsCountStatus); ?>;
        const leadLabels = ['OPEN', 'CONTACTED', 'WORKING', 'DISQUALIFIED', 'CUSTOMER'];
        const leadData = Object.values(leadstatusCounts);

        // Create Chart.js doughnut chart
        const leadTableId = document.getElementById('leadstatusChart').getContext('2d');
        const leadstatusChart = new Chart(leadTableId, {
            type: 'doughnut',
            data: {
                labels: leadLabels,
                datasets: [{
                    data: leadData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 205, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(39, 245, 174, 0.8)',
                    ],
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Lead Status',
                    },
                },
            },
        });


        const sourceCountData = <?= $leadsSourceStatus ?>;
        const labels = sourceCountData.map(entry => entry.source_name);
        const dataset1Data = sourceCountData.map(entry => entry.source_count);

        const customColors = [
            'darkred', 'darkblue', 'darkgreen', 'darkorange', 'darkpurple', 'darkpink', 'darkbrown'
        ];

        const backgroundColors = customColors.slice(0, labels.length);

        const data = {
            labels: labels,
            datasets: [{
                type: 'bar',
                label: 'Source Count',
                backgroundColor: backgroundColors,
                data: dataset1Data,
            }]
        };

        const ctx = document.getElementById('sourceStatus').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                plugins: {
                    title: {
                        text: 'Lead Source Count',
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    </body>

    </html>