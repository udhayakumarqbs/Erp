<div class="alldiv flex widget_title">
    <h3><?= $title; ?></h3>
    <div class="title_right">

    </div>
</div>

<div class="alldiv">
    <div class="flex">
        <div class="col-width">
            <ul class="ul-padding1">
                <li class="li-align-head">
                    <h3><i class="fa fa-balance-scale"></i> Sales Report</h3>
                </li>
                <hr class="hr-space">
                <li class="li-align"><a href="#" onclick="report('inv_report')"><i class="fa fa-caret-down"></i>
                        Invoices Report</a></li>
                <hr class="hr-space">
                <li class="li-align"><a href="#" onclick="report('item_report')"><i class="fa fa-caret-down"></i> Items
                        Report</a></li>
                <hr class="hr-space">
                <li class="li-align"><a href="#" onclick="report('payment_report')"><i class="fa fa-caret-down"></i>
                        Payments Received</a></li>
                <hr class="hr-space">
                <li class="li-align"><a href="#" onclick="report('credit_report')"><i class="fa fa-caret-down"></i>
                        Credit Notes Report</a></li>
                <hr class="hr-space">
                <li class="li-align"><a href="#" onclick="report('proposal_report')"><i class="fa fa-caret-down"></i>
                        Quotations Report</a></li>
                <hr class="hr-space">
                <li class="li-align"><a href="#" onclick="report('estimate_report')"><i class="fa fa-caret-down"></i>
                        Estimates Report</a></li>
                <hr class="hr-space">
                <li class="li-align"><a href="#" onclick="report('customer_report')"><i class="fa fa-caret-down"></i>
                        Customers Report</a></li>
                <hr class="hr-space">
            </ul>
        </div>
        <div class="col-width">
            <ul class="ul-padding2">
                <li class="li-align-head">
                    <h3><i class="fa fa-area-chart"></i> Chart Based Report</h3>
                </li>
                <hr class="hr-space">
                <li class="li-align"><a href="#" onclick="chart('total_income')"><i class="fa fa-caret-down"></i> Total
                        Income</a></li>
                <hr class="hr-space">
                <li class="li-align"><a href="#" onclick="chart('payment_mode')"><i class="fa fa-caret-down"></i>
                        Payment Modes(Transactions)</a></li>
                <hr class="hr-space">
                <li class="li-align"><a href="#" onclick="chart('total_customer_value')"><i
                            class="fa fa-caret-down"></i> Total Value by Customer Groups</a></li>
                <hr class="hr-space">
            </ul>
        </div>
        <div class="col-width">
            <div class="ul-padding3" style="display: none">
                <!-- <div>
                    <label for="currency"><i class="fa fa-question-circle" data-toggle="tooltip"
                         title data-original-title="You need to select currency because you have invoices with different currency"></i> Currency</label><br>
                    <select class="selectBoxBtn" name="currency" id=""></select>
                </div>   -->
                <div class="period">
                    <label for="period">Period</label><br>
                    <select class="selectBoxBtn" name="period" id="periodSelect" onchange="period(this)">
                        <option value="all_time">All Time</option>
                        <option value="this_month">This Month</option>
                        <option value="duration">Period</option>
                    </select>
                </div>
                <div id="durationFields" style="display: none;">
                    <div class="flex">
                        <div class="col-w">
                            <label for="fromDate">From Date</label><br>
                            <input type="date" class="f-width" name="fromDate" id="fromDate">
                        </div>
                        <div class="col-w">
                            <label for="toDate">To Date</label><br>
                            <input type="date" class="f-width" name="toDate" id="toDate">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="alldiv">
    <div class="datatable" id="report" data-ajax-url="<?= url_to('erp.sale.ajax.reports') . '?'; ?>"
        style="display: none">
        <div class="report-head">
            <h2>Generated Report</h2>
            <hr class="hr-space-report">
        </div>

        <div class="filterBox">
            <div class="table_responsive table_invoice">
                <table class="table" id="datatable-id">
                    <thead class="thead">
                    </thead>
                    <tbody class="table-paint-area">
                    </tbody>
                </table>
            </div>
            <div class="table_responsive table_item">
                <table class="table" id="datatable-id">
                    <thead class="thead">
                    </thead>
                    <tbody class="table-paint-area">
                    </tbody>
                </table>
            </div>
            <div class="table_responsive table_payment">
                <table class="table" id="datatable-id">
                    <thead class="thead">
                    </thead>
                    <tbody class="table-paint-area">
                    </tbody>
                </table>
            </div>
            <div class="table_responsive table_credit">
                <table class="table" id="datatable-id">
                    <thead class="thead">
                    </thead>
                    <tbody class="table-paint-area">
                    </tbody>
                </table>
            </div>
            <div class="table_responsive table_quotation">
                <table class="table" id="datatable-id">
                    <thead class="thead">
                    </thead>
                    <tbody class="table-paint-area">
                    </tbody>
                </table>
            </div>
            <div class="table_responsive table_estimate">
                <table class="table" id="datatable-id">
                    <thead class="thead">
                    </thead>
                    <tbody class="table-paint-area">
                    </tbody>
                </table>
            </div>
            <div class="table_responsive table_customer">
                <table class="table" id="datatable-id">
                    <thead class="thead">
                    </thead>
                    <tbody class="table-paint-area">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="chart-container" style="display: none;">
        <canvas id="chartCanvas"></canvas>
    </div>
