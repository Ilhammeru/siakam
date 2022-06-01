@extends('email_template.layout')
@section('content')
    <p>Halo, <strong>{{ $receiverName }}</strong></p>
    <br />
    <p style="text-align:justify;">Selamat, anda mendapatkan Serial dan PIN dari {{ $senderName }} sejumlah {{ $amount }} pcs.</p>
    <p style="text-align:justify;">Total Serial dan PIN anda sekarang adalah {{$total}}. </p>
    <br />
    <br />
    <p><strong>{{ $setting->where('name', 'app_name')->first()->value }}</strong></p>
@endsection