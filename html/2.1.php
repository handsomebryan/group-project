<!doctype html>
<html lang="en">

<head>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var salesChart; // Variable for the chart instance
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
        monthDropdown.value = '';
      });
      monthDropdown.addEventListener('change', function () {
        quarterDropdown.disabled = !!this.value;
      });
      document.getElementById('searchButton').addEventListener('click', function () {
        var year = document.getElementById('yearDropdown').value;
        var quarter = document.getElementById('quarterDropdown').value;
        var month = document.getElementById('monthDropdown').value;
        var isSpecificMonth = !!month;
        var url = `get/getBSData.php?year=${year}`;
        if (quarter) {
          url += `&quarter=${quarter}`;
        } else if (month) {
          url += `&month=${month}`;
        }
        fetch(url)
          .then(response => response.json())
          .then(data => updateChart(data, isSpecificMonth))
          .catch(error => console.error('Error:', error));
      });
      document.getElementById('resetButton').addEventListener('click', function () {
        document.getElementById('yearDropdown').selectedIndex = 0;
        quarterDropdown.selectedIndex = 0;
        monthDropdown.selectedIndex = 0;
        quarterDropdown.disabled = false;
        monthDropdown.disabled = false;
        if (salesChart) {
          salesChart.destroy();
        }
      });
      function updateChart(data) {
        var ctx = document.getElementById('salesChart').getContext('2d');
        if (salesChart) {
          salesChart.destroy();
        }
        var specificColors = ['#ed5739', '#64b579', '#a46ce0'];
        var labels = ['', ...new Set(data.map(item => item[1]))];
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
            labels: labels,
            datasets: datasets
          },
          options: {
            scales: {
              y: { beginAtZero: true },
            }
          }
        });
      }
    });
  </script>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-8 d-flex align-items-strech">
        <div class="card w-100">
          <div class="card-body">
            <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
              <div class="mb-3 mb-sm-0">
                <h5 class="card-title fw-semibold">表現最佳的保險產品</h5>
              </div>
            </div>
            <div class="form-inline">
              <div class="input-group">
                <select id="yearDropdown" class="form-select ">
                  <option value="">Select Year</option>
                </select>
                <select id="quarterDropdown" class="form-select ">
                  <option value="">Select Quarter</option>

                </select>
                <select id="monthDropdown" class="form-select ">
                  <option value="">Select Month</option>
                </select>
                <button id="searchButton" type="button" class="btn btn-outline-primary">Search</button>
                <button id="resetButton" type="button" class="btn btn-outline-danger">Reset</button>
              </div>
            </div>
            <canvas id="salesChart" width="400" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
</body>

</html>