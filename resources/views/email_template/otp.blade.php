@extends('email_template.layout')
@section('content')
    <p>Halo, <strong>{{ $name }}</strong></p>
    <br />
    <p style="text-align:justify;">Berikut adalah kode OTP Anda:</p>
    <h1 style="text-align:center;"><strong><?= implode(' ', str_split($otp)) ?></strong></h1>
    <br />
    <br />
    <p><strong>{{ $setting->where('name', 'app_name')->first()->value }}</strong></p>
@endsection