<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kelola Dashboard</title>

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/ArutalaHitam.png') }}">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    
    <!-- Template Main CSS File -->    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet">
</head>
<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="logo">
            <img src="{{ asset('assets/img/logo/ArutalaHitam.png') }}" alt="">
        </div>

        <div class="d-flex flex-column">
            <span>Dashboard ATMS</span>
            <div class="d-flex">
                <span>Halaman 1 dari 3</span>
                <span>|</span>
                <span id="menu-data">Pilih Data</span>
                <span>|</span>
                <span id="menu-visualisasi">Pilih Visualisasi</span>
            </div>
        </div>
        
    </header>

    <!-- ======= Sidebar ======= -->
    <div class="sidebar-container">
        <!-- Sidebar 1 -->
        <div id="sidebar" class="sidebar">
            
            <div class="sub-title">
                <img src="{{ asset('assets/img/icons/Storage.png') }}" alt="">
                <span class="sub-text">Data</span>
            </div>
            <hr class="full-line">
            <div class="accordion" id="tableAccordion"></div>
            <div id="loading" class="alert alert-info">Loading...</div>
        </div>
    
        <!-- Sidebar 2 -->
        <div id="sidebar-data" class="sidebar-2">
            <div class="sub-title">
                <img src="{{ asset('assets/img/icons/ChartPieOutline.png') }}" alt="">
                <span class="sub-text">Data</span>
            </div>
            <hr class="full-line">
            
            <div class="form-diagram">
                <div class="form-group">
                    <span>Dimensi</span>
                    <input type="text">
                </div>

                <div class="form-group">
                    <span>Metrik</span>
                    <input type="text">
                </div>

                <div class="form-group">
                    <span>Tanggal</span>
                    <input type="text">
                </div>

                <div class="form-group">
                    <span>Filter</span>
                    <input type="text">
                </div>
            </div>
        </div>

        <div id="sidebar-diagram" class="sidebar-2">
            <div class="sub-title">
                <img src="{{ asset('assets/img/icons/ChartPieOutline.png') }}" alt="">
                <span class="sub-text">Diagram</span>
            </div>
            <hr class="full-line">
            <div class="form-diagram">
                <div class="form-group">
                    <span>Batang</span>
                    <div class="card-row">
                        <div class="mini-card"></div>
                        <div class="mini-card"></div>
                        <div class="mini-card"></div>
                        <div class="mini-card"></div>
                    </div>
                </div>

                <div class="form-group">
                    <span>Kolom</span>
                    <div class="card-row">
                        <div class="mini-card"></div>
                        <div class="mini-card"></div>
                        <div class="mini-card"></div>
                        <div class="mini-card"></div>
                    </div>
                </div>

                <div class="form-group">
                    <span>Pie</span>
                    <div class="card-row">
                        <div class="mini-card"></div>
                        <div class="mini-card"></div>
                        <div class="mini-card"></div>
                        <div class="mini-card"></div>
                    </div>
                </div>
            </div>
        </div>

        <main class="main-container">
            <div class="canvas" id="canvas">
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Doloribus harum non necessitatibus ducimus est delectus possimus rerum, placeat recusandae accusantium incidunt? Aspernatur asperiores doloribus labore eum ipsam, laboriosam sunt. Voluptatibus. lo
            </div>
        </main>
    </div>

    
    <script>
        let scale = 1;
        const zoomSpeed = 0.005; 
        const canvas = document.getElementById('canvas');
        const mainContainer = document.querySelector('.main-container');

        mainContainer.addEventListener('wheel', (event) => {
            if (event.ctrlKey) { // Zoom hanya terjadi saat Ctrl ditekan
                event.preventDefault();
                scale += event.deltaY * -zoomSpeed;
                scale = Math.min(Math.max(0.5, scale), 3); // Batas zoom min 0.5x, max 3x
                canvas.style.transform = `scale(${scale})`;

                // Memastikan main-container bisa di-scroll dengan benar
                mainContainer.style.overflow = "auto";
            }
        });


    </script>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    
  

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tableAccordion = document.getElementById("tableAccordion");
            const loading = document.getElementById("loading");
    
            axios.get("/api/kelola-dashboard/tables")
                .then(response => {
                    const tables = response.data.data;
                    if (tables.length === 0) {
                        tableAccordion.innerHTML = "<p class='text-muted'>Tidak ada tabel tersedia.</p>";
                        loading.style.display = "none";
                        return;
                    }
                    
                    tables.forEach((table, index) => {
                        const accordionItem = document.createElement("div");
                        accordionItem.classList.add("accordion-item");
                        accordionItem.innerHTML = `
                            <h2 class="accordion-header" id="heading-${index}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${index}" aria-expanded="false" aria-controls="collapse-${index}">
                                    ${table}
                                </button>
                            </h2>
                            <div id="collapse-${index}" class="accordion-collapse collapse" aria-labelledby="heading-${index}" data-bs-parent="#tableAccordion">
                                <div class="column-container" id="columns-${table}">
                                    <p class='text-muted'>Loading...</p>
                                </div>
                            </div>
                        `;
                        tableAccordion.appendChild(accordionItem);
                        fetchColumns(table);
                    });
                    loading.style.display = "none";
                })
                .catch(error => {
                    tableAccordion.innerHTML = `<p class='text-danger'>Gagal mengambil data tabel.</p>`;
                    console.error(error);
                    loading.style.display = "none";
                });
    
            function fetchColumns(table) {
                axios.get(`/api/kelola-dashboard/columns/${table}`)
                    .then(response => {
                        const columns = response.data.data;
                        const columnContainer = document.getElementById(`columns-${table}`);
                        columnContainer.innerHTML = "";
                        
                        columns.forEach(col => {
                            const columnCard = document.createElement("div");
                            columnCard.classList.add("column-card");
                            columnCard.draggable = true;
                            columnCard.dataset.column = col.name;
                            
                            let icon = '';
                            if (col.type.includes('int') || col.type.includes('numeric') || col.type.includes('float') || col.type.includes('double') || col.type.includes('decimal')) {
                                icon = '<span class="column-icons">123</span>';
                            } else if (col.type.includes('char') || col.type.includes('text') || col.type.includes('string')) {
                                icon = '<span class="column-icons">ABC</span>';
                            } else if (col.type.includes('date') || col.type.includes('time') || col.type.includes('timestamp')) {
                                icon = '<span class="column-icons">DATE</span>';
                            } else {
                                icon = '<i class="fas fa-database column-icons"></i>'; // Default icon
                            }
    
                            columnCard.innerHTML = `
                                ${icon} ${col.name}
                            `;
    
                            columnCard.addEventListener("dragstart", event => {
                                event.dataTransfer.setData("text/plain", col.name);
                            });
    
                            columnContainer.appendChild(columnCard);
                        });
                    })
                    .catch(error => {
                        console.error(`Gagal mengambil kolom untuk tabel ${table}:`, error);
                    });
            }
        });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarData = document.getElementById("sidebar-data");
        const sidebarDiagram = document.getElementById("sidebar-diagram");

        // Pastikan hanya sidebar-data yang muncul pertama kali
        sidebarData.style.display = "block";
        sidebarDiagram.style.display = "none";

        // Tangkap tombol Pilih Data dan Pilih Visualisasi
        const pilihDataBtn = document.getElementById("menu-data");
        const pilihVisualisasiBtn = document.getElementById("menu-visualisasi");

        if (pilihDataBtn && pilihVisualisasiBtn) {
            pilihDataBtn.addEventListener("click", function() {
                sidebarData.style.display = "block";
                sidebarDiagram.style.display = "none";
            });

            pilihVisualisasiBtn.addEventListener("click", function() {
                sidebarData.style.display = "none";
                sidebarDiagram.style.display = "block";
            });
        }
    });
</script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
