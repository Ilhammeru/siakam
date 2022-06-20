@extends('layouts.master')
{{-- begin::styles --}}
@push('styles')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<link
    href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
    rel="stylesheet"
/>
@endpush
{{-- end::styles --}}
{{-- begin::content --}}
@section('content')
    {{-- begin::card-action --}}
    <div class="card card-flush mb-4">
        <div class="card-body p-3">
            <div class="text-start">
                <a class="btn btn-light-primary" href="{{ route('burial-data.index') }}">
                    <i class="fas fa-chevron-left me-4"></i>
                    Kembali                   
                </a>
            </div>
        </div>
    </div>
    {{-- end::card-action --}}

    {{-- begin::card-form --}}
    <div class="card card-flush">
        <div class="card-body">
            <h3 class="mb-5 text-center">Data Pemakaman</h3>
            <form action="{{ route('burial-data.store') }}" id="formBurialData" method="POST" enctype="multipart/form-data">
                <div class="form-group row mb-5">
                    <div class="col-md-6">
                        <label for="burialId" class="col-form-label">No Pemakaman</label>
                        <input type="text" name="burial_data_id" class="form-control" id="burialId" value="{{ $number }}">
                    </div>
                </div>
                <div class="form-group row mb-5">
                    <div class="col-md-6 col-xl-6">
                        <label for="name" class="col-form-label required">Nama</label>
                        <input type="text" name="name" placeholder="Nama Jenazah" class="form-control" id="name">
                    </div>
                    <div class="col-md-6 col-xl-6">
                        <label for="nik" class="col-form-label required">NIK</label>
                        <input type="number" name="nik" placeholder="NIK Jenazah" class="form-control" id="name">
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col-md-6">
                        <label for="regencyOfBirth" class="col-form-label required">Tempat Lahir</label>
                        <select name="regency_of_birth" id="regencyOfBirth" class="form-control">
                            <option value="">- Pilih Kota -</option>
                            @foreach ($city as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="dateOfBirth" class="col-form-label required">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" class="form-control" id="dateOfBirth">
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col-md-6">
                        <label for="address" class="col-form-label required">Alamat</label>
                        <textarea name="address" class="form-control" id="address" cols="1" rows="1"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label for="addressVilage" class="col-form-label required">Kota</label>
                        <select name="village_id" id="addressVilage" class="form-control">
                            <option value="">- Pilih Kota -</option>
                            @foreach ($city as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="addressRt" class="col-form-label required">RT</label>
                        <input type="number" name="rt" class="form-control" id="addressRt" placeholder="RT">
                    </div>
                    <div class="col-md-1">
                        <label for="addressRw" class="col-form-label required">RW</label>
                        <input type="number" name="rw" class="form-control" id="addressRw" placeholder="RW">
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col-md-6">
                        <label for="reporterName" class="col-form-label">Nama Ahli Waris</label>
                        <input name="reporter_name" class="form-control" id="reporterName" placeholder="Nama Ahli Waris" type="text" />
                    </div>
                    <div class="col-md-6">
                        <label for="reporterNik" class="col-form-label">NIK Ahli Waris</label>
                        <input name="reporter_nik" class="form-control" id="reporterNik" placeholder="NIK Ahli Waris" type="text" />
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col-md-6">
                        <label for="placeOfDeath" class="col-form-label">Lokasi Wafat</label>
                        <input type="text" name="place_of_death" placeholder="Lokasi Wafat" class="form-control" id="placeOfDeath">
                    </div>
                    <div class="col-md-6">
                        <label for="dateOfDeath" class="col-form-label">Tanggal Wafat</label>
                        <input type="date" name="date_of_death" class="form-control" id="dateOfDeath">
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col-md-6">
                        <label for="buriedDate" class="col-form-label">Tanggal Pemakaman</label>
                        <input type="date" name="burial_date" class="form-control" id="buriedDate">
                    </div>
                    <div class="col-md-6">
                        <label for="burialType" class="col-form-label">Jenis Pemakaman</label>
                        <select name="burial_type_id" id="burialType" class="form-control">
                            <option value="">- Pilih Jenis -</option>
                            @foreach ($burialTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    @if (Auth::user()->role != 'tpu')
                    <div class="{{ Auth::user()->role == 'tpu' ? 'col-md-6' : 'col-md-4' }}">
                        <label for="tpuId" class="col-form-label">TPU</label>
                        <select name="tpu_id" id="tpuId" class="form-control" onchange="getGrave()">
                            <option value="">- Pilih TPU -</option>
                            @foreach ($tpus as $tpu)
                                <option value="{{ $tpu->id }}">{{ $tpu->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="{{ Auth::user()->role == 'tpu' ? 'col-md-6' : 'col-md-4' }}">
                        <label for="graveBlock" class="col-form-label">Blok Makam</label>
                        <select name="grave_block" id="graveBlock" class="form-control" onchange="blockOnChange()">
                            <option value="">- Pilih Blok -</option>
                            @foreach ($graveBlocks as $block)
                                <option value="{{ $block->id }}">{{ $block->grave_block }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="{{ Auth::user()->role == 'tpu' ? 'col-md-6' : 'col-md-4' }}">
                        <label for="graveNumber" class="col-form-label">Nomor Makam</label>
                        <input type="number" name="grave_number" placeholder="Nomor Makam" class="form-control" id="graveNumber">
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col">
                        <label for="notes" class="col-form-label">Keterangan</label>
                        <textarea name="notes" id="notes" cols="3" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col-md-6">
                        <label for="gravePhoto" class="col-form-label">Foto Makam</label>
                        <input type="file" name="grave_photo" class="form-control" id="gravePhoto">
                    </div>
                    <div class="col-md-6">
                        <label for="longLatGrave" class="col-form-label">Lokasi</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="lat_long" id="longLatGrave" placeholder="Lokasi Makam" aria-label="Lokasi Makam" aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2" style="cursor: pointer;" onclick="getLocation()">Dapatkan Lokasi</span>
                        </div>
                    </div>
                </div>

                {{-- begin::data-requirement --}}
                <div class="row mt-5 mb-5">
                    <div class="col">
                        <h3 class="text-center">Data Persyaratan</h3>
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col-md-3">
                        <label for="applicationLetter" class="col-form-label">Surat Permohonan</label>
                        <p style="color: #898989; font-size: 12px;">Format gambar .jpg .jpeg .png .pdf dan ukuran minimum 3Mb</p>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                        <input type="file" name="application_letter" class="form-control" id="applicationLetter">
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col-md-3">
                        <label for="ktpCorpse" class="col-form-label">KTP Jenazah</label>
                        <p style="color: #898989; font-size: 12px;">Format gambar .jpg .jpeg .png .pdf dan ukuran minimum 3Mb</p>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                        <input type="file" name="ktp_corpse" class="form-control" id="ktpCorpse">
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col-md-3">
                        <label for="coverLetter" class="col-form-label">Surat Pengantar</label>
                        <p style="color: #898989; font-size: 12px;">Format gambar .jpg .jpeg .png .pdf dan ukuran minimum 3Mb</p>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                        <input type="file" name="cover_letter" class="form-control" id="coverLetter">
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col-md-3">
                        <label for="reporterKtpPhoto" class="col-form-label">Foto KTP Pelapor</label>
                        <p style="color: #898989; font-size: 12px;">Format gambar .jpg .jpeg .png .pdf dan ukuran minimum 3Mb</p>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                        <input type="file" name="rerporter_ktp" class="form-control" id="reporterKtpPhoto">
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col-md-3">
                        <label for="reporterKkPhoto" class="col-form-label">Foto KK Pelapor</label>
                        <p style="color: #898989; font-size: 12px;">Format gambar .jpg .jpeg .png .pdf dan ukuran minimum 3Mb</p>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                        <input type="file" name="rerporter_kk" class="form-control" id="reporterKkPhoto">
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col-md-3">
                        <label for="hospitalStatement" class="col-form-label">Foto Surat Keterangan RS</label>
                        <p style="color: #898989; font-size: 12px;">Format gambar .jpg .jpeg .png .pdf dan ukuran minimum 3Mb</p>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                        <input type="file" name="letter_of_hospital_statement" class="form-control" id="hospitalStatement">
                    </div>
                </div>
                <div class="form-group mb-5 row">
                    <div class="col">
                        <div class="text-end">
                            <button class="btn btn-primary" type="button" id="btnSave" onclick="save()">Simpan</button>
                        </div>
                    </div>
                </div>
                {{-- end::data-requirement --}}
            </form>
        </div>
    </div>
    {{-- end::card-form --}}
@endsection
{{-- end::content --}}

{{-- begin::script --}}
@push('scripts')
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            // FilePondPluginImageExifOrientation,
            // FilePondPluginFileValidateSize,
            // FilePondPluginImageEdit
        );

        // Select the file input and use 
        // create() to turn it into a pond
        const pondGrave = FilePond.create(
            document.getElementById('gravePhoto')
        );
        const pondApplicationLetter = FilePond.create(
            document.getElementById('applicationLetter')
        );
        const pondKtpCorpse = FilePond.create(
            document.getElementById('ktpCorpse')
        );
        const pondCoverLetter = FilePond.create(
            document.getElementById('coverLetter')
        );
        const pondReporterKtp = FilePond.create(
            document.getElementById('reporterKtpPhoto')
        );
        const pondReporterKk = FilePond.create(
            document.getElementById('reporterKkPhoto')
        );
        const pondHospitalStatement = FilePond.create(
            document.getElementById('hospitalStatement')
        );

        $('#regencyOfBirth').select2();
        $('#burialType').select2();
        $('#graveBlock').select2();
        $('#addressVilage').select2();
        $('#tpuId').select2();

        // begin::variable
        let form = $('#formBurialData');
        let btnSave = $('#btnSave');
        // end::variable

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else { 
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            let lat = position.coords.latitude;
            let long = position.coords.longitude;
            let longLat = `${lat},${long}`;
            $('#longLatGrave').val(longLat);
        }

        function getGrave() {
            let val = $('#tpuId').val();
            $.ajax({
                type: "GET",
                url: "{{ url('/tpu/show') }}" + "/" + val,
                dataType: "json",
                success: function(res) {
                    let option = "<option value=''>- Pilih Blok -</option>";
                    let data = res.data.graves;
                    for (let a = 0; a < data.length; a++) {
                        option += `<option value="${data[a].id}">${data[a].grave_block}</option>`;
                    }
                    $('#graveBlock').html(option);
                }
            })
        }

        function save() {
            let data = new FormData($('#formBurialData')[0]);
            let method = form.attr('method');
            let url = form.attr('action');

            // handle photo
            // grave file
            let graveFile = pondGrave.getFile();
            if (graveFile) {
                graveFile = graveFile.file;
                data.append('photo[0][grave_photo]', graveFile);
            }
            // application letter file
            let applicationFile = pondApplicationLetter.getFile();
            if (applicationFile) {
                applicationFile = applicationFile.file;
                data.append('photo[1][application_letter_photo]', applicationFile);
            }
            // ktp corpse file
            let ktpCorpseFile = pondKtpCorpse.getFile();
            if (ktpCorpseFile) {
                ktpCorpseFile = ktpCorpseFile.file;
                data.append('photo[2][ktp_corpse_photo]', ktpCorpseFile);
            }
            // cover letter file
            let converLetterFile = pondCoverLetter.getFile();
            if (converLetterFile) {
                converLetterFile = converLetterFile.file;
                data.append('photo[3][cover_letter_photo]', converLetterFile);
            }
            // reporter KTP file
            let reporterKtpFile = pondReporterKtp.getFile();
            if (reporterKtpFile) {
                reporterKtpFile = reporterKtpFile.file;
                data.append('photo[4][reporter_ktp_photo]', reporterKtpFile);
            }
            // reporter KK file
            let reporterKkFile = pondReporterKk.getFile();
            if (reporterKkFile) {
                reporterKkFile = reporterKkFile.file;
                data.append('photo[5][reporter_kk_photo]', reporterKkFile);
            }
            // letter of hospital statement file
            let letterOfHospitalFile = pondHospitalStatement.getFile();
            if (letterOfHospitalFile) {
                letterOfHospitalFile = letterOfHospitalFile.file;
                data.append('photo[6][letter_of_hospital_statement_photo]', letterOfHospitalFile);
            }

            $.ajax({
                type: method,
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                beforeSend: function() {
                    btnSave.attr('disabled', true);
                    btnSave.text('Menyimpan Data ...');
                },
                success: function(res) {
                    console.log(res);
                    btnSave.attr('disabled', false);
                    btnSave.text('Simpan');
                    window.location.href = "{{ route('burial-data.create') }}";
                    iziToast['success']({
                        message: 'Berhasil Menyimpan Data',
                        position: "topRight"
                    });
                },
                error: function(err){
                    console.log(err);
                    btnSave.attr('disabled', false);
                    btnSave.text('Simpan');
                    handleError(err);
                }
            })
        }

        function blockOnChange() {
            let val = $('#graveBlock').val();
            if (val == "") {
                $('#graveNumber').val('');
            }
        }
    </script>
@endpush
{{-- end::script --}}