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

{{-- begin::modal-grave --}}
<div class="modal" tabindex="-1" id="modalGrave">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Blok Makam</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" id="formGrave">
            <div class="modal-body">
                <div class="form-group row mb-5">
                    <label for="graveBlock" class="col-form-label">Blok</label>
                    <input type="text" name="grave_block" placeholder="Blok Makam" class="form-control" id="graveBlock">
                </div>
                <div class="form-group row mb-5">
                    <label for="" class="col-form-label">Status Ketersediaan</label>
                    <div class="col-md-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" checked name="status" id="graveAvailable">
                            <label class="form-check-label" for="graveAvailable">
                              Tersedia
                            </label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="status" id="graveAvailable1">
                            <label class="form-check-label" for="graveAvailable1">
                              Tidak Tersedia
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="save()" id="btnSave">Simpan</button>
            </div>
        </form>
      </div>
    </div>
  </div>
{{-- end::modal-grave --}}
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

        let buttonSave = $('#btnSave');
        let buttonSaveIdentity = $('#btnSaveIdentity');
        let _modal = $('#modalGrave');
        let _modalIdentity = $('#modalEditTpu')
        let form = $('#formGrave');
        let formIdentity = $('#formIdentity');

        // begin::modal-event
        const _modalGrave = document.getElementById('modalGrave');
        _modalGrave.addEventListener('hidden.bs.modal', event => {
            document.getElementById('formGrave').reset();
        });

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

        function addGrave() {
            let route = "{{ route('tpu.grave.store') }}";
            form.attr('action', route);
            form.attr('method', 'POST');
            _modal.modal('show');
        }

        function save() {
            let data = form.serialize();
            let url = form.attr('action');
            let method = form.attr('method');
            if ($('#graveBlock').val() == '') {
                iziToast['error']({
                    message: 'Nama Blok Harus Diisi',
                    position: "topRight"
                });
            } else {
                $.ajax({
                    type: method,
                    url: url,
                    data: data,
                    beforeSend: function() {
                        buttonSave.attr('disabled', true);
                        buttonSave.text('Menyimpan data ...');
                    },
                    success: function(res) {
                        buttonSave.attr('disabled', false);
                        buttonSave.text('Simpan');
                        iziToast['success']({
                            message: 'Blok Makam Berhasil di Simpan',
                            position: "topRight"
                        });
                        _modal.modal('hide');
                        init();
                    },
                    error: function(err) {
                        buttonSave.attr('disabled', false);
                        buttonSave.text('Simpan');
                        handleError(err);
                    }
                });
            }
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

