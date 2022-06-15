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
                        <th>Alamat</th>
                        <th>No. Telfon</th>
                        <th>Jumlah Makam</th>
                        <th>Jumlah Tesedia</th>
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

    {{-- begin::modal-detail-grave --}}
    <div class="modal" tabindex="-1" id="modalDetailGrave">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="row d-flex align-center justify-content-center" id="targetDetail">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group row">
                        <div class="col">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end::modal-detail-grave --}}
@endsection
{{-- begin::end --}}

@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var _columns = [{
            data: "name"
        },{
            data: "address"
        },{
            data: "phone"
        },{
            data: "grave"
        },{
            data: "available_grave"
        },{
            data: 'action'
        }];
    
        let dataTables = $("#dt_table").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: "{{ route('tpu.json') }}",
            columns: _columns,
        });

        // variable
        let form = $('#formRole');
        let elem = $('#btnSave');
        let modal = $('#modalRole');

        function save() {
            // validation
            if ($('#roleName').val() == "") {
                iziToast['error']({
                    message: `Nama Role harus terisi`,
                    position: "topRight"
                });
            } else {
                let data = $('#formRole').serialize();
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
                        document.getElementById('formRole').reset();
                    },
                    error: function(err) {
                        elem.attr('disabled', false);
                        elem.text('Simpan');
                        let message = err.responseJSON.message;
                        if (message == 'FAILED') {
                            iziToast['error']({
                                message: err.responseJSON.data.error,
                                position: "topRight"
                            });
                        } else {
                            iziToast['error']({
                                message: message,
                                position: "topRight"
                            });
                        }
                    }
                })
            }
        }

        function detailGrave(id) {
            $.ajax({
                type: "GET",
                url: "{{ url('/tpu/detail-grave') }}" + "/" + id,
                dataType: "json",
                success: function(res) {
                    console.log(res);
                    $('#modalTitle').text('Detail Makam');
                    $('#targetDetail').html(res.data.view);
                    $('#modalDetailGrave').modal('show');
                }
            })
        }

        function edit(id) {
            let url = '{{ url('/tpu/') }}' + '/' + id;

            $.ajax({
                type: "GET",
                url: "{{ url("/tpu/") }}" + "/" + id,
                dataType: 'json',
                success: function(res) {
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    form.attr('action', url);
                    form.attr('method', 'POST');
                    modal.modal('show');
                    $('#roleName').val(res.data.name);
                    $('#modalTitle').text('Edit TPU');
                },
                error: function(err) {
                    console.log(err);
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    let message = err.responseJSON.message;
                    if (message == 'FAILED') {
                        iziToast['error']({
                            message: err.responseJSON.data.error,
                            position: "topRight"
                        });
                    } else {
                        iziToast['error']({
                            message: message,
                            position: "topRight"
                        });
                    }
                }
            })
        }

        function deleteRole(id) {
            Swal.fire({
                title: 'Apakah anda yakin ingin menghapus role ini?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Ya! Hapus',
                denyButtonText: `Batalkan`,
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('/role/') }}" + "/" + id,
                        success: function(res) {
                            iziToast['success']({
                                message: 'Role berhasil di hapus',
                                position: "topRight"
                            });

                            dataTables.ajax.reload();
                        },
                        error: function(err) {
                            let message = err.responseJSON.message;
                            if (message == 'FAILED') {
                                iziToast['error']({
                                    message: err.responseJSON.data.error,
                                    position: "topRight"
                                });
                            } else {
                                iziToast['error']({
                                    message: message,
                                    position: "topRight"
                                });
                            }
                        }
                    })
                }
            })
        }
    </script>
@endpush