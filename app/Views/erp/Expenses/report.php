<style>
    .input-group .form-control {
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
        width: 30%;
        height: 41px;
        border-radius: 12px;
    }

    .input-group-append .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-left: 0;
    }

    .material-icons {
        font-size: 20px;
    }

    .datepicker table tr td span.focused,
    .datepicker table tr td span:hover {
        background: #c5f7d2;
    }

    tr th,
    tr td {
        border: 1px solid rgb(115, 107, 124);
    }
</style>
<div class="alldiv flex widget_title">
    <h3>Expenses Report</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.expensesview'); ?>" class="btn bg-success"><i
                class="fa-solid fa-arrow-left"></i> Detailed Report</a>
    </div>
    <div class="formWidth-1 p-2">
        <!--export button-->
        <a type="button" class="exprotBtn btn bg-primary"><i class="fa fa-external-link"></i> Export</a>
        <div class="export_container poF">
            <ul class="exportUl" id="export_url">
                <?php $year = date("Y") ?>
                <li><a id="excel"
                        data-default-href="<?php echo url_to("erp.expenses.report.export") . '?export=excel&year=' . $year; ?>"
                        href="<?php echo url_to("erp.expenses.report.export") . '?export=excel&year=' . $year; ?>"
                        target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/xls.png'; ?>"
                            alt="excel">EXCEL</a>
                </li>
                <li><a id="pdf"
                        data-default-href="<?php echo url_to("erp.expenses.report.export") . '?export=pdf&year=' . $year; ?>"
                        href="<?php echo url_to("erp.expenses.report.export") . '?export=pdf&year=' . $year; ?>"
                        target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/pdf.png'; ?>"
                            alt="pdf">PDF</a>
                </li>
                <li><a id="csv"
                        data-default-href="<?php echo url_to("erp.expenses.report.export") . '?export=csv&year=' . $year; ?>"
                        href="<?php echo url_to("erp.expenses.report.export") . '?export=csv&year=' . $year; ?>"
                        target="_BLANK"><img src="<?php echo base_url() . 'assets/images/icons/csv.png'; ?>"
                            alt="csv">CSV</a>
                </li>
            </ul>
            <a type="button" class="closeBtn3 HoverA"><i class="fa fa-close"></i></a>
        </div>
        <!--export button-->
    </div>
</div>
<div class="Expense-table-container overflow-scroll border" style="max-height: 50vh;">
    <table style="width : 100%;">
        <thead>
            <tr>
                <th>Category</th>
                <th>January</th>
                <th>February</th>
                <th>March</th>
                <th>April</th>
                <th>May</th>
                <th>June</th>
                <th>July</th>
                <th>August</th>
                <th>September</th>
                <th>October</th>
                <th>November</th>
                <th>December</th>
                <th>Year</th>
            </tr>
        </thead>
        <tbody class="report-container">

        </tbody>
    </table>
</div>
<div class="alldiv">
    <div class="container mt-5">
        <div class="form-group">
            <!-- Material Design input with Bootstrap datepicker -->
            <label for="datepicker" class="font-weight-bold" style="margin-left: 20px;">Select a Year</label>
            <div class="input-group date" id="datepicker" style="margin-left: 20px;">
                <input type="text" class="form-control" id="datepickerInput" placeholder="Pick a Year" readonly>
            </div>
        </div>
    </div>
    <canvas id="expenseChart"></canvas>
</div>
</div>
</div>



