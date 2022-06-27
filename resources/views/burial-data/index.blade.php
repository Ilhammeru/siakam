@extends('layouts.master')
{{-- begin::styles --}}
@push('styles')
    <style>
        #tableBurialData > thead > tr > th,
        #tableBurialData > tbody > tr > td {
            padding-left: 20px !important;
        }
    </style>
@endpush
{{-- end::styles --}}
{{-- begin::section --}}
@section('content')
    {{-- begin::card-action --}}
    <div class="card card-flush mb-4">
        <div class="card-body p-3">
            <div class="text-end">
                {{-- begin::button-add --}}
                <a class="btn btn-light-primary" href="{{route("burial-data.create")}}">
                    <i class="fa fa-plus me-3"></i>
                    Tambah
                </a>
                {{-- end::button-add --}}
            </div>
        </div>
    </div>
    {{-- end::card-action --}}
    @if (Auth::user()->role != 'tpu')
    {{-- begin::card-filter --}}
    <div class="card card-flush mb-4">
        <div class="card-body">
            {{-- begin::filter --}}
            <div class="row mb-5">
                <div class="col-md-4">
                    <label for="" class="col-form-label">Filter TPU</label>
                    <p style="color: #898989; font-size: 12px;" class="mb-0">
                        Pilih TPU yang akan di tampilkan pada tabel.
                    </p>
                    <p style="color: #898989; font-size: 12px;">Data akan otomatis berganti setiap memilih TPU</p>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-4">
                    <select name="filter_tpu" id="filterTpu" class="form-select form-control">
                        <option value="">- Pilih TPU -</option>
                        <option value="0">Semua TPU</option>
                        @foreach ($tpus as $tpu)
                            <option value="{{ $tpu->id }}">{{ $tpu->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- end::filter --}}
        </div>
    </div>
    {{-- end::card-filter --}}
    @endif
    {{-- begin::card-list --}}
    <div class="card card-flush">
        <div class="card-body">
            {{-- begin::action export --}}
            <div class="row mb-5">
                <div class="col">
                    <div class="text-end">
                        <button type="button" class="btn btn-info btn-sm" href="{{ route('burial-data.download.pdf') }}" onclick="downloadPdf()">Export PDF</button>
                    </div>
                </div>
            </div>
            {{-- end::action export --}}
            {{-- begin::table --}}
            <table class="table align-middle table-striped fs-6 gy-5 mb-0" id="tableBurialData">
                <!--begin::Table head-->
                <thead class="table-primary">
                    <!--begin::Table row-->
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th></th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Alamat</th>
                        <th>TPU</th>
                        <th>Tanggal Wafat</th>
                        <th>Tanggal Pemakaman</th>
                        <th>Nama Pelapor</th>
                        <th>Blok Makam</th>
                        <th></th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="fw-bold text-gray-600">
                    
                </tbody>
                <!--end::Table body-->
            </table>
            {{-- end::table --}}
        </div>
    </div>
    {{-- end::card-list --}}

    {{-- begin::modal --}}
    <div class="modal fade" tabindex="-1" id="modalDownloadPdf">
        <div class="modal-dialog">
          <div class="modal-content">
                <form action="{{ route('burial-data.download.pdf') }}" method="POST" id="formDownload">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Download</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row mb-2">
                            <div class="col-md-6">
                                <label for="pdfDate" class="col-form-label">Tanggal Awal</label>
                                <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="form-control" id="pdfDate">
                            </div>
                            <div class="col-md-6">
                                <label for="pdfEndDate" class="col-form-label">Tanggal Akhir</label>
                                <input type="date" name="end_date" value="{{ date('Y-m-d') }}" class="form-control" id="pdfEndDate">
                            </div>
                        </div>
                        @if (Auth::user()->role == 'admin')
                        <div class="form-group row mb-2">
                            <div class="col">
                                <label for="tpuPdf" class="col-form-label">TPU</label>
                                <select name="tpu_id" id="tpuPdf" class="form-select form-control">
                                    @foreach ($tpus as $tpu)
                                        <option value="{{ $tpu->id }}">{{ $tpu->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Download PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end::modal --}}
@endsection
{{-- begin::end --}}

@push('scripts')
    <script>
        $('#tpuPdf').select2({
            dropdownParent: $('#modalDownloadPdf')
        });

        var _columns = [{
            data: "id",
            visible: false,
        },{
            data: "name"
        },{
            data: "nik"
        },{
            data: "address",
            width: "15%"
        },{
            data: 'tpu_id'
        },{
            data: "date_of_death"
        },{
            data: "buried_date"
        },{
            data: "reporters_name"
        },{
            data: "grave_block"
        },{
            data: 'action',
            width: "10%"
        }];
    
        let dataTables = $("#tableBurialData").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [ [0, 'desc'] ],
            ajax: "{{ url('/burial-data/json/00') }}",
            columns: _columns,
        });

        $('#filterTpu').select2();

        $('#filterTpu').on('change', function(e) {
            e.preventDefault();
            let val = $(this).val();
            dataTables.destroy();
            dataTables = $("#tableBurialData").DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                scrollX: true,
                order: [ [0, 'asc'] ],
                ajax: "{{ url('/burial-data/json/') }}" + "/" + val,
                columns: _columns,
            });
        })

        function deleteBurialData(id) {
            Swal.fire({
                title: 'Apakah anda yakin ingin menghapus data ini?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Ya! Hapus',
                denyButtonText: `Batalkan`,
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('/burial-data/') }}" + "/" + id,
                        success: function(res) {
                            iziToast['success']({
                                message: 'Data Pemakaman berhasil di hapus',
                                position: "topRight"
                            });

                            dataTables.ajax.reload();
                        },
                        error: function(err) {
                            handleError(err);
                        }
                    })
                }
            })
        }

        function downloadPdf() {
            $('#modalDownloadPdf').modal('show');
        }
    </script>
@endpush