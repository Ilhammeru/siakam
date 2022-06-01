@extends('layouts.master')

@section('content')

@php
    $userImage = true;
@endphp

<!--begin::Card-->
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-end">
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="#" class="btn btn-light-primary">
                <!--begin::Svg Icon | path: icons/duotune/general/gen035.svg-->
                <i class="fas fa-sign-in-alt"></i>
                <!--end::Svg Icon-->Login</a>
                <!--end::Button-->
            </div>
            <!--end::Card toolbar-->
        </div>
    </div>
</div>
<!--end::Card-->

<div class="row">
    <div class="col-md-4">
        <!--begin::Card-->
        <div class="card mb-5">
            <div class="card-body">
                <!--begin::Input group-->
                <div class="d-flex justify-content-center">
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12">
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ asset('images/blank.png') }})">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-250px h-250px" style="background-image: url( {{ asset('images/blank.png') }})"></div>
                                <!--end::Preview existing avatar-->
                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ganti Foto">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <!--begin::Inputs-->
                                    <input type="file" id="inputUserImage" name="avatar" accept="image/jpeg, image/x-png" />
                                    <input type="hidden" name="avatar_remove" />
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->
                                <!--begin::Cancel-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Batal">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <!--end::Cancel-->
                                @if($userImage)
                                <!--begin::Remove-->
                                <a href="#" data-toggle="delete-profile-image">
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus Foto">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </a>
                                <!--end::Remove-->
                                @endif
                            </div>
                            <!--end::Image input-->
                            <!--begin::Hint-->
                            <div class="form-text">Tipe file yang diperbolehkan: png, jpg, jpeg.</div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Col-->
                    </div>
                </div>
                <!--end::Input group-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <div class="col-md-8">
        <!--begin::Card-->
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-between">
                        <h3>Identitas</h3>
                        <a href="#" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah</a>
                    </div>
                </div>
                <table class="table table-user">
                    <tbody>
                        <tr>
                            <td>Nama</td>
                            <td><b>User</b></td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td><b>123456789</b></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><b>user@gmail.com</b></td>
                        </tr>
                        <tr>
                            <td>HP</td>
                            <td><b>08123456677</b></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td><b>Laki - laki</b></td>
                        </tr>
                        <tr>
                            <td>Tgl Register</td>
                            <td><b>02 Mei 2022</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!--begin::Card-->
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-between">
                        <h3>Login</h3>
                        <a href="#" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah Password</a>
                    </div>
                </div>
                <table class="table table-user">
                    <tbody>
                        <tr>
                            <td>Username</td>
                            <td><b>userusername</b></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><b>********</b></td>
                        </tr>
                        <tr>
                            <td>Terakhir Login</td>
                            <td><b>02 Mei 2022</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <div class="col-md-6">
        <!--begin::Card-->
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-between">
                        <h3>Alamat</h3>
                        <a href="#" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah</a>
                    </div>
                </div>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                Jl. Kutilan </br>
                                Bandulan, Sukun, Malang</br>
                                65146
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>

<!--begin::Card-->
<div class="card mb-5">
    <div class="card-body">
        <div class="row">
            <div class="d-flex justify-content-between">
                <h3>Akun Payment</h3>
                <a href="#" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah</a>
            </div>
        </div>
        <div class="col-md-6">
            <table class="table table-user">
                <tbody>
                    <tr>
                        <td>Payment</td>
                        <td><b>Cash</b></td>
                    </tr>
                    <tr>
                        <td>Akun Payment</td>
                        <td><b>143434343434</b></td>
                    </tr>
                    <tr>
                        <td>Nama Pemilik</td>
                        <td><b>User</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--end::Card-->

<!--begin::Card-->
<div class="card mb-5">
    <div class="card-body">
        <div class="row">
            <div class="d-flex justify-content-between">
                <h3>Keluarga</h3>
                <a href="" class="btn btn-light-primary btn-sm"><i class="fas fa-user-plus"></i>Tambah</a>
            </div>
        </div>
    </div>
</div>
<!--end::Card-->

