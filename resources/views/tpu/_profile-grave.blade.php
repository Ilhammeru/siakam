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