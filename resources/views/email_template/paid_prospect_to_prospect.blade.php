@extends('email_template.layout')
@section('content')
    <p>Halo, <strong>{{ $name }}</strong></p>
    <br />
    <p style="text-align:justify;">Pembayaran anda telah dikonfirmasi oleh Admin.</p>
    <p style="text-align:justify;">Segera hubungi Sponsor anda untuk aktivasi akun.</p>
    <p style="text-align:justify;">Berikut detail dari sponsor anda.</p>
    <br />
    <table style="width:100%;border-spacing:0;border-collapse:collapse;margin:0 auto">
        <tbody>
            <tr>
                <td style="width:120px;">Nama</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $sponsorName }}</strong></td>
            </tr>
            <tr>
                <td style="width:120px;">Telepon</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $sponsorPhone }}</strong></td>
            </tr>
            <tr>
                <td style="width:120px;">Email</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $sponsorEmail }}</strong></td>
            </tr>
        </tbody>
    </table>
    <br />
    <br />
    <p>Terimakasih.</p>
    <p><strong>{{ $setting->where('name', 'app_name')->first()->value }}</strong></p>
@endsection