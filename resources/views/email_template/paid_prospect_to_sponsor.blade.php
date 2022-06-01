@extends('email_template.layout')
@section('content')
    <p>Halo, <strong>{{ $name }}</strong></p>
    <br />
    <p style="text-align:justify;">Pembayaran dari Prospect anda atas nama {{ $prospectName }} telah di konfirmasi oleh Admin.</p>
    <p style="text-align:justify;">Segera lakukan aktivasi dengan login ke akun anda</p>
    <br />
    <p>Terimakasih.</p>
    <p><strong>{{ $setting->where('name', 'app_name')->first()->value }}</strong></p>
@endsection