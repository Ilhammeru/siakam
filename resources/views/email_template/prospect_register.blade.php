@extends('email_template.layout')
@section('content')
    <p>Halo, <strong>{{ $name }}</strong></p>
    <br />
    <p style="text-align:justify;">Selamat! Anda telah berhasil terdaftar sebagai calon member <strong>{{ $setting->where('name', 'app_name')->first()->value }}</strong>!</p>
    <p style="text-align:justify;">Kode registrasi Anda adalah sebagai berikut:</p>
    <h1 style="text-align:center;"><strong><?= implode(' ', str_split($registration_code)) ?></strong></h1>
    <br />
    <p style="text-align:center;">Berikut data akun Anda:</p>
    <table style="width:100%;border-spacing:0;border-collapse:collapse;margin:0 auto">
        <tbody>
            <tr>
                <td style="width:120px;">Username</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $username }}</strong></td>
            </tr>
            <tr>
                <td style="width:120px;">Password</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $password }}</strong></td>
            </tr>
        </tbody>
    </table>
    <br />
    <p>Terimakasih.</p>
    <p><strong>{{ $setting->where('name', 'app_name')->first()->value }}</strong></p>
@endsection