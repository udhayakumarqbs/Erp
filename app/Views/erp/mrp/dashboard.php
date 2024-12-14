<div class="alldiv flex widget_title">
    <h3>MRP Dashboard</h3>

</div>

<style>
    .year-picker-container {
        margin-bottom: 20px;
    }

    .year-input {
        padding: 10px;
        font-size: 16px;
        width: 200px;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .chart-container {
        position: relative;
        max-width: 800px;
        margin: auto;
    }
</style>


<style>
    .card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 200px;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .count {
        font-weight: bold;
    }


    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="alldiv">


    <div class="card-container">
        <div class="card">
            <h3>Not Started</h3>
            <p class="count mt-3 text-warning"> <?= $status_count['not_started']; ?> </p>
        </div>
        <div class="card">
            <h3>Ongoing</h3>
            <p class="count mt-3 text-success"><?= $status_count['ongoing']; ?> </p>

        </div>
        <div class="card">
            <h3>Complete</h3>
            <p class="count mt-3 text-primary"><?= $status_count['complete']; ?> </p>

        </div>
        <div class="card">
            <h3>Cancelled</h3>
            <p class="count mt-3 text-danger"><?= $status_count['cancelled']; ?></p>

        </div>

    </div>

    <!-- stcok deatils -->

    <div class="p-3 mt-5">

        <div class="year-picker-container">
            <label for="yearInput">Select a Year:</label>
            <input type="number" id="yearInput" class="year-input" placeholder="Enter Year" min="2000" max="2100"
                value="2024">
        </div>
        <div class="chart-container">
            <canvas id="chart_0"></canvas>
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
<script>
    let chartInstance = null;

    // Function to initialize or update the chart
    function initializeChart(labels, data) {
        const ctx = document.getElementById('chart_0').getContext('2d');

        const chartData = {
            labels: labels,
            datasets: [{
                backgroundColor: "rgba(75, 192, 192, 0.2)",
                borderColor: "rgba(75, 192, 192, 1)",
                borderWidth: 2,
                data: data
            }]
        };

        const chartOptions = {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Stock'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Products'
                    }
                }
            }
        };

        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: chartOptions
        });
    }

    // Fetch data from the API and update the chart
    function fetchDataForYear(year) {
        fetch(`<?= url_to('erp.dashboard.yearly.ajax.data') ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ year: year })
        })
            .then(response => response.json())
            .then(data => {
                if (data[year]) {
                    const labels = data[year].map(item => item.product);
                    const stocks = data[year].map(item => item.stock);
                    initializeChart(labels, stocks);
                } else {
                    alert("No data available for the selected year.");
                }
            })
            .catch(error => console.error("Error fetching data:", error));
    }

    // Event listener for year input
    const yearInput = document.getElementById('yearInput');
    yearInput.addEventListener('input', function () {
        const selectedYear = this.value;
        fetchDataForYear(selectedYear);
    });

    // Initialize chart with default year
    const defaultYear = yearInput.value;
    fetchDataForYear(defaultYear);

</script>
</body>

</html>