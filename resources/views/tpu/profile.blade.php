@extends('layouts.master')
{{-- begin::style --}}
@push('styles')
    <style>
        .card-grave {
            height: 600px;
        }

        .grave-row {
            max-height: 500px;
            overflow: scroll;
        }
    </style>
@endpush
{{-- end::style --}}
@section('content')

@php
    $userImage = true;
@endphp

<div class="row" id="targetTpuGrave">
    <div class="col-md-4">
        <div class="card card-flush">
            <div class="card-body" id="targetTpu"></div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card card-flush">
            <div class="card-body" id="targetGrave"></div>
        </div>
    </div>
</div>

{{-- begin::modal-identity --}}
<div class="modal" tabindex="-1" id="modalEditTpu">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit TPU</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" id="formIdentity">
            <div class="modal-body">
                <div class="form-group mb-5 row">
                    <div class="col">
                        <label for="tpuName" class="col-form-label">Nama</label>
                        <input type="text" name="name" class="form-control" id="tpuName">
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col">
                        <label for="tpuName" class="col-form-label">Alamat</label>
                        <textarea name="address" class="form-control" id="tpuAddress" cols="3" rows="3">

                        </textarea>
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col">
                        <label for="tpuPhone" class="col-form-label">No. Telfon</label>
                        <input type="number" class="form-control" name="phone" id="tpuPhone">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" id="btnSaveIdentity" class="btn btn-primary" onclick="saveIdentity()">Simpan</button>
            </div>
        </form>
      </div>
    </div>
</div>
{{-- end::modal-identity --}}

{{-- begin::modal add tpu --}}
<div class="modal fade" tabindex="-1" id="modalAddTpu">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleTPU"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <form action="" id="formAddTpu">
                    <div class="form-group mb-5 mt-5 row rowGrave" id="rowGrave1">
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
                            <button class="btn btn-primary btn-sm" id="btnAddRowGrave" type="button" onclick="addGraveRow()">
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
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/croppie.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            init();
        });

        // begin::variable
        let buttonSave = $('#btnSave');
        let buttonSaveIdentity = $('#btnSaveIdentity');
        let _modal = $('#modalGrave');
        let _modalIdentity = $('#modalEditTpu')
        let form = $('#formGrave');
        let formIdentity = $('#formIdentity');
        let formTpu = $('#formAddTpu');
        let modalTpu = $('#modalAddTpu');
        let btnSaveTpu = $('#btnSaveTpu');
        // end::variable

        // begin::modal-event
        const _modalIdentityNative = document.getElementById('modalEditTpu');
        _modalIdentityNative.addEventListener('hidden.bs.modal', event => {
            document.getElementById('formIdentity').reset();
        });
        // end::modal-event

        function init() {
            let route = "{{ route('tpu.index') }}";
            $.ajax({
                type: "GET",
                url: route,
                beforeSend: function() {
                    let loading = `<div class="d-flex align-items-center justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        </div>`
                    $('#targetTpu').html(loading);
                    $('#targetGrave').html(loading);
                },
                success: function(res) {
                    let view = res.data.view;
                    $('#targetTpuGrave').html(view);
                },
                error: function(err) {
                    handleError(err);
                }
            })
        }

        function addTpu() {
            modalTpu.modal('show');
            formTpu.attr('method', 'POST');
            formTpu.attr('action', "{{ route('tpu.grave.store') }}"); 
            btnSaveTpu.attr('onclick', 'save()');
            $('#modalTitleTPU').text('Tambah Blok Makam');
        }

        function addGraveRow() {
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
        }

        function deleteRowGrave(idRow) {
            $('#rowGrave' + idRow).remove();
        }

        function editTpu() {
            let tpuId = "{{ $tpu->id }}";
            let urlSave = "{{ url('/tpu/identity') }}" + "/" + tpuId;
            let urlShow = "{{ url('/tpu/show') }}" + "/" + tpuId;
            $.ajax({
                type: "GET",
                url: urlShow,
                success: function(res) {
                    formIdentity.attr('action', urlSave);
                    formIdentity.attr('method', 'POST');
                    $('#tpuName').val(res.data.name);
                    $('#tpuAddress').val(res.data.address);
                    $('#tpuPhone').val(res.data.phone);
                    _modalIdentity.modal('show');
                }
            });
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
                            init();
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
                    iziToast['success']({
                        message: 'Berhasil edit data',
                        position: "topRight"
                    });
                    blockViewRow.text(res.data.grave_block);
                    quotaViewRow.text(res.data.quota);
                    init();
                    cancelEditGrave(graveId);
                }
            })
        }

        function save() {
            let data = formTpu.serialize();
            let url = formTpu.attr('action');
            let method = formTpu.attr('method');

            $.ajax({
                type: method,
                url: url,
                data: data,
                dataType: "json",
                beforeSend: function() {
                    btnSaveTpu.attr('disabled', true);
                    btnSaveTpu.text('Menyimpan data ...');
                },
                success: function(res) {
                    btnSaveTpu.attr('disabled', false);
                    btnSaveTpu.text('Simpan');
                    iziToast['success']({
                        message: 'Role berhasil di simpan',
                        position: "topRight"
                    });

                    modalTpu.modal("hide");
                    init();
                    document.getElementById('formAddTpu').reset();
                },
                error: function(err) {
                    handleError(err, btnSaveTpu)
                }
            });
        }

        function saveIdentity() {
            let data = formIdentity.serialize();
            let url = formIdentity.attr('action');
            let method = formIdentity.attr('method');
            $.ajax({
                type: method,
                url: url,
                data: data,
                beforeSend: function() {
                    buttonSaveIdentity.attr('disabled', true);
                    buttonSaveIdentity.text('Menyimpan data ...');
                },
                success: function(res) {
                    buttonSaveIdentity.attr('disabled', false);
                    buttonSaveIdentity.text('Simpan');
                    iziToast['success']({
                        message: 'Data TPU Berhasil di Simpan',
                        position: "topRight"
                    });
                    _modalIdentity.modal('hide');
                    init();
                },
                error: function(err) {
                    buttonSaveIdentity.attr('disabled', false);
                    buttonSaveIdentity.text('Simpan');
                    handleError(err);
                }
            });
        }
    </script>
@endpush

