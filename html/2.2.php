<!DOCTYPE html>
<html>
<head>
    <title>Insurance Sales Data</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var salesChart;
            fetch('getYears.php')
                .then(response => response.json())
                .then(years => {
                    var select = document.getElementById('yearDropdown');
                    years.forEach(function (year) {
                        var option = document.createElement('option');
                        option.text = year;
                        option.value = year;
                        select.add(option);
                    });
                })
                .catch(error => console.error('Error:', error));
            var quarterDropdown = document.getElementById('quarterDropdown');
            var monthDropdown = document.getElementById('monthDropdown');
            quarterDropdown.addEventListener('change', function () {
                monthDropdown.disabled = !!this.value;
            });
            monthDropdown.addEventListener('change', function () {
                quarterDropdown.disabled = !!this.value;
            });
            document.getElementById('searchButton').addEventListener('click', function () {
                fetchData();
            });
            document.getElementById('resetButton').addEventListener('click', function () {
                resetForm();
            });
            function fetchData() {
                var id = document.getElementById('idInput').value;
                var year = document.getElementById('yearDropdown').value;
                var quarter = document.getElementById('quarterDropdown').value;
                var month = document.getElementById('monthDropdown').value;
                var url = `admin/getCASData.php`;
                var queryParams = [];
                if (id) queryParams.push(`id=${id}`);
                if (year) queryParams.push(`year=${year}`);
                if (quarter) queryParams.push(`quarter=${quarter}`);
                if (month) queryParams.push(`month=${month}`);
                if (queryParams.length > 0) {
                    url += '?' + queryParams.join('&');
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            updateChart(data); 
                        })
                        .catch(error => console.error('Fetch error:', error));
                } else {
                    alert("Please enter a User ID or select a Year.");
                }
            }
            function resetForm() {
                document.getElementById('yearDropdown').selectedIndex = 0;
                quarterDropdown.selectedIndex = 0;
                monthDropdown.selectedIndex = 0;
                quarterDropdown.disabled = false;
                monthDropdown.disabled = false;
                document.getElementById('idInput').value = '';
                if (salesChart) {
                    salesChart.destroy();
                }
            }
            function updateChart(data) {
                var ctx = document.getElementById('salesChart').getContext('2d');
                if (salesChart) {
                    salesChart.destroy();
                }
                salesChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.user.map(d => d.Date || `${d.Year}-${d.Month}`),
                        datasets: [{
                            label: 'ID: ' + (data.user.length > 0 ? data.user[0].業務員序號 : 'N/A') + '',
                            data: data.user.map(d => d.TotalSales),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Top 1 Sales',
                            data: data.T1.map(d => d.TotalSales),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Top 2 Sales',
                            data: data.T2.map(d => d.TotalSales),
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
            }
        });
    </script>
</head>
<body>
    <select id="yearDropdown">
        <option value="">Select Year</option>
    </select>
    <select id="quarterDropdown">
        <option value="">Select Quarter</option>
        <option value="1">Q1</option>
        <option value="2">Q2</option>
        <option value="3">Q3</option>
        <option value="4">Q4</option>
    </select>
    <select id="monthDropdown">
        <option value="">Select Month</option>
        <option value="">Select Month</option>
        <option value="1">Jan</option>
        <option value="2">Feb</option>
        <option value="3">Mar</option>
        <option value="4">Apr</option>
        <option value="5">May</option>
        <option value="6">Jun</option>
        <option value="7">Jul</option>
        <option value="8">Aug</option>
        <option value="9">Sep</option>
        <option value="10">Oct</option>
        <option value="11">Nov</option>
        <option value="12">Dec</option>
    </select>
    <input type="text" id="idInput" placeholder="業務員序號(後5碼)">
    <button id="searchButton">Search</button>
    <button id="resetButton">Reset</button>
    <canvas id="salesChart" width="800" height="400"></canvas>
</body>
</html>