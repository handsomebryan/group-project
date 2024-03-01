<!DOCTYPE html>
<html>

<head>
    <title>Line Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

</head>

<body>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let salesChart;
            document.getElementById('searchButton').addEventListener('click', function () {
                var year = document.getElementById('s_id').value;
                var quarter = document.getElementById('c_id').value;
                var url = `getFrequency.php?`;
                if (s_id && c_id) {
                    url += `s_id=${s_id}&c_id=${c_id}`;
                } else if (s_id) {
                    url += `&s_id=${s_id}`;
                } else if (c_id) {
                    url += `&c_id=${c_id}`;
                }
                fetch(url)
                    .then(response => response.json())
                    .then(data => updateChart(data))
                    .catch(error => console.error('Error:', error));
            });
            document.getElementById('resetButton').addEventListener('click', function () {
                document.getElementById('s_id').value = '';
                document.getElementById('c_id').value = '';
                if (salesChart) {
                    salesChart.destroy();
                }
            });
            function updateChart(data) {
                var ctx = document.getElementById('salesChart').getContext('2d');
                if (salesChart) {
                    salesChart.destroy();
                }
                var specificColors = ['#ed5739', '#64b579'];
                var labels = [...new Set(data.map(item => item[1]))];
                var datasets = [];
                var groupedData = data.reduce(function (acc, item) {
                    if (!acc[item[0]]) {
                        acc[item[0]] = Array(labels.length).fill(0);
                    }
                    var index = labels.indexOf(item[1]);
                    acc[item[0]][index] = item[2];
                    return acc;
                }, {});
                Object.keys(groupedData).forEach(function (key, index) {
                    datasets.push({
                        label: key,
                        data: groupedData[key],
                        borderColor: specificColors[index % specificColors.length],
                        fill: false
                    });
                });
                salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.visit.map(d => d.Date || `${d.Year}-${d.Month}`),
                        datasets: [{
                            label: '拜訪次數 ' + '',
                            data: data.visit.map(d => d.拜訪次數),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }, {
                            label: '聯繫次數' + '',
                            data: data.visit.map(d => d.聯繫次數),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
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
    <input type="text" id="s_id" placeholder="業務員序號">
    <input type="text" id="c_id" placeholder="客戶序號">
    <button id="searchButton">Search</button>
    <button id="resetButton">Reset</button>
    <canvas id="salesChart"></canvas>
</body>

</html>