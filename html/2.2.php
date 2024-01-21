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
                var id = document.getElementById('idInput').value;
                var year = document.getElementById('yearDropdown').value;
                var quarter = document.getElementById('quarterDropdown').value;
                var month = document.getElementById('monthDropdown').value;

                console.log("Search button clicked. ID:", id, "Year:", year, "Quarter:", quarter, "Month:", month); // Debugging

                var url = `getCASData.php`;
                var queryParams = [];
                if (id) queryParams.push(`id=${id}`);
                if (year) queryParams.push(`year=${year}`);
                if (quarter) queryParams.push(`quarter=${quarter}`);
                if (month) queryParams.push(`month=${month}`);

                if (queryParams.length > 0) {
                    url += '?' + queryParams.join('&');
                    console.log("URL being fetched:", url); // Debugging

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            console.log("Data received:", data); // Debugging
                            updateTable(data);
                        })
                        .catch(error => console.error('Fetch error:', error));
                } else {
                    alert("Please enter a User ID or select a Year.");
                }
            });


            document.getElementById('resetButton').addEventListener('click', function () {
                document.getElementById('yearDropdown').selectedIndex = 0;
                quarterDropdown.selectedIndex = 0;
                monthDropdown.selectedIndex = 0;
                quarterDropdown.disabled = false;
                monthDropdown.disabled = false;
                document.getElementById('idInput').value = '';
                updateTable([]); // Clear the table
            });
        });

        function updateTable(data) {
            var table = document.getElementById('data_table').getElementsByTagName('tbody')[0];
            table.innerHTML = '';

            // Assuming the longest array will determine the number of rows
            let maxRows = Math.max(data.user.length, data.T1.length, data.T2.length);

            for (let i = 0; i < maxRows; i++) {
                var newRow = table.insertRow();

                // Add user-specific data
                addUserDataToRow(newRow, data.user[i], 3); // 3 columns for user data

                // Add top 1 salesperson data
                addUserDataToRow(newRow, data.T1[i], 3); 

                // Add top 2 salesperson data
                addUserDataToRow(newRow, data.T2[i], 3);
            }
        }

        function addUserDataToRow(row, userData, columns) {
            if (!userData) {
                // If userData is null or undefined, add empty cells for the number of columns
                for (let i = 0; i < columns; i++) {
                    addCell(row, '');
                }
                return;
            }

            // Add ID cell
            addCell(row, userData.業務員序號 || '');

            // Determine and add the time frame cell
            var timeFrame = userData.Date ? userData.Date :
                (userData.Year && userData.Month ? `${userData.Year}-${userData.Month}` : '');
            addCell(row, timeFrame);

            // Add Total Sales cell
            addCell(row, userData.TotalSales || '');
        }

        function addCell(row, text) {
            var cell = row.insertCell();
            cell.textContent = text;
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
    <input type="text" id="idInput" placeholder="業務員序號(後5碼)">

    <button id="searchButton">Search</button>
    <button id="resetButton">Reset</button>

    <table id="data_table">
        <thead>
            <tr>
                <th>| ID(search by user) </th>
                <th>| Date </th>
                <th>| Monthly / Daily Sales |</th>
                <th> ID(of Top 1 sales) </th>
                <th>| Date(of Top 1 sales) </th>
                <th>| Monthly / Daily Sales(of Top 1 sales) |</th>
                <th> ID(Top 2) </th>
                <th>| Date </th>
                <th>| Monthly / Daily Sales |</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data rows will go here -->
        </tbody>
    </table>
</body>
</html>