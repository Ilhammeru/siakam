@extends('email_template.layout')
@section('content')
    <p>Halo, <strong>{{ $name }}</strong></p>
    <br />
    <p style="text-align:justify;">Selamat! Anda telah bergabung dengan keluarga besar <strong>{{ $setting->where('name', 'app_name')->first()->value }}</strong>. Anda sudah dapat login ke member portal dengan menggunakan data akun Anda.</p>
    <p style="text-align:justify;"><a href="{{ route('login') }}">Klik disini</a> untuk menuju ke halaman Login. Lengkapi data diri Anda , dan jaga kerahasiaaan data akun login Anda agar tidak disalahgunakan.</p>
    <br />
    <p style="text-align:justify;">Silahkan lakukan konfirmasi bahwa alamat email ini adalah benar milik Anda dengan mengklik link berikut ini:</p>
    <p style="text-align:justify;"><a href="{{ route('confirmEmail', ['username' => $username, 'code' => $code]) }}">{{ route('confirmEmail', ['username' => $username, 'code' => $code]) }}</a></p>
    <p style="text-align:justify;">Masa berlaku link akan berakhir pada <strong><?= date('d F Y', strtotime($expire)) ?></strong> pukul <strong><?= date('H:i', strtotime($expire)) ?> WIB</strong>.</p>
    <br />
    <br />
    <p>Terimakasih.</p>
    <p><strong>{{ $setting->where('name', 'app_name')->first()->value }}</strong></p>
@endsection