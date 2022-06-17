@extends('layouts.master')
{{-- begin::section --}}
@section('content')
    {{-- begin::card-action --}}
    <div class="card card-flush mb-4">
        <div class="card-body p-3">
            <div class="text-end">
                {{-- begin::button-add --}}
                <button class="btn btn-light-primary" id="btnAdd">
                    <i class="fa fa-plus me-3"></i>
                    Tambah
                </button>
                {{-- end::button-add --}}
            </div>
        </div>
    </div>
    {{-- end::card-action --}}
    {{-- begin::card-list --}}
    <div class="card card-flush">
        <div class="card-body">
            {{-- begin::table --}}
            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="dt_table">
                <!--begin::Table head-->
                <thead>
                    <!--begin::Table row-->
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th>Nama</th>
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
    <div class="modal" tabindex="-1" id="modalBurialType">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="formBurialType">
                    <div class="modal-body">
                        <div class="form-group mb-5 row">
                            <label for="burialTypeName" class="col-form-label">Nama</label>
                            <input type="text" class="form-control" id="burialTypeName" name="name" value="{{ isset($role) ? $role->name : '' }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group row">
                            <div class="col">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button class="btn btn-primary" id="btnSave" onclick="save()" type="button">Simpan</button>
                            </div>
                        </div>
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
        var _columns = [{
            data: "name"
        },{
            data: 'action'
        }];
    
        let dataTables = $("#dt_table").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [ [0, 'asc'] ],
            ajax: "{{ route('burial-type.json') }}",
            columns: _columns,
        });

        const modalBurialType = document.getElementById('modalBurialType')
        modalBurialType.addEventListener('hidden.bs.modal', event => {
            $('#btnSave').attr('disabled', false);
            $('#btnSave').text('Simpan');
            document.getElementById('formBurialType').reset();
        })

        // variable
        let form = $('#formBurialType');
        let elem = $('#btnSave');
        let modal = $('#modalBurialType');
        $('#btnAdd').on('click', function(e) {
            e.preventDefault();

            $('#modalTitle').text('Tambah Jenis Pemakaman');
            form.attr('action', "{{ route('burial-type.store') }}");
            form.attr('method', 'POST');
            modal.modal('show');
        });

        function save() {
            let data = $('#formBurialType').serialize();
            let url = form.attr('action');
            let method = form.attr('method');

            $.ajax({
                type: method,
                url: url,
                data: data,
                dataType: "json",
                beforeSend: function() {
                    elem.attr('disabled', true);
                    elem.text('Menyimpan data ...');
                },
                success: function(res) {
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    iziToast['success']({
                        message: 'Role berhasil di simpan',
                        position: "topRight"
                    });

                    modal.modal("hide");
                    dataTables.ajax.reload();
                    document.getElementById('formBurialType').reset();
                },
                error: function(err) {
                    handleError(err, elem);
                }
            });
        }

        function edit(id) {
            let url = '{{ url("/burial-type/") }}' + '/' + id;

            $.ajax({
                type: "GET",
                url: "{{ url('/burial-type/') }}" + "/" + id,
                dataType: 'json',
                success: function(res) {
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    form.attr('action', url);
                    form.attr('method', 'PUT');
                    modal.modal('show');
                    $('#burialTypeName').val(res.data.name);
                    $('#modalTitle').text('Edit Jenis Pemakaman');
                },
                error: function(err) {
                    handleError(err, elem);
                }
            })
        }

        function deleteBurialType(id) {
            Swal.fire({
                title: 'Apakah anda yakin ingin menghapus jenis ini?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Ya! Hapus',
                denyButtonText: `Batalkan`,
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('/burial-type/') }}" + "/" + id,
                        success: function(res) {
                            iziToast['success']({
                                message: 'Jenis Pemakaman berhasil di hapus',
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
    </script>
@endpush