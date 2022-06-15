<div class="col-md-4">
    <!--begin::Card-->
    <div class="card mb-5">
        <div class="card-body" id="targetIdentity">
            <div class="row">
                <div class="d-flex justify-content-between">
                    <h3>Identitas</h3>
                    <button onclick="editTpu()" type="button" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah</button>
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
                </div>
            </div>
            <div class="row grave-row" id="targetGrave">
                @foreach ($tpu->graves as $grave)
                    @php
                        if ($grave->is_available == 1) {
                            $color = 'yellow';
                        } else {
                            $color = '#828704';
                        }
                    @endphp
                    <div class="col-md-3 col-xl-3 mb-4">
                        <div class="grave d-flex align-items-center justify-content-center" style="background: {{ $color }}; border-radius: 12px; height: 100px; width: auto;">
                            <div>
                                <p class="text-center mb-0">Blok</p>
                                <h3 class="text-center mb-0">{{ $grave->grave_block }}</h3>
                                @if ($grave->is_available == 0)
                                <p class="text-center">Tidak Tersedia</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-3 col-xl-3 mb-4" onclick="addGrave()">
                    <div class="grave d-flex align-items-center justify-content-center" style="border-radius: 12px; border: 1px solid #A0E4FF; cursor: pointer; height: 100px; width: auto;">
                        <div>
                            <h3 class="text-center mb-0">
                                <i class="fa fa-plus me-4"></i>
                                Tambah
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end::card-grave --}}
</div>