@extends('email_template.layout')
@section('content')
    <p>Halo, <strong>{{ $name }}</strong></p>
    <br />
    <p style="text-align:justify;">Selamat, Anda Mempunyai prospect baru!</p>
    <p style="text-align:justify;">Segera hubungi admin untuk konfirmasi pembayaran prospect anda.</p>
    <br />
    <p style="text-align:justify;">Berikut detail prospect baru anda:</p>
    <table style="width:100%;border-spacing:0;border-collapse:collapse;margin:0 auto">
        <tbody>
            <tr>
                <td style="width:120px;">Nama</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $prospectName }}</strong></td>
            </tr>
            <tr>
                <td style="width:120px;">Telepon</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $prospectPhone }}</strong></td>
            </tr>
            <tr>
                <td style="width:120px;">Email</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $prospectEmail }}</strong></td>
            </tr>
            <tr>
                <td style="width:120px;">Alamat</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $prospectAddress }}</strong></td>
            </tr>
        </tbody>
    </table>
    <br />
    <p>Terimakasih.</p>
    <p><strong>{{ $setting->where('name', 'app_name')->first()->value }}</strong></p>
@endsection