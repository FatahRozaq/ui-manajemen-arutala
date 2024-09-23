@extends('layouts/AdminLayouts')

@section('content')

<div class="pagetitle">
    <h1>Dashboard</h1>
  </div><!-- End Page Title -->

  

  <section class="section dashboard">
    <div class="row">
      <div class="col-xxl-3 col-xl-3">
        <div class="card info-card sales-card">

          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow wide-dropdown">
              <li class="dropdown-header text-start">
                <h6>Filter</h6>
              </li>
              <li class="dropdown-item">
                <label for="year">Tahun:</label>
                <select id="year" class="form-select">
                  <option value="2024">2024</option>
                  <option value="2023">2023</option>
                  <option value="2022">2022</option>
                  <option value="2021">2021</option>
                </select>
              </li>
              <li class="dropdown-item d-flex justify-content-between align-items-center">
                <div>
                  <label for="start-month">Bulan Awal:</label>
                  <select id="start-month" class="form-select">
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                  </select>
                </div>
                <div>
                  <label for="end-month">Bulan Akhir:</label>
                  <select id="end-month" class="form-select">
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                  </select>
                </div>
              </li>
              <li class="dropdown-footer text-end">
                <button class="btn btn-primary apply-btn">Apply</button>
              </li>
            </ul>
          </div>

            <div class="card-body">
                <h5 class="card-title">Pendaftar <span></span></h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                        <h6 id="total-pendaftar">Loading...</h6> <!-- Tempat untuk total pendaftar -->
                        {{-- <span class="text-success small pt-1 fw-bold">12%</span> 
                        <span class="text-muted small pt-2 ps-1">increase</span> --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
          // Fungsi untuk mengambil data dengan filter
          function fetchPendaftarData(year, startMonth, endMonth) {
              const url = new URL('/api/dashboard/pendaftar', window.location.origin);
              url.searchParams.append('year', year);
              url.searchParams.append('startMonth', startMonth);
              url.searchParams.append('endMonth', endMonth);
      
              fetch(url)
                  .then(response => response.json())
                  .then(data => {
                      if (data.status === 'success') {
                          // Update jumlah pendaftar
                          document.getElementById('total-pendaftar').textContent = data.jumlah_pendaftar;
                      } else {
                          console.error(data.message);
                      }
                  })
                  .catch(error => {
                      console.error('Error fetching data:', error);
                  });
          }
      
          // Event listener untuk tombol Apply
          document.querySelector('.apply-btn').addEventListener('click', function() {
              const year = document.getElementById('year').value;
              const startMonth = document.getElementById('start-month').value;
              const endMonth = document.getElementById('end-month').value;
      
              fetchPendaftarData(year, startMonth, endMonth);
          });
      
          // Memuat data awal saat halaman dimuat
          fetchPendaftarData(new Date().getFullYear(), 1, 12); // Default: tahun ini, dari Januari hingga Desember
      });
      </script>
      


    <!-- Customers Card -->
    <div class="col-xxl-3 col-xl-3">

      <div class="card info-card customers-card">

        <div class="filter">
          <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow wide-dropdown">
            <li class="dropdown-header text-start">
              <h6>Filter</h6>
            </li>
            <li class="dropdown-item">
              <label for="pelatihan">Pelatihan:</label>
              <select id="pelatihan" class="form-select">
                  <option value="">Semua Pelatihan</option>
              </select>
          </li>
          <li class="dropdown-item">
              <label for="batch">Batch:</label>
              <select id="batch" class="form-select">
                  {{-- <option value="">Semua Batch</option> --}}
              </select>
          </li>
          
            <li class="dropdown-footer text-end">
              <button class="btn btn-primary apply-btn-peserta">Apply</button>
            </li>
          </ul>
        </div>

        <div class="card-body">
          <h5 class="card-title">Peserta <span></span></h5>

          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-people"></i>
            </div>
            <div class="ps-3">
              <h6 id="total-peserta">0</h6> <!-- Pastikan elemen ini memiliki ID yang benar -->
              
            </div>
            
            
          </div>

        </div>
      </div>

    </div><!-- End Customers Card -->
    <script>
 // Function to fetch the list of pelatihan
 function fetchPelatihanList() {
    fetch('/api/dashboard/pelatihan-list')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const pelatihanSelect = document.getElementById('pelatihan');
                data.pelatihan_list.forEach(pelatihan => {
                    const option = document.createElement('option');
                    option.value = pelatihan.id_pelatihan; // Use id_pelatihan as value for fetching batches
                    option.textContent = pelatihan.nama_pelatihan;
                    pelatihanSelect.appendChild(option);
                    option.setAttribute('data-nama-pelatihan', pelatihan.nama_pelatihan); // Store nama_pelatihan in a data attribute
                });
            } else {
                console.error('API Error:', data.message);
            }
        })
        .catch(error => console.error('Error fetching pelatihan list:', error));
}

