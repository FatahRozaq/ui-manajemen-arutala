@extends('layouts.AdminLayouts')

@section('style')
<link href="{{ asset('assets/css/masterPelatihan.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="pagetitle" style="d-space-between">
    <h1>Data Pelatihan</h1>
    <a href="/form-pelatihan" class="btn btn-success">
      <i class="bi bi-plus-circle-fill" style="font-size:18px; margin-right:3px; margin-top:10px"></i>
      Tambah Pelatihan
    </a>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
      <div class="col-lg-12">
          <div class="card" style="padding: 20px">
              <div class="card-body">
                  <table id="dataPelatihanTable" class="table table-striped">
                      <thead>
                          <tr>
                              <th>Gambar</th>
                              <th>Pelatihan</th>
                              <th>Jumlah Batch</th>
                              <th>Aksi</th>
                          </tr>
                      </thead>
                      <tbody>
                          <!-- Data will be populated by DataTables -->
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
</section>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
$(document).ready(function() {
    // Fetch data using Axios
    axios.get('/api/pelatihan/daftar-pelatihan')
        .then(function(response) {
            let responseData = [];

            // Format the data according to the DataTables structure
            response.data.data.forEach(item => {
                responseData.push({
                    gambar: `<img src="/uploads/${item.gambar_pelatihan}" alt="Gambar Pelatihan" style="width: 70px; height: auto;">`,
                    pelatihan: item.nama_pelatihan,
                    jumlah_batch: item.jumlah_batch,
                    id: item.id_pelatihan
                });
            });

            // Initialize DataTables with the fetched data
            $('#dataPelatihanTable').DataTable({
                data: responseData,
                columns: [
                    { data: 'gambar', orderable: false },
                    { data: 'pelatihan' },
                    { data: 'jumlah_batch' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <a href="/pelatihan/detail-pelatihan/${row.id}" class="view-icon" title="View">
                                    <i class="fas fa-eye text-primary"></i>
                                </a>
                                <a href="/pelatihan/update-pelatihan/${row.id}" class="update-icon" data-id="${row.id}" title="Update">
                                    <i class="fas fa-edit text-warning"></i>
                                </a>
                                <a href="#" class="delete-icon" data-id="${row.id}" title="Delete">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </a>
                            `;
                        }

                    }
                ]
            });
        })
        .catch(function(error) {
            console.log('Error fetching data:', error);
        });

  
});
</script>
@endsection
