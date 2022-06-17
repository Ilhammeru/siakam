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

    {{-- begin::modal-detail-tpu --}}
    <div class="modal" tabindex="-1" id="modalDetailTpu">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="row" id="targetDetail">
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
    {{-- end::modal-detail-tpu --}}

    {{-- begin::modal add tpu --}}
    <div class="modal fade" tabindex="-1" id="modalAddTpu">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <form action="" id="formTpu">
                        <div class="form-group row mb-5">
                            <div class="col">
                                <label for="tpuName" class="col-form-label">Nama TPU</label>
                                <input type="text" placeholder="Nama TPU" name="name" class="form-control" id="tpuName">
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col">
                                <label for="tpuPhone" class="col-form-label">No. Telfon TPU</label>
                                <input type="text" placeholder="No. Telfon TPU" name="phone" class="form-control" id="tpuPhone">
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col">
                                <label for="tpuPhone" class="col-form-label">No. Telfon TPU</label>
                                <textarea name="address" id="tpuAddress" cols="3" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group mb-5 mt-5 row rowGrave" id="rowGrave1">
                            <h3 class="text-center">Data Makam</h3>
                            <div class="col-md-6">
                                <label for="tpuGraveBlock" class="col-form-label">Nama Blok</label>
                                <input type="text" name="grave_block[]" class="form-control" id="tpuGraveBlock" placeholder="Nama Block">
                            </div>
                            <div class="col-md-6">
                                <label for="tpuGraveQuota" class="col-form-label">Kuota</label>
                                <input type="text" name="quota[]" class="form-control" id="tpuGraveQuota" placeholder="Kuota Block">
                            </div>
                        </div>
                        <div class="targetFieldGrave"></div>
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-primary btn-sm" id="btnAddRowGrave">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group row">
                        <div class="col">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" id="btnSaveTpu">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end::modal add tpu --}}
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
            data: "quota"
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
        let form = $('#formTpu');
        let modalTpu = $('#modalAddTpu');
        let btnSave = $('#btnSaveTpu');

        $('#btnAdd').on('click', function(e) {
            e.preventDefault();
            modalTpu.modal('show');
            form.attr('method', 'POST');
            form.attr('action', "{{ route('tpu.grave.store') }}"); 
        });

        $('#btnAddRowGrave').on('click', function(e) {
            e.preventDefault();
            let countRow = $('.rowGrave').length;
            let form = `<div class="form-group mb-5 mt-5 row rowGrave" id="rowGrave${countRow + 1}">
                            <div class="col-md-6">
                                <label for="tpuGraveBlock" class="col-form-label">Nama Blok</label>
                                <input type="text" name="grave_block[]" class="form-control" id="tpuGraveBlock" placeholder="Nama Block">
                            </div>
                            <div class="col-md-5">
                                <label for="tpuGraveQuota" class="col-form-label">Kuota</label>
                                <input type="text" name="quota[]" class="form-control" id="tpuGraveQuota" placeholder="Kuota Block">
                            </div>
                            <div class="col-md-1">
                                <div class="text-center">
                                    <label for="tpuGraveQuota" class="col-form-label" style="color: transparent;">data</label>
                                    <i class="fas fa-times text-danger" style="cursor: pointer;" onclick="deleteRowGrave(${countRow + 1})"></i>
                                </div>
                            </div>
                        </div>`;

            $('.targetFieldGrave').append(form);
        })

        function deleteRowGrave(idRow) {
            $('#rowGrave' + idRow).remove();
        }

        function save() {
            // validation
            if ($('#roleName').val() == "") {
                iziToast['error']({
                    message: `Nama Role harus terisi`,
                    position: "topRight"
                });
            } else {
                let data = $('#formTpu').serialize();
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
                        document.getElementById('formTpu').reset();
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
                    $('#modalDetailTpu').modal('show');
                },
                error: function(err) {
                    handleError(err);
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

        function editGrave(graveId, graveBlock, quota) {
            const button = `<span class="text-info me-4" onclick="saveGrave(${graveId})"><i class="fas fa-check text-success"></i></span>
                            <span class="text-info" onclick="cancelEditGrave(${graveId})"><i class="fas fa-times text-danger"></i></span>`;
            let row = $('#editableGrave' + graveId);
            let blockEditRow = $('#editGraveBlock' + graveId);
            let quotaEditRow = $('#editGraveQuota' + graveId);
            let blockViewRow = $('#viewGraveBlock' + graveId);
            let quotaViewRow = $('#viewGraveQuota' + graveId);
            let divActionEdit = $('#actionEditGrave' + graveId);
            let divActionSave = $('#actionSaveGrave' + graveId);
            blockEditRow.attr('hidden', false);
            quotaEditRow.attr('hidden', false);
            blockViewRow.attr('hidden', true);
            quotaViewRow.attr('hidden', true);
            divActionEdit.attr('hidden', true);
            divActionSave.html(button);
            divActionSave.attr('hidden', false);
            blockEditRow.val(graveBlock);
            quotaEditRow.val(quota);
        }

        function cancelEditGrave(graveId) {
            let row = $('#editableGrave' + graveId);
            let blockEditRow = $('#editGraveBlock' + graveId);
            let quotaEditRow = $('#editGraveQuota' + graveId);
            let blockViewRow = $('#viewGraveBlock' + graveId);
            let quotaViewRow = $('#viewGraveQuota' + graveId);
            let divActionEdit = $('#actionEditGrave' + graveId);
            let divActionSave = $('#actionSaveGrave' + graveId);
            blockEditRow.attr('hidden', true);
            quotaEditRow.attr('hidden', true);
            blockViewRow.attr('hidden', false);
            quotaViewRow.attr('hidden', false);
            divActionEdit.attr('hidden', false);
            divActionSave.html('');
            divActionSave.attr('hidden', true);
        }

        function deleteGrave(graveId) {
            let row = $('#editableGrave' + graveId);
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
                        url: "{{ url('/tpu/grave') }}" + "/" + graveId,
                        dataType: "json",
                        success: function(res) {
                            iziToast['success']({
                                message: 'Berhasil menghapus data',
                                position: "topRight"
                            });
                            row.remove();
                            cancelEditGrave();
                            dataTables.ajax.reload();
                        },
                        error: function(err) {
                            handleError(err);
                        }
                    });
                }
            });
        }

        function saveGrave(graveId) {
            let blockEditRow = $('#editGraveBlock' + graveId);
            let quotaEditRow = $('#editGraveQuota' + graveId);
            let blockViewRow = $('#viewGraveBlock' + graveId);
            let quotaViewRow = $('#viewGraveQuota' + graveId);
            let blockVal = blockEditRow.val();
            let quotaVal = quotaEditRow.val();

            $.ajax({
                type: "PUT",
                url: "{{ url('/tpu/grave/') }}" + "/" + graveId,
                data: {
                    grave_block: blockVal,
                    quota: quotaVal
                },
                dataType: "json",
                error: function(err) {
                    handleError(err);
                },
                success: function(res) {
                    console.log(res);
                    iziToast['success']({
                        message: 'Berhasil edit data',
                        position: "topRight"
                    });
                    blockViewRow.text(res.data.grave_block);
                    quotaViewRow.text(res.data.quota);
                    dataTables.ajax.reload();
                    cancelEditGrave(graveId);
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