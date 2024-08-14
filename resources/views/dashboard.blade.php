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
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <li class="dropdown-header text-start">
              <h6>Filter</h6>
            </li>

            <li><a class="dropdown-item" href="#">Today</a></li>
            <li><a class="dropdown-item" href="#">This Month</a></li>
            <li><a class="dropdown-item" href="#">This Year</a></li>
          </ul>
        </div>

        <div class="card-body">
          <h5 class="card-title">Sales <span>| Today</span></h5>

          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-cart"></i>
            </div>
            <div class="ps-3">
              <h6>145</h6>
              <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>

            </div>
          </div>
        </div>
        

      </div>


    </div><!-- End Sales Card -->

    <!-- Revenue Card -->
    <div class="col-xxl-2 col-xl-3">
      <div class="card info-card revenue-card">

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

        <div class="card-body">
          <h5 class="card-title">Revenue <span>| This Month</span></h5>

          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="ps-3">
              <h6>$3,264</h6>
              <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>

            </div>
          </div>
        </div>

      </div>
    </div><!-- End Revenue Card -->

    <!-- Customers Card -->
    <div class="col-xxl-3 col-xl-3">

      <div class="card info-card customers-card">

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

        <div class="card-body">
          <h5 class="card-title">Customers <span>| This Year</span></h5>

          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-people"></i>
            </div>
            <div class="ps-3">
              <h6>1244</h6>
              <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>
            </div>
          </div>

        </div>
      </div>

    </div><!-- End Customers Card -->

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
          <h5 class="card-title">Customers <span>| This Year</span></h5>

          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-people"></i>
            </div>
            <div class="ps-3">
              <h6>1244</h6>
              <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>

            </div>
          </div>

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
                    const allSeries = {
                      'Pelatihan Reguler': [100, 120, 300, 50, 90, 400],
                      'Pelatihan Corporate': [80, 100, 130, 240, 80, 30],
                      'Pelatihan Kampus': [60, 120, 100, 60, 20, 120],
                      'Webinar': [50, 100, 50, 80, 160, 250],
                      'Sertifikasi Kampus': [40, 80, 120, 60, 20, 240],
                    };
          
                    let selectedFilters = new Set(Object.keys(allSeries));
          
                    const chart = new ApexCharts(document.querySelector("#reportsChart"), {
                      series: Object.entries(allSeries).map(([name, data]) => ({ name, data })),
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
                        categories: ["2021", "2022", "2023", "2024", "2025", "2026"]
                      },
                      tooltip: {
                        x: {
                          format: 'yyyy'
                        },
                      }
                    });
          
                    chart.render();
          
                    const updateChart = () => {
                      const series = [];
                      selectedFilters.forEach(filter => {
                        series.push({ name: filter, data: allSeries[filter] });
                      });
                      chart.updateSeries(series);
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
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>
                      Perguruan Tinggi
                    </th>
                    <th>Total Peserta</th>
                    
          
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Politeknik Negeri Bandung</td>
                    <td>9958</td>
                  </tr>
                  <tr>
                    <td>Institut Negeri Bandung</td>
                    <td>8971</td>
                  </tr>
                  <tr>
                    <td>Universitas Gadjah Mada</td>
                    <td>3147</td>
                  </tr>
                  <tr>
                    <td>Universitas Indonesia</td>
                    <td>3497</td>
                  </tr>
                  <tr>
                    <td>Blossom Dickerson</td>
                    <td>5018</td>
                  </tr>
                  <tr>
                    <td>Elliott Snyder</td>
                    <td>3925</td>
                  </tr>
                  <tr>
                    <td>Castor Pugh</td>
                    <td>9488</td>
                  </tr>
                  <tr>
                    <td>Pearl Carlson</td>
                    <td>6231</td>
                  </tr>
                  <tr>
                    <td>Deirdre Bridges</td>
                    <td>1579</td>
                  </tr>
                  <tr>
                    <td>Daniel Baldwin</td>
                    <td>6095</td>
                  </tr>
                  <tr>
                    <td>Phelan Kane</td>
                    <td>9519</td>
                  </tr>
                  <tr>
                    <td>Quentin Salas</td>
                    <td>1339</td>
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
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>
                      Aktivitas
                    </th>
                    <th>Total Peserta</th>
                   
          
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Freelance</td>
                    <td>9958</td>
                  </tr>
                  <tr>
                    <td>Karyawan</td>
                    <td>8971</td>
                  </tr>
                  <tr>
                    <td>Fresh Graduate</td>
                    <td>3147</td>
                  </tr>
                  <tr>
                    <td>Mahasiswa</td>
                    <td>3497</td>
                  </tr>
                  <tr>
                    <td>Administrasi</td>
                    <td>5018</td>
                  </tr>
                  <tr>
                    <td>Elliott Snyder</td>
                    <td>3925</td>
                  </tr>
                  <tr>
                    <td>Castor Pugh</td>
                    <td>9488</td>
                  </tr>
                  <tr>
                    <td>Pearl Carlson</td>
                    <td>6231</td>
                  </tr>
                  <tr>
                    <td>Deirdre Bridges</td>
                    <td>1579</td>
                  </tr>
                  <tr>
                    <td>Daniel Baldwin</td>
                    <td>6095</td>
                  </tr>
                  <tr>
                    <td>Phelan Kane</td>
                    <td>9519</td>
                  </tr>
                  <tr>
                    <td>Quentin Salas</td>
                    <td>1339</td>
                  </tr>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Daftar Perusahaan yang ikut</h5>
              <p></p>

              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>
                      Perusahaan
                    </th>
                    <th>Total Peserta</th>
                    
          
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Telkom Indonesia</td>
                    <td>9958</td>
                    
                  </tr>
                  <tr>
                    <td>PT Padepokan Tujuh Sembilan</td>
                    <td>8971</td>
                    
                  </tr>
                  <tr>
                    <td>Yukuensi</td>
                    <td>3147</td>
                    
                  </tr>
                  <tr>
                    <td>Mitra Inegrasi Informatika</td>
                    <td>3497</td>
                    
                  </tr>
                  <tr>
                    <td>PT Mitra Seribu Saudara</td>
                    <td>5018</td>
                    
                  </tr>
                  <tr>
                    <td>Elliott Snyder</td>
                    <td>3925</td>
                    
                  </tr>
                  <tr>
                    <td>Castor Pugh</td>
                    <td>9488</td>
                    
                  </tr>
                  <tr>
                    <td>Pearl Carlson</td>
                    <td>6231</td>
                    
                  </tr>
                  <tr>
                    <td>Deirdre Bridges</td>
                    <td>1579</td>
                    
                  </tr>
                  <tr>
                    <td>Daniel Baldwin</td>
                    <td>6095</td>
                    
                  </tr>
                  <tr>
                    <td>Phelan Kane</td>
                    <td>9519</td>
                    
                  </tr>
                  <tr>
                    <td>Quentin Salas</td>
                    <td>1339</td>
                    
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

        <div class="card col-lg-6">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <!-- <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li class="dropdown-header text-start">
                <h6>Filter</h6>
              </li>
              <li><a class="dropdown-item" href="#">Provinsi</a></li>
              <li><a class="dropdown-item" href="#">Kota</a></li>
            </ul> -->
          </div>
          <div class="card-body">
            <h5 class="card-title">5 Provinsi Teratas</h5>

            <!-- Pie Chart -->
            <div id="pieChartProvinsi"></div>

            <script>
              document.addEventListener("DOMContentLoaded", () => {
                new ApexCharts(document.querySelector("#pieChartProvinsi"), {
                  series: [44, 55, 13, 43, 22],
                  chart: {
                    height: 350,
                    type: 'pie',
                    toolbar: {
                      show: true
                    }
                  },
                  labels: ['Jawa Barat', 'JawFa Tengah', 'Jawa Timur', 'Bali', 'Banten']
                }).render();
              });
            </script>

        </div>
        </div>

        <div class="card col-lg-6">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <!-- <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li class="dropdown-header text-start">
                <h6>Filter</h6>
              </li>
              <li><a class="dropdown-item" href="#">Provinsi</a></li>
              <li><a class="dropdown-item" href="#">Kota</a></li>
            </ul> -->
          </div>
          <div class="card-body">
            <h5 class="card-title">5 Kota Teratas</h5>

            <!-- Pie Chart -->
            <div id="pieChartKota"></div>

            <script>
              document.addEventListener("DOMContentLoaded", () => {
                new ApexCharts(document.querySelector("#pieChartKota"), {
                  series: [44, 55, 13, 43, 22],
                  chart: {
                    height: 350,
                    type: 'pie',
                    toolbar: {
                      show: true
                    }
                  },
                  labels: ['Bandung', 'Jakarta', 'Surabaya', 'Denpasar', 'Jogja']
                }).render();
              });
            </script>

        </div>
        </div>
        

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