<!--SCRIPT WORKS -->
</div>
</main>
<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>

    var datepicker = $('#datepicker');

    $('#datepicker').datepicker({
        format: 'yyyy',
        viewMode: "years", // This will ensure that the datepicker shows only months
        minViewMode: "years",
        startView: "years", // This hides the days and shows only months and years
        todayHighlight: true,  // Highlight today's date
        autoclose: true        // Close the datepicker once a date is selected
    });

    $("#datepickerInput").on("change", function () {
        let data = $(this).val();
        let pdf = $("#pdf");
        let excel = $("#excel");
        let csv = $("#csv");

        if (data.length != "" && data != "") {
            apicall(data);

            //pdf
            pdf.attr("href", `<?= url_to("erp.expenses.report.export") ?>?export=excel&year=${data}`);
            pdf.attr("data-default-href", `<?= url_to("erp.expenses.report.export") ?>?export=excel&year=${data}`);

            //excel
            excel.attr("href", `<?= url_to("erp.expenses.report.export") ?>?export=excel&year=${data}`);
            excel.attr("data-default-href", `<?= url_to("erp.expenses.report.export") ?>?export=excel&year=${data}`);

            //csv
            excel.attr("href", `<?= url_to("erp.expenses.report.export") ?>?export=excel&year=${data}`);
            excel.attr("data-default-href", `<?= url_to("erp.expenses.report.export") ?>?export=excel&year=${data}`);

        }


    })

    let expenseChart = null;

    function reinitializeChart(datas) {

        if (expenseChart) {
            expenseChart.destroy();
        }
        const ctx = document.getElementById('expenseChart').getContext('2d');

        // Labels for the months of the year
        const labels = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Example yearly expense data for each month
        const data = datas;
        // const data = [1200, 1500, 1000, 1800, 1600, 2000, 2200, 1900, 1700, 2500, 2300, 2100];

        expenseChart = new Chart(ctx, {
            type: 'line', // You can also use 'bar' for a bar chart
            data: {
                labels: labels, // Months of the year as x-axis labels
                datasets: [{
                    label: 'Monthly Expenses',
                    data: data, // Yearly data for each month
                    borderColor: 'rgba(75, 192, 192, 1)', // Line color
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Fill under the line
                    borderWidth: 2,
                    fill: true, // Fill the area under the line
                    tension: 0.2 // Smoothness of the line
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Expense Amount (rupees)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `Expense: Rs.${context.raw}`;
                            }
                        }
                    }
                }
            }
        });

    }

    function apicall(date) {
        const datachart = Array(12).fill(0); // Initialize data array with 12 zeros
        $.ajax({
            url: "<?= url_to("erp.ajaxexpensetable") ?>",
            method: "POST",
            data: { val: date },
            success: function (response) {
                let container = $(".report-container");
                $(container).empty();

                let exatc_response = response.data;
                let netamounts = response.netamount;
                let gstammount = response.gst_amount;
                let Totalamount = response.total_amount;


                for (const category in exatc_response) {

                    if (exatc_response.hasOwnProperty(category)) {
                        let row = `<tr><td style="font-weight : bold;">${category}</td>`;
                        let count = 1;
                        for (const months in exatc_response[category]) {

                            if (exatc_response[category].hasOwnProperty(months)) {

                                row += `<td>${exatc_response[category][months]} Rs</td>`;

                                count++;
                            }
                        }
                        row += `</tr>`;
                        $(container).append(row);
                    }
                }

                //net amount

                let row_1 = `<tr><td style="color : #2196f3; font-weight : bold; ">Net Amount</td>`;
                for (const val in netamounts) {
                    row_1 += `<td>${netamounts[val]} Rs</td>`;
                }
                row_1 += `</tr>`;

                $(container).append(row_1);


                //gst amount

                let row_2 = `<tr><td style="color : #2196f3; font-weight : bold; ">Total Tax</td>`;
                for (const val in gstammount) {
                    row_2 += `<td>${gstammount[val]} Rs</td>`;
                }
                row_2 += `</tr>`;

                $(container).append(row_2);

                //total amount
                let row_3 = `<tr><td style="color : #2196f3; font-weight : bold; ">Total</td>`;
                for (const val in Totalamount) {
                    row_3 += `<td>${Totalamount[val]} Rs</td>`;
                }
                row_3 += `</tr>`;

                $(container).append(row_3);

                //chart
                let count = 1;
                for (const [month, expense] of Object.entries(Totalamount)) {
                    if (count <= 12) {
                        datachart[parseInt(month) - 1] = expense;
                    }
                    count++;
                }



                reinitializeChart(datachart);

            }
            , error: function (xhr, status, error) {
                console.log(status);
            }
        })
    }

    const today = new Date();
    const currentYear = today.getFullYear();
    apicall(currentYear);



</script>
</body>

</html>