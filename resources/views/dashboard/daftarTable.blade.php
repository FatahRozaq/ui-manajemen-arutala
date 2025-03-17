<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tabel dan Kolom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .accordion-button:focus {
            box-shadow: none;
            outline: none;
        }
        .accordion-button:not(.collapsed) {
            background-color: #f8f9fa;
            color: black;
        }
        #sidebar {
            width: 250px;
            min-width: 250px;
        }
        .list-group-item {
            padding: 5px;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
</head>
<body class="p-4">
    <div class="container d-flex">
        <div class="border-end pe-3" id="sidebar">
            <h4 class="mb-3">Tabel</h4>
            <div class="accordion" id="tableAccordion"></div>
        </div>
        <div class="flex-grow-1 p-3" id="content">
            <h1 class="mb-4">Daftar Tabel dan Kolom</h1>
            <div id="loading" class="alert alert-info">Loading...</div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tableAccordion = document.getElementById("tableAccordion");
            const loading = document.getElementById("loading");

            axios.get("/api/tables")
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
                                <ul class="list-group list-group-flush" id="columns-${table}">
                                    <li class="list-group-item text-muted">Loading...</li>
                                </ul>
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
                axios.get(`/api/columns/${table}`)
                    .then(response => {
                        const columns = response.data.data;
                        const columnList = document.getElementById(`columns-${table}`);
                        columnList.innerHTML = "";
                        
                        columns.forEach(col => {
                            const listItem = document.createElement("li");
                            listItem.classList.add("list-group-item");
                            listItem.innerHTML = `<strong>${col.name}</strong> (${col.type}) ${col.nullable ? '<span class="text-muted">[Nullable]</span>' : ''}`;
                            columnList.appendChild(listItem);
                        });
                    })
                    .catch(error => {
                        console.error(`Gagal mengambil kolom untuk tabel ${table}:`, error);
                    });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>