// Function to fetch the batch list based on id_pelatihan
function fetchBatchList(idPelatihan) {
    if (!idPelatihan) return; // If no pelatihan is selected, do not fetch batches
    fetch(`/api/dashboard/batch-list?id_pelatihan=${idPelatihan}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const batchSelect = document.getElementById('batch');
                batchSelect.innerHTML = '<option value="">Semua Batch</option>'; // Reset batch options
                data.batch_list.forEach(batch => {
                    const option = document.createElement('option');
                    option.value = batch;
                    option.textContent = `Batch ${batch}`;
                    batchSelect.appendChild(option);
                });
            } else {
                console.error('API Error:', data.message);
            }
        })
        .catch(error => console.error('Error fetching batch list:', error));
}

// Function to fetch the total number of participants based on filters
function fetchTotalPeserta(namaPelatihan = '', batch = '') {
    const url = new URL('/api/dashboard/total-peserta', window.location.origin);

    if (namaPelatihan) {
        url.searchParams.append('nama_pelatihan', namaPelatihan);
    }

    if (batch) {
        url.searchParams.append('batch', batch);
    }

    console.log(`Fetching total peserta with URL: ${url}`); // Debugging log to check URL

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Update the total number of participants based on the filtered results
                document.getElementById('total-peserta').textContent = data.total_peserta;
                console.log('Total peserta updated:', data.total_peserta); // Debugging log
            } else {
                console.error('API Error:', data.message);
            }
        })
        .catch(error => console.error('Error fetching total peserta:', error));
}

// Event listeners
document.querySelector('.apply-btn-peserta').addEventListener('click', function () {
    const pelatihanSelect = document.getElementById('pelatihan');
    const selectedOption = pelatihanSelect.options[pelatihanSelect.selectedIndex];
    const namaPelatihan = selectedOption.getAttribute('data-nama-pelatihan'); // Get nama_pelatihan from data attribute
    const batch = document.getElementById('batch').value;

    console.log(`Applying filter with Pelatihan: ${namaPelatihan} and Batch: ${batch}`); // Debugging log

    // Call the function to fetch filtered data
    fetchTotalPeserta(namaPelatihan, batch);
});

document.getElementById('pelatihan').addEventListener('change', function () {
    const idPelatihan = this.value;
    console.log(`Selected Pelatihan ID: ${idPelatihan}`); // Debugging log
    fetchBatchList(idPelatihan);
});

// Initial data fetching
fetchPelatihanList();
fetchTotalPeserta(); // Default fetch without any filters


      </script>
      
      
      
      

    <div class="col-xxl-3 col-xl-3">

      <div class="card info-card customers-card">

        <div class="filter">
          <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow wide-dropdown">
            <li class="dropdown-header text-start">
              <h6>Filter</h6>
            </li>
            <li class="dropdown-item">
              <label for="year">Tahun:</label>
              <select id="year" class="form-select">
                
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
              </select>
            </li>
            <li class="dropdown-item d-flex justify-content-between align-items-center">
              <div>
                <label for="start-month">Bulan Awal:</label>
                <select id="start-month" class="form-select">
                  <option value="1">Januari</option>
                  <option value="2">Februari</option>
                  <option value="3">Maret</option>
                  <option value="4">April</option>
                  <option value="5">Mei</option>
                  <option value="6">Juni</option>
                  <option value="7">Juli</option>
                  <option value="8">Agustus</option>
                  <option value="9">September</option>
                  <option value="10">Oktober</option>
                  <option value="11">November</option>
                  <option value="12">Desember</option>
                </select>
              </div>
              <div>
                <label for="end-month">Bulan Akhir:</label>
                <select id="end-month" class="form-select">
                  <option value="1">Januari</option>
                  <option value="2">Februari</option>
                  <option value="3">Maret</option>
                  <option value="4">April</option>
                  <option value="5">Mei</option>
                  <option value="6">Juni</option>
                  <option value="7">Juli</option>
                  <option value="8">Agustus</option>
                  <option value="9">September</option>
                  <option value="10">Oktober</option>
                  <option value="11">November</option>
                  <option value="12">Desember</option>
                </select>
              </div>
            </li>
            <li class="dropdown-footer text-end">
              <button class="btn btn-primary apply-btn">Apply</button>
            </li>
          </ul>
        </div>
        
        

        <div class="card-body">
          <h5 class="card-title">Pelatihan <span></span></h5>
        
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-book"></i>
            </div>
            <div class="ps-3">
              <h6 id="total-pelatihan">0</h6> <!-- Tambahkan ID untuk menampilkan total pelatihan -->
              {{-- <span class="text-danger small pt-1 fw-bold">12%</span> 
              <span class="text-muted small pt-2 ps-1">decrease</span> --}}
            </div>
          </div>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk mengambil total pelatihan
            function fetchTotalPelatihan() {
                const url = '/api/dashboard/total-pelatihan';
        
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        console.log('API Response for Total Pelatihan:', data); // Log untuk memverifikasi respons API
                        if (data.status === 'success') {
                            // Update tampilan total pelatihan
                            document.getElementById('total-pelatihan').textContent = data.jumlah_pelatihan;
                        } else {
                            console.error('Error:', data.message);
                        }
                    })
                    .catch(error => console.error('Error fetching total pelatihan:', error));
            }
        
            // Panggil fungsi untuk mengambil total pelatihan saat halaman dimuat
            fetchTotalPelatihan();
        });
        </script>
        

        </div>
      </div>

    </div><!-- End Customers Card -->

  </div>

    

{{-- <div class="row"> --}}
      <!-- Left side columns -->
      <div class="col-lg-12">
        <div class="row">

          <!-- Sales Card -->


          <!-- Reports -->
          <div class="col-12">
            <div class="card">
                <!-- Filter for Months and Years -->
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow wide-dropdown" id="month-year-filter-list">
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>
                        <li class="dropdown-item">
                            <label for="year">Tahun:</label>
                            <select id="year" class="form-select">
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option> 
                            </select>
                        </li>
                        <li class="dropdown-item d-flex justify-content-between align-items-center">
                            <div>
                                <label for="start-month">Bulan Awal:</label>
                                <select id="start-month" class="form-select">
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                            <div>
                                <label for="end-month">Bulan Akhir:</label>
                                <select id="end-month" class="form-select">
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                        </li>
                        <li class="dropdown-footer text-end">
                            <button class="btn btn-primary apply-btn-bulan">Apply</button>
                        </li>
                    </ul>
        
                    <!-- Filter for Training Types -->
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" id="filter-list">
                        <!-- Dropdown filters will be dynamically generated here -->
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>
                    </ul>
                </div>
        
                <div class="card-body">
                    <h5 class="card-title">Tren Jumlah Peserta</h5>
        
                    <!-- Line Chart -->
                    <div id="reportsChart"></div>
                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            const allSeries = {}; // Ini akan menampung data dari API
                            let selectedFilters = new Set();
                            let selectedYear = new Date().getFullYear(); // Tahun default saat ini
                            let selectedStartMonth = 1; // Bulan awal default (Januari)
                            let selectedEndMonth = 12; // Bulan akhir default (Desember)
        
                            const chart = new ApexCharts(document.querySelector("#reportsChart"), {
                                series: [],
                                chart: {
                                    height: 350,
                                    type: 'area',
                                    toolbar: {
                                        show: false
                                    },
                                },
                                markers: {
                                    size: 4
                                },
                                colors: ['#4154f1', '#2eca6a', '#ff771d', '#f54291', '#42f5e6'],
                                fill: {
                                    type: "gradient",
                                    gradient: {
                                        shadeIntensity: 1,
                                        opacityFrom: 0.3,
                                        opacityTo: 0.4,
                                        stops: [0, 90, 100]
                                    }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                    curve: 'smooth',
                                    width: 2
                                },
                                xaxis: {
                                    type: 'category',
                                    categories: [] // Bulan akan diisi berdasarkan data API
                                },
                                tooltip: {
                                    x: {
                                        format: 'MM/yyyy'
                                    },
                                }
                            });
        
                            chart.render();
        
                            const updateChart = () => {
                                const series = [];
                                const filteredCategories = [];
        
                                selectedFilters.forEach(filter => {
                                    Object.keys(allSeries).forEach(key => {
                                        if (key.includes(filter) && allSeries[key]) {
                                            const filteredData = allSeries[key].filter(dataPoint => {
                                                const date = new Date(dataPoint.date); // Asumsikan dataPoint memiliki properti 'date'
                                                const year = date.getFullYear();
                                                const month = date.getMonth() + 1;
                                                return (
                                                    year === selectedYear &&
                                                    month >= selectedStartMonth &&
                                                    month <= selectedEndMonth
                                                );
                                            });
        
                                            if (filteredData.length > 0) {
                                                series.push({ name: key, data: filteredData.map(data => data.jumlah_peserta) });
                                                filteredCategories.push(...filteredData.map(data => new Date(data.date).toLocaleString('default', { month: 'short', year: 'numeric' })));
                                            }
                                        }
                                    });
                                });
        
                                chart.updateOptions({
                                    xaxis: {
                                        categories: [...new Set(filteredCategories)].sort((a, b) => new Date(a) - new Date(b))
                                    }
                                });
        
                                chart.updateSeries(series);
                            };
        
                            const fetchTrenPelatihan = () => {
                                fetch('/api/dashboard/tren-pelatihan')
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.status === 'success') {
                                            const trenData = data.tren_pelatihan;
                                            const months = new Set();
        
                                            trenData.forEach(pelatihan => {
                                                pelatihan.agenda.forEach(agenda => {
                                                    agenda.jumlah_peserta_per_bulan.forEach(bulanData => {
                                                        const monthName = new Date(bulanData.bulan).toLocaleString('default', { month: 'short', year: 'numeric' });
                                                        months.add(monthName);
        
                                                        const filterName = `${pelatihan.nama_pelatihan} Batch ${agenda.batch}`;
                                                        if (!allSeries[filterName]) {
                                                            allSeries[filterName] = [];
                                                        }
                                                        allSeries[filterName].push({ date: bulanData.bulan, jumlah_peserta: bulanData.jumlah_peserta });
                                                    });
                                                });
                                            });
        
                                            chart.updateOptions({
                                                xaxis: {
                                                    categories: Array.from(months).sort((a, b) => new Date(a) - new Date(b))
                                                }
                                            });
        
                                            updateCheckboxFilters(Object.keys(allSeries));
                                            selectedFilters = new Set(Object.keys(allSeries));
                                            updateChart();
                                        } else {
                                            console.error('Error fetching tren pelatihan:', data.message);
                                        }
                                    })
                                    .catch(error => console.error('Error fetching tren pelatihan:', error));
                            };
        
                            const updateCheckboxFilters = (filters) => {
                                const filterList = document.getElementById('filter-list');
                                filterList.innerHTML = '<li class="dropdown-header text-start"><h6>Filter</h6></li>';
                                filters.forEach(filter => {
                                    const listItem = document.createElement('li');
                                    listItem.innerHTML = `
                                        <label class="pelatihan dropdown-item">
                                            <input type="checkbox" class="filter-checkbox" data-filter="${filter}" checked>
                                            ${filter}
                                        </label>
                                    `;
                                    filterList.appendChild(listItem);
                                });
        
                                document.querySelectorAll('.filter-checkbox').forEach(item => {
                                    item.addEventListener('change', event => {
                                        const filter = event.target.getAttribute('data-filter');
                                        if (event.target.checked) {
                                            selectedFilters.add(filter);
                                        } else {
                                            selectedFilters.delete(filter);
                                        }
                                        updateChart();
                                    });
                                });
                            };
        
                            document.getElementById('year').addEventListener('change', (event) => {
                                selectedYear = parseInt(event.target.value);
                            });
        
                            document.getElementById('start-month').addEventListener('change', (event) => {
                                selectedStartMonth = parseInt(event.target.value);
                            });
        
                            document.getElementById('end-month').addEventListener('change', (event) => {
                                selectedEndMonth = parseInt(event.target.value);
                            });
        
                            document.querySelector('.apply-btn-bulan').addEventListener('click', () => {
                                updateChart();
                            });
        
                            fetchTrenPelatihan();
                        });
                    </script>
                </div>
            </div>
          </div>        
          
          <style>
            .filter-checkbox {
              margin-right: 8px;
            }
          </style>

            <div class="pieChart5Teratas d-flex justify-content-between" style="gap: 20px;">

              <!-- Card untuk Provinsi -->
              <div class="card d-flex flex-grow-1" style="flex-basis: 48%;">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"></a>
                </div>
                <div class="card-body">
                  <h5 class="card-title">5 Provinsi Teratas</h5>

                  <!-- Pie Chart -->
                  <div id="pieChartProvinsi"></div>
                </div>
              </div>

              <!-- Card untuk Kota -->
              <div class="card d-flex flex-grow-1" style="flex-basis: 48%;">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"></a>
                </div>
                <div class="card-body">
                  <h5 class="card-title">5 Kota Teratas</h5>

                  <!-- Pie Chart -->
                  <div id="pieChartKota"></div>
                </div>
              </div>

            </div>

            <!-- Script untuk Mengambil Data dari API dan Membuat Pie Chart -->
            <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
            <script>
             // Fetch data untuk 5 provinsi teratas
            axios.get('/api/dashboard/top-provinces')
              .then(response => {
                const data = response.data.top_provinces;
                const filteredData = data.filter(item => item.provinsi !== null); // Hilangkan provinsi null
                const series = filteredData.map(item => item.jumlah); // Ambil jumlah peserta
                const labels = filteredData.map(item => item.provinsi); // Ambil nama provinsi

                new ApexCharts(document.querySelector("#pieChartProvinsi"), {
                  series: series,
                  chart: {
                    height: 350,
                    type: 'pie',
                    toolbar: {
                      show: true
                    }
                  },
                  labels: labels
                }).render();
              })
              .catch(error => {
                console.error('Error fetching provinces data:', error);
              });

            // Fetch data untuk 5 kota teratas
            axios.get('/api/dashboard/top-city')
              .then(response => {
                const data = response.data.top_cities;
                const filteredData = data.filter(item => item.kab_kota !== null); // Hilangkan kota null
                const series = filteredData.map(item => item.jumlah); // Ambil jumlah peserta
                const labels = filteredData.map(item => item.kab_kota); // Ambil nama kota

                new ApexCharts(document.querySelector("#pieChartKota"), {
                  series: series,
                  chart: {
                    height: 350,
                    type: 'pie',
                    toolbar: {
                      show: true
                    }
                  },
                  labels: labels
                }).render();
              })
              .catch(error => {
                console.error('Error fetching cities data:', error);
              });

            </script>
          

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Jumlah Peserta Berdasarkan Perguruan Tinggi</h5>
              {{-- <p></p> --}}

              <!-- Table with stripped rows -->
              <table id="dataUniversitiesParticipantsTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Perguruan Tinggi</th>
                        <th>Total Peserta</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Politeknik Negeri Bandung</td>
                        <td>9958</td>
                    </tr>
                </tbody>
            </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

          

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Jumlah Peserta Berdasarkan Aktivitas</h5>
              {{-- <p></p> --}}

              <!-- Table with stripped rows -->
              <table id="dataActivityTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Aktivitas</th>
                        <th>Total Peserta</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Politeknik Negeri Bandung</td>
                        <td>9958</td>
                    </tr>
                </tbody>
            </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Jumlah Peserta Berdasarkan Perusahaan</h5>
              {{-- <p></p> --}}

              <!-- Table with stripped rows -->
              <table id="dataCompanyTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Perusahaan</th>
                        <th>Total Peserta</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Politeknik Negeri Bandung</td>
                        <td>9958</td>
                    </tr>
                </tbody>
            </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>
      

          <!-- Top Selling -->
          <div class="col-12">
            <div class="card top-selling overflow-auto">
        
                <div class="card-body pb-0">
                    <h5 class="card-title">Agenda Pelatihan</h5>
        
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">Nama Pelatihan</th>
                                <th scope="col">Batch</th>
                                <th scope="col">Start</th>
                                <th scope="col">End</th>
                                <th scope="col">Total Pendaftar</th>
                                <th scope="col">Total Peserta</th>
                            </tr>
                        </thead>
                        <tbody id="training-agenda">
                            <!-- Dynamic Data Will Be Injected Here -->
                        </tbody>
                    </table>
        
                </div>
        
            </div>
        </div><!-- End Top Selling -->
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Fetch data from the API
                axios.get('/api/dashboard/training-agenda')
                    .then(function(response) {
                        const agendaData = response.data.data;
                        const tableBody = document.getElementById('training-agenda');
                        
                        // Clear any existing content in the table body
                        tableBody.innerHTML = '';
        
                        // Loop through the data and create table rows
                        agendaData.forEach(function(item) {
                            const row = document.createElement('tr');
                            
                            row.innerHTML = `
                                <td>${item.nama_pelatihan}</td>
                                <td>${item.batch}</td>
                                <td>${new Date(item.start_date).toLocaleDateString('id-ID')}</td>
                                <td>${new Date(item.end_date).toLocaleDateString('id-ID')}</td>
                                <td>${item.total_pendaftar}</td>
                                <td>${item.total_peserta}</td>
                            `;
                            
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(function(error) {
                        console.error('Error fetching data:', error);
                    });
            });
        </script>
        

        </div>
      {{-- </div><!-- End Left side columns --> --}}


      </div><!-- End Right side columns -->
    </div>

    </div>
  </section>


@endsection
@section('scripts')

<!-- jQuery, DataTables, and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // DataTable for Jumlah Peserta Berdasarkan Perguruan Tinggi
    $('#dataUniversitiesParticipantsTable').DataTable({
        "ajax": {
            "url": "/api/dashboard/universities-participants",
            "dataSrc": "data"
        },
        "columns": [
            { "data": "nama_instansi" },
            { "data": "total_peserta" }
        ]
    });

    $('#dataActivityTable').DataTable({
        "ajax": {
            "url": "/api/dashboard/participants-by-activity",
            "dataSrc": "data"
        },
        "columns": [
            { "data": "aktivitas" },
            { "data": "total_peserta" }
        ]
    });

    $('#dataCompanyTable').DataTable({
        "ajax": {
            "url": "/api/dashboard/companies-participants",
            "dataSrc": "data"
        },
        "columns": [
            { "data": "nama_instansi" },
            { "data": "total_peserta" }
        ]
    });
});


</script>
@endsection
