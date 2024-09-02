@extends('layouts.AdminLayouts')

@section('content')
<style>
    .dropdown-menu {
        width: 90%;
        max-height: 150px;
        overflow-y: auto;
        border-radius: 20px;
    }
    .dropdown-item:hover {
        background-color: white;
    }
  
    .button-submit {
      width: 100%;
      display: flex;
      flex: end;
      justify-content: end;
    }
  
    /* Styling for the selected item in Selectize */
    /* Background color for selected mentor item */

    .selectize-control.multi .selectize-input > .item{
        background: #e2e2e2;
        color: #000; /* Optional: White text color for better contrast */
        border-radius: 20px;
        padding-right: 25px; /* Space for the close icon */
        position: relative;
        border-color: none;
        border: none;
    }
  
    /* Styling for the remove icon */
    .selectize-control .selectize-input.items.has-items .item .remove {
        position: absolute;
        top: 50%;
        right: 8px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #000;
    }
  
    .selectize-control .selectize-input.items.has-items .item .remove:hover {
        /* color: #ff0000; Change color on hover */
    }

    
  </style>
  

<div class="pagetitle">
    <h1>Form Pelatihan</h1>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">General Form Elements</h5>

                    <!-- General Form Elements -->
                    <form>
                        <!-- Nama Pelatihan -->
                        <div class="form-group row position-relative">
                          <label for="trainingInput" class="col-sm-3 col-form-label">Nama Pelatihan</label>
                          <div class="col-sm-9">
                              <input type="text" class="form-control disable" id="trainingInput" value="Nama Pelatihan" aria-label="Disabled input example" disabled readonly>
                              <div class="dropdown-menu" id="trainingDropdown"></div>
                          </div>
                      </div>

                      <div class="form-group row position-relative">
                        <label for="trainingInput" class="col-sm-3 col-form-label">Batch</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control disable" id="trainingInput" value="2" aria-label="Disabled input example" disabled readonly>
                            <div class="dropdown-menu" id="trainingDropdown"></div>
                        </div>
                    </div>

                    <div class="form-group row position-relative">
                        <label for="trainingInput" class="col-sm-3 col-form-label">Start</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control disable" id="trainingInput">
                            <div class="dropdown-menu" id="trainingDropdown"></div>
                        </div>
                    </div>

                    <div class="form-group row position-relative">
                        <label for="trainingInput" class="col-sm-3 col-form-label">End</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control disable" id="trainingInput">
                            <div class="dropdown-menu" id="trainingDropdown"></div>
                        </div>
                    </div>

                    <div class="form-group row position-relative">
                        <label for="trainingInput" class="col-sm-3 col-form-label">Sesi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control disable" id="trainingInput">
                            <div class="dropdown-menu" id="trainingDropdown"></div>
                        </div>
                    </div>

                    <div id="materiContainer">
                        <!-- Input untuk data numerik -->
                        <div class="form-group row position-relative mb-1">
                            <label class="col-sm-3 col-form-label">Investasi (Numerik)</label>
                            <div class="col-sm-9 input-group">
                                <input type="number" class="form-control">
                                <div class="input-group-append">
                                    
                                </div>
                            </div>
                        </div>
                    
                        <!-- Input untuk data string -->
                        <div class="form-group row position-relative mb-1">
                            <label class="col-sm-3 col-form-label">Investasi (String)</label>
                            <div class="col-sm-9 input-group">
                                <input type="text" class="form-control">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-success add-materi" type="button"><i class="bi bi-plus-circle"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                      <div class="form-group row position-relative mt-3">
                        <label for="trainingInput" class="col-sm-3 col-form-label">Diskon %</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control disable" id="trainingInput">
                            <div class="dropdown-menu" id="trainingDropdown"></div>
                        </div>
                    </div>

                    <div class="form-group row position-relative">
                        <label for="trainingInput" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                        <select class="form-control" id="exampleFormControlSelect1">
                            <option>Planning</option>
                            <option>Masa Pendaftaran</option>
                            <option>Sedang Berlangsung</option>
                            <option>Selesai</option>
                          </select>
                        </div>
                    </div>

                    <div class="form-group row position-relative mt-3">
                        <label for="trainingInput" class="col-sm-3 col-form-label">Link Mayar</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="trainingInput">
                            <div class="dropdown-menu" id="trainingDropdown"></div>
                        </div>
                    </div>

                        <!-- Mentor -->
                        <div class="form-group row position-relative">
                          <label for="mentorInput" class="col-sm-3">Mentor</label>
                          <div class="col-sm-9">
                              <select id="mentorInput" class="form" multiple></select>
                          </div>
                      </div>


                        <!-- Submit -->
                        <div class="button-submit mt-4">
                          <button class="btn btn-success col-sm-3" type="button">Submit</button>
                        </div>

                    </form>
                  </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<!-- Pastikan jQuery dimuat sebelum Selectize -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>

<script>
    $(document).ready(function() {
        var mentors = [
            { id: 1, name: "John Doe" },
            { id: 2, name: "Jane Smith" },
            { id: 3, name: "Michael Johnson" },
            { id: 4, name: "Emily Davis" },
            { id: 5, name: "David Wilson" },
            { id: 6, name: "Sarah Brown" },
            { id: 7, name: "James Taylor" },
            { id: 8, name: "Laura Martinez" },
            { id: 9, name: "Robert Garcia" },
            { id: 10, name: "Sophia Rodriguez" }
        ];
  
        var selectize = $('#mentorInput').selectize({
            options: mentors,
            labelField: 'name',
            valueField: 'id',
            searchField: ['name'],
            create: false,
            placeholder: 'Pilih mentor...',
            render: {
                item: function(data, escape) {
                    return '<div class="item">' + escape(data.name) + '<span class="remove bi bi-x" style="font-size:16px"></span></div>';
                }
            },
            onItemRemove: function(value) {
                // After removing an item, trigger refresh
                var selectizeInstance = this;
                selectizeInstance.refreshOptions(false); // Refresh without rebuilding
            }
        });
  
        // Handle click event on remove icon
        $(document).on('click', '.remove', function() {
            var $item = $(this).closest('.item');
            var value = $item.attr('data-value');
            selectize[0].selectize.removeItem(value);
        });
    });

    document.addEventListener('click', function (e) {
          if (!trainingInput.contains(e.target) && !trainingDropdown.contains(e.target)) {
              trainingDropdown.classList.remove('show');
          }
      });

      // Tambah kolom baru pada Materi
      $('#materiContainer').on('click', '.add-materi', function () {
        var newMateriRow = `
            <div class="form-group row position-relative mb-1">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-9 input-group">
                    <input type="text" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary remove-materi" type="button"><i class="bi bi-dash-circle"></i></button>
                    </div>
                </div>
            </div>
        `;
        $('#materiContainer').append(newMateriRow);
    });

    // Hapus kolom materi
    $('#materiContainer').on('click', '.remove-materi', function () {
        $(this).closest('.form-group').remove();
    });

  </script>
    
@endsection
