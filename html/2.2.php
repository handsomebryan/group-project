<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var salesChart;
            fetch('get/getYears.php')
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
                var url = `get/getCASData.php`;
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
                            label: '指定業務員 (業務員序號: ' + (data.user.length > 0 ? data.user[0].業務員序號.slice(-5) : 'N/A') + ')',
                            data: data.user.map(d => d.TotalSales),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            datalabels: {
                                color: 'black',
                                align: 'right'
                            }
                        }, {
                            label: '銷量第一名 (業務員序號: ' + (data.T1.length > 0 ? data.T1[0].業務員序號.slice(-5) : 'N/A') + ')',
                            data: data.T1.map(d => d.TotalSales),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            datalabels: {
                                color: 'black',
                                align: 'right'
                            }
                        }, {
                            label: '銷量第二名 (業務員序號: ' + (data.T2.length > 0 ? data.T2[0].業務員序號.slice(-5) : 'N/A') + ')',
                            data: data.T2.map(d => d.TotalSales),
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            datalabels: {
                                color: 'black',
                                align: 'right'
                            }
                        }]
                    }, plugins: [ChartDataLabels],
                    options: {
                        indexAxis: 'y',
                        aspectRatio: 1,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: '銷售額',
                                    color: 'black',
                                    weight: 'bold'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: '日期',
                                    color: 'black',
                                    weight: 'bold'
                                },
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
    <select id="yearDropdown" class="form-select ">
        <option value="">年份</option>
    </select>
    <select id="quarterDropdown" class="form-select ">
        <option value="">季度</option>
        <option value="1">第一季度</option>
        <option value="2">第二季度</option>
        <option value="3">第三季度</option>
        <option value="4">第四季度</option>
    </select>
    <select id="monthDropdown" class="form-select ">
        <option value="">月份</option>
        <option value="1">1月</option>
        <option value="2">2月</option>
        <option value="3">3月</option>
        <option value="4">4月</option>
        <option value="5">5月</option>
        <option value="6">6月</option>
        <option value="7">7月</option>
        <option value="8">8月</option>
        <option value="9">9月</option>
        <option value="10">10月</option>
        <option value="11">11月</option>
        <option value="12">12月</option>
    </select>
    <input type="text" id="idInput" placeholder="業務員序號(後5碼)">
    <button id="searchButton">Search</button>
    <button id="resetButton">Reset</button>
    <canvas id="salesChart" width="800" height="400"></canvas>
</body>

</html>