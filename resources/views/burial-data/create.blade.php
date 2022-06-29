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
            <form action="{{ route('burial-data.store') }}" id="formBurialData" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="text-center mb-5">Data Pemakaman</h3>
                        <div class="form-group row mb-2">
                            <div class="col-md-12">
                                <label for="name" class="col-form-label required">Nama</label>
                                <input type="text" name="name" placeholder="Nama Jenazah" class="form-control" id="name">
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col-md-12 col-xl-12">
                                <label for="nik" class="col-form-label required">NIK</label>
                                <input type="number" name="nik" placeholder="NIK Jenazah" class="form-control" id="name">
                            </div>    
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col">
                                <label for="CorpseGender" class="col-form-label">Jenik Kelamin</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="corpse_gender" id="corpseGenerL" value="L">
                                            <label class="form-check-label" for="corpseGenerL">
                                              Laki - laki
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="corpse_gender" id="corpseGenerP" value="L">
                                            <label class="form-check-label" for="corpseGenerP">
                                              Perempuan
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col">
                                <label for="corpseReligion" class="col-form-label">Agama</label>
                                <select name="corpse_religion" id="corpseReligion" class="form-select form-control">
                                    <option value="">- Pilih Agama -</option>
                                    @foreach ($religion as $item)
                                        <option value="{{ $item['name'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
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
                        <div class="form-group row mb-2">
                            <div class="col-md-6">
                                <label for="dateOfDeath" class="col-form-label">Tanggal Wafat</label>
                                <input type="date" name="date_of_death" class="form-control" id="dateOfDeath">
                            </div>
                            <div class="col-md-6">
                                <label for="buriedDate" class="col-form-label">Tanggal Pemakaman</label>
                                <input type="date" name="burial_date" class="form-control" id="buriedDate">
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col-md-12">
                                <label for="address" class="col-form-label required">Alamat</label>
                                <textarea name="address" class="form-control" id="address" cols="1" rows="1"></textarea>
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col-md-8">
                                <label for="addressVilage" class="col-form-label required">Kota</label>
                                <select name="village_id" id="addressVilage" class="form-control">
                                    <option value="">- Pilih Kota -</option>
                                    @foreach ($city as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="addressRt" class="col-form-label required">RT</label>
                                <input type="number" name="rt" class="form-control" id="addressRt" placeholder="RT">
                            </div>
                            <div class="col-md-2">
                                <label for="addressRw" class="col-form-label required">RW</label>
                                <input type="number" name="rw" class="form-control" id="addressRw" placeholder="RW">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col">
                                <label for="buriedNote" class="col-form-label">Keterangan</label>
                                <textarea name="buried_note" class="form-control" id="buriedNote" cols="3" rows="3"></textarea>
                            </div>
                        </div>

                        <h3 class="text-center mb-5" style="margin-top: 25px;">Data Ahli Waris</h3>

                        <div class="form-group mb-5 row">
                            <div class="col-md-6">
                                <label for="reporterName" class="col-form-label">Nama Ahli Waris</label>
                                <input name="reporter_name" class="form-control" id="reporterName" placeholder="Nama Ahli Waris" type="text" />
                            </div>
                            <div class="col-md-6">
                                <label for="reporterNik" class="col-form-label">NIK Ahli Waris</label>
                                <input name="reporter_nik" class="form-control" id="reporterNik" placeholder="NIK Ahli Waris" type="number" />
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col-md-6">
                                <label for="reporterRelationship" class="col-form-label">Hubungan Ahli Waris</label>
                                <input type="text" name="reporter_relationship" class="form-control" id="reporterRelationship" placeholder="Hubungan Ahli Waris">
                            </div>
                            <div class="col-md-6">
                                <label for="reporterPhone" class="col-form-label">No. HP Ahli Waris</label>
                                <input type="number" name="reporter_phone" class="form-control" id="reporterPhone" placeholder="0875xxxxx">
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col">
                                <label for="reporterAddress" class="col-form-label">Alamat Ahli Waris</label>
                                <textarea  name="reporter_address" plac id="reporterAddress" class="form-control" cols="3" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <h3 class="text-center mb-5" style="margin-top: 25px;">Data Pemakaman</h3>

                        <div class="form-group row mb-2">
                            <div class="col-md-12">
                            <label for="burialId" class="col-form-label {{ Auth::user()->role == 'admin' ? 'required' : (Auth::user()->role == 'superadmin' ? 'required' : '') }}">No Pemakaman</label>
                                <input type="text" name="burial_data_id" placeholder="{{ $number == "" ? 'Akan otomatis terisi bila TPU sudah terisi' : '' }}" readonly class="form-control" id="burialId" value="{{ $number }}">
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
                            <div class="col-md-12">
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
                            <div class="col-md-12">
                                <label for="longLatGrave" class="col-form-label">Lokasi</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="lat_long" id="longLatGrave" placeholder="Lokasi Makam" aria-label="Lokasi Makam" aria-describedby="basic-addon2">
                                    <span class="input-group-text" id="basic-addon2" style="cursor: pointer;" onclick="getLocation()">Dapatkan Lokasi</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col-md-12">
                                <label for="gravePhoto" class="col-form-label">Foto Makam</label>
                                <input type="file" name="grave_photo" class="form-control" id="gravePhoto">
                            </div>    
                        </div>
                        {{-- <div class="form-group mb-5 row">
                            <div class="col">
                                <label for="notes" class="col-form-label">Keterangan</label>
                                <textarea name="notes" id="notes" cols="3" rows="3" class="form-control"></textarea>
                            </div>
                        </div> --}}
                    </div>

                    <div class="col-md-6">
                        {{-- begin::data-requirement --}}
                        <h3 class="text-center mb-5" style="margin-top: 25px;">Data Pemakaman</h3>
                        <div class="form-group mb-2 row">
                            <div class="col-md-12">
                                <label for="applicationLetter" class="col-form-label">Surat Permohonan</label>
                                <input type="file" name="application_letter" class="form-control" id="applicationLetter">
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col-md-12">
                                <label for="ktpCorpse" class="col-form-label">KTP Jenazah</label>
                                <input type="file" name="ktp_corpse" class="form-control" id="ktpCorpse">
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col-md-12">
                                <label for="coverLetter" class="col-form-label">Surat Pengantar</label>
                                <input type="file" name="cover_letter" class="form-control" id="coverLetter">
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col-md-12">
                                <label for="reporterKtpPhoto" class="col-form-label">Foto KTP Pelapor</label>
                                <input type="file" name="rerporter_ktp" class="form-control" id="reporterKtpPhoto">
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col-md-12">
                                <label for="reporterKkPhoto" class="col-form-label">Foto KK Pelapor</label>
                                <input type="file" name="rerporter_kk" class="form-control" id="reporterKkPhoto">
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col-md-12">
                                <label for="hospitalStatement" class="col-form-label">Foto Surat Keterangan Medis</label>
                                <input type="file" name="letter_of_hospital_statement" class="form-control" id="hospitalStatement">
                            </div>
                        </div>
                        {{-- end::data-requirement --}}
                        <div class="form-group mb-5 row">
                            <div class="col">
                                <div class="text-end">
                                    <button class="btn btn-primary" type="button" id="btnSave" onclick="save()">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            // FilePondPluginImageExifOrientation,
            // FilePondPluginImageEdit
        );

        // Select the file input and use 
        // create() to turn it into a pond
        const pondGrave = FilePond.create(
            document.getElementById('gravePhoto'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB",
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        const pondApplicationLetter = FilePond.create(
            document.getElementById('applicationLetter'), {
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        const pondKtpCorpse = FilePond.create(
            document.getElementById('ktpCorpse'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        const pondCoverLetter = FilePond.create(
            document.getElementById('coverLetter'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        const pondReporterKtp = FilePond.create(
            document.getElementById('reporterKtpPhoto'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        const pondReporterKk = FilePond.create(
            document.getElementById('reporterKkPhoto'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        const pondHospitalStatement = FilePond.create(
            document.getElementById('hospitalStatement'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );

        $('#regencyOfBirth').select2();
        $('#burialType').select2();
        $('#graveBlock').select2();
        $('#addressVilage').select2();
        $('#corpseReligion').select2();
        $('#tpuId').select2();

        // begin::variable
        let form = $('#formBurialData');
        let btnSave = $('#btnSave');
        // end::variable

        function getLocation() {
            let currentVal = $('#longLatGrave').val();
            if (currentVal == "") {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else { 
                    x.innerHTML = "Geolocation is not supported by this browser.";
                }
            } else {
                $('#longLatGrave').val('');
                getLocation();
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
                    let block = res.data.graveBlock;
                    let option = "<option value=''>- Pilih Blok -</option>";
                    let data = res.data.tpu.graves;
                    let registrationNumber = res.data.number;
                    for (let a = 0; a < data.length; a++) {
                        let disabled, text;
                        if (block[a] == data[a].quota) {
                            disabled = 'disabled';
                            text = data[a].grave_block + " (Penuh)";
                        }  else {
                            disabled = "";
                            text = data[a].grave_block;
                        }
                        option += `<option ${disabled} value="${data[a].id}">${text}</option>`;
                    }

                    if (registrationNumber != "") {
                        $('#burialId').val(registrationNumber);
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
                    btnSave.attr('disabled', false);
                    btnSave.text('Simpan');
                    window.location.href = "{{ route('burial-data.create') }}";
                    iziToast['success']({
                        message: 'Berhasil Menyimpan Data',
                        position: "topRight"
                    });
                },
                error: function(err){
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