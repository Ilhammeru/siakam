@if (!$tpu)
    <div class="col">
        <div class="card card-flush">
            <div class="card-body">
                <div class="row mb-5">
                    <div class="d-flex justify-content-end">
                        <button onclick="editTpu()" type="button" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Tambah</button>
                    </div>
                </div>
                <div class="text-center">
                    <h3 class="mb-5">Anda Belum Mempunya TPU ataupun Makam</h3>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="col-md-4">
        <!--begin::Card-->
        <div class="card mb-5">
            <div class="card-body" id="targetIdentity">
                <div class="row">
                    <div class="d-flex justify-content-between">
                        <h3>Identitas</h3>
                        <button onclick="editTpu({{ $tpu->id }})" type="button" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah</button>
                    </div>
                </div>
                <table class="table table-user">
                    <tbody>
                        <tr>
                            <td>Nama</td>
                            <td><b>{{ $tpu->name }}</b></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td><b>{{ $tpu->address }}</b></td>
                        </tr>
                        <tr>
                            <td>No Telepon</td>
                            <td><b>{{ $tpu->phone }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <div class="col-md-8">
        {{-- begin::card-grave --}}
        <div class="card card-flush">
            <div class="card-body card-grave">
                <div class="row mb-5">
                    <div class="d-flex justify-content-between">
                        <h3>Makam</h3>
                        {{-- begin::button-add --}}
                        <button class="btn btn-light-primary" onclick="addTpu()" id="btnAddTpu" type="button">
                            <i class="fa fa-plus me-3"></i>
                            Tambah
                        </button>
                        {{-- end::button-add --}}
                    </div>
                </div>
                <div class="row grave-row" id="targetGrave">
                    <div class="col">
                        <table class="table table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center" style="width: 150px;">Blok Makam</th>
                                    <th class="text-center" style="width: 150px;">Kuota</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($tpu->graves) == 0)
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <span class="text-info">Belum Ada Data</span>
                                        </td>
                                    </tr>
                                @else
                                    @php
                                        $graves = $tpu->graves;
                                        $a = 1;
                                    @endphp
                                    @foreach ($graves as $grave)
                                        <tr id="editableGrave{{ $grave->id }}">
                                            <td class="text-center">{{ $a }}</td>
                                            <td class="text-center">
                                                <span id="viewGraveBlock{{ $grave->id }}">
                                                    {{ $grave->grave_block }}
                                                </span>
                                                <input type="text" id="editGraveBlock{{ $grave->id }}" class="form-control" hidden value="{{ $grave->grave_block }}">
                                            </td>
                                            <td class="text-center">
                                                <span id="viewGraveQuota{{ $grave->id }}">
                                                    {{ $grave->quota }}
                                                </span>
                                                <input type="text" id="editGraveQuota{{ $grave->id }}" hidden class="form-control" value="{{ $grave->quota }}">
                                            </td>
                                            <td class="text-center">
                                                <div id="actionEditGrave{{ $grave->id }}">
                                                    <span class="text-info me-4" onclick="editGrave({{ $grave->id }}, '{{ $grave->grave_block }}', {{ $grave->quota }})"><i class="fas fa-edit"></i></span>
                                                    <span class="text-info" onclick="deleteGrave({{ $grave->id }})"><i class="fas fa-trash"></i></span>
                                                </div>
                                                <div id="actionSaveGrave{{ $grave->id }}"></div>
                                            </td>
                                        </tr>
                    
                                        @php
                                            $a++;
                                        @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- end::card-grave --}}
    </div>
@endif