<div class="modal fade" id="userImageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body pb-5">
                <div class="image-cropper">
                    <div id="userImageCropper" style="width: 320px; height: 320px;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" data-toggle="upload-image" data-username="userusername">Terapkan</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/croppie.min.js') }}"></script>
    <script>
        var userImage = null;

        function readUserImageFile(input) {
        if (input.files && input.files[0]) {
            $('#userImageModal').modal('show');
            var reader = new FileReader();

            reader.onload = function (e) {
            setTimeout(function () {
                userImage = new Croppie(document.getElementById('userImageCropper'), {
                viewport: {
                    width: 240,
                    height: 240,
                    type: 'square'
                },
                boundary: {
                    width: 320,
                    height: 320
                },
                url: e.target.result,
                enableExif: true
                });
            }, 500);
            };

            reader.readAsDataURL(input.files[0]);
        }
        }

        $('#inputUserImage').on('change', function () {
            readUserImageFile(this);
        });
        $('#userImageModal').on('hide.bs.modal', function (e) {
            userImage.destroy();
            $('#inputUserImage').val('');
        });
        $('#userImageModal [data-toggle="crop-image"]').on('click', function (e) {
            userImage.result({
                type: 'base64',
                format: 'jpeg',
                size: {
                width: 320,
                height: 320
                }
            }).then(function (resp) {
                $('#userImagePreview img').attr({
                    src: resp,
                    'data-upload': true,
                    'data-filename': $('#inputUserImage')[0].files[0].name
                });
                $('[data-toggle="reset-user-image"]').removeClass('d-none');
                $('#userImageModal').modal('hide');
            });
        });

        function resetUserImage() {
            var $imgTag = $('#userImagePreview img');
            $imgTag.attr({
                src: $imgTag.data('original'),
                'data-upload': false,
                'data-filename': '',
                'data-delete': false
            });
            $('[data-toggle="reset-user-image"]').addClass('d-none');
            $('[data-toggle="delete-user-image"]').removeClass('d-none');
        }

        function deleteUserImage() {
            var $imgTag = $('#userImagePreview img');
            $imgTag.attr({
                src: $imgTag.data('placeholder'),
                'data-upload': false,
                'data-filename': '',
                'data-delete': true
            });
            $('[data-toggle="reset-user-image"]').removeClass('d-none');
            $('[data-toggle="delete-user-image"]').addClass('d-none');
        }

        $('[data-toggle="reset-user-image"]').click(resetUserImage);
        $('[data-toggle="delete-user-image"]').click(deleteUserImage);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#userImageModal [data-toggle="upload-image"]').on('click', function (e) {
            var $this = $(this);
            userImage.result({
                type: 'blob',
                format: 'jpeg',
                size: {
                width: 320,
                height: 320
                }
            }).then(function (blob) {
                var formData = new FormData();
                formData.append('user_image', blob, $('#inputUserImage')[0].files[0].name);
                $.ajax({
                url:  "",
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                dataType: 'json',
                error: function error(response) {
                    if (response.responseJSON.message) {
                        iziToast['error']({
                            message: response.responseJSON.message,
                            position: "topRight"
                        });
                    }
                },
                success: function success(response) {
                    Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Data berhasil disimpan'
                    }).then(function (result) {
                    window.location.reload();
                    });
                },
                });
                $('#userImageModal').modal('hide');
            });
        });

        $('[data-toggle="delete-profile-image"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            Swal.fire({
                title: "Hapus gambar ini?",
                text: "Gambar akan dihapus selamanya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batalkan',
                customClass: {
                confirmButton: 'btn btn-danger mr-2',
                cancelButton: 'btn btn-secondary ml-2'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (result.isConfirmed) {
                $.ajax({
                    url: $this.attr('href'),
                    method: 'POST',
                    dataType: 'json',
                    error: function error(response) {
                        if (response.responseJSON.message) {
                            iziToast['error']({
                                message: response.responseJSON.message,
                                position: "topRight"
                            });
                        }
                    },
                    success: function success(data, status, xhr) {
                    window.location.reload();
                    },
                });
                }
                if(result.isDismissed){
                    window.location.reload();
                }
            });
        });
    </script>
@endpush

