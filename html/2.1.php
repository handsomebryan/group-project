<!DOCTYPE html>
<html>

<head>
  <title>Insurance Sales Data</title>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
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
        if (this.value) {
          monthDropdown.disabled = true;
          monthDropdown.value = '';
        } else {
          monthDropdown.disabled = false;
        }
      });

      monthDropdown.addEventListener('change', function () {
        if (this.value) {
          quarterDropdown.disabled = true;
        } else {
          quarterDropdown.disabled = false;
        }
      });

      document.getElementById('searchButton').addEventListener('click', function () {
        var year = document.getElementById('yearDropdown').value;
        var quarter = document.getElementById('quarterDropdown').value;
        var month = document.getElementById('monthDropdown').value;

        var url = `getBSData.php?year=${year}`;
        if (quarter) {
          url += `&quarter=${quarter}`;
        } else if (month) {
          url += `&month=${month}`;
        }

        fetch(url)
          .then(response => response.json())
          .then(data => updateTable(data))
          .catch(error => console.error('Error:', error));
      });


      document.getElementById('resetButton').addEventListener('click', function () {
        document.getElementById('yearDropdown').selectedIndex = 0;

        var quarterDropdown = document.getElementById('quarterDropdown');
        var monthDropdown = document.getElementById('monthDropdown');

        quarterDropdown.selectedIndex = 0;
        monthDropdown.selectedIndex = 0;

        quarterDropdown.disabled = false;
        monthDropdown.disabled = false;

        updateTable([]); // Clear the table
      });

    });

    function updateTable(data) {
      var table = document.getElementById('data_table').getElementsByTagName('tbody')[0];
      table.innerHTML = '';
      data.forEach(function (row) {
        var newRow = table.insertRow();
        row.forEach(function (cell) {
          var newCell = newRow.insertCell();
          newCell.textContent = cell;
        });
      });
    }
  </script>
</head>

<body>
  <select id="yearDropdown">
    <option value="">Select Year</option>
  </select>
  <!-- Quarter Dropdown -->
  <select id="quarterDropdown">
    <option value="">Select Quarter</option>
    <option value="1">Q1</option>
    <option value="2">Q2</option>
    <option value="3">Q3</option>
    <option value="4">Q4</option>
  </select>

  <!-- Month Dropdown -->
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

  <table id="data_table">
    <thead>
      <tr>
        <th>| Product Code </th>
        <th>| Month /Daily </th>
        <th>| Monthly / Daily Sales |</th>
      </tr>
    </thead>
    <tbody>
      <!-- Data rows will go here -->
    </tbody>
  </table>
</body>

</html>