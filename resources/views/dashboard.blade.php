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
                <h5 class="card-title">Pendaftar <span>| Month</span></h5>

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
                  <option value="">Semua Batch</option>
              </select>
          </li>
          
            <li class="dropdown-footer text-end">
              <button class="btn btn-primary apply-btn">Apply</button>
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
      document.addEventListener('DOMContentLoaded', function() {
          // Mengambil daftar pelatihan dari API dan mengisi dropdown
          function fetchPelatihanList() {
              fetch('/api/dashboard/pelatihan-list')
                  .then(response => response.json())
                  .then(data => {
                      if (data.status === 'success') {
                          const pelatihanSelect = document.getElementById('pelatihan');
                          pelatihanSelect.innerHTML = '<option value="">Semua Pelatihan</option>'; // Reset dropdown
      
                          data.data.forEach(pelatihan => {
                              const option = document.createElement('option');
                              option.value = pelatihan.id_pelatihan;
                              option.textContent = pelatihan.nama_pelatihan;
                              pelatihanSelect.appendChild(option);
                          });
                      } else {
                          console.error(data.message);
                      }
                  })
                  .catch(error => console.error('Error fetching pelatihan list:', error));
          }
      
          // Mengambil daftar batch berdasarkan pelatihan yang dipilih
          function fetchBatchList(idPelatihan) {
              const url = new URL('/api/dashboard/batch-list', window.location.origin);
              url.searchParams.append('id_pelatihan', idPelatihan);
      
              fetch(url)
                  .then(response => response.json())
                  .then(data => {
                      if (data.status === 'success') {
                          const batchSelect = document.getElementById('batch');
                          batchSelect.innerHTML = '<option value="">Semua Batch</option>'; // Reset dropdown
      
                          data.data.forEach(batch => {
                              const option = document.createElement('option');
                              option.value = batch.batch;
                              option.textContent = 'Batch ' + batch.batch;
                              batchSelect.appendChild(option);
                          });
                      } else {
                          console.error(data.message);
                      }
                  })
                  .catch(error => console.error('Error fetching batch list:', error));
          }
      
          // Fungsi untuk mengambil total peserta dengan filter
          // Fungsi untuk mengambil total peserta dengan filter
          function fetchTotalPeserta(idPelatihan = '', batch = '') {
        const url = new URL('/api/dashboard/total-peserta', window.location.origin);
        
        if (idPelatihan) {
            url.searchParams.append('id_pelatihan', idPelatihan);
        }
        
        if (batch) {
            url.searchParams.append('batch', batch);
        }

        console.log('Fetching total peserta with URL:', url.toString());

        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('API Response:', data);
                if (data.status === 'success') {
                    // Mengambil jumlah total peserta berdasarkan agenda
                    let totalPeserta = 0;
                    if (data.total_peserta.length > 0) {
                        totalPeserta = data.total_peserta.reduce((acc, curr) => acc + curr.total_peserta, 0);
                    }
                    
                    document.getElementById('total-peserta').textContent = totalPeserta; // Update tampilan total peserta
                } else {
                    console.error('API Error:', data.message);
                }
            })
            .catch(error => console.error('Error fetching total peserta:', error));
    }

    document.querySelector('.apply-btn').addEventListener('click', function() {
        const idPelatihan = document.getElementById('pelatihan').value;
        const batch = document.getElementById('batch').value;
        
        console.log(`Applying filter with Pelatihan ID: ${idPelatihan} and Batch: ${batch}`);

        fetchTotalPeserta(idPelatihan, batch);
    });

    document.getElementById('pelatihan').addEventListener('change', function() {
        const idPelatihan = this.value;
        console.log(`Pelatihan changed: ${idPelatihan}`);
        fetchBatchList(idPelatihan);
    });

    fetchPelatihanList(); // Ambil daftar pelatihan saat halaman dimuat
    fetchTotalPeserta();  // Default tanpa filter
});
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
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
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
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>
                  <li>
                    <label class="pelatihan dropdown-item">
                      <input type="checkbox" class="filter-checkbox" data-filter="Pelatihan Reguler" checked>
                      Pelatihan Reguler
                    </label>
                  </li>
                  <li>
                    <label class="pelatihan dropdown-item">
                      <input type="checkbox" class="filter-checkbox" data-filter="Pelatihan Corporate" checked>
                      Pelatihan Corporate
                    </label>
                  </li>
                  <li>
                    <label class="pelatihan dropdown-item">
                      <input type="checkbox" class="filter-checkbox" data-filter="Pelatihan Kampus" checked>
                      Pelatihan Kampus
                    </label>
                  </li>
                  <li>
                    <label class="pelatihan dropdown-item">
                      <input type="checkbox" class="filter-checkbox" data-filter="Webinar" checked>
                      Webinar
                    </label>
                  </li>
                  <li>
                    <label class="pelatihan dropdown-item">
                      <input type="checkbox" class="filter-checkbox" data-filter="Sertifikasi Kampus" checked>
                      Sertifikasi Kampus
                    </label>
                  </li>
                </ul>
              </div>
          
              <div class="card-body">
                <h5 class="card-title">Tren Jumlah Peserta <span>/Year</span></h5>
          
                <!-- Line Chart -->
                <div id="reportsChart"></div>
          
                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    const allSeries = {}; // Ini akan menampung data dari API
                    let selectedFilters = new Set();
                  
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
                      selectedFilters.forEach(filter => {
                        if (allSeries[filter]) {
                          series.push({ name: filter, data: allSeries[filter] });
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
                            const months = new Set(); // Untuk mengumpulkan bulan
                  
                            trenData.forEach(pelatihan => {
                              pelatihan.agenda.forEach(agenda => {
                                agenda.jumlah_peserta_per_bulan.forEach(bulanData => {
                                  const monthName = new Date(bulanData.bulan).toLocaleString('default', { month: 'short', year: 'numeric' });
                                  months.add(monthName);
                  
                                  const filterName = `${pelatihan.nama_pelatihan} Batch ${agenda.batch}`;
                                  if (!allSeries[filterName]) {
                                    allSeries[filterName] = [];
                                  }
                                  allSeries[filterName].push(bulanData.jumlah_peserta);
                                });
                              });
                            });
                  
                            chart.updateOptions({
                              xaxis: {
                                categories: Array.from(months).sort((a, b) => new Date(a) - new Date(b))
                              }
                            });
                  
                            selectedFilters = new Set(Object.keys(allSeries));
                            updateChart();
                          } else {
                            console.error('Error fetching tren pelatihan:', data.message);
                          }
                        })
                        .catch(error => console.error('Error fetching tren pelatihan:', error));
                    };
                  
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
                  
                    document.querySelectorAll('.pelatihan').forEach(item => {
                      item.addEventListener('click', event => {
                        event.stopPropagation();
                      });
                    });
                  
                    fetchTrenPelatihan();
                  });
                  </script>
                  
                  
                <!-- End Line Chart -->
              </div>
            </div>
          </div><!-- End Reports -->
          
          <style>
            .filter-checkbox {
              margin-right: 8px;
            }
          </style>

            <div class="pieChart5Teratas d-flex justify-content-between" style="gap: 20px;">

              <!-- Card untuk Provinsi -->
              <div class="card d-flex flex-grow-1" style="flex-basis: 48%;">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
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
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
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
              document.addEventListener("DOMContentLoaded", () => {
                // Fetch data untuk 5 provinsi teratas
                axios.get('/api/dashboard/top-provinces')
                  .then(response => {
                    const data = response.data.top_provinces;
                    const series = data.map(item => item.jumlah); // Ambil jumlah peserta
                    const labels = data.map(item => item.provinsi); // Ambil nama provinsi

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
                    const series = data.map(item => item.jumlah); // Ambil jumlah peserta
                    const labels = data.map(item => item.kab_kota); // Ambil nama kota

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
              });
            </script>
          

          <!-- Recent Sales
          <div class="col-12">
            <div class="card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>

                  <li><a class="dropdown-item" >Perguruan Tinggi</a></li>
                  <li><a class="dropdown-item" >Jurusan</a></li>
                  <li><a class="dropdown-item" >Aktivitas</a></li>
                </ul>
              </div>
              <div class="card-body">
                <h5 class="card-title">Jumlah Peserta Berdasarkan Perguruan Tinggi</h5>
  
               
                <div id="pieChart"></div>
  
                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    new ApexCharts(document.querySelector("#pieChart"), {
                      series: [44, 55, 13, 43, 20, 30, 100, 80, 90, 12, 4],
                      chart: {
                        height: 350,
                        type: 'pie',
                        toolbar: {
                          show: true
                        }
                      },
                      labels: ['Politeknik Negeri Bandung', 'Universitas Gadjah Mada', 'Udayana', 'ITB','WE',' te', 'qwrq', 'wrq', 'tqwq', 'Informasi', 'IPA']
                    }).render();
                  });
                </script>
                
  
              </div>
          </div>
          </div> -->

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

              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>

                  <li><a class="dropdown-item">Today</a></li>
                  <li><a class="dropdown-item">This Month</a></li>
                  <li><a class="dropdown-item">This Year</a></li>
                </ul>
              </div>

              <div class="card-body pb-0">
                <h5 class="card-title">Agenda Pelatihan</h5>

                <table class="table table-borderless">
                  <thead>
                    <tr>
                      {{-- <th scope="col">Gambar</th> --}}
                      <th scope="col">Nama Pelatihan</th>
                      <th scope="col">Batch</th>
                      <th scope="col">Start</th>
                      <th scope="col">End</th>
                      <th scope="col">Total Pendaftar</th>
                      <th scope="col">Total Peserta</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      {{-- <th scope="row"><a href="#"><img src="assets/img/product-1.jpg" alt=""></a></th> --}}
                      <td><a href="#" class="text-primary fw-bold">SWQA</a></td>
                      <td>2</td>
                      <td >07/08/2023</td>
                      <td>21/08/2023</td>
                      <td>102</td>
                      <td>80</td>
                    </tr>
                    <tr>
                      {{-- <th scope="row"><a href="#"><img src="assets/img/product-2.jpg" alt=""></a></th> --}}
                      <td><a href="#" class="text-primary fw-bold">Digital Bootcamp-Kelas Arutala</a></td>
                      <td>2</td>
                      <td>07/08/2023</td>
                      <td>21/08/2023</td>
                      <td>102</td>
                      <td>80</td>
                    </tr>
                    <tr>
                      {{-- <th scope="row"><a href="#"><img src="assets/img/product-3.jpg" alt=""></a></th> --}}
                      <td><a href="#" class="text-primary fw-bold">Coding Express</a></td>
                      <td>1</td>
                      <td >07/08/2023</td>
                      <td>21/08/2023</td>
                      <td>102</td>
                      <td>80</td>
                    </tr>
                    <tr>
                      {{-- <th scope="row"><a href="#"><img src="assets/img/product-4.jpg" alt=""></a></th> --}}
                      <td><a href="#" class="text-primary fw-bold">CI/CD & UI Testing</a></td>
                      <td>1</td>
                      <td >07/08/2023</td>
                      <td>21/08/2023</td>
                      <td>102</td>
                      <td>80</td>
                    </tr>
                    <tr>
                      {{-- <th scope="row"><a href="#"><img src="assets/img/product-5.jpg" alt=""></a></th> --}}
                      <td><a href="#" class="text-primary fw-bold">Spring Boot</a></td>
                      <td>1</td>
                      <td >07/08/2023</td>
                      <td>21/08/2023</td>
                      <td>102</td>
                      <td>80</td>
                    </tr>
                  </tbody>
                </table>

              </div>

            </div>
          </div><!-- End Top Selling -->

        </div>
      {{-- </div><!-- End Left side columns --> --}}

      <!-- Right side columns -->
      {{-- <div class="col-lg-"> --}}

        <!-- Recent Acti6vity -->
        
        
        
        

        <!-- Budget Report -->
        <div class="card">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li class="dropdown-header text-start">
                <h6>Filter</h6>
              </li>

              <li><a class="dropdown-item" href="#">Today</a></li>
              <li><a class="dropdown-item" href="#">This Month</a></li>
              <li><a class="dropdown-item" href="#">This Year</a></li>
            </ul>
          </div>

          <div class="card-body pb-0">
            <h5 class="card-title">Budget Report <span>| This Month</span></h5>

            <div id="budgetChart" style="min-height: 400px;" class="echart"></div>

            <script>
              document.addEventListener("DOMContentLoaded", () => {
                var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
                  legend: {
                    data: ['Allocated Budget', 'Actual Spending']
                  },
                  radar: {
                    // shape: 'circle',
                    indicator: [{
                        name: 'Sales',
                        max: 6500
                      },
                      {
                        name: 'Administration',
                        max: 16000
                      },
                      {
                        name: 'Information Technology',
                        max: 30000
                      },
                      {
                        name: 'Customer Support',
                        max: 38000
                      },
                      {
                        name: 'Development',
                        max: 52000
                      },
                      {
                        name: 'Marketing',
                        max: 25000
                      }
                    ]
                  },
                  series: [{
                    name: 'Budget vs spending',
                    type: 'radar',
                    data: [{
                        value: [4200, 3000, 20000, 35000, 50000, 18000],
                        name: 'Allocated Budget'
                      },
                      {
                        value: [5000, 14000, 28000, 26000, 42000, 21000],
                        name: 'Actual Spending'
                      }
                    ]
                  }]
                });
              });
            </script>

          </div>
        </div><!-- End Budget Report -->

        <!-- Website Traffic -->
        <div class="card">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li class="dropdown-header text-start">
                <h6>Filter</h6>
              </li>

              <li><a class="dropdown-item" href="#">Today</a></li>
              <li><a class="dropdown-item" href="#">This Month</a></li>
              <li><a class="dropdown-item" href="#">This Year</a></li>
            </ul>
          </div>

          <div class="card-body pb-0">
            <h5 class="card-title">Website Traffic <span>| Today</span></h5>

            <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

            <script>
              document.addEventListener("DOMContentLoaded", () => {
                echarts.init(document.querySelector("#trafficChart")).setOption({
                  tooltip: {
                    trigger: 'item'
                  },
                  legend: {
                    top: '5%',
                    left: 'center'
                  },
                  series: [{
                    name: 'Access From',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                      show: false,
                      position: 'center'
                    },
                    emphasis: {
                      label: {
                        show: true,
                        fontSize: '18',
                        fontWeight: 'bold'
                      }
                    },
                    labelLine: {
                      show: false
                    },
                    data: [{
                        value: 1048,
                        name: 'Search Engine'
                      },
                      {
                        value: 735,
                        name: 'Direct'
                      },
                      {
                        value: 580,
                        name: 'Email'
                      },
                      {
                        value: 484,
                        name: 'Union Ads'
                      },
                      {
                        value: 300,
                        name: 'Video Ads'
                      }
                    ]
                  }]
                });
              });
            </script>

          </div>
        </div><!-- End Website Traffic -->

        <!-- News & Updates Traffic -->
        <div class="card">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li class="dropdown-header text-start">
                <h6>Filter</h6>
              </li>

              <li><a class="dropdown-item" href="#">Today</a></li>
              <li><a class="dropdown-item" href="#">This Month</a></li>
              <li><a class="dropdown-item" href="#">This Year</a></li>
            </ul>
          </div>

          <div class="card-body pb-0">
            <h5 class="card-title">News &amp; Updates <span>| Today</span></h5>

            <div class="news">
              <div class="post-item clearfix">
                <img src="assets/img/news-1.jpg" alt="">
                <h4><a href="#">Nihil blanditiis at in nihil autem</a></h4>
                <p>Sit recusandae non aspernatur laboriosam. Quia enim eligendi sed ut harum...</p>
              </div>

              <div class="post-item clearfix">
                <img src="assets/img/news-2.jpg" alt="">
                <h4><a href="#">Quidem autem et impedit</a></h4>
                <p>Illo nemo neque maiores vitae officiis cum eum turos elan dries werona nande...</p>
              </div>

              <div class="post-item clearfix">
                <img src="assets/img/news-3.jpg" alt="">
                <h4><a href="#">Id quia et et ut maxime similique occaecati ut</a></h4>
                <p>Fugiat voluptas vero eaque accusantium eos. Consequuntur sed ipsam et totam...</p>
              </div>

              <div class="post-item clearfix">
                <img src="assets/img/news-4.jpg" alt="">
                <h4><a href="#">Laborum corporis quo dara net para</a></h4>
                <p>Qui enim quia optio. Eligendi aut asperiores enim repellendusvel rerum cuder...</p>
              </div>

              <div class="post-item clearfix">
                <img src="assets/img/news-5.jpg" alt="">
                <h4><a href="#">Et dolores corrupti quae illo quod dolor</a></h4>
                <p>Odit ut eveniet modi reiciendis. Atque cupiditate libero beatae dignissimos eius...</p>
              </div>

            </div><!-- End sidebar recent posts-->

          </div>
        </div><!-- End News & Updates -->

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
