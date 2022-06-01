@extends('layouts.auth')

@push('styles')
    <style>
        .select2 {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1.1rem;
            font-weight: 500;
            line-height: 1.5;
            color: #181c32;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #e4e6ef;
            appearance: none;
            border-radius: 0.475rem;
            box-shadow: inset 0 1px 2px rgb(0 0 0 / 8%);
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .select2-container--default .select2-selection--single {
            border: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px;
        }
    </style>
@endpush

@section('content')
{{-- php condition --}}
@php
    $disable = true;
@endphp
{{-- end php condition --}}

<!--begin::Content-->
<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
    <!--begin::Logo-->
    <a href="{{ route('dashboard') }}" class="mb-12">
        <img alt="Logo" src="{{ asset('images/logo-1.svg') }}" class="h-40px" />
    </a>
    <!--end::Logo-->
    <!--begin::Wrapper-->
    <div class="w-lg-1000px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto body-register">
        <!--begin::Form-->
            <form method="POST" action="{{ route('register.member') }}" id="form-register">
                @csrf
                
                <!--begin::Heading-->
                <div class="text-center mb-10">
                    <!--begin::Title-->
                    <h1 class="text-dark mb-3">Register</h1>
                    <!--end::Title-->
                    <!--begin::Link-->
                    <div class="text-gray-400 fw-bold fs-4">Sudah punya akun?
                    <a href="{{ route('login') }}" class="link-primary fw-bolder">Login</a></div>
                    <!--end::Link-->
                </div>
                <!--end::Heading-->

                <div class="container" >
                    {{-- begin::alert --}}
                    @if ($isAlert)
                        {{-- condition when member do this request for his downline --}}
                        <div class="form-group">
                            <div class="alert alert-danger">Anda belum memiliki Serial dan PIN, Hubungi Stockist atau Admin untuk melanjutkan</div>
                        </div>
                    @endif
                    {{-- end::alert --}}
                    <div class="form-group">
                        <h3 class="border-bottom">Data Identitas</h3>
                    </div>
                    {{-- status verify number whatsapp --}}
                    <input type="text" hidden name="verify_whatsapp" id="verifyWhatsappInput">
                    
                    {{-- begin::admin form --}}
                    @if ($isAdmin)
                        @if (count($sponsors) > 0)
                            <div class="form-group row mb-5">
                                <label for="select_referral_username" class="col-lg-4 col-md-4 col-form-label required">Sponsor</label>
                                <div class="col-lg-8 col-md-8">
                                    <select name="referral_username" data-placeholder="- Pilih Sponsor -" class="form-select form-control" id="select_referral_username">
                                        @foreach ($sponsors as $item)
                                        <option value="{{ $item['name'] }}">{{ ucwords($item['name']) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            {{-- begin::serial pin form --}}
                            <div class="form-group row mb-5">
                                <label for="serialInput" class="col-lg-4 col-md-4 col-form-label required">Serial</label>
                                <div class="col-lg-8 col-md-8">
                                    <input type="text" id="serialInput" name="serial_user" class="form-control" readonly value="{{ $serial }}" />
                                </div>
                            </div>
                            <div class="form-group row mb-5">
                                <label for="pinInput" class="col-lg-4 col-md-4 col-form-label required">PIN</label>
                                <div class="col-lg-8 col-md-8">
                                    <input type="text" id="pinInput" name="pin_user" class="form-control" readonly value="{{ $pin }}" />
                                </div>
                            </div>
                            {{-- end::serial pin form --}}
                        @endif

                        
                        {{-- begin::member type form --}}
                        <div class="form-group row mb-5">
                            <label for="" class="col-form-label col-lg-4 col-md-4">Jenis Member</label>
                            <div class="col-lg-4 col-md-4">
                                <input type="radio" id="is_free" name="member_type" value="1" class="custom-control-input">
                                <label class="custom-control-label" for="is_free">Member Free</label>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <input type="radio" id="is_not_free" name="member_type" value="0" class="custom-control-input">
                                <label class="custom-control-label" for="is_not_free">Member Non Free</label>
                            </div>
                        </div>
                        {{-- end::member type form --}}
                    @endif
                    {{-- end::admin form --}}

                    {{-- begin::member form --}}
                    @if (!$isAdmin)
                        @if (count($sponsors) > 0)
                            <div class="form-group row mb-5">
                                <label for="inputRef" class="col-lg-4 col-md-4 col-form-label required">Sponsor</label>
                                <div class="col-lg-8 col-md-8">
                                    <input type="text" id="inputRef" name="referral_username" class="form-control" readonly value="{{ $sponsors['name'] }}" />
                                </div>
                            </div>
                        @endif

                        @if ($isDashboard)
                            {{-- begin::serial pin form where in dashboard area --}}
                            <div class="form-group row mb-5">
                                <label for="serialInput" class="col-lg-4 col-md-4 col-form-label required">Serial</label>
                                <div class="col-lg-8 col-md-8">
                                    <input type="text" id="serialInput" name="serial_user" class="form-control" readonly value="{{ $serial }}" />
                                </div>
                            </div>
                            <div class="form-group row mb-5">
                                <label for="pinInput" class="col-lg-4 col-md-4 col-form-label required">PIN</label>
                                <div class="col-lg-8 col-md-8">
                                    <input type="text" id="pinInput" name="pin_user" class="form-control" readonly value="{{ $pin }}" />
                                </div>
                            </div>
                            {{-- end::serial pin form where in dashboard area --}}
                        @endif
                    @endif
                    {{-- end::member form --}}

                    {{-- begin::hidden form --}}
                    <input type="text" hidden name="status" value="{{ $status }}">
                    {{-- end::hidden form --}}

                    <div class="form-group row mb-5">
                        <label for="inputName" class="col-lg-4 col-md-4 col-form-label required">Nama Lengkap</label>
                        <div class="col-lg-8 col-md-8">
                            <input type="text" id="inputName" name="name" class="form-control" value="{{ @old('name') }}" autofocus />
                            
                        </div>
                    </div>
                    <div class="form-group row mb-5">
                        <label for="inputNIK" class="col-lg-4 col-md-4 col-form-label required">NIK</label>
                        <div class="col-lg-8 col-md-8">
                            <input type="text" id="inputNIK" name="nik" class="form-control" value="{{ @old('nik') }}" />
                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-md-4 col-form-label required">Jenis Kelamin</label>
                        <div class="col-lg-8 col-md-8">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="inputGenderMale" value="male"  />
                                <label class="form-check-label" for="inputGenderMale">Laki-laki</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="inputGenderFemale" value="female" />
                                <label class="form-check-label" for="inputGenderFemale">Perempuan</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-5">
                        <label for="inputPhone" class="col-lg-4 col-md-4 col-form-label required">Telepon / WA</label>
                        <div class="col-lg-8 col-md-8">
                            <input type="number" id="inputPhone" name="phone" class="form-control" value="{{ @old('phone') }}" max-length="13" />
                            
                        </div>
                    </div>
                    <div class="form-group row mb-5">
                        <label for="inputEmail" class="col-lg-4 col-md-4 col-form-label required">Email</label>
                        <div class="col-lg-8 col-md-8">
                            <input type="email" id="inputEmail" name="email" class="form-control" value="{{ @old('email') }}" />
                            
                        </div>
                    </div>
                    <div class="form-group row mb-5">
                        <label for="textareaAddress" class="col-lg-4 col-md-4 col-form-label required">Alamat</label>
                        <div class="col-lg-8 col-md-8">
                            <textarea id="textareaAddress" class="form-control" name="address" rows="3"></textarea>
                            
                        </div>
                    </div>
                    <div class="form-group row mb-5">
                        <label for="selectDistrict" class="col-lg-4 col-md-4 col-form-label required">Kecamatan / Kota</label>
                        <div class="col-lg-8 col-md-8">
                            <select id="selectDistrict" data-placeholder="- Pilih Alamat -" class="form-select form-control select-district" name="district">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-5">
                        <label for="inputPostCode" class="col-lg-4 col-md-4 col-form-label required">Kode POS</label>
                        <div class="col-lg-8 col-md-8">
                            <input type="text" id="inputPostCode" name="post_code" class="form-control" value="{{ @old('post_code') }}" />
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 class="border-bottom">Data Login</h3>
                    </div>
                    <div class="form-group row mb-5">
                        <label for="inputUsername" class="col-lg-4 col-md-4 col-form-label required">Username</label>
                        <div class="col-lg-8 col-md-8">
                            <input type="text" id="inputUsername" name="username" class="form-control" value="{{ @old('username') }}" min-length="4" max-length="20" />
                            
                        </div>
                    </div>
                    <div class="form-group row mb-5">
                        <label for="inputPassword" class="col-lg-4 col-md-4 col-form-label required">Password</label>
                        <div class="col-lg-8 col-md-8">
                            <input type="password" id="inputPassword" name="password" class="form-control" value="" />
                        </div>
                    </div>
                    <div class="form-group row mb-5">
                        <label for="inputConfPassword" class="col-lg-4 col-md-4 col-form-label required">Konfirmasi Password</label>
                        <div class="col-lg-8 col-md-8">
                            <input type="password" id="inputConfPassword" name="password_confirmation" class="form-control" value="" />
                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="offset-lg-4 col-lg-9 offset-md-4 col-md-8">
                            <button type="button" {{ $disable }} class="btn btn-primary btn-lg btn-register" onclick="verify()">DAFTAR</button>
                        </div>
                    </div>
                </div>
            </form>
        <!--end::Form-->

        {{-- begin::modal confirmation email --}}
        <div class="modal fade" tabindex="-1" id="modalOtp">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">OTP</h5>
        
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <img src="{{ asset('images/times-multiply.png') }}" style="width: 20px; height: auto;" alt="">
                        </div>
                        <!--end::Close-->
                    </div>
        
                    <div class="modal-body">
                        <form action="">
                            <div class="form-group row mb-5">
                                <label for="inputOtp" class="col-form-label">Masukan Kode OTP</label>
                                <input type="text" class="form-control" id="otpLocal" name="otp" placeholder="1234">
                                <input type="text" hidden name="otp_server" id="otpServer">
                                <span class="text-danger" hidden style="font-size: 9px;" id="errorOtp">Kode OTP salah</span>
                            </div>
                        </form>
                    </div>
        
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" onclick="closeOtp()">Kembali</button>
                        <button type="button" class="btn btn-primary" onclick="verifyOtp()">Kirim</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- end::modal confirmation email --}}
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection
@push('scripts')
    <script>
        // define ajax csrf
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#selectDistrict').select2({
            ajax: {
                url: '/dtDistrict',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.district+", "+ item.regency+", "+item.province,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true,
            },
        });

        $("#select_referral_username").select2();

        function verify() {
            let elem = $('.btn-register');
            $.ajax({
                type: "POST",
                url: "{{ route('whatsapp.verify') }}",
                data: {
                    number: $('#inputPhone').val()
                },
                dataType: 'json',
                beforeSend: function() {
                    elem.attr('disabled', true);
                    elem.html('Verifikasi Nomor ...');
                },
                success: function(res) {
                    elem.attr('disabled', true);
                    let status;
                    if (res.success) {
                        status = 1;
                    } else {
                        status = 0;
                    }
                    $('#verifyWhatsappInput').val(status);

                    // verify email by by OTP
                    $.ajax({
                        type: "POST",
                        url: "{{ route('email.verify') }}",
                        data: {
                            email: $('#inputEmail').val(),
                            name: $('#inputName').val()
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            elem.html('Verifikasi Email ...');
                        },
                        success: function(res) {
                            if (!res.success) {
                                elem.html('DAFTAR');
                                elem.attr('disabled', false);
                                iziToast['error']({
                                    message: res.message,
                                    position: "topRight"
                                });
                            } else {
                                $('#otpServer').val(res.data.otp);
                                // open modal
                                $('#modalOtp').modal('show');
                            }
                        },
                        error: function(err) {
                            elem.attr('disabled', false);
                            elem.html('DAFTAR');
                            iziToast['error']({
                                message: err.responseJSON.error,
                                position: "topRight"
                            });
                        }
                    })
                },
                error: function(err) {
                    console.log('err', err);
                }
            })
        }

        function closeOtp() {
            $('#modalOtp').modal('hide');
            let elemButton = $('.btn-register');
            elemButton.attr('disabled', false);
            elemButton.html("DAFTAR");
        }

        function verifyOtp() {
            let elem = $('.btn-register');
            elem.attr('disabled', false);
            elem.html('DAFTAR');
            let local = $('#otpLocal').val();
            let server = $('#otpServer').val();
            if (local == server) {
                $('#errorOtp').attr('hidden', true);
                $('#modalOtp').modal('hide');
                $('#form-register').submit();
            } else {
                $('#errorOtp').attr('hidden', false);
            }
        }

        function confirmSerial(downlineID) {
            $.ajax({
                type: "POST",
                url: "{{ route('register.serial.generate') }}",
                data: {
                    id: downlineID
                },
                beforeSend: function() {
                    $('.button-confirm-serial').attr('disabled', true);
                    $('#confirm-button').text('Generate data ...');
                },
                error: function(err) {
                    console.log('error generate serial pin', err)
                },
                success: function(res) {
                    $('.button-confirm-serial').attr('disabled', false);
                    $('#confirm-button').text('Ya');
                    $('#serialInput').val(res.data.serial);
                    $('#pinInput').val(res.data.pin);
                }
            })
        }
        
    </script>
@endpush