</div>



</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>

<script type="text/javascript">
    let modalAlert = new ModalAlert();
    let closer = new WindowCloser();
    closer.init();
    var r_type = 0;
    let data_table;

    function report(report_type) {
        $('.ul-padding3').show();
        $('.datatable').show();
        r_type = report_type;
        console.log(r_type)

        $.ajax({
            url: "<?= url_to('erp.sale.ajax.reports') ?>",
            type: 'GET',
            data: {
                r_type: r_type
            },
            success: function (data) {
                // console.log(data);

                //Remove chart if its exist
                destroyAllCharts();

                console.log($('.table_responsive' + table_class(r_type) + ' #datatable-id').hasClass('dataTable'));
                if (!$('.table_responsive' + table_class(r_type) + ' #datatable-id').hasClass('dataTable')) {

                    var columnNames = data.datatable_config_title.columnNames;
                    var sortableConfig = data.datatable_config_title.sortable;
                    var tableData = data.datatable_config_data.data;
                    var columns = columnNames.map(function (columnName, index) {
                        return {
                            title: columnName,
                            sortable: sortableConfig[index] === 1
                        };
                    });

                    datatable = $(table_class(r_type) + ' #datatable-id').DataTable({
                        destroy: true,
                        columns: columns,
                        data: tableData,
                        dom: 'Bfrtip',
                        buttons: [{
                            extend: 'excel',
                            title: title(r_type),
                            titleAttr: {
                                className: 'text-center',
                            },
                        },
                        {
                            extend: 'csv',
                            title: title(r_type),
                            titleAttr: {
                                className: 'text-center',
                            },
                        },
                        {
                            extend: 'pdf',
                            title: title(r_type),
                            titleAttr: {
                                className: 'text-center',
                            },
                        },
                        {
                            extend: 'print',
                            title: title(r_type),
                            titleAttr: {
                                className: 'text-center',
                            },
                        },
                        ],
                    });
                    console.log('table not exist');
                } else {
                    console.log('table exist');
                }
                $('.table_responsive').hide();
                $('.table_responsive' + table_class(r_type)).show();

            },
            error: function (error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    function period(selectElement) {
        var durationFields = document.getElementById('durationFields');
        var fromDateInput = document.getElementById('fromDate');
        var toDateInput = document.getElementById('toDate');
        var selectedPeriod = selectElement.value;
        var report_type = this.r_type;
        console.log(selectedPeriod);
        console.log('report type :' + r_type);

        if (selectedPeriod === 'duration') {
            durationFields.style.display = 'block';
        } else {
            durationFields.style.display = 'none';
        }

        handleDateChange();

        fromDateInput.addEventListener('change', handleDateChange);
        toDateInput.addEventListener('change', handleDateChange);

        function handleDateChange() {
            var fromDate = fromDateInput.value;
            var toDate = toDateInput.value;
            $.ajax({
                url: "<?= url_to('erp.sale.period.report') ?>",
                type: "GET",
                data: {
                    report_type: report_type,
                    selectedPeriod: selectedPeriod,
                    fromDate: fromDate,
                    toDate: toDate
                },
                success: function (data) {
                    console.log(data);
                    var columnNames = data.datatable_config_title.columnNames;
                    var sortableConfig = data.datatable_config_title.sortable;
                    var tableData = data.datatable_config_data.data;
                    var columns = columnNames.map(function (columnName, index) {
                        return {
                            title: columnName,
                            sortable: sortableConfig[index] === 1
                        };
                    });

                    if ($.fn.dataTable.isDataTable(table_class(r_type) + ' #datatable-id')) {
                        console.log('table exist');
                        datatable.destroy();


                    } else {
                        console.log('table not exist');
                    }
                    // $('#datatable-id tbody').empty();
                    // if (dataTable != undefined) {
                    //     dataTable.clear().draw();
                    // }


                    datatable = $(table_class(r_type) + ' #datatable-id').DataTable({
                        destroy: true,
                        columns: columns,
                        data: tableData,
                        dom: 'Bfrtip',
                        buttons: [{
                            extend: 'excel',
                            title: title(r_type),
                            titleAttr: {
                                className: 'text-center',
                            },
                        },
                        {
                            extend: 'csv',
                            title: title(r_type),
                            titleAttr: {
                                className: 'text-center',
                            },
                        },
                        {
                            extend: 'pdf',
                            title: title(r_type),
                            titleAttr: {
                                className: 'text-center',
                            },
                        },
                        {
                            extend: 'print',
                            title: title(r_type),
                            titleAttr: {
                                className: 'text-center',
                            },
                        },
                        ],
                    });

                    $('.table_responsive').hide();
                    $('.table_responsive' + table_class(r_type)).show();


                },
                error: function (error) {
                    console.error('Error Fetching Data:', error);
                }
            });
        }
    }

    function title(r_type) {
        if (r_type == 'inv_report') {
            return 'Invoices Report';
        } else if (r_type == 'item_report') {
            return 'Items Rport';
        } else if (r_type == 'payment_report') {
            return 'Payments Report';
        } else if (r_type == 'credit_report') {
            return 'Credit Notes Report';
        } else if (r_type == 'proposal_report') {
            return 'Proposals Report';
        } else if (r_type == 'estimate_report') {
            return 'Estimates Report';
        } else if (r_type == 'customer_report') {
            return 'Customers Report';
        }
    }
    function table_class(r_type) {
        if (r_type == 'inv_report') {
            return '.table_invoice';
        } else if (r_type == 'item_report') {
            return '.table_item';
        } else if (r_type == 'payment_report') {
            return '.table_payment';
        } else if (r_type == 'credit_report') {
            return '.table_credit';
        } else if (r_type == 'proposal_report') {
            return '.table_quotation';
        } else if (r_type == 'estimate_report') {
            return '.table_estimate';
        } else if (r_type == 'customer_report') {
            return '.table_customer';
        }
    }


    var chartInstances = [];

    function chart($chart_type) {
        var selected_chart = $chart_type;
        console.log('c_type: ', selected_chart);

        $.ajax({
            type: "POST",
            url: "<?= url_to('erp.sales.chart') ?>",
            data: {
                selected_chart: selected_chart
            },
            success: function (response) {
                console.log(response);
                $('#report').hide();
                $('.chart-container').show();

                //Remove existing loaded charts
                destroyAllCharts();

                if (selected_chart === 'total_income') {
                    createIncomeChart(response.result);
                } else if (selected_chart === 'payment_mode') {
                    createPaymentChart(response.result);
                } else if (selected_chart === 'total_customer_value') {
                    createTotalCustomerValueChart(response.result);
                }
            },
            error: function (error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    function destroyAllCharts() {
        chartInstances.forEach(function (chartInstance) {
            chartInstance.destroy();
        });
        chartInstances = [];
    }

    function createIncomeChart(totalIncome) {
        var ctx = document.getElementById('chartCanvas').getContext('2d');
        var currentDate = new Date();
        var formattedDate = currentDate.toLocaleString('en-US', {
            month: 'long',
            year: 'numeric'
        });

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [formattedDate],
                datasets: [{
                    label: 'Total Income',
                    data: [totalIncome],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        chartInstances.push(myChart);
    }

    function createPaymentChart(paymentData) {
        var ctx = document.getElementById('chartCanvas').getContext('2d');
        var currentDate = new Date();
        var formattedDate = currentDate.toLocaleString('en-US', {
            month: 'long',
            year: 'numeric'
        });

        var paymentModes = paymentData.map(function (item) {
            return item.name;
        });
        var totalAmounts = paymentData.map(function (item) {
            return parseFloat(item.total_amount);
        });
        var colors = ['#E5A7B3', '#95DED6', '#F1C9A6'];
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: paymentModes,
                datasets: [{
                    label: formattedDate + ' , Total Amount',
                    data: totalAmounts,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        chartInstances.push(myChart);
    }

    function createTotalCustomerValueChart(createTotalvalueCustomer) {
        var ctx = document.getElementById('chartCanvas').getContext('2d');

        var customerGroups = Object.keys(createTotalvalueCustomer);
        var totalAmounts = Object.values(createTotalvalueCustomer);

        var colors = ['#E5A7B3', '#95DED6', '#F1C9A6', '#d9a7e5'];

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: customerGroups,
                datasets: [{
                    label: 'Total Amount',
                    data: totalAmounts,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        chartInstances.push(myChart);
    }

    <?php
    if (session()->getFlashdata("op_success")) { ?>
        modalAlert.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
        <?php
    } else if (session()->getFlashdata("op_error")) { ?>
            modalAlert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
        <?php
    }
    ?>
</script>
<style>
    .table.dataTable {
        width: 100% !important;
    }
</style>
</body>

</html>