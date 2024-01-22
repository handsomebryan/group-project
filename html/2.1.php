<!DOCTYPE html>
<html>

<head>
  <title>Insurance Sales Data</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var salesChart; // Variable for the chart instance

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

        var url = `getBSData.php?year=${year}`;
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

        //  unique labels for the x-axis
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
            label: key, // 商品中文名稱(商品英文代碼)
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
    <div><select id="yearDropdown">
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
      <button id="searchButton">Search</button>
      <button id="resetButton">Reset</button>
    </div>
    <canvas id="salesChart" width="400" height="200"></canvas>
</body>

</html>