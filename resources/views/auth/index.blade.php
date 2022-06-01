@extends('layouts.auth')

@section('content')
<!--begin::Content-->
<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
    <!--begin::Logo-->
    <a href="{{ route('dashboard') }}" class="mb-12">
        <img alt="Logo" src="{{ asset('images/logo-1.svg') }}" class="h-40px" />
    </a>
    <!--end::Logo-->
    <!--begin::Wrapper-->
    <div class="w-lg-700px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <!--begin::Form-->
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!--begin::Heading-->
                <div class="text-center mb-10">
                    <!--begin::Title-->
                    <p class="lead text-center mb-5">
                        Masukkan kode referral pada kolom berikut ini, lalu klik <strong>SUBMIT</strong>.<br />
                        Setelah itu Anda akan diarahkan ke formulir pendaftaran
                    </p>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->
                <div class="row justify-content-center mb-5">
                    <div class="col-md-12">
                        <input id="referral_code" type="text" class="form-control text-center @error('referral_code') is-invalid @enderror" name="referral_code" value="{{ old('referral_code') }}" required autofocus>

                        @error('referral_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="row mb-0">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Submit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection
