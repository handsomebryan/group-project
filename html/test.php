<!DOCTYPE html>
<html>
<head>
    <title>Line Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
</head>
<body>
    <input type="text" id="s_id" placeholder="業務員序號">
    <input type="text" id="c_id" placeholder="客戶序號">
    <button onclick="fetchData()">Search</button>
    <button onclick="resetData()">Reset</button>
    <canvas id="myChart"></canvas>

    <script>
        let chart;
        async function fetchData() {
            const s_id = document.getElementById('s_id').value;
            const c_id = document.getElementById('c_id').value;
            const response = await fetch(`test2.php?s_id=${s_id}&c_id=${c_id}`);
            const data = await response.json();
            if (chart) chart.destroy();
            const ctx = document.getElementById('myChart').getContext('2d');
            chart = new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    scales: {
                        x: { type: 'time' },
                        y: { beginAtZero: true }
                    }
                }
            });
        }
        function resetData() {
            document.getElementById('s_id').value = '';
            document.getElementById('c_id').value = '';
            if (chart) chart.destroy();
        }
    </script>
</body>
</html>
