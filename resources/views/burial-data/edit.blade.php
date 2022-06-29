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
    @php
        if ($burialData->latitude == NULL && $burialData->longitude == NULL) {
            $latLong = "";
        } else {
            $latLong = $burialData->latitude . ',' . $burialData->longitude;
        }
    @endphp

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
            <form action="{{ route('burial-data.update', $burialData->id) }}" method="POST" id="formBurialData" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="mb-5 text-center">Data Pemakaman</h3>
                        <div class="form-group row mb-2">
                            <div class="col-md-12">
                                <label for="name" class="col-form-label required">Nama</label>
                                <input type="text" name="name" value="{{ $burialData->name }}" placeholder="Nama Jenazah" class="form-control" id="name">
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col-md-12 col-xl-12">
                                <label for="nik" class="col-form-label required">NIK</label>
                                <input type="number" value="{{ $burialData->nik }}" name="nik" placeholder="NIK Jenazah" class="form-control" id="name">
                            </div>    
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col">
                                <label for="CorpseGender" class="col-form-label">Jenik Kelamin</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" {{ $burialData->gender == 'L' ? 'checked' : '' }} type="radio" name="corpse_gender" id="corpseGenerL" value="L">
                                            <label class="form-check-label" for="corpseGenerL">
                                              Laki - laki
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" {{ $burialData->gender == 'P' ? 'checked' : '' }} type="radio" name="corpse_gender" id="corpseGenerP" value="P">
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
                                        <option {{ $burialData->religion == $item['name'] ? 'selected' : '' }} value="{{ $item['name'] }}">{{ $item['name'] }}</option>
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
                                        <option {{ $burialData->regency_of_birth == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="dateOfBirth" class="col-form-label required">Tanggal Lahir</label>
                                <input type="date" value="{{ date('Y-m-d', strtotime($burialData->birth_date)) }}" name="date_of_birth" class="form-control" id="dateOfBirth">
                            </div>
                        </div>
                        
                        <div class="form-group row mb-2">
                            <div class="col-md-6">
                                <label for="dateOfDeath" class="col-form-label">Tanggal Wafat</label>
                                <input type="date" value="{{ date('Y-m-d', strtotime($burialData->date_of_death)) }}" name="date_of_death" class="form-control" id="dateOfDeath">
                            </div>
                            <div class="col-md-6">
                                <label for="buriedDate" class="col-form-label">Tanggal Pemakaman</label>
                                <input type="date" value="{{ date('Y-m-d', strtotime($burialData->buried_date)) }}" name="burial_date" class="form-control" id="buriedDate">
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col-md-12">
                                <label for="address" class="col-form-label required">Alamat</label>
                                <textarea name="address" class="form-control" id="address" cols="3" rows="3">{{ $burialData->address }}</textarea>
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col-md-8">
                                <label for="addressVilage" class="col-form-label required">Kota</label>
                                <select name="village_id" id="addressVilage" class="form-control">
                                    <option value="">- Pilih Kota -</option>
                                    @foreach ($city as $c)
                                        <option {{ $burialData->village_id == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="addressRt" class="col-form-label required">RT</label>
                                <input type="number" value="{{ $burialData->rt }}" name="rt" class="form-control" id="addressRt" placeholder="RT">
                            </div>
                            <div class="col-md-2">
                                <label for="addressRw" class="col-form-label required">RW</label>
                                <input type="number" value="{{ $burialData->rw }}" name="rw" class="form-control" id="addressRw" placeholder="RW">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col">
                                <label for="buriedNote" class="col-form-label">Keterangan</label>
                                <textarea name="notes" class="form-control" id="buriedNote" cols="3" rows="3">{{ $burialData->notes }}</textarea>
                            </div>
                        </div>
    
                        <h3 class="text-center mb-5" style="margin-top: 25px;">Data Ahli Waris</h3>
    
                        <div class="form-group mb-5 row">
                            <div class="col-md-6">
                                <label for="reporterName" class="col-form-label">Nama Ahli Waris</label>
                                <input name="reporter_name" value="{{ $burialData->reporters_name }}" class="form-control" id="reporterName" placeholder="Nama Ahli Waris" type="text" />
                            </div>
                            <div class="col-md-6">
                                <label for="reporterNik" class="col-form-label">NIK Ahli Waris</label>
                                <input name="reporter_nik" value="{{ $burialData->reporters_nik }}" class="form-control" id="reporterNik" placeholder="NIK Ahli Waris" type="number" />
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col-md-6">
                                <label for="reporterRelationship" class="col-form-label">Hubungan Ahli Waris</label>
                                <input type="text" value="{{ $burialData->reporters_relationship }}" name="reporter_relationship" class="form-control" id="reporterRelationship" placeholder="Hubungan Ahli Waris">
                            </div>
                            <div class="col-md-6">
                                <label for="reporterPhone" class="col-form-label">No. HP Ahli Waris</label>
                                <input type="number" name="reporter_phone" value="{{ $burialData->reporters_phone }}" class="form-control" id="reporterPhone" placeholder="0875xxxxx">
                            </div>
                        </div>
                        <div class="form-group mb-2 row">
                            <div class="col">
                                <label for="reporterAddress" class="col-form-label">Alamat Ahli Waris</label>
                                <textarea  name="reporter_address" plac id="reporterAddress" class="form-control" cols="3" rows="3">{{ $burialData->reporters_address }}</textarea>
                            </div>
                        </div>
    
                        <h3 class="text-center mb-5" style="margin-top: 25px;">Data Pemakaman</h3>
    
                        <div class="form-group row mb-2">
                            <div class="col-md-12">
                                <label for="burialId" class="col-form-label">No Pemakaman</label>
                                <input type="text" value="{{ $burialData->burial_data_id == "" ? $number : $burialData->burial_data_id }}" name="burial_data_id" placeholder="{{ $number == "" ? 'Akan otomatis terisi bila TPU sudah terisi' : '' }}" readonly class="form-control" id="burialId">
                            </div>
                        </div>
                        <div class="form-group mb-5 row">
                            @if (Auth::user()->role != 'tpu')
                            <div class="{{ Auth::user()->role == 'tpu' ? 'col-md-6' : 'col-md-4' }}">
                                <label for="tpuId" class="col-form-label">TPU</label>
                                <select name="tpu_id" id="tpuId" class="form-control" onchange="getGrave()">
                                    <option value="">- Pilih TPU -</option>
                                    @foreach ($tpus as $tpu)
                                        <option {{ $burialData->tpu_id == $tpu->id ? 'selected' : '' }} value="{{ $tpu->id }}">{{ $tpu->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="{{ Auth::user()->role == 'tpu' ? 'col-md-6' : 'col-md-4' }}">
                                <label for="graveBlock" class="col-form-label">Blok Makam</label>
                                <select name="grave_block" id="graveBlock" class="form-control" onchange="blockOnChange()">
                                    <option value="">- Pilih Blok -</option>
                                    @foreach ($graveBlocks as $block)
                                        <option {{ $burialData->grave_block == $block->id ? 'selected' : '' }} value="{{ $block->id }}">{{ $block->grave_block }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="{{ Auth::user()->role == 'tpu' ? 'col-md-6' : 'col-md-4' }}">
                                <label for="graveNumber" class="col-form-label">Nomor Makam</label>
                                <input type="number" value="{{ $burialData->grave_number }}" name="grave_number" placeholder="Nomor Makam" class="form-control" id="graveNumber">
                            </div>
                            <div class="form-group mb-5 row">
                                <div class="col-md-12">
                                    <label for="burialType" class="col-form-label">Jenis Pemakaman</label>
                                    <select name="burial_type_id" id="burialType" class="form-control">
                                        <option value="">- Pilih Jenis -</option>
                                        @foreach ($burialTypes as $type)
                                            <option {{ $burialData->burial_type_id == $type->id ? 'selected' : '' }} value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-5 row">
                                <div class="col-md-12">
                                    <label for="longLatGrave" class="col-form-label">Lokasi</label>
                                    <div class="input-group mb-3">
                                        <input type="text" value="{{ $latLong }}" class="form-control" name="lat_long" id="longLatGrave" placeholder="Lokasi Makam" aria-label="Lokasi Makam" aria-describedby="basic-addon2">
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
                        </div>
                    </div>
                    <div class="col-md-6">
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
            FilePondPluginFileValidateSize
        );

        // Select the file input and use 
        // create() to turn it into a pond
        const gravePhoto = FilePond.create(
            document.getElementById('gravePhoto'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB",
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        let fileGrave = "{{ $burialData->grave_photo }}";
        if (fileGrave != "") {
            gravePhoto.addFile("{{ asset($burialData->grave_photo) }}")
        }
        const applicationLetter = FilePond.create(
            document.getElementById('applicationLetter'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB",
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        let fileApp = "{{ $burialData->application_letter_photo }}";
        if (fileApp != "") {
            applicationLetter.addFile("{{ asset($burialData->application_letter_photo) }}");
        }
        const ktpCorpse = FilePond.create(
            document.getElementById('ktpCorpse'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB",
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        let fileKtpCorpse = "{{ $burialData->ktp_corpse_photo }}";
        if (fileKtpCorpse != "") {
            ktpCorpse.addFile("{{ asset($burialData->ktp_corpse_photo) }}")
        }
        const coverLetter = FilePond.create(
            document.getElementById('coverLetter'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB",
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        let fileCover = "{{ $burialData->cover_letter_photo }}";
        if (fileCover != "") {
            coverLetter.addFile("{{ asset($burialData->cover_letter_photo) }}")
        }
        const reporterKtpPhoto = FilePond.create(
            document.getElementById('reporterKtpPhoto'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB",
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        let fileRepKtp = "{{ $burialData->reporter_ktp_photo }}";
        if (fileRepKtp != "") {
            reporterKtpPhoto.addFile("{{ asset($burialData->reporter_ktp_photo) }}")
        }
        const reporterKkPhoto = FilePond.create(
            document.getElementById('reporterKkPhoto'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB",
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        let fileRepKk = "{{ $burialData->reporter_kk_photo }}";
        if (fileRepKk != "") {
            reporterKkPhoto.addFile("{{ asset($burialData->reporter_kk_photo) }}")
        }
        const hospitalStatement = FilePond.create(
            document.getElementById('hospitalStatement'),{
                allowFileSizeValidation: true,
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB",
                labelMaxFileSizeExceeded: 'File Terlalu Besar',
                labelMaxFileSize: "Ukuran Maximal 5MB"
            }
        );
        let fileHos = "{{ $burialData->letter_of_hospital_statement_photo }}";
        if (fileHos != "") {
            hospitalStatement.addFile("{{ asset($burialData->letter_of_hospital_statement_photo) }}")
        }

        const listId = [
            'gravePhoto', 'applicationLetter', 'ktpCorpse', 'coverLetter',
            'reporterKtpPhoto', 'reporterKkPhoto', 'hospitalStatement'
        ];
        const files = [
            "{{ asset($burialData->grave_photo) }}", "{{ asset($burialData->application_letter_photo) }}",
            "{{ asset($burialData->ktp_corpse_photo) }}", "{{ asset($burialData->cover_letter_photo) }}",
            "{{ asset($burialData->reporter_ktp_photo) }}", "{{ asset($burialData->reporter_kk_photo) }}",
            "{{ asset($burialData->letter_of_hospital_statement_photo) }}"
        ];
        const assets = [
            "{{ $burialData->grave_photo }}", "{{ $burialData->application_letter_photo }}",
            "{{ $burialData->ktp_corpse_photo }}", "{{ $burialData->cover_letter_photo }}",
            "{{ $burialData->reporter_ktp_photo }}", "{{ $burialData->reporter_kk_photo }}",
            "{{ $burialData->letter_of_hospital_statement_photo }}"
        ];
        
        for (let x = 0; x < listId.length; x++) {
            if (assets[x] != "") {
                let check = document.getElementById(listId[x]);
                check.addEventListener('FilePond:removefile', (e) => {
                    let ids = e.path[0].attributes[1].nodeValue;
                    let typePond;
                    let eachPond;
                    if (ids == 'ktpCorpse') {
                        typePond = 'ktp_corpse_photo';
                        // eachPond = pondKtpCorpse;
                    } else if (ids == 'applicationLetter') {
                        typePond = 'application_letter_photo';
                    } else if (ids == 'coverLetter') {
                        typePond = 'cover_letter_photo';
                    } else if (ids == 'reporterKtpPhoto') {
                        typePond = 'reporter_ktp_photo';
                    } else if (ids == 'reporterKkPhoto') {
                        typePond = 'reporter_kk_photo';
                    } else if (ids == 'hospitalStatement') {
                        typePond = 'letter_of_hospital_statement_photo';
                    } else if (ids == 'gravePhoto') {
                        typePond = "grave_photo";
                    } else {
                        typePond = "";
                    }
        
                    Swal.fire({
                        title: 'Apakah anda yakin ingin menghapus foto ini?',
                        showDenyButton: true,
                        showCancelButton: false,
                        confirmButtonText: 'Ya! Hapus',
                        denyButtonText: `Batalkan`,
                    }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            let idBurial = "{{ $burialData->id }}";
                            $.ajax({
                                type: "DELETE",
                                url: "{{ url('/burial-data/delete-photo') }}" + "/" + idBurial + "/" + typePond,
                                success: function(res) {
                                    iziToast['success']({
                                        message: 'Data Pemakaman berhasil di hapus',
                                        position: "topRight"
                                    });
                                    window.location.href = "{{ url('/burial-data/') }}" + "/" + idBurial + '/edit';
                                },
                                error: function(err) {
                                    handleError(err);
                                }
                            })
                        } else {
                            eval(listId[x]).addFile(files[x]);
                        }
                    })
                });
            }
        }

        $('#regencyOfBirth').select2();
        $('#burialType').select2();
        $('#graveBlock').select2();
        $('#addressVilage').select2();
        $('#tpuId').select2();
        $('#corpseReligion').select2();

        // condition block grave
        let selectedTpu = "{{ $burialData->tpu_id }}";
        let role = "{{ Auth::user()->role }}";
        if (role != 'tpu') {
            if (selectedTpu != "") {
                getGrave();
            }
        }

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
                    
                    let option = "<option value=''>- Pilih Blok -</option>";
                    let data = res.data.tpu.graves;
                    let selected;
                    for (let a = 0; a < data.length; a++) {
                        if (selectedTpu != "") {
                            let selectedBlock = "{{ $burialData->grave_block }}";
                            if (selectedBlock == data[a].id) {
                                selected = 'selected';
                            } else {
                                selected = '';
                            }
                        }
                        option += `<option ${selected} value="${data[a].id}">${data[a].grave_block}</option>`;
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
            let graveFile = gravePhoto.getFile();
            if (graveFile) {
                graveFile = graveFile.file;
                data.append('photo[0][grave_photo]', graveFile);
            }
            // application letter file
            let applicationFile = applicationLetter.getFile();
            if (applicationFile) {
                applicationFile = applicationFile.file;
                data.append('photo[1][application_letter_photo]', applicationFile);
            }
            // ktp corpse file
            let ktpCorpseFile = ktpCorpse.getFile();
            if (ktpCorpseFile) {
                ktpCorpseFile = ktpCorpseFile.file;
                data.append('photo[2][ktp_corpse_photo]', ktpCorpseFile);
            }
            // cover letter file
            let converLetterFile = coverLetter.getFile();
            if (converLetterFile) {
                converLetterFile = converLetterFile.file;
                data.append('photo[3][cover_letter_photo]', converLetterFile);
            }
            // reporter KTP file
            let reporterKtpFile = reporterKtpPhoto.getFile();
            if (reporterKtpFile) {
                reporterKtpFile = reporterKtpFile.file;
                data.append('photo[4][reporter_ktp_photo]', reporterKtpFile);
            }
            // reporter KK file
            let reporterKkFile = reporterKkPhoto.getFile();
            if (reporterKkFile) {
                reporterKkFile = reporterKkFile.file;
                data.append('photo[5][reporter_kk_photo]', reporterKkFile);
            }
            // letter of hospital statement file
            let letterOfHospitalFile = hospitalStatement.getFile();
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
                    let dataId = "{{ $burialData->id }}"
                    window.location.href = "{{ url('/burial-data/') }}" + "/" + dataId + '/edit';
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