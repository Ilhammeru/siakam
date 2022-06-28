{{-- begin::layouts --}}
@extends('layouts.master')

{{-- begin::styles --}}
@push('styles')
    <style>
        .card-corpse {
            height: 420px;
        }

        .card-burial {
            height: 270px;
            margin-bottom: 20px;
        }

        .ribbon {
            width: 100px;
            height: 100px;
            overflow: hidden;
            position: absolute !important;
        }
        .ribbon::before,
        .ribbon::after {
            position: absolute;
            z-index: -1;
            content: '';
            display: block;
            border: 5px solid #2980b9;
        }
        .ribbon span {
            position: absolute;
            display: block;
            width: 225px;
            padding: 5px 0;
            background-color: #00C91A;
            box-shadow: 0 5px 10px rgba(0,0,0,.1);
            color: #fff;
            font: 700 18px/1 'Lato', sans-serif;
            text-shadow: 0 1px 1px rgba(0,0,0,.2);
            text-transform: uppercase;
            text-align: center;
            font-size: 12px;
        }

        /* top left*/
        .ribbon-top-left {
            top: 0px;
            left: 0px;
        }
        .ribbon-top-left::before,
        .ribbon-top-left::after {
            border-top-color: transparent;
            border-left-color: transparent;
        }
        .ribbon-top-left::before {
            top: 0;
            right: 0;
        }
        .ribbon-top-left::after {
            bottom: 0;
            left: 0;
        }
        .ribbon-top-left span {
            right: -45px;
            top: 30px;
            transform: rotate(-45deg);
        }
    </style>
@endpush
{{-- end::styles --}}

