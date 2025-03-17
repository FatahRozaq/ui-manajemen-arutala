<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApexCharts dengan Laravel Blade</title>

    <!-- Tambahkan ApexCharts dan Axios -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
        }
        input, button {
            margin-top: 10px;
            padding: 8px;
        }
        #chart {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h2>Grafik Dinamis dari Query SQL</h2>

    <!-- Input Query -->
    <input type="text" id="sqlQuery" placeholder="Masukkan query SQL..." style="width: 60%;">
    <button onclick="fetchChartData()">Tampilkan</button>

    <!-- Elemen Chart -->
    <div id="chart"></div>

    <script>
        function fetchChartData() {
            const sqlQuery = document.getElementById("sqlQuery").value;
    
            axios.post("{{ url('api/convert-sql') }}", { sql: sqlQuery })
            .then(response => {
                const data = response.data;

                console.log(data.categories)
    
                // Hapus chart lama jika ada
                document.getElementById("chart").innerHTML = "";
    
                var options = {
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    series: [{
                        name: 'Total',
                        data: data.series[0].data
                    }],
                    xaxis: {
                        categories: data.categories // Sudah diperbaiki agar sesuai format ApexCharts
                    }
                };
    
                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            })
            .catch(error => {
                console.error("Error fetching chart data:", error);
                alert("Terjadi kesalahan dalam query SQL.");
            });
        }
    </script>
    

</body>
</html>
