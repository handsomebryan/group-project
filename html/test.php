<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>業務一把罩</title>
  <link rel="shortcut icon" type="image/png" href="../../assets/images/logos/logo-sm.png" />
  <link rel="stylesheet" href="../../assets/css/styles.min.css" />
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
    function fetchData() {
        var id = document.getElementById('idInput').value;
        var url = `../get/getGAData.php`;
        var queryParams = [];
        if (id) queryParams.push(`id=${id}`);
        if (queryParams.length > 0) {
            url += '?' + queryParams.join('&');
            fetch(url)
            .then(response => response.json())
            .then(data => {
                updateChart(data); 
            })
            .catch(error => console.error('Fetch error:', error));
        } else {
            alert("Please enter a User ID.");
        }
    }
    function resetForm() {
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
        let labelsSet = new Set(data.GA.map(d => d.age_cate));
        let labelsArray = Array.from(labelsSet);
        salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsArray,
                datasets: [{
                    label: 'Male',
                    data: data.GA.filter(d => d.客戶性別 === '男').map(d => d.客戶數量),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Female',
                    data: data.GA.filter(d => d.客戶性別 === '女').map(-d => -d.客戶數量),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        beginAtZero: true,
                        stacked: true
                    }
                }
            }
        });
    }
});
</script>
</head>
<div class="form-inline">
                  <div class="form-group">
                    <div class="input-group">
                      <input type="text" class="form-control" id="idInput" placeholder="業務員序號(後5碼)">
                      <button id="searchButton" type="button" class="btn btn-outline-primary">Search</button>
                      <button id="resetButton" type="button" class="btn btn-outline-danger">Reset</button>
                    </div>
                  </div>
                </div>
                <canvas id="salesChart" width="400" height="200"></canvas>