{{-- begin::content --}}
@section('content')
    {{-- begin::card-action --}}
    <div class="card card-flush mb-4">
        <div class="card-body p-3">
            <div class="d-flex align-items justify-content-between">
                <a class="btn btn-light-primary" href="{{ route('burial-data.index') }}">
                    <i class="fas fa-chevron-left me-4"></i>
                    Kembali                   
                </a>
            </div>
        </div>
    </div>
    {{-- end::card-action --}}

    {{-- begin::corpse-data --}}
    <div class="row">
        <div class="col-md-8 mb-5">
            <div class="card card-flush card-corpse">
                <div class="card-body">

                    {{-- begin::ribbon --}}
                    @if ($funeralStatus)
                        <div class="ribbon ribbon-top-left"><span>Lengkap</span></div>
                    @endif
                    {{-- end::ribbon --}}
                    
                    <h3 class="mb-5">Data Jenazah</h3>
                    {{-- <div class="row">
                        <div class="col">
                            <div class="d-flex justify-content-between align-items-center">
                                @if ($funeralStatus)
                                    <a class="btn btn-light-info" href="{{ route('burial-data.downloadFuneralLetter', $data->id) }}">Download Surat Keterangan Pemakaman</a>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    <table class="table mt-5">
                        <tbody>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td> <b>{{ ucwords($data->name) }}</b> </td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td> <b>{{ $address }}</b> </td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td>:</td>
                                <td> <b>{{ $data->nik }}</b> </td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td> <b>{{ $data->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</b> </td>
                            </tr>
                            <tr>
                                <td>Agama</td>
                                <td>:</td>
                                <td> <b>{{ $data->religion }}</b> </td>
                            </tr>
                            <tr>
                                <td>Tanggal Wafat</td>
                                <td>:</td>
                                <td> <b>{{ $dateOfDeath }}</b> </td>
                            </tr>
                            <tr>
                                <td>Tempat, Tanggal Lahir</td>
                                <td>:</td>
                                <td> <b>{{ $regencyOfBirth . ', ' . formatIndonesiaDate(date('Y-m-d', strtotime($data->birth_date))) }}</b> </td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td>
                                    <b>{{ $data->notes ?? '-' }}</b>
                                 </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-5">
            <div class="card card-flush card-corpse">
                <div class="card-body">
                    <h3 class="mb-1">Foto Makam</h3>
                    <div class="gravePhoto d-flex align-items-center justify-content-center h-100">
                        @if ($data->grave_photo != NULL)
                        <img src="{{ asset($data->grave_photo) }}" style="width: 300px; height: auto; border-radius: 10px;" alt="">
                        @else 
                        <div class="text-center">
                            <p>Foto Makam Belum di Upload</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-flush card-burial">
                <div class="card-body">
                    <h3 class="mb-5">Data Ahli Waris</h3>
                    <table class="table mt-5">
                        <tbody>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td>{{ $data->reporters_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>No Telfon</td>
                                <td>:</td>
                                <td>{{ $data->reporters_phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td>:</td>
                                <td>{{ $data->reporters_nik ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Hubungan</td>
                                <td>:</td>
                                <td>{{ $data->reporters_relationship ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>{{ $data->reporters_address ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-flush card-burial">
                <div class="card-body">
                    <h3 class="mb-5">Data Pemakaman</h3>
                    <table class="table mt-5">
                        <tbody>
                            <tr>
                                <td>TPU / Blok</td>
                                <td>:</td>
                                <td>
                                    <b>{{ $tpuBlock }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Tipe Pemakaman</td>
                                <td>:</td>
                                <td> <b>{{ $data->burialType ? $data->burialType->name : '-' }}</b> </td>
                            </tr>
                            <tr>
                                <td>Tanggal Pemakaman</td>
                                <td>:</td>
                                <td>
                                    <b>{{ $buriedDate }}</b>
                                 </td>
                            </tr>
                            <tr>
                                <td>Koordinat Makam</td>
                                <td>:</td>
                                <td>
                                    <b>{{ $latLong }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- end::corpse-data --}}

    {{-- begin::additional-info --}}
    <div class="row">
        <div class="col">
            <div class="card card-flush">
                <div class="card-body">
                    <h3 class="mb-5">Data Persyaratan</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table mb-5">
                                <tbody>
                                    <tr>
                                        <td>Surat Permohonan</td>
                                        <td>:</td>
                                        <td>
                                            @if ($data->application_letter_photo == NULL)
                                                <span>Belum Di Upload</span>                                        
                                            @else
                                                <span style="color: #009ef7; cursor: pointer;" onclick="detailPhoto('{{ asset($data->application_letter_photo) }}', 'Surat Permohonan')">
                                                    <i class="fas fa-link me-4" style="color: #009ef7;"></i>
                                                    Lihat
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>KTP Jenazah</td>
                                        <td>:</td>
                                        <td>
                                            @if ($data->ktp_corpse_photo == NULL)
                                                <span>Belum Di Upload</span>                                        
                                            @else
                                                <span style="color: #009ef7; cursor: pointer;" onclick="detailPhoto('{{ asset($data->ktp_corpse_photo) }}', 'KTP Jenazah')">
                                                    <i class="fas fa-link me-4" style="color: #009ef7;"></i>
                                                    Lihat
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Surat Pengantar</td>
                                        <td>:</td>
                                        <td>
                                            @if ($data->cover_letter_photo == NULL)
                                                <span>Belum Di Upload</span>                                        
                                            @else
                                                <span style="color: #009ef7; cursor: pointer;" onclick="detailPhoto('{{ asset($data->cover_letter_photo) }}', 'Surat Pengantar')">
                                                    <i class="fas fa-link me-4" style="color: #009ef7;"></i>
                                                    Lihat
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>KTP Ahli Waris</td>
                                        <td>:</td>
                                        <td>
                                            @if ($data->reporter_ktp_photo == NULL)
                                                <span>Belum Di Upload</span>                                        
                                            @else
                                                <span style="color: #009ef7; cursor: pointer;" onclick="detailPhoto('{{ asset($data->reporter_ktp_photo) }}', 'KTP Ahli Waris')">
                                                    <i class="fas fa-link me-4" style="color: #009ef7;"></i>
                                                    Lihat
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>KK Ahli Waris</td>
                                        <td>:</td>
                                        <td>
                                            @if ($data->reporter_kk_photo == NULL)
                                                <span>Belum Di Upload</span>                                        
                                            @else
                                                <span style="color: #009ef7; cursor: pointer;" onclick="detailPhoto('{{ asset($data->reporter_kk_photo) }}', 'KK Ahli Waris')">
                                                    <i class="fas fa-link me-4" style="color: #009ef7;"></i>
                                                    Lihat
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Surat Keterangan Medis</td>
                                        <td>:</td>
                                        <td>
                                            @if ($data->letter_of_hospital_statement_photo == NULL)
                                                <span>Belum Di Upload</span>                                        
                                            @else
                                                <span style="color: #009ef7; cursor: pointer;" onclick="detailPhoto('{{ asset($data->letter_of_hospital_statement_photo) }}', 'Surat Keterangan Medis')">
                                                    <i class="fas fa-link me-4" style="color: #009ef7;"></i>
                                                    Lihat
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end::additional-info --}}

    {{-- begin::modal-photo --}}
    <div class="modal fade" tabindex="-1" id="modalPhoto">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" id="targetImage" style="width: 100%; height: auto;" alt="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
      </div>
    {{-- end::modal-photo --}}
@endsection
{{-- end::content --}}
{{-- end::layouts --}}

{{-- begin::scripts --}}
@push('scripts')
    <script>
        function detailPhoto(files, type) {
            $('#targetImage').attr('src', files);
            $('#modalTitle').text(type);
            $('#modalPhoto').modal('show');
        }
    </script>
@endpush
{{-- end::scripts --}}