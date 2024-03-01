<!DOCTYPE html>
<html>

<head>
    <title>Insurance Sales Data</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var salesChart;
            document.getElementById('searchButton').addEventListener('click', function () {
                fetchData();
            });
            document.getElementById('resetButton').addEventListener('click', function () {
                resetForm();
            });
            fetch('getCID.php')
                .then(response => response.json())
                .then(data => {
                    var select = document.getElementById('c_id');
                    data.forEach(function (id) {
                        var option = document.createElement('option');
                        option.value = id;
                        option.text = id;
                        select.add(option);
                    });
                })
                .catch(error => console.error('Fetch error:', error));
            function fetchData() {
                var s_id = document.getElementById('s_id').value;
                var c_id = document.getElementById('c_id').value;
                var url = `getFrequency.php`;
                var queryParams = [];
                if (s_id) queryParams.push(`s_id=${s_id}`);
                if (c_id) queryParams.push(`c_id=${c_id}`);
                if (queryParams.length > 0) {
                    url += '?' + queryParams.join('&');
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            updateChart(data);
                        })
                        .catch(error => console.error('Fetch error:', error));
                } else {
                    alert("Please enter a Salesperson ID or Customer ID.");
                }
            }
            function resetForm() {
                document.getElementById('s_id').value = '';
                document.getElementById('c_id').value = '';
                if (salesChart) {
                    salesChart.destroy();
                }
            }
            function updateChart(data) {
                var ctx = document.getElementById('salesChart').getContext('2d');
                if (salesChart) {
                    salesChart.destroy();
                }
                var combinedDates = [...new Set([...data.visit.map(d => d.日期), ...data.contact.map(d => d.日期)])].sort();
                salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: combinedDates,
                        datasets: [{
                            label: 'Visit Frequency',
                            data: combinedDates.map(date => {
                                var visit = data.visit.find(d => d.日期 === date);
                                return visit ? visit.拜訪次數 : 0;
                            }),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            fill: false
                        }, {
                            label: 'Contact Frequency',
                            data: combinedDates.map(date => {
                                var contact = data.contact.find(d => d.日期 === date);
                                return contact ? contact.聯絡次數 : 0;
                            }),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            fill: false
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</head>

<body>
    <input type="text" id="s_id" placeholder="s_id">
    <select id="c_id">
        <option value="">Select c_id</option>
    </select>
    <button id="searchButton">Search</button>
    <button id="resetButton">Reset</button>
    <canvas id="salesChart" width="800" height="400"></canvas>
</body>

</html>