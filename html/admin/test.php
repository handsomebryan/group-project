<!doctype html>
<html lang="en">
<head>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var ctx = document.getElementById('myChart').getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: [],
          datasets: [{
            data: [],
            backgroundColor: ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f'], // Add colors for each doughnut segment
          }]
        },
      });

      // Function to fetch postal codes
      function fetchPostalCodes() {
        fetch('../getPC.php')
          .then(response => response.json())
          .then(data => {
            populatePostalCodes(data);
          })
          .catch(error => console.error('Error:', error));
      }

      // Function to populate postal code dropdown
      function populatePostalCodes(postalCodes) {
        var postalCodeSelect = document.getElementById('postalCode');
        postalCodeSelect.innerHTML = '<option value="">Select Postal Code</option>';
        postalCodes.forEach(function(code) {
          var option = document.createElement('option');
          option.value = code;
          option.textContent = code;
          postalCodeSelect.appendChild(option);
        });
      }

      // Fetch postal codes when the page loads
      fetchPostalCodes();

      document.getElementById('searchButton').addEventListener('click', function () {
        var gender = document.getElementById('gender').value;
        var postalCode = document.getElementById('postalCode').value;
        var age = document.getElementById('age').value;

        fetch(`getREData.php?gender=${gender}&postalCode=${postalCode}&age=${age}`)
          .then(response => response.json())
          .then(data => updateChart(myChart, data))
          .catch(error => console.error('Error:', error));
      });

      document.getElementById('resetButton').addEventListener('click', function () {
        document.getElementById('gender').value = '';
        document.getElementById('postalCode').value = '';
        document.getElementById('age').value = '';
        updateChart(myChart, []); // Clear the chart
      });
    });

    function updateChart(chart, data) {
      chart.data.labels = data.map(row => row[0]); // Product names
      chart.data.datasets[0].data = data.map(row => row[2]); // Purchase counts
      chart.update();
    }
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
                <h5 class="card-title fw-semibold">客戶產品推薦</h5>
              </div>
            </div>
            <div class="input-group">
              <select id="gender" class="form-select ">
                <option value="">Select Gender</option>
                <option value="男">Male</option>
                <option value="女">Female</option>
              </select>

              <!-- Postal code dropdown -->
              <select id="postalCode" class="form-select ">
                <option value="">Select Postal Code</option>
              </select>

              <input type="number" class="form-control" id="age" placeholder="Enter Age">

              <button id="searchButton" type="button" class="btn btn-outline-primary">Search</button>
              <button id="resetButton" type="button" class="btn btn-outline-danger">Reset</button>
            </div>
            <canvas id="myChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
