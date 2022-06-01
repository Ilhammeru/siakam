@extends('email_template.layout')
@section('content')
    <p>Halo, <strong>{{ $senderName }}</strong></p>
    <br />
    <p style="text-align:justify;">Serial dan PIN anda berhasil di transfer kepada {{ $receiverName }}</p>
    <p style="text-align:justify;">Sisa Serial dan PIN anda sebanyak {{ $remainingSerial }} pcs. </p>
    <br />
    <br />
    <p><strong>{{ $setting->where('name', 'app_name')->first()->value }}</strong></p>
@